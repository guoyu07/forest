jQuery(document).ready(function ($) {

  // Pinterest

  var pinterest = $('.pinterest');
  var pins = $('.pinterest .post');
  var viewport = window.innerWidth;

  function Grid(space, gutter) {
    pins.css('position','absolute');
    var columns = 3;
    var column = (space / columns);
    var pins_count = pins.size();

    var grid = new Array(columns);
    $.each(grid, function(j) {
      grid[j] = 0;
    });
    
    $.each(pins, function() {
      var lowest = Math.min.apply(null, grid);
      var lowest_index = grid.indexOf(lowest);
      var height = $(this).outerHeight();
      grid[lowest_index] += height;
      var x = ( lowest_index * column );
      var y = lowest;
      $(this).css('padding', gutter);
      if (lowest_index == 0) {
        $(this).css('border-left', 'none');
        $(this).css('padding-left', 0);
        x = gutter;
      } else if (lowest_index == 2) {
        $(this).css('padding-right', 0);
      }
      $(this).css('left', x);
      $(this).css('top', y);
    });
    
    var biggest = Math.max.apply(null, grid);
    pinterest.css('height', biggest + 'px');
  }
  
  function Reset() {
    // pins.css('padding-left', 0);
    pins.css('position', 'static');
  }
  
  function Display(size) {
    if (size > 1020) {
      Grid(1020, 20);
    } else if (size > 960) {
      Grid(960, 15);
    } else if (size > 900) {
      Grid(900, 10);
    } else if (size > 750) {
      Grid(750, 10);
    } else {
      Reset();
    }
  }

  // Grid(1020, 20);
  // Grid(960, 15);
  
  Display(window.innerWidth);
  // alert(viewport.width);
  
  var resize_timer;
  $(window).resize(function() {
    var new_viewport = window.innerWidth;
    clearTimeout(resize_timer);
    resize_timer = setTimeout(Display(new_viewport), 100);
  });

});    