mapPolygon = new google.maps.Polygon({strokeColor   : '#ff0000',
                                      strokeOpacity : 0.6,
                                      strokeWeight  : 4,
                                    });
var followLine1 = new google.maps.Polyline({
  clickable: false,
  map : map,
  path: [],
  strokeColor: "#787878",
  strokeOpacity: 1,
  strokeWeight: 2
});
var followLine2 = new google.maps.Polyline({
  clickable: false,
  map : map,
  path: [],
  strokeColor: "#787878",
  strokeOpacity: 1,
  strokeWeight: 2
});

window.path = new google.maps.MVCArray;

function Update_Listeners(type){

 if (type == 'none'){
  //Remove the listeners
/*  google.maps.event.clearListeners(map_array[0],'click');
  google.maps.event.clearListeners(map_array[0],'dragend');
  
  mapPolygon.setMap(null); //remove the polygon
  mapPolygon.stopEdit();*/
 }
 else if (type == 'point'){
  //Remove present listeners
  Update_Listeners('none')
  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', function(mEvent) {Point_Data(mEvent.latLng)});
 }
 else if (type == 'spatial'){
  //Remove present listeners
  //Update_Listeners('none')
  mapPolygon.stopEdit();
  mapPolygon.setMap(null);
  mapPolygon = null;
  google.maps.event.clearListeners(map_array[0], "click");
  google.maps.event.clearListeners(map_array[0], "mousemove");
  google.maps.event.clearListeners(map_array[0], "rightclick");
  map.setOptions({ draggableCursor: 'crosshair'});
  mapPolygon = new google.maps.Polygon({map : map_array[0],
                                      strokeColor   : '#ff0000',
                                      strokeOpacity : 0.6,
                                      strokeWeight  : 4,
                                      path:[]
                                     });

  google.maps.event.addListener(map_array[0], 'click', function(point) {
       mapPolygon.stopEdit();
       mapPolygon.getPath().push(point.latLng);
       mapPolygon.runEdit(true);
  }); //*/
     
  google.maps.event.addListener(map_array[0], 'rightclick', function () {
   followLine1.setMap(null);
   followLine2.setMap(null);
   google.maps.event.clearListeners(map, "click");
   google.maps.event.clearListeners(map, "mousemove");
   google.maps.event.clearListeners(map, "rightclick");
   map_array[0].setOptions({ draggableCursor: 'pointer' });
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
    } //*/
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
 //Print all the markers lat/lon
/* info = []
 info = ''
 for (var i in window.markers){
  info = info + ' ' + window.markers[i].position
 }*/

 //Create the popup background
 
 //Upon closing remove the markers and polygon
/* for (marker in window.markers){
  window.markers[marker].setMap(null);
 }
 window.markers = [];
 //Clear the paths
 window.path.clear();*/

}

/*function addPoint(event) {
    window.path.insertAt(window.path.length, event.latLng);

    var marker = new google.maps.Marker({
      position: event.latLng,
      map: map_array[0],
      draggable: true
    });
    window.markers.push(marker);
    marker.setTitle("#" + path.length);

    google.maps.event.addListener(marker, 'click', function() {
      marker.setMap(null);
      for (var i = 0, I = window.markers.length; i < I && window.markers[i] != marker; ++i);
      window.markers.splice(i, 1);
      window.path.removeAt(i);
      }
    );

    google.maps.event.addListener(marker, 'dragend', function() {
      for (var i = 0, I = window.markers.length; i < I && window.markers[i] != marker; ++i);
      window.path.setAt(i, marker.getPosition());
      }
    );
  }*/

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
































  function initialize2 () {
    var map = new google.maps.Map(document.getElementById("map"),{zoom: 14,
                                                                  center: new google.maps.LatLng(50.909528, 34.811726),
                                                                  mapTypeId: google.maps.MapTypeId.ROADMAP
                                                                 });
    var followLine1 = new google.maps.Polyline({
      clickable: false,
      map : map,
      path: [],
      strokeColor: "#787878",
      strokeOpacity: 1,
      strokeWeight: 2
     });
    var followLine2 = new google.maps.Polyline({
      clickable: false,
      map : map,
      path: [],
      strokeColor: "#787878",
      strokeOpacity: 1,
      strokeWeight: 2
     });
    
/*    var mapPolygon = new google.maps.Polygon({map : map,
                                        strokeColor   : '#ff0000',
                                        strokeOpacity : 0.6,
                                        strokeWeight  : 4,
                                        path:[new google.maps.LatLng(50.91607609098315,34.80485954492187),new google.maps.LatLng(50.91753710953153,34.80485954492187),new google.maps.LatLng(50.91759122044873,34.815159227539056),new google.maps.LatLng(50.9159678655622,34.815159227539056),new google.maps.LatLng(50.91044803534999,34.81258430688476),new google.maps.LatLng(50.91044803534999,34.81584587304687),new google.maps.LatLng(50.90931151845126,34.81533088891601),new google.maps.LatLng(50.90931151845126,34.811897661376946),new google.maps.LatLng(50.90395327929007,34.8094944020996),new google.maps.LatLng(50.9040074060014,34.80700531213378),new google.maps.LatLng(50.90914915662899,34.809666063476556),new google.maps.LatLng(50.90920327729935,34.8065761586914),new google.maps.LatLng(50.91033979684091,34.80700531213378),new google.maps.LatLng(50.910285677492006,34.81035270898437),new google.maps.LatLng(50.91607609098315,34.81301346032714)]
                                       });

    google.maps.event.addListener(mapPolygon, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      mapPolygon.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<mapPolygon.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });        

    mapPolygon.runEdit(true);*/
    
    
    document.getElementById("newPolygon").onclick = function () {
      mapPolygon.stopEdit();
      mapPolygon.setMap(null);
      mapPolygon = null;
      document.getElementById("info").innerHTML = "Create a new Polygon. Press the mouse on the map and all will understand :) Finish adding new points - right-click.";
      google.maps.event.clearListeners(map, "click");
      google.maps.event.clearListeners(map, "mousemove");
      google.maps.event.clearListeners(map, "rightclick");
      map.setOptions({ draggableCursor: 'crosshair'});
      
      mapPolygon = new google.maps.Polygon({map : map,
                                        strokeColor   : '#ff0000',
                                        strokeOpacity : 0.6,
                                        strokeWeight  : 4,
                                        path:[]
                                       });
/*      followLine1.setPath([]);
      followLine2.setPath([]);
      followLine1.setMap(map);
      followLine2.setMap(map);*/
      
     google.maps.event.addListener(mapPolygon, 'click', function() {
      document.getElementById("info").innerHTML = 'path:[';
      mapPolygon.getPath().forEach(function (vertex, inex) {
        document.getElementById("info").innerHTML += 'new google.maps.LatLng('+vertex.lat()+','+vertex.lng()+')' + ((inex<mapPolygon.getPath().getLength()-1)?',':'');
      });
      document.getElementById("info").innerHTML += ']';
    });
    
     google.maps.event.addListener(map, 'click', function(point) {
       mapPolygon.stopEdit();
       mapPolygon.getPath().push(point.latLng);
       mapPolygon.runEdit(true);
     } ); //*/
     
     google.maps.event.addListener(map, 'rightclick', function () {
       followLine1.setMap(null);
       followLine2.setMap(null);
       google.maps.event.clearListeners(map, "click");
       google.maps.event.clearListeners(map, "mousemove");
       google.maps.event.clearListeners(map, "rightclick");
       map.setOptions({ draggableCursor: 'pointer' });
     } );
     
     
     google.maps.event.addListener(map, 'mousemove', function(point) {
      var pathLength = mapPolygon.getPath().getLength();
      if (pathLength >= 1) {
        var startingPoint1 = mapPolygon.getPath().getAt(pathLength - 1);
        var followCoordinates1 = [startingPoint1, point.latLng];
        followLine1.setPath(followCoordinates1);
        
        var startingPoint2 = mapPolygon.getPath().getAt(0);
        var followCoordinates2 = [startingPoint2, point.latLng];
        followLine2.setPath(followCoordinates2);
      } //*/
     } );   
     
  }
  
  
  }

  
 