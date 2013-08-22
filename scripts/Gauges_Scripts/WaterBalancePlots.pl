use FileHandle;
use strict;
#Input Variables
my @Final_Time = ($ARGV[0],$ARGV[1],$ARGV[2]);#2011,10,9);
my @Initial_Time = ($ARGV[3],$ARGV[4],$ARGV[5]);
my $gauge_number = $ARGV[6];#1992900;
my $gauge_lat = $ARGV[8];
my $gauge_lon = $ARGV[9];
my $gauge_area = $ARGV[10];
my $wb_title= $ARGV[11];
my $sm_title = $ARGV[12];
$wb_title =~ s/_/ /g;
$sm_title =~ s/_/ /g;
my $wb_ylabel = $ARGV[13];
$wb_ylabel =~ s/_/ /g;
my $xlabel = $ARGV[14];
$xlabel =~ s/_/ /g;
my $Webpage_Root_Dir = $ARGV[7];
my $workspace = "/tmp/ADM_workspace";
my $html_workspace = "ADM_workspace";
my $file_realtime = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Realtime/BasinAverages_output/gauge_fluxes_${gauge_number}";
my $file_spinup = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Catch_up/BasinAverages_output/gauge_fluxes_${gauge_number}";
my $file_historical = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Historical/basin_averages/gauge_fluxes_${gauge_number}";
my $Image_output = $workspace."/wb_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}${Initial_Time[2]}-${Final_Time[0]}${Final_Time[1]}${Final_Time[2]}.png";
my $SMImage_output = $workspace."/sm_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}${Initial_Time[2]}-${Final_Time[0]}${Final_Time[1]}${Final_Time[2]}.png";

#Local Variables
my @Temp_Time;
my $range = 10000;
my $random_number = int(rand($range)); #Random number to make sure queries do not overlap
my $gnuplot_script = $workspace."/gauge_gnuplot_script_${random_number}.txt";
my $Image_temp = $workspace."/Image_gauge_wb_temp_${random_number}.ps";
my $gauge_ascii_gnuplot = $workspace."/gauge_wb_${gauge_number}_${random_number}.txt";
my $ascii_output = $html_workspace."/gauge_wb_${gauge_number}_${random_number}.txt";

#Executables
my $gnuplot_exe = "gnuplot";

#Remove he previous text file
if (-e ${gauge_ascii_gnuplot})
	{
	system("rm ${gauge_ascii_gnuplot}");
	}

#Write the first lines to the output file
system("echo \'Gauge Number: $gauge_number\' > ${gauge_ascii_gnuplot}");
system("echo \'Latitude: $gauge_lat\' >> ${gauge_ascii_gnuplot}");
system("echo \'Longitude: $gauge_lon\' >> ${gauge_ascii_gnuplot}");
system("echo \'Catchment Area: $gauge_area km2\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 1: Year\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 2: Month\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 3: Day\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 4: Basin average precipitation (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 5: Basin average evaporation (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 6: Basin average surface runoff (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 7: Basin average baseflow (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 8: Basin average soil moisture in layer 1 (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 9: Basin average soil moisture in layer 2 (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 10: Basin average soil moisture in layer 3 (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 11: Basin average relative soil moisture of layer 1 (%)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 12: Basin average relative soil moisture of layer 2 (%)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 13: Basin average relative soil moisture of layer 3 (%)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 14: Drought Index (%)\' >> ${gauge_ascii_gnuplot}");
#
my $nheader = 18;
#

#Grab each part of the time series from the corresponding file
@Temp_Time = @Initial_Time;
if (&julian_day(@Initial_Time) <= &julian_day(2008,12,31))
	{
	#my $awk_script =  "awk \'/${Temp_Time[0]} ${Temp_Time[1]} ${Temp_Time[2]}/,/${Final_Time[0]} ${Final_Time[1]} ${Final_Time[2]}/\' /${file_historical} > ${gauge_ascii_gnuplot}";
	#system("${awk_script}");
	my $awk_script = "awk \'/${Temp_Time[0]} [ \s]+ ${Temp_Time[1]} [ \s]+ ${Temp_Time[2] }/,/${Final_Time[0]} [ \s]+ ${Final_Time[1]} [ \s]+ ${Final_Time[2]} / {print \$1,\$2,\$3,\$4,\$5,\$7,\$8,\$10,\$11,\$12,\$13,\$14,\$15,\$17}\' /${file_historical} >> ${gauge_ascii_gnuplot}";
        system("${awk_script}");
	@Temp_Time = (2009,1,1);
	}
