// All custom JS not relating to theme options goes here

jQuery(document).ready(function($) {

/*----------------------------------------------------------------------------------*/
/*  Display post format meta boxes as needed
/*----------------------------------------------------------------------------------*/

    /* Grab our vars ---------------------------------------------------------------*/
  var quoteOptions = $('#madeleine-quote'),
      quoteTrigger = $('#post-format-quote'),
      linkOptions = $('#madeleine-link'),
      linkTrigger = $('#post-format-link'),
      videoOptions = $('#madeleine-video'),
      videoTrigger = $('#post-format-video'),
      group = jQuery('#post-formats-select input');

    /* Hide and show sections as needed --------------------------------------------*/
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