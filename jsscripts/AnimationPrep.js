function ReadTimeInterval()
{
  year_initial = parseInt(document.forms["AnimationForm"]["year_initial"].value);
  year_final = parseInt(document.forms["AnimationForm"]["year_final"].value);
  month_initial = parseInt(document.forms["AnimationForm"]["month_initial"].value);
  month_final = parseInt(document.forms["AnimationForm"]["month_final"].value);
  day_initial = parseInt(document.forms["AnimationForm"]["day_initial"].value);
  day_final = parseInt(document.forms["AnimationForm"]["day_final"].value);
  //Set Time delay between images
  //frames_per_second = parseInt(document.forms["AnimationForm"]["frames_per_second"].value);
}

function ImageArrayPrep(ImageStrArray,ImageTimeArray)
{

  var current_timestep = $("ul.ts-selection li.active").attr('id');
  var dataset = $("ul.datalist>li>ul.dropdown-menu>li.active").find("a").attr('id');
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
      dir_tstring = Ystring + '/' + Mstring + '/' + Dstring;
      tstamp = Ystring + "/" + Mstring + "/" + Dstring;
      date_temp.setDate(date_temp.getDate() + 1);
    }
    else if(""+current_timestep == "monthly") {
      dir_tstring = Ystring + '/' + Mstring;
      tstring = Ystring + Mstring;
      tstamp = Ystring + "/" + Mstring;
      date_temp.setMonth(date_temp.getMonth() + 1);
    }
    else {
      tstring = Ystring;
      dir_tstring = Ystring;
      tstamp = Ystring;
      date_temp.setFullYear(date_temp.getFullYear() + 1);
    }

    //ImageStrArray[framect] = "../IMAGES/" + current_timestep.toUpperCase() + "/" + tstring + "/" + dataset + "_" + tstring + ".png";
    ImageStrArray[framect] = "../IMAGES/" + dir_tstring + "/" + dataset + "_" + tstring + ".png";
    ImageTimeArray[framect] = tstamp;
    framect += 1;
  }

}

function ChangeTimeStamp(flag_time,i,j)
{
  obj = document.getElementById("TimeStamp").style;
  //Option 1: Add time stamp to map
  if (flag_time == 1)
  {
    obj.visibility = "visible";
    obj.height = "100";
    contentString = "<h2>" + ImageTimeArray[j][""+i] + "</h2>";
    //contentString = "<div>" + ImageTimeArray[j][i] + "</div>";
    document.getElementById('TimeStamp').innerHTML = contentString;
  }
  //Option 2: Update time stamp on map
  if (flag_time == 2)
  {
    contentString = "<h2>" + ImageTimeArray[j][""+i] + "</h2>";;
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
