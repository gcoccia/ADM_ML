      <ul class="nav nav-list" data-spy="affix" data-offset-top="900">
         <div id="data-form-expand">
         <li class="nav-header" style="background: linear-gradient(rgb(238, 238, 238), rgb(204, 204, 204)); border-radius: 5px 5px 0px 0px;"><?php echo $_("Animation Interface")?></li> 
        <div class="data-form-block">
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
      	    <input id="day_initial" type="text" maxlength="2" name="day_initial" value=<?php echo $day_initial ?> style="width:18px;">
            <input id="month_initial" type="text" maxlength="2" name="month_initial" value=<?php echo $month_initial ?> style="width:18px;">
            <input id="year_initial" type="text" maxlength="4" name="year_initial" value=<?php echo $year_initial ?> style="width:35px;">
      	    <input type="button" value="-" class="btn btn-mini" onclick="Update_TimeStamp_MP(-1,0)">
            <input type="button" value="+" class="btn btn-mini" onclick="Update_TimeStamp_MP(1,0)">
         </div>
         <div class="control-group form-inline">
            <label><?php echo $_("Final") ?>:</label>
            <input id="day_final" type="text" maxlength="2" name="day_final" value=<?php echo $day_final ?> style="width:18px;">
            <input id="month_final" type="text" maxlength="2" name="month_final" value=<?php echo $month_final ?> style="width:18px;">
            <input id="year_final" type="text" maxlength="4" name="year_final" value=<?php echo $year_final ?> style="width:35px;">
            <input type="button" value="-" class="btn btn-mini" onclick="Update_TimeStamp_MP(-1,1)">
            <input type="button" value="+" class="btn btn-mini" onclick="Update_TimeStamp_MP(1,1)"><br/>
         </div>

      <table><tr>
      <td><input id="update_interval" type="button" class="btn" value=<?php echo $_("Update time interval")?> ></td>
      <td><input id="clear_all" type="button" class="btn" value=<?php echo $_("Clear all overlays")?> ></td>
      </tr>
      </table>
     </form>
     </div>
     </div>

     <!--       Consider floating the opacity options over the map <?php echo $_("Image Opacity")?>:
      <input type="button" value="-" onclick="update_overlay_opacity(0)">
      <input type="button" value="+" onclick="update_overlay_opacity(1)"><br/> -->

    <div id="data-form-expand">
    <li class="nav-header" style="background: linear-gradient(rgb(238, 238, 238), rgb(204, 204, 204)); border-radius: 5px 5px 0px 0px;"><?php echo $_("Data Extraction") ?></li>
      <div class="data-form-block">
      <div class="btn-group form-inline">
	      <label class="radio inline">
                 <input id="none" type="radio" class="de-radio" name="de-radio" checked="true" onclick='Update_Listeners("none")'> None
              </label>
              <label class="radio inline">                 
                 <input id="point" type="radio" class="de-radio" name="de-radio" onclick='Update_Listeners("point")'>Point
              </label>
              <label class="radio inline">
                 <input id="spatial" type="radio" class="de-radio" name="de-radio" onclick='Update_Listeners("spatial")'>Spatial
	      </label>
         </div>
      </div>
    </div>   

<?php foreach($xmlobj->variables->group as $group) { ?>
    <div id="data-form-expand">  
    <li id=<?php echo $_("".$group["name"])?> class="nav-header" style="background: linear-gradient(rgb(238, 238, 238), rgb(204, 204, 204)); border-radius: 5px 5px 0px 0px;">
      <?php echo $_("".$group["name"])?>
<a id=<?php echo $_("".$group["name"])?> href="#" data-toggle="popover"><img class="question_mark" src="icons/question_icon.png"></a>
  </li>
  <div class="data-form-block">
  <div class="radio inline">
    <?php foreach($group->variable as $var) { ?>
      <label class="dataset" for="<?php echo $var['dataset']."_".$var['name'] ?>">
             <input id="<?php echo $var['dataset']."_".$var['name'] ?>" 
             type="radio" class="data-radio" name="group1" value="<?php echo $var["name"]?>"
             <?php if(strcmp($xmlobj->variables->default["tag"],$var["name"]) == 0) echo "checked=true"?>> <?php echo $_("".$var["title"])?> 
      </label>
    <?php } ?>
  </div>
</div>
</div>
<?php } ?>
</ul>
