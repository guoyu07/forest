jQuery(document).ready(function ($) {

  // Top menu

  var $top_icon = $('#top-icon'),
      $top_menu = $('#top-menu');

  $top_icon.click( function() {
    $top_menu.toggle();
  });

  // Nav menu

  var $nav_icon = $('#nav-icon'),
      $nav_menu = $('#nav-menu');

  $nav_icon.click( function() {
    $nav_menu.toggle();
  });

  // Category menu

  var $category_icon = $('#category strong .icon'),
      $category_menu = $('#category ul');

  $category_icon.click( function() {
    $category_menu.toggle();
    return false;
  });

  // Subnav

  var $nav = $('#nav'),
      $nav_links = $('#nav .cat-item a'),
      $subnav = $('#subnav'),
      $subnav_reel = $('#subnav-reel'),
      hovering_submenu = false,
      hover_timer,
      exit_timer;

  $nav_links.hover(
    function() {
      hovering_submenu = true;
      $subnav.stop().animate({
        height: 241
      }, 250);
    }, function() {
      hovering_submenu = false;
      unhoverSubmenu();
    }
  );

  $subnav.hover(
    function() {
      hovering_submenu = true;
      $subnav.stop().animate({
        height: 241
      }, 250);
    }, function() {
      hovering_submenu = false;
      unhoverSubmenu();
    }
  );

  function activateSubmenu(row) {
    var $row = $(row);
    var index = $nav_links.index($row);
    $subnav_reel.stop().animate({
      left: index * -1020
    }, 500, 'easeOutExpo');
    $row.addClass('maintainHover');
  }

  function deactivateSubmenu(row) {
    var $row = $(row);
    $row.removeClass('maintainHover');
  }

  function unhoverSubmenu() {
    hover_timer = setTimeout(function() {
      if (!hovering_submenu) {
        $subnav.stop().height(0);
        $("a.maintainHover").removeClass("maintainHover");
      }
    }, 100);
  }


  function exitSubmenu() {
    exit_timer = setTimeout(function() {
      if (!hovering_submenu) {
        $("a.maintainHover").removeClass("maintainHover");
      }
    }, 100);
  }

  $nav.menuAim({
      activate: activateSubmenu,
      deactivate: deactivateSubmenu,
      rowSelector: '.cat-item a',
      submenuDirection: 'below',
      tolerance: 0
  });

  // Latest posts widget

  var $latest_lists = $('#latest ul'),
      $latest_dots = $('#latest-dots span');

  $latest_dots.first().addClass('on');

  $latest_dots.click( function() {
    var index = $(this).index();
    var $target = $latest_lists.eq(index);
    $latest_dots.removeClass('on');
    $(this).addClass('on');
    $latest_lists.not($target).hide();
    $target.show();
  });

  // Popular posts widget

  var $popular_bars = $('#popular li em'),
      popular_highest = parseInt($popular_bars.first().data('total'));

  $.each($popular_bars, function() {
    var total = $(this).data('total');
    var relative_width = (100 * total) / popular_highest;
    $(this).stop().animate({
      width: relative_width + '%'
    });
  });

  // Videos widget

  var $videos = $('#videos li'),
      $videos_dots = $('#videos-dots span');

  $videos_dots.first().addClass('on');

  $videos_dots.click( function() {
    var index = $(this).index();
    var $target = $videos.eq(index);
    $videos_dots.removeClass('on');
    $(this).addClass('on');
    $videos.not($target).hide();
    $target.show();
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