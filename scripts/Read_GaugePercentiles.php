<?php
#Read in the stream gauge information one at a time
#$Current_Time= date('d/m/Y',jdtounix(unixtojd() - 3));
#$year_final = (int)(substr($Current_Time,6,4));
#$month_final = (int)(substr($Current_Time,3,2));
#$day_final = (int)(substr($Current_Time,0,2));#StationAscii/gauges_20111101.txt
$GaugePercentiles_File = "Data/ADM_Data/Realtime/StationAscii/gauges_".sprintf("%04d",$year_initial).sprintf("%02d",$month_initial).sprintf("%02d",$day_initial).".txt";
$GaugePercentiles_Info;
$data = file($GaugePercentiles_File);
$count = 0;
foreach($data as $value) 
	{
	$temp_str = preg_split("/[\s,]+/", $value);
	$GaugePercentiles_Info[$count] = $temp_str;
	if ($count == 0)
		{
		$gauge_percentile = '['.$GaugePercentiles_Info[$count][3];
		}
	else
		{
		$gauge_percentile = $gauge_percentile.','.$GaugePercentiles_Info[$count][3];
		}
	$count = $count + 1;
	}
$gauge_percentile = $gauge_percentile.']';
?>
