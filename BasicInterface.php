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

$text = <<< EOF
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
<title>African Drought Monitor: <?php echo "$subtitle"?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-style-type" content="text/css" />
<meta content="African Drought Monitor" name="description" />
<meta content="Drought, drought monitor, drought monitoring, streamflow, soil moisture, hydrological forecast,hydrologic forecast, water, resource, management, Nathaniel Chaney, Justin Sheffield, Eric Wood" name="keywords" />
<meta content="Nathaniel Chaney" name="author" />

<script type="text/javascript" src="js/mootools.v1.1.js"></script>
<script type="text/javascript" src="js/slimbox.js"></script>
<script type="text/javascript" src="jsscripts/Static_Images.js"></script>
<script type="text/javascript" src="jsscripts/MiscFunctions.js"></script>

<link rel="Shortcut Icon" href="vic.ico" type="image/x-icon" >
<link href="css/s.css" rel=stylesheet> 
<link rel="stylesheet" type="text/css" media="screen,projection" href="css/Moz.css" title="Moz" />
<link rel="stylesheet" type="text/css" media="screen" href="css/slimbox.css" />
<link rel="stylesheet" type="text/css" media="print" href="css/print.css" title="print" />
</head>
<div class='shim'></div>
<div class="top">
                <div class="box">
                           <div><table><tbody><tr>
                                <td align="center"><img id="UNESCO_logo" src="icons/Unesco_logo.gif"></td>
                                <td align="center"><img id="ICPAC_logo" src="icons/ICPAC_logo.gif"></td>
                                <td align="center"><img id="AGRHYMET_logo" src="icons/agrhymet_logo.gif"></td>
                                <td align="center" width="65%"> Experimental African Drought Monitor </td>
                                <td align="center"><img id="PU_logo" src="icons/PU_logo.gif"></td>
                                <td align="center"><img id="UW_logo" src="icons/UW_logo.png"></td>
                                <td align="center"><img id="CB_logo" src="icons/cesbio_logo.png"></td>
                          </tr></tbody></table></div>
                </div>
        </div>
<div class='body'>
	<div class='hbar'>
	<table id="nav"><tr><td>{$_("Basic Interface")}</td><td  class="link" onClick="document.location.href='index.php'+window.location.search">{$_("Google Maps Interface")}</td><td class="link" onClick="document.location.href='Resources/ADM_Background.pdf'">{$_("Background")}</td><td class="link" onClick="document.location.href='Resources/ADM_Glossary.pdf'">{$_("Glossary")}</td><td class="link" onClick="document.location.href='Resources/Tutorial_HornAfrica.pdf'">{$_("Tutorial")}</td></tr></table>
</div>
<div class='main'>
<div class='Static_Controls'>
<form name="TimeForm">
	{$_("Timestamp")} ({$_("dd/mm/yyyy")}) :  <input type="button" value="<" onclick="Update_Static_Images_Step(0)"><input type="text" size=1 name="latest_day" value=$Latest_Day><input type="text" size=1 name="latest_month" value=$Latest_Month><input type="text" size=3 name="latest_year" value=$Latest_Year><input type="button" value=">" onclick="Update_Static_Images_Step(1)">
        <input type="button" value="{$_('Update Images')}" onclick="Update_Static_Images()">
        $Initial_Time - $Final_Time
</form>
</div>
<h5 id="Drought_Conditions">{$_("Drought Conditions on")} $Latest_Timestamp</h5>
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
</html>
EOF;

print $text;

page_footer();
?>
