<?php

// Set up the translation scripts
require_once('php-gettext-1.0.11/gettext.inc');
$locale = BP_LANG;
$textdomain="adm";
if (empty($locale))
  $locale = 'en';
if (isset($_GET['locale']) && !empty($_GET['locale']))
  $locale = $_GET['locale'];
else if (isset($_POST['locale']) && !empty($_POST['locale']))
  $locale = $_POST['locale'];
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
/*if (!isset($_POST["latitude"]) || !isset($_POST["longitude"]))
{
  header('HTTP/1.1 500 Internal Server Error: Parameters not defined in request');
  header('Content-Type: application/json');
  die('ERROR');
}*/

?>

<!DOCTYPE html> 

<!--Close window box-->
<a onclick="Data_Extraction_Popup()" style="width:80px; height:10px"><?php echo $_('Close Window') ?></a>
<!--Chart Container-->
<div id="popup_container"></div>
<!--Controls-->
<div id="popup_controls">

  <button onclick="Request_and_Display()"><?php echo $_('Update Plot') ?></button>
  <br>
  <?php echo $_('Plots') ?>:
  <input type="radio" name="plot" value="Drought_Indices" checked=checked><?php echo $_('Drought Indices') ?>
  <input type="radio" name="plot" value="Water_Balance" ><?php echo $_('Water Balance') ?>
  <input type="radio" name="plot" value="Surface_Fluxes" ><?php echo $_('Surface Fluxes') ?>
  <br>
  <?php echo $_('Time Step') ?>:
  <input type="radio" name="tstep" value="DAILY" checked=checked><?php echo $_('Daily') ?>
  <input type="radio" name="tstep" value="MONTHLY" ><?php echo $_('Monthly') ?>
  <input type="radio" name="tstep" value="YEARLY" ><?php echo $_('Yearly') ?>
  <br>
  <?php echo $_('Initial Time: (1 jan 2001 - 31 dec 2001)') ?>
  <br>
  <input type="text" id="iyear" value=2001>
  <input type="text" id="imonth" value=1>
  <input type="text" id="iday" value=1>
  <br>
  <?php echo $_('Final Time') ?>
  <br>
  <input type="text" id="fyear" value=2001>
  <input type="text" id="fmonth" value=1>
  <input type="text" id="fday" value=10>
  <br>
  <?php echo $_('Latitude (other coords broken right now)') ?>
  <br>
  <input type="text" id="latitude" value=-34.6250>
  <br>
  <?php echo $_('Longitude') ?>
  <br>
  <input type="text" id="longitude" value=19.8750>
  <br>
  <?php echo $_('Actual Coordinates').": ".$_POST["latitude"]." ".$_POST["longitude"] ?>

 </div>