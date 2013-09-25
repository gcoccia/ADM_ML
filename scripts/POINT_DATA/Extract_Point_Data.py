import json
import numpy as np
import netCDF4 as netcdf
import datetime

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
elif tstep == "MONTHLY":
 pointInterval = 30.4375*24*3600*1000
 nt = 12*(fdate_datetime.year - idate_datetime.year) + fdate_datetime.month - idate_datetime.month + 1
 dt = 24*3600*30.4375
elif tstep == "YEARLY":
 pointInterval = 365.25*24*3600*1000
 nt = fdate_datetime.year - idate_datetime.year + 1
 dt = 365.25*24*3600

#Read in the desired data
file = '../../DATA_CELL/cell_%0.3f_%0.3f.nc' % (lat,lon)
fp = netcdf.Dataset(file,'r',format='NETCDF4')
variables = []
date = {'pointInterval':pointInterval,'iyear':idate_datetime.year,'imonth':idate_datetime.month,'iday':idate_datetime.day}
data_out = {}
data_out["TIME"] = date
data_out["VARIABLES"] = {}
data = []

#Choose the datasets
data_tmp = np.ones(nt)
date_tmp = np.ones(nt)
for var in info:
 data_tmp[:] = undef
 for dataset in info[var]['datasets']:
  if dataset in fp.groups[tstep].groups.keys():
   date = fp.groups[tstep].groups[dataset].variables["time"][:]
   idx = list(np.where((date >= idate) & (date <= fdate)))[0]
   date_tmp = date[idx]
   ipos = np.int32(np.round(np.float32(date_tmp - idate)/np.float32(dt)))
   data_tmp[ipos] = fp.groups[tstep].groups[dataset].variables[var][idx]
   var_data = data_tmp#fp.groups[tstep].groups[dataset].variables[var][idx]
   var_data[var_data == undef] = float('NaN')
   data_out['VARIABLES'][var] = {}
   data_out['VARIABLES'][var]['data'] = list(np.float64(var_data))
   data_out['VARIABLES'][var]['units'] = 'mm'
   data_out['VARIABLES'][var]['long_name'] = 'long_name'
   data_out['VARIABLES'][var]['dataset'] = dataset

#Print json
print json.dumps(data_out,allow_nan=True)
