function update_timestep()
{
  var dataset = $("input[name='group1']:checked").attr('id');
  var current_timestep = $("input[name='ts-radio']:checked").attr('id');
  var all_unchecked = true;

  // Check in the XML settings which of these actually exist for this dataset.
  // If they don't exist, disable the button. Also if it was checked before, check something else instead.
  if(data_timesteps[dataset].indexOf("D") == -1) {
    $("input[id='daily']:radio").prop({disabled: true, checked: false});
    if(""+current_timestep == "daily")
      $("input[id='monthly']:radio").prop({checked: true});
  } else {
    $("input[id='daily']:radio").prop({disabled: false});
  }
  current_timestep = $("input[name='ts-radio']:checked").attr('id');

  if(data_timesteps[dataset].indexOf("M") == -1) {
    $("input[id='monthly']:radio").prop({disabled: true, checked: false});
    if(""+current_timestep == "monthly")
      $("input[id='yearly']:radio").prop({checked: true});
  } else {
    $("input[id='monthly']:radio").prop({disabled: false});
  }
  current_timestep = $("input[name='ts-radio']:checked").attr('id');

  if(data_timesteps[dataset].indexOf("Y") == -1) {
    $("input[id='yearly']:radio").prop({disabled: true, checked: false});
    if(""+current_timestep == "yearly") {
      if(data_timesteps[dataset].indexOf("D") != -1)
        $("input[id='daily']:radio").prop({checked: true});
      else if(data_timesteps[dataset].indexOf("M") != -1)
        $("input[id='monthly']:radio").prop({checked: true});
    }
  }else {
    $("input[id='yearly']:radio").prop({disabled: false});
  }
  current_timestep = $("input[name='ts-radio']:checked").attr('id');

  if(""+current_timestep == "daily") {
    $("input[id='day_initial'], input[id='month_initial'], input[id='year_initial']").prop({disabled: false});
    $("input[id='day_final'], input[id='month_final'], input[id='year_final']").prop({disabled: false});
  }
  else if(""+current_timestep == "monthly") {
    $("input[id='month_initial'], input[id='year_initial'], input[id='month_final'], input[id='year_final']").prop({disabled: false});
    $("input[id='day_initial'], input[id='day_final']").prop({disabled: true});
  } else {
    $("input[id='year_initial'], input[id='year_final']").prop({disabled: false});
    $("input[id='day_initial'], input[id='month_initial'], input[id='day_final'], input[id='month_final']").prop({disabled: true});
  }

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
  else if (flag_timestamp == 1 && date.temp.valueOf() > (new Date()).valueOf()) return;

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