var mapPolygon = null,
    followLine1 = null,
    followLine2 = null;

function Update_Listeners(type){

 if (type == 'none'){
  // Revert to the animation sidebar, and hide the others
  $("Animation-Sidebar").show();
  $("Point-Sidebar").hide();
  $("Spatial-Sidebar").hide();

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
  // Switch to the point sidebar
  $("Animation-Sidebar").hide();
  $("Point-Sidebar").show();
  $("Spatial-Sidebar").hide();

  //Remove present listeners
  Update_Listeners('none')
  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', function(mEvent) {Point_Data(mEvent.latLng)});
 }
 else if (type == 'spatial'){
  // Switch to the spatial sidebar
  // Revert to the animation sidebar, and hide the others
  $("Animation-Sidebar").hide();
  $("Point-Sidebar").hide();
  $("Spatial-Sidebar").show();

  //Remove present listeners
  Update_Listeners('none');
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
  });
     
  google.maps.event.addListener(map_array[0], 'rightclick', function () {
    followLine1.setMap(null);
    followLine2.setMap(null);
    google.maps.event.clearListeners(map_array[0], "click");
    google.maps.event.clearListeners(map_array[0], "mousemove");
    google.maps.event.clearListeners(map_array[0], "rightclick");
    map_array[0].setOptions({draggableCursor:null});
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
  
  google.maps.event.addListener(mapPolygon, 'click', function() {
    Spatial_Data();
  });

 }
}

function Point_Data(latLng){
 //Create the popup
 Data_Extraction_Popup('popUpDiv');
 //Add controls
 Prepare_Point_Data_Display(latLng) 
 //Add initial data
 var variables = {SPI:['spi1','spi3','spi6','spi12']};
 Plot_Data(variables,'Drought Indices');
}

function Spatial_Data(){

 //Create the popup
 Data_Extraction_Popup('popUpDiv')
 //Add controls
 Prepare_Spatial_Data_Display()
}

function Data_Extraction_Popup() {
  windowname = "popUpDiv";
  //Place underlying blanket
  toggle('blanket');
  //PLace main window
  toggle(windowname);
}

function toggle(div_id) {
 var el = document.getElementById(div_id);
 if ( el.style.display == 'none' ) {     el.style.display = 'block';}
 else {el.style.display = 'none';}
}

/* Point Data functions */

function Prepare_Point_Data_Display(latLng) {
  //Empty the box
  $('#popUpDiv').empty();
  var request = {'latitude': latLng.lat(), 'longitude': latLng.lng()};
  //Get the current language and append it to the request
  var lang = getURLParameter('locale');
  if(lang != null)
    request.locale = lang;

  $.ajax({
    type:"post",
    url: 'point-popup-controls.php',
    data: request,
    success: function(response){
      $('#popUpDiv').html(response);
    },
    async: false,
    cache: false
  });
}

function Plot_Data(variables,subtitle) {
 
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
 idate = Date.UTC(parseInt($("#iyear").val()),parseInt($("#imonth").val()-1),parseInt($("#iday").val()))/1000;
 fdate = Date.UTC(parseInt($("#fyear").val()),parseInt($("#fmonth").val()-1),parseInt($("#fday").val())+1)/1000;
 lat = $("#latitude").val();
 lon = $("#longitude").val();
 tstep = $('input:radio[name=tstep]:checked').val();//"DAILY";
 script = 'python POINT_DATA/Extract_Point_Data.py'
 input = {idate:idate,fdate:fdate,tstep:tstep,lat:lat,lon:lon,variables:variables};
 input = JSON.stringify(input);
 request = {script:script,input:input};
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

function Request_and_Display() {
 plot = $('input:radio[name=plot]:checked').val();
 if (plot == "Drought_Indices"){
  //Drought Indices
  var variables = {SPI:['spi1','spi3','spi6','spi12']};
  Plot_Data(variables,'Drought Indices');
 }
 else if (plot == "Water_Balance"){
  //Water Balance
  var variables = {PGF:['prec'],VIC_PGF:['runoff','baseflow','evap']};
  Plot_Data(variables,'Water Balance');
 }
  //Precipitation Products
 else if (plot == "Surface_Fluxes"){
  //Surface Fluxes
  var variables = {VIC_PGF:['net_short','net_long','r_net']};
  Plot_Data(variables,'Surface Fluxes');
 };
 //TO DO: 
 //Specify the type of plot on entry per variable, order in plot of variable (e.g. prec at back and column...)
};

/* Spatial Data functions */

function Prepare_Spatial_Data_Display() {
  //Compute the bounding box
  lats = []
  lons = []
  mapPolygon.getPath().forEach(function(positions) {
    lats.push(positions.lat());
    lons.push(positions.lng());
  });

  var minlat = Math.min.apply(Math, lats); 
  var minlon = Math.min.apply(Math, lons);   
  var maxlat = Math.max.apply(Math, lats);  
  var maxlon = Math.max.apply(Math, lons);  

  //Empty the box
  $('#popUpDiv').empty();

  var request = {'minlat': minlat, 'minlon': minlon, 'maxlat': maxlat, 'maxlon': maxlon};
  //Get the current language and append it to the request
  var lang = getURLParameter('locale');
  if(lang != null)
    request.locale = lang;

  $.ajax({
    type:"post",
    url: 'spatial-popup-controls.php',
    data: request,
    success: function(response){
      $('#popUpDiv').html(response);
    },
    async: false,
    cache: false
  });
}

function Submit_Spatial_Data() {
 var Output;
 //Get info to send to the server to request the data
 //Timestep
 tstep = $('input:radio[name=tstep_spatial_data]:checked').val();
 //Initial Timestamp
 iyear = $('input:text[name=iyear_spatial_data]').val();
 imonth = $('input:text[name=imonth_spatial_data]').val();
 iday = $('input:text[name=iday_spatial_data]').val();
 idate = Date.UTC(parseInt(iyear),parseInt(imonth)-1,parseInt(iday))/1000;
 //Final Timestamp
 fyear = $('input:text[name=fyear_spatial_data]').val();
 fmonth = $('input:text[name=fmonth_spatial_data]').val();
 fday = $('input:text[name=fday_spatial_data]').val();
 fdate = Date.UTC(parseInt(fyear),parseInt(fmonth)-1,parseInt(fday))/1000;
 //Spatial Bounding Box
 llclat = $('input:text[name=llclat_spatial_data]').val();
 llclon = $('input:text[name=llclon_spatial_data]').val();
 urclat = $('input:text[name=urclat_spatial_data]').val();
 urclon = $('input:text[name=urclon_spatial_data]').val();
 //Spatial resolution
 sres = $('input:radio[name=sres_spatial_data]:checked').val();
 //Variables
 var variables = []
 $("input[name='variables_spatial_data[]']:checked").each(function (){variables.push($(this).val());});
 //File format
 format = $('input:radio[name=format_spatial_data]:checked').val();
 //Email
 email = $('input:text[name=email_spatial_data]').val();
 //Define the python script for data extraction
 script = 'python SPATIAL_DATA/Spatial_Data_Request.py';//Extract_Point_Data.py'
 input = {idate:idate,
          fdate:fdate,
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
 request = {script:script,input:input};
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
 