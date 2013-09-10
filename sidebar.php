<ul class="nav nav-list" data-spy="affix" data-offset-top="900">
  <ul class="nav nav-pills data-extraction">
    <li class="active"><a href="javascript:void(0)" id="none" class="de-pills" onclick='Update_Listeners("none")'><?php echo $_("Animation")?></a></li>
    <li><a href="javascript:void(0)" id="point" class="de-pills" onclick='Update_Listeners("point")'><?php echo $_("Point Data")?></a></li>
    <li><a href="javascript:void(0)" id="spatial" class="de-pills" onclick='Update_Listeners("spatial")'><?php echo $_("Spatial Data")?></a></li>
  </ul>

  <!-- Select between Monitor and Forecast Data for Animation -->
  <div id="monitor-or-forecast-div">
    <li class="divider"></li>
    <ul class="nav nav-pills monitor-or-forecast">
      <li class="active"><a href="javascript:void(0)" id="monitor" class="mf-pills" onclick=''><?php echo $_("Monitor")?></a></li>
      <li><a href="javascript:void(0)" id="forecast" class="mf-pills" onclick=''><?php echo $_("Forecast")?></a></li>
    </ul>
  </div>

  <li class="divider"></li>
  <div class="dummy">
  <li class="nav-header"><?php echo $_("Time Interval")." (".$_("dd/mm/yyyy").")"?></li>
  <div class="data-form-block">
  <form id="AnimationForm" name="AnimationForm">
    <ul class="nav nav-pills ts-selection">
      <li id="daily" class="active"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Daily")?></a></li>
      <li id="monthly"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Monthly")?></a></li>
      <li id="yearly"><a href="javascript:void(0)"class="ts-pills"><?php echo $_("Yearly")?></a></li>
    </ul>
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
  </div>
  </div>
     <!--       Consider floating the opacity options over the map <?php echo $_("Image Opacity")?>:
      <input type="button" value="-" onclick="update_overlay_opacity(0)">
      <input type="button" value="+" onclick="update_overlay_opacity(1)"><br/> -->

 <!-- Slider for animation -->
<div id="slider-div">
  <li class="divider"></li>
  <div id="slider-date"></div>
  <span><i id="pause-or-continue" class="icon-pause" style="display:none"></i></span>
  <div id="animation-slider"></div>
</div>

<div id="Animation-Sidebar">
<?php foreach($xmlobj->variables->group as $group) { ?>
  <div class="dummy">
  <li class="divider"></li>
  <li id=<?php echo $_("".$group["name"])?> class="nav-header">
      <?php echo $_("".$group["name"])?>
      <a id=<?php echo $_("".$group["name"])?> href="#" data-toggle="popover"><img class="question_mark" src="icons/question_icon.png"></a>
  </li>
  <div class="data-form-block">
  <ul class="nav nav-list datalist">
    <?php foreach($group->datatype as $datatype) { ?>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"><i></i>
          <?php echo $datatype['title'] ?>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li class="nav-header"><?php echo $_("Dataset")?></li>
          <?php foreach($datatype->dataset as $dataset) { ?>
          <li><a id="<?php echo $dataset['name']."_".$datatype['name'] ?>" href="javascript:void(0)"><i></i><?php echo $dataset['name']?></a></li>
          <?php } ?>
        </ul>
      </li>
    <?php } ?>
  </ul>
  </div>
  </div>
<?php } ?>
</div>

<!--Sidebar for point data selection - hidden by default -->
<div id="Point-Sidebar" style="display: none">
  <li class="divider"></li>
  <li class="nav-header"><?php echo $_("Point Data Selection") ?></li>
  <div class="data-form-block">  
  <i><?php echo $_("Click a point on the map to view time series data.") ?></i>
  <br>
  <div class="radio inline">
    <label><input type="radio" name="plot" value="Drought_Indices" checked=checked><?php echo $_('Drought Indices') ?></label>
    <label><input type="radio" name="plot" value="Water_Balance" ><?php echo $_('Water Balance') ?></label>
    <label><input type="radio" name="plot" value="Surface_Fluxes" ><?php echo $_('Surface Fluxes') ?></label>
  </div>
  <br>
  <?php echo $_('Latitude')?>: <span id="point-latitude"></span>
  <br>
  <?php echo $_('Longitude')?>: <span id="point-longitude"></span>
  <br>
</div>
</div>

<!--Sidebar for spatial data selection - hidden by default -->
<div id="Spatial-Sidebar" style="display: none">
  <li class="divider"></li>
  <li class="nav-header"><?php echo $_("Spatial Data Selection") ?></li>
  <i><?php echo $_("Click points on the map to draw a polygon and select spatial data. Then select variables below.") ?></i>
  <br>

    <?php foreach($xmlobj->variables->group as $group) { ?>
        <div class="dummy">
          <li class="divider"></li>
          <li id=<?php echo $_("".$group["name"])."_spatial"?> class="nav-header">
              <?php echo $_("".$group["name"])?>
              <a id=<?php echo $_("".$group["name"])?> href="#" data-toggle="popover"><img class="question_mark" src="icons/question_icon.png"></a>
          </li>
          <div class="data-form-block" style="display:none">

          <ul class="nav nav-list spatial-datalist">
              <?php foreach($group->datatype as $datatype) { ?>
                <li class="dropdown">
                  <a class="dropdown-toggle" data-toggle="dropdown" href="javascript:void(0)"><i></i>
                    <?php echo $datatype['title'] ?>
                    <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="nav-header"><?php echo $_("Dataset")?></li>
                    <?php foreach($datatype->dataset as $dataset) { ?>
                    <li>
                      <label><input type="checkbox" name="variables_spatial_data[]" value="<?php echo $datatype['name']."-".$dataset['name'] ?>" href="javascript:void(0)"><i></i><?php echo $dataset['name']?></label>
                    </li>
                    <?php } ?>
                  </ul>
                </li>
      <?php   } ?>
          </ul>
        </div>
        </div>
    <?php } ?>

  <li class="divider"></li>

  <div class="btn-group form-inline">
    <label class="radio inline control-label"><?php echo $_('Resolution')?>: </label>
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

  <div class="btn-group form-inline" style="margin-left: 0px">
    <label class="radio inline control-label"><?php echo $_('File Format')?>: </label>
    <label class="radio inline"><input type="radio" name="format_spatial_data" value="arc_ascii"><?php echo $_('arc ascii')?></label>
    <label class="radio inline"><input type="radio" name="format_spatial_data" value="netcdf" checked><?php echo $_('netcdf')?></label><br>
  </div>
  <br>
  <br>
  <input type="text" name="email_spatial_data" placeholder="<?php echo $_('Email address')?>"></br>
  <button type="button" onclick="Submit_Spatial_Data()"><?php echo $_('Submit Data Request')?></button>
  <br>
  <?php echo $_('Estimated Download Size')?>: <span id="estimated-download-size">0</span>
  <br>

</div>
</ul>
