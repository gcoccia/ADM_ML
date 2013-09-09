var mapPolygon = null,
    followLine1 = null,
    followLine2 = null;

function Update_Listeners(type){

 if (type == 'none'){
  // Revert to the animation sidebar, and hide the others
  $("#Animation-Sidebar").show();
  $("#Point-Sidebar").hide();
  $("#Spatial-Sidebar").hide();

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
 }
 else if (type == 'point'){
  //Remove present listeners
  Update_Listeners('none');
  map_array[0].setOptions({draggableCursor:'crosshair'});

  // Switch to the point sidebar
  $("#Animation-Sidebar").hide();
  $("#Point-Sidebar").show();
  $("#Spatial-Sidebar").hide();

  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', function(mEvent) {Point_Data(mEvent.latLng)});
  google.maps.event.addListener(map_array[0], 'mousemove', function(point) {
    $("#point-latitude").html(point.latLng.lat());
    $("#point-longitude").html(point.latLng.lng());
  });
 }
 else if (type == 'spatial'){
  //Remove present listeners
  Update_Listeners('none');

  // Switch to the spatial sidebar
  $("#Animation-Sidebar").hide();
  $("#Point-Sidebar").hide();
  $("#Spatial-Sidebar").show();

  map_array[0].setOptions({draggableCursor:'crosshair'});
  // Add polygon and lines to map
  var polyOptions = { map : map_array[0],
                    strokeColor   : '#ff0000',
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

  google.maps.event.addListener(mapPolygon, 'dragend', function(point) {
    Update_Spatial_Data_Display();
  });

 }
}

function Point_Data(latLng){
  //Create the popup
  $("#blanket").show();
  $("#popUpDiv").show();

  Create_Point_Plot();
}

function Hide_Data_Extraction_Popup() {
  $("#blanket").hide();
  $("#popUpDiv").hide();
}

function Create_Point_Plot() {

  var variables, subtitle;
  var plot = $('input:radio[name=plot]:checked').val();
  if (plot == "Drought_Indices"){
    variables = {SPI:['spi1','spi3','spi6','spi12']};
  }
  else if (plot == "Water_Balance"){
    variables = {PGF:['prec'],VIC_PGF:['runoff','baseflow','evap']};
  }
  else if (plot == "Surface_Fluxes"){
    variables = {VIC_PGF:['net_short','net_long','r_net']};
  };
  subtitle = plot;
 
 //Request data for these variables
 var Output = Request_Data(variables); 
 //Create the input for the chart
 var chart_options = {
      xAxis: {type: 'datetime',},
      yAxis: [],
      legend: {layout: 'horizontal',align: 'center',verticalAlign: 'bottom',},
      series: [],
      title: {text: subtitle,},
      subtitle: {text: 'African Water Cycle Monitor',},
     };
 for (variable in Output["VARIABLES"]){
  var units = Output["VARIABLES"][variable]["units"];
  var series = {
       marker: {enabled: false},
       id: variable,
       name: variable,
       type: 'spline',
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
function Request_Data(variables) {
  var Output;
  // Use hardcoded values for now, rather than the input values.
  //var initial_date = Date.UTC(2001,0,1)/1000;
  //var final_date = Date.UTC(2001,0,11)/1000;
  var initial_date = Date.UTC(parseInt($("#year_initial").val()),
                           parseInt($("#month_initial").val()-1),
                           parseInt($("#day_initial").val()))/1000;
  var final_date = Date.UTC(parseInt($("#year_final").val()),
                           parseInt($("#month_final").val()-1),
                           parseInt($("#day_final").val())+1)/1000;

  //var lat = "-34.6250"; //$("#point-latitude").val();
  //var lon = "19.8750"; //$("#point-longitude").val();
  var lat = $("#point-latitude").html();
  var lon = $("#point-longitude").html();
  var tstep = $("ul.ts-selection li.active").attr('id').toUpperCase(); // "daily", "monthly" or "yearly"
  var script = 'python POINT_DATA/Extract_Point_Data.py';
  var input = {idate:initial_date, fdate:final_date, tstep:tstep, lat:lat, lon:lon, variables:variables};
  input = JSON.stringify(input);
  var request = {script:script,input:input};
  $.ajax({
    type:"post",
    url: 'scripts/Jquery_Python_JSON_Glue.php',
    data: request,
    success: function(response){
     Output = JSON.parse(response);
    },
    async: false,
    cache: false
  });    
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
  var nvars = $("input[name='variables_spatial_data[]']:checked").length;
  var size_per_value = 8; // ??? 8 bytes? compressed? depends on choice of format?
  var estimated_download_size = npts*nt*nvars*size_per_value;

  // then do something with the estimated download size
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
  var variables = []
  $("input[name='variables_spatial_data[]']:checked").each(function (){variables.push($(this).val());});
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
  async: false,
  cache: false
  });
  return Output;
}
 
