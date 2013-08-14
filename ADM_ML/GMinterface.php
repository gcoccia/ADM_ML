<?php
require_once('php-gettext-1.0.11/gettext.inc');
include 'scripts/Read_Gauges.php';
include 'scripts/Read_DM_log.php';#Script to read in the drought monitor parameters to set as limits
include 'scripts/Read_GaugePercentiles.php';
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

$main_page = <<< EOF
<!DOCTYPE html> 
<html style="height:100%"> 
	<head> 
	<title>{$_("Africa Drought Monitor")}</title>
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" /> 
	<meta http-equiv="content-type" content="text/html; charset=UTF-8"/> 
	<link href="css/s.css" rel=stylesheet> 
	<link rel="stylesheet" type="text/css" media="screen,projection" href="css/Moz.css" title="Moz" />
	<script type="text/javascript" src="http://maps.googleapis.com/maps/api/js?sensor=false"></script>
	<script type="text/javascript" src="jsscripts/popupcss.js"></script>
	<script type="text/javascript" src="jsscripts/MiscFunctions.js"></script>
	<script type="text/javascript" src="jsscripts/VarDeclaration.js"></script>
	<script type="text/javascript" src="jsscripts/AnimationPrep.js"></script>
	<script type="text/javascript">
	var basinImage  = $mask_gauge;
        var year_initial = $year_initial;
	var year_final = $year_final;
	var month_initial = $month_initial;
	var month_final = $month_final;
	var day_initial = $day_initial;
	var day_final = $day_final;
        var gauge_year_initial = $gauge_year_initial;
        var gauge_month_initial = $gauge_month_initial;
        var gauge_day_initial = $gauge_day_initial;
        var gauge_year_final = $gauge_year_final;
        var gauge_month_final = $gauge_month_final;
        var gauge_day_final = $gauge_day_final;
	var gauge_year_initial_monthly = $gauge_year_initial_monthly;
	var gauge_month_initial_monthly = $gauge_month_initial_monthly;
	var gauge_year_final_monthly = $gauge_year_final_monthly;
	var gauge_month_final_monthly = $gauge_month_final_monthly;
        var gauge_year_initial_orig = $gauge_year_initial;
        var gauge_month_initial_orig = $gauge_month_initial;
        var gauge_day_initial_orig = $gauge_day_initial;
        var gauge_year_final_orig = $gauge_year_final;
        var gauge_month_final_orig = $gauge_month_final;
        var gauge_day_final_orig = $gauge_day_final;
        var gauge_year_initial_monthly_orig = $gauge_year_initial_monthly;
        var gauge_month_initial_monthly_orig = $gauge_month_initial_monthly;
        var gauge_year_final_monthly_orig = $gauge_year_final_monthly;
        var gauge_month_final_monthly_orig = $gauge_month_final_monthly;
	var ImageTimeArray = [];	
	var ImageStrArray = [];
	var ImageRootArray = [];
	var ImageIdArray = [];
	ImageIdArray[5] = "PrecImage";
	ImageIdArray[6] = "TmaxImage";
	ImageIdArray[7] = "TminImage";
	ImageIdArray[8] = "WindImage";
	ImageIdArray[9] = "EvapImage";
	ImageIdArray[10] = "SM1Image";
	ImageIdArray[11] = "SM2Image";
	ImageIdArray[12] = "SM3Image";
	ImageIdArray[13] = "BaseImage";
	ImageIdArray[14] = "RunoffImage";
	ImageIdArray[15] = "smqallImage";
	ImageRootArray[5] = "/prec_GMaps/prec_";
	ImageRootArray[6] = "/tmax_GMaps/tmax_";
	ImageRootArray[7] = "/tmin_GMaps/tmin_";
	ImageRootArray[8] = "/wind_GMaps/wind_";
	ImageRootArray[9] = "/evap_GMaps/evap_";
	ImageRootArray[10] = "/smwet1_GMaps/smwet1_";
	ImageRootArray[11] = "/smwet2_GMaps/smwet2_";
	ImageRootArray[12] = "/smwet3_GMaps/smwet3_";
	ImageRootArray[13] = "/baseflow_GMaps/baseflow_";
	ImageRootArray[14] = "/runoff_GMaps/runoff_";
	ImageRootArray[15] = "/smqall_GMaps/smqall_";
	var Colorbar_Images = [];
	Colorbar_Images[5] = "Data/Colorbar/colorbar_prec.png";
	Colorbar_Images[6] = "Data/Colorbar/colorbar_tmax.png";
	Colorbar_Images[7] = "Data/Colorbar/colorbar_tmin.png";
	Colorbar_Images[8] = "Data/Colorbar/colorbar_wind.png";
	Colorbar_Images[9] = "Data/Colorbar/colorbar_evap.png";
	Colorbar_Images[10] = "Data/Colorbar/colorbar_smwet1.png";
	Colorbar_Images[11] = "Data/Colorbar/colorbar_smwet2.png";
	Colorbar_Images[12] = "Data/Colorbar/colorbar_smwet3.png";
	Colorbar_Images[13] = "Data/Colorbar/colorbar_baseflow.png";
	Colorbar_Images[14] = "Data/Colorbar/colorbar_runoff.png";
	Colorbar_Images[15] = "Data/Colorbar/colorbar_smqall.png";
	var overlay_opacity = 0.8;
	var overlay_mask_dropdown = new Array();
	var ImageLoadedBoolean;
	var day;
	var daycount;
	var gaugen; //Declare the gauge number as a global variable.
	var gauge_area;
        var gauge_lat;
        var gauge_lon;
	var image_type; //Determines what type of gauge plot one is looking at (wb or ds)
	var wb_image_string; //water balance plot image string
	var ds_image_string; //discharge plot image string
	var wb_data_string; //water balance data string
	var ds_data_string; //discharge data string
	var sm_image_string; //soil moisture plot image string
	var month;
	var timestep_flag = 1; //flag to indicate whether we are working with daily or monthly time steps
	timecount = 0;
	var variable_image_number = 999; //Number that tells which overlay to animate
	var t;
	</script>
	<script type="text/javascript" src="jsscripts/Gmapsimageoverlay.js"></script>
	<script language="JavaScript" type="text/javascript"> 
