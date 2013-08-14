use FileHandle;
use strict;
#Input Variables
my @Final_Time = ($ARGV[0],$ARGV[1],1);#2011,10,9);
my @Initial_Time = ($ARGV[2],$ARGV[3],1);
my $gauge_number = $ARGV[4];#1992900;
my $gauge_lat = $ARGV[6];
my $gauge_lon = $ARGV[7];
my $gauge_area = $ARGV[8];
my $Webpage_Root_Dir = $ARGV[5];
my $wb_title= $ARGV[9];
my $sm_title = $ARGV[10];
$wb_title =~ s/_/ /g;
$sm_title =~ s/_/ /g;
my $ylabel = $ARGV[11];
$ylabel =~ s/_/ /g;
my $xlabel = $ARGV[12];
$xlabel =~ s/_/ /g;
my $workspace = "/tmp/ADM_workspace";
my $html_workspace = "ADM_workspace";
#my $workspace = "/raid2/hydrology/data/nchaney/Workspace";
#my $html_workspace = "/raid2/hydrology/data/nchaney/Workspace";
my $file_realtime = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Realtime/BasinAverages_output/gauge_fluxes_${gauge_number}";
my $file_spinup = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Catch_up/BasinAverages_output/gauge_fluxes_${gauge_number}";
my $file_historical = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Historical/basin_averages/gauge_fluxes_${gauge_number}";
my $Image_output = $workspace."/wb_monthly_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}-${Final_Time[0]}${Final_Time[1]}.png";
my $SMImage_output = $workspace."/sm_monthly_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}-${Final_Time[0]}${Final_Time[1]}.png";

#Get last day of month
my @Temp_Time = &inverse_julian_day(&julian_day(@Final_Time) + 32);
$Temp_Time[2] = 1;
my @Final_Time = &inverse_julian_day(&julian_day(@Temp_Time) - 1);

#Local Variables
my @Temp_Time;
my $range = 10000;
my $random_number = int(rand($range)); #Random number to make sure queries do not overlap
my $gnuplot_script = $workspace."/gauge_gnuplot_script_${random_number}.txt";
my $Image_temp = $workspace."/Image_gauge_wb_temp_${random_number}.ps";
my $gauge_ascii_gnuplot = $workspace."/gauge_wb_${random_number}.txt";
my $ascii_output = $html_workspace."/gauge_wb_${random_number}.txt";
my $monthly_temp = $workspace."/daily_wb_${random_number}.txt";
my $nheader = 13 + 4;
my @Current_Time;

#Executables
my $gnuplot_exe = "gnuplot";

#Remove he previous text file
if (-e ${gauge_ascii_gnuplot})
	{
	system("rm ${gauge_ascii_gnuplot}");
	}
if (-e ${monthly_temp})
	{
	system("rm ${monthly_temp}");
	}

#Grab each part of the time series from the corresponding file
@Temp_Time = @Initial_Time;
if (&julian_day(@Initial_Time) <= &julian_day(2008,12,31))
	{
	my $awk_script = "awk \'/${Temp_Time[0]} [ \s]+ ${Temp_Time[1]} [ \s]+ ${Temp_Time[2] }/,/${Final_Time[0]} [ \s]+ ${Final_Time[1]} [ \s]+ ${Final_Time[2]} / {print \$1,\$2,\$4,\$5,\$7,\$8,\$10,\$11,\$12,\$13,\$14,\$15,\$17}\' /${file_historical} >> ${monthly_temp}";
        system("${awk_script}");
	@Temp_Time = (2009,1,1);
	}
if (&julian_day(@Initial_Time) <= &julian_day(2011,9,30) && &julian_day(@Final_Time) >= &julian_day(2008,12,31))
	{
	my $awk_script = "awk \'/${Temp_Time[0]} [ \s]+ ${Temp_Time[1]} [ \s]+ ${Temp_Time[2] }/,/${Final_Time[0]} [ \s]+ ${Final_Time[1]} [ \s]+ ${Final_Time[2]} / {print \$1,\$2,\$4,\$5,\$7,\$8,\$10,\$11,\$12,\$13,\$14,\$15,\$17}\' /${file_spinup} >> ${monthly_temp}";
	system("${awk_script}");
	@Temp_Time = (2011,10,1);
	}
if (&julian_day(@Final_Time) => &julian_day(2011,10,1))
	{
        my $awk_script = "awk \'/${Temp_Time[0]} [ \s]+ ${Temp_Time[1]} [ \s]+ ${Temp_Time[2] }/,/${Final_Time[0]} [ \s]+ ${Final_Time[1]} [ \s]+ ${Final_Time[2]} / {print \$1,\$2,\$4,\$5,\$7,\$8,\$10,\$11,\$12,\$13,\$14,\$15,\$17}\' /${file_realtime} >> ${monthly_temp}";
        system("${awk_script}");
	}

#Make Monthly File
#Write the first lines to the output file
system("echo \'Gauge Number: $gauge_number\' > ${gauge_ascii_gnuplot}");
system("echo \'Latitude: $gauge_lat\' >> ${gauge_ascii_gnuplot}");
system("echo \'Longitude: $gauge_lon\' >> ${gauge_ascii_gnuplot}");
system("echo \'Catchment Area: $gauge_area km2\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 1: Year\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 2: Month\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 3: Monthly basin average precipitation (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 4: Monthly basin average evaporation (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 5: Monthly basin average surface runoff (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 6: Monthly Basin average baseflow (mm/day)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 7: Monthly basin average soil moisture in layer 1 (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 8: Monthly basin average soil moisture in layer 2 (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 9: Monthly basin average soil moisture in layer 3 (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 10: Monthly basin average relative soil moisture of layer 1 (%)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 11: Monthly basin average relative soil moisture of layer 2 (%)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 12: Monthly basin average relative soil moisture of layer 3 (%)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 13: Monthly basin Drought Index (%)\' >> ${gauge_ascii_gnuplot}");

