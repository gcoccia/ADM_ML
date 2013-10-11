<?php

if (file_exists('../settings.xml')) {
  $xmlobj = simplexml_load_file("../settings.xml");
} else { exit("Error: settings.xml file not found."); }

require_once('languages/php-gettext-1.0.11/gettext.inc');
$locale = BP_LANG;
$textdomain="awcm";

if (isset($_GET['locale']) && !empty($_GET['locale'])) {
  $locale = $_GET['locale'];
  setcookie("locale", $locale, time()+60*30); //cookie expires 30 minutes from last page visit
  #setcookie("locale", $locale, time()-3600); //cookie expires 30 minutes from last page visit
}
elseif(isset($_COOKIE["locale"]) && !empty($_COOKIE["locale"])) {
  header("Location: interface.php?locale=".$_COOKIE["locale"]);
}
else {
  $locale = 'en';
}

putenv('LANGUAGE='.$locale);
putenv('LANG='.$locale);
putenv('LC_ALL='.$locale);
putenv('LC_MESSAGES='.$locale);
T_setlocale(LC_ALL,$locale);
T_setlocale(LC_CTYPE,$locale);

$locales_dir = dirname(__FILE__).'/languages/i18n';
T_bindtextdomain($textdomain,$locales_dir);
T_bind_textdomain_codeset($textdomain, 'UTF-8'); 
T_textdomain($textdomain);

//Extract the time information
foreach($xmlobj->variables->group as $group) {
 foreach($group->datatype as $dt) {
  foreach($dt->dataset as $ds) {
   if ($dt['name'] == 'vcpct' and $ds['name'] == 'VIC_DERIVED'){
    $fdate = (date_parse($ds['ftime']));
    $year_initial = $fdate['year'];
    $month_initial = $fdate['month'];
    $day_initial = $fdate['day'];
    break 3;
   }
  }
 }
} 
$year_final = $year_initial;
$month_final = $month_initial;
$day_final = $day_initial;
$_ = 'T_';

$date_array = compact("year_initial", "month_initial", "day_initial",
                      "year_final", "month_final", "day_final");

