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

  // Dots

  var videos = $('#videos li');
  var videos_dots = $('#videos-dots span');
  videos_dots.first().addClass('on');

  videos_dots.hover( function() {
    videos_dots.removeClass('on');
    $(this).addClass('on');
    videos.hide();
    videos.eq($(this).index()).show();
  });

  // Wheel tabs

  var wheels = $('.wheel');
  var wheel_tabs = $('#wheel-tabs ul a');
  var wheel_link = $('#wheel-link');
  wheel_tabs.first().addClass('on');

  wheel_tabs.click( function() {
    var index = $(this).parent().index();
    var url = $(this).attr('href');
    var name = $(this).text();
    wheels.hide();
    wheels.eq(index).show();
    wheel_tabs.removeClass();
    $(this).addClass('on');
    wheel_link.attr('href', url);
    wheel_link.find('span').text(name);
    return false;
  });

  // Reviews tabs

  var reviews = $('.reviews-grid .review');
  var reviews_tabs = $('#reviews-tabs ul a');
  var reviews_link = $('#reviews-link');
  reviews_tabs.first().addClass('on');

  reviews_tabs.click( function() {
    var index = $(this).parent().index();
    var url = $(this).attr('href');
    var name = $(this).text();
    if (name == 'All') {
      name = 'Reviews';
    }
    reviews_tabs.removeClass();
    $(this).addClass('on');
    UpdateReviews($(this).parent().data('id'));
    reviews_link.attr('href', url);
    reviews_link.find('span').text(name);
    return false;
  });

  // Popular

  var bars = $('#popular li em');
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

  // Ajax

  function UpdateReviews(id) {
    $('.loading').show();
    $.ajax({
      url: 'http://localhost/forest/wp-admin/admin-ajax.php',
      data: {
        'action': 'madeleine_ajax',
        'fn': 'madeleine_reviews_tabs',
        'id': id
      },
      dataType: 'html',
      success:function(data) {
        $('.loading').hide();
        $('#reviews-result').html(data);
      },
      error: function(errorThrown){
        alert('Oops! An error occured.');
        console.log(errorThrown);
      }
    });
  }

  function FilterReviews(product, brand, rating_min, rating_max, price_min, price_max) {
    $.ajax({
      url: 'http://localhost/forest/wp-admin/admin-ajax.php',
      data: {
        'action': 'madeleine_ajax',
        'fn': 'madeleine_reviews_filter',
        'product': product,
        'brand': brand,
        'rating_min': rating_min,
        'rating_max': rating_max,
        'price_min': price_min,
        'price_max': price_max
      },
      dataType: 'html',
      success:function(data) {
        window.location.href = '';
      },
      error: function(errorThrown){
        alert('Oops! An error occured.');
        console.log(errorThrown);
      }
    });
  }

});    