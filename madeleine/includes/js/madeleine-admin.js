jQuery(document).ready(function($) {

  console.log('Ok');

  // Category colors

  var category_color = $('#madeleine-category-color');
  var color_choices = $('#madeleine-color-choices li');

  function MadeleinePickColor(color) {
    category_color.val(color);
  }

  category_color.wpColorPicker({
    change: function(event, ui) {
      MadeleinePickColor( category_color.wpColorPicker('color') );
    },
    clear: function() {
      MadeleinePickColor('');
    }
  });

  color_choices.click( function() {
    category_color.wpColorPicker('color', $(this).data('color'));
  });

  // Post formats

  var quoteOptions = $('#madeleine-quote'),
      quoteTrigger = $('#post-format-quote'),
      linkOptions = $('#madeleine-link'),
      linkTrigger = $('#post-format-link'),
      videoOptions = $('#madeleine-video'),
      videoTrigger = $('#post-format-video'),
      group = jQuery('#post-formats-select input');

  MadeleineHideAll(null); 
  
  group.change( function() {
    MadeleineHideAll(null);   
    if($(this).val() == 'quote') {
      quoteOptions.css('display', 'block');   
    } else if($(this).val() == 'link') {
      linkOptions.css('display', 'block');
    } else if($(this).val() == 'video') {
      videoOptions.css('display', 'block');
    }
  });
  
  if(quoteTrigger.is(':checked'))
    quoteOptions.css('display', 'block');

  if(linkTrigger.is(':checked'))
    linkOptions.css('display', 'block');
    
  if(videoTrigger.is(':checked'))
    videoOptions.css('display', 'block');
    
  function MadeleineHideAll() {
    videoOptions.css('display', 'none');
    quoteOptions.css('display', 'none');
    linkOptions.css('display', 'none');
  }
  
});