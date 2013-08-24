$(document).ready(function(){

  // Functions

  function URLToArray(url) {
    var request = {};
    var pairs = url.substring(url.indexOf('?') + 1).split('&');
    for (var i = 0; i < pairs.length; i++) {
      var pair = pairs[i].split('=');
      request[decodeURIComponent(pair[0])] = decodeURIComponent(pair[1]);
    }
    return request;
  }

  function ArrayToURL(array) {
    var pairs = [];
    for (var key in array)
      if (array.hasOwnProperty(key))
        if (array[key] != 'undefined')
          pairs.push(encodeURIComponent(key) + '=' + encodeURIComponent(array[key]));
    return pairs.join('&');
  }

  function RatingValue(a, b) {
    return '<span class="rating rating-' + a + '">' + a + '</span> - <span class="rating rating-' + b + '">' + b + '</span>';
  }

  // Initialization

  var parameters = URLToArray(window.location.href);
  var reviews_filter = $('#reviews-filter');
  var products = $('#products a');
  var brands = $('#brands a');
  var rating = $('#rating');
  var rating_value = $('#rating-value');
  var price = $('#price');
  var price_value = $('#price-value');

  var rating_min = (parameters['rating_min'] != undefined) ? parameters['rating_min'] : 0;
  var rating_max = (parameters['rating_max'] != undefined) ? parameters['rating_max'] : 10;
  var price_min = (parameters['price_min'] != undefined) ? parameters['price_min'] : 0;
  var price_max = (parameters['price_max'] != undefined) ? parameters['price_max'] : 2000;

  if ( parameters['product_id'] != '' ) {
    $('#products li[data-id="' + parameters['product_id'] + '"').addClass('current-cat');
  }

  if ( parameters['brand_id'] != '' ) {
    $('#brands li[data-id="' + parameters['brand_id'] + '"').addClass('current-cat');
  }

  // Events

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

  reviews_filter.click( function() {
    var url = ArrayToURL(parameters);
    window.location.href = 'http://localhost/forest/reviews?' + url;
    return false;
  });

  rating.slider({
    range: true,
    min: 0,
    max: 10,
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
    max: 2000,
    step: 50,
    values: [ price_min, price_max ],
    slide: function(event, ui) {
      price_value.text('$' + ui.values[0] + ' - $' + ui.values[1]);
      parameters['price_min'] = ui.values[0];
      parameters['price_max'] = ui.values[1];
    }
  });
  price_value.text('$' + price.slider('values', 0) + ' - $' + price.slider( 'values', 1));

});    