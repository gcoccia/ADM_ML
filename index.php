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

$info_box_strings = array("Meteorology" => $_("Weather data used to drive the hydrologic model. Precipitation comes from either satellite precipitation (TMPA) or the global forecasting system (GFS). The temperature and wind data come from GFS. All variables are bias-corrected against the data set that is used in the historical simulations to ensure consistency."),
                          "Hydrologic" => $_("Hydrologic variables obtained through simulations of the Variable Infiltration Capacity model. The soil moisture is expressed as relative soil moisture (0 - 100%)."),
                          "Drought" => $_("The drought index is obtained by comparing the current relative soil moisture of layers 1 and 2 to empirical cumulative distribution functions derived from the historical record (1948 - 2008). There is a different empirical cumulative distribution function per day per grid cell which samples each year using a 21 day window around the day in question."),
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
<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/s.css"> 
<link rel="stylesheet" type="text/css" href="css/Moz.css" title="Moz">
<link href='http://fonts.googleapis.com/css?family=Raleway:200' rel='stylesheet' type='text/css'>
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<script type="text/javascript" src="jsscripts/bootstrap.min.js"></script>
<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
<script type="text/javascript" src="jsscripts/MiscFunctions.js"></script>
<script type="text/javascript" src="jsscripts/VarDeclaration.js"></script>
<script type="text/javascript" src="jsscripts/AnimationPrep.js"></script>
<script type="text/javascript" src="jsscripts/ImageOverlay.js"></script>
<script type="text/javascript" src="jsscripts/MainFunctions.js"></script>
<script type="text/javascript" src="jsscripts/timestep.js"></script>
<script type="text/javascript" src="jsscripts/polygonEdit_packed.js"></script>
<script type="text/javascript" src="jsscripts/data_extraction.js"></script>
<script src="http://code.highcharts.com/highcharts.js" type="text/javascript"></script>
<script src="http://code.highcharts.com/modules/exporting.js" type="text/javascript"></script>
<script type="text/javascript">
  var basinImage  = <?php echo $mask_gauge ?>;
  var info_box_strings = <?php echo json_encode($info_box_strings, JSON_NUMERIC_CHECK) ?>;
  var data_timesteps = [], data_idates = [], data_fdates = [];
  <?php foreach($xmlobj->variables->group as $group) {
    foreach($group->datatype as $dt) {
      foreach($dt->dataset as $ds) {
        echo "data_timesteps[\"".$ds['name']."_".$dt['name']."\"] = \"".$ds['ts']."\";\n";
        echo "data_idates[\"".$ds['name']."_".$dt['name']."\"] = \"".$ds['itime']."\";\n";
        echo "data_fdates[\"".$ds['name']."_".$dt['name']."\"] = \"".$ds['ftime']."\";\n";
      }
    }
  } ?>

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
    var neBound = new google.maps.LatLng(dim.minlat + (dim.nlat-1)*dim.res, dim.minlon + (dim.nlon-1)*dim.res);
    var mapCenter = new google.maps.LatLng(dim.minlat + dim.nlat*dim.res/2.5, dim.minlon + dim.nlon*dim.res/2);
    
    var styleArray = [{featureType: 'administrative.country',stylers: [{ visibility: 'simplified' }]}];

    var myOptions = {styles: styleArray,zoom: 3,center: mapCenter,panControl: false,zoomControl: true,zoomControlOptions:{style:      
    google.maps.ZoomControlStyle.DEFAULT,position: google.maps.ControlPosition.LEFT_TOP},scaleControl: false,streetViewControl: false,mapTypeControl: 
    true,mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,position: google.maps.ControlPosition.TOP_LEFT},mapTypeId: 
    google.maps.MapTypeId.TERRAIN};

    //Insert the map canvas into html
    map_array[0] = new google.maps.Map(document.getElementById("map_canvas_1"), myOptions);
    bounds = new google.maps.LatLngBounds(swBound, neBound);
  }

  $(document).ready(function() {

    initialize();
    update_timestep();
    update_animation(); // Start animation with default settings

    //Collapsible sidebar elements
    $(".nav-header").click(function() {
      $(this).parent().find(".data-form-block").toggle();
    });

   
   $('a').hover(function(){
     //alert($(this).parent().attr("class")); 
     if ($(this).parent().attr("class") == "nav-header") {
       var string = info_box_strings[$(this).parent().attr("id")];
       //alert(string);
       $(this).popover({
          content: string,
          html: true,
          placement: 'top',
          container: 'body',
          trigger: 'hover'
       }); }
    });  
          
    $('#hideBtn').click(function() {
      $('#sidebar1').toggle();
      if ($('#hideBtnImg').attr('class') == 'icon-arrow-right') {
	  $('#hideBtnImg').removeClass('icon-arrow-right');
          $('#hideBtnImg').addClass('icon-arrow-left');
          $('#hideBtnImg').css({"background-color": "#FFFFFF", "border-radius" : "2px"}); 
      } else if ($('#hideBtnImg').attr('class') == 'icon-arrow-left') {
	  $('#hideBtnImg').removeClass('icon-arrow-left');
          $('#hideBtnImg').addClass('icon-arrow-right');
          $('#hideBtnImg').css({"background-color": "transparent", "border-radius" : "2px"}); 
      }
     });

    $("#update_interval").click(function() {
      var current_setting = $("ul.de-selection li.active>a").attr('id');
      if(""+current_setting == "none") update_animation();
      else if(""+current_setting == "point") //
      else //
    });
    $("#clear_all").click(function() {
      clear_all_overlays();
      // Turn off the active chosen datasets
      $("ul.datalist>li").removeClass("active");
      $("ul.datalist>li>ul.dropdown-menu>li").removeClass("active");
      $("ul.datalist>li>a>i").removeClass("icon-ok");
      $("ul.datalist>li>ul.dropdown-menu>li>a>i").removeClass("icon-ok");
    });
    $("input[name=group1]:radio").change(function() {
      update_timestep();
      update_animation();
    });

    // Validation for date entry
    $( "input[id='day_initial']" ).change(function() {
      if($(this).val() < 1 || $(this).val() > 31)
        $(this).val(day_initial);
    });
    $( "input[id='day_final']" ).change(function() {
      if($(this).val() < 1 || $(this).val() > 31)
        $(this).val(day_final);
    });
    $( "input[id='month_initial']" ).change(function() {
      if($(this).val() < 1 || $(this).val() > 12)
        $(this).val(month_initial);
    });
    $( "input[id='month_final']" ).change(function() {
      if($(this).val() < 1 || $(this).val() > 12)
        $(this).val(month_final);
    });
    $( "input[id='year_initial']" ).change(function() {
      if($(this).val() < 1948 || $(this).val() > 2013)
        $(this).val(year_initial);
    });
    $( "input[id='year_final']" ).change(function() {
      if($(this).val() < 1948 || $(this).val() > 2013)
        $(this).val(year_final);
    });


    $(".de-pills").click(function() {
      if(!$(this).parent().hasClass("active")) { // only act on change
        $(".de-pills").parent().removeClass("active");
        $(this).parent().addClass("active");
      }
    });
    $(".ts-pills").click(function() {
      if(!$(this).parent().hasClass("active")) {
        $(".ts-pills").parent().removeClass("active");
        $(this).parent().addClass("active");
        update_timestep();
        update_animation();
      }
    });

    // When you click a dataset from a dropdown menu...
    // Check if it's different than the previously chosen one. If so, do a bunch of stuff.
    $("ul.datalist>li>ul.dropdown-menu>li>a").click(function() {
      if(!$(this).parent().hasClass("active")) {
        $("ul.datalist>li").removeClass("active");
        $("ul.datalist>li>ul.dropdown-menu>li").removeClass("active");
        $("ul.datalist>li>a>i").removeClass("icon-ok");
        $("ul.datalist>li>ul.dropdown-menu>li>a>i").removeClass("icon-ok");

        $(this).parent().addClass("active");
        $(this).parent().parent().parent().addClass("active");
        $(this).find('i').addClass("icon-ok");
        $(this).parent().parent().parent().find("a.dropdown-toggle>i").addClass("icon-ok");

        update_animation();
      }
    });
  });

