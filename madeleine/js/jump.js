jQuery(document).ready(function ($) {

  // Jump

  var jump = $('#jump');
  var jump_links = $('#jump a');
  var chapters = $('.chapter');

  $(window).scroll(function() {
    var position = $(window).scrollTop();
    var jump_offset = $('.review .entry-text').offset();
    $('#value').text(jump_offset.top);
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
  });

});    