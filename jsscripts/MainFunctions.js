var ImageTimeArray = [];  
var ImageStrArray = [];
var ImageCounter = 0;
var overlay_opacity = 0.8;
var overlay_mask_dropdown = new Array();

// JH: Which of these variables are actually being used??
var ImageLoadedBoolean;
var day;
var daycount;
var gaugen; //Declare the gauge number as a global variable.
var gauge_area;
var gauge_lat;
var gauge_lon;
var image_type; //Determines what type of gauge plot one is looking at (wb or ds)
var wb_image_string; //water balance plot image string
var ds_image_string; //discharge plot image string
var wb_data_string; //water balance data string
var ds_data_string; //discharge data string
var sm_image_string; //soil moisture plot image string
var month;
var timestep_flag = 1; //flag to indicate whether we are working with daily or monthly time steps
timecount = 0;
var variable_image_number = 999; //Number that tells which overlay to animate
var t;
var Logo_Images = [];
Logo_Images[16] = "icons/smos_logo.png";
var myVariable;

// function update_animation()
// (1) Clears all existing animations
// (2) Loads new animations based on (a) which radio button is checked, and (b) the timestamp form
// Called on the following events:
// (1) Initial page load (whichever dataset is checked by default)
// (2) When the selected radio button changes
// (3) When the timestamp form "update" button is clicked
function update_animation()
{
  clear_image_overlays();
  ReadTimeInterval();
  var dataset = $("input[name='group1']:checked").attr('id');

  //Fill up the Array of image strings
  ImageTimeArray[dataset] = new Array();
  ImageStrArray[dataset] = new Array();

/*  if (time_flag == "SPI")ImageArrayPrep_SPI(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
  else ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);*/

  ImageArrayPrep(ImageStrArray[dataset], ImageTimeArray[dataset]);

  display_colorbar(dataset);
  update_logo(dataset);

  var time_delay = 1000*1/frames_per_second;

  overlay_obj[dataset] = new ImageOverlay(bounds, ImageStrArray[dataset][0], map_array[0], dataset);
  ChangeTimeStamp(1, ImageCounter, dataset);
  ImageCounter = 1;

  t = setInterval(next_image(dataset), time_delay);
}

function next_image(dataset)
{
  if (ImageCounter == daycount) ImageCounter = 0;
  overlay_obj[dataset].swap(ImageStrArray[dataset][ImageCounter]);
  ChangeTimeStamp(2, ImageCounter, dataset);
  ImageCounter += 1;
}

function clear_image_overlays()
{
  clearInterval(t);

  for (k=0; k < overlay_obj.length; k++){
    if (overlay_obj[k] != undefined){
      overlay_obj[k].remove();
      delete overlay_obj[k];
      //Remove time stamp
      ChangeTimeStamp(3);
    }
  }
  $("#Colorbar").css({visibility: "hidden", height: ""});
}

function display_colorbar(dataset)
{
  var cbar_img = "Data/Colorbar/colorbar_" + dataset + ".png";
  $("#Colorbar").css({visibility: "visible", height: "100"});
  $("#Colorbar").html("<img src=" + cbar_img + "></img>");
}

function imageLoaded()
{
  ImageLoadedBoolean = true;
}
  
function update_overlay(j) 
{
  var k;
  var cbar_string;  
  if (typeof static_overlay_obj[j] !="undefined") //hide the overlay
  {
    static_overlay_obj[j].remove();
    delete static_overlay_obj[j];
  }
  else if(typeof static_overlay_obj[j] == "undefined")
  {
    // grab overlay image select value
    value = document.getElementById("overlayImageSelect_" + j).value;
    // add new overlay to map
    static_overlay_obj[j] = new ImageOverlay(bounds, srcImage[j], map_array[0]);
  }
}

function update_overlay_opacity(flag_dir)
{
  var j = variable_image_number;
  var flag_dir
  if (flag_dir == 0){
    overlay_opacity = overlay_opacity - 0.2;
    if (overlay_opacity < 0){overlay_opacity = 0;};
  }
  else{
    overlay_opacity = overlay_opacity + 0.2;
    if (overlay_opacity > 1){overlay_opacity = 1;};
  }
  if (overlay_obj[j] != undefined){overlay_obj[j].ChangeOpacity();}
}
  
