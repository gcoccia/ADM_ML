var ImageTimeArray = [];  
var ImageStrArray = [];
var ImageCounter = 0;
var overlay_opacity = 0.6;
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
  var dataset = $("ul.datalist>li>ul.dropdown-menu>li.active").find("a").attr('id');

  if(!(typeof dataset === "undefined")) {
    //Fill up the Array of image strings
    ImageTimeArray[dataset] = new Array();
    ImageStrArray[dataset] = new Array();

    if(data_dates_are_valid()) {
      ImageArrayPrep(ImageStrArray[dataset], ImageTimeArray[dataset]);
      display_colorbar(dataset);

      var time_delay = 1000*1/frames_per_second;

      overlay_obj[dataset] = new ImageOverlay(bounds, ImageStrArray[dataset][0], map_array[0], dataset);
      ChangeTimeStamp(1, ImageCounter, dataset);
      ImageCounter = 1;

      // Set the slider values
      $(function() {
        $( "#animation-slider" ).slider({
          value:1,
          min: 1,
          max: ImageStrArray[dataset].length,
          step: 1,
          disabled: false,
          slide: function( event, ui ) {
            $( "#slider-date" ).html( ImageTimeArray[dataset][ui.value] );
          }
        });
      });

      t = setInterval(next_image, time_delay);
    }
    else { // Error
      clear_all_overlays();
      // Turn off the active chosen datasets
      $("ul.datalist>li").removeClass("active");
      $("ul.datalist>li>ul.dropdown-menu>li").removeClass("active");
      $("ul.datalist>li>a>i").removeClass("icon-ok");
      $("ul.datalist>li>ul.dropdown-menu>li>a>i").removeClass("icon-ok");
      alert("Error: Dataset " + dataset + " is only available from " + data_idates[dataset] + " to " + data_fdates[dataset] + ".");
    }
  }
}

function next_image()
{
  var dataset = $("ul.datalist>li>ul.dropdown-menu>li.active").find("a").attr('id');
  if (ImageCounter == ImageTimeArray[dataset].length) ImageCounter = 0;
  overlay_obj[dataset].swap(ImageStrArray[dataset][ImageCounter]);
  ChangeTimeStamp(2, ImageCounter, dataset);
  ImageCounter += 1;
  $( "#animation-slider" ).slider("option", "value", ImageCounter);
}

function clear_image_overlays()
{
  clearInterval(t);

  for (var key in overlay_obj){
    if (overlay_obj[key] != undefined){
      overlay_obj[key].remove();
      delete overlay_obj[key];
      //Remove time stamp
      ChangeTimeStamp(3);
    }
  }
  $("#Colorbar").css({visibility: "hidden", height: ""});
  $( "#animation-slider" ).slider("option", "disabled", true);
  $( "#slider-date" ).html("");
}

function display_colorbar(dataset)
{
  var current_timestep = $("ul.ts-selection li.active").attr('id').toUpperCase();
  var cbar_img = "../IMAGES/COLORBARS/" + dataset + "_" + current_timestep + ".png";
  $("#Colorbar").css({visibility: "visible", height: "52px"});
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

  // Clear all forms
  document.getElementById("AnimationForm").reset();
  $(".data-radio").prop('checked', false);
}

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

function update_monitor_or_forecast()
{
  clear_image_overlays();
  var morf = $("ul.monitor-or-forecast>li.active").find("a").attr('id');

  if(""+morf == "monitor") {
    $("#Animation-Sidebar>div.dummy").show();
    $("li#Forecast").parent().hide();
  } else {
    $("#Animation-Sidebar>div.dummy").hide();
    $("li#Forecast").parent().show();
  }

}
