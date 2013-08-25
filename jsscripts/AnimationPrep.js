function ReadTimeInterval()
{
  year_initial = parseInt(document.forms["AnimationForm"]["year_initial"].value);
  year_final = parseInt(document.forms["AnimationForm"]["year_final"].value);
  month_initial = parseInt(document.forms["AnimationForm"]["month_initial"].value);
  month_final = parseInt(document.forms["AnimationForm"]["month_final"].value);
  day_initial = parseInt(document.forms["AnimationForm"]["day_initial"].value);
  day_final = parseInt(document.forms["AnimationForm"]["day_final"].value);
  //Set Time delay between images
  frames_per_second = parseInt(document.forms["AnimationForm"]["frames_per_second"].value);
}

function ImageArrayPrep(ImageStrArray,ImageTimeArray)
{

  //Because we are lacking SMOS data, we will set the latest date to equate to 5/26/2012
/*  if (variable_image_number == 16){
    //Make the dates
    SMOS_date_final = new Date(year_final,month_final-1,day_final);
    SMOS_date_initial = new Date(year_initial,month_initial-1,day_initial);
    SMOS_date_final_available = new Date(2012,5-1,26);
    if (SMOS_date_final.getTime() > SMOS_date_final_available.getTime()){
      year_final = 2012;
      month_final = 5;
      day_final = 26;
      alert("SMOS data is available up to May 26th,2012");
      }
                if (SMOS_date_initial.getTime() > SMOS_date_final_available.getTime()){
                        year_initial = 2012;
                        month_initial = 5;
                        day_initial = 26;
                        }
    }*/

  var current_timestep = $("input[name='ts-radio']:checked").attr('id');
  var dataset = $("input[name='group1']:checked").attr('id');
  var initial_date = new Date(year_initial, month_initial-1, day_initial);
  var final_date = new Date(year_final, month_final-1, day_final);
  var date_temp = initial_date;
  var framect = 0;
  var Dstring, Mstring, Ystring, tstring, tstamp;

  // Example image urls:
  //../IMAGES/DAILY/19480101/PGF_prec_19480101_daily.svg
  //../IMAGES/MONTHLY/200212/...
  //../IMAGES/YEARLY/1948/...etc.

  while(date_temp.valueOf() <= final_date.valueOf())
  {
    Dstring = sprintf("%02d",parseInt(date_temp.getDate()));
    Mstring = sprintf("%02d",parseInt(date_temp.getMonth()+1));
    Ystring = sprintf("%02d", parseInt(date_temp.getFullYear()));

    if(""+current_timestep == "daily") {
      tstring = Ystring + Mstring + Dstring;
      tstamp = Ystring + "/" + Mstring + "/" + Dstring;
      date_temp.setDate(date_temp.getDate() + 1);
    }
    else if(""+current_timestep == "monthly") {
      tstring = Ystring + Mstring;
      tstamp = Ystring + "/" + Mstring;
      date_temp.setMonth(date_temp.getMonth() + 1);
    }
    else {
      tstring = Ystring;
      tstamp = Ystring;
      date_temp.setFullYear(date_temp.getFullYear() + 1);
    }

    ImageStrArray[framect] = "../IMAGES/" + current_timestep.toUpperCase() + "/" + tstring + "/" + dataset + "_" + tstring + "_" + current_timestep + ".svg";
    ImageTimeArray[framect] = tstamp;
    framect += 1;
  }

}

/*function ImageArrayPrep_SPI(ImageStrArray,ImageStrRoot,ImageTimeArray)
        {
        var day;
        var month;
        var mi;
        var mf;
        var di;
        var df;
        var Data_Dir = "Data/ADM_Data";
        var Time_Period;
        var ndays = [31,28,31,30,31,30,31,31,30,31,30,31];
        var temp;
  var min;
  var min_pos;
        daycount = 0;
        var t;
  var nweeks;
  var date_temp;
  var date_initial;
  //Assume every year is a leap year (2008)
        for (year = year_initial; year < year_final + 1; year++)
                {
    date_initial = new Date(2012,0,1);
                if (year == year_initial){mi = month_initial;}
                else {mi = 1;}
                if (year == year_final){mf = month_final;}
                else {mf = 12;}
                for (month = mi; month < mf + 1; month++)
                        {
                        if (year == year_initial && month == month_initial){di = day_initial}
                        else {di = 1}
                        if (year == year_final && month == month_final){df = day_final}
                        else {df = ndays[month-1]}
                        for (day = di; day < df + 1; day++)
                                {
                                Time_Period = "/Realtime";
        if (year >= 2012){
          //Update every week
          //Find the julian time stamp
          date_temp = new Date(year,month-1,day);
          //Calculate the number of days between the time stamp and Jan. 1st
          temp = Math.floor((date_temp.getTime() - date_initial.getTime())/1000/60/60/24);
          //Calculate the number of weeks that have been completed
          nweeks = Math.floor(temp/7);
          //Calculate the last day for which a week has completed
          temp = 7*nweeks;
          //Add these days to Jan 1st to obtain the date
          date_temp = date_initial;
          date_temp.setDate(date_temp.getDate() + temp);
                                  ImageStrArray[daycount] = Data_Dir + Time_Period + ImageStrRoot + sprintf("%02d",parseInt(year)) + sprintf("%02d",parseInt(date_temp.getMonth()+1)) + sprintf("%02d",parseInt(date_temp.getDate())) + ".gif"; 
        }
        else {
          //Set to Jan. 1st
                                  ImageStrArray[daycount] = Data_Dir + Time_Period + ImageStrRoot + sprintf("%02d",parseInt(year)) + sprintf("%02d",parseInt(month)) + sprintf("%02d",parseInt(1)) + ".gif";  
        }         
                                ImageTimeArray[daycount] = sprintf("%02d",parseInt(day)) + "/" + sprintf("%02d",parseInt(month)) + "/" + sprintf("%02d",parseInt(year));
                                daycount = daycount + 1
        }
      }
    }
  }*/

function ChangeTimeStamp(flag_time,i,j)
{
  var flag_time;
  var j;
  var i;
  obj = document.getElementById("TimeStamp").style;
  //Option 1: Add time stamp to map
  if (flag_time == 1)
  {
    obj.visibility = "visible";
    obj.height = "100";
    contentString = "<h2>" + ImageTimeArray[j][i] + "</h2>";
    //contentString = "<div>" + ImageTimeArray[j][i] + "</div>";
    document.getElementById('TimeStamp').innerHTML = contentString;
  }
  //Option 2: Update time stamp on map
  if (flag_time == 2)
  {
    contentString = "<h2>" + ImageTimeArray[j][i] + "</h2>";;
    //contentString = "<div>" + ImageTimeArray[j][i] + "</div>";
    document.getElementById('TimeStamp').innerHTML = contentString;
  }
  //Option 3: Remove time stamp form map
  if (flag_time == 3)
  {
    obj.visibility = "hidden";
    obj.height = "";
  }
}
