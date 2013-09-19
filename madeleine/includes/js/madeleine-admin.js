jQuery(document).ready(function($) {

  console.log('Madeleine Admin JS loaded.');

  // Category colors

  var category_parent = $('#edittag #parent');
  var category_color_form = $('#madeleine-category-color-form');
  var category_color = $('#madeleine-category-color');
  var color_choices = $('#madeleine-color-choices li');

  function MadeleineToggleCategoryColorForm() {
    if (category_parent.val() == -1) {
      category_color_form.show();
    } else {
      category_color_form.hide();
    }
  }

  function MadeleinePickColor(color) {
    category_color.val(color);
  }

  category_parent.change( function() {
    MadeleineToggleCategoryColorForm();
  });

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

  MadeleineToggleCategoryColorForm();

  // Post formats

  var quote_panel = $('#madeleine-quote');
  var quote_button = $('#post-format-quote');
  var link_panel = $('#madeleine-link');
  var link_button = $('#post-format-link');
  var video_panel = $('#madeleine-video');
  var video_button = $('#post-format-video');
  var formats_group = $('#post-formats-select input');
    
  function MadeleineHideAll() {
    video_panel.css('display', 'none');
    quote_panel.css('display', 'none');
    link_panel.css('display', 'none');
  }
  
  formats_group.change( function() {
    MadeleineHideAll(null);   
    if ($(this).val() == 'quote') {
      quote_panel.css('display', 'block');   
    } else if($(this).val() == 'link') {
      link_panel.css('display', 'block');
    } else if($(this).val() == 'video') {
      video_panel.css('display', 'block');
    }
  });
  
  if (quote_button.is(':checked'))
    quote_panel.css('display', 'block');

  if (link_button.is(':checked'))
    link_panel.css('display', 'block');
    
  if (video_button.is(':checked'))
    video_panel.css('display', 'block');

  MadeleineHideAll(null);

  // Popular Posts

  var unschedule_checkbox = $('#madeleine-unschedule');
  var unschedule_message = $('#madeleine-unschedule-message');

  unschedule_checkbox.change(function(){
    if ($(this).is(':checked')) {
      unschedule_message.slideDown();
    } else {
      unschedule_message.slideUp();
    }
  });
  
});