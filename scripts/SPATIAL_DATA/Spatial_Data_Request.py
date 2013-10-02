import json
import sys
import smtplib
from email.mime.text import MIMEText
import datetime
import dateutil.relativedelta as relativedelta
import grads
import numpy as np
import netCDF4 as netcdf
import random
import os
import json

def Write_Arc_Ascii(dims,file,data):

 #Output data in arc ascii format
 ncols = dims["nlon"]
 nrows = dims["nlat"]
 xllcorner = dims["minlon"] - dims["res"]/2
 yllcorner = dims["minlat"] - dims["res"]/2
 cellsize = dims["res"]
 undef = -9.99e+08
 header = 'ncols %d\nnrows %d\nxllcorner %f\nyllcorner %f\ncellsize %f\n NODATA_value %.3f' % (ncols,nrows,xllcorner,yllcorner,cellsize,undef)
 comments=''
 data = np.flipud(data)
 np.savetxt(file,data,fmt='%.3f',header=header,comments=comments)
 return

def Create_NETCDF_File(dims,file,vars,vars_info,tinitial,tstep,nt):

 nlat = dims['nlat']
 nlon = dims['nlon']
 res = dims['res']
 minlon = dims['minlon']
 minlat = dims['minlat']
 t = np.arange(0,nt)

 #Prepare the netcdf file
 #Create file
 f = netcdf.Dataset(file, 'w')

 #Define dimensions
 f.createDimension('lon',nlon)
 f.createDimension('lat',nlat)
 f.createDimension('t',len(t))

 #Longitude
 f.createVariable('lon','d',('lon',))
 f.variables['lon'][:] = np.linspace(minlon,minlon+res*(nlon-1),nlon)
 f.variables['lon'].units = 'degrees_east'
 f.variables['lon'].long_name = 'Longitude'
 f.variables['lon'].res = res

 #Latitude
 f.createVariable('lat','d',('lat',))
 f.variables['lat'][:] = np.linspace(minlat,minlat+res*(nlat-1),nlat)
 f.variables['lat'].units = 'degrees_north'

 #Time
 times = f.createVariable('t','d',('t',))
 f.variables['t'][:] = t
 f.variables['t'].units = '%s since %04d-%02d-%02d %02d:00:00.0' % (tstep,tinitial.year,tinitial.month,tinitial.day,tinitial.hour)
 f.variables['t'].long_name = 'Time'

 #Data
 i = 0
 for var in vars:
  f.createVariable(var,'f',('t','lat','lon'),fill_value=-9.99e+08)
  f.variables[var].long_name = vars_info[i]
  i = i + 1

 return f

def Grads_Regrid(var_in,var_out,dims):

 ga("%s = re(%s,%d,linear,%f,%f,%d,linear,%f,%f)" % (var_out,var_in,dims['nlon'],dims['minlon'],dims['res'],dims['nlat'],dims['minlat'],dims['res']))

 return

def datetime2gradstime(date):

 #Convert datetime to grads time
 str = date.strftime('%HZ%d%b%Y')

 return str

def gradstime2datetime(str):

 #Convert grads time to datetime
 date = datetime.datetime.strptime(str,'%HZ%d%b%Y')

 return date

def Send_Email(txt):

  with open('creds.json') as creds_file:    
    creds = json.load(creds_file)
    receiver = email
    msg = MIMEText(txt)
    msg['Subject'] = 'African Water Monitor Data Request'
    msg['From'] = creds["username"] + "@gmail.com"
    msg['To'] = email
    s = smtplib.SMTP('smtp.gmail.com:587')
    s.starttls()  
    s.login(creds["username"], creds["password"])  
    s.sendmail(sender,receiver,msg.as_string())
    s.quit()

 return

metadata = json.loads(raw_input())
tstep = metadata['tstep']
llclat = float(metadata['llclat'])
llclon = float(metadata['llclon'])
urclat = float(metadata['urclat'])
urclon = float(metadata['urclon'])
idate = datetime.datetime.utcfromtimestamp(int(metadata['idate']))
fdate = datetime.datetime.utcfromtimestamp(int(metadata['fdate']))
format= metadata['format']
email = metadata['email']
variables = metadata['variables']
res = float(metadata['sres'])
http = metadata['http'].split('/')
http_root = '/'.join(http[0:-2])
user = email.split('@')[0]

#Change directory
os.chdir('../..')

if tstep == "daily":
 dt = relativedelta.relativedelta(days=1)
if tstep == "monthly":
 dt = relativedelta.relativedelta(months=1)
if tstep == "yearly":
 dt = relativedelta.relativedelta(years=1)

#Define the monitor's boundaries
minlat = -35.0
minlon = -19.0
maxlat = 38.0
maxlon = 55.0

