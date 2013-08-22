function toggle(div_id) {
	var el = document.getElementById(div_id);
	if ( el.style.display == 'none' ) {	el.style.display = 'block';}
	else {el.style.display = 'none';}
}
function blanket_size(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportheight = window.innerHeight;
	} else {
		viewportheight = document.documentElement.clientHeight;
	}
	if ((viewportheight > document.body.parentNode.scrollHeight) && (viewportheight > document.body.parentNode.clientHeight)) {
		blanket_height = viewportheight;
	} else {
		if (document.body.parentNode.clientHeight > document.body.parentNode.scrollHeight) {
			blanket_height = document.body.parentNode.clientHeight;
		} else {
			blanket_height = document.body.parentNode.scrollHeight;
		}
	}
	var blanket = document.getElementById('blanket');
	blanket.style.height = blanket_height + 'px';
	var popUpDiv = document.getElementById(popUpDivVar);
	popUpDiv_height=(viewportheight-600)/2;
	popUpDiv.style.top = popUpDiv_height + 'px';
}
function window_pos(popUpDivVar) {
	if (typeof window.innerWidth != 'undefined') {
		viewportwidth = window.innerHeight;
	} else {
		viewportwidth = document.documentElement.clientHeight;
	}
	if ((viewportwidth > document.body.parentNode.scrollWidth) && (viewportwidth > document.body.parentNode.clientWidth)) {
		window_width = viewportwidth;
	} else {
		if (document.body.parentNode.clientWidth > document.body.parentNode.scrollWidth) {
			window_width = document.body.parentNode.clientWidth;
		} else {
			window_width = document.body.parentNode.scrollWidth;
		}
	}
	var popUpDiv = document.getElementById(popUpDivVar);
	window_width=(window_width-1150)/2;//600 is half popup's width
	if (window_width >= 0)
		{
		popUpDiv.style.left = window_width + 'px';
		}
	else
		{
                popUpDiv.style.left = 0 + 'px';
		}		
}
function popup(windowname,m,contentString,gaugen_temp,gauge_lat_temp,gauge_lon_temp,gauge_area_temp) {
	var flag_popup = 0;
	gaugen = gaugen_temp;
  var gauge_area = gauge_area_temp;
  var gauge_lat = gauge_lat_temp;
  var gauge_lon = gauge_lon_temp;
	blanket_size(windowname);
	window_pos(windowname);
	var el = document.getElementById(windowname);
	if ( el.style.display == 'none' )
		{	
		InitialContentLoad(contentString,gaugen);
		flag_popup = 1
		}
	toggle('blanket');
	toggle(windowname);	
	if (flag_popup == 1)
		{
		image_type = 1; //Begin with discharge plot
		image_string = ds_image_string; //Initial discharge plot
		data_string = ds_data_string; //Initial data associated with discharge plot
		document.getElementById("routed_image").innerHTML = '<table><tr><td><div "class="GaugeImage"><img id = "popup_image" src=' + image_string + '></div></td></tr><tr><td><a id = "popup_image_src" href="' + image_string + '" target="_blank">' + LinktoImage + '</a></tr></td><tr><td><a id = "popup_image_data" href="' + data_string + '" target="_blanck">' + LinktoData + '</a></td></tr></table>';
		/*document.getElementById("routed_image").innerHTML = '<table><tr><td><div "class="GaugeImage"><img id = "popup_image" src=' + image_string + '><h2 class="PlotTitle">' + SimulatedDischarge + '</h2><h2 class="SurplusDischarge">' + SurplusDischarge + ' ' + Qdischarge + '</h2></div></td></tr><tr><td><a id = "popup_image_src" href="' + image_string + '" target="_blank">' + LinktoImage + '</a></tr></td><tr><td><a id = "popup_image_data" href="' + data_string + '" target="_blanck">' + LinktoData + '</a></td></tr></table>';*/
		}
}
function InitialContentLoad(contentString)
	{
	timestep_flag = 1;
	var image_string;
	var string_in_array = [];
	wb_image_string = "Data/ADM_Data/Realtime/WB_plots/wb_" + gaugen + ".png";
	ds_image_string = "Data/ADM_Data/Realtime/DS_plots/ds_" + gaugen + ".png";
        sm_image_string = "Data/ADM_Data/Realtime/SM_plots/sm_" + gaugen + ".png";
	wb_monthly_image_string = "Data/ADM_Data/Realtime/Monthly_WB_plots/wb_monthly_" + gaugen + ".png";
	ds_monthly_image_string = "Data/ADM_Data/Realtime/Monthly_DS_plots/ds_monthly_" + gaugen + ".png";
        sm_monthly_image_string = "Data/ADM_Data/Realtime/Monthly_SM_plots/sm_monthly_" + gaugen + ".png";
	ds_data_string = "Data/ADM_Data/Realtime/DS_data/gauge_" + gaugen + ".txt";
	wb_data_string = "Data/ADM_Data/Realtime/WB_data/gauge_fluxes_" + gaugen + ".txt"; 
        ds_monthly_data_string = "Data/ADM_Data/Realtime/Monthly_DS_data/gauge_" + gaugen + ".txt";
        wb_monthly_data_string = "Data/ADM_Data/Realtime/Monthly_WB_data/gauge_fluxes_" + gaugen + ".txt";
        gauge_day_initial = gauge_day_initial_orig; 
        gauge_month_initial = gauge_month_initial_orig;
	gauge_year_initial = gauge_year_initial_orig;
        gauge_year_final = gauge_year_final_orig;
        gauge_month_final = gauge_month_final_orig;
        gauge_day_final = gauge_day_final_orig;
        gauge_year_initial_monthly = gauge_year_initial_monthly_orig;
        gauge_month_initial_monthly = gauge_month_initial_monthly_orig;
        gauge_year_final_monthly = gauge_year_final_monthly_orig;
        gauge_month_final_monthly = gauge_month_final_monthly_orig;

	var string_out = "data=" + gauge_year_initial + ' ' + gauge_month_initial + ' ' + gauge_year_final + ' ' + gauge_month_final + ' ' + gaugen;
	document.getElementById('popUpDiv').innerHTML = contentString;
	/*document.getElementById('gauge_initial_time').innerHTML = 'Initial Time:  <input type="text" size=3 name="gauge_year_initial" value=' + gauge_year_initial + '><input type="text" size=1 name="gauge_month_initial" value=' + gauge_month_initial + '><input type="text" size=1 name="gauge_day_initial" value=' + gauge_day_initial + '>';*/
	document.getElementById('gauge_initial_time').innerHTML = 'Initial Time:  <input type="text" size=1 name="gauge_day_initial" value=' + gauge_day_initial + '><input type="text" size=1 name="gauge_month_initial" value=' + gauge_month_initial + '><input type="text" size=3 name="gauge_year_initial" value=' + gauge_year_initial + '>';
	document.getElementById('gauge_final_time').innerHTML = 'Final Time:  <input type="text" size=1 name="gauge_day_final" value=' + gauge_day_final + '><input type="text" size=1 name="gauge_month_final" value=' + gauge_month_final + '><input type="text" size=3 name="gauge_year_final" value=' + gauge_year_final + '>';
        /*document.getElementById('gauge_final_time').innerHTML = 'Final Time:  <input type="text" size=3 name="gauge_year_final" value=' + gauge_year_final + '><input type="text" size=1 name="gauge_month_final" value=' + gauge_month_final + '><input type="text" size=1 name="gauge_day_final" value=' + gauge_day_final + '>';*/

	}	