function clear_all_overlays()
{
  clear_image_overlays();

  //Clear all static overlays
  for (var k=0;k<static_overlay_obj.length;k++)
  {
    if (static_overlay_obj[k] != undefined)
    {
    clearTimeout(t);
    static_overlay_obj[k].remove();
    delete static_overlay_obj[k];
    }
  }

  update_logo(0);

  //Remove all basin layers
  for (var k=0;k<overlay_mask_dropdown.length;k++)
  {                
    if (overlay_mask_dropdown[k] != undefined)
    {
      overlay_mask_dropdown[k].remove();
      delete overlay_mask_dropdown[k];
    }                
  }

  // Clear all forms
  document.getElementById("AnimationForm").reset();
  $(".data-radio").prop('checked', false);
}

/*function animate_overlay(j,time_flag)
{
  var j;
  var time_flag;
  if (j < 17 | j > 20);
  variable_image_number = j;
  var k;
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

  cbar = document.getElementById("Colorbar").style;
  cbar.visibility = "hidden";
  cbar.height = "";

  //Fill up the Array of image strings
  ImageTimeArray[j] = new Array();
  ImageStrArray[j] = new Array();
  if (time_flag == "SPI")ImageArrayPrep_SPI(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
  else ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
  //Add the new overlay 
  var fcnstr;
  var i = 0;
  update_colorbar(j);
  update_overlay_animate(j,0);
  update_logo(j);
}*/
  
/*function update_colorbar(j)
{
  var flag_m;
  var obj
  obj = document.getElementById("Colorbar").style;
  if (obj.visibility == "visible")
  {
    obj.visibility = "hidden";
    obj.height = "";
  }
  else if(obj.visibility == "hidden")
  {
    obj.visibility = "visible";
    obj.height = "100";
    contentString = "<img src="+Colorbar_Images[j]+"></img>";
    document.getElementById('Colorbar').innerHTML = contentString;
  }
}*/
  
/*function update_overlay_animate(j,i) 
{
  var j;
  var k;
  var maxi = daycount;
  var time_delay = 1000*1/frames_per_second;
  //var ImageLoadedBoolean;
  if (i == 0)
  {
    value = document.getElementById("overlayImageSelect_" + j).value;
    overlay_obj[j] = new ImageOverlay(bounds, ImageStrArray[j][i], map_array[0],ImageIdArray[j]);
    ChangeTimeStamp(1,i,j)
    i = i+1;
    fcnstr = "update_overlay_animate(" + j + "," + i + ")";
    t = setTimeout(fcnstr,time_delay);
  }
  else
  {
    if (i == maxi) {i = 0};
        //ImageLoadedBoolean = false;
    overlay_obj[j].swap(ImageStrArray[j][i]);
    //while(ImageLoadedBoolean == false){}
    ChangeTimeStamp(2,i,j)
    i = i+1;
    fcnstr = "update_overlay_animate(" + j + "," + i + ")";
    t = setTimeout(fcnstr,time_delay);
  }
}*/
  
/*function animate_overlay_submit()
{
  var k;
  var j = variable_image_number;
  for (k=0;k<overlay_obj.length;k++)
  {
    if (overlay_obj[k] != undefined)
    {
      clearTimeout(t);
      overlay_obj[k].remove();
      delete overlay_obj[k];
      update_colorbar(k);
      //Remove time stamp
      ChangeTimeStamp(3);
      //Fill up the Array of image strings
      ImageTimeArray[j] = new Array();
      ImageStrArray[j] = new Array();
      if (j >= 17 & j <= 20) ImageArrayPrep_SPI(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
      else ImageArrayPrep(ImageStrArray[j],ImageRootArray[j],ImageTimeArray[j]);
      //Add the new overlay 
      update_colorbar(j);
      update_overlay_animate(j,0);
    }
  }
}*/
  
