<ul class="nav nav-list" data-spy="affix" data-offset-top="900">
  <ul class="nav nav-pills data-extraction">
    <li class="active"><a href="javascript:void(0)" id="none" class="de-pills" onclick='Update_Listeners("none")'><?php echo $_("Animation")?></a></li>
    <li id="pointpill"><a href="javascript:void(0)" id="point" class="de-pills" onclick='Update_Listeners("point")'><?php echo $_("Point Data")?></a></li>
    <li id="spatialpill"><a href="javascript:void(0)" id="spatial" class="de-pills" onclick='Update_Listeners("spatial")'><?php echo $_("Spatial Data")?></a></li>
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
  <li id="TimeInterval" class="nav-header"><?php echo $_("Time Interval")." (".$_("dd/mm/yyyy").")"?></li>
  <div class="data-form-block">
  <form id="AnimationForm" name="AnimationForm">
    <ul class="nav nav-pills ts-selection">
      <li id="daily" class="active"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Daily")?></a></li>
      <li id="monthly"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Monthly")?></a></li>
      <li id="yearly"><a href="javascript:void(0)" class="ts-pills"><?php echo $_("Yearly")?></a></li>
    </ul>
    <div id="initial-date-inputs" class="control-group form-inline">
      <label style="width:40px;"><?php echo $_("Initial")?>:</label>
      <input id="day_initial" type="text" maxlength="2" name="day_initial" value=<?php echo $day_initial ?> style="width:20px;">
      <input id="month_initial" type="text" maxlength="2" name="month_initial" value=<?php echo $month_initial ?> style="width:20px;">
      <input id="year_initial" type="text" maxlength="4" name="year_initial" value=<?php echo $year_initial ?> style="width:35px;">
	    <input type="button" value="-" class="btn btn-mini" onclick="Update_TimeStamp_MP(-1,0)">
      <input type="button" value="+" class="btn btn-mini" onclick="Update_TimeStamp_MP(1,0)">
    </div>
    <div id="final-date-inputs" class="control-group form-inline">
      <label style="width:40px;"><?php echo $_("Final") ?>:</label>
      <input id="day_final" type="text" maxlength="2" name="day_final" value=<?php echo $day_final ?> style="width:20px;">
      <input id="month_final" type="text" maxlength="2" name="month_final" value=<?php echo $month_final ?> style="width:20px;">
      <input id="year_final" type="text" maxlength="4" name="year_final" value=<?php echo $year_final ?> style="width:35px;">
      <input type="button" value="-" class="btn btn-mini" onclick="Update_TimeStamp_MP(-1,1)">
      <input type="button" value="+" class="btn btn-mini" onclick="Update_TimeStamp_MP(1,1)"><br/>
    </div>
  <table id="Animation-Update"><tr>
  <td><input id="update_interval" type="button" class="btn" value=<?php echo $_("Update")?> ></td>
  <td><input id="clear_all" type="button" class="btn" value=<?php echo $_("Clear")?> ></td>
  </tr>
  </table>
  </form>
  </div>
  </div>

 <!-- Slider for animation -->
<div id="slider-div" style="display:none">
  <span id="slider-date"></span>
  <span style="padding-left:10px"><i id="pause-or-continue" class="icon-pause icon-large"></i></span>
  <div id="animation-slider"></div>
</div>

