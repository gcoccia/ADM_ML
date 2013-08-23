<?php

if (file_exists('settings.xml')) {
  $xmlobj = simplexml_load_file("settings.xml");
} else { exit("Error: settings.xml file not found."); }

require_once('php-gettext-1.0.11/gettext.inc');
include 'scripts/Read_Gauges.php';
include 'scripts/Read_DM_log.php';#Script to read in the drought monitor parameters to set as limits
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
$year_final = $year_initial;
$month_final = $month_initial;
$day_final = $day_initial;
$Gauge_Initial_Time = date('d/m/Y',jdtounix(unixtojd() - 91));
$gauge_year_initial = (int)(substr($Gauge_Initial_Time,6,4));
$gauge_month_initial = (int)(substr($Gauge_Initial_Time,3,2));
$gauge_day_initial = (int)(substr($Gauge_Initial_Time,0,2));
$Gauge_Initial_Time_Monthly = date('d/m/Y',jdtounix(unixtojd() - 5*365));
$gauge_year_initial_monthly = (int)(substr($Gauge_Initial_Time_Monthly,6,4));
$gauge_month_initial_monthly = (int)(substr($Gauge_Initial_Time_Monthly,3,2));
$Gauge_Final_Time_Monthly = date('d/m/Y',mktime(0, 0, 0, $month_final - 1 ,1 ,$year_final));  
$gauge_year_final_monthly = (int)(substr($Gauge_Final_Time_Monthly,6,4));
$gauge_month_final_monthly = (int)(substr($Gauge_Final_Time_Monthly,3,2));
$gauge_year_final = $year_final;
$gauge_month_final = $month_final;
$gauge_day_final = $day_final;
$_ = 'T_';

$date_array = compact("year_initial", "month_initial", "day_initial",
                      "year_final", "month_final", "day_final",
                      "gauge_year_initial", "gauge_month_initial", "gauge_day_initial",
                      "gauge_year_initial_monthly", "gauge_month_initial_monthly",
                      "gauge_year_final_monthly", "gauge_month_final_monthly",
                      "gauge_year_final", "gauge_month_final", "gauge_day_final");

$label_array = array("LinktoImage" => $_('Link to Image'),
                     "LinktoData" => $_('Link to Data'),
                     "PlotTitle" => $_('Simulated_Discharge').",".$_('Water_Balance').",".$_('Soil_Moisture_Products'),
                     "PlotYlabel" => $_('Surplus_[mm]').",".$_('Q_[mm/day]').",".$_('Basin_Average'),
                     "PlotXlabel" => $_('Time_[day]'),
                     "PlotXlabel_Month" => $_('Time_[month]'),
                     "ProcessNTI" => $_('Process new time interval'),
                     "ProcessRPW" => $_('Processing request, please wait'));

$info_box_strings = array(1 => $_("Weather data used to drive the hydrologic model. Precipitation comes from either satellite precipitation (TMPA) or the global forecasting system (GFS). The temperature and wind data come from GFS. All variables are bias-corrected against the data set that is used in the historical simulations to ensure consistency."),
                          2 => $_("Hydrologic variables obtained through simulations of the Variable Infiltration Capacity model. The soil moisture is expressed as relative soil moisture (0 - 100%)."),
                          3 => $_("The drought index is obtained by comparing the current relative soil moisture of layers 1 and 2 to empirical cumulative distribution functions derived from the historical record (1948 - 2008). There is a different empirical cumulative distribution function per day per grid cell which samples each year using a 21 day window around the day in question."),
                          4 => $_("Each point represents a location at which different variables are calculated that are specific to the basin that drains into that point. The variables include simulated discharge and basin averaged variables including precipitation, evaporation, runoff, soil moisture and the drought index. The colors on the map represents the percentile of the current simulated discharge with respect to the historical record (1948 - 2008)."),
                          5 => $_("This is the SMOS CATDS L4 Root zone soil moisture index. The product is obtained from the integration of SMOS surface soil moisture L3 products into a double bucket hydrological model. It represents the soil moisture in the first meters of the soil in percentage."),
                          6 => $_("The SPI is an index based on the probability of recording a given amount of precipitation after standardizing the probabilities so that an index of zero indicates the median precipitation amount for the entire precipitation record. The SPI can be calculated at any time step. The index is negative for drought, and positive for wet conditions."));

/*$gauge_info_arrays = array("gauge_number" => $gauge_number_2,
                            "gauge_lat" => $gauge_lat,
                            "gauge_lon" => $gauge_lon,
                            "gauge_area" => $gauge_area,
                            "gauge_percentile" => $gauge_percentile,
                            "gauge_flag" => $gauge_flag);*/

?>

<!DOCTYPE html> 
<html style="height:100%"> 
<head> 
<title><?php echo $_("Africa Drought Monitor") ?></title>
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<link href="css/s.css" rel=stylesheet> 
<link rel="stylesheet" type="text/css" media="screen,projection" href="css/Moz.css" title="Moz" />
<script src="http://code.jquery.com/jquery-1.10.1.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="jsscripts/popupcss.js"></script>
<script type="text/javascript" src="jsscripts/MiscFunctions.js"></script>
<script type="text/javascript" src="jsscripts/VarDeclaration.js"></script>
<script type="text/javascript" src="jsscripts/AnimationPrep.js"></script>
<script type="text/javascript" src="jsscripts/ImageOverlay.js"></script>
<script type="text/javascript" src="jsscripts/MainFunctions.js"></script>

