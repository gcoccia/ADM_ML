<?php

if (file_exists('../settings.xml')) {
  $xmlobj = simplexml_load_file("../settings.xml");
} else { exit("Error: settings.xml file not found."); }

require_once('php-gettext-1.0.11/gettext.inc');
$locale = BP_LANG;
$textdomain="adm";

if (isset($_GET['locale']) && !empty($_GET['locale'])) {
  $locale = $_GET['locale'];
  setcookie("locale", $locale, time()+60*30); //cookie expires 30 minutes from last page visit
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

$locales_dir = dirname(__FILE__).'/i18n';
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

$label_array = array("LinktoImage" => $_('Link to Image'),
                     "LinktoData" => $_('Link to Data'),
                     "PlotTitle" => $_('Simulated_Discharge').",".$_('Water_Balance').",".$_('Soil_Moisture_Products'),
                     "PlotYlabel" => $_('Surplus_[mm]').",".$_('Q_[mm/day]').",".$_('Basin_Average'),
                     "PlotXlabel" => $_('Time_[day]'),
                     "PlotXlabel_Month" => $_('Time_[month]'),
                     "ProcessNTI" => $_('Process new time interval'),
                     "ProcessRPW" => $_('Processing request, please wait'));

$info_box_strings = array("prec" => $_(""),
                          "tmax" => $_(""),
                          "tmin" => $_(""),
                          "wind" => $_(""),
                          "vc1" => $_(""),
                          "vc2" => $_(""),
			  "evap" => $_(""),
                          "runoff" => $_(""),
  			  "baseflow" => $_(""),
			  "flw" => $_(""),
   			  "spi1" => $_(""),
			  "spi3" => $_(""),
			  "spi6" => $_(""),
			  "spi12" => $_(""),
			  "vcpct" => $_(""),
 			  "pct30day" => $_(""),
 			  "flw_pct" => $_(""),
			  "r_net" => $_(""),
 			  "net_long" => $_(""),
			  "net_short" => $_(""),
			  "ndvi30" => $_(""),
			  "t2m" => $_(""),
			  "t2ano" => $_(""));

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
  var info_box_strings = <?php echo json_encode($info_box_strings, JSON_NUMERIC_CHECK) ?>;
  var data_timesteps = [], data_idates = [], data_fdates = [];
  <?php foreach($xmlobj->variables->group as $group) {
    foreach($group->datatype as $dt) {
      foreach($dt->dataset as $ds) {
        echo "data_timesteps[\"".$ds['name']."--".$dt['name']."\"] = \"".$ds['ts']."\";\n";
        echo "data_idates[\"".$ds['name']."--".$dt['name']."\"] = \"".$ds['itime']."\";\n";
        echo "data_fdates[\"".$ds['name']."--".$dt['name']."\"] = \"".$ds['ftime']."\";\n";
      }
    }
  } ?>

  var DEFAULT_ANIMATION_DATASET = "VIC_DERIVED--vcpct";

  // Define JS variables from PHP arrays
  <?php 
    foreach($date_array as $key => $value) {
      echo "var ".$key." = ".$value.";\n";
      echo "var ".$key."_orig = ".$value.";\n";
    } 
    foreach($label_array as $key => $value) {
      echo "var ".$key." = "."\"".$value."\"".";\n";
    }
  ?>

  function initialize() 
  {
    // Echo user settings from PHP
    var dim = <?php echo json_encode($xmlobj->dimensions, JSON_NUMERIC_CHECK) ?>;
    var swBound = new google.maps.LatLng(dim.minlat, dim.minlon);
    var neBound = new google.maps.LatLng(dim.minlat + (dim.nlat-1)*dim.res, dim.minlon + (dim.nlon-1)*dim.res);
    //var mapCenter = new google.maps.LatLng(dim.minlat + dim.nlat*dim.res/2.5, dim.minlon + dim.nlon*dim.res/2);
    var mapCenter = new google.maps.LatLng(dim.centerlat, dim.centerlon);
    
    var styleArray = [{featureType: 'administrative.country',stylers: [{ visibility: 'simplified' }]}];

    var myOptions = {styles: styleArray,zoom: dim.izoom,center: mapCenter,panControl: false,zoomControl: true,zoomControlOptions:{style:      
    google.maps.ZoomControlStyle.DEFAULT,position: google.maps.ControlPosition.LEFT_TOP},scaleControl: false,streetViewControl: false,mapTypeControl: 
    true,mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,position: google.maps.ControlPosition.TOP_LEFT},mapTypeId: 
    google.maps.MapTypeId.TERRAIN};

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
      else if(""+current_setting == "point" && $("#popUpDiv").is(":visible")) Hide_Data_Extraction_Popup();
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
      }
    });
    $(".mf-pills").click(function() {
      if(!$(this).parent().hasClass("active")) { // only act on change
        $(".mf-pills").parent().removeClass("active");
        $(this).parent().addClass("active");
        update_monitor_or_forecast();
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
          update_animation();
          update_basic();
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
      copyLi.find('a').text(copyLi.find('a').attr('id')); // give new element full ID including datatype
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
      <a class="brand" href='index.php'><?php echo $_("African Water Cycle Monitor"); ?></a>
      <ul class="nav" >
        <li class="divider-vertical"></li>
	<li id="InteractiveInterface" class="active"><a onclick="LoadInteractive()"><?php echo $_("Interactive Interface"); ?></a></li>
        <li class="divider-vertical"></li>
        <li id="BasicInterface"><a onclick="LoadBasic()"><?php echo $_("Basic Interface"); ?></a></li>
        <li class="divider-vertical"></li>
        <li><a href='Resources/ADM_Background.pdf'><?php echo $_("Background"); ?></a></li>
        <li class="divider-vertical"></li>
        <li><a href='Resources/ADM_Glossary.pdf'><?php echo $_("Glossary"); ?></a></li>
        <li class="divider-vertical"></li>
        <li><a href='Resources/Tutorial_HornAfrica.pdf'><?php echo $_("Tutorial"); ?></a></li>
      </ul>
    </div>
  </div>
</div>

<div class="row-fluid" style="width:100%; position: absolute; bottom: 0px; top:40px;">
    <div class="span12" style="height:100%; width=100%;">
      <div id="blanket" style="display:none;"></div>
      <div id="popUpDiv" style="display:none;">
        <!--Close window box-->
        <a onclick="Hide_Data_Extraction_Popup()" style="width:80px; height:10px">[X] <?php echo $_('Close Window') ?></a>
        <!--Chart Container-->
        <div id="popup_container"></div>
      </div>

      <div id="Colorbar" style="visibility:hidden;"></div>
      <div id="TimeStamp" style="visibility:hidden;"></div>
      <div id="Logo" style="visibility:hidden;"></div>

    <div id="map_canvas_1" style="max-width: none;"></div>
    <div class="row">
      <div id="sidebar1" class="span3 scrollDiv" style="visibility:visible; padding-right:0; position: absolute; top: 0px; background-color: rgb(240,240,240); width: auto; width:320px; right:0px; bottom: 0px;">
         <?php include('sidebar.php'); ?>
        </div>      
     <div id="basic_interface1" class="span10 scrollDiv" style="visibility:hidden; overflow:hidden; position: absolute; top:0px; bottom:0px; left:0px; margin-left:0;"></div>
     </div>
     </div>
   <div id="hideBtn"><i id="hideBtnImg" class="icon-arrow-right" style="position: absolute; top:0px; right:0px; z-index: 9100;"></i></div>
</div>
</div>
</body>
</html>

