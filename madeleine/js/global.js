jQuery(document).ready(function ($) {

  // Top menu

  var $top_icon = $('#top-icon');
  var $top_menu = $('#top-menu');

  $top_icon.click( function() {
    $top_menu.toggle();
  });

  // Nav menu

  var $nav_icon = $('#nav-icon');
  var $nav_menu = $('#nav-menu');

  $nav_icon.click( function() {
    $nav_menu.toggle();
  });

  // Category menu

  var $category_icon = $('#category strong .icon');
  var $category_menu = $('#category ul');

  $category_icon.click( function() {
    $category_menu.toggle();
    return false;
  });

  // Latest posts widget

  var $latest_lists = $('#latest ul');
  var $latest_dots = $('#latest-dots span');
  $latest_dots.first().addClass('on');

  $latest_dots.hover( function() {
    var index = $(this).index();
    var $target = $latest_lists.eq(index);
    $latest_dots.removeClass('on');
    $(this).addClass('on');
    $latest_lists.not($target).hide();
    $target.show();
  });

  // Popular posts widget
  var $popular_bars = $('#popular li em');
  var popular_highest = parseInt($popular_bars.first().data('total'));

  $.each($popular_bars, function() {
    var total = $(this).data('total');
    var relative_width = (100 * total) / popular_highest;
    $(this).stop().animate({
      width: relative_width + '%'
    });
  });

  // Videos widget

  var $videos = $('#videos li');
  var $videos_dots = $('#videos-dots span');
  $videos_dots.first().addClass('on');

  $videos_dots.click( function() {
    $videos_dots.removeClass('on');
    $(this).addClass('on');
    $videos.hide();
    $videos.eq($(this).index()).show();
  });

  var global_resize_timer;
  $(window).resize(function() {
    var new_viewport = window.innerWidth;
    clearTimeout(global_resize_timer);
    global_resize_timer = setTimeout(function() {
      if (new_viewport > 680) {
        $nav_menu.show();
        $category_menu.show();
      }
    }, 100);
  });

});    