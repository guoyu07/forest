jQuery(document).ready(function($) {

  console.log('Madeleine Admin JS loaded.');

  // File uploader

  var custom_uploader;

  $('#upload-image-button').click(function(e) {

    e.preventDefault();

    // If the uploader object has already been created, reopen the dialog
    if (custom_uploader) {
      custom_uploader.open();
      return;
    }

    // Extend the wp.media object
    custom_uploader = wp.media.frames.file_frame = wp.media({
      title: 'Choose Image',
      button: {
        text: 'Choose Image'
      },
      multiple: false
    });

    // When a file is selected, grab the URL and set it as the text field's value
    custom_uploader.on('select', function() {
      attachment = custom_uploader.state().get('selection').first().toJSON();
      $('#upload-image-url').val(attachment.url);
      $('#upload-image-preview').attr('src', attachment.url);
    });

    // Open the uploader dialog
    custom_uploader.open();

  });

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

  function MadeleinePickColor(element, color) {
    element.val(color);
  }

  category_parent.change( function() {
    MadeleineToggleCategoryColorForm();
  });

  category_color.wpColorPicker({
    change: function(event, ui) {
      MadeleinePickColor(category_color, category_color.wpColorPicker('color'));
    },
    clear: function() {
      MadeleinePickColor(category_color, '');
    }
  });

  color_choices.click( function() {
    category_color.wpColorPicker('color', $(this).data('color'));
  });

  MadeleineToggleCategoryColorForm();

  // Colors settings

  $('.madeleine-color-picker').wpColorPicker({
    change: function(event, ui) {
      MadeleinePickColor($(this), $(this).wpColorPicker('color'));
    },
    clear: function() {
      MadeleinePickColor($(this), '');
    }
  });

  // Font tester

  function MadeleinePreviewFont(element) {
    var preview = $('#madeleine-font-' + element.data('font-select'));
    var font = element.find('option:selected').text();
    WebFont.load({
      google: {
        families: [font]
      }
    });
    preview.css('font-family', font);
  }

  $('.madeleine-font-select').each( function() {
    MadeleinePreviewFont($(this));
  });

  $('.madeleine-font-select').change( function() {
    MadeleinePreviewFont($(this));
  });

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
  
  if (quote_button.is(':checked')) {
    quote_panel.css('display', 'block');
  }

  if (link_button.is(':checked')) {
    link_panel.css('display', 'block');
  }
    
  if (video_button.is(':checked')) {
    video_panel.css('display', 'block');
  }

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