<?php 
require_once('php-gettext-1.0.11/gettext.inc');
#require('scripts/main_layout.inc');
include 'scripts/Read_DM_log.php';
$locale = BP_LANG;
$textdomain="adm";
if (empty($locale))
	$locale = 'en';
if (isset($_GET['locale']) && !empty($_GET['locale']))
	$locale = $_GET['locale'];
putenv('LANGUAGE='.$locale);
putenv('LANG='.$locale);
putenv('LC_ALL='.$locale);
putenv('LC_MESSAGES='.$locale);
T_setlocale(LC_ALL,$locale);
T_setlocale(LC_CTYPE,$locale);

$locales_dir = dirname(__FILE__).'/i18n';
T_bindtextdomain($textdomain,$locales_dir);
T_bind_textdomain_codeset($textdomain, 'UTF-8'); 
T_textdomain($textdomain);
$_= 'T_';

#page_header($locale, "Current Conditions");
#menu();
#$Current_Time= date('d/m/Y',jdtounix(unixtojd() - 2));
$Latest_Year = $year_initial;
$Latest_Month = $month_initial;
$Latest_Day = $day_initial;
$Initial_Year = 1950; 
$Initial_Month = 1;
$Initial_Day = 1;
$Latest_Time = sprintf("%04d",$Latest_Year).sprintf("%02d",$Latest_Month).sprintf("%02d",$Latest_Day);
$Initial_Time = sprintf("%02d",$Initial_Day)."/".sprintf("%02d",$Initial_Month)."/".sprintf("%04d",$Initial_Year);
$Final_Time = sprintf("%02d",$Latest_Day)."/".sprintf("%02d",$Latest_Month)."/".sprintf("%04d",$Latest_Year);
$Month_Name = array("Jan","Feb","Mar","Apr","May","Jun","Jul","Aug","Sep","Oct","Nov","Dec");
$Latest_Timestamp = $Month_Name[$Latest_Month-1]."/".sprintf("%02d",$Latest_Day)."/".sprintf("%04d",$Latest_Year);

$text = <<<EOF
<!DOCTYPE html>
<html style="height:100%">
<head>
<title>African Drought Monitor</title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-style-type" content="text/css" />
<meta content="African Drought Monitor" name="description" />
<meta content="Drought, drought monitor, drought monitoring, streamflow, soil moisture, hydrological forecast,hydrologic forecast, water, resource, management, Nathaniel Chaney, Justin Sheffield, Eric Wood" name="keywords" />
<meta content="Nathaniel Chaney" name="author" />

<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="jsscripts/bootstrap.min.js"></script>
<script type="text/javascript" src="js/mootools.v1.1.js"></script>
<script type="text/javascript" src="js/slimbox.js"></script>
<script type="text/javascript" src="jsscripts/Static_Images.js"></script>
<script type="text/javascript" src="jsscripts/MiscFunctions.js"></script>
<script type="text/javascript" src="jsscripts/MainFunctions.js"></script>

<link rel="Shortcut Icon" href="vic.ico" type="image/x-icon" >
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link href="css/s.css" rel=stylesheet> 
<link rel="stylesheet" type="text/css" media="screen,projection" href="css/Moz.css" title="Moz" />
</head>

