<?php
/* $Id: structure.inc, 02/07/2005$ */

require("function.inc");
require_once('php-gettext-1.0.11/gettext.inc');
page_header("fr", "whatever");
function menu()
{
?>
<!--  Menus starts here-->
<div class="menu">
<ul id="nav">
    <li id="nav1"><a title="Information about this project">About This Project</a><ul></ul></li>
    <li id="nav4"><a title="Google Maps Interface" href="GMinterface.php">Google Maps Interface</a><ul></ul></li>
    <li id="nav3"><span>Current conditions</span>
        <ul>
	<li><a title="Max Temperature">Max Temperature</a></li>
	<li><a title="Min Temperature">Min Temperature</a></li>
	<li><a title="Precipitation">Precipitation</a></li>
        <li><a title="Soil moisture L1">Soil Moisture (L1)</a></li>
	<li><a title="Soil Moisture L2">Soil Moisture (L2)</a></li>
	<li><a title="Soil Moisture L3">Soil Moisture (L3)</a></li-->
        <!--<li><a title="Drought estimate" >Drought Estimate</a></li>-->
	<li><a title="Soil Moisture" >Drought Probability</a></li>
        <li><a title="Streamflow" >Streamflow</a></li>
        </ul>
    </li>
    <li id="nav5"><a  title="Historical Drought Maps"">Historical Droughts</a><ul></ul></li>
    <!--li id="nav12"><a title="Hindcast Verification" href="hcstvrf.php">Hindcast Verification</a></li-->
    </li>
    <li id="nav7"><span>Documentation
</span>
        <ul>
	<li><a title="Background">Background</a></li-->
        <li><a title="Methodology">Methodology</a></li>
	<!--<li><a title="Bug report" href="doc.bug.php">Bug Report</a></li>-->
        <!--<li><a title="References and related publications" href="doc.ref.php">References</a></li>-->
        </ul>
    </li>
</ul> 
</div>
<!-- Menu ends here -->

<?php
}

function page_header($locale, $subtitle=false)
{
$textdomain="adm";

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
$_= 'T_';

$text = <<< EOF
<!DOCTYPE html PUBLIC '-//W3C//DTD XHTML 1.0 Strict//EN' "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" >
<head>
<title>African Drought Monitor: <?php echo "$subtitle"?></title>
<meta http-equiv="content-type" content="text/html; charset=iso-8859-1" />
<meta http-equiv="content-style-type" content="text/css" />
<meta content="African Drought Monitor" name="description" />
<meta content="Drought, drought monitor, drought monitoring, streamflow, soil moisture, hydrological forecast,hydrologic forecast, water, resource, management, Nathaniel Chaney, Justin Sheffield, Eric Wood" name="keywords" />
<meta content="Nathaniel Chaney" name="author" />

<script type="text/javascript" src="js/mootools.v1.1.js"></script>
<script type="text/javascript" src="js/slimbox.js"></script>
<script type="text/javascript" src="jsscripts/Static_Images.js"></script>
<script type="text/javascript" src="jsscripts/MiscFunctions.js"></script>

<link rel="Shortcut Icon" href="vic.ico" type="image/x-icon" >
<link rel="stylesheet" type="text/css" media="screen,projection" href="css/Moz.css" title="Moz" />
<link rel="stylesheet" type="text/css" media="screen" href="css/slimbox.css" />
<link rel="stylesheet" type="text/css" media="print" href="css/print.css" title="print" />
</head>

<body>
<a name="top"></a>
<!--here is the header -->
<div class="top">
    <div class="box">
	    <div><table><tbody><tr>
		<td align="center" right="25%"><img id="UNESCO_logo" src="icons/Unesco_logo.gif"></td>
                <td align="center" right="32%"><img id="AGRHYMET_logo" src="icons/agrhymet_logo.gif"></td>
		<td id="Test" align="center" right="50%" width="50%"> {$_("African Drought Monitor")} </td>
		<td align="center" left="32%"><img id="PU_logo" src="icons/PU_logo.gif"></td>
                <td align="center" left="25%"><img id="UW_logo" src="icons/UW_logo.png"></td>
	    </tr></tbody></table></div>
    </div>
</div>
<div class='hbar'>
<!--<table id="nav"><tr><td>Basic Interface</td><td  class="link" onClick="document.location.href='GMinterface.php'">Google Maps Interface</td><td class="link">Methodology</td><td class="link" >Background</td></tr></table>-->
<table id="nav"><tr><td>{$_("Basic Interface")}</td><td  class="link" onClick="document.location.href='GMinterface.php'">{$_("Google Maps Interface")}</td><td class="link" onClick="document.location.href='Resources/ADM_Background.pdf'">{$_("Background")}</td><td class="link" onClick="document.location.href='Resources/ADM_Glossary.pdf'">{$_("Glossary")}</td><td class="link" onClick="document.location.href='Resources/Tutorial_HornAfrica.pdf'">{$_("Tutorial")}</td></tr></table>
</div>
EOF;
print $text;
?>

<?php
}

function page_footer()
{
?>
</div>
<div class='hbar'></div>
<div class='footer'>
Prepared by <a href='http://hydrology.princeton.edu/~nchaney/'>Nathaniel Chaney</a><br/>
<a href="http://www.cee.princeton.edu/eewr">EEWR</a>/<a href="http://www.cee.princeton.edu">CEE</a>, <a href="http://www.princeton.edu">Princeton University</a><br/>
Last update: <?php echo strftime("%c %Z", getlastmod()); ?><br>
<!--table cellpadding="0" cellspacing="0" border="0">
<tr align="center">
    <td>Total &nbsp;</td><td>
    <?php 
    //include_once "acountphp/dcounter.php";
    //$ani_counter = new acounter("forecast");
    
    //echo $ani_counter->create_output("forecast");
    ?> </td>
    <td> &nbsp;visits since September 2003.</td>
</tr>
</table-->

</div>
<script src="http://www.google-analytics.com/urchin.js" type="text/javascript">
</script>
<script type="text/javascript">
_uacct = "UA-817873-4";
urchinTracker();
</script>
</body>
</html>
<?php
}


