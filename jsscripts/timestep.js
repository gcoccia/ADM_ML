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