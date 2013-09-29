var mapPolygon = null,
    followLine1 = null,
    followLine2 = null;

function Update_Listeners(type){

 if (type == 'none'){
  // Revert to the animation sidebar, and hide the others
  $("#Animation-Sidebar").show();
  $("#Point-Sidebar").hide();
  $("#Spatial-Sidebar").hide();
  $("#monitor-or-forecast-div").show();
  $("#hideBtnImg").show();
   
  //Set up time info accordingly
  update_monitor_or_forecast();

  //Remove the listeners and lines/polygons from the map
  if(mapPolygon) {
    mapPolygon.stopEdit();
    mapPolygon.setMap(null);
    google.maps.event.clearListeners(mapPolygon, "click");
  }
  if(followLine1) followLine1.setMap(null);
  if(followLine2) followLine2.setMap(null);
  google.maps.event.clearListeners(map_array[0], "click");
  google.maps.event.clearListeners(map_array[0], "mousemove");
  google.maps.event.clearListeners(map_array[0], "rightclick");
  map_array[0].setOptions({draggableCursor:null});
 
  //Remove Popup if it is open
  if ($("#pointpill").hasClass("active")) {
      Hide_Data_Extraction_Popup();
  }
}
 else if (type == 'point'){
  //Remove present listeners
  clear_all_overlays();
  // Turn off the active chosen datasets
  $("ul.datalist>li").removeClass("active");
  $("ul.datalist>li>ul.dropdown-menu>li").removeClass("active");
  $("ul.datalist>li>a>i").removeClass("icon-ok");
  $("ul.datalist>li>ul.dropdown-menu>li>a>i").removeClass("icon-ok");
  Update_Listeners('none');
  map_array[0].setOptions({draggableCursor:'crosshair'});
  // Make sure the time info is present
  $("#final-date-inputs").show();
  $("#initial-date-inputs").show();
  $("#Animation-Update").show();
  var sample_dataset = 'VIC_DERIVED--vcpct';
  var final_date = new Date(data_fdates[sample_dataset]);
  final_date.setDate(final_date.getDate() + 7);
  var initial_date = new Date(final_date);
  initial_date.setDate(initial_date.getDate() - 30); 
  $("#year_initial").val(initial_date.getFullYear());
  $("#month_initial").val(initial_date.getMonth() + 1);
  $("#day_initial").val(initial_date.getDate());
  $("#year_final").val(final_date.getFullYear());
  $("#month_final").val(final_date.getMonth() + 1);
  $("#day_final").val(final_date.getDate());
  // Switch to the point sidebar
  $("#Animation-Sidebar").hide();
  $("#Point-Sidebar").show();
  $("#Spatial-Sidebar").hide();
  $("#monitor-or-forecast-div").hide();
  $("#hideBtnImg").hide();

  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', function(mEvent) {Point_Data(mEvent.latLng)});
  google.maps.event.addListener(map_array[0], 'mousemove', function(point) {
    $("#point-latitude").html(point.latLng.lat());
    $("#point-longitude").html(point.latLng.lng());
  });
 }
 else if (type == 'spatial'){

  //Remove present listeners
  clear_all_overlays();
  Update_Listeners('none');
  // Turn off the active chosen datasets
  $("ul.datalist>li").removeClass("active");
  $("ul.datalist>li>ul.dropdown-menu>li").removeClass("active");
  $("ul.datalist>li>a>i").removeClass("icon-ok");
  $("ul.datalist>li>ul.dropdown-menu>li>a>i").removeClass("icon-ok");

  // Switch to the spatial sidebar
  $("#Animation-Sidebar").hide();
  $("#Point-Sidebar").hide();
  $("#Spatial-Sidebar").show();
  $("#monitor-or-forecast-div").hide();
  $("#hideBtnImg").hide();

  // Ensure the time info is present
  $("#final-date-inputs").show();
  $("#initial-date-inputs").show();
  $("#Animation-Update").show();
  var sample_dataset = 'VIC_DERIVED--vcpct';
  var final_date = new Date(data_fdates[sample_dataset]);
  $("#year_initial").val(final_date.getFullYear());
  $("#month_initial").val(final_date.getMonth() + 1);
  $("#day_initial").val(final_date.getDate());
  $("#year_final").val(final_date.getFullYear());
  $("#month_final").val(final_date.getMonth() + 1);
  $("#day_final").val(final_date.getDate());

  // Remove forecast variables for now
  $("li#Forecast_spatial").parent().hide();

  map_array[0].setOptions({draggableCursor:'crosshair'});
  // Add polygon and lines to map
  var polyOptions = { map : map_array[0],
                    strokeColor   : '#08c',
                    strokeOpacity : 0.6,
                    strokeWeight  : 4,
                    path:[]
                  };
  var lineOptions = { clickable: false,
                    map : map_array[0],
                    path: [],
                    strokeColor: "#787878",
                    strokeOpacity: 1,
                    strokeWeight: 2
                  };
  mapPolygon = new google.maps.Polygon(polyOptions);
  followLine1 = new google.maps.Polyline(lineOptions);
  followLine2 = new google.maps.Polyline(lineOptions);

  // Add event handlers related to polygon drawing
  google.maps.event.addListener(map_array[0], 'click', function(point) {
       mapPolygon.stopEdit();
       mapPolygon.getPath().push(point.latLng);
       mapPolygon.runEdit(true);
       Update_Spatial_Data_Display();
  });
     
  google.maps.event.addListener(map_array[0], 'rightclick', function () {
    followLine1.setMap(null);
    followLine2.setMap(null);
    google.maps.event.clearListeners(map_array[0], "click");
    google.maps.event.clearListeners(map_array[0], "mousemove");
    google.maps.event.clearListeners(map_array[0], "rightclick");
    map_array[0].setOptions({draggableCursor:null});
    Update_Spatial_Data_Display();
  });
     
  google.maps.event.addListener(map_array[0], 'mousemove', function(point) {
    var pathLength = mapPolygon.getPath().getLength();
    if (pathLength >= 1) {
      var startingPoint1 = mapPolygon.getPath().getAt(pathLength - 1);
      var followCoordinates1 = [startingPoint1, point.latLng];
      followLine1.setPath(followCoordinates1);
      var startingPoint2 = mapPolygon.getPath().getAt(0);
      var followCoordinates2 = [startingPoint2, point.latLng];
      followLine2.setPath(followCoordinates2);
    }
  });

  google.maps.event.addListener(mapPolygon.getPath(), 'set_at', function(point) {
    Update_Spatial_Data_Display();
  });

 //Initialize errors
 Update_Spatial_Data_Display()

 }
}

