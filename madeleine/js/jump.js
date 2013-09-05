jQuery(document).ready(function ($) {

  // Jump

  var jump = $('#jump');
  var jump_links = $('#jump a');
  var chapters = $('.chapter');

  function JumpReset() {
    jump.css('position', 'absolute');
    $('.review .entry-text').css('padding-top', jump.height() + 20);
  }

  $(window).scroll(function() {
    var position = $(window).scrollTop();
    var jump_offset = $('.review .entry-text').offset();
    var viewport = window.innerWidth;
    // $('#value').text(jump_offset.top);
    if (viewport > 680) {
      $('.review .entry-text').css('padding-top', 6);
      if (position > jump_offset.top) {
        jump.css('position', 'fixed');
      } else {
        jump.css('position', 'absolute');
      }
      chapters.each( function(index) {
        var chapter_offset = $(this).offset();
        if ((position + 20) > chapter_offset.top) {
          jump_links.removeClass();
          jump_links.eq(index).addClass('on');
        }
      });
    } else {
      JumpReset()
    }
  });
  
  var jump_resize_timer;
  $(window).resize(function() {
    var new_viewport = window.innerWidth;
    clearTimeout(jump_resize_timer);
    jump_resize_timer = setTimeout(function() {
      if (new_viewport > 680) {
        $('.review .entry-text').css('padding-top', 6);
      } else {
        JumpReset();
      }
    }, 100);
  });

});    