<?php

// This data structure, or something like it, could be read in from an XML config file
$sidebar_groups = array("Forcing" =>
                        array("products" => array("5" => array("value" => "Prec", "title" => $_("Precipitation (mm/day)")),
                                                  "6" => array("value" => "Tmax", "title" => $_("Maximum Temperature (C)")),
                                                  "7" => array("value" => "Temp", "title" => $_("Minimum Temperature (C)")),
                                                  "8" => array("value" => "Wind", "title" => $_("Wind (m/s)"))),
                              "title" => $_("Meteorology")),

                        "Model" =>
                        array("products" => array("9" => array("value" => "Evap", "title" => $_("Evaporation (mm/day)")),
                                                  "10" => array("value" => "Sm_1", "title" => $_("Soil Moisture(%) - Layer 1")),
                                                  "11" => array("value" => "Sm_2", "title" => $_("Soil Moisture(%) - Layer 2")),
                                                  "14" => array("value" => "runoff", "title" => $_("Surface Runoff (mm/day)"))),
                              "title" => $_("Hydrologic Variables")),

                        "Drought" =>
                        array("products" => array("15" => array("value" => "smqall", "title" => $_("Drought Index"))),
                              "title" => $_("Drought Products"))
                        );

$sidebar_default = 15;

?>

<div id="Display_Control"> 
  <h1 id="DC_header" onclick=animate_div("DC_div")><?php echo $_("User Interface")?></h1> 
  <div id="DC_div" style="visibility:visible;">
    <form name="AnimationForm">
      <?php echo $_("Time Interval")."(".$_("dd/mm/yyyy")."):"?><br/>
      <?php echo $_("Initial")?>: 
      <input id="day_initial" type="text" size=1 name="day_initial" value=<?php echo $day_initial ?>>
      <input id="month_initial" type="text" size=1 name="month_initial" value=<?php echo $month_initial ?>>
      <input id="year_initial" type="text" size=3 name="year_initial" value=<?php echo $year_initial ?>>
      <input type="button" value="-" onclick="Update_TimeStamp_MP(0,0)">
      <input type="button" value="+" onclick="Update_TimeStamp_MP(1,0)"><br/>
      
      <?php echo $_("Final") ?>:  
      <input id="day_final" type="text" size=1 name="day_final" value=<?php echo $day_final ?>>
      <input id="month_final" type="text" size=1 name="month_final" value=<?php echo $month_final ?>>
      <input id="year_final" type="text" size=3 name="year_final" value=<?php echo $year_final ?>>
      <input type="button" value="-" onclick="Update_TimeStamp_MP(0,1)">
      <input type="button" value="+" onclick="Update_TimeStamp_MP(1,1)"><br/>

      <?php echo $_("Days per second")?>:  <input type="text" size=1 name="frames_per_second" value=1><br/>
      <table><tr>
      <td><input type="button" value=<?php echo $_("Update time interval")?> onclick="animate_overlay_submit()"></td>
      <td><input type="button" value=<?php echo $_("Clear all overlays")?> onclick="ClearAllOverlays()"></td>
      </tr>
      </table>

      <?php echo $_("Image Opacity")?>:
      <input type="button" value="-" onclick="update_overlay_opacity(0)">
      <input type="button" value="+" onclick="update_overlay_opacity(1)"><br/>
    </form>
  </div>
</div> 

<div id="Basins">
  <h1 id="Basins_header" onclick=animate_div("Basins_div")><?php echo $_("Catchment Data")?> <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(4)" onmouseout="Info_Box_Call(4)"></h1>
  <div id="Basins_div" style="visibility:visible;">
    <input id="overlayImageSelect_1" type="radio" name="group1" value="Basins" onclick=update_markers()> <?php echo $_("Stream Gauges")." : ".$gauge_day_final."/".$gauge_month_final."/".$gauge_year_final ?><br />
  </div>
</div>

<?php
foreach($sidebar_groups as $key => $value) {
?>

<div id=<?php echo $key?> >
  <h1 id=<?php echo $key."_header"?> onclick=animate_div("Forcing_div")><?php echo $_("Meteorology")?> <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(1)" onmouseout="Info_Box_Call(1)"></h1>
  <div id="Forcing_div" style="visibility:visible;">
    <input id="overlayImageSelect_5" type="radio" name="group1" value="Prec" onclick=animate_overlay(5)> <?php echo $_("Precipitation (mm/day)")?> <br/>
    <input id="overlayImageSelect_6" type="radio" name="group1" value="Tmax" onclick=animate_overlay(6)> <?php echo $_("Maximum Temperature (C)")?> <br/>
    <input id="overlayImageSelect_7" type="radio" name="group1" value="Temp" onclick=animate_overlay(7)> <?php echo $_("Minimum Temperature (C)")?> <br/>
    <input id="overlayImageSelect_8" type="radio" name="group1" value="Wind" onclick=animate_overlay(8)> <?php echo $_("Wind (m/s)")?> <br/> 
  </div>
</div>

<?php
}
?>

<h1 id="sidebar_header" onclick=animate_sidebar()><img src="icons/Arrow_up.png"/></h1>
<div id="Info_Box" style="visibility:hidden;"></div>