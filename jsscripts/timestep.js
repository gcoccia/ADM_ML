function update_timestep()
{
  var current_timestep = $("ul.ts-selection li.active").attr('id');
  var abbrevs = {"daily": "D", "monthly": "M", "yearly": "Y"};
  var ddlink;

  for(dataset in data_timesteps) {
    ddlink = $("ul.datalist>li>ul.dropdown-menu>li>a#" + dataset);

    if(data_timesteps[dataset].indexOf(abbrevs[current_timestep]) == -1) {
      ddlink.parent().hide(150, function() {});
      ddlink.parent().removeClass("visible-data");

      if(ddlink.parent().hasClass("active")) {
        ddlink.parent().removeClass("active");
        ddlink.parent().parent().parent().removeClass("active");
        ddlink.parent().parent().parent().find("a.dropdown-toggle>i").removeClass("icon-ok");
        ddlink.find('i').removeClass("icon-ok");
      }
    }
    else {
      ddlink.parent().show(150, function() {});
      ddlink.parent().addClass("visible-data");
    }
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
  $("ul.datalist>li").each(function(index) {
    if($(this).find("ul.dropdown-menu>li.visible-data").length == 0) {
      $(this).hide(150, function() {});
    } else {
      $(this).show(150, function() {});
    }
  });
}

function Update_TimeStamp_MP(increment, flag_timestamp)
{
  var current_timestep = $("ul.ts-selection li.active").attr('id');
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
  
  var morf = $("ul.monitor-or-forecast>li.active").find("a").attr('id');
  if (flag_timestamp == 0 && increment == 1 && date_temp.valueOf() > final_date.valueOf()) {
    if(""+morf == "monitor") return;
  }
  else if (flag_timestamp == 1 && increment == -1 && date_temp.valueOf() < initial_date.valueOf()) return;

  // Update the time string
  $("#year_" + i_or_f).val(date_temp.getFullYear());
  $("#month_" + i_or_f).val(date_temp.getMonth() + 1);
  $("#day_" + i_or_f).val(date_temp.getDate());
}

function data_dates_are_valid()
{
  var dataset = $("ul.datalist>li>ul.dropdown-menu>li.active").find("a").attr('id');
  var initial_date = new Date(parseInt($("#year_initial").val()),
                           parseInt($("#month_initial").val())-1,
                           parseInt($("#day_initial").val()));
  var final_date = new Date(parseInt($("#year_final").val()),
                           parseInt($("#month_final").val())-1,
                           parseInt($("#day_final").val()));

  if(Date.parse(data_idates[dataset]).valueOf() <= initial_date.valueOf()
  && Date.parse(data_fdates[dataset]).valueOf() >= final_date.valueOf())
    return true;
  else return false; 
}