var myVariable
function update_basins(j)
	{
	var j;
	var k;
	if (typeof overlay_mask[j] !="undefined") //hide the overlay
		{
		overlay_mask[j].remove();
		delete overlay_mask[j];
		}
	else if(typeof overlay_mask[j] == "undefined")
		{
		overlay_mask[j] = new ImageOverlay(bounds, basinImage[j], map_array[0]);
		}
	}
function update_markers(j)
	{
	if (markersArray[0] == undefined)
		{
		 //Remove all the current overlays
       		 for (k=0; k < ImageStrArray.length; k++)
               		 {
               		 if (overlay_obj[k] != undefined)
                      		 {
                       		 clearTimeout(t);
                       		 overlay_obj[k].remove();
                      		 delete overlay_obj[k];
                       		 update_colorbar(k);
                      		  //Remove time stamp
                       		 ChangeTimeStamp(3);
                       		 }
               		 }
		var gauge_number = ${gauge_number_2};
		var gauge_lat = ${gauge_lat};
		var gauge_lon = ${gauge_lon};
		var gauge_area = ${gauge_area};
		var gauge_percentile = ${gauge_percentile};
		var gauge_flag = ${gauge_flag};
		var contentString = [];
		var icon_image;
                cbar = document.getElementById("Colorbar").style;
                cbar.visibility = "visible";
                cbar.height = "100";
                cbarcontentString = "<img src=Data/Colorbar/PercentilesClasses.png></img>";
                document.getElementById('Colorbar').innerHTML = cbarcontentString;
		for (m=0;m<${ngauges};m++)
			{
  			var myLatLng = new google.maps.LatLng(gauge_lat[m], gauge_lon[m]);
			contentString[m] = "<div id='PopUpControl' onclick=\"popup(\'popUpDiv\')\"><B>CLOSE WINDOW</B></div><table><tr><td><div><table><tr><td><div id='CatchmentInfo'><table><tr><td><B> {$_("Gauge number")}: </B>" + gauge_number[m] + "<br><B>{$_("Latitude")}: </B>" + gauge_lat[m] + " <B>{$_("Longitude")}: </B>" + gauge_lon[m]  + "<br><B>{$_("Catchment area")}: </B>" + sprintf('%.2f',gauge_area[m]*1.609344*1.609344) + " km2</td></tr></table></div></td></tr></div></td></tr><tr><td><div id='routed_image'><img src=''></div></td><td><div id='BasinForms'><div id='gaugevariableform'><B>  {$_("Variable Selection")}: </B><br><br><form name='gaugevariableform'><input id='Discharge' type='radio' name='gaugevariableform' value='Discharge' onclick='SwapGaugeImage(1)' checked='checked'> {$_("Discharge at gauge")}<br><input id='WaterBalance' type='radio' name='gaugevariableform' value='WaterBalance' onclick='SwapGaugeImage(2)'> {$_("Basin water balance")}<br><input id='SoilMoisture' type='radio' name='gaugevariableform' value='SoilMoisture' onclick='SwapGaugeImage(3)'> {$_("Soil Moisture")}<br></form><div id='timestepform'><B> {$_("Time Step")}: </B><br><br><form name='timestepform'><input id='daily' type='radio' name='timestep' value='daily' onclick='UpdatePopUpTimestep(0)' checked='checked'> {$_("Daily")}<br><input id='monthly' type='radio' name='timestep' value='monthly' onclick='UpdatePopUpTimestep(1)'> {$_("Monthly")}<br></form></div><div id='GaugeTimeInterval'><B> {$_("Time Interval")} <i id='time_interval_text'> ({$_("dd/mm/yyyy")})</i>: </B><br><br><form name='GaugeTimeInterval'><div id='gauge_initial_time'></div><BR><div id='gauge_final_time'></div><BR><input type='button' name='GaugeProcessButton' value='{$_("Process new time interval")}' onclick='GaugeProcess()'></form></div></div></td></tr><tr><td><div id='GaugeDownloadLinks'></div></td></tr></table>" 
			if (gauge_percentile[m] < 1)icon_image = "icons/gauges_percentiles/dot0.png";
			if (gauge_percentile[m] >= 1 && gauge_percentile[m] < 10)icon_image = "icons/gauges_percentiles/dot1.png";
			if (gauge_percentile[m] >= 10 && gauge_percentile[m] < 25)icon_image = "icons/gauges_percentiles/dot2.png";
			if (gauge_percentile[m] >= 25 && gauge_percentile[m] < 75)icon_image = "icons/gauges_percentiles/dot3.png";
			if (gauge_percentile[m] >= 75 && gauge_percentile[m] < 90)icon_image = "icons/gauges_percentiles/dot4.png";
			if (gauge_percentile[m] >= 90 && gauge_percentile[m] < 99)icon_image = "icons/gauges_percentiles/dot5.png";
			if (gauge_percentile[m] >= 99)icon_image = "icons/gauges_percentiles/dot6.png";
			if (gauge_percentile [m] >= 0 && gauge_flag[m] != -9999)
				{
  			markersArray[m] = new google.maps.Marker({position: myLatLng,map: map_array[0],icon: icon_image,zindex:100000});
			google.maps.event.addListener(markersArray[m], 'mouseover', (function(m) {return function() {update_basins(m);};})(m));	
			google.maps.event.addListener(markersArray[m], 'mouseout', (function(m) {return function() {update_basins(m);};})(m));		
			google.maps.event.addListener(markersArray[m], 'mouseup',(function(m) {return function() {popup("popUpDiv",m,contentString[m],gauge_number[m],gauge_lat[m],gauge_lon[m],gauge_area[m]);};})(m));
				}
			}
		}
	else
		{
  		clearMarkers();
		markersArray = [];
		cbar = document.getElementById("Colorbar").style;
                cbar.visibility = "hidden";
                cbar.height = "";
		}
	}
