jQuery(document).ready(function ($) {

  // Nav menu

  var nav_icon = $('#nav-menu');
  var nav_menu = $('#nav');

  nav_icon.click( function() {
    nav_menu.toggle();
  });

  // Category menu

  var category_icon = $('#category strong .icon');
  var category_menu = $('#category ul');

  category_icon.click( function() {
    category_menu.toggle();
    return false;
  });

  // Reviews menu

  var reviews_icon = $('#reviews .heading .icon');
  var reviews_menu = $('#reviews #menu');

  reviews_icon.click( function() {
    reviews_menu.toggle();
    return false;
  });

  // Latest posts widget

  var latest_lists = $('#latest ul');
  var latest_dots = $('#latest-dots span');
  latest_dots.first().addClass('on');

  latest_dots.click( function() {
    latest_dots.removeClass('on');
    $(this).addClass('on');
    latest_lists.hide();
    latest_lists.eq($(this).index()).show();
  });

  // Popular posts widget
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

  videos_dots.click( function() {
    videos_dots.removeClass('on');
    $(this).addClass('on');
    videos.hide();
    videos.eq($(this).index()).show();
  });

});    