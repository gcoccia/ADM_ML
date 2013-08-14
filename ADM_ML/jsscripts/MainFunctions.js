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
    	SPIselect(0);
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
		contentString[m] = "<div id='PopUpControl' onclick=\"popup(\'popUpDiv\')\"><B>{$_("CLOSE WINDOW")}</B></div><table><tr><td><div><table><tr><td><div id='CatchmentInfo'><table><tr><td><B> {$_("Gauge number")}: </B>" + gauge_number[m] + "<br><B>{$_("Latitude")}: </B>" + gauge_lat[m] + " <B>{$_("Longitude")}: </B>" + gauge_lon[m]  + "<br><B>{$_("Catchment area")}: </B>" + sprintf('%.2f',gauge_area[m]*1.609344*1.609344) + " km2</td></tr></table></div></td></tr></div></td></tr><tr><td><div id='routed_image'><img src=''></div></td><td><div id='BasinForms'><div id='gaugevariableform'><B>  {$_("Variable Selection")}: </B><br><br><form name='gaugevariableform'><input id='Discharge' type='radio' name='gaugevariableform' value='Discharge' onclick='SwapGaugeImage(1)' checked='checked'> {$_("Discharge at gauge")}<br><input id='WaterBalance' type='radio' name='gaugevariableform' value='WaterBalance' onclick='SwapGaugeImage(2)'> {$_("Basin water balance")}<br><input id='SoilMoisture' type='radio' name='gaugevariableform' value='SoilMoisture' onclick='SwapGaugeImage(3)'> {$_("Soil Moisture")}<br></form><div id='timestepform'><B> {$_("Time Step")}: </B><br><br><form name='timestepform'><input id='daily' type='radio' name='timestep' value='daily' onclick='UpdatePopUpTimestep(0)' checked='checked'> {$_("Daily")}<br><input id='monthly' type='radio' name='timestep' value='monthly' onclick='UpdatePopUpTimestep(1)'> {$_("Monthly")}<br></form></div><div id='GaugeTimeInterval'><B> {$_("Time Interval")} <i id='time_interval_text'> ({$_("dd/mm/yyyy")})</i>: </B><br><br><form name='GaugeTimeInterval'><div id='gauge_initial_time'></div><BR><div id='gauge_final_time'></div><BR><input type='button' name='GaugeProcessButton' value='{$_("Process new time interval")}' onclick='GaugeProcess()'></form><BR><p id='PLflag'>{$_("Note: To update the plot to your selected language press the button above")}.</p></div></div></td></tr><tr><td><div id='GaugeDownloadLinks'></div></td></tr></table>"; 
		if (gauge_percentile[m] < 1)icon_image = "icons/gauges_percentiles/dot0.svg";
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
function animate_overlay(j,time_flag)
{
var j;
var time_flag;
if (j < 17 | j > 20){SPIselect(0)};
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
if (time_flag == "SPI")ImageArrayPrep_SPI(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
else ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
//Add the new overlay 
var fcnstr;
var i = 0;
update_colorbar(j);
update_overlay_animate(j,0);
    update_logo(j);
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
    SPIselect(0);
update_logo(0);

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
   			  if (j >= 17 & j <= 20)ImageArrayPrep_SPI(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
            	  else ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
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
    else if (language == 'Chinese') { locale_val = escape('cn');}
else if (language == 'Spanish') { locale_val = escape('sp');}
    else if (language == 'Arabic') { locale_val = escape('ar');}

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
    if (data_type == 5){        document.getElementById("Info_Box").innerHTML = "<p>{$_("This is the SMOS CATDS L4 Root zone soil moisture index. The product is obtained from the integration of SMOS surface soil moisture L3 products into a double bucket hydrological model. It represents the soil moisture in the first meters of the soil in percentage.")}</p>";
            }
if (data_type == 6){        document.getElementById("Info_Box").innerHTML = "<p>{$_("The SPI is an index based on the probability of recording a given amount of precipitation after standardizing the probabilities so that an index of zero indicates the median precipitation amount for the entire precipitation record. The SPI can be calculated at any time step. The index is negative for drought, and positive for wet conditions.")}</p>";
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
function SPIselect(flag)
{
if (flag == 1){
animate_overlay(17,"SPI");
document.getElementById('SPIdiv').innerHTML = 'SPI <select id="SPI_form" onchange=animate_overlay(value,"SPI")><option id="overlayImageSelect_17" value="17">1 month</option><option id="overlayImageSelect_18" value="18">3 months</option><option id="overlayImageSelect_19" value="19">6 months</option><option id="overlayImageSelect_20" value="20">12 months</option></select><img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(6)" onmouseout="Info_Box_Call(6)">'
}
else{
    document.getElementById('SPIdiv').innerHTML = 'SPI <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(6)" onmouseout="Info_Box_Call(6)">'
}
}

function update_logo(j)
    {
    var obj;
    obj = document.getElementById("Logo").style;
    if (obj.visibility == "visible")
            {
            obj.visibility = "hidden";
            obj.height = "";
            }
    if (j == 16){
        if(obj.visibility == "hidden")
            {
            obj.visibility = "visible";
            obj.height = "100";
            contentString = "<img src="+Logo_Images[j]+"></img>";
            document.getElementById('Logo').innerHTML = contentString;
            }
         else if (obj.visibility == "visible")
            {
            obj.visibility = "hidden";
            obj.height = "";
            }
   }

    }
