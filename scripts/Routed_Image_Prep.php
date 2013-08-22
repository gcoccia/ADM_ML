<?php
ini_set('display_errors','On');
error_reporting(E_ALL);
#Script that take information regarding the streamgauge and prepares the corresponding plot
#Input variables

#Bring in variables from the javascript
$data_in = explode(' ',$_GET['data']);
$gauge_number = $data_in[4];
#$gauge_number = 1147010;
$gauge_year_initial = $data_in[0];
#$year_initial = 2000;
$gauge_month_initial =$data_in[1];
#$month_initial = 1;
$gauge_year_final = $data_in[2] ;
#$year_final = 2000;
$gauge_month_final = $data_in[3];
#$month_final = 12;
$gauge_data_path = "/home/nchaney/public_html/ADM/Data/Streamgauges/GRDC/Routed_Ascii/Monthly/";
# Make directory 
#system("mkdir /tmp/ADM_workspace");
$workspace = "/tmp/ADM_workspace/";
$html_workspace = "ADM_workspace/";

#Look for prior files and delete
system("rm $workspace*");

#Local variables
$gauge_data_file = $gauge_data_path."gauge_".$gauge_number; 
$gauge_image_file = $workspace.'gauge_'.$gauge_number.'_'.$gauge_year_initial.sprintf('%02d',$gauge_month_initial).'_'.$gauge_year_final.sprintf('%02d',$gauge_month_final).".pbm";
$gauge_image_file_png = $workspace.'gauge_'.$gauge_number.'_'.$gauge_year_initial.sprintf('%02d',$gauge_month_initial).'_'.$gauge_year_final.sprintf('%02d',$gauge_month_final).".png";
$gauge_image_file_html_png = $html_workspace.'gauge_'.$gauge_number.'_'.$gauge_year_initial.sprintf('%02d',$gauge_month_initial).'_'.$gauge_year_final.sprintf('%02d',$gauge_month_final).".png";
$gnuplot_script_file = $workspace.'gauge_'.$gauge_number.'_'.$gauge_year_initial.sprintf('%02d',$gauge_month_initial).'_'.$gauge_year_final.sprintf('%02d',$gauge_month_final).".gnu";

#Create script for GNUPLOT
	#NOTE: WILL WANT TO CHANGE TO PRINT IN PNG AND NOT PBM
$gnuplot_script = <<< EOF
# Gnuplot script file for plotting routed data
set   autoscale                        # scale axes automatically
unset log                              # remove any log-scaling
unset label                            # remove any previous labels
set xtic auto                          # set xtics automatically
set ytic auto                          # set ytics automatically
set xlabel "Time" font "Helvetica,20"
set ylabel "Discharge" font "Helvetica,20"
set xtics font "Time-Roman,15"
set ytics font "Time-Roman,15"
set xdata time
set timefmt "%Y %m"
set format x "%m/%y"
set size 1,1
set xr ["${gauge_year_initial} ${gauge_month_initial}":"${gauge_year_final} ${gauge_month_final}"]
set terminal pbm
set output "${gauge_image_file}"
plot  "${gauge_data_file}" using 1:3 title 'Q (m^{3}/s)' with line lt 3 lw 4
EOF;
$fh_gnuplot_script_file = fopen ($gnuplot_script_file, "w+"); 
fwrite($fh_gnuplot_script_file, $gnuplot_script); 
fclose ($fh_gnuplot_script_file);  

#Run GNUPLOT in batch mode
system("gnuplot ${gnuplot_script_file}");

#Convert to .png
system ("convert ${gauge_image_file} ${gauge_image_file_png}");
system ("convert ${gauge_image_file_png} -resize 60% ${gauge_image_file_png}");

#Remove the script
system ("rm ${gauge_image_file}");
system ("rm ${gnuplot_script_file}");
#system ("rm ${gauge_image_file_png}");
echo $gauge_image_file_html_png;
#system("chmod 777 $gauge_image_file_png");

#Move png to workspace
#system("mv $gauge_image_file_png /home/nchaney/public_html/image.png");
?>