/*function update_basins(j)
{
  var j;
  var k;
  if (typeof overlay_mask[j] !="undefined") //hide the overlay
  {
    overlay_mask[j].remove();
    delete overlay_mask[j];
  }
  else if(typeof overlay_mask[j] == "undefined")
  {
    overlay_mask[j] = new ImageOverlay(bounds, basinImage[j], map_array[0]);
  }
}*/
  
function ChangeLanguage(language)
{
  var locale_val;
  if(language == 'English') {locale_val = escape('en');}
  else if (language == 'French') { locale_val = escape('fr');}
  else if (language == 'Chinese') { locale_val = escape('cn');}
  else if (language == 'Spanish') { locale_val = escape('sp');}
  else if (language == 'Arabic') { locale_val = escape('ar');}

  var uri = window.location.toString().split('?');
  if (uri.length > 1) {
    var kvp = uri[1].split('&');
    var i=kvp.length; var x; while(i--) 
    {
      x = kvp[i].split('=');
      if (x[0]=='locale')
      {
        x[1] = locale_val;
        kvp[i] = x.join('=');
        break;
      }
    }
    if(i<0) {
      kvp[kvp.length] = ['locale',locale_val].join('=');
    }
    window.location.replace(uri[0]+'?'+kvp.join('&'));
  }
  else {
    window.location.replace(uri[0]+'?locale='+locale_val);
  }


}
  
function ChangeBasin(basin_name)
{
  var basin_name;
  var basins_lat = [-2.0,14,13,13,9.7];
  var basins_lon = [23.0,32,6,-10,0.125];
  var basins_number = [0,34,68,238,259];
  var basins_zoom = [5,4,5,5,5];
  var basins_id = ["Congo","Nile","Niger","Senegal","Volta"];
  //Delete any existing basins
  for (i=0;i<basins_lat.length;i++)
  {
    if (typeof overlay_mask_dropdown[basins_number[i]] !="undefined") //hide the overlay
    {
      overlay_mask_dropdown[basins_number[i]].remove();
      delete overlay_mask_dropdown[basins_number[i]];
    }
  }

  for (i=0;i<basins_lat.length;i++)
  {
    if (basins_id[i] == basin_name)
    {
      map_array[0].setZoom(basins_zoom[i]);
      var latlng = new google.maps.LatLng(basins_lat[i], basins_lon[i]);
      map_array[0].setCenter(latlng);
      if(typeof overlay_mask_dropdown[basins_number[i]] == "undefined")
      {
        overlay_mask_dropdown[basins_number[i]] = new ImageOverlay(bounds, basinImage[basins_number[i]],map_array[0],9999,1);
      }
    }
  }
  //flag_basin = 0;
  //overlay_opacity = overlay_opacity_temp;
}
  
/*function SPIselect(flag)
  {
  if (flag == 1){
  animate_overlay(17,"SPI");
  document.getElementById('SPIdiv').innerHTML = 'SPI <select id="SPI_form" onchange=animate_overlay(value,"SPI")><option id="overlayImageSelect_17" value="17">1 month</option><option id="overlayImageSelect_18" value="18">3 months</option><option id="overlayImageSelect_19" value="19">6 months</option><option id="overlayImageSelect_20" value="20">12 months</option></select><img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(6)" onmouseout="Info_Box_Call(6)">';
  }
  else{
      document.getElementById('SPIdiv').innerHTML = 'SPI <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(6)" onmouseout="Info_Box_Call(6)">';
  }
  }*/

function update_logo(j)
{
  var obj;
  obj = document.getElementById("Logo").style;
  if (obj.visibility == "visible")
  {
    obj.visibility = "hidden";
    obj.height = "";
  }
  if (j == 16){
    if(obj.visibility == "hidden")
    {
      obj.visibility = "visible";
      obj.height = "100";
      contentString = "<img src="+Logo_Images[j]+"></img>";
      document.getElementById('Logo').innerHTML = contentString;
    }
    else if (obj.visibility == "visible")
    {
      obj.visibility = "hidden";
      obj.height = "";
    }
  }

}
  