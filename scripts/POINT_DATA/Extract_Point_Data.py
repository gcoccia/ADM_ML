import json
import numpy as np
import netCDF4 as netcdf
import datetime
import dateutil.relativedelta as relativedelta
import os

def Calculate_Percentiles(var,info,dataset,tstep,lat,lon,idate,fdate,undef,maxnt):

 pcts = info['values']
 
 #REMAINING ISSUES: NDVI decades and do not need to redo what has already been calculated...
 if var == 'ndvi30':
  decades = np.array([2000,])
 else:
  decades = np.arange(1950,2000+10,10)

 for decade in decades:
  file = '../../DATA_CELL/%d/cell_%0.3f_%0.3f.nc' % (decade,lat,lon)
  fp = netcdf.Dataset(file,'r',format='NETCDF4')
  if decade == decades[0]:
   dates = fp.groups[tstep].groups[dataset].variables["time"][:]
   data = fp.groups[tstep].groups[dataset].variables[var][:]
  else:
   dates = np.append(dates,fp.groups[tstep].groups[dataset].variables["time"][:])
   data = np.append(data,fp.groups[tstep].groups[dataset].variables[var][:])
  fp.close()

 #Conver dates to an array of year/month/day
 dates_array = []
 for date in dates:
  date_datetime = datetime.datetime.utcfromtimestamp(date)
  tmp = [date_datetime.year,date_datetime.month,date_datetime.day]
  dates_array.append(tmp)
 dates_array = np.array(dates_array)
 #Define the timestep
 if tstep == 'DAILY':
  dt = relativedelta.relativedelta(days=1)
 elif tstep == 'MONTHLY':
  dt = relativedelta.relativedelta(months=1)
 elif tstep == 'YEARLY':
  dt = relativedelta.relativedelta(years=1)

 #Find the percentiles for each datay
 vals = []
 date = fdate
 #dates_done = []
 nt = 0
 while date >= idate:
  if tstep == 'DAILY':
   #date_simple = datetime.datetime(2000,date.month,date.day)
   #if date_simple in dates_done:
   # vals.append(vals[dates_done.index(date_simple)])
   # date = date + dt
   # continue
   for tside in xrange(-5,5):
    tdate = date + tside*dt
    if tside == -5:
     idx = np.where((dates_array[:,1] == tdate.month) & (dates_array[:,2] == tdate.day))[0]
    else:
     idx = np.append(idx,np.where((dates_array[:,1] == tdate.month) & (dates_array[:,2] == tdate.day))[0])
   data_new = data[idx]
   #dates_done.append(date_simple)
  elif tstep == 'MONTHLY':
   idx = np.where(dates_array[:,1] == date.month)[0]
   data_new = data[idx]
  elif tstep == 'YEARLY':
   data_new = data
  #data_done = np.append(data_done,np.percentile(data_new[data_new > 0],pcts))
  #find the desired percentiles
  vals.append(np.percentile(data_new[data_new > 0],pcts))
  date = date - dt
  nt = nt + 1
  if nt > maxnt:
   break

 #Convert to a dictionary for output
 vals = np.array(vals).T
 vals = np.fliplr(vals)
 #Subtract the previous one for stacking purposes
 percentiles = {}
 i = 0
 for pct in pcts:
  tmp = vals[i]
  tmp[tmp == undef] = float('NaN')
  if i >= 1:
   tmp0 = vals[i-1]
   tmp0[tmp0 == undef] = float('NaN')
  else:
   tmp0 = np.zeros(tmp.size)
  tmp = list(np.float64(tmp))
  tmp0 = list(np.float64(tmp0))
  tmp_final = []
  for j in xrange(0,len(tmp)):
   tmp_final.append([tmp0[j],tmp[j]])
  percentiles[pct] = tmp_final#list(np.float64(tmp))
  i = i + 1
  
 return percentiles

def Create_Text_File(data,tstep,idate,fdate,data_group,lat,lon,http_root,undef):

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
  header.append('%s' % data['VARIABLES'][var]['name'].encode('utf-8'))
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
maxnt = 2*365#1000
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
data_out = {}
data_out["VARIABLES"] = {}
data = []

#Construct decades and open all files
idecade = np.int(10*np.floor(idate_datetime.year/10))
fdecade = np.int(10*np.floor(fdate_datetime.year/10))
decades = np.arange(idecade,fdecade+10,10)
fps = []

#Determine if file exists
file = '../../DATA_CELL/%d/cell_%0.3f_%0.3f.nc' % (decades[0],lat,lon)
if os.path.exists(file) == False:
 print json.dumps('out_of_bounds')
 exit()

for decade in decades:
 file = '../../DATA_CELL/%d/cell_%0.3f_%0.3f.nc' % (decade,lat,lon)
 fps.append(netcdf.Dataset(file,'r',format='NETCDF4'))

#Choose the datasets
data_tmp = np.ones(nt)
for var in info:
 data_tmp[:] = undef
 data_out['VARIABLES'][var] = {}
 #Extract normal data or calculate percentiles
 if 'percentiles' in info[var]:
  dataset = info[var]['datasets'][0]
  percentiles = Calculate_Percentiles(var,info[var]['percentiles'],dataset,tstep,lat,lon,idate_datetime,fdate_datetime,undef,maxnt)
  data_out['VARIABLES'][var]['percentiles'] = percentiles
 for dataset in info[var]['datasets']:
  for fp in fps:
   if dataset in fp.groups[tstep].groups.keys():
    date = fp.groups[tstep].groups[dataset].variables["time"][:]
    idx = list(np.where((date >= idate) & (date <= fdate)))[0]
    date_tmp = date[idx]
    ipos = np.int32(np.round(np.float32(date_tmp - idate)/np.float32(dt)))
    data_tmp[ipos] = fp.groups[tstep].groups[dataset].variables[var][idx]
 var_data = data_tmp#fp.groups[tstep].groups[dataset].variables[var][idx]
 #Compute min/max
 tmp = var_data[var_data != undef]
 if tmp.size > 1:
  min = np.min(var_data[var_data != undef])
  max = np.max(var_data[var_data != undef])
  data_out['VARIABLES'][var]['min'] = min
  data_out['VARIABLES'][var]['max'] = max
 #Place the data
 var_data[var_data == undef] = float('NaN')
 if var == 'prec':
  var_data = var_data/dt1 #THIS NEEDS TO BE FIXED IN THE FUTURE 
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

#Reduce the number of chart time steps if requesting too many
if nt > maxnt:
 nt = maxnt
 if tstep == "DAILY":
  idate_datetime = fdate_datetime - (nt-1)*relativedelta.relativedelta(days=1)
 elif tstep == "MONTHLY":
  idate_datetime = fdate_datetime - (nt-1)*relativedelta.relativedelta(months=1)
 elif tstep == "YEARLY":
  idate_datetime = fdate_datetime - (nt-1)*relativedelta.relativedelta(years=1)
 for var in data_out['VARIABLES']:
  data_out['VARIABLES'][var]['data'] = data_out['VARIABLES'][var]['data'][-maxnt:]

#Define time info
date = {'pointInterval':pointInterval,'iyear':idate_datetime.year,'imonth':idate_datetime.month,'iday':idate_datetime.day}
data_out["TIME"] = date

#Print json
print json.dumps(data_out,allow_nan=True)
