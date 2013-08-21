$(document).ready(function(){

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
    var month = $('.month[data-value*="' + m + '"]');
    month.addClass('on');
    var days = $('.days[data-month*="' + m + '"]');
    days.show();
    if (d) {
      days.addClass('active');
      var day = $('.day[data-value*="' + d + '"]');
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

  // Popular

  var bars = $('.popular li em');
  var totals = new Array();
  var total_highest = parseInt(bars.first().data('total'));

  $.each(bars, function() {
    var total = $(this).data('total');
    var relative_width = (250 * total) / total_highest;
    $(this).stop().animate({
      width: relative_width
    });
    // $(this).css('width', relative_width);
  });

  // Jump

  var jump = $('#jump');
  var jump_links = $('#jump a');
  var jump_reference = $('.review .entry-text');
  var jump_offset = jump_reference.offset();
  var chapters = $('.chapter');

  $(window).scroll(function() {
    var position = $(window).scrollTop();
    if (position > jump_offset.top) {
      jump.css('position', 'fixed');
    } else {
      jump.css('position', 'absolute');
    }
    chapters.each( function(index) {
      var chapter_offset = $(this).offset();
      if ((position + 20) > chapter_offset.top) {
        jump_links.removeClass();
        jump_links.eq(index).addClass('on');
      }
    });
  });

  // Pinterest

  var pinterest = $('.pinterest');
  var pins = $('.pinterest .post');

  function Grid(space) {
    pins.css('position','absolute');
    var columns = 3;
    var column = (space / columns);
    var gutter = 0;
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
      if (lowest_index == 0) {
        $(this).css('border-left', 'none');
        $(this).css('padding-left', 0);
        x = 20;
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
    pins.css('padding-left', 0);
    pins.css('position', 'static');
  }
  
  function Display(size) {
    if (size > 980) {
      pins.css('padding-left', 60);
      Grid(940);
    } else if (size > 860) {
      pins.css('padding-left', 0);
      Grid(820);
    } else {
      Reset();
    }
  }
  
  Grid(1020);

});    