#Get monthly averages and attach them to the file
my @Current_Time = @Initial_Time;
while (&julian_day(@Current_Time) <= &julian_day(@Final_Time))
	{
	my $awk_script = "awk \'BEGIN {n = 0} /$Current_Time[0] $Current_Time[1] / {n+=1; c1 = \$1; c2 = \$2; c3+=\$3; c4+=\$4; c5+=\$5; c6+=\$6; c7+=\$7; c8+=\$8; c9+=\$9; c10+=\$10; c11+=\$11; c12+=\$12; c13+=\$13;} END {print c1,c2,c3/n,c4/n,c5/n,c6/n,c7/n,c8/n,c9/n,c10/n,c11/n,c12/n,c13/n}\' ${monthly_temp}";
	system("$awk_script >> $gauge_ascii_gnuplot");
	@Current_Time = &inverse_julian_day(&julian_day(@Current_Time) + 32);
	@Current_Time[2] = 1;
        }

#Figure out the number of months
my $ndays = &julian_day(@Final_Time) - &julian_day(@Initial_Time) + 1;
my $xtickfmt;
if ($ndays <= 5*365){$xtickfmt = '"%m/%y"';}
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
set ylabel "$ylabel [mm]" font "Helvetica,20"
set xtics font "Time-Roman,18"
set ytics font "Time-Roman,18"
set title "$wb_title ($Initial_Time[0]/$Initial_Time[1] - $Final_Time[0]/$Final_Time[1])" font "Helvetica,25";
set xdata time
set timefmt "%Y %m"
set format x $xtickfmt
set size 1.2,0.85
set grid
set xr ["$Initial_Time[0] $Initial_Time[1]":"$Final_Time[0] $Final_Time[1]"]
set key horiz
set terminal postscript enhanced color
set output "$Image_temp"
plot "${gauge_ascii_gnuplot}" using 1:3 every :: $nheader title 'Precipitation' with boxes fs solid 0.7 lt 1 lc rgb "blue",\\
"${gauge_ascii_gnuplot}" using 1:(\$3-\$4-\$6-\$5) every :: $nheader title '{/Symbol D}Storage' with line lt 1 lw 4 lc rgb "red",\\
"${gauge_ascii_gnuplot}" using 1:4 every :: $nheader title 'Evaporation' with line lt 1 lw 4 lc rgb "green",\\
"${gauge_ascii_gnuplot}" using 1:(\$5+\$6) every :: $nheader title 'Runoff' with line lt 1 lw 4 lc rgb "yellow"
EOF
close($fh_gnuplot_script);
system("${gnuplot_exe} $gnuplot_script");
system("convert $Image_temp -rotate 90 -background white -mosaic +matte $Image_output");

#Rename file for output to webpage
my $Image_output = $html_workspace."/wb_monthly_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}-${Final_Time[0]}${Final_Time[1]}.png";

#Create the gnuplot script for the soil moisture plot 
my $fh_gnuplot_script = new FileHandle (">$gnuplot_script");
print $fh_gnuplot_script <<EOF;
set   autoscale                        # scale axes automatically
unset log                              # remove any log-scaling
unset label                            # remove any previous labels
set xtic auto                          # set xtics automatically
set ytic auto                          # set ytics automatically
set xlabel "$xlabel" font "Helvetica,20"
set ylabel "$ylabel [%]" font "Helvetica,20"
set xtics font "Time-Roman,18"
set ytics font "Time-Roman,18"
set title "$sm_title ($Initial_Time[0]/$Initial_Time[1] - $Final_Time[0]/$Final_Time[1])" font "Helvetica,25";
set xdata time
set timefmt "%Y %m"
set format x $xtickfmt
set size 1.2,0.85
set grid
set xr ["$Initial_Time[0] $Initial_Time[1]":"$Final_Time[0] $Final_Time[1]"]
set key horiz
set terminal postscript enhanced color
set output "$Image_temp"
plot "${gauge_ascii_gnuplot}" using 1:10 every :: $nheader title 'Layer 1 (%)' with line lt 2 lw 4 lc rgb "blue",\\
"${gauge_ascii_gnuplot}" using 1:11 every :: $nheader title 'Layer 2 (%)' with line lt 2 lw 4 lc rgb "red",\\
"${gauge_ascii_gnuplot}" using 1:13 every :: $nheader title 'Drought Index (%)' with line lt 1 lw 5 lc rgb "green"
EOF
close($fh_gnuplot_script);
system("${gnuplot_exe} $gnuplot_script");
system("convert $Image_temp -rotate 90 -background white -mosaic +matte $SMImage_output");

#Rename file for output to webpage
my $SMImage_output = $html_workspace."/sm_monthly_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}-${Final_Time[0]}${Final_Time[1]}.png";

#Print filename to the screen
print $Image_output." ".$ascii_output." ".$SMImage_output;

#Remove the temporary files
system("rm $Image_temp");
system("rm $gnuplot_script");

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




