function update_overlay(j) 
	{
	var k;
	var cbar_string;	
	if (typeof static_overlay_obj[j] !="undefined") //hide the overlay
		{
		static_overlay_obj[j].remove();
		delete static_overlay_obj[j];
		}
	else if(typeof static_overlay_obj[j] == "undefined")
		{
		// grab overlay image select value
		value = document.getElementById("overlayImageSelect_" + j).value;
		// add new overlay to map
		static_overlay_obj[j] = new ImageOverlay(bounds, srcImage[j], map_array[0]);
		}
	}
function update_overlay_opacity(flag_dir)
	{
	var j = variable_image_number;
	var flag_dir
	if (flag_dir == 0){
		overlay_opacity = overlay_opacity - 0.2;
		if (overlay_opacity < 0){overlay_opacity = 0;};
		}
	else{
		overlay_opacity = overlay_opacity + 0.2;
		if (overlay_opacity > 1){overlay_opacity = 1;};
		}
	if (overlay_obj[j] != undefined){overlay_obj[j].ChangeOpacity();}
	/*
        for (k=0;k<overlay_mask_dropdown.length;k++)
                {
                if (overlay_mask_dropdown[k] != undefined)
                        {
                        overlay_mask_dropdown[k].ChangeOpacity();
                        }
                }
	*/
	}