if (&julian_day(@Initial_Time) <= &julian_day(2011,9,30) && &julian_day(@Final_Time) > &julian_day(2008,12,31))
	{
	#my $awk_script =  "awk \'/${Temp_Time[0]} ${Temp_Time[1]} ${Temp_Time[2]}/,/${Final_Time[0]} ${Final_Time[1]} ${Final_Time[2]}/\' /${file_spinup} >> ${gauge_ascii_gnuplot}";
	my $awk_script = "awk \'/${Temp_Time[0]} [ \s]+ ${Temp_Time[1]} [ \s]+ ${Temp_Time[2] }/,/${Final_Time[0]} [ \s]+ ${Final_Time[1]} [ \s]+ ${Final_Time[2]} / {print \$1,\$2,\$3,\$4,\$5,\$7,\$8,\$10,\$11,\$12,\$13,\$14,\$15,\$17}\' /${file_spinup} >> ${gauge_ascii_gnuplot}";
	system("${awk_script}");
	@Temp_Time = (2011,10,1);
	}
if (&julian_day(@Final_Time) > &julian_day(2011,9,30))
	{
        my $awk_script = "awk \'/${Temp_Time[0]} [ \s]+ ${Temp_Time[1]} [ \s]+ ${Temp_Time[2] }/,/${Final_Time[0]} [ \s]+ ${Final_Time[1]} [ \s]+ ${Final_Time[2]} / {print \$1,\$2,\$3,\$4,\$5,\$7,\$8,\$10,\$11,\$12,\$13,\$14,\$15,\$17}\' /${file_realtime} >> ${gauge_ascii_gnuplot}";
        system("${awk_script}");

#	my $awk_script =  "awk \'/${Temp_Time[0]} ${Temp_Time[1]} ${Temp_Time[2]}/,/${Final_Time[0]} ${Final_Time[1]} ${Final_Time[2]}/\' /${file_realtime} >> ${gauge_ascii_gnuplot}";
#	system("${awk_script}");
	}

#Figure out the number of days
my $ndays = &julian_day(@Final_Time) - &julian_day(@Initial_Time) + 1;
my $xtickfmt;
if ($ndays <= 60){$xtickfmt ='"%m/%d"';}
elsif ($ndays > 60 && $ndays <= 5*365){$xtickfmt = '"%m/%y"';}
else {$xtickfmt = '"%Y"'};

#Create the gnuplot script 
my $fh_gnuplot_script = new FileHandle (">$gnuplot_script");
print $fh_gnuplot_script <<EOF;
set   autoscale                        # scale axes automatically
unset log                              # remove any log-scaling
unset label                            # remove any previous labels
set xtic auto                          # set xtics automatically
set ytic auto                          # set ytics automatically
set xlabel "$xlabel" font "Helvetica,20"
set ylabel "$wb_ylabel [mm]" font "Helvetica,20"
set xtics font "Time-Roman,18"
set ytics font "Time-Roman,18"
set title "$wb_title ($Initial_Time[0]/$Initial_Time[1]/$Initial_Time[2] - $Final_Time[0]/$Final_Time[1]/$Final_Time[2])" font "Helvetica,25";
set xdata time
set timefmt "%Y %m %d"
set format x $xtickfmt
set size 1.2,0.85
set grid
set xr ["$Initial_Time[0] $Initial_Time[1] $Initial_Time[2]":"$Final_Time[0] $Final_Time[1] $Final_Time[2]"]
set key horiz
set terminal postscript enhanced color
set output "$Image_temp"
plot "${gauge_ascii_gnuplot}" using 1:4 every :: $nheader title 'Precipitation' with boxes fs solid 0.7 lt 1 lc rgb "blue",\\
"${gauge_ascii_gnuplot}" using 1:(\$4-\$5-\$7-\$6) every :: $nheader title '{/Symbol D}Storage' with line lt 1 lw 4 lc rgb "red",\\
"${gauge_ascii_gnuplot}" using 1:5 every :: $nheader title 'Evaporation' with line lt 1 lw 4 lc rgb "green",\\
"${gauge_ascii_gnuplot}" using 1:(\$6+\$7) every :: $nheader title 'Runoff' with line lt 1 lw 4 lc rgb "yellow"
EOF
close($fh_gnuplot_script);
system("${gnuplot_exe} $gnuplot_script");
system("convert $Image_temp -rotate 90 -background white -mosaic +matte $Image_output");