#Not allow for more than 1 gb request
nlat = (urclat - llclat)/res
nlon = (urclon - llclon)/res
nvars = len(variables)

#Define dimensions
dims = {}
dims['minlat'] = llclat#minlat #-89.8750
dims['minlon'] = llclon#minlon #0.1250
dims['maxlat'] = urclat#maxlat
dims['maxlon'] = urclon#maxlon
dims['res'] = res
dims['nlat'] = np.int(np.ceil((dims['maxlat'] - dims['minlat'])/ dims['res'] + 1))
dims['nlon'] = np.int(np.ceil((dims['maxlon'] - dims['minlon'])/ dims['res'] + 1))
dims['maxlat'] = dims['minlat'] + dims['res']*(dims['nlat']-1)
dims['maxlon'] = dims['minlon'] + dims['res']*(dims['nlon']-1)

#Remove old files
os.system("find WORKSPACE/* -mmin +400 -exec rm -rf {} \;")

#Run some initial checks 
dir = "WORKSPACE/%s" % user
os.system("mkdir %s" % dir)

#Open Grads
grads_exe = 'LIBRARIES/grads-2.0.1.oga.1/Contents/grads'
ga = grads.GrADS(Bin=grads_exe,Window=False,Echo=False)

for var in variables:

 #Create directory for variable
 var_dir = dir + "/" + var
 os.system("mkdir %s" % var_dir)
 dataset = var.split("--")[0]
 ctl_file = "DATA_GRID/CTL/%s_%s.ctl" % (dataset,tstep.upper())
 ga("xdfopen %s" % ctl_file)
 var = var.split("--")[1]
 qh = ga.query("file")
 var_info = qh.var_titles[qh.vars.index(var)]

 #Make sure we are within the ti1")
 idate_var = idate
 fdate_var = fdate
 ga("set t 1 last")
 idate_dataset = gradstime2datetime(ga.query('dims').time[0])
 fdate_dataset = gradstime2datetime(ga.query('dims').time[1])
 if idate_var < idate_dataset:
  idate_var = idate_dataset
 if fdate_var > fdate_dataset:
  fdate_var = fdate_dataset
 
 #Change month and year
 if tstep == "monthly":
  idate_var = datetime.datetime(idate_var.year,idate_var.month,1)
  fdate_var = datetime.datetime(fdate_var.year,fdate_var.month,1)
 if tstep == "yearly":
  idate_var = datetime.datetime(idate_var.year,1,1)
  fdate_var = datetime.datetime(fdate_var.year,1,1)

 #Set grads region
 ga("set lat %f %f" % (dims['minlat'],dims['maxlat']))
 ga("set lon %f %f" % (dims['minlon'],dims['maxlon']))

 date = idate_var
 while date <= fdate_var:
  time = datetime2gradstime(date)  
  #Set time
  ga("set time %s" % time)
  #Regrid data
  Grads_Regrid(var,'data',dims)
  #Write data
  if format == "netcdf":
   if tstep == "daily":
    file = var_dir + "/%s_%s_%04d%02d%02d_daily.nc" % (var,dataset,date.year,date.month,date.day)
   elif tstep == "monthly":
    file = var_dir + "/%s_%s_%04d%02d_monthly.nc" % (var,dataset,date.year,date.month)
   elif tstep == "yearly":
    file = var_dir + "/%s_%s_%04d_yearly.nc" % (var,dataset,date.year)
   fp = Create_NETCDF_File(dims,file,[var,],[var_info,],date,'days',1)
   fp.variables[var][0] = np.ma.getdata(ga.exp("data"))
   fp.close()
  elif format == "arc_ascii":
   if tstep == "daily":
    file = var_dir + "/%s_%s_%04d%02d%02d_daily.asc" % (var,dataset,date.year,date.month,date.day)
   elif tstep == "monthly":
    file = var_dir + "/%s_%s_%04d%02d_monthly.asc" % (var,dataset,date.year,date.month)
   elif tstep == "yearly":
    file = var_dir + "/%s_%s_%04d_yearly.asc" % (var,dataset,date.year)
   Write_Arc_Ascii(dims,file,np.ma.getdata(ga.exp("data")))
  #Move to next time step
  date = date + dt
 ga("close 1")

#Zip up the directory
file = "WORKSPACE/%s.tar.gz" % user
if os.path.exists(file) == True:
 os.system("rm %s" % file)
http_file = http_root + '/' + file
os.chdir('WORKSPACE')
os.system("tar -czf %s.tar.gz %s" % (user,user) )
os.system("rm -rf %s" % user)

#Send the email confirming that it succeeded and the location of the zipped archive
Send_Email("The data was processed and can be dowloaded at %s. The data will be removed in 6 hours." % http_file)
