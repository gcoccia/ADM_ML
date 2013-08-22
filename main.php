<?php 
require('scripts/main_layout.inc');
page_header("Current Forecast");
menu();
$current = "??????";

$text = <<<EOF
<div class='main'>
<h2>Drought Conditions On $current</h2>
<p>
The drought analysis is based on comparing the current soil moisture 
against the 59-yr retrospective climatology. The climatology is
developed separately for each grid cell (&gt; 40,000 cells over Africa) in the
form of a pdf. Here we use empirical distributions based directly on the data. The plots shows the percentile of current soil moisture with respect to the 59-yr climatology defined as all values in a 24-day sampling window centered at $current.</p>
<p>
Viewing the data through the Google Maps user interface is recommended.
</p>
<h3>Our Drought Monitoring</h3>
<form name='historyform'>
View nowcast on:
<select name='period'";>
</div>
<img src="Data/Images/main_webpage/daily/20111017/smqall_20111017.png">
EOF;

print $text;

//print $text2;
page_footer();
?>