#Rename file for output to webpage
my $Image_output = $html_workspace."/wb_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}${Initial_Time[2]}-${Final_Time[0]}${Final_Time[1]}${Final_Time[2]}.png";

#Create the gnuplot script for the soil moisture plot 
my $fh_gnuplot_script = new FileHandle (">$gnuplot_script");
print $fh_gnuplot_script <<EOF;
set   autoscale                        # scale axes automatically
unset log                              # remove any log-scaling
unset label                            # remove any previous labels
set xtic auto                          # set xtics automatically
set ytic auto                          # set ytics automatically
set xlabel "$xlabel" font "Helvetica,20"
set ylabel "$wb_ylabel [%]" font "Helvetica,20"
set xtics font "Time-Roman,18"
set ytics font "Time-Roman,18"
set title "$sm_title ($Initial_Time[0]/$Initial_Time[1]/$Initial_Time[2] - $Final_Time[0]/$Final_Time[1]/$Final_Time[2])" font "Helvetica,25";
set xdata time
set timefmt "%Y %m %d"
set format x $xtickfmt
set size 1.2,0.85
set grid
set xr ["$Initial_Time[0] $Initial_Time[1] $Initial_Time[2]":"$Final_Time[0] $Final_Time[1] $Final_Time[2]"]
set key horiz
set terminal postscript enhanced color
set output "$Image_temp"
plot "${gauge_ascii_gnuplot}" using 1:11 every :: $nheader title 'Layer 1 (%)' with line lt 2 lw 4 lc rgb "blue",\\
"${gauge_ascii_gnuplot}" using 1:12 every :: $nheader title 'Layer 2 (%)' with line lt 2 lw 4 lc rgb "red",\\
"${gauge_ascii_gnuplot}" using 1:14 every :: $nheader title 'Drought Index (%)' with line lt 1 lw 5 lc rgb "green"
EOF
close($fh_gnuplot_script);
system("${gnuplot_exe} $gnuplot_script");
system("convert $Image_temp -rotate 90 -background white -mosaic +matte $SMImage_output");

#Rename file for output to webpage
my $SMImage_output = $html_workspace."/sm_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}${Initial_Time[2]}-${Final_Time[0]}${Final_Time[1]}${Final_Time[2]}.png";

#Print filename to the screen
print $Image_output." ".$ascii_output." ".$SMImage_output;

#Remove the temporary files
#system("rm $Image_temp");
#system("rm $gnuplot_script");

#Define the subroutines
sub julian_day
	{
    use integer;
    my($year, $month, $day) = @_;
    my($tmp);
    $tmp = $day - 32075
      + 1461 * ( $year + 4800 - ( 14 - $month ) / 12 )/4
      + 367 * ( $month - 2 + ( ( 14 - $month ) / 12 ) * 12 ) / 12
      - 3 * ( ( $year + 4900 - ( 14 - $month ) / 12 ) / 100 ) / 4
      ;
    return($tmp);
	}

sub inverse_julian_day
{
	use integer;
        my($jd) = @_;
        my($jdate_tmp);
        my($m,$d,$y);
	$jdate_tmp = $jd - 1721119;
        $y = (4 * $jdate_tmp - 1)/146097;
        $jdate_tmp = 4 * $jdate_tmp - 1 - 146097 * $y;
        $d = $jdate_tmp/4;
        $jdate_tmp = (4 * $d + 3)/1461;
        $d = 4 * $d + 3 - 1461 * $jdate_tmp;
        $d = ($d + 4)/4;
        $m = (5 * $d - 3)/153;
        $d = 5 * $d - 3 - 153 * $m;
        $d = ($d + 5) / 5;
        $y = 100 * $y + $jdate_tmp;
        if($m < 10) {
                $m += 3;
        } else {
                $m -= 9;
                ++$y;
        }
        return ($y, $m, $d);
}




















