jQuery(document).ready(function ($) {

  // Date archive

  var y = $('#date-archive').data('year');
  var m = $('#date-archive').data('month');
  var d = $('#date-archive').data('day');
  var date_lists = $('.years, .months, .days');
  var date_selects = $('#date-archive ul .select');
  var open = false;

  var year = $('.year[data-value*="' + y + '"]');
  year.addClass('on');
  var months = $('.months[data-year*="' + y + '"]');
  months.show();
  
  if (m) {
    months.addClass('active');
    var month = $('.month[data-value="' + m + '"]');
    month.addClass('on');
    var days = $('.days[data-month="' + m + '"]');
    days.show();
    if (d) {
      days.addClass('active');
      var day = $('.day[data-value="' + d + '"]');
      day.addClass('on');
    }
  }

  // date_lists.click( function() {
  //   $(this).siblings().find('li').not('.on').hide();
  //   open = !open;
  //   if (open) {
  //     $(this).addClass('open');
  //     $(this).find('li').show();
  //   } else {
  //     $(this).removeClass('open');
  //     $(this).find('li').not('.on, .select').hide();
  //     if ($(this).hasClass('active')) {
  //       $(this).find('.select').hide();
  //     }
  //   }
  //   return false;
  // });

  date_lists.hover(
    function() {
      $(this).addClass('open');
      $(this).find('li').show();
    },
    function() {
      $(this).removeClass('open');
      $(this).find('li').not('.on, .select').hide();
      if ($(this).hasClass('active')) {
        $(this).find('.select').hide();
      }
    }
  );

  date_selects.click( function() {
    $(this).parent().removeClass('open');
    $(this).siblings().not('.on').hide();
    $(this).hide();
  });

});    