$info_box_strings = array("prec" => $_("Daily total surface precipitation"),
                          "tmax" => $_("Daily maximum temperature measured at 2 meters above the surface"),
                          "tmin" => $_("Daily minimum temperature measured at 2 meters above the surface"),
                          "wind" => $_("Daily mean wind speed measured at 2 meters above the surface"),
                          "vc1" => $_("Relative soil moisture of the top layer (0 - 10 cm) calculated from the land surface model output."),
                          "vc2" => $_("Relative soil moisture of the second layer (10 - ~100cm) calculated from the land surface model output. "),
			  "evap" => $_("The sum of the land surface model’s soil evaporation, canopy interception and plant transpiration."),
                          "runoff" => $_("Excess water from rain, snowmelt or other sources that does not infiltrate due to soil saturation or high intensity but instead flows overland."),
  			  "baseflow" => $_("Portion of streamflow that comes from the sum of deep subsurface flow and delayed shallow subsurface flow."),
			  "flw" => $_("Daily basin discharge calculated by inputting the baseflow and surface runoff from the VIC land surface model at each grid cell into the Velocity Driven Spatially Continuous routing model"),
   			  "spi1" => $_("The 1 month standard precipitation index is the number of standard deviations that observed 1-month cumulative precipitation deviates from the climatological average (McKee, 1993).
"),
			  "spi3" => $_("The 3 month standard precipitation index is the number of standard deviations that observed 3-month cumulative precipitation deviates from the climatological average (McKee, 1993).
"),
			  "spi6" => $_("The 6 month standard precipitation index is the number of standard deviations that observed 6-month cumulative precipitation deviates from the climatological average (McKee, 1993)."),
			  "spi12" => $_("The 12 month standard precipitation index is the number of standard deviations that observed 12-month cumulative precipitation deviates from the climatological average (McKee, 1993)."),
			  "vcpct" => $_("A measure of the severity of drought in soil moisture; low values indicate drought conditions"),
 			  "pct30day" => $_("A measure of the severity of agricultural drought; low values indicate drought conditions. The 30-day moving average of NDVI is compared to the historical record of NDVI via the empirical cumulative distribution function to determine the percentile."),
 			  "flw_pct" => $_("A measure of the severity of hydrological drought; low values indicate drought conditions. Percentile of the simulated discharge at each stream gauge with respect to the historical simulations (1950 - 2008)"),
			  "r_net" => $_("Difference between the incoming and outgoing components of radiation calculated using the VIC land surface model."),
 			  "net_long" => $_("Difference between the incoming and outgoing components of longwave radiation calculated using the VIC land surface model."),
			  "net_short" => $_("Difference between the incoming and outgoing components of shortwave radiation calculated using the VIC land surface model.
"),
			  "ndvi30" => $_("The Normalized Difference Vegetation Index (NDVI) is measure of live green vegetation (0-1)"),
			  "t2m" => $_("Average temperature at 2 meters above the surface."),
			  "t2ano" => $_("Departure from the climatological value of average temperature at 2 meters above the surface."));

$strings_to_translate = array("Indices" => $_('Indices'),
                              "SPI (1 month)" => $_('SPI (1 month)'),
                              "SPI (3 months)" => $_('SPI (3 months)'),
                              "SPI (6 months)" => $_('SPI (6 months)'),
                              "SPI (12 months)" => $_('SPI (12 months)'),
                              "Soil Moisture Index (%)" => $_('Soil Moisture Index (%)'),
                              "Vegetation Index (%)" => $_('Vegetation Index (%)'),
                              "Streamflow Index (%)" => $_('Streamflow Index (%)'),
                              "Percentile (%)" => $_('Percentile (%)'),
                              "Temperature Anomaly (C)" => $_('Temperature Anomaly (C)'),
			      "mm/day" => $_('mm/day'),
			      "Precipitation (mm/day)" => $_('Precipitation (mm/day)'),
                              "Evaporation (mm/day)" => $_('Evaporation (mm/day)'),
                              "Runoff (mm/day)" => $_('Runoff (mm/day)'),
                              "Baseflow (mm/day)" => $_('Baseflow (mm/day)'),
                              "Water Balance" => $_('Water Balance'),
    			      "Meteorology" => $_('Meteorology'),
                              "Daily Maximum Temperature (K)" => $_('Daily Maximum Temperature (K)'),
                              "Daily Minimum Temperature (K)" => $_('Daily Minimum Temperature (K)'),
                              "Daily Average Wind Speed (m/s)" => $_('Daily Average Wind Speed (m/s)'),
                              "Surface Fluxes" => $_('Surface Fluxes'),
                              "Net Shortwave (W/m2)" => $_('Net Shortwave (W/m2)'),
 			      "Net Longwave (W/m2)" => $_('Net Longwave (W/m2)'),
			      "Net Radiation (W/m2)" => $_('Net Radiation (W/m2)'),
			      "Streamflow (m3/s)" => $_('Streamflow (m3/s)'),
			      "Streamflow Index (%)" => $_('Streamflow Index (%)'),
			      "Streamflow" => $_('Streamflow'),
			      "Soil Moisture" => $_('Soil Moisture'),
			      "Soil Moisture (%) - Layer 1" => $_('Soil Moisture (%) - Layer 1'),
			      "Soil Moisture (%) - Layer 2" => $_('Soil Moisture (%) - Layer 2'),
			      "Soil Moisture Index (%)" => $_('Soil Moisture Index (%)'),
			      "Vegetation Index (%)" => $_('Vegetation Index (%)'),
			      "Vegetation" => $_('Vegetation'),
                              "Error: " => $_('Error: '),
                              " is only available from " => $_(' is only available from '),
                              " to " => $_(' to '),
                              "Your request has been submitted. You will receive an email when the data is ready to be downloaded." => $_('Your request has been submitted. You will receive an email when the data is ready to be downloaded.'),
			      "Point out of bounds. Please choose a point inside the domain." => $_('Point out of bounds. Please choose a point inside the domain.'),
			      "Error: The initial date must be after " => $_("Error: The initial date must be after "),
			      "Error: The final date must be before " => $_("Error: The final date must be before "),
                              "Error: The final date must be after the initial date." => $_("Error: The final date must be after the initial date."),
                              "Error: The selected domain is completely outside of the monitor's coverage. Please adjust your selection." => $_("Error: The selected domain is completely outside of the monitor's coverage. Please adjust your selection."),
			      "Warning: The selected domain is partially outside of the monitor's coverage. Your spatial request will be cropped." => $_("Warning: The selected domain is partially outside of the monitor's coverage. Your spatial request will be cropped."),
                              "Error: The final date must be on or after the initial date." => $_("Error: The final date must be on or after the initial date."),
                              );

header('X-UA-Compatible: IE=edge');

?>

<!DOCTYPE html> 
<html style="height:100%"> 
<head> 
<title>African Water Cycle Monitor</title>
<link rel="icon" type="image/ico" href="icons/AWCM_logo.ico">
<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
<link rel="stylesheet" type="text/css" href="css/bootstrap.css">
<link rel="stylesheet" type="text/css" href="css/custom.css">
<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.2/jquery.min.js"></script>
<link rel="stylesheet" href="http://code.jquery.com/ui/1.10.3/themes/smoothness/jquery-ui.css">
<script src="http://code.jquery.com/ui/1.10.3/jquery-ui.js"></script>
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
  var timeoutid;
  var general_info = <?php echo json_encode($xmlobj->dimensions, JSON_NUMERIC_CHECK) ?>;
  general_info.maxlon = general_info.minlon + general_info.res*(general_info.nlon-1);
  general_info.maxlat = general_info.minlat + general_info.res*(general_info.nlat-1);
  var info_box_strings = <?php echo json_encode($info_box_strings, JSON_NUMERIC_CHECK) ?>;
  var data_timesteps = [], data_idates = [], data_fdates = [], data_titles = [];
  <?php foreach($xmlobj->variables->group as $group) {
    foreach($group->datatype as $dt) {
      foreach($dt->dataset as $ds) {
        echo "data_timesteps[\"".$dt['name']."--".$ds['name']."\"] = \"".$ds['ts']."\";\n";
        echo "data_idates[\"".$dt['name']."--".$ds['name']."\"] = \"".$ds['itime']."\";\n";
        echo "data_fdates[\"".$dt['name']."--".$ds['name']."\"] = \"".$ds['ftime']."\";\n";
        echo "data_titles[\"".$dt['name']."--".$ds['name']."\"] = \"".$_("".$ds['title'])."\";\n";
      }
    }
  } ?>

  var DEFAULT_ANIMATION_DATASET = "vcpct--VIC_DERIVED";
  var TRANSLATE = <?php echo json_encode($strings_to_translate, JSON_NUMERIC_CHECK) ?>;

  // Define JS variables from PHP arrays
  <?php 
    foreach($date_array as $key => $value) {
      echo "var ".$key." = ".$value.";\n";
      echo "var ".$key."_orig = ".$value.";\n";
    } 
  ?>

  function initialize() 
  {
    // Echo user settings from PHP
    var dim = <?php echo json_encode($xmlobj->dimensions, JSON_NUMERIC_CHECK) ?>;
    //var swBound = new google.maps.LatLng(dim.minlat, dim.minlon);
    //var neBound = new google.maps.LatLng(dim.minlat + (dim.nlat-1)*dim.res, dim.minlon + (dim.nlon-1)*dim.res);
    var swBound = new google.maps.LatLng(dim.minlat, dim.minlon);
    var neBound = new google.maps.LatLng(dim.minlat + (dim.nlat-1)*dim.res - dim.res, dim.minlon + (dim.nlon-1)*dim.res - dim.res);
    //var mapCenter = new google.maps.LatLng(dim.minlat + dim.nlat*dim.res/2.5, dim.minlon + dim.nlon*dim.res/2);
    var mapCenter = new google.maps.LatLng(dim.centerlat, dim.centerlon);
    
    /*var styleArray = [
     {featureType: 'administrative.country',stylers: [{ visibility: 'on' }]},
     {elementType: 'all',styles: [{visibility:'off'}]}
    ];*/
    //var styleArray = [{stylers: [{ visibility:'on'}]}];
    var styleArray = [{
     featureType: "administrative.country",
     elementType: "labels",
     stylers: [
      { visibility: "simplified" }
     ]
    }]

    var myOptions = {
     minZoom:3,
     //maxZoom:8,
     styles:styleArray,
     zoom:dim.izoom,
     center:mapCenter,
     panControl:false,
     zoomControl:true,
     zoomControlOptions:{
      style:google.maps.ZoomControlStyle.DEFAULT,
      position: google.maps.ControlPosition.LEFT_TOP
     },
     scaleControl:false,
     streetViewControl:false,
     mapTypeControl:true,
     mapTypeControlOptions:{
      style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,
      position: google.maps.ControlPosition.TOP_LEFT
     },
     mapTypeId:google.maps.MapTypeId.TERRAIN
    };

    //Insert the map canvas into html
    map_array[0] = new google.maps.Map(document.getElementById("map_canvas_1"), myOptions);
    bounds = new google.maps.LatLngBounds(swBound, neBound);
  }

  $(document).ready(function() {

    // Initialize Jquery UI slider
    $( "#animation-slider" ).slider();

    initialize();
    update_timestep();
    update_animation(); // Start animation with default settings
    update_monitor_or_forecast();

    //Collapsible sidebar elements
    $(".nav-header").click(function() {
      $(this).parent().find(".data-form-block").toggle();
    });

    
   $('a').hover(function(){
     //alert($(this).parent().attr("class")); 
     if ($(this).parent().attr("class") == "dropdown") {
      var string = info_box_strings[$(this).attr("id")];
      var $name = $(this);
      // alert(string);
      $(this).popover({content: string, html: true, placement: 'top', trigger: 'hover', delay: {show: 1500, hide: 100}});
	}
    }); 

  $('a').trigger('mouseover'); 
      
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
      var current_setting = $("ul.data-extraction li.active>a").attr('id');
      if(""+current_setting == "none" && $("#InteractiveInterface").hasClass("active")) update_animation();
      else if(""+current_setting == "none") update_basic();
      else if(""+current_setting == "point" && $("#popUpDiv").is(":visible")) Create_Point_Plot();
      else if(""+current_setting == "spatial") Update_Spatial_Data_Display();
    });
    $("#clear_all").click(function() {
      var current_setting = $("ul.data-extraction li.active>a").attr('id');
      if(""+current_setting == "none") {
        clear_all_overlays();
        // Turn off the active chosen datasets
        $("ul.datalist>li").removeClass("active");
        $("ul.datalist>li>ul.dropdown-menu>li").removeClass("active");
        $("ul.datalist>li>a>i").removeClass("icon-ok");
        $("ul.datalist>li>ul.dropdown-menu>li>a>i").removeClass("icon-ok");
      }
      else if(""+current_setting == "point") {
        if($("#popUpDiv").is(":visible")) 
          Hide_Data_Extraction_Popup();
        if($("#ajax_request_load").is(":visible"))
          $("#ajax_request_load").hide();
      }
      else if(""+current_setting == "spatial") {
        Update_Listeners('spatial');
        $("input[name='variables_spatial_data[]']:checked").prop('checked', false);
        Update_Spatial_Data_Display();
      }
    });
    $("input[name=group1]:radio").change(function() {
      update_timestep();
      update_animation();
    });
    $("input[name=plot]:radio").change(function() {
      if($("#popUpDiv").is(":visible")) Create_Point_Plot();
    });
    $("input[name='sres_spatial_data']").change(function() {
      Update_Spatial_Data_Display();
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
      if($(this).val() < 1948 || $(this).val() > 2020)
        $(this).val(year_initial);
    });
    $( "input[id='year_final']" ).change(function() {
      if($(this).val() < 1948 || $(this).val() > 2020)
        $(this).val(year_final);
    });


    $(".de-pills").click(function() {
      if(!$(this).parent().hasClass("active")) { // only act on change
        $(".de-pills").parent().removeClass("active");
        $(this).parent().addClass("active");
        if($("#ajax_request_load").is(":visible"))
          $("#ajax_request_load").hide();
      }
    });
    $(".mf-pills").click(function() {
      if(!$(this).parent().hasClass("active")) { // only act on change
        $(".mf-pills").parent().removeClass("active");
        $(this).parent().addClass("active");
        update_monitor_or_forecast();

        if(""+$("ul.data-extraction li.active>a").attr('id') == "none")
          $("#clear_all").click(); // when switching between monitor/forecast, clear the current animation.
      }
    });
    $(".ts-pills").click(function() {
      if(!$(this).parent().hasClass("active")) {
        $(".ts-pills").parent().removeClass("active");
        $(this).parent().addClass("active");
        update_timestep();

        // If running animation, update
        if("none" == $("ul.data-extraction li.active>a").attr('id'))
          update_basic();
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
        
        if ($("#InteractiveInterface").hasClass("active")) {
          update_animation(); }
        else {
          update_basic(); }
      }
    });

    // Selecting spatial data types from dropdown menus
    $("ul.spatial-datalist>li>ul.dropdown-menu>li>a").click(function() {
      var copyLi = $(this).parent().clone();
      copyLi.appendTo("ul#currently-selected-vars");
      copyLi.find('a>i').remove();
      copyLi.find('a').prepend(copyLi.find('a').attr('id').split('--')[0] + ': '); // give new element full ID including datatype
      copyLi.find('a').prepend("<i class='icon-remove'></i>");

      // need to bind the removal click listener here, because the element did not exist at page load
      copyLi.find('a').click(function() {
        $("ul.spatial-datalist>li>ul.dropdown-menu>li>a#" + $(this).attr('id')).parent().show();
        $(this).parent().remove();
        Update_Spatial_Data_Display();
      });
      
      $(this).parent().hide();
      Update_Spatial_Data_Display();
    });

    // Animation play/pause buttons
    $( "#pause-or-continue").click(function() {
      if($(this).attr('class') == "icon-pause") {
        clearInterval(t);
        $(this).removeClass("icon-pause");
        $(this).addClass("icon-play");
      } else {
        t = setInterval(next_image, 1000*1/frames_per_second);
        $(this).removeClass("icon-play");
        $(this).addClass("icon-pause");
      }
    });

    $("#email_spatial_data").on('keyup change', function() {
      Update_Spatial_Data_Display();
    });

    $("textarea#input3").on('keyup', function() {
      var remaining = $(this).attr('maxlength') - $(this).val().length;
      $('#feedback-num-chars-remaining').text(remaining);
    });

    // Load the default dataset
    $("ul.datalist>li>ul.dropdown-menu>li>a#" + DEFAULT_ANIMATION_DATASET).click();
  });

</script>
</head> 

<body style="width:100%; height:100%">
<div class="container-fluid" style="width:100%; height:100%; padding-right:0px; padding-left:0px;">
<div class="navbar navbar-inverse">
  <div class="navbar-inner" style="border-radius: 0px"> 
    <div class="container">
      <a class="brand" href='index.php?locale=<?php echo $_GET['locale'];?>'><?php echo $_("African Water Cycle Monitor"); ?></a>
      <ul class="nav" >
        <li class="divider-vertical"></li>
	<li id="InteractiveInterface" class="active"><a onclick="LoadInteractive()"><?php echo $_("Interactive Interface"); ?></a></li>
        <li class="divider-vertical"></li>
        <li id="BasicInterface"><a onclick="LoadBasic()"><?php echo $_("Basic Interface"); ?></a></li>
        <li class="divider-vertical"></li>
        <!--<li><a href='Resources/ADM_Background.pdf'><?php echo $_("Background"); ?></a></li>
        <li class="divider-vertical"></li>-->
        <!--<li><a href='Resources/ADM_Glossary.pdf'><?php echo $_("Glossary"); ?></a></li>
        <li class="divider-vertical"></li>-->
        <li><a href='Resources/Tutorial_HornAfrica.pdf'><?php echo $_("Tutorial"); ?></a></li>
      	<li class="divider-vertical"></li>
        </ul>
        <ul class="nav pull-right">
          <li class="divider-vertical"></li>
          <li id="feedbackBtn"><a onclick="LoadFeedback()"><?php echo $_("Feedback"); ?></a></li>
	  <li class="divider-vertical"></li>
        </ul>
    </div>
  </div>
</div>

<div class="row-fluid" style="width:100%; position: absolute; bottom: 0px; top:40px;">
    <div class="span12" style="height:100%; width=100%;">
      <div id="blanket" style="display:none;"></div>
      <div id="ajax_request_load" style="display:none;"><img src="icons/ajax-loader.gif"/></div>
      <div id="popUpDiv" style="display:none;">
        <!--Chart Container-->
        <div id="popup_container"></div>
      </div>

      <div id="feedbackPopup" style="visibility:hidden; position:absolute; background:rgb(229, 227, 223); margin:auto; left: 22%; width:700px; top:60px; height:400px; z-index:9100; padding-top:15px; padding-bottom:15px; border-radius:5px; border:2px solid grey;">
        <form id='feedbackForm' method='POST' action='' class='form-horizontal'>
          <div>
            <h4 style='margin-left:30px;'><?php echo $_("Contact Us:")?></h4>
          </div>
          <div class='control-group'>
            <label class='control-label' for='input1'><?php echo $_("Name")?></label>
            <div class='controls'>
              <input type='text' name='contact_name' id='input1' maxlength='200' placeholder="<?php echo $_("Your name")?>">
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='input2'><?php echo $_("Email Address")?></label>
            <div class='controls'>
              <input type='text' name='contact_email' maxlength='100' id='input2' placeholder="<?php echo $_("Your email address")?>">
            </div>
          </div>
          <div class='control-group'>
            <label class='control-label' for='input3'><?php echo $_("Message")?></label>
            <div class='controls'>
              <textarea name='contact_message' id='input3' rows='8' class='span9' maxlength='1500' style='resize:none;' placeholder="<?php echo $_("Message to send.")?>"></textarea>
            </div>
            <p id="feedback-char-limit" style="margin: 0px; margin-left: 180px; margin-top:5px"><span id="feedback-num-chars-remaining">1500</span> <?php echo $_('characters remaining')?>.</p>
          </div>
          <div class='form-actions' style='border-radius:0px 0px 5px 5px;'>
            <input type='hidden' name='save' value='contact'>
            <button type='submit' class='btn btn-primary'><?php echo $_("Send")?></button>
            <button id='closeForm' type='button' class='btn' style='margin-left:30px' onclick='clearPopup();'><?php echo $_("Cancel")?></button>
          </div>
        </form>
      </div>
      <div id="Colorbar" style="visibility:hidden;"></div>
      <div id="TimeStamp" style="visibility:hidden;"></div>
      <div id="Logo" style="visibility:hidden;"></div>

    <div id="map_canvas_1" style="max-width: none;"></div>
    <div class="row">
      <div id="sidebar1" class="span3 scrollDiv" style="visibility:visible; padding-right:0; position: absolute; top: 0px; background-color: rgb(240,240,240); width: auto; width:320px; right:0px; bottom: 0px;">
         <?php include('sidebar.php'); ?>
        </div>      
     <div id="basic_interface1" class="span11 scrollDiv" style="visibility:hidden; overflow:hidden; position: absolute; top:0px; bottom:0px; margin-left:0; margin-right:0;"></div>
     </div>
     </div>
   <div id="hideBtn"><i id="hideBtnImg" class="icon-arrow-right" style="position: absolute; top:0px; right:0px; z-index: 9100;"></i></div>
</div>
</div>
</body>
</html>

