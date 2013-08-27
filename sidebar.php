      <ul class="nav nav-list" data-spy="affix" data-offset-top="900">
        <li class="nav-header" style="background: linear-gradient(rgb(238, 238, 238), rgb(204, 204, 204))"><?php echo $_("Animation Interface")?></li> 
      <form id="AnimationForm" name="AnimationForm">
         <div class="btn-group form-inline">
	   <label class="control-label"><?php echo $_("Timestep").":" ?></label>
	      <label class="radio inline">
                 <input id="daily" type="radio" name="ts-radio" checked="true"> Daily 
              </label>
	      <label class="radio inline">
                 <input id="monthly" type="radio" name="ts-radio"> Monthly 
              </label>
              <label class="radio inline">
                 <input id="yearly" type="radio" name="ts-radio"> Yearly
              </label>
         </div>
         <label class="control-label"><?php echo $_("Time Interval")." (".$_("dd/mm/yyyy")."):"?></label>
        <div class="control-group form-inline">
          <label><?php echo $_("Initial")?>:</label>
      	    <input id="day_initial" type="text" class="span2" maxlength="2" name="day_initial" value=<?php echo $day_initial ?>>
            <input id="month_initial" type="text" class="span2" maxlength="2" name="month_initial" value=<?php echo $month_initial ?>>
            <input id="year_initial" type="text" class="span2" maxlength="4" name="year_initial" value=<?php echo $year_initial ?>>
      	    <input type="button" value="-" class="btn btn-small" onclick="Update_TimeStamp_MP(-1,0)">
            <input type="button" value="+" class="btn btn-small" onclick="Update_TimeStamp_MP(1,0)">
         </div>
         <div class="control-group form-inline">
            <label><?php echo $_("Final") ?>:</label>
            <input id="day_final" type="text" class="span2" maxlength="2" name="day_final" value=<?php echo $day_final ?>>
            <input id="month_final" type="text" class="span2" maxlength="2" name="month_final" value=<?php echo $month_final ?>>
            <input id="year_final" type="text" class="span2" maxlength="4" name="year_final" value=<?php echo $year_final ?>>
            <input type="button" value="-" class="btn btn-small" onclick="Update_TimeStamp_MP(-1,1)">
            <input type="button" value="+" class="btn btn-small" onclick="Update_TimeStamp_MP(1,1)"><br/>
         </div>

      <table><tr>
      <td><input id="update_interval" type="button" class="btn" value=<?php echo $_("Update time interval")?> ></td>
      <td><input id="clear_all" type="button" class="btn" value=<?php echo $_("Clear all overlays")?> ></td>
      </tr>
      </table>
     </form>
     </ul>
<!--       Consider floating the opacity options over the map <?php echo $_("Image Opacity")?>:
      <input type="button" value="-" onclick="update_overlay_opacity(0)">
      <input type="button" value="+" onclick="update_overlay_opacity(1)"><br/> -->
<!--
<div id="Data Extraction" class="sidebar-block">
  <h1 id="de_header" class="data-group-header"><?php echo $_("Data Extraction") ?></h1>
  <div id="de_div" class="data-form-block">
    <input id="none" type="radio" class="de-radio" name="de-radio" checked="true" onclick='Update_Listeners("none")'> None
    <input id="point" type="radio" class="de-radio" name="de-radio" onclick='Update_Listeners("point")'>Point
    <input id="spatial" type="radio" class="de-radio" name="de-radio" onclick='Update_Listeners("spatial")'>Spatial
  </div>
</div>
-->
<!-- <div id="Basins">
  <h1 id="Basins_header" class="data-group-header"><?php echo $_("Catchment Data")?> <img class="question_mark" id="4" src="icons/question_icon.png"/></h1>
  <div id="Basins_div" class="data-form-block">
    <input id="overlayImageSelect_1" type="radio" name="group1" value="Basins" onclick=update_markers()> <?php echo $_("Stream Gauges")." : ".$gauge_day_final."/".$gauge_month_final."/".$gauge_year_final ?><br />
  </div>
</div> -->
<!--
<?php foreach($xmlobj->variables->group as $group) { ?>
<div id="<?php echo $group['divtitle']?>" class="sidebar-block">
  <h1 id="<?php echo $group['divtitle']."_header"?>" class="data-group-header">
    <?php echo $_("".$group["name"])?> 
    <img class="question_mark" id="<?php echo $group->infobox ?>" src="icons/question_icon.png" >
  </h1>
  <div id="<?php echo $group['divtitle']."_div"?>" class="data-form-block">
    <?php foreach($group->variable as $var) { ?>
      <label class="dataset" for="<?php echo $var['dataset']."_".$var['name'] ?>">
             <input id="<?php echo $var['dataset']."_".$var['name'] ?>" 
             type="radio" class="data-radio" name="group1" value="<?php echo $var["name"]?>"
             <?php if(strcmp($xmlobj->variables->default["tag"],$var["name"]) == 0) echo "checked=true"?>> <?php echo $_("".$var["title"])?> 
      </label>
    <?php } ?>
  </div>
</div>
<?php } ?>

<div id="Info_Box" style="visibility: hidden;"></div>
-->