function animate_overlay(j)
	{
	var j;
	variable_image_number = j;
	var k;
	//Remove all the current overlays
        for (k=0; k < ImageStrArray.length; k++)
		{
	        if (overlay_obj[k] != undefined) 
	               {
        	        clearTimeout(t);
                        overlay_obj[k].remove();
                        delete overlay_obj[k];
                        update_colorbar(k);
                        //Remove time stamp
                        ChangeTimeStamp(3);
                        }
		}
	//Remove markers
	clearMarkers();
        markersArray = [];
        cbar = document.getElementById("Colorbar").style;
        cbar.visibility = "hidden";
        cbar.height = "";

	//Fill up the Array of image strings
	ImageTimeArray[j] = new Array();
	ImageStrArray[j] = new Array();
	ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
	//Add the new overlay 
	var fcnstr;
	var i = 0;
	update_colorbar(j);
	update_overlay_animate(j,0);
	}
function update_colorbar(j)
	{
	var flag_m;
	var obj
	obj = document.getElementById("Colorbar").style;
	if (obj.visibility == "visible")
		{
		obj.visibility = "hidden";
		obj.height = "";
		}
	else if(obj.visibility == "hidden")
		{
		obj.visibility = "visible";
		obj.height = "100";
		contentString = "<img src="+Colorbar_Images[j]+"></img>";
		document.getElementById('Colorbar').innerHTML = contentString;
		}
	}
function update_overlay_animate(j,i) 
	{
	var j;
	var k;
	var maxi = daycount;
	var time_delay = 1000*1/frames_per_second;
	//var ImageLoadedBoolean;
	if (i == 0)
		{
		value = document.getElementById("overlayImageSelect_" + j).value;
		overlay_obj[j] = new ImageOverlay(bounds, ImageStrArray[j][i], map_array[0],ImageIdArray[j]);
		ChangeTimeStamp(1,i,j)
		i = i+1;
		fcnstr = "update_overlay_animate(" + j + "," + i + ")";
		t = setTimeout(fcnstr,time_delay);
		}
	else
		{
		if (i == maxi) {i = 0};
        	//ImageLoadedBoolean = false;
		overlay_obj[j].swap(ImageStrArray[j][i]);
		//while(ImageLoadedBoolean == false){}
		ChangeTimeStamp(2,i,j)
		i = i+1;
		fcnstr = "update_overlay_animate(" + j + "," + i + ")";
		t = setTimeout(fcnstr,time_delay);
		}
	}
function ClearAllOverlays()
	{
	var k;
	//Clear all variable overlays
	for (k=0;k<overlay_obj.length;k++)
		{
		if (overlay_obj[k] != undefined)
			{
			clearTimeout(t);
                        overlay_obj[k].remove();
                        delete overlay_obj[k];
                        update_colorbar(k);
                        //Remove time stamp
                        ChangeTimeStamp(3);
			} 
		}
	//Clear all static overlays
        for (k=0;k<static_overlay_obj.length;k++)
                {
                if (static_overlay_obj[k] != undefined)
                        {
                        clearTimeout(t);
                        static_overlay_obj[k].remove();
                        delete static_overlay_obj[k];
                        }
                }
	//Clear all markers
        clearMarkers();
	markersArray = [];
        cbar = document.getElementById("Colorbar").style;
        cbar.visibility = "hidden";
        cbar.height = "";

	//Remove all basin layers
	for (k=0;k<overlay_mask_dropdown.length;k++)
                {                
		if (overlay_mask_dropdown[k] != undefined)
                        {
			overlay_mask_dropdown[k].remove();
                        delete overlay_mask_dropdown[k];
                        }                
		}


	//Clear all forms
	document.getElementById("variables_form").reset();
	//document.getElementById("constants_form").reset();
	//Make sure the drought index isn't clicked when clearing the maps
	document.getElementById("overlayImageSelect_15").checked = false;//.checked = "unchecked";// = "unchecked"; 

	}
