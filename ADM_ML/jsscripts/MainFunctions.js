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
ImageRootArray[16] = "/SMOS_SW2F_GMaps/SMOS_SW2F_";
ImageRootArray[17] = "/SPI_1month_GMaps/SPI_1month_";
ImageRootArray[18] = "/SPI_3month_GMaps/SPI_3month_";
ImageRootArray[19] = "/SPI_6month_GMaps/SPI_6month_";
ImageRootArray[20] = "/SPI_12month_GMaps/SPI_12month_";
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
Colorbar_Images[16] = "Data/Colorbar/colorbar_SMOS_SW2F.png";
Colorbar_Images[17] = "Data/Colorbar/colorbar_SPI.png";
Colorbar_Images[18] = "Data/Colorbar/colorbar_SPI.png";
Colorbar_Images[19] = "Data/Colorbar/colorbar_SPI.png";
Colorbar_Images[20] = "Data/Colorbar/colorbar_SPI.png";
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
var Logo_Images = [];
Logo_Images[16] = "icons/smos_logo.png";
var myVariable;
	
function clearMarkers() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
  }
}

function initialize() 
{
	var myMapCenter = new google.maps.LatLng(-10, 30);
	var styleArray = [{featureType: 'administrative.country',stylers: [{ visibility: 'simplified' }]}];

	var myOptions = {styles: styleArray,zoom: 3,center: myMapCenter,panControl: false,zoomControl: true,zoomControlOptions:{style:    	
	google.maps.ZoomControlStyle.DEFAULT,position: google.maps.ControlPosition.LEFT_TOP},scaleControl: false,streetViewControl: false,mapTypeControl: 
	true,mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,position: google.maps.ControlPosition.TOP_LEFT},mapTypeId: 
	google.maps.MapTypeId.TERRAIN};

	//Insert the map canvas into html
	$("#DBandMC").html('<div id="map_canvas_1' + '" style="width:' + wpercent + '%; height:' + hpercent + '%;"></div>');
	map_array[0] = new google.maps.Map($("map_canvas_1", myOptions);
	var swBound = new google.maps.LatLng(-35.000, -19.000);
	var neBound = new google.maps.LatLng(38.000, 55.000);
	bounds = new google.maps.LatLngBounds(swBound, neBound);
	animate_overlay(15) //Load the drought index map from the start
}

function imageLoaded()
	{
	ImageLoadedBoolean = true;
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
	}
	
function animate_overlay(j,time_flag)
{
	var j;
	var time_flag;
	if (j < 17 | j > 20);
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
	
/*function SPIselect(flag)
	{
	if (flag == 1){
	animate_overlay(17,"SPI");
	document.getElementById('SPIdiv').innerHTML = 'SPI <select id="SPI_form" onchange=animate_overlay(value,"SPI")><option id="overlayImageSelect_17" value="17">1 month</option><option id="overlayImageSelect_18" value="18">3 months</option><option id="overlayImageSelect_19" value="19">6 months</option><option id="overlayImageSelect_20" value="20">12 months</option></select><img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(6)" onmouseout="Info_Box_Call(6)">';
	}
	else{
	    document.getElementById('SPIdiv').innerHTML = 'SPI <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(6)" onmouseout="Info_Box_Call(6)">';
	}
	}*/

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
	