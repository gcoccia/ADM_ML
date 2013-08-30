<ul class="nav nav-list" data-spy="affix" data-offset-top="900">
  <ul class="nav nav-pills data-extraction">
    <li class="active"><a href="javascript:void(0)" id="none" class="de-pills" onclick='Update_Listeners("none")'><?php echo $_("Animation")?></a></li>
    <li><a href="javascript:void(0)" id="point" class="de-pills" onclick='Update_Listeners("point")'><?php echo $_("Point Data")?></a></li>
    <li><a href="javascript:void(0)" id="spatial" class="de-pills" onclick='Update_Listeners("spatial")'><?php echo $_("Spatial Data")?></a></li>
  </ul>

  <li class="divider"></li>

  <form id="AnimationForm" name="AnimationForm">
    <ul class="nav nav-pills ts-selection">
      <li id="daily" class="active"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Daily")?></a></li>
      <li id="monthly"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Monthly")?></a></li>
      <li id="yearly"><a href="javascript:void(0)"class="ts-pills"><?php echo $_("Yearly")?></a></li>
    </ul>
    <li class="nav-header"><?php echo $_("Time Interval")." (".$_("dd/mm/yyyy")."):"?></li>
    <div class="control-group form-inline">
      <label><?php echo $_("Initial")?>:</label>
	    <input id="day_initial" type="text" maxlength="2" name="day_initial" value=<?php echo $day_initial ?> style="width:15px;">
      <input id="month_initial" type="text" maxlength="2" name="month_initial" value=<?php echo $month_initial ?> style="width:15px;">
      <input id="year_initial" type="text" maxlength="4" name="year_initial" value=<?php echo $year_initial ?> style="width:35px;">
	    <input type="button" value="-" class="btn btn-mini" onclick="Update_TimeStamp_MP(-1,0)">
      <input type="button" value="+" class="btn btn-mini" onclick="Update_TimeStamp_MP(1,0)">
    </div>
    <div class="control-group form-inline">
      <label><?php echo $_("Final") ?>:</label>
      <input id="day_final" type="text" maxlength="2" name="day_final" value=<?php echo $day_final ?> style="width:15px;">
      <input id="month_final" type="text" maxlength="2" name="month_final" value=<?php echo $month_final ?> style="width:15px;">
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

     <!--       Consider floating the opacity options over the map <?php echo $_("Image Opacity")?>:
      <input type="button" value="-" onclick="update_overlay_opacity(0)">
      <input type="button" value="+" onclick="update_overlay_opacity(1)"><br/> -->

<div id="Animation-Sidebar">
<?php foreach($xmlobj->variables->group as $group) { ?>
  <li class="divider"></li>
  <li class="nav-header">
      <?php echo $_("".$group["name"])?>
      <img class="question_mark" id="<?php echo $group->infobox ?>" src="icons/question_icon.png" >
  </li>
  <ul class="nav nav-list">
    <?php foreach($group->datatype as $datatype) { ?>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)">
          <?php echo $datatype['title'] ?>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <?php foreach($datatype->dataset as $dataset) { ?>
          <li><a id="<?php echo $datatype['name']."_".$dataset['name'] ?>" href="javascript:void(0)"><?php echo $dataset['name']?></a></li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
  </ul>
<?php } ?>
</div>

<!--Sidebar for point data selection - hidden by default -->
<div id="Point-Sidebar" style="display: none">
  <li class="nav-header" style="background: linear-gradient(rgb(238, 238, 238), rgb(204, 204, 204)); border-radius: 5px 5px 0px 0px;">
    <?php echo $_("Point Data Selection") ?>
  </li>
    
  <i>Click a point on the map to view time series data.</i>
  <br>
  <div class="radio inline">
    <label><input type="radio" name="plot" value="Drought_Indices" checked=checked><?php echo $_('Drought Indices') ?></label>
    <label><input type="radio" name="plot" value="Water_Balance" ><?php echo $_('Water Balance') ?></label>
    <label><input type="radio" name="plot" value="Surface_Fluxes" ><?php echo $_('Surface Fluxes') ?></label>
  </div>
  <br>
  <?php echo $_('Latitude') ?>
  <br>
  <input type="text" id="point-latitude" value=-34.6250>
  <br>
  <?php echo $_('Longitude') ?>
  <br>
  <input type="text" id="point-longitude" value=19.8750>
  <br>

</div>

<!--Sidebar for spatial data selection - hidden by default -->
<div id="Spatial-Sidebar" style="display: none">
  <li class="nav-header" style="background: linear-gradient(rgb(238, 238, 238), rgb(204, 204, 204)); border-radius: 5px 5px 0px 0px;">
    <?php echo $_("Spatial Data Selection") ?>
  </li>
  <i>Click points on the map to draw a polygon and select spatial data.</i>

 <!--  <?php echo $_('Lower Left Corner Latitude')?>: <input type="text" name="llclat_spatial_data" value=0><br>
  <?php echo $_('Lower Left Corner Longitude')?>: <input type="text" name="llclon_spatial_data" value=0><br>
  <?php echo $_('Upper Right Corner Latitude')?>: <input type="text" name="urclat_spatial_data" value=0><br>
  <?php echo $_('Upper Right Corner Longitude')?>: <input type="text" name="urclon_spatial_data" value=0><br>
   --><br>
  <?php echo $_('Spatial resolution (degrees)')?>:
  <div class="btn-group form-inline">
    <label class="radio inline">
      <input type="radio" name="sres_spatial_data" value="0.1">0.1&deg;
    </label>
    <label class="radio inline">
      <input type="radio" name="sres_spatial_data" value="0.25" checked>0.25&deg;
    </label>
    <label class="radio inline">
      <input type="radio" name="sres_spatial_data" value="1.0">1.0&deg;
    </label>
  </div>
  <br>
  <br>
  <?php echo $_('Choose the variables')?>: <br>
  <div class="checkbox inline">
    <label><input type="checkbox" name="variables_spatial_data[]" value="prec-PGF"><?php echo $_('prec_pgf')?></label>
    <label><input type="checkbox" name="variables_spatial_data[]" value="tmax-PGF"><?php echo $_('tmax_pgf')?></label>
    <label><input type="checkbox" name="variables_spatial_data[]" value="tmin-PGF"><?php echo $_('tmin_pgf')?></label>
    <label><input type="checkbox" name="variables_spatial_data[]" value="wind-PGF"><?php echo $_('wind_pgf')?></label>
    <label><input type="checkbox" name="variables_spatial_data[]" value="vcpct-VIC_DERIVED_PGF"><?php echo $_('vcpct_vic_derived_pgf')?></label>
  </div>
  <br>
  <?php echo $_('Choose the file format')?>: <br>
  <div class="radio inline">
    <label><input type="radio" name="format_spatial_data" value="arc_ascii"><?php echo $_('arc ascii')?></label>
    <label><input type="radio" name="format_spatial_data" value="netcdf" checked><?php echo $_('netcdf')?></label><br>
  </div><br>
  <?php echo $_('Email to notify when data is ready:')?><br>
  <input type="text" name="email_spatial_data"></br>
  <button type="button" onclick="Submit_Spatial_Data()"><?php echo $_('Submit Data Request')?></button>
  <br>

</div>  

</ul>
<div id="Info_Box" style="visibility: hidden;"></div>
