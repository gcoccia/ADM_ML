use FileHandle;
use strict;
#Input Variables
my @Final_Time = ($ARGV[0],$ARGV[1],$ARGV[2]);#2011,10,9);
my @Initial_Time = ($ARGV[3],$ARGV[4],$ARGV[5]);
my $gauge_number = $ARGV[6];#1992900;
my $Webpage_Root_Dir = $ARGV[7];
my $gauge_lat = $ARGV[8];
my $gauge_lon = $ARGV[9];
my $gauge_area = $ARGV[10];
my $sd_title = $ARGV[11];
$sd_title =~ s/_/ /g;
my $sd_ylabel1 = $ARGV[12];
$sd_ylabel1 =~ s/_/ /g;
my $sd_ylabel2 = $ARGV[13];
$sd_ylabel2 =~ s/_/ /g;
my $xlabel = $ARGV[14];
$xlabel =~ s/_/ /g;
my $workspace = "/tmp/ADM_workspace";
my $html_workspace = "ADM_workspace";
my $file_realtime = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Realtime/Routed_output/gauge_${gauge_number}";
my $file_spinup = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Catch_up/Routed_output/gauge_${gauge_number}";
my $file_historical = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Historical/Gauges/${gauge_number}";
#my $file_historical = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Historical/Gauges/gauge_${gauge_number}";
my $Image_output = $workspace."/ds_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}${Initial_Time[2]}-${Final_Time[0]}${Final_Time[1]}${Final_Time[2]}.png";

