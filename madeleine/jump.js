$(document).ready(function(){

  // Jump

  var jump = $('#jump');
  var jump_links = $('#jump a');
  var jump_reference = $('.review .entry-text');
  var jump_offset = jump_reference.offset();
  var chapters = $('.chapter');

  $(window).scroll(function() {
    var position = $(window).scrollTop();
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