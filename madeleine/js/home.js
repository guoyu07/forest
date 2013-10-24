jQuery(document).ready(function ($) {

  // Focus Highlight

  var $focus_bigs = $('.focus-big');
  var $focus_smalls = $('.focus-small');
  var $focus_small_links = $('.focus-small .entry-permalink');
  var current_highlight = 0;
  var highlighting = false;

  function HighlightCycle(target) {
    highlighting = true;
    if (target == null) {
      target = (current_highlight + 1) > 4 ? 0 : current_highlight + 1;
    }
    var position = $focus_smalls.eq(target).css('left');
    $focus_bigs.eq(current_highlight).fadeOut();
    $focus_bigs.eq(target).fadeIn();
    $focus_smalls.eq(current_highlight).css('left', position).fadeIn();
    $focus_smalls.eq(target).fadeOut();
    current_highlight = target;
    setTimeout( function() {
      highlighting = false;
    }, 500);
  }

  var highlight_timer = setInterval( function() {
    if (!highlighting) {
      HighlightCycle();
    }
  }, 5000);

  $('#level-focus-highlight #focus').hover(
    function() {
      window.clearInterval(highlight_timer);
    },
    function() {
      highlight_timer = setInterval( function() {
        if (!highlighting) {
          HighlightCycle();
        }
      }, 5000);
    }
  );

  $focus_small_links.click( function() {
    var index = $focus_small_links.index(this);
    if (!highlighting) {
      HighlightCycle(index);
    }
    return false;
  });

  // Focus Carousel

  var $focus_carousel = $('.focus-carousel');
  var $focus_carousel_items = $('.focus-carousel .focus');
  var $focus_carousel_links = $('.focus-carousel .focus .entry-permalink');
  var letters = ['alpha', 'beta', 'gamma', 'delta', 'epsilon'];
  var positions = [0, 1, 2, 3, 4];
  var current_focus_carousel = 0;
  var carouseling = false;

  function CarouselCycle(direction) {
    carouseling = true;
    $focus_carousel_items.each( function(i) {
      var current = positions[i];
      if (direction == 'right') {
        var target = (current + 1) > 4 ? 0 : current + 1;
      } else {
        var target = (current - 1) < 0 ? 4 : current - 1;
      }
      $(this).removeClass('focus-' + letters[current]);
      $(this).addClass('focus-' + letters[target]);
      positions[i] = target;
    });
    setTimeout( function() {
      carouseling = false;
    }, 1000);
  }

  var carousel_timer = setInterval(CarouselCycle, 5000);

  $('#level-focus-carousel').hover(
    function() {
      window.clearInterval(carousel_timer);
    },
    function() {
      if (!carouseling) {
        $focus_carousel.removeClass('focus-direction-right').addClass('focus-direction-left');
        carousel_timer = setInterval(CarouselCycle, 5000);
      }
    }
  );

  $focus_carousel_links.click( function() {
    if (carouseling) {
      return false;
    } else {
      var index = $focus_carousel_links.index(this);
      if (positions[index] == 2) {
        $focus_carousel.removeClass('focus-direction-right').addClass('focus-direction-left');
        CarouselCycle('left');
        return false;
      } else if (positions[index] == 4) {
        $focus_carousel.removeClass('focus-direction-left').addClass('focus-direction-right');;
        CarouselCycle('right');
        return false;
      }
    }
  });

  // Wheel tabs

  var wheels_display = $('.wheels').data('display');

  if (wheels_display == 'tabs') {
    var $wheels = $('.wheel');
    var $wheels_tabs = $('#wheel-tabs .tabs ul a');
    var $wheels_link = $('#wheel-link');
    var $first_tab = $wheels_tabs.first();

    function UpdateWheelLink(url, name) {
      $wheels_link.attr('href', url);
      $wheels_link.find('span').text(name);
    }

    $wheels.hide();
    $wheels.first().show();
    $first_tab.addClass('on');
    UpdateWheelLink($first_tab.attr('href'), $first_tab.text());

    $wheels_tabs.click( function() {
      var index = $(this).parent().index();
      var url = $(this).attr('href');
      var name = $(this).text();
      $wheels.hide();
      $wheels.eq(index).show();
      $wheels_tabs.removeClass();
      $(this).addClass('on');
      UpdateWheelLink(url, name);
      return false;
    });
  }

  // Reviews tabs

  var $reviews_tabs = $('#reviews-tabs ul a');
  var $reviews_link = $('#reviews-link');
  var reviews_loading = false;
  $reviews_tabs.first().addClass('on');

  function UpdateReviews(id) {
    $('#reviews-loading').show();
    reviews_loading = true;
    $.ajax({
      url: 'http://localhost/forest/wp-admin/admin-ajax.php',
      data: {
        'action': 'madeleine_ajax',
        'fn': 'madeleine_reviews_tabs',
        'id': id
      },
      dataType: 'html',
      success:function(data) {
        $('#reviews-loading').hide();
        $('#reviews-result').html(data);
        reviews_loading = false;
      },
      error: function(errorThrown){
        alert('Oops! An error occured.');
        console.log(errorThrown);
      }
    });
  }

  $reviews_tabs.click( function() {
    if (reviews_loading == false) {
      var index = $(this).parent().index();
      var url = $(this).attr('href');
      var name = $(this).text();
      if (name == 'All') {
        name = 'Reviews';
      }
      $reviews_tabs.removeClass();
      $(this).addClass('on');
      UpdateReviews($(this).parent().data('id'));
      $reviews_link.attr('href', url);
      $reviews_link.find('span').text(name);
    }
    return false;
  });

});    