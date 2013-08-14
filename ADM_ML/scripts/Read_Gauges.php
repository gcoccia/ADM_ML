<?php
#Read in the stream gauge information one at a time
$Basin_File = "Data/Basin_Info/GRDC/Africa_900s_basins.dat";
$Basin_Info;
$data = file($Basin_File);
$count = 0;
foreach($data as $value) 
	{
	$temp_str = preg_split("/[\s,]+/", $value);
	$Basin_Info[$count] = $temp_str;
	if ($count == 0)
		{
		$gauge_lat = '['.$Basin_Info[$count][4];
		$gauge_lon = '['.$Basin_Info[$count][6];
		$gauge_number_2 ='['.$Basin_Info[$count][0];
		$gauge_area = '['.$Basin_Info[$count][9];
		$mask_gauge = '['.'"Data/Basin_Info/GRDC/Images/mask_gauge_'.$Basin_Info[$count][0].".gif\"";
		$gauge_flag = '['.$Basin_Info[$count][1];
		}
	else
		{
		$gauge_lat = $gauge_lat.', '.$Basin_Info[$count][4];
		$gauge_lon = $gauge_lon.','.$Basin_Info[$count][6];
		$gauge_number_2 = $gauge_number_2.','.$Basin_Info[$count][0];	
		$gauge_area = $gauge_area.','.$Basin_Info[$count][9];
		$mask_gauge = $mask_gauge.', "Data/Basin_Info/GRDC/Images/mask_gauge_'.$Basin_Info[$count][0].".gif\"";
		$gauge_flag = $gauge_flag.', '.$Basin_Info[$count][1];
		}
	$count = $count + 1;
	}
$gauge_flag = $gauge_flag.']';
$gauge_lat = $gauge_lat.']';
$gauge_lon = $gauge_lon.']';
$gauge_number_2 = $gauge_number_2.']';
$gauge_area = $gauge_area.']';
$mask_gauge = $mask_gauge.'];';
$ngauges = $count;
$count = 1;
$gauge_number = $Basin_Info[$count][1];
?>