#Local Variables
my @Temp_Time;
my $range = 10000;
my $random_number = int(rand($range)); #Random number to make sure queries do not overlap
my $gnuplot_script = $workspace."/gauge_gnuplot_script_${random_number}.txt";
my $Image_temp = $workspace."/Image_gauge_ds_temp_${random_number}.ps";
my $gauge_ascii_gnuplot = $workspace."/gauge_ds_${gauge_number}_${random_number}.txt";
my $ascii_output = $html_workspace."/gauge_ds_${gauge_number}_${random_number}.txt";
my $nheader = 24;

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
system("echo \'column 4: Daily Average Discharge (m^3/s)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 5: Daily Average Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 6: Corresponding Percentile to Daily Average Runoff\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 7: 1st Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 8: Cumulative Surplus with respect to 1st percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 9: 10th Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 10: Cumulative Surplus with respect to 10th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 11: 25th Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 12: Cumulative Surplus with respect to 25th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 13: 50th Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 14: Cumulative Surplus with respect to 50th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 15: 75th Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 16: Cumulative Surplus with respect to 75th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 17: 90th Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 18: Cumulative Surplus with respect to 90th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 19: 99th Percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 20: Cumulative Surplus with respect to 99th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
=pod
system("echo \'column 1: Year\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 2: Month\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 3: Day\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 4: Daily Average Discharge (m^3/s)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 5: Percentile with respect to historical record\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 6: 50% Percentile\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 7: Cumulative yearly surplus (m^3)\' >> ${gauge_ascii_gnuplot}");
=cut
my $print_string = "{print \$1,\$2,\$3,\$4,\$8,\$5,\$9,\$15,\$10,\$16,\$11,\$17,\$6,\$7,\$12,\$18,\$13,\$19,\$14,\$20}";
#Grab each part of the time series from the corresponding file
@Temp_Time = @Initial_Time;
if (&julian_day(@Initial_Time) <= &julian_day(2008,12,31))
	{
	my $awk_script =  "awk \'/${Temp_Time[0]}  *${Temp_Time[1]}  *${Temp_Time[2]}/,/${Final_Time[0]}  *${Final_Time[1]}  *${Final_Time[2]}/ $print_string \' /${file_historical} >> ${gauge_ascii_gnuplot}";
	system("${awk_script}");
	@Temp_Time = (2009,1,1);
	}
if (&julian_day(@Initial_Time) <= &julian_day(2011,9,30) && &julian_day(@Final_Time) > &julian_day(2008,12,31))
	{
	my $awk_script =  "awk \'/${Temp_Time[0]}  *${Temp_Time[1]}  *${Temp_Time[2]}/,/${Final_Time[0]}  *${Final_Time[1]}  *${Final_Time[2]}/ $print_string \' /${file_spinup} >> ${gauge_ascii_gnuplot}";
	system("${awk_script}");
	@Temp_Time = (2011,10,1);
	}
if (&julian_day(@Final_Time) > &julian_day(2011,9,30))
	{
	my $awk_script =  "awk \'/${Temp_Time[0]}  *${Temp_Time[1]}  *${Temp_Time[2]}/,/${Final_Time[0]}  *${Final_Time[1]}  *${Final_Time[2]}/ $print_string \' /${file_realtime} >> ${gauge_ascii_gnuplot}";
	system("${awk_script}");
	}

#Figure out the number of days
my $ndays = &julian_day(@Final_Time) - &julian_day(@Initial_Time) + 1;
my $xtickfmt;
if ($ndays <= 60){$xtickfmt ='"%m/%d"';}
elsif ($ndays > 60 && $ndays <= 5*365){$xtickfmt = '"%m/%y"';}
else {$xtickfmt = '"%Y"'};

#Find the minimum and maximum of our data
my $line_temp = `awk 'NR == $nheader+1  {max=\$14 ; min=\$14} \$14 >= max {max = \$14} \$14 <= min {min = \$14} END { print min, max }' ${gauge_ascii_gnuplot}`;
my ($min_CD,$max_CD) = split(' ',$line_temp);
my $line_temp = `awk 'NR ==  $nheader+1  {max=\$5 ; min=\$5} \$5 >= max {max = \$5} \$5 <= min {min = \$5} END { print min, max }' ${gauge_ascii_gnuplot}`;
my ($min_Q,$max_Q) = split(' ',$line_temp);
my $Q_int = sprintf("%f",$max_Q/5);
my $max_Q = sprintf("%f",$max_Q + $Q_int);
my $CD_int = sprintf("%f",($max_CD - $min_CD)/3);
my $max_CD = sprintf("%f",$max_CD + $CD_int/3);
my $min_CD = sprintf("%f",$min_CD - $CD_int/3);

my $abs_CD;
if (abs($max_CD) > abs($min_CD)){$abs_CD = abs($max_CD);}
else {$abs_CD = abs($min_CD);}

#Create the gnuplot script 
my $fh_gnuplot_script = new FileHandle (">$gnuplot_script");
print $fh_gnuplot_script <<EOF;
unset log                              # remove any log-scaling
unset label                            # remove any previous labels
set title "$sd_title ($Initial_Time[0]/$Initial_Time[1]/$Initial_Time[2] - $Final_Time[0]/$Final_Time[1]/$Final_Time[2])" font "Helvetica,25";
set xdata time
set timefmt "%Y %m %d"
set format x $xtickfmt
set grid
set size 1.2,0.85
set xr ["$Initial_Time[0] $Initial_Time[1] $Initial_Time[2]":"$Final_Time[0] $Final_Time[1] $Final_Time[2]"]
unset xtics
set key horiz out bottom center samplen 1 spacing 1 #font "Helvetica=12"
set ytic nomirror
set yrange [0:$max_Q];
set ytic $Q_int - $Q_int/2,$Q_int,$max_Q-$Q_int/2;
set ylabel "$sd_ylabel2" font "Helvetica,18";
set encoding iso_8859_1
set term post enhanced color
set output "$Image_temp"
set multiplot;                          # get into multiplot mode
set size 1.2,0.5
set ytics font "Helvetica,16"
set origin 0.0,0.35;
set format y "%7.2f"
set style fill solid 0.4 noborder
a = $max_Q;
plot a title ">99%" with filledcurve y1=0 lc rgb "black",\\
"< awk '{for (i=1; i<=NF; i++) if (\$19 > $max_Q) \$19 = $max_Q-$max_Q/10000; if (\$19 < $max_Q) \$19 = \$19; print }' ${gauge_ascii_gnuplot}" using 1:19 every :: $nheader title "90-99%" with filledcurve y1=0 lc rgb "#0000CD",\\
"< awk '{for (i=1; i<=NF; i++) if (\$17 > $max_Q) \$17 = $max_Q-$max_Q/10000; if (\$17 < $max_Q) \$17 = \$17; print }' ${gauge_ascii_gnuplot}" using 1:17 every :: $nheader title "75-90%" with filledcurve y1=0 lc rgb "cyan",\\
"< awk '{for (i=1; i<=NF; i++) if (\$15 > $max_Q) \$15 = $max_Q-$max_Q/10000; if (\$15 < $max_Q) \$15 = \$15; print }' ${gauge_ascii_gnuplot}" using 1:15 every :: $nheader title "50-75%" with filledcurve y1=0 lc rgb "green",\\
"< awk '{for (i=1; i<=NF; i++) if (\$13 > $max_Q) \$13 = $max_Q-$max_Q/10000; if (\$13 < $max_Q) \$13 = \$13; print }' ${gauge_ascii_gnuplot}" using 1:13 every :: $nheader title "25-50%" with filledcurve y1=0 lc rgb "#9ACD32",\\
"< awk '{for (i=1; i<=NF; i++) if (\$11 > $max_Q) \$11 = $max_Q-$max_Q/10000; if (\$11 < $max_Q) \$11 = \$11; print }' ${gauge_ascii_gnuplot}" using 1:11 every :: $nheader title "10-25%" with filledcurve y1=0 lw 0 lc rgb "orange",\\
"< awk '{for (i=1; i<=NF; i++) if (\$9 > $max_Q) \$9 = $max_Q-$max_Q/10000; if (\$9 < $max_Q) \$9 = \$9; print }' ${gauge_ascii_gnuplot}" using 1:9 every :: $nheader title "1-10%" with filledcurve y1=0 lc rgb "#FF6347",\\
"< awk '{for (i=1; i<=NF; i++) if (\$7 > $max_Q) \$7 = $max_Q-$max_Q/10000; if (\$7 < $max_Q) \$7 = \$7; print }' ${gauge_ascii_gnuplot}" using 1:7 every :: $nheader title "<1%" with filledcurve y1=0 lc rgb "red",\\
"${gauge_ascii_gnuplot}" using 1:5 every :: $nheader notitle with line lt 1 lw 4 lc rgb "black"
set size 1.2,0.35
set origin 0.0,0.02;
unset key;
unset yrange;
unset ytic;
set format y "%7.2f"
set yrange [$min_CD:$max_CD];
set ytic $min_CD,$CD_int,$max_CD;
set ylabel "$sd_ylabel1" font "Helvetica,18";
set ytics font "Helvetica,16";
set xtics font "Helvetica,16";
set xlabel "$xlabel" font "Helvetica,18";
unset title
set style fill solid 1.0 border
set key horiz out bottom center
plot "< awk '{for (i=1; i<=NF; i++) if (\$14 > 0) \$14 = 0; if (\$14 < 0) \$14 = \$14; print }' ${gauge_ascii_gnuplot}" using 1:14 every :: $nheader title '50% (-)' with boxes fs solid 0.8 lt -1 lc rgb "#B22222",\\
"< awk '{for (i=1; i<=NF; i++) if (\$14 < 0) \$14 = 0; if (\$14 > 0) \$14 = \$14; print }' ${gauge_ascii_gnuplot}" using 1:14 every :: $nheader title '50% (+)' with boxes fs solid 0.8 lt -1 lc rgb "#483D8B",\\
0 notitle w lines lt 1 lw 1 lc rgb "black"
unset multiplot  
EOF
=pod
set key horiz
set ytics font "Helvetica,18"
set y2tics font "Helvetica,18"
set ytic nomirror
set yrange [0:$max_Q];
set ytic $Q_int - $Q_int/2,$Q_int,$max_Q-$Q_int/2;
set y2range [0:110];
set y2tic 10,20,90;
set ylabel "Q(m^{3}/s)" font "Helvetica,18";
set y2label "Percentile(%)" font "Helvetica,18";
set terminal postscript enhanced color
set output "$Image_temp"
set multiplot;                          # get into multiplot mode
set size 1.2,0.5
set origin 0.0,0.35;
set format y "%9.2e"
plot "${gauge_ascii_gnuplot}" using 1:5 every :: $nheader axes x1y2 title 'Percentile(%)' with line lt 2 lw 1 lc rgb "red",\\
 "${gauge_ascii_gnuplot}" using 1:6 every :: $nheader title 'Median(m^{3}/s)' with line lt 1 lw 3 lc rgb "green",\\
"${gauge_ascii_gnuplot}" using 1:4 every :: $nheader title 'Q(m^{3}/s)' with line lt 1 lw 3 lc rgb "blue"
set size 1.122,0.35
set origin 0.0,0.05;
unset key;
unset yrange;
unset ytic;
set yrange [$min_CD:$max_CD];
set ytic $min_CD,$CD_int,$max_CD;
set ylabel "Gigaliters*" font "Helvetica,18";
set ytics font "Helvetica,18";
set xtics font "Helvetica,18";
set xlabel "Time (day)" font "Helvetica,20";
unset y2tics;
unset y2label;
unset title;
set label "*Note: V = {/Symbol S}[24*3600*(Q(t) - Median(t))] where t = 1:n (t = 1 <=> 1/1/2011)." at screen 0.05,0.03
plot "< awk '{for (i=1; i<=NF; i++) if (\$7 > 0) \$7 = 0; if (\$7 < 0) \$7 = \$7/1000000; print }' ${gauge_ascii_gnuplot}" using 1:7 every :: $nheader title 'Cumulative Deficit (m^{3})' with boxes fs solid 0.7 lt 1 lc rgb "red",\\
"< awk '{for (i=1; i<=NF; i++) if (\$7 < 0) \$7 = 0; if (\$7 > 0) \$7 = \$7/1000000; print }' ${gauge_ascii_gnuplot}" using 1:7 every :: $nheader with boxes fs solid 0.7 lt 1 lc rgb "blue",\\
0 w lines lt 1 lw 1 lc rgb "black"
unset multiplot
EOF
=cut
close($fh_gnuplot_script);
system("gnuplot $gnuplot_script");
system("convert $Image_temp -rotate 90 -background white -mosaic +matte $Image_output");

#Rename image output for webserver
my $Image_output = $html_workspace."/ds_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}${Initial_Time[2]}-${Final_Time[0]}${Final_Time[1]}${Final_Time[2]}.png";

#Print image filename to the screen
print $Image_output." ".$ascii_output;

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


















