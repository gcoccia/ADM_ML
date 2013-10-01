import json
import numpy as np
import netCDF4 as netcdf
import datetime

def Create_Text_File(data,tstep,idate,fdate,data_group,lat,lon,http_root,undef):

 import os 
 import dateutil.relativedelta as relativedelta

 #Change directory
 os.chdir('../..')

 #Remove old files
 os.system("find WORKSPACE/* -mmin +400 -exec rm -rf {} \;")

 #Run some initial checks 
 dt = datetime.timedelta(days=1)
 if tstep == 'DAILY':
  fmt = '%Y%m%d'
  header = ['year,month,day',]
  dt = relativedelta.relativedelta(days=1)
 elif tstep == 'MONTHLY':
  fmt = '%Y%m'
  header = ['year,month',]
  dt = relativedelta.relativedelta(months=1)
 elif tstep == 'YEARLY':
  fmt = '%Y'
  header = ['year',]
  dt = relativedelta.relativedelta(years=1)
 itime = idate.strftime(fmt)
 ftime = fdate.strftime(fmt)
 file = "WORKSPACE/%s_%s_%s_%.3f_%.3f.txt" % (data_group,itime,ftime,lat,lon)
 http_file = http_root + '/%s' % file

 #Open file
 fp = open(file,'w')

 #Write header information
 for var in data['VARIABLES']:
  header.append('%s' % data['VARIABLES'][var]['name'])
 header = (',').join(header)
 fp.write('%s\n' % header)
 
 #Write data
 date = idate
 count = 0
 while date < fdate:
  if tstep == 'DAILY':
   str = ['%d,%d,%d' % (date.year,date.month,date.day)]
  elif tstep == 'MONTHLY':
   str = ['%d,%d' % (date.year,date.month)]
  elif tstep == 'YEARLY':
   str = ['%d' % (date.year)]
  for var in data['VARIABLES']:
   if np.isnan(data['VARIABLES'][var]['data'][count]) == 0:
    tmp = data['VARIABLES'][var]['data'][count]
   else:
    tmp = -999.0
   str.append('%.3f' % tmp)
  str = (',').join(str)
  fp.write('%s\n' % str)
  date = date + dt
  count = count + 1
 #Close file
 fp.close()

 return http_file

#Parse the JSON string
metadata = json.loads(raw_input())
idate = int(metadata["idate"])
fdate = int(metadata["fdate"])
lat = float(metadata["lat"])
lon = float(metadata["lon"])
tstep = metadata["tstep"]
info = metadata["variables"]
idate_datetime = datetime.datetime.utcfromtimestamp(idate)
fdate_datetime = datetime.datetime.utcfromtimestamp(fdate)
create_text_file = metadata["create_text_file"]
data_group = metadata["data_group"]
http = metadata['http'].split('/')
http_root = '/'.join(http[0:-2])

undef = -9.99e+08
#Find closet grid cell
minlat = -34.875
minlon = -18.875
res = 0.25
ilat = np.rint((lat - minlat)/res + 1)
lat = minlat + res*(ilat-1)
ilon = np.rint((lon - minlon)/res + 1)
lon = minlon + res*(ilon-1)

#Define the time step for highcharts and determine number of time steps
if tstep == "DAILY":
 pointInterval = 24*3600*1000
 nt = (fdate_datetime - idate_datetime).days + 1
 dt = 24*3600
 dt1 = 1
elif tstep == "MONTHLY":
 pointInterval = 30.4375*24*3600*1000
 nt = 12*(fdate_datetime.year - idate_datetime.year) + fdate_datetime.month - idate_datetime.month + 1
 dt = 24*3600*30.4375
 dt1 = 30.4375
elif tstep == "YEARLY":
 pointInterval = 365.25*24*3600*1000
 nt = fdate_datetime.year - idate_datetime.year + 1
 dt = 365.25*24*3600
 dt1 = 365.25

#Read in the desired data
variables = []
date = {'pointInterval':pointInterval,'iyear':idate_datetime.year,'imonth':idate_datetime.month,'iday':idate_datetime.day}
data_out = {}
data_out["TIME"] = date
data_out["VARIABLES"] = {}
data = []

#Construct decades and open all files
idecade = np.int(10*np.floor(idate_datetime.year/10))
fdecade = np.int(10*np.floor(fdate_datetime.year/10))
decades = np.arange(idecade,fdecade+10,10)
fps = []
for decade in decades:
 file = '../../DATA_CELL/%d/cell_%0.3f_%0.3f.nc' % (decade,lat,lon)
 fps.append(netcdf.Dataset(file,'r',format='NETCDF4'))

#Choose the datasets
data_tmp = np.ones(nt)
for var in info:
 data_tmp[:] = undef
 for dataset in info[var]['datasets']:
   for fp in fps:
    if dataset in fp.groups[tstep].groups.keys():
     date = fp.groups[tstep].groups[dataset].variables["time"][:]
     idx = list(np.where((date >= idate) & (date <= fdate)))[0]
     date_tmp = date[idx]
     ipos = np.int32(np.round(np.float32(date_tmp - idate)/np.float32(dt)))
     data_tmp[ipos] = fp.groups[tstep].groups[dataset].variables[var][idx]
   var_data = data_tmp#fp.groups[tstep].groups[dataset].variables[var][idx]
   var_data[var_data == undef] = float('NaN')
   if var == 'prec':
    var_data = var_data/dt1 #THIS NEEDS TO BE FIXED IN THE FUTURE
   data_out['VARIABLES'][var] = {}
   data_out['VARIABLES'][var]['data'] = list(np.float64(var_data))
   data_out['VARIABLES'][var]['units'] = info[var]['units']
   data_out['VARIABLES'][var]['name'] = info[var]['name']
   data_out['VARIABLES'][var]['dataset'] = dataset

#Close the datasets
for fp in fps:
 fp.close()

#If required, create the ascii text file of data
if create_text_file == 'yes':
 data_out['point_data_link'] = Create_Text_File(data_out,tstep,idate_datetime,fdate_datetime,data_group,lat,lon,http_root,undef)

#Print json
print json.dumps(data_out,allow_nan=True)