function Point_Data(latLng){
  //Create the popup
  $("#blanket").show();
  $("#popUpDiv").show();
  $("#hideBtn").hide();
  Create_Point_Plot();
}

function Hide_Data_Extraction_Popup() {
  $('#popup_container').empty();
  $("#blanket").hide();
  $("#popUpDiv").hide();
  $("#hideBtn").show();
}

function Create_Point_Plot() {

  var variables, subtitle;
  var Create_Text_Data = $('input:radio[name=Create_Text_Data]:checked').val();
  var plot = $('input:radio[name=plot]:checked').val();
  if (plot == "Indices"){
    var chart_data = {
     spi1:{units:'SPI',name:'SPI (1 month)',datasets:['SPI','GFS_7DAY_FORECAST','MultiModel'],},
     spi3:{units:'SPI',name:'SPI (3 months)',datasets:['SPI','GFS_7DAY_FORECAST','MultiModel'],},
     spi6:{units:'SPI',name:'SPI (6 months)',datasets:['SPI','GFS_7DAY_FORECAST','MultiModel'],},
     spi12:{units:'SPI',name:'SPI (12 months)',datasets:['SPI','GFS_7DAY_FORECAST','MultiModel'],},
     vcpct:{units:'Percentile (%)',name:'Soil Moisture Index (%)',datasets:['VIC_DERIVED','GFS_7DAY_FORECAST'],},
     flw_pct:{units:'Percentile (%)',name:'Streamflow Index (%)',datasets:['ROUTING_VIC_DERIVED','GFS_7DAY_FORECAST'],},
     pct30day:{units:'Percentile (%)',name:'Vegetation Index (%)',datasets:['MOD09_NDVI_MA_DERIVED'],},
     t2ano:{units:'Temperature Anomaly (C)',name:'Temperature Anomaly (C)',datasets:['MultiModel'],},
    }
    var chart_controls = {
     title: {text: "Indices",}
    }
    /*
     SPI:{
      'spi1':{'units':TRANSLATE['SPI'],'name':TRANSLATE['SPI_1_month)']},
      'spi3':{'units':TRANSLATE['SPI'],'name':TRANSLATE['SPI_3_months)']},
      'spi6':{'units':TRANSLATE['SPI'],'name':TRANSLATE['SPI_6_months)']},
      'spi12':{'units':TRANSLATE['SPI'],'name':TRANSLATE['SPI_12_months)']},
     },
     VIC_DERIVED:{
      'vcpct':{'units':TRANSLATE['Percentile'],'name':TRANSLATE['Soil_Moisture_Index']},
     },
     ROUTING_VIC_DERIVED:{
      'flw_pct':{'units':TRANSLATE['Percentile'],'name':TRANSLATE['Streamflow_Index']},
     },
     MOD09_NDVI_MA_DERIVED:{
      'pct30day':{'units':TRANSLATE['Percentile'],'name':TRANSLATE['Vegetation_Index']},
     }
    };
    var chart_controls = {
     title: {text: TRANSLATE["Drought_Indices"]}
    };
    */
  }
  else if (plot == "Water_Balance"){
    var chart_data = {
     prec:{units:'mm/day',name:'Precipitation',datasets:['PGF','3B42RT_BC','GFS_7DAY_FORECAST'],},
     evap:{units:'mm/day',name:'Evaporation',datasets:['VIC_PGF','VIC_3B42RT','GFS_7DAY_FORECAST'],},
     runoff:{units:'mm/day',name:'Runoff',datasets:['VIC_PGF','VIC_3B42RT','GFS_7DAY_FORECAST'],},
     baseflow:{units:'mm/day',name:'Baseflow',datasets:['VIC_PGF','VIC_3B42RT','GFS_7DAY_FORECAST'],},
    }
    var chart_controls = {
     title: {text: "Water Balance",}
    }
  }
  else if (plot == "Meteorology"){
    var chart_data = {
     prec:{units:'mm/day',name:'Precipitation (mm/day)',datasets:['PGF','3B42RT_BC','GFS_7DAY_FORECAST'],},
     tmax:{units:'K',name:'Daily Maximum Temperature (K)',datasets:['PGF','GFS_ANALYSIS_BC','GFS_7DAY_FORECAST'],},
     tmin:{units:'K',name:'Daily Minimum Temperature (K)',datasets:['PGF','GFS_ANALYSIS_BC','GFS_7DAY_FORECAST'],},
     wind:{units:'m/s',name:'Daily Average Wind Speed (m/s)',datasets:['PGF','GFS_ANALYSIS_BC','GFS_7DAY_FORECAST'],},
    }
    var chart_controls = {
     title: {text: "Meteorology",}
    }
  }
  else if (plot == "Surface_Fluxes"){
    var chart_data = {
     net_short:{units:'W/m2',name:'Net Shortwave',datasets:['VIC_PGF','VIC_3B42RT','GFS_7DAY_FORECAST'],},
     net_long:{units:'W/m2',name:'Net Longwave',datasets:['VIC_PGF','VIC_3B42RT','GFS_7DAY_FORECAST'],},
     r_net:{units:'W/m2',name:'Net Radiation',datasets:['VIC_PGF','VIC_3B42RT','GFS_7DAY_FORECAST'],},
    }
    var chart_controls = {
     title: {text: "Surface Fluxes",}
    }
  }
  else if (plot == "Streamflow"){
    var chart_data = {
     flw:{units:'m3/s',name:'Streamflow (m3/s)',datasets:['ROUTING_VIC_PGF','ROUTING_VIC_3B42RT','GFS_7DAY_FORECAST'],},
     flw_pct:{units:'%',name:'Streamflow Index (%)',datasets:['ROUTING_VIC_DERIVED','GFS_7DAY_FORECAST'],},
    }
    var chart_controls = {
     title: {text: "Streamflow",}
    }
  }
  else if (plot == "Soil_Moisture"){
    var chart_data = {
     vc1:{units:'%',name:'Soil Moisture (%) - Layer 1',datasets:['VIC_DERIVED','GFS_7DAY_FORECAST'],},
     vc2:{units:'%',name:'Soil Moisture (%) - Layer 2',datasets:['VIC_DERIVED','GFS_7DAY_FORECAST'],},
     vcpct:{units:'%',name:'Soil Moisture Index (%)',datasets:['VIC_DERIVED','GFS_7DAY_FORECAST'],},
    }
    var chart_controls = {
     title: {text: "Soil Moisture",}
    }
  }
  else if (plot == "Vegetation"){
    var chart_data = {
     ndvi30:{units:'NDVI',name:'NDVI',datasets:['MOD09_NDVI_MA'],},
     pct30day:{units:'%',name:'Vegetation Index (%)',datasets:['MOD09_NDVI_MA_DERIVED'],},
    }
    var chart_controls = {
     title: {text: "Vegetation",}
    }
  };
  subtitle = TRANSLATE[plot];
 
 //Request data for these variables
 var Output = Request_Data(chart_data,Create_Text_Data,plot); 
 //If we have requested the data display the link
 point_data_link = Output['point_data_link']
 if (Create_Text_Data == 'no'){
  $("#point_data_link").hide();
 }
 else{
  $("#point_data_link").show();
  $("#point_data_link").attr("href",point_data_link);
 }

 //Define forecast dates
 var tstep = $("ul.ts-selection li.active").attr('id').toUpperCase(); // "daily", "monthly" or "yearly"
 if (tstep == 'DAILY'){
  var sample_dataset = 'VIC_DERIVED--vcpct';
  var final_date = new Date(data_fdates[sample_dataset]);
  var initial_date = new Date();
  initial_date.setDate(final_date.getDate()+1);
  final_date.setDate(final_date.getDate()+7);
  var initial_date = Date.UTC(initial_date.getFullYear(),initial_date.getMonth(),initial_date.getDate());
  var final_date = Date.UTC(final_date.getFullYear(),final_date.getMonth(),final_date.getDate());
 }
 else if (tstep == 'MONTHLY'){
  var sample_dataset = 'MultiModel--spi1';
  var final_date = new Date(data_fdates[sample_dataset]);
  var initial_date = new Date();
  initial_date.setDate(final_date.getDate());
  final_date.setDate(final_date.getDate()+31*6);
  var initial_date = Date.UTC(initial_date.getFullYear(),initial_date.getMonth(),initial_date.getDate());
  var final_date = Date.UTC(final_date.getFullYear(),final_date.getMonth(),final_date.getDate());
 }
 else if (tstep == 'YEARLY'){
  var forecast_initial_date = Date.UTC(parseInt($("#year_initial").val()),0,1)/1000;
  var forecast_final_date = Date.UTC(parseInt($("#year_final").val()),11,31)/1000;
 }

 //Create the input for the chart
 var chart_options = {
      chart: {
       borderRadius: 0,
      },
      xAxis: {
       type: 'datetime',
       labels: {style: {fontSize: '15px',fontFamily: 'Avant Garde, Avantgarde, Century Gothic, CenturyGothic, AppleGothic, sans-serif'}},
       plotBands: [{ // visualize the forecast
        from: initial_date,//Date.UTC(2013,8,23),
        to: final_date,//Date.UTC(2013,8,29),
        color: 'rgba(68, 170, 213, .2)'
       }],
      },
      yAxis: [],
      legend: {layout: 'horizontal',align: 'center',verticalAlign: 'bottom',},
      series: [],
      title: chart_controls['title'],
      subtitle: {text: TRANSLATE['African_Water_Cycle_Monitor'],},
      tooltip: {
       formatter: function() {
        return Highcharts.numberFormat(this.y, 3);
       }
      },
     };
 for (variable in chart_data){
   if (!(variable in Output["VARIABLES"])){
    continue;
   }
   var units = chart_data[variable]['units'];//Output["VARIABLES"][variable]["units"];
   var series = {
        marker: {enabled: false},
        id: variable,
        name: chart_data[variable]['name'],
        type: 'line',
        yAxis: units,
        pointInterval: Output["TIME"]["pointInterval"],
        pointStart: Date.UTC(Output["TIME"]["iyear"],Output["TIME"]["imonth"]-1,Output["TIME"]["iday"]),
        data: Output["VARIABLES"][variable]["data"],
       };
   //Determine if we need a new axis. If so add it
   new_axis = true;
   for (i in chart_options.yAxis){
    id = chart_options.yAxis[i].id;
    if (id == units){new_axis = false;}
   };
   if (new_axis == true){
    var opposite=true;
    if(chart_options.yAxis.length%2 == 0){opposite=false;}
    var yAxis = {
     title: {text: units},
     name: units ,
     id: units,
     opposite: opposite,
     labels: {style: {fontSize: '15px',fontFamily: 'Avant Garde, Avantgarde, Century Gothic, CenturyGothic, AppleGothic, sans-serif'}}
    }
    chart_options.yAxis.push(yAxis);
   }; 
   //Add the series
   chart_options.series.push(series);
 };
 //Create the chart
 var chart = $('#popup_container').highcharts(chart_options);
};

