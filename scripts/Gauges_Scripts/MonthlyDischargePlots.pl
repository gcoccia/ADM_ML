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
my $sd_title = $ARGV[9];
$sd_title =~ s/_/ /g;
my $sd_ylabel1 = $ARGV[10];
$sd_ylabel1 =~ s/_/ /g;
my $sd_ylabel2 = $ARGV[11];
$sd_ylabel2 =~ s/_/ /g;
my $xlabel = $ARGV[12];
$xlabel =~ s/_/ /g;
my $workspace = "/tmp/ADM_workspace";
my $html_workspace = "ADM_workspace";
#my $workspace = "/raid2/hydrology/data/nchaney/Workspace";
#my $html_workspace = "/raid2/hydrology/data/nchaney/Workspace";
#system("rm $workspace/*");
my $file_realtime = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Realtime/Routed_output/gauge_${gauge_number}";
my $file_spinup = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Catch_up/Routed_output/gauge_${gauge_number}";
my $file_historical = $Webpage_Root_Dir."/Africa_Drought_Monitor_Webpage/Data/ADM_Data/Historical/Gauges/${gauge_number}";
my $Image_output = $workspace."/ds_monthly_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}-${Final_Time[0]}${Final_Time[1]}.png";

#Get last day of month
my @Temp_Time = &inverse_julian_day(&julian_day(@Final_Time) + 32);
$Temp_Time[2] = 1;
my @Final_Time = &inverse_julian_day(&julian_day(@Temp_Time) - 1);

#Local Variables
my @Temp_Time;
my $range = 10000;
my $random_number = int(rand($range)); #Random number to make sure queries do not overlap
my $gnuplot_script = $workspace."/gauge_gnuplot_script_${random_number}.txt";
my $Image_temp = $workspace."/Image_gauge_ds_temp_${random_number}.ps";
my $gauge_ascii_gnuplot = $workspace."/gauge_ds_${gauge_number}_${random_number}.txt";
my $ascii_output = $html_workspace."/gauge_ds_${gauge_number}_${random_number}.txt";
my $monthly_temp = $workspace."/daily_temp_${random_number}.txt";
my $nheader = 23;
my @Current_Time;

#Remove the previous text files
if (-e ${gauge_ascii_gnuplot})
	{
	system("rm ${gauge_ascii_gnuplot}");
	}

if (-e ${monthly_temp})
	{
	system("rm ${monthly_temp}");
	}

#Grab each part of the daily time series 
my $print_string = "{print \$1,\$2,\$3,\$4,\$8,\$5,\$9,\$15,\$10,\$16,\$11,\$17,\$6,\$7,\$12,\$18,\$13,\$19,\$14,\$20}";
@Temp_Time = @Initial_Time;
if (&julian_day(@Initial_Time) <= &julian_day(2008,12,31))
	{
	my $awk_script =  "awk \'/${Temp_Time[0]} *${Temp_Time[1]} *${Temp_Time[2]}/,/${Final_Time[0]} *${Final_Time[1]} *${Final_Time[2]}/ $print_string\' /${file_historical} >> ${monthly_temp}";
	system("${awk_script}");
	@Temp_Time = (2009,1,1);
	}
if (&julian_day(@Initial_Time) <= &julian_day(2011,9,30) && &julian_day(@Final_Time) > &julian_day(2008,12,31))
	{
	my $awk_script =  "awk \'/${Temp_Time[0]} *${Temp_Time[1]} *${Temp_Time[2]}/,/${Final_Time[0]} *${Final_Time[1]} *${Final_Time[2]}/ $print_string \' /${file_spinup} >> ${monthly_temp}";
	system("${awk_script}");;
	@Temp_Time = (2011,10,1);
	}