<body style="width:100%; height:100%">
  <div class="container-fluid" style="width:100%; height:100%; padding-right:0px; padding-left:0px;">
    <h2>African Drought Monitor
      <img style="float:right" id="UW_logo" src="icons/UW_logo.png">
      <img style="float:right" id="UNESCO_logo" src="icons/Unesco_logo.gif">
      <img style="float:right" id="ICPAC_logo" src="icons/ICPAC_logo.gif">
      <img style="float:right" id="AGRHYMET_logo" src="icons/agrhymet_logo.gif">
      <img style="float:right" id="PU_logo" src="icons/PU_logo.gif">
    </h2>
  <div class="navbar">
    <div class="navbar-inner">
      <div class="container">
        <ul class="nav">
          <li><a href='index.php'>Google Maps Interface</a></li>
          <li class="active"><a href='#'>Basic Interface</a></li>
          <li><a href='Resources/ADM_Background.pdf'>Background</a></li>
          <li><a href='Resources/ADM_Glossary.pdf'>Glossary</a></li>
          <li><a href='Resources/Tutorial_HornAfrica.pdf'>Tutorial</a></li>
        </ul>
        <img id="Flag_Image" style="float:right" src="icons/flags/arabic_flag.gif" onclick=ChangeLanguage("Arabic")>
        <img id="Flag_Image" style="float:right" src="icons/flags/spanish_flag.gif" onclick=ChangeLanguage("Spanish")>
        <img id="Flag_Image" style="float:right" src="icons/flags/chinese_flag.gif" onclick=ChangeLanguage("Chinese")>
        <img id="Flag_Image" style="float:right" src="icons/flags/french_flag.gif" onclick=ChangeLanguage("French")>
        <img id="Flag_Image" style="float:right" src="icons/flags/english_flag.gif" onclick=ChangeLanguage("English")>
      </div>
    </div>
  </div>

  <div class="row-fluid" style="text-align:center">
    <div id='Static_Controls'>
      <form name="TimeForm"> Timestamp (dd/mm/yyyy) :  <button class="btn" type="button" onclick="Update_Static_Images_Step(0)"> < </button>
      <input type="text" name="latest_day" class="input-small" value=$Latest_Day>
      <input type="text" name="latest_month" class="input-small" value=$Latest_Month>
      <input type="text" name="latest_year" class="input-small" value=$Latest_Year>
      <button class="btn" type="button" onclick="Update_Static_Images_Step(1)"> > </button>
      <button class="btn" type="button" onclick="Update_Static_Images()">Update Images</button>  $Initial_Time - $Final_Time
      </form>
    </div>
  </div>
  
  <div class="row-fluid" style="text-align:center">
    <h2 id="Drought_Conditions">{$_("Drought Conditions on")} $Latest_Timestamp</h2>
      <div id="Daily_Images">
        <div class="inline">
	  <img id="SMQALL" src="Data/ADM_Data/Realtime/smqall_basic/smqall_$Latest_Time.png" title="image" onerror="this.src='icons/Basic_Noimage.png'"/>
	  <div class="image_superposition">
	  <h3 class="text_superposition">{$_('Drought Index')} (%)</h3>
	  </div>
      </div>
  <div class="inline">
        <img id="PREC" src="Data/ADM_Data/Realtime/prec_basic/prec_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>
        <div class="image_superposition">
        <h3 class="text_superposition">{$_('Precipitation (mm/day)')}</h3>
        </div>
   </div>
  <div class="inline">
        <img id="EVAP" src="Data/ADM_Data/Realtime/evap_basic/evap_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>
        <div class="image_superposition">    
        <h3 class="text_superposition">{$_('Evaporation (mm/day)')}</h3> 
        </div>
  </div>
  <div class="inline">       
        <img id="SMWET1" src="Data/ADM_Data/Realtime/smwet1_basic/smwet1_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/> 
       <div class="image_superposition">  
       <h3 class="text_superposition">{$_('Soil Moisture(%) - Layer 1')}</h3>        
        </div>
  </div>
  <div class="inline"> 
        <img id="SMWET2" src="Data/ADM_Data/Realtime/smwet2_basic/smwet2_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>  
        <div class="image_superposition">    
        <h3 class="text_superposition">{$_('Soil Moisture(%) - Layer 2')}</h3>        
        </div>
  </div>
  <div class="inline">  
        <img id="RUNOFF" src="Data/ADM_Data/Realtime/runoff_basic/runoff_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/> 
        <div class="image_superposition">   
        <h3 class="text_superposition">{$_('Surface Runoff (mm/day)')}</h3>        
        </div>
  </div>
 <div class="inline">  
        <img id="GAUGES_PERCENTILES" src="Data/ADM_Data/Realtime/gaugepct_basic/gaugepct_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>         <div class="image_superposition">  
        <h3 class="text_superposition">{$_('Stream Gauges')}</h3>  
        </div>
  </div>
  <div class="inline"> 
        <img id="TMAX" src="Data/ADM_Data/Realtime/tmax_basic/tmax_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>  
        <div class="image_superposition"> 
        <h3 class="text_superposition">{$_('Maximum Temperature (C)')}</h3>  
        </div>
  </div>
  <div class="inline">                
	<img id="TMIN" src="Data/ADM_Data/Realtime/tmin_basic/tmin_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>                
	<div class="image_superposition">                        
	<h3 class="text_superposition">{$_('Minimum Temperature (C)')}</h3>  
        </div>
  </div>
  <div class="inline">                
	<img id="WIND" src="Data/ADM_Data/Realtime/wind_basic/wind_$Latest_Time.png" onerror="this.src='icons/Basic_Noimage.png'"/>                
	<div class="image_superposition">                        
	<h3 class="text_superposition">{$_('Wind (m/s)')}</h3>  
        </div>
  </div>
</div>
</div>
</body>
</html>
EOF;

print $text;

?>



