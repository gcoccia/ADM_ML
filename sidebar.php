<div id="Display_Control"> 
  <h1 id="DC_header" class="data-group-header"><?php echo $_("User Interface")?></h1> 
  <div id="DC_div" class="data-form-block" style="padding-top: 5px">
    <form id="AnimationForm" name="AnimationForm">
      <?php echo $_("Timestep").":" ?><br/>
      <input id="daily" type="radio" class="ts-radio" name="ts-radio" checked="true">Daily 
      <input id="monthly" type="radio" class="ts-radio" name="ts-radio">Monthly 
      <input id="yearly" type="radio" class="ts-radio" name="ts-radio">Yearly<br/>

      <?php echo $_("Time Interval")." (".$_("dd/mm/yyyy")."):"?><br/>
      <?php echo $_("Initial")?>: 
      <input id="day_initial" type="text" size=1 name="day_initial" value=<?php echo $day_initial ?>>
      <input id="month_initial" type="text" size=1 name="month_initial" value=<?php echo $month_initial ?>>
      <input id="year_initial" type="text" size=3 name="year_initial" value=<?php echo $year_initial ?>>
      <input type="button" value="-" onclick="Update_TimeStamp_MP(-1,0)">
      <input type="button" value="+" onclick="Update_TimeStamp_MP(1,0)"><br/>
      
      <?php echo $_("Final") ?>:  
      <input id="day_final" type="text" size=1 name="day_final" value=<?php echo $day_final ?>>
      <input id="month_final" type="text" size=1 name="month_final" value=<?php echo $month_final ?>>
      <input id="year_final" type="text" size=3 name="year_final" value=<?php echo $year_final ?>>
      <input type="button" value="-" onclick="Update_TimeStamp_MP(-1,1)">
      <input type="button" value="+" onclick="Update_TimeStamp_MP(1,1)"><br/>

      <table><tr>
      <td><input id="update_interval" type="button" value=<?php echo $_("Update time interval")?> ></td>
      <td><input id="clear_all" type="button" value=<?php echo $_("Clear all overlays")?> ></td>
      </tr>
      </table>

      <?php echo $_("Image Opacity")?>:
      <input type="button" value="-" onclick="update_overlay_opacity(0)">
      <input type="button" value="+" onclick="update_overlay_opacity(1)"><br/>

      <?php echo $_("Data Extraction").":" ?><br/>
      <input id="none" type="radio" class="de-radio" name="de-radio" checked="true" onclick='Update_Listeners("none")'> None
      <input id="point" type="radio" class="de-radio" name="de-radio" onclick='Update_Listeners("point")'>Point
      <input id="spatial" type="radio" class="de-radio" name="de-radio" onclick='Update_Listeners("spatial")'>Spatial<br/>
    </form>
  </div>
</div> 

<!-- <div id="Basins">
  <h1 id="Basins_header" class="data-group-header"><?php echo $_("Catchment Data")?> <img class="question_mark" id="4" src="icons/question_icon.png"/></h1>
  <div id="Basins_div" class="data-form-block">
    <input id="overlayImageSelect_1" type="radio" name="group1" value="Basins" onclick=update_markers()> <?php echo $_("Stream Gauges")." : ".$gauge_day_final."/".$gauge_month_final."/".$gauge_year_final ?><br />
  </div>
</div> -->

<?php foreach($xmlobj->variables->group as $group) { ?>
<div id="<?php echo $group['divtitle']?>" >
  <h1 id="<?php echo $group['divtitle']."_header"?>" class="data-group-header">
    <?php echo $_("".$group["name"])?> 
    <img class="question_mark" id="<?php echo $group->infobox ?>" src="icons/question_icon.png" >
  </h1>
  <div id="<?php echo $group['divtitle']."_div"?>" class="data-form-block">
    <?php foreach($group->variable as $var) { ?>
      <label for="<?php echo $var['dataset']."_".$var['name'] ?>">
             <input id="<?php echo $var['dataset']."_".$var['name'] ?>" 
             type="radio" class="data-radio" name="group1" value="<?php echo $var["name"]?>"
             <?php if(strcmp($xmlobj->variables->default["tag"],$var["name"]) == 0) echo "checked=true"?>> <?php echo $_("".$var["title"])?> 
      </label>
    <?php } ?>
  </div>
</div>
<?php } ?>

<div id="Info_Box" style="visibility: hidden;"></div>