if (&julian_day(@Final_Time) > &julian_day(2011,9,30))
	{
	my $awk_script =  "awk \'/${Temp_Time[0]} *${Temp_Time[1]} *${Temp_Time[2]}/,/${Final_Time[0]} *${Final_Time[1]} *${Final_Time[2]}/ $print_string \' /${file_realtime} >> ${monthly_temp}";
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
system("echo \'column 3: Monthly Average Daily Discharge (m^3/s)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 4: Monthly Average Daily Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 5: Corresponding Percentile to Monthly Average Daily Runoff\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 6: 1st Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 7: Cumulative Surplus with respect to 1st percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 8: 10th Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 9: Cumulative Surplus with respect to 10th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 10: 25th Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 11: Cumulative Surplus with respect to 25th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 12: 50th Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 13: Cumulative Surplus with respect to 50th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 14: 75th Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 15: Cumulative Surplus with respect to 75th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 16: 90th Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 17: Cumulative Surplus with respect to 90th percentile (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 18: 99th Percentile Runoff (mm)\' >> ${gauge_ascii_gnuplot}");
system("echo \'column 19: Cumulative Surplus with respect to 99th percentile (mm)\' >> ${gauge_ascii_gnuplot}");

#Get monthly averages and attach them to the file
@Current_Time = @Initial_Time;
while (&julian_day(@Current_Time) <= &julian_day(@Final_Time))
        {
        #my $awk_script = "awk \'BEGIN {n = 0} /$Current_Time[0] $Current_Time[1] / {n+=1; c1 = \$1; c2 = \$2; c4+=\$4; c5+=\$5; c6+=\$6; c7+=\$7; } END {print c1,c2,c4/n,c5/n,c6/n,c7/n}\' ${monthly_temp}";
        my $awk_script = "awk \'BEGIN {n = 0} /$Current_Time[0] $Current_Time[1] / {n+=1; c1 = \$1; c2 = \$2; c4+=\$4; c5+=\$5; c6+=\$6; c7+=\$7; c8+=\$8; c9+=\$9; c10+=\$10; c11+=\$11; c12+=\$12; c13+=\$13; c14+=\$14; c15+=\$15; c16+=\$16; c17+=\$17; c18+=\$18; c19+=\$19; c20+=\$20} END {print c1,c2,c4/n,c5/n,c6/n,c7/n,c8/n,c9/n,c10/n,c11/n,c12/n,c13/n,c14/n,c15/n,c16/n,c17/n,c18/n,c19/n,c20/n}\' ${monthly_temp}";
        system("$awk_script >> $gauge_ascii_gnuplot");
        @Current_Time = &inverse_julian_day(&julian_day(@Current_Time) + 32);
        @Current_Time[2] = 1;
        }

#Figure out the number of months
my $ndays = &julian_day(@Final_Time) - &julian_day(@Initial_Time) + 1;
my $xtickfmt;
if ($ndays <= 5*365){$xtickfmt = '"%m/%y"';}
else {$xtickfmt = '"%Y"'};

#Find the minimum and maximum of our data
my $line_temp = `awk 'NR == $nheader + 1 {max=\$13 ; min=\$13} \$13 >= max {max = \$13} \$13 <= min {min = \$13} END { print min, max }' ${gauge_ascii_gnuplot}`;
my ($min_CD,$max_CD) = split(' ',$line_temp);
my $line_temp = `awk 'NR ==  $nheader + 1 {max=\$4 ; min=\$4} \$4 >= max {max = \$4} \$4 <= min {min = \$4} END { print min, max }' ${gauge_ascii_gnuplot}`;
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
set title "$sd_title ($Initial_Time[0]/$Initial_Time[1] - $Final_Time[0]/$Final_Time[1])" font "Helvetica,25";
set xdata time
set timefmt "%Y %m"
set format x $xtickfmt
set grid
set size 1.2,0.85
set xr ["$Initial_Time[0] $Initial_Time[1]":"$Final_Time[0] $Final_Time[1]"]
unset xtics
set key horiz out bottom center samplen 1 spacing 1
set ytics font "Helvetica,16"
set ytic nomirror
set yrange [0:$max_Q];
set ytic $Q_int - $Q_int/2,$Q_int,$max_Q-$Q_int/2;
set ylabel "$sd_ylabel2" font "Helvetica,18";
set terminal postscript enhanced color
set output "$Image_temp"
set multiplot;                          # get into multiplot mode
set size 1.2,0.5
set origin 0.0,0.35;
set format y "%7.2f"
set style fill solid 0.4 noborder
a = $max_Q;
plot a title ">99%" with filledcurve y1=0 lc rgb "black",\\
"< awk '{for (i=1; i<=NF; i++) if (\$18 > $max_Q) \$18 = $max_Q-$max_Q/10000; if (\$18 < $max_Q) \$18 = \$18; print }' ${gauge_ascii_gnuplot}" using 1:18 every :: $nheader title "90-99%" with filledcurve y1=0 lc rgb "#0000CD",\\
"< awk '{for (i=1; i<=NF; i++) if (\$16 > $max_Q) \$16 = $max_Q-$max_Q/10000; if (\$16 < $max_Q) \$16 = \$16; print }' ${gauge_ascii_gnuplot}" using 1:16 every :: $nheader title "75-90%" with filledcurve y1=0 lc rgb "cyan",\\
"< awk '{for (i=1; i<=NF; i++) if (\$14 > $max_Q) \$14 = $max_Q-$max_Q/10000; if (\$14 < $max_Q) \$14 = \$14; print }' ${gauge_ascii_gnuplot}" using 1:14 every :: $nheader title "50-75%" with filledcurve y1=0 lc rgb "green",\\
"< awk '{for (i=1; i<=NF; i++) if (\$12 > $max_Q) \$12 = $max_Q-$max_Q/10000; if (\$12 < $max_Q) \$12 = \$12; print }' ${gauge_ascii_gnuplot}" using 1:12 every :: $nheader title "25-50%" with filledcurve y1=0 lc rgb "#9ACD32",\\
"< awk '{for (i=1; i<=NF; i++) if (\$10 > $max_Q) \$10 = $max_Q-$max_Q/10000; if (\$10 < $max_Q) \$10 = \$10; print }' ${gauge_ascii_gnuplot}" using 1:10 every :: $nheader title "10-25%" with filledcurve y1=0 lw 0 lc rgb "orange",\\
"< awk '{for (i=1; i<=NF; i++) if (\$8 > $max_Q) \$8 = $max_Q-$max_Q/10000; if (\$8 < $max_Q) \$8 = \$8; print }' ${gauge_ascii_gnuplot}" using 1:8 every :: $nheader title "1-10%" with filledcurve y1=0 lc rgb "#FF6347",\\
"< awk '{for (i=1; i<=NF; i++) if (\$6 > $max_Q) \$6 = $max_Q-$max_Q/10000; if (\$6 < $max_Q) \$6 = \$6; print }' ${gauge_ascii_gnuplot}" using 1:6 every :: $nheader title "<1%" with filledcurve y1=0 lc rgb "red",\\
"${gauge_ascii_gnuplot}" using 1:4 every :: $nheader notitle with line lt 1 lw 4 lc rgb "black"
set size 1.2,0.35
set origin 0.0,0.02;
unset key;
unset yrange;
unset ytic;
set yrange [$min_CD:$max_CD];
set ytic $min_CD,$CD_int,$max_CD;
set ylabel "$sd_ylabel1" font "Helvetica,18";
set ytics font "Helvetica,16";
set xtics font "Helvetica,16";
set xlabel "$xlabel" font "Helvetica,18";
unset title
set style fill solid 1.0 border
set key horiz out bottom center
plot "< awk '{for (i=1; i<=NF; i++) if (\$13 > 0) \$13 = 0; if (\$13 < 0) \$13 = \$13; print }' ${gauge_ascii_gnuplot}" using 1:13 every :: $nheader title '50% (-)' with boxes fs solid 0.8 lt -1 lc rgb "#B22222",\\
"< awk '{for (i=1; i<=NF; i++) if (\$13 < 0) \$13 = 0; if (\$13 > 0) \$13 = \$13; print }' ${gauge_ascii_gnuplot}" using 1:13 every :: $nheader title '50% (+)' with boxes fs solid 0.8 lt -1 lc rgb "#483D8B",\\
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
plot "${gauge_ascii_gnuplot}" using 1:4 every :: $nheader axes x1y2 title 'Percentile(%)' with line lt 2 lw 1 lc rgb "red",\\
 "${gauge_ascii_gnuplot}" using 1:5 every :: $nheader title 'Median(m^{3}/s)' with line lt 1 lw 3 lc rgb "green",\\
"${gauge_ascii_gnuplot}" using 1:3 every :: $nheader title 'Q(m^{3}/s)' with line lt 1 lw 3 lc rgb "blue"
EOF
=pod
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
set xlabel "Time (month)" font "Helvetica,20";
unset y2tics;
unset y2label;
unset title;
set label "*Note: V = {/Symbol S}[24*3600*(Q(t) - Median(t))] where t = 1:n (t = 1 <=> 1/1/2011)." at screen 0.05,0.03
plot "< awk '{for (i=1; i<=NF; i++) if (\$6 > 0) \$6 = 0; if (\$6 < 0) \$6 = \$6/1000000; print }' ${gauge_ascii_gnuplot}" using 1:6 every :: $nheader title 'Cumulative Deficit (m^{3})' with boxes fs solid 0.7 lt 1 lc rgb "red",\\
"< awk '{for (i=1; i<=NF; i++) if (\$6 < 0) \$6 = 0; if (\$6 > 0) \$6 = \$6/1000000; print }' ${gauge_ascii_gnuplot}" using 1:6 every :: $nheader with boxes fs solid 0.7 lt 1 lc rgb "blue",\\
0 w lines lt 1 lw 1 lc rgb "black"
unset multiplot
EOF
=cut
close($fh_gnuplot_script);
system("gnuplot $gnuplot_script");
system("convert $Image_temp -rotate 90 -background white -mosaic +matte $Image_output");

#Rename image output for webserver
my $Image_output = $html_workspace."/ds_monthly_${gauge_number}_${Initial_Time[0]}${Initial_Time[1]}-${Final_Time[0]}${Final_Time[1]}.png";

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


