function animate_overlay_submit()
        {
	ReadTimeInterval();
	var k;
	var j = variable_image_number;
	for (k=0;k<overlay_obj.length;k++)
		{
		 if (overlay_obj[k] != undefined)
			 {
                          clearTimeout(t);
                          overlay_obj[k].remove();
                          delete overlay_obj[k];
                          update_colorbar(k);
                          //Remove time stamp
                          ChangeTimeStamp(3);
	                  //Fill up the Array of image strings
        	          ImageTimeArray[j] = new Array();
                	  ImageStrArray[j] = new Array();
                	  ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
                	  //Add the new overlay 
                	  update_colorbar(j);
                	  update_overlay_animate(j,0);
			 }
		}
	}
function update_basins(j)
	{
	var j;
	var k;
	if (typeof overlay_mask[j] !="undefined") //hide the overlay
		{
		overlay_mask[j].remove();
		delete overlay_mask[j];
		}
	else if(typeof overlay_mask[j] == "undefined")
		{
		overlay_mask[j] = new ImageOverlay(bounds, basinImage[j], map_array[0]);
		}
	}
function ChangeLanguage(language)
    {
	var locale_val;
	if(language == 'English') {locale_val = escape('en');}
	else if (language == 'French') { locale_val = escape('fr');}

	var uri = window.location.toString().split('?');
	if (uri.length > 1) {
		var kvp = uri[1].split('&');
	var i=kvp.length; var x; while(i--) 
	{
    		x = kvp[i].split('=');
    		if (x[0]=='locale')
    		{
            		x[1] = locale_val;
            		kvp[i] = x.join('=');
            		break;
    		}
	}
	if(i<0) {
    		kvp[kvp.length] = ['locale',locale_val].join('=');
    	}
	window.location.replace(uri[0]+'?'+kvp.join('&'));
	}
	else {
		window.location.replace(uri[0]+'?locale='+locale_val);
	}
	 
    
    }

function ChangeBasin(basin_name)
	{
	var basin_name;
	var basins_lat = [-2.0,14,13,13,9.7];
	var basins_lon = [23.0,32,6,-10,0.125];
	var basins_number = [0,34,68,238,259];
	var basins_zoom = [5,4,5,5,5];
	var basins_id = ["Congo","Nile","Niger","Senegal","Volta"];
	//Delete any existing basins
	for (i=0;i<basins_lat.length;i++)
		{
		if (typeof overlay_mask_dropdown[basins_number[i]] !="undefined") //hide the overlay
			{
			overlay_mask_dropdown[basins_number[i]].remove();
			delete overlay_mask_dropdown[basins_number[i]];
			}
		}
		
	for (i=0;i<basins_lat.length;i++)
		{
		if (basins_id[i] == basin_name)
			{
			map_array[0].setZoom(basins_zoom[i]);
			var latlng = new google.maps.LatLng(basins_lat[i], basins_lon[i]);
			map_array[0].setCenter(latlng);
			if(typeof overlay_mask_dropdown[basins_number[i]] == "undefined")
				{
				overlay_mask_dropdown[basins_number[i]] = new ImageOverlay(bounds, basinImage[basins_number[i]],map_array[0],9999,1);
				}
			}
		}
	//flag_basin = 0;
	//overlay_opacity = overlay_opacity_temp;
	}