function SwapGaugeImage(image_type_temp)
	{
	var image_type_temp;
	image_type = image_type_temp;
	if (image_type == 1) //Discharge plot
		{
		if (timestep_flag == 1)
			{
			image_string = ds_image_string;
			data_string = ds_data_string;
			}
		else
			{ 
                        image_string = ds_monthly_image_string;
                        data_string = ds_monthly_data_string;
			}
		document.getElementById("popup_image").src = image_string;
		document.getElementById("popup_image_src").href = image_string;
		document.getElementById("popup_image_data").href = data_string; 
		}
	if (image_type == 2) //Water balance
		{
                if (timestep_flag == 1)
                        {
                        image_string = wb_image_string;
                        data_string = wb_data_string;
                        }
                else
                        {                        
                        image_string = wb_monthly_image_string;
                        data_string = wb_monthly_data_string;
                        }
		document.getElementById("popup_image").src = image_string;
                document.getElementById("popup_image_src").href = image_string;
		document.getElementById("popup_image_data").href = data_string;
		}
        if (image_type == 3) //Soil Moisture
                {
                if (timestep_flag == 1)
                        {
                        image_string = sm_image_string;
                        data_string = wb_data_string;
                        }
                else
                        {                        
                        image_string = sm_monthly_image_string;
                        data_string = wb_monthly_data_string;
                        }
                document.getElementById("popup_image").src = image_string;
                document.getElementById("popup_image_src").href = image_string;
                document.getElementById("popup_image_data").href = data_string;
                }

	}	

