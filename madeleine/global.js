$(document).ready(function(){

  // Popular widget

  var popular_bars = $('#popular li em');
  var popular_highest = parseInt(popular_bars.first().data('total'));

  $.each(popular_bars, function() {
    var total = $(this).data('total');
    var relative_width = (250 * total) / popular_highest;
    $(this).stop().animate({
      width: relative_width
    });
  });

  // Videos widget

  var videos = $('#videos li');
  var videos_dots = $('#videos-dots span');
  videos_dots.first().addClass('on');

  videos_dots.hover( function() {
    videos_dots.removeClass('on');
    $(this).addClass('on');
    videos.hide();
    videos.eq($(this).index()).show();
  });

});    