function Info_Box_Call(data_type)
	{
	var data_type;
 	obj = document.getElementById("Info_Box").style;
        if (obj.visibility == "visible")
                {
                obj.visibility = "hidden";
                }
        else 
                {
                obj.visibility = "visible";
                }
	if (data_type == 1){
        document.getElementById("Info_Box").innerHTML = "<p>{$_("Weather data used to drive the hydrologic model. Precipitation comes from either satellite precipitation (TMPA) or the global forecasting system (GFS). The temperature and wind data come from GFS. All variables are bias-corrected against the data set that is used in the historical simulations to ensure consistency.")}</p>";
		}
        if (data_type == 2){        document.getElementById("Info_Box").innerHTML = "<p>{$_("Hydrologic variables obtained through simulations of the Variable Infiltration Capacity model. The soil moisture is expressed as relative soil moisture (0 - 100%).")}</p>";
                }
        if (data_type == 3){        document.getElementById("Info_Box").innerHTML = "<p>{$_("The drought index is obtained by comparing the current relative soil moisture of layers 1 and 2 to empirical cumulative distribution functions derived from the historical record (1948 - 2008). There is a different empirical cumulative distribution function per day per grid cell which samples each year using a 21 day window around the day in question.")}</p>";
		}
        if (data_type == 4){        document.getElementById("Info_Box").innerHTML = "<p>{$_("Each point represents a location at which different variables are calculated that are specific to the basin that drains into that point. The variables include simulated discharge and basin averaged variables including precipitation, evaporation, runoff, soil moisture and the drought index. The colors on the map represents the percentile of the current simulated discharge with respect to the historical record (1948 - 2008).")}</p>";
                }

	}
function Update_TimeStamp_MP(flag_arrow,flag_timestamp)
	{
	var flag_arrow; //Increase or decrease timestamp
	var flag_timestamp; //Change initial time or final time
	var newtimestamp = new Array(3);
	//Read the current timestamps
	var initial_year = parseInt(document.getElementById("year_initial").value);
        var initial_month = parseInt(document.getElementById("month_initial").value);
        var initial_day = parseInt(document.getElementById("day_initial").value);
	var final_year = parseInt(document.getElementById("year_final").value);
        var final_month = parseInt(document.getElementById("month_final").value);
        var final_day = parseInt(document.getElementById("day_final").value);
	var initial_date = new Date(initial_year,initial_month-1,initial_day);
	var final_date = new Date(final_year,final_month-1,final_day);
	if (flag_timestamp == 0)
		{
		date_temp = initial_date;
		}
	else 
		{
		date_temp = final_date;
		}
	//Find the next or previous timestamp
	if (flag_arrow == 1)
		{
		date_temp.setDate(date_temp.getDate() + 1);
		if (flag_timestamp == 0 && date_temp.valueOf() > final_date.valueOf())
			{
			return;
			}
		newtimestamp = [date_temp.getFullYear(),date_temp.getMonth() + 1,date_temp.getDate()];
		}
	else 
		{
                date_temp.setDate(date_temp.getDate() - 1);
		if (flag_timestamp == 1 && date_temp.valueOf() < initial_date.valueOf())
			{
			return;
			}
                newtimestamp = [date_temp.getFullYear(),date_temp.getMonth() + 1,date_temp.getDate()];
		}
	//Update the time string
	if (flag_timestamp == 0)
		{
		document.getElementById("year_initial").value = newtimestamp[0];
		document.getElementById("month_initial").value = newtimestamp[1];
		document.getElementById("day_initial").value = newtimestamp[2];
		}
	else
		{
		document.getElementById("year_final").value = newtimestamp[0];
		document.getElementById("month_final").value = newtimestamp[1];
		document.getElementById("day_final").value = newtimestamp[2];
		}
	}

function UpdatePopUpTimestep(j)
	{
	var j;
	if(j == 0)//Daily time step
		{
		document.getElementById('time_interval_text').innerHTML = '({$_("dd/mm/yyyy")})';
		document.getElementById('gauge_initial_time').innerHTML = '{$_("Initial Time")}:  <input type="text" size=1 name="gauge_day_initial" value=' + gauge_day_initial + '><input type="text" size=1 name="gauge_month_initial" value=' + gauge_month_initial + '><input type="text" size=3 name="gauge_year_initial" value=' + gauge_year_initial + '>';
		document.getElementById('gauge_final_time').innerHTML = '{$_("Final Time")}:  <input type="text" size=1 name="gauge_day_final" value=' + gauge_day_final + '><input type="text" size=1 name="gauge_month_final" value=' + gauge_month_final + '><input type="text" size=3 name="gauge_year_final" value=' + gauge_year_final + '>';
                timestep_flag = 1;
		SwapGaugeImage(image_type);
		}
	if(j == 1) //Monthly time step
		{
                document.getElementById('time_interval_text').innerHTML = '({$_("mm/yyyy")})';
		document.getElementById('gauge_initial_time').innerHTML = '{$_("Initial Time")}:  <input type="text" size=1 name="gauge_month_initial" value=' + gauge_month_initial_monthly + '><input type="text" size=3 name="gauge_year_initial" value=' + gauge_year_initial_monthly + '>';
		document.getElementById('gauge_final_time').innerHTML = '{$_("Final Time")}:  <input type="text" size=1 name="gauge_month_final" value=' + gauge_month_final_monthly + '><input type="text" size=3 name="gauge_year_final" value=' + gauge_year_final_monthly + '>';
		timestep_flag = 2;
		SwapGaugeImage(image_type);
		}
	}

