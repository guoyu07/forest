$(document).ready(function(){

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

});    