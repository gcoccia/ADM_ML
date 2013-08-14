<?php
$Log_File = "Data/ADM_Data/Realtime/LastUpd.log";
$Log_Info;
$data = file($Log_File);
$count = 0;
$year;
$month;
$day;
foreach($data as $value)
        {
        $temp_str = preg_split("/[\s,]+/", $value);
        $Log_Info[$count] = $temp_str;
	$count = $count + 1;
        }
$year_initial = $Log_Info[1][1];
$month_initial = $Log_Info[1][2];
$day_initial =  $Log_Info[1][3];
?>

