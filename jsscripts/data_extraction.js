//What to do when we know the lat/lon
window.path = new google.maps.MVCArray;
window.poly = new google.maps.Polygon({strokeWeight: 1,fillColor: '#5555FF'});

function Update_Listeners(type){

 if (type == 'none'){
  //Remove the listeners
  google.maps.event.clearListeners(map_array[0],'click');
  google.maps.event.clearListeners(map_array[0],'dragend');
  //Remove all markers
  for (marker in window.markers){
   window.markers[marker].setMap(null);
  }
  //Clear the paths
  window.path.clear();
 }
 else if (type == 'point'){
  //Remove present listeners
  Update_Listeners('none')
  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', function(mEvent) {Point_Data(mEvent.latLng)});//function(mEvent) {alert(mEvent.latLng)});
 }
 else if (type == 'spatial'){
  //Remove present listeners
  Update_Listeners('none')
  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', addPoint);
  window.poly.setMap(map_array[0]);
  window.poly.setPaths(new google.maps.MVCArray([window.path]));
  window.markers = [];
  google.maps.event.addListener(window.poly, 'click',function() {Spatial_Data()});  
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

 //Print all the markers lat/lon
 info = []
 info = ''
 for (var i in window.markers){
  info = info + ' ' + window.markers[i].position
 }

 //Create the popup background
 Data_Extraction_Popup('popUpDiv')
 
 //Upon closing remove the markers and polygon
 for (marker in window.markers){
  window.markers[marker].setMap(null);
 }
 window.markers = [];
 //Clear the paths
 window.path.clear();

}

function addPoint(event) {
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
  //Add the close window box
  $('#popUpDiv').append('<a onclick="Data_Extraction_Popup()" style="width:80px; height:10px">Close Window</a>');
  //Add the chart container
  $('#popUpDiv').append('<div id="popup_container"></div>');
  //Add the contorols
  $('#popUpDiv').append('<div id="popup_controls"></div>');
  html_input = [
   '<button onclick="Request_and_Display()">Update Plot</button>',
   '<br>',
   'Plots:',
   '<input type="radio" name="plot" value="Drought_Indices" checked=checked>Drought Indices',
   '<input type="radio" name="plot" value="Water_Balance" >Water Balance',
   '<input type="radio" name="plot" value="Surface_Fluxes" >Surface Fluxes',
   '<br>',
   'Time Step: (Only Daily works for now...)',
   '<input type="radio" name="tstep" value="DAILY" checked=checked>Daily',
   '<input type="radio" name="tstep" value="MONTHLY" >Monthly',
   '<input type="radio" name="tstep" value="YEARLY" >Yearly',
   '<br>',
   'Initial Time: (1 jan 2001 - 31 dec 2001)',
   '<br>',
   '<input type="text" id="iyear" value=2001>',
   '<input type="text" id="imonth" value=1>',
   '<input type="text" id="iday" value=1>',
   '<br>',
   'Final Time:',
   '<br>',
   '<input type="text" id="fyear" value=2001>',
   '<input type="text" id="fmonth" value=1>',
   '<input type="text" id="fday" value=10>',
   '<br>',
   'Latitude: (Other coordinates wont work for now)',
   '<br>',
   '<input type="text" id="latitude" value=-34.6250>',
   '<br>',
   'Longitude:',
   '<br>',
   '<input type="text" id="longitude" value=19.8750>',
   '<br>',
   'Actual Coordinates ' + latLng.lat() + ' ' + latLng.lng(),
  ];
  $('#popup_controls').append(html_input);
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
 script = 'python Extract_Point_Data.py'
 input = {idate:idate,fdate:fdate,tstep:tstep,lat:lat,lon:lon,variables:variables};
 input = JSON.stringify(input);
 request = {script:script,input:input};
 $.ajax({
  type:"post",
  url: 'scripts/POINT_DATA/Jquery_Python_JSON_Glue.php',
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

