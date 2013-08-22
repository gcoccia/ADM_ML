//Define the objects
ImageOverlay.prototype = new google.maps.OverlayView();
 
//Define the functions
function ImageOverlay(bounds, image, map,id,flag_basin)
	{
    var map;
    var flag_basin;
    this.bounds_ = bounds;
    this.image_ = image;
    this.map_ = map;
    this.div_ = null; 
    this.setMap(map);
    this.imageid_ = id;
	if (flag_basin == "undefined")
		{
		flag_basin = 0;
		}
    this.flag_basin = flag_basin;
	}
 
ImageOverlay.prototype.onAdd = function() 
	{
    var div = document.createElement('DIV');
    //img.setAttribute("id","nameba;hwskefbg");
    div.style.borderStyle = "none";
    div.style.borderWidth = "0px";
    div.style.position = "absolute";
	div.style.left = "200px";
  	div.style.top="100px";
	//div.style.opacity = 0.8;
    var img = document.createElement("img");
    img.setAttribute('id',this.imageid_);
    img.src = this.image_;
    img.onerror = function (evt){
    	this.src = 'icons/gm_noimage.gif';
    	}
    img.style.width = "100%";
    img.style.height = "100%";
    if (this.flag_basin == 1)
	{
	img.style.opacity = 0.2;
	}
    else
	{
    	img.style.opacity = overlay_opacity;//0.4;
	}
    div.appendChild(img);
    this.div_ = div;
   // this.img_ = img;
    var panes = this.getPanes();
    panes.overlayImage.appendChild(div);
	}

ImageOverlay.prototype.ChangeOpacity = function()
	{
	var img = document.getElementById(this.imageid_);
	img.style.opacity = overlay_opacity;
	}
 
ImageOverlay.prototype.draw = function() 
	{ 
    var overlayProjection = this.getProjection();
    var sw = overlayProjection.fromLatLngToDivPixel(this.bounds_.getSouthWest());
    var ne = overlayProjection.fromLatLngToDivPixel(this.bounds_.getNorthEast());
    var div = this.div_;
    div.style.left = sw.x + 'px';
    div.style.top = ne.y + 'px';
    div.style.width = (ne.x - sw.x) + 'px';
    div.style.height = (sw.y - ne.y) + 'px';
    }
 
ImageOverlay.prototype.onRemove = function() 
	{
    this.div_.parentNode.removeChild(this.div_);
    this.div_ = null;
	}

ImageOverlay.prototype.remove = function() 
	{
	if (this.getMap()) 
		{
	    this.setMap(null); 
		//replace color bar with white strip
		} 
	else 
		{
	    this.setMap(this.map_);
		//replace color bar with new colorbar
		}
	}
ImageOverlay.prototype.swap=function(image_str)
	{
	img = document.getElementById(this.imageid_)
	var image_str;
	//img.addEvent('load',function(e){});
	img.src = image_str;
	//img.onload=imageLoaded();
	img.onerror = function (evt){
		this.src = 'icons/gm_noimage.gif';
		}	
	}
function clearMarkers() {
  if (markersArray) {
    for (i in markersArray) {
      markersArray[i].setMap(null);
    }
  }
}

function initialize() 
	{
	var i;
	var map_canvas_st;
	var myLatLng = new google.maps.LatLng(-10, 30);
    var styleArray = [{featureType: 'administrative.country',stylers: [{ visibility: 'simplified' }]}];
    var myOptions = {styles: styleArray,zoom: 3,center: myLatLng,panControl: false,zoomControl: true,zoomControlOptions:{style:    	
		google.maps.ZoomControlStyle.DEFAULT,position: google.maps.ControlPosition.LEFT_TOP},scaleControl: false,streetViewControl: false,mapTypeControl: 
		true,mapTypeControlOptions:{style: google.maps.MapTypeControlStyle.DROPDOWN_MENU,position: google.maps.ControlPosition.TOP_LEFT},mapTypeId: 
		google.maps.MapTypeId.TERRAIN};

	//Insert the map canvas into html
	var form_string = "";
	var Dropdown_temp;
	var MapCanvas_temp;
	MapCanvas_temp = '<div id="map_canvas_1' + '" style="width:' + wpercent + '%; height:' + hpercent + '%;"></div>';
	form_string = form_string + MapCanvas_temp;
	document.getElementById("DBandMC").innerHTML = form_string;
	map_canvas_st = "map_canvas_1";
	map_array[0] = new google.maps.Map(document.getElementById(map_canvas_st), myOptions);
	var swBound = new google.maps.LatLng(-35.000, -19.000);
    	var neBound = new google.maps.LatLng(38.000, 55.000);
    	bounds = new google.maps.LatLngBounds(swBound, neBound);
	animate_overlay(15) //Load the drought index map from the start
	}

function imageLoaded()
	{
	ImageLoadedBoolean = true;
	}


