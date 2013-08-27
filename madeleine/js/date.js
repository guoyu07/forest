jQuery(document).ready(function ($) {

  // Date archive

  var y = $('#date-archive').data('year');
  var m = $('#date-archive').data('month');
  var d = $('#date-archive').data('day');

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

  $('.years, .months, .days').hover(
    function() {
      $(this).find('li').show();
    },
    function() {
      $(this).find('li').not('.on, .select').hide();
      if ($(this).hasClass('active')) {
        $(this).find('.select').hide();
      }
    }
  );

});    