</script>
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
	<li class="active"><a href="#"><?php echo $_("Google Maps Interface"); ?></a></li>
        <li><a href='BasicInterface.php'><?php echo $_("Basic Interface"); ?></a></li>
        <li><a href='Resources/ADM_Background.pdf'><?php echo $_("Background"); ?></a></li>
        <li><a href='Resources/ADM_Glossary.pdf'><?php echo $_("Glossary"); ?></a></li>
        <li><a href='Resources/Tutorial_HornAfrica.pdf'><?php echo $_("Tutorial"); ?></a></li>
        </ul>
	<img id="Flag_Image" style="float:right" src="icons/flags/arabic_flag.gif" onclick=ChangeLanguage("Arabic")>
	<img id="Flag_Image" style="float:right" src="icons/flags/spanish_flag.gif" onclick=ChangeLanguage("Spanish")>
	<img id="Flag_Image" style="float:right" src="icons/flags/chinese_flag.gif" onclick=ChangeLanguage("Chinese")>
	<img id="Flag_Image" style="float:right" src="icons/flags/french_flag.gif" onclick=ChangeLanguage("French")>
	<img id="Flag_Image" style="float:right" src="icons/flags/english_flag.gif" onclick=ChangeLanguage("English")>
    </div>
  </div>
</div>

<div class="row-fluid" style="width:100%; position: absolute; bottom: 0px; top:110px;">
    <div class="span12" style="height:100%; width=100%;">
      <div id="blanket" style="display:none;"></div>
      <div id="popUpDiv" style="display:none;">
        <!--Close window box-->
        <a onclick="Hide_Data_Extraction_Popup()" style="width:80px; height:10px"><?php echo $_('Close Window') ?></a>
        <!--Chart Container-->
        <div id="popup_container"></div>
      </div>

      <div id="Colorbar" style="visibility:hidden;"></div>
      <div id="TimeStamp" style="visibility:hidden;"></div>
      <div id="Logo" style="visibility:hidden;"></div>

    <div id="map_canvas_1" style="max-width: none;"></div>
    <div class="row">
      <div id="sidebar1" class="span3 scrollDiv" style="visibility:visible; padding-right:0; position: absolute; top: 0px; background-color: #FFFFFF; border-radius: 5px; width: auto; min-width:250px; max-width:320px; right:0px; bottom: 0px;">
         <?php include('sidebar.php'); ?>
        </div>
      </div>
     </div>
   <div id="hideBtn"><i id="hideBtnImg" class="icon-arrow-right" style="position: absolute; top:0px; right:0px; z-index: 100;"></i></div>
</div>
</div>
</body>
</html>