<script type="text/javascript">
  var basinImage  = <?php echo $mask_gauge ?>;
  var info_box_strings = <?php echo json_encode($info_box_strings, JSON_NUMERIC_CHECK) ?>;
  
  // Define JS variables from PHP arrays
  <?php 
    foreach($date_array as $key => $value) {
      echo "var ".$key." = ".$value.";\n";
      echo "var ".$key."_orig = ".$value.";\n";
    } 
    foreach($label_array as $key => $value) {
      echo "var ".$key." = "."\"".$value."\"".";\n";
    }
/*    foreach($gauge_info_arrays as $key => $value) {
      echo "var ".$key." = ".$value.";\n";
    }*/
  ?>

  function initialize() 
  {
    // Echo user settings from PHP
    var dim = <?php echo json_encode($xmlobj->dimensions, JSON_NUMERIC_CHECK) ?>;
    var swBound = new google.maps.LatLng(dim.minlat, dim.minlon);
    var neBound = new google.maps.LatLng(dim.minlat + dim.nlat*dim.res, dim.minlon + dim.nlon*dim.res);
    var mapCenter = new google.maps.LatLng(dim.minlat + dim.nlat*dim.res/2.5, dim.minlon + dim.nlon*dim.res/2);
    
    var styleArray = [{featureType: 'administrative.country',stylers: [{ visibility: 'simplified' }]}];

    var myOptions = {styles: styleArray,zoom: 3,center: mapCenter,panControl: false,zoomControl: true,zoomControlOptions:{style:      
    google.maps.ZoomControlStyle.DEFAULT,position: google.maps.ControlPosition.LEFT_TOP},scaleControl: false,streetViewControl: false,mapTypeControl: 
    true,mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,position: google.maps.ControlPosition.TOP_LEFT},mapTypeId: 
    google.maps.MapTypeId.TERRAIN};

    //Insert the map canvas into html
    map_array[0] = new google.maps.Map(document.getElementById("map_canvas_1"), myOptions);
    bounds = new google.maps.LatLngBounds(swBound, neBound);
    animate_overlay(15) //Load the drought index map from the start
  }

  $(document).ready(function() {

    initialize();

    //Collapsible sidebar elements
    $(".data-group-header").click(function() {
      $(this).parent().find(".data-form-block").toggle();
    });

    //Info Box events
    $(".question_mark").hover(function() {
        $("#Info_Box").css("visibility", "visible");
        $("#Info_Box").html(info_box_strings[$(this).attr('id')])},
      function() {
        $("#Info_Box").css("visibility", "hidden");
        $("#Info_Box").html('');
    });

  });

</script>
</head> 

<body style="height:100%;margin:0">  

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

<div class='hbar'>
  <table id="nav"><tr>
    <td class="link" onClick="document.location.href='BasicInterface.php'+window.location.search">
      <?php echo $_("Basic Interface"); ?></td>
    <td>
      <?php echo $_("Google Maps Interface"); ?></td>
    <td class="link" onClick="document.location.href='Resources/ADM_Background.pdf'">
      <?php echo $_("Background"); ?></td>
    <td class="link" onClick="document.location.href='Resources/ADM_Glossary.pdf'">
      <?php echo $_("Glossary"); ?></td>
    <td class="link" onClick="document.location.href='Resources/Tutorial_HornAfrica.pdf'">
      <?php echo $_("Tutorial"); ?></td>
    <td class="flag">
      <img id="Flag_Image" src="icons/flags/english_flag.gif" onclick=ChangeLanguage("English")></td>
    <td class="flag">
      <img id="Flag_Image" src="icons/flags/french_flag.gif" onclick=ChangeLanguage("French")></td>
    <td class="flag">
      <img id="Flag_Image" src="icons/flags/chinese_flag.gif" onclick=ChangeLanguage("Chinese")></td>
    <td class="flag">
      <img id="Flag_Image" src="icons/flags/spanish_flag.gif" onclick=ChangeLanguage("Spanish")></td>
    <td class="flag">
      <img id="Flag_Image" src="icons/flags/arabic_flag.gif" onclick=ChangeLanguage("Arabic")></td>
    <td class="version">Version 1.1</td></tr></table>
</div>

<div id="blanket" style="display:none;"></div>
<div id="popUpDivparent">
  <div id="popUpDiv" style="display:none;">
    <a href="#" onclick="popup('popUpDiv')"></a>
  </div>
</div>

<div id="Region_Placement">
  <select id="BasinSelect" onchange=ChangeBasin(value)>
    <option value="Title"><?php echo $_("Select Region...")?></option>
    <option value="Congo">Congo</option>
    <option value="Nile">Nile</option>
    <option value="Niger">Niger</option>
    <option value="Senegal">Senegal</option>
    <option value="Volta">Volta</option>
  </select>
</div>  

<div id="Colorbar" style="visibility:hidden;"></div>
<div id="TimeStamp" style="visibility:hidden;"></div>
<div id="Logo" style="visibility:hidden;"></div>
<div id="DBandMC">
  <div id="map_canvas_1" style="width:100%; height:100%;"></div>
</div>

<div id="sidebar" style="visibility:visible"> 
<?php include('sidebar.php'); ?>
</div>

</body> 
</html> 
