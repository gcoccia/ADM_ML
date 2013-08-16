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
	var gaugen = gaugen_temp;
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





	
