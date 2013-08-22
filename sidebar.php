<div id="Display_Control"> 
  <h1 id="DC_header" class="data-group-header"><?php echo $_("User Interface")?></h1> 
  <div id="DC_div" class="data-form-block">
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
      <input id="<?php echo "overlayImageSelect_".$var['num']?>" 
             type="radio" name="group1" value="<?php echo $var["name"]?>"
             onclick=animate_overlay(<?php echo $var['num']?>)
             <?php if($xmlobj->variables->default['num'] == $var['num']) echo "checked=true"?>> <?php echo $_("".$var["title"])?> <br/>
    <?php } ?>
  </div>
</div>
<?php } ?>

<div id="Info_Box" style="visibility: hidden;"></div>