</script> 

</head> 
<body onload="initialize();" style="height:100%;margin:0">  
	<div class="top">
    		<div class="box">
	 		   <div><table><tbody><tr>
		                <td align="center" right="25%"><img id="UNESCO_logo" src="icons/Unesco_logo.gif"></td>
              			<td align="center" right="32%"><img id="AGRHYMET_logo" src="icons/agrhymet_logo.gif"></td>
               			<td align="center" right="50%" width="50%"> {$_("African Drought Monitor")} </td>
               			<td align="center" left="32%"><img id="PU_logo" src="icons/PU_logo.gif"></td>
                		<td align="center" left="25%"><img id="UW_logo" src="icons/UW_logo.png"></td>
	   		  </tr></tbody></table></div>
  		</div>
	</div>
	<div class='hbar'>
	<table id="nav"><tr><td class="link" onClick="document.location.href='index.php'+window.location.search">{$_("Basic Interface")}</td><td > {$_("Google Maps Interface")}</td><td class="link" onClick="document.location.href='Resources/ADM_Background.pdf'">{$_("Background")}</td><td class="link" onClick="document.location.href='Resources/ADM_Glossary.pdf'">{$_("Glossary")}</td><td class="link" onClick="document.location.href='Resources/Tutorial_HornAfrica.pdf'">{$_("Tutorial")}</td></tr></table>
