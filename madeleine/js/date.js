jQuery(document).ready(function ($) {

  // Date archive

  var $all_months = $('#date-archive .date-months'),
      $all_days = $('#date-archive .date-days'),
      $all_uls = $('#date-archive ul'),
      $date_go = $('#date-go'),
      home_url = $('body').data('url');

  function FilterDate() {
    var y = current_date[0];
    var m = current_date[1];
    var d = current_date[2];
    $all_uls.removeClass('open');
    var $year = $('.date-year[data-value*="' + y + '"]');
    $year.siblings().removeClass('on');
    $year.addClass('on');
    var $months = $('.date-months[data-year*="' + y + '"]');
    $all_months.hide();
    $months.show();
    if (m) {
      var $month = $months.find('.date-month[data-value="' + m + '"]');
      $month.siblings().removeClass('on');
      $month.addClass('on');
      var $days = $('.date-days[data-year="' + y + '"][data-month="' + m + '"]');
      $all_days.hide();
      $days.show();
      if (d) {
        var $day = $days.find('.date-day[data-value="' + d + '"]');
        $day.siblings().removeClass('on');
        $day.addClass('on');
      } else {
        $days.find('.date-day').removeClass('on');
        $days.find('.date-select').addClass('on');
      }
    } else {
      $months.find('.date-month').removeClass('on');
      $months.find('.date-select').addClass('on');
      $all_days.hide();
    }
    console.log(current_date);
  }

  var current_date = new Array($('#date-archive').data('year'), $('#date-archive').data('month'), $('#date-archive').data('day'));
  FilterDate();

  $('#date-archive li').click( function() {
    var $ul = $(this).parent();
    var $li = $(this);
    if ( $ul.hasClass('open') ) {
      if ( $li.hasClass('date-year') ) {
        var new_year = $li.data('value');
        current_date = [new_year, 0, 0];
        FilterDate();
      } else if ( $li.hasClass('date-month') ) {
        var new_month = $li.data('value');
        current_date[1] = new_month;
        current_date[2] = 0;
        FilterDate();
      } else if ( $li.hasClass('date-day') ) {
        var new_day = $li.data('value');
        current_date[2] = new_day;
        FilterDate();
      } else if ( $li.hasClass('date-select') ) {
        var type = $li.data('type');
        if (type == 'month') {
         current_date[1] = 0;
         current_date[2] = 0;
        } else if (type == 'day') {
         current_date[2] = 0;
        }
        FilterDate();
      }
    } else {
      $ul. addClass('open');
    }
  });

  $('#date-go').click( function() {
    var url = home_url + '/' + current_date[0];
    $.each([current_date[1], current_date[2]], function(index, value) {
      if (value != 0) {
        var parameter = value.toString().length < 2 ? '0' + value : value;
        url += '/' + parameter;
      }
    });
    window.location.href = url;
    return false;
  });

});