<div id="Animation-Sidebar">
<?php foreach($xmlobj->variables->group as $group) { ?>
  <div class="dummy">
  <li class="divider"></li>
  <li id=<?php echo ("".$group["name"])?> class="nav-header">
      <?php echo $_("".$group["name"])?>
      <!--<a id=<?php echo $_("".$group["name"])?> href="#" data-toggle="popover"><img class="question_mark" src="icons/question_icon.png"></a>-->
  </li>
  <div class="data-form-block">
  <ul class="nav nav-list datalist">
    <?php foreach($group->datatype as $datatype) { ?>
      <li class="dropdown">
        <a id=<?php echo $datatype["name"]?> data-toggle="dropdown" href="javascript:void(0)"><i></i>
          <?php echo $_("".$datatype['title'])?>
          <b class="caret"></b>
        </a>
        <ul class="dropdown-menu">
          <li class="nav-header"><?php echo $_("Dataset")?></li>
          <?php foreach($datatype->dataset as $dataset) { ?>
          <li><a id="<?php echo $datatype['name']."--".$dataset['name'] ?>" href="javascript:void(0)"><i></i><?php echo $_("".$dataset["title"])?></a></li>
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
  
  <ul id="point-corm" class="nav nav-pills click-or-manual">
    <li class="active"><a href="javascript:void(0)" id="point-mapclick" class="corm-pills-point"><?php echo $_("Map Click")?></a></li>
    <li><a href="javascript:void(0)" id="point-manual" class="corm-pills-point"><?php echo $_("Manual Entry")?></a></li>
  </ul>

  <div id="point-ll-mapclick">
    <?php echo $_('Latitude')?>: <span id="point-latitude"></span>
    <br>
    <?php echo $_('Longitude')?>: <span id="point-longitude"></span>
    <br>
  </div>

  <div id="point-ll-manual" style="display:none">
    <form class="form-horizontal manual-entry-form" id="point-manual-entry-form">
      <div class="control-group">
        <label class="control-label" for="point-manual-latitude"><?php echo $_('Latitude')?>:</label>
        <div class="controls">
          <input type="text" id="point-manual-latitude">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="point-manual-longitude"><?php echo $_('Longitude')?>:</label>
        <div class="controls">
          <input type="text" id="point-manual-longitude">
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <button id="point-manual-submit" type="submit" class="btn">Submit</button>
        </div>
      </div>
    </form>
  </div>

  <div class="radio inline">
    <label><input type="radio" name="plot" value="Indices" checked=checked><?php echo $_('Indices') ?></label>
    <label><input type="radio" name="plot" value="Water_Balance" ><?php echo $_('Water Balance') ?></label>
    <label><input type="radio" name="plot" value="Surface_Fluxes" ><?php echo $_('Surface Fluxes') ?></label>
    <label><input type="radio" name="plot" value="Streamflow" ><?php echo $_('Streamflow') ?></label>
    <label><input type="radio" name="plot" value="Soil_Moisture_1" ><?php echo $_('Soil Moisture (Layer 1)') ?></label>
    <label><input type="radio" name="plot" value="Soil_Moisture_2" ><?php echo $_('Soil Moisture (Layer 2)') ?></label>
    <label><input type="radio" name="plot" value="Vegetation" ><?php echo $_('Vegetation') ?></label>
    <label><input type="radio" name="plot" value="Meteorology" ><?php echo $_('Meteorology') ?></label>
  </div>
  <br>
  <i><?php echo $_('Create Corresponding Data File?') ?></i>
  <br>
  <div class="radio inline">
    <label><input type="radio" name="Create_Text_Data" value="yes"><?php echo $_('Yes') ?></label>
    <label><input type="radio" name="Create_Text_Data" value="no" checked=checked><?php echo $_('No') ?></label>
  </div>
  <br>
  <a id='point_data_link' href="" target="_blank" download="" style="display:none">Download Data</a>
  <i><?php echo $_('Only the last 1000 timesteps from the selected final date will be displayed') ?></i>
</div>
</div>

<!--Sidebar for spatial data selection - hidden by default -->
<div id="Spatial-Sidebar" style="display: none">
  <li class="divider"></li>
  <li class="nav-header"><?php echo $_("Spatial Data Selection") ?></li>
  
  <ul id="spatial-corm" class="nav nav-pills click-or-manual">
    <li class="active"><a href="javascript:void(0)" id="spatial-mapclick" class="corm-pills-spatial"><?php echo $_("Map Click")?></a></li>
    <li><a href="javascript:void(0)" id="spatial-manual" class="corm-pills-spatial"><?php echo $_("Manual Entry")?></a></li>
  </ul>

  <div id="spatial-ll-manual" style="display:none">
    <form class="form-horizontal manual-entry-form" id="spatial-manual-entry-form">
      <div class="control-group">
        <label class="control-label" for="spatial-manual-min-latitude"><?php echo $_('Min. Lat.')?>:</label>
        <div class="controls">
          <input type="text" id="spatial-manual-min-latitude">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="spatial-manual-max-latitude"><?php echo $_('Max. Lat.')?>:</label>
        <div class="controls">
          <input type="text" id="spatial-manual-max-latitude">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="spatial-manual-min-longitude"><?php echo $_('Min. Long.')?>:</label>
        <div class="controls">
          <input type="text" id="spatial-manual-min-longitude">
        </div>
      </div>
      <div class="control-group">
        <label class="control-label" for="spatial-manual-max-longitude"><?php echo $_('Max. Long.')?>:</label>
        <div class="controls">
          <input type="text" id="spatial-manual-max-longitude">
        </div>
      </div>
      <div class="control-group">
        <div class="controls">
          <button id="spatial-manual-submit" type="submit" class="btn">Submit</button>
        </div>
      </div>
    </form>
  </div>

  <ul id="currently-selected-vars" class="nav nav-list">
  </ul>

    <?php foreach($xmlobj->variables->group as $group) { ?>
        <div class="dummy">
          <li class="divider"></li>
          <li id=<?php echo ("".$group["name"])."_spatial"?> class="nav-header expandable">
              <?php echo $_("".$group["name"])?>
              <b class="caret centeredcaret"></b>
          </li>
          <div class="data-form-block" style="display:none">

          <ul class="nav nav-list spatial-datalist">
              <?php foreach($group->datatype as $datatype) { ?>
                <li class="dropdown">
                  <a id=<?php echo $datatype['name']?> data-toggle="dropdown" href="javascript:void(0)"><i></i>
                    <?php echo $_("".$datatype['title']) ?>
                    <b class="caret"></b>
                  </a>
                  <ul class="dropdown-menu">
                    <li class="nav-header"><?php echo $_("Dataset")?></li>
                    <?php foreach($datatype->dataset as $dataset) { ?>
                      <li><a id="<?php echo $datatype['name']."--".$dataset['name'] ?>" href="javascript:void(0)"><i class="icon-plus-sign" style="color:#5cb85c"></i><?php echo $_("".$dataset['title'])?></a></li>
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
    <!--<label class="radio inline control-label"><?php echo $_('Resolution')?>: </label>-->
    <!--<label class="radio inline">
      <input type="radio" name="sres_spatial_data" value="0.25" checked>0.25&deg;
    </label>-->
    <!--
    <label class="radio inline">
      <input type="radio" name="sres_spatial_data" value="0.25" checked>0.25&deg;
    </label>
    <label class="radio inline">
      <input type="radio" name="sres_spatial_data" value="1.0">1.0&deg;
    </label>
    -->
  </div>

  <div class="btn-group form-inline" style="margin-left: 0px">
    <label class="radio inline control-label"><?php echo $_('File Format')?>: </label>
    <label class="radio inline"><input type="radio" name="format_spatial_data" value="arc_ascii"><?php echo $_('arc ascii')?></label>
    <label class="radio inline"><input type="radio" name="format_spatial_data" value="netcdf" checked><?php echo $_('netcdf')?></label><br>
  </div>
  <br>
  <br>
  <input type="text" id = "email_spatial_data" name="email_spatial_data" placeholder="<?php echo $_('Email address')?>"><br>
  <button type="button" id="submit_request_button" onclick="Submit_Spatial_Data()"><?php echo $_('Submit Data Request')?></button>
  <br>
  <span id="est_dl_size_holder"><?php echo $_('Estimated Download Size')?>: <span id="estimated-download-size">0</span></span>
  <br>
  <p id="npts_warning" style="color:red; display:none"><?php echo $_("Please select a region.")?></p>
  <p id="nvars_warning" style="color:red; display:none"><?php echo $_("Please select variables.")?></p>
  <p id="email_warning" style="color:red; display:none"><?php echo $_("Please provide an email.")?></p>
  <p id="download_size_warning" style="color:red; display:none"><?php echo $_("The current request exceeds 1 GB. Please reduce the size of your request.")?></p>

</div>
</ul>
