jQuery(document).ready(function ($) {

  // Wheel tabs

  var wheels_display = $('.wheels').data('display');

  if (wheels_display == 'tabs') {
    var wheels = $('.wheel');
    var wheels_tabs = $('#wheel-tabs .tabs ul a');
    var wheels_link = $('#wheel-link');
    var first_tab = wheels_tabs.first();

    function UpdateWheelLink(url, name) {
      wheels_link.attr('href', url);
      wheels_link.find('span').text(name);
    }

    wheels.hide();
    wheels.first().show();
    first_tab.addClass('on');
    UpdateWheelLink(first_tab.attr('href'), first_tab.text());

    wheels_tabs.click( function() {
      var index = $(this).parent().index();
      var url = $(this).attr('href');
      var name = $(this).text();
      wheels.hide();
      wheels.eq(index).show();
      wheels_tabs.removeClass();
      $(this).addClass('on');
      UpdateWheelLink(url, name);
      return false;
    });
  }

  // Reviews tabs

  var reviews = $('.reviews-grid .review');
  var reviews_tabs = $('#reviews-tabs ul a');
  var reviews_link = $('#reviews-link');
  var reviews_loading = false;
  reviews_tabs.first().addClass('on');

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

  reviews_tabs.click( function() {
    if (reviews_loading == false) {
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
    }
    return false;
  });

});    