/*Obtain all the data at once from the server*/
function Request_Data(variables,Create_Text_Data,data_group) {
  var Output;
  // Use hardcoded values for now, rather than the input values.
  //var initial_date = Date.UTC(2001,0,1)/1000;
  //var final_date = Date.UTC(2001,0,11)/1000;
  var tstep = $("ul.ts-selection li.active").attr('id').toUpperCase(); // "daily", "monthly" or "yearly"
  if (tstep == 'DAILY'){
   var initial_date = Date.UTC(parseInt($("#year_initial").val()),parseInt($("#month_initial").val()-1),parseInt($("#day_initial").val()))/1000;
   var final_date = Date.UTC(parseInt($("#year_final").val()),parseInt($("#month_final").val()-1),parseInt($("#day_final").val()))/1000;
  }
  else if (tstep == 'MONTHLY'){
   var initial_date = Date.UTC(parseInt($("#year_initial").val()),parseInt($("#month_initial").val()-1),1)/1000;
   var final_date = Date.UTC(parseInt($("#year_final").val()),parseInt($("#month_final").val()-1),28)/1000;
  }
  else if (tstep == 'YEARLY'){
   var initial_date = Date.UTC(parseInt($("#year_initial").val()),0,1)/1000;
   var final_date = Date.UTC(parseInt($("#year_final").val()),11,31)/1000;
  }

  //var lat = "-34.6250"; //$("#point-latitude").val();
  //var lon = "19.8750"; //$("#point-longitude").val();
  var lat = $("#point-latitude").html();
  var lon = $("#point-longitude").html();
  var script = 'python POINT_DATA/Extract_Point_Data.py';
  var input = {idate:initial_date, fdate:final_date, tstep:tstep, lat:lat, lon:lon, variables:variables,create_text_file:Create_Text_Data,data_group:data_group,http:document.URL,};
  input = JSON.stringify(input);
  var request = {script:script,input:input};
  $.ajax({
    type:"post",
    url: 'scripts/Jquery_Python_JSON_Glue.php',
    data: request,
    beforeSend: function() {$("#ajax_request_load").show();},
    complete: function() {$("#ajax_request_load").hide();},
    success: function(response){
     //alert(response);
     Output = JSON.parse(response.replace(/\bNaN\b/g, "null"));
    },
    async: false,
    cache: false,
  })
  return Output;
}

/* Spatial Data functions */

function Update_Spatial_Data_Display() {

 
  //Get spatial resolution
  var sres = $('input:radio[name=sres_spatial_data]:checked').val();

  //Compute the bounding box
  var lats = []
  var lons = []
  mapPolygon.getPath().forEach(function(positions) {
    lats.push(positions.lat());
    lons.push(positions.lng());
  });

  var minlat = Math.min.apply(Math, lats),
      minlon = Math.min.apply(Math, lons),
      maxlat = Math.max.apply(Math, lats),
      maxlon = Math.max.apply(Math, lons),
      npts = ((maxlat-minlat)/sres)*((maxlon-minlon)/sres);

  // Compute approximate number of timesteps
  var initial_date = Date.UTC(parseInt($("#year_initial").val()),
                           parseInt($("#month_initial").val())-1,
                           parseInt($("#day_initial").val()))/1000;
  var final_date = Date.UTC(parseInt($("#year_final").val()),
                           parseInt($("#month_final").val())-1,
                           parseInt($("#day_final").val())+1)/1000;
  var tstep = 86400; // in seconds
  var tstep_string = $("ul.ts-selection li.active").attr('id'); // "daily", "monthly" or "yearly"
  if(""+tstep_string == "monthly")
    tstep *= 30;
  else if(""+tstep_string == "yearly")
    tstep *= 365;
  var nt = (final_date - initial_date)/tstep;
  var nvars = $("ul#currently-selected-vars").find("li>a").length;
  var size_per_value = 4; // ??? 8 bytes? compressed? depends on choice of format?
  var estimated_download_size = npts*nt*nvars*size_per_value/1024/1024;
  var email = $('input:text[name=email_spatial_data]').val();

  if(estimated_download_size < 1)
    $("#estimated-download-size").html(estimated_download_size.toFixed(2) + " MB"); // what about units?
  else
    $("#estimated-download-size").html(Math.round(estimated_download_size) + " MB");

  if(nvars <= 0 | npts <= 0 | nt <= 0){
   $("#estimated-download-size").html(0);
  }

  if(nvars <= 0 | estimated_download_size > 1000 | email == ''){
   $("#submit_request_button").prop('disabled', true);  
  } else {
   $("#submit_request_button").prop('disabled', false);
  }

  if(email == ''){
    $("#email_warning").show();
  } else {
    $("#email_warning").hide();
  }

  if(npts == Infinity){
    $("#npts_warning").show();
  } else {
    $("#npts_warning").hide();
  }

  if(nvars <= 0){
    $("#nvars_warning").show();
  } else {
    $("#nvars_warning").hide();
  }

  if(estimated_download_size > 1000) {
    $("#download_size_warning").show();
  } else {
    $("#download_size_warning").hide();
  }

}

function Submit_Spatial_Data() {
  var Output;
  //Get info to send to the server to request the data
  //Timestep
  var tstep = $("ul.ts-selection li.active").attr('id');
  var initial_date = Date.UTC(parseInt($("#year_initial").val()),
                           parseInt($("#month_initial").val())-1,
                           parseInt($("#day_initial").val()))/1000;
  var final_date = Date.UTC(parseInt($("#year_final").val()),
                           parseInt($("#month_final").val())-1,
                           parseInt($("#day_final").val()))/1000;

  //Spatial Bounding Box
  var lats = []
  var lons = []
  mapPolygon.getPath().forEach(function(positions) {
    lats.push(positions.lat());
    lons.push(positions.lng());
  });
  var llclat = Math.min.apply(Math, lats),
     llclon = Math.min.apply(Math, lons),
     urclat = Math.max.apply(Math, lats),
     urclon = Math.max.apply(Math, lons);

  //Spatial resolution
  var sres = $('input:radio[name=sres_spatial_data]:checked').val();
  //Variables
  var variables = [];

  $("ul#currently-selected-vars").find("li>a").each(function (){variables.push($(this).attr('id'));});
  //File format
  var format = $('input:radio[name=format_spatial_data]:checked').val();
  //Email
  var email = $('input:text[name=email_spatial_data]').val();
  //Define the python script for data extraction
  var script = 'python SPATIAL_DATA/Spatial_Data_Request.py';//Extract_Point_Data.py'
  var input = {idate:initial_date,
          fdate:final_date,
          tstep:tstep,
          llclat:llclat,
          llclon:llclon,
          urclat:urclat,
          urclon:urclon,
          sres:sres,
          variables:variables,
          format:format,
          email:email,
          http:document.URL,
          };
  input = JSON.stringify(input);
  var request = {script:script,input:input};
  $.ajax({
  type:"post",
  url: 'scripts/Jquery_Python_JSON_Glue.php',//'Spatial_Data_Request.php ',
  data: request,
  success: function(response){
   Output = JSON.parse(response);
  },
  async: true,
  cache: false
  });
  return Output;
}
 
