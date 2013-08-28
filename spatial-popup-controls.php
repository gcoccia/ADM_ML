<?php

// Set up the translation scripts
require_once('php-gettext-1.0.11/gettext.inc');
$locale = BP_LANG;
$textdomain="adm";
if (empty($locale))
  $locale = 'en';
if (isset($_GET['locale']) && !empty($_GET['locale']))
  $locale = $_GET['locale'];
putenv('LANGUAGE='.$locale);
putenv('LANG='.$locale);
putenv('LC_ALL='.$locale);
putenv('LC_MESSAGES='.$locale);
T_setlocale(LC_ALL,$locale);
T_setlocale(LC_CTYPE,$locale);
$locales_dir = dirname(__FILE__).'/i18n';
T_bindtextdomain($textdomain,$locales_dir);
T_bind_textdomain_codeset($textdomain, 'UTF-8'); 
T_textdomain($textdomain);
$_ = 'T_';

// If post variables are not set, error.
// (don't let anyone access this directly)
if (!isset($_POST["minlat"]) || !isset($_POST["maxlat"]) || !isset($_POST["minlon"]) || !isset($_POST["maxlon"]))
{
  header('HTTP/1.1 500 Internal Server Error: Parameters not defined in request');
  header('Content-Type: application/json');
  die('ERROR');
}

?>

<!DOCTYPE html> 

<!--Close window box-->
<a onclick="Data_Extraction_Popup()" style="width:80px; height:10px"><?php echo $_('Close Window') ?></a>
<!--Controls-->
<div id="popup_controls">

  <?php echo $_('Time Step') ?>:<br>
  <input type="radio" name="tstep_spatial_data" value="DAILY" checked><?php echo $_('Daily') ?>
  <input type="radio" name="tstep_spatial_data" value="MONTHLY"><?php echo $_('Monthly') ?>
  <input type="radio" name="tstep_spatial_data" value="YEARLY"><?php echo $_('Yearly') ?>
  <br>
  <br>
  <?php echo $_('Choose the Initial Time Stamp (after 1/1/1950)')?>:<br>
  <?php echo $_('Year') ?>: <input type="text" name="iyear_spatial_data" value="1950">
  <?php echo $_('Month') ?>: <input type="text" name="imonth_spatial_data" value="1">
  <?php echo $_('Day') ?>: <input type="text" name="iday_spatial_data" value="1"><br>
  <br>
  <?php echo $_('Choose the Final Time Stamp (3 days before realtime)')?>:<br>
  <?php echo $_('Year') ?>: <input type="text" name="fyear_spatial_data" value="1950">
  <?php echo $_('Month') ?>: <input type="text" name="fmonth_spatial_data" value="1">
  <?php echo $_('Day') ?>: <input type="text" name="fday_spatial_data" value="1"><br>
  <br>
  <?php echo $_('Choose the spatial box dimensions')?>:<br>
  <?php echo $_('Lower Left Corner Latitude')?>: <input type="text" name="llclat_spatial_data" value="' + minlat + '"><br>
  <?php echo $_('Lower Left Corner Longitude')?>: <input type="text" name="llclon_spatial_data" value="' + minlon + '"><br>
  <?php echo $_('Upper Right Corner Latitude')?>: <input type="text" name="urclat_spatial_data" value="' + maxlat + '"><br>
  <?php echo $_('Upper Right Corner Longitude')?>: <input type="text" name="urclon_spatial_data" value="' + maxlon + '"><br>
  <br>
  <?php echo $_('Define the spatial resolution (degrees)')?>:<br>
  <input type="radio" name="sres_spatial_data" value="0.1"><?php echo $_('0.1 degree')?>
  <input type="radio" name="sres_spatial_data" value="0.25" checked><?php echo $_('0.25 degree')?>
  <input type="radio" name="sres_spatial_data" value="1.0"><?php echo $_('1.0 degree')?><br>
  <br>
  <?php echo $_('Choose the variables')?>: <br>
  <input type="checkbox" name="variables_spatial_data[]" value="prec-PGF"><?php echo $_('prec_pgf')?><br>
  <input type="checkbox" name="variables_spatial_data[]" value="tmax-PGF"><?php echo $_('tmax_pgf')?><br>
  <input type="checkbox" name="variables_spatial_data[]" value="tmin-PGF"><?php echo $_('tmin_pgf')?><br>
  <input type="checkbox" name="variables_spatial_data[]" value="wind-PGF"><?php echo $_('wind_pgf')?><br>
  <input type="checkbox" name="variables_spatial_data[]" value="vcpct-VIC_DERIVED_PGF"><?php echo $_('vcpct_vic_derived_pgf')?><br>
  <br>
  <?php echo $_('Choose the file format')?>: <br>
  <input type="radio" name="format_spatial_data" value="arc_ascii"><?php echo $_('arc ascii')?>
  <input type="radio" name="format_spatial_data" value="netcdf" checked><?php echo $_('netcdf')?><br>
  <br>
  <?php echo $_('Provide an email to notify when the data is ready')?><br>
  <?php echo $_('Email')?>: <input type="text" name="email_spatial_data"></br>
  <br>
  <button type="button" onclick="Submit_Spatial_Data()"><?php echo $_('Submit')?></button>

</div>