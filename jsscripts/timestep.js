function update_timestep()
{
  var current_timestep = $("input[name='ts-radio']:checked").attr('id');
  var abbrevs = {"daily": "D", "monthly": "M", "yearly": "Y"};

  for(dataset in data_timesteps) {
    if(data_timesteps[dataset].indexOf(abbrevs[current_timestep]) == -1)
      $("input[id='" + dataset + "']:radio").parent().hide();
    else
      $("input[id='" + dataset + "']:radio").parent().show();
  }

  // If the currently-checked button is now disabled, pick a different one
  var current_dataset = $("input[name='group1']:checked");
  if(!current_dataset.is(':visible')) {
    current_dataset.prop('checked', false);

    for(var dataset in data_timesteps) {
      console.log("AAA");
      if($("input[id='" + dataset + "']:radio").is(':visible')) {
        $("input[id='" + dataset + "']:radio").prop('checked', true);
        break;
      }
    }
  }

  // Disable/Enable the relevant timestamp input boxes depending which radio button is selected
  if(""+current_timestep == "daily")
    $("input[id='day_initial'], input[id='day_final']").show();
  else
    $("input[id='day_initial'], input[id='day_final']").hide();

  if(""+current_timestep == "yearly")
    $("input[id='month_initial'], input[id='month_final']").hide();
  else
    $("input[id='month_initial'], input[id='month_final']").show();
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