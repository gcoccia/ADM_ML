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
  google.maps.event.addListener(map_array[0], 'click', function(mEvent) {alert(mEvent.latLng)});
 }
 else if (type == 'spatial'){
  //Remove present listeners
  Update_Listeners('none')
  //Add the listeners
  google.maps.event.addListener(map_array[0], 'click', addPoint);
  window.poly.setMap(map_array[0]);
  window.poly.setPaths(new google.maps.MVCArray([window.path]));
  window.markers = [];
  google.maps.event.addListener(window.poly, 'click',function() {Spatial_Data_Extraction()});  
 }
}

function Spatial_Data_Extraction(){

 //Print all the markers lat/lon
 info = ''
 for (var i in window.markers){
  info = info + ' ' + window.markers[i].position
 }
 alert(info)
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


