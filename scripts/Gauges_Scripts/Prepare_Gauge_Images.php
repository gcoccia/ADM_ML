<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
#Script that take information regarding the streamgauge and prepares the corresponding plot
#Input variables

#Bring in variables from the javascript
$data_in = explode(',',$_GET['data']);
$gauge_lat = $data_in[7];
$gauge_lon = $data_in[8];
$gauge_area = $data_in[9]*1.609344*1.609344;
$gauge_number = $data_in[6];
#$gauge_number = 1147010;
$gauge_year_initial = $data_in[0];
#$gauge_year_initial = 2011;
$gauge_month_initial = $data_in[1];
#$gauge_month_initial = 9;
$gauge_day_initial = $data_in[2];
#$gauge_day_initial = 16;
$gauge_year_final = $data_in[3] ;
#$gauge_year_final = 2011;
$gauge_month_final = $data_in[4];
#$gauge_month_final = 10;
$gauge_day_final = $data_in[5];
#$gauge_day_final = 13;
$sd_title = $data_in[10];
$wb_title = $data_in[11];
$sm_title = $data_in[12];
$sd_ylabel1 = $data_in[13];
$sd_ylabel2 = $data_in[14];
$wb_ylabel = $data_in[15];
$xlabel = $data_in[16];
# Make directory 
#system("mkdir /tmp/ADM_workspace");
$Web_Root = "/home/nchaney/public_html";
$workspace = "/tmp/ADM_workspace";
$html_workspace = "ADM_workspace";
#Find files that are older than 10 minutes in the workspace and delete
system("find $workspace/* -mmin +1 -exec rm {} \;");

#Run perl scripts to make new images
$discharge_plot_file = exec("perl DischargePlots.pl ${gauge_year_final} ${gauge_month_final} ${gauge_day_final} ${gauge_year_initial} ${gauge_month_initial} ${gauge_day_initial} ${gauge_number} ${Web_Root} ${gauge_lat} ${gauge_lon} ${gauge_area} ${sd_title} ${sd_ylabel1} ${sd_ylabel2} ${xlabel}");
$waterbalance_plot_file = exec("perl WaterBalancePlots.pl ${gauge_year_final} ${gauge_month_final} ${gauge_day_final} ${gauge_year_initial} ${gauge_month_initial} ${gauge_day_initial} ${gauge_number} ${Web_Root} ${gauge_lat} ${gauge_lon} ${gauge_area} ${wb_title} ${sm_title} ${wb_ylabel} ${xlabel}");
echo $discharge_plot_file." ".$waterbalance_plot_file."\n";
#echo $sd_ylabel1.$sd_ylabel2."\n";
?>