function GaugeProcess()
	{
	if (timestep_flag == 1)
		{
		document.getElementById("PLflag").innerHTML = "";
		document.forms["GaugeTimeInterval"]["GaugeProcessButton"].value = ProcessRPW;
		var gauge_number = gaugen;
		var month_initial = document.forms["GaugeTimeInterval"]["gauge_month_initial"].value;
		gauge_month_initial = month_initial;
		var year_initial = document.forms["GaugeTimeInterval"]["gauge_year_initial"].value;
		gauge_year_initial = year_initial;
		var day_initial = document.forms["GaugeTimeInterval"]["gauge_day_initial"].value;
		gauge_day_initial = day_initial;
		var month_final = document.forms["GaugeTimeInterval"]["gauge_month_final"].value
		gauge_month_final = month_final;
		var year_final = document.forms["GaugeTimeInterval"]["gauge_year_final"].value;
		gauge_year_final = year_final;
		var day_final = document.forms["GaugeTimeInterval"]["gauge_day_final"].value;
		gauge_day_final = day_final;
		var image_string;
		var string_in_array = [];
		var string_out = "data=" + year_initial + ',' + month_initial + ',' + day_initial + ',' + year_final + ',' + month_final + ',' + day_final + ',' + gauge_number + ',' + gauge_lat + ',' + gauge_lon + ',' + gauge_area + ',' + PlotTitle + ',' + PlotYlabel + ',' + PlotXlabel;
		var ajax_request = new ajaxRequest();
		ajax_request.open("GET","scripts/Gauges_Scripts/Prepare_Gauge_Images.php?"+ string_out, true);
		ajax_request.onreadystatechange = function()
			{
			if(ajax_request.readyState == 4)
				{
				image_string=this.responseText;
			        document.forms["GaugeTimeInterval"]["GaugeProcessButton"].value = "Process new time interval";	
				string_in_array = image_string.split(" ",5);	
      				ds_image_string = string_in_array[0];
				wb_image_string = string_in_array[2];
				ds_data_string = string_in_array[1];
				wb_data_string = string_in_array[3];
				sm_image_string = string_in_array[4];
				if (image_type == 1)
					{
					document.getElementById("popup_image").src = ds_image_string;
					document.getElementById("popup_image_src").href = ds_image_string;
					document.getElementById("popup_image_data").href = ds_data_string;
					}
				if (image_type == 2)
					{
                			document.getElementById("popup_image").src = wb_image_string;
               				document.getElementById("popup_image_src").href = wb_image_string;
                        	     	document.getElementById("popup_image_data").href = wb_data_string;
					}
                       		if (image_type == 3)
                               		 {
                                	document.getElementById("popup_image").src = sm_image_string;
                                	document.getElementById("popup_image_src").href = sm_image_string;
                                	document.getElementById("popup_image_data").href = wb_data_string;
					}
			 	document.forms["GaugeTimeInterval"]["GaugeProcessButton"].value = ProcessNTI;
				}
			}
		ajax_request.send(null);
		}
	if (timestep_flag == 2)
		{
                document.getElementById("PLflag").innerHTML = "";
 		document.forms["GaugeTimeInterval"]["GaugeProcessButton"].value = ProcessRPW;
                var gauge_number = gaugen;
                var month_initial = document.forms["GaugeTimeInterval"]["gauge_month_initial"].value;
                gauge_month_initial_monthly = month_initial;
                var year_initial = document.forms["GaugeTimeInterval"]["gauge_year_initial"].value;
                gauge_year_initial_monthly = year_initial;
                var month_final = document.forms["GaugeTimeInterval"]["gauge_month_final"].value
                gauge_month_final_monthly = month_final;
                var year_final = document.forms["GaugeTimeInterval"]["gauge_year_final"].value;
                gauge_year_final_monthly = year_final;
                var image_string;
                var string_in_array = [];
                var string_out = "data=" + year_initial + ',' + month_initial  + ',' + year_final + ',' + month_final  + ',' + gauge_number + ',' + gauge_lat + ',' + gauge_lon + ',' + gauge_area + ',' + PlotTitle + ',' + PlotYlabel + ',' + PlotXlabel_Month;
                var ajax_request = new ajaxRequest();
                ajax_request.open("GET","scripts/Gauges_Scripts/Prepare_Monthly_Gauge_Images.php?"+ string_out, true);
                ajax_request.onreadystatechange = function()
                        {
                        if(ajax_request.readyState == 4)
                                {
                                image_string=this.responseText;
                                document.forms["GaugeTimeInterval"]["GaugeProcessButton"].value = ProcessNTI;
                                string_in_array = image_string.split(" ",5);
                                ds_monthly_image_string = string_in_array[0];
                                wb_monthly_image_string = string_in_array[2];
                                ds_monthly_data_string = string_in_array[1];
                                wb_monthly_data_string = string_in_array[3];
                                sm_monthly_image_string = string_in_array[4];
                                if (image_type == 1)
                                        {
                                        document.getElementById("popup_image").src = ds_monthly_image_string;
                                        document.getElementById("popup_image_src").href = ds_monthly_image_string;
                                        document.getElementById("popup_image_data").href = ds_monthly_data_string;
                                        }
                                if (image_type == 2)
                                        {
                                        document.getElementById("popup_image").src = wb_monthly_image_string;
                                        document.getElementById("popup_image_src").href = wb_monthly_image_string;
                                        document.getElementById("popup_image_data").href = wb_monthly_data_string;
                                        }
                                if (image_type == 3)
                                         {
                                        document.getElementById("popup_image").src = sm_monthly_image_string;
                                        document.getElementById("popup_image_src").href = sm_monthly_image_string;
                                        document.getElementById("popup_image_data").href = wb_monthly_data_string;
                                        }
                                document.forms["GaugeTimeInterval"]["GaugeProcessButton"].value = ProcessNTI;
                                }
                        }
                ajax_request.send(null);
                }

	}

