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

<div id="Forcings">
  <h1 id="Forcing_header" onclick=animate_div("Forcing_div")><?php echo $_("Meteorology")?> <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(1)" onmouseout="Info_Box_Call(1)"></h1>
  <div id="Forcing_div" style="visibility:visible;">
    <input id="overlayImageSelect_5" type="radio" name="group1" value="Prec" onclick=animate_overlay(5)> <?php echo $_("Precipitation (mm/day)")?> <br/>
    <input id="overlayImageSelect_6" type="radio" name="group1" value="Tmax" onclick=animate_overlay(6)> <?php echo $_("Maximum Temperature (C)")?> <br/>
    <input id="overlayImageSelect_7" type="radio" name="group1" value="Temp" onclick=animate_overlay(7)> <?php echo $_("Minimum Temperature (C)")?> <br/>
    <input id="overlayImageSelect_8" type="radio" name="group1" value="Wind" onclick=animate_overlay(8)> <?php echo $_("Wind (m/s)")?> <br/> 
  </div>
</div>

<div id="Model">
  <h1 id="Model_header" onclick=animate_div("Model_div")><?php echo $_("Hydrologic Variables")?> <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(2)" onmouseout="Info_Box_Call(2)"></h1>
  <div id="Model_div" style="visibility:visible;">
    <input id="overlayImageSelect_9" type="radio" name="group1" value="Evap" onclick=animate_overlay(9)> <?php echo $_("Evaporation (mm/day)")?><br />
    <input id="overlayImageSelect_10" type="radio" name="group1" value="Sm_1" onclick=animate_overlay(10)> <?php echo $_("Soil Moisture(%) - Layer 1")?><br />
    <input id="overlayImageSelect_11" type="radio" name="group1" value="Sm_2" onclick=animate_overlay(11)> <?php echo $_("Soil Moisture(%) - Layer 2")?><br />
    <input id="overlayImageSelect_14" type="radio" name="group1" value="runoff" onclick=animate_overlay(14)> <?php echo $_("Surface Runoff (mm/day)")?> <br />
  </div>
</div>

<div id="Drought">
  <h1 id="Drought_header" onclick=animate_div("Drought_div")><?php echo $_("Drought Products")?></h1>
  <div id="Drought_div" style="visibility:visible;">
  <input id="overlayImageSelect_15" input type="radio" name="group1" value="smqall" onclick=animate_overlay(15) checked = "checked" > <?php echo $_("Drought Index")?><img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(3)" onmouseout="Info_Box_Call(3)"><br/>
    <!--<input id="overlayImageSelect_16" input type="radio" name="group1" value="SMOS_SW2F" onclick=animate_overlay(16)> SARI - SMOS Index <a href="http://www.cesbio.ups-tlse.fr/SMOS_blog/?page_id=2589" target="_blank">(CESBIO)</a> <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(5)" onmouseout="Info_Box_Call(5)"> <br />
        <input id="overlayImageSelect_17" type="radio" name="group1" onclick=SPIselect(1) style="float: left"> 
    <div id="SPIdiv" class="SPIdiv">SPI <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(5)" onmouseout="Info_Box_Call(5)"></div>
    -->
  <br/>
  </div>
</div>

<div id="Basins">
  <h1 id="Basins_header" onclick=animate_div("Basins_div")><?php echo $_("Catchment Data")?> <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(4)" onmouseout="Info_Box_Call(4)"></h1>
  <div id="Basins_div" style="visibility:visible;">
    <input id="overlayImageSelect_1" type="radio" name="group1" value="Basins" onclick=update_markers()> <?php echo $_("Stream Gauges")." : ".$gauge_day_final."/".$gauge_month_final."/".$gauge_year_final ?><br />
  </div>
</div>

<!--<div id="RemoteSensing">
    <h1 id="RS_header" onclick=animate_div("RS_div")>Remote Sensing Data <img id="question_mark" src="icons/question_icon.png" onmouseover="Info_Box_Call(5)" onmouseout="Info_Box_Call(5)"></h1>
    <div id="RS_div" style="visibility:visible;">
    <input id="overlayImageSelect_16" input type="radio" name="group1" value="SMOS_SW2F" onclick=animate_overlay(16)> SARI - SMOS Index <a href="http://www.cesbio.ups-tlse.fr/SMOS_blog/?page_id=2589" target="_blank">(CESBIO)</a>  <br />
    </div>
    </form>
</div>-->

<h1 id="sidebar_header" onclick=animate_sidebar()><img src="icons/Arrow_up.png"/></h1>
<div id="Info_Box" style="visibility:hidden;"></div>