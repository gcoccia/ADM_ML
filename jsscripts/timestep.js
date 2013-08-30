function update_timestep()
{
  var current_timestep = $("ul.ts-selection li.active").attr('id');
  var abbrevs = {"daily": "D", "monthly": "M", "yearly": "Y"};
  var ddlink;

  for(dataset in data_timesteps) {
    ddlink = $("ul.datalist>li>ul.dropdown-menu>li>a#" + dataset);

    if(data_timesteps[dataset].indexOf(abbrevs[current_timestep]) == -1) {
      ddlink.parent().hide(150, function() {});
      if(ddlink).parent().hasClass("active")) {
        ddlink.parent().removeClass("active");
        ddlink.parent().parent().parent().removeClass("active");
      }
    }
    else
      $("ul.datalist>li>ul.dropdown-menu>li>a#" + dataset).parent().show(150, function() {});
  }

  // Disable/Enable the relevant timestamp input boxes depending which radio button is selected
  if(""+current_timestep == "daily")
    $("input[id='day_initial'], input[id='day_final']").show(150, function() {});
  else
    $("input[id='day_initial'], input[id='day_final']").hide(150, function() {});

  if(""+current_timestep == "yearly")
    $("input[id='month_initial'], input[id='month_final']").hide(150, function() {});
  else
    $("input[id='month_initial'], input[id='month_final']").show(150, function() {});

  // loop through dropdown list and hide anything with no dropdown links
  /*$("ul.datalist>li").each(function(index) {
    if($(this).find("ul.dropdown-menu>li:visible").length == 0)
      $(this).hide(150, function() {});
  });*/
}

function Update_TimeStamp_MP(increment, flag_timestamp)
{
  var current_timestep = $("input[name='ts-radio']:checked").attr('id');
  var date_temp, i_or_f;

  var initial_date = new Date(parseInt($("#year_initial").val()),
                           parseInt($("#month_initial").val())-1,
                           parseInt($("#day_initial").val()));
  var final_date = new Date(parseInt($("#year_final").val()),
                           parseInt($("#month_final").val())-1,
                           parseInt($("#day_final").val()));

  if (flag_timestamp == 0) {
    date_temp = initial_date;
    i_or_f = "initial";
  }
  else {
    date_temp = final_date;
    i_or_f = "final";
  }

  //Find the next or previous timestamp
  if(""+current_timestep == "daily")
    date_temp.setDate(date_temp.getDate() + increment);
  else if(""+current_timestep == "monthly")
    date_temp.setMonth(date_temp.getMonth() + increment); // will loop around 12 automatically
  else 
    date_temp.setFullYear(date_temp.getFullYear() + increment);
  
  if (flag_timestamp == 0 && date_temp.valueOf() > final_date.valueOf()) return;
  else if (flag_timestamp == 1 && date_temp.valueOf() < initial_date.valueOf()) return;
  else if (flag_timestamp == 1 && date_temp.valueOf() > (new Date()).valueOf()) return;

  // Update the time string
  $("#year_" + i_or_f).val(date_temp.getFullYear());
  $("#month_" + i_or_f).val(date_temp.getMonth() + 1);
  $("#day_" + i_or_f).val(date_temp.getDate());
}

function UpdatePopUpTimestep(j)
{
  var j;
  if(j == 0)//Daily time step
  {
    document.getElementById('time_interval_text').innerHTML = '({$_("dd/mm/yyyy")})';
    document.getElementById('gauge_initial_time').innerHTML = '{$_("Initial Time")}:  <input type="text" size=1 name="gauge_day_initial" value=' + gauge_day_initial + '><input type="text" size=1 name="gauge_month_initial" value=' + gauge_month_initial + '><input type="text" size=3 name="gauge_year_initial" value=' + gauge_year_initial + '>';
    document.getElementById('gauge_final_time').innerHTML = '{$_("Final Time")}:  <input type="text" size=1 name="gauge_day_final" value=' + gauge_day_final + '><input type="text" size=1 name="gauge_month_final" value=' + gauge_month_final + '><input type="text" size=3 name="gauge_year_final" value=' + gauge_year_final + '>';
    timestep_flag = 1;
    SwapGaugeImage(image_type);
  }
  if(j == 1) //Monthly time step
  {
    document.getElementById('time_interval_text').innerHTML = '({$_("mm/yyyy")})';
    document.getElementById('gauge_initial_time').innerHTML = '{$_("Initial Time")}:  <input type="text" size=1 name="gauge_month_initial" value=' + gauge_month_initial_monthly + '><input type="text" size=3 name="gauge_year_initial" value=' + gauge_year_initial_monthly + '>';
    document.getElementById('gauge_final_time').innerHTML = '{$_("Final Time")}:  <input type="text" size=1 name="gauge_month_final" value=' + gauge_month_final_monthly + '><input type="text" size=3 name="gauge_year_final" value=' + gauge_year_final_monthly + '>';
    timestep_flag = 2;
    SwapGaugeImage(image_type);
  }
}