/* The update_markers function won't work unless it's run through PHP first
It's included here in case it helps future efforts with the popup window.
function update_markers()
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

      var contentString = [];
      var icon_image;

      cbar = document.getElementById("Colorbar").style;
      cbar.visibility = "visible";
      cbar.height = "100";
      cbarcontentString = "<img src=Data/Colorbar/PercentilesClasses.png></img>";
      document.getElementById('Colorbar').innerHTML = cbarcontentString;
      
      for (m=0; m < <?php echo $ngauges ?>; m++)
      {
        var myLatLng = new google.maps.LatLng(gauge_lat[m], gauge_lon[m]);

        // NOTE: This next line ... should probably be revisited soon :)
        // I can't unravel it all right now. What are best practices for filling element content using javascript?
        contentString[m] = <?php echo "\"<div id='PopUpControl' onclick=popup('popUpDiv')><B>{$_("CLOSE WINDOW")}</B></div><table><tr><td><div><table><tr><td><div id='CatchmentInfo'><table><tr><td><B> {$_("Gauge number")}: </B>\""?> + gauge_number[m] + <?php echo "\"<br><B>{$_("Latitude")}: </B>\""?> + gauge_lat[m] + <?php echo "\" <B>{$_("Longitude")}: </B>\""?> + gauge_lon[m]  + <?php echo "\"<br><B>{$_("Catchment area")}: </B>\""?> + sprintf('%.2f',gauge_area[m]*1.609344*1.609344) + <?php echo "\" km2</td></tr></table></div></td></tr></div></td></tr><tr><td><div id='routed_image'><img src=''></div></td><td><div id='BasinForms'><div id='gaugevariableform'><B>  {$_("Variable Selection")}: </B><br><br><form name='gaugevariableform'><input id='Discharge' type='radio' name='gaugevariableform' value='Discharge' onclick='SwapGaugeImage(1)' checked='checked'> {$_("Discharge at gauge")}<br><input id='WaterBalance' type='radio' name='gaugevariableform' value='WaterBalance' onclick='SwapGaugeImage(2)'> {$_("Basin water balance")}<br><input id='SoilMoisture' type='radio' name='gaugevariableform' value='SoilMoisture' onclick='SwapGaugeImage(3)'> {$_("Soil Moisture")}<br></form><div id='timestepform'><B> {$_("Time Step")}: </B><br><br><form name='timestepform'><input id='daily' type='radio' name='timestep' value='daily' onclick='UpdatePopUpTimestep(0)' checked='checked'> {$_("Daily")}<br><input id='monthly' type='radio' name='timestep' value='monthly' onclick='UpdatePopUpTimestep(1)'> {$_("Monthly")}<br></form></div><div id='GaugeTimeInterval'><B> {$_("Time Interval")} <i id='time_interval_text'> ({$_("dd/mm/yyyy")})</i>: </B><br><br><form name='GaugeTimeInterval'><div id='gauge_initial_time'></div><BR><div id='gauge_final_time'></div><BR><input type='button' name='GaugeProcessButton' value='{$_("Process new time interval")}' onclick='GaugeProcess()'></form><BR><p id='PLflag'>{$_("Note: To update the plot to your selected language press the button above")}.</p></div></div></td></tr><tr><td><div id='GaugeDownloadLinks'></div></td></tr></table>\"" ?>;
        
        if (gauge_percentile[m] < 1) icon_image = "icons/gauges_percentiles/dot0.svg";
        else if (gauge_percentile[m] < 10) icon_image = "icons/gauges_percentiles/dot1.png";
        else if (gauge_percentile[m] < 25) icon_image = "icons/gauges_percentiles/dot2.png";
        else if (gauge_percentile[m] < 75) icon_image = "icons/gauges_percentiles/dot3.png";
        else if (gauge_percentile[m] < 90) icon_image = "icons/gauges_percentiles/dot4.png";
        else if (gauge_percentile[m] < 99) icon_image = "icons/gauges_percentiles/dot5.png";
        else icon_image = "icons/gauges_percentiles/dot6.png";
        
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
  }*/





	