</div>
	<div id="blanket" style="display:none;"></div>
	<div id="popUpDivparent">
		<div id="popUpDiv" style="display:none;">
			<a href="#" onclick="popup('popUpDiv')"></a>
		</div>
	</div>
	<div id="Region_Placement">
		<select id="BasinSelect" onchange=ChangeBasin(value)>
		  <option value="Title">Select Region...</option>
		  <option value="Congo">Congo Basin</option>
		  <option value="Nile">Nile Basin</option>
		  <option value="Niger">Niger Basin</option>
		  <option value="Senegal">Senegal Basin</option>
		  <option value="Volta">Volta Basin</option>
		</select>
	</div>
    <div id="Language_Selection">
    <select id="LanguageSelect" onchange=ChangeLanguage(value)>
    <option value="Title">Select Language...</option>
    <option value="English">English</option>
    <option value="French">Français</option>
    </select>
	</div>
	<div id="Colorbar" style="visibility:hidden;">
	</div>
	<div id="TimeStamp" style="visibility:hidden;">
	</div>
	<div id="DBandMC">
	</div>
	<div id="Info_Box" style="visibility:hidden;">
	</div>
	<div id="sidebar_call" style="visibility:hidden;">
		  <h1 onclick=animate_sidebar() >	<img src="icons/Arrow_down.png"/> </h1>
	</div>
	<div id="sidebar" style="visibility:visible">	
		<div id="Display_Control"> 
			<h1 id="DC_header" onclick=animate_div("DC_div")>{$_("User Interface")}</h1> 
			<div id="DC_div" style="visibility:visible;">
				<form name="AnimationForm">
					{$_("Time Interval")} ({$_("dd/mm/yyyy")}):<BR>
					{$_("Initial")}: <input id="day_initial" type="text" size=1 name="day_initial" value=$day_initial><input id="month_initial" type="text" size=1 name="month_initial" value=$month_initial><input id="year_initial" type="text" size=3 name="year_initial" value=$year_initial><input type="button" value="-" onclick="Update_TimeStamp_MP(0,0)"><input type="button" value="+" onclick="Update_TimeStamp_MP(1,0)"><BR>
					{$_("Final")}:  <input id="day_final" type="text" size=1 name="day_final" value=$day_final><input id="month_final" type="text" size=1 name="month_final" value=$month_final><input id="year_final" type="text" size=3 name="year_final" value=$year_final><input type="button" value="-" onclick="Update_TimeStamp_MP(0,1)"><input type="button" value="+" onclick="Update_TimeStamp_MP(1,1)"><BR>
					{$_("Days per second")}:  <input type="text" size=1 name="frames_per_second" value=1><BR>
					<table><tr>
					<td><input type="button" value={$_("Update time interval")} onclick="animate_overlay_submit()"></td>
					<td><input type="button" value={$_("Clear all overlays")} onclick="ClearAllOverlays()"></td>
					</tr>
					</table>
					{$_("Image Opacity")}: <input type="button" value="-" onclick="update_overlay_opacity(0)"><input type="button" value="+" onclick="update_overlay_opacity(1)"><BR>
				</form>
			</div>
		</div> 
		<div id="Forcings">
			<h1 id="Forcing_header" onclick=animate_div("Forcing_div")>{$_("Meteorology")} <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(1)" onmouseout="Info_Box_Call(1)"></h1>
			<div id="Forcing_div" style="visibility:visible;">
			<form id="variables_form">
				<input id="overlayImageSelect_5" type="radio" name="group1" value="Prec" onclick=animate_overlay(5)> {$_("Precipitation (mm/day)")} <br />
				<input id="overlayImageSelect_6" type="radio" name="group1" value="Tmax" onclick=animate_overlay(6)> {$_("Maximum Temperature (C)")}<br />
				<input id="overlayImageSelect_7" type="radio" name="group1" value="Temp" onclick=animate_overlay(7)> {$_("Minimum Temperature (C)")} <br />
				<input id="overlayImageSelect_8" type="radio" name="group1" value="Wind" onclick=animate_overlay(8)> {$_("Wind (m/s)")}<br /> 
			</div>
		</div>
		<div id="Model">
			<h1 id="Model_header" onclick=animate_div("Model_div")>{$_("Hydrologic Variables")} <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(2)" onmouseout="Info_Box_Call(2)"></h1>
			<div id="Model_div" style="visibility:visible;">
				<input id="overlayImageSelect_9" type="radio" name="group1" value="Evap" onclick=animate_overlay(9)> {$_("Evaporation (mm/day)")}<br />
				<input id="overlayImageSelect_10" type="radio" name="group1" value="Sm_1" onclick=animate_overlay(10)> {$_("Soil Moisture(%) - Layer 1")}<br />
				<input id="overlayImageSelect_11" type="radio" name="group1" value="Sm_2" onclick=animate_overlay(11)> {$_("Soil Moisture(%) - Layer 2")}<br />
				<input id="overlayImageSelect_14" type="radio" name="group1" value="runoff" onclick=animate_overlay(14)> {$_("Surface Runoff (mm/day)")} <br />
			</div>
		</div>
                <div id="Drought">
                        <h1 id="Drought_header" onclick=animate_div("Drought_div")>{$_("Drought Products")}<img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(3)" onmouseout="Info_Box_Call(3)"></h1>
                        <div id="Drought_div" style="visibility:visible;">
                                <input id="overlayImageSelect_15" input type="radio" name="group1" value="smqall" onclick=animate_overlay(15) checked = "checked" > {$_("Drought Index")} <br />
                        </div>
                </div>
		<div id="Basins">
			<h1 id="Basins_header" onclick=animate_div("Basins_div")>{$_("Catchment Data")} <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(4)" onmouseout="Info_Box_Call(4)"></h1>
			<div id="Basins_div" style="visibility:visible;">
				<input id="overlayImageSelect_1" type="radio" name="group1" value="Basins" onclick=update_markers()> {$_("Stream Gauges")} ($gauge_day_final/$gauge_month_final/$gauge_year_final) <br />
			</form>
			</div>
		</div>
		<h1 id="sidebar_header" onclick=animate_sidebar()><img src="icons/Arrow_up.png"/></h1> 	
</body> 

</html> 
EOF;
echo $main_page
?>
