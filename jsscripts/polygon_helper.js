var mapPolygon = null,
    followLine1 = null,
    followLine2 = null;

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

//Remove the listeners and lines/polygons from the map
function removePolygon() {
    if(mapPolygon) {
    mapPolygon.stopEdit();
    mapPolygon.setMap(null);
    google.maps.event.clearListeners(mapPolygon, "click");
  }
}

function finalizePolygon() {
  if(followLine1) followLine1.setMap(null);
  if(followLine2) followLine2.setMap(null);
  google.maps.event.clearListeners(map_array[0], "click");
  google.maps.event.clearListeners(map_array[0], "mousemove");
  google.maps.event.clearListeners(map_array[0], "rightclick");
  map_array[0].setOptions({draggableCursor:null});
}

function newPolygon() {
  map_array[0].setOptions({draggableCursor:'crosshair'});
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
    finalizePolygon();
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
}