jQuery(document).ready(function ($) {

  // Defaults

  var maximum_rating = $('#reviews #menu').data('maximum-rating');
  var maximum_price = $('#reviews #menu').data('maximum-price');

  // Functions

  function URLToArray(query) {
    var request = {};
    if (query != '') {
      var pairs = query.substring(query.indexOf('?') + 1).split('&');
      for (var i = 0; i < pairs.length; i++) {
        var pair = pairs[i].split('=');
        request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
      }
    }
    return request;
  }

  function ArrayToURL(array) {
    var pairs = [];
    var query = '';
    for (var key in array)
      if (array.hasOwnProperty(key))
        if (array[key] != 'undefined')
          pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(array[key]));
    if (pairs.length > 0)
      query = '?' + pairs.join('&')
    return query
  }

  function RatingValue(a, b) {
    var a_range = a * 10 / maximum_rating;
    var b_range = b * 10 / maximum_rating;
    return '<span class="rating rating-' + a_range + '">' + a + '</span> - <span class="rating rating-' + b_range + '">' + b + '</span>';
  }

  function LoadReviews(url) {
    console.log(url);
    $('.loading').show();
    $('#reviews-result').load(url + ' #reviews-result', function() {
      history.replaceState(null, null, url);
      $('.loading').hide();
      $('html, body').animate({
          scrollTop: $('#reviews-result').offset().top
      }, 250);
    });
  }

  // Initialization

  var home_url = $('body').data('url');
  var parameters = URLToArray(window.location.search);
  var reviews_title = $('#reviews-title');
  var reviews_filter = $('#reviews-filter');
  var reviews_button = $('#reviews-filter button');
  var reviews_icon = $('#menu-icon');
  var reviews_menu = $('#reviews #menu');
  var products = $('#products a');
  var brands = $('#brands a');
  var rating = $('#rating');
  var rating_value = $('#rating-value');
  var price = $('#price');
  var price_value = $('#price-value');

  var rating_min = (parameters['rating_min'] != undefined) ? parameters['rating_min'] : 0;
  var rating_max = (parameters['rating_max'] != undefined) ? parameters['rating_max'] : maximum_rating;
  var price_min = (parameters['price_min'] != undefined) ? parameters['price_min'] : 0;
  var price_max = (parameters['price_max'] != undefined) ? parameters['price_max'] : maximum_price;

  if ( parameters['product_id'] != '' )
    $('#products li[data-id="' + parameters['product_id'] + '"').addClass('current-cat');

  if ( parameters['brand_id'] != '' )
    $('#brands li[data-id="' + parameters['brand_id'] + '"').addClass('current-cat');

  if (window.location.pathname.indexOf('/product/') != -1) {
    var id = $('#products .current-cat').data('id');
    parameters['product_id'] = id;
  } else if (window.location.pathname.indexOf('/brand/') != -1) {
    var id = $('#brands .current-cat').data('id');
    parameters['brand_id'] = id;
  }
  console.log(parameters);

  // Events

  reviews_icon.click( function() {
    reviews_menu.toggle();
    return false;
  });

  products.click( function() {
    var id = $(this).parent().data('id');
    products.parent().removeClass('current-cat');
    if ( parameters['product_id'] == id ) {
      delete parameters['product_id'];
    } else {
      $(this).parent().addClass('current-cat');
      parameters['product_id'] = id;
    }
    return false;
  });

  brands.click( function() {
    var id = $(this).parent().data('id');
    brands.parent().removeClass('current-cat');
    if ( parameters['brand_id'] == id ) {
      delete parameters['brand_id'];
    } else {
      $(this).parent().addClass('current-cat');
      parameters['brand_id'] = id;
    }
    return false;
  });

  reviews_button.click( function() {
    var url = home_url + '/reviews' + ArrayToURL(parameters);
    LoadReviews(url);
    return false;
  });

  rating.slider({
    range: true,
    min: 0,
    max: rating_max,
    step: maximum_rating / 10,
    values: [ rating_min, rating_max ],
    slide: function(event, ui) {
      rating_value.html(RatingValue(ui.values[0], ui.values[1]));
      parameters['rating_min'] = ui.values[0];
      parameters['rating_max'] = ui.values[1];
    }
  });
  rating_value.html(RatingValue(rating.slider('values', 0), rating.slider('values', 1)));

  price.slider({
    range: true,
    min: 0,
    max: price_max,
    step: 50,
    values: [ price_min, price_max ],
    slide: function(event, ui) {
      price_value.text('$' + ui.values[0] + ' - $' + ui.values[1]);
      parameters['price_min'] = ui.values[0];
      parameters['price_max'] = ui.values[1];
    }
  });
  price_value.text('$' + price.slider('values', 0) + ' - $' + price.slider( 'values', 1));

  // Scroll

  $(window).scroll(function() {
    var menu_bottom = $('#menu').offset().top + $('#menu').innerHeight();
    var view_bottom = $(window).scrollTop() + $(window).height();
    var viewport = window.innerWidth;
    if (viewport > 900) {
      if ((menu_bottom <= view_bottom)) {
        reviews_filter.css('position', 'absolute');
      } else {
        reviews_filter.css('position', 'fixed');
      }
    } else {
      reviews_filter.css('position', 'static');
    }
  });

  var reviews_resize_timer;
  $(window).resize(function() {
    var new_viewport = window.innerWidth;
    clearTimeout(reviews_resize_timer);
    reviews_resize_timer = setTimeout(function() {
      if (new_viewport > 680) {
        reviews_menu.show();
        if (new_viewport > 900) {
          reviews_filter.css('position', 'absolute');
        }
      } else {
        reviews_filter.css('position', 'static');
      }
    }, 100);
  });

});    