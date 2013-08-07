function madeleine_excerpt( $text ) {
  return str_replace(' [...]', '... <a href="'. get_permalink() . '">&rarr;</a>', $text);
}
add_filter( 'the_excerpt', 'madeleine_excerpt' );


function madeleine_excerpt_length( $length ) {
  global $post;
  if ( has_post_thumbnail( $post->ID ) )
    return 25;
  else
    return 75;
}
add_filter( 'excerpt_length', 'madeleine_excerpt_length' );

function madeleine_post_class( $classes ) {
  $top_category_ID = madeleine_top_category();
  $top_category = get_category( $top_category_ID );
  $classes[] = 'category-' . $top_category->category_nicename;
  return $classes;
}
add_filter( 'post_class', 'madeleine_post_class' );


function madeleine_thumbnail( $size = 'thumbnail' ) {
  if ( has_post_thumbnail() )
    echo '<a href="' . get_permalink() . '" class="entry-thumbnail">' . get_the_post_thumbnail( null, $size ) . '</a>';
}

function madeleine_caption( $val, $attr, $content = null ) {
  extract(shortcode_atts(array(
    'id'      => '',
    'align'   => 'alignnoe',
    'width'   => '',
    'caption' => ''
  ), $attr));
  
  // No caption, no dice... But why width? 
  if ( 1 > (int) $width || empty($caption) )
    return $val;
 
  if ( $id )
    $id = esc_attr( $id );
     
  // Add itemprop="contentURL" to image - Ugly hack
  $content = str_replace( 'height=', 'data-height=', $content );
  $content = str_replace( 'width=', 'data-width=', $content );

  return '<figure id="' . $id . '" class="wp-caption ' . esc_attr($align) . '">' . do_shortcode( $content ) . '<figcaption id="figcaption_'. $id . '" class="wp-caption-text" itemprop="description">' . $caption . '</figcaption></figure>';
}
add_filter( 'img_caption_shortcode', 'madeleine_caption', 10, 3 );


function madeleine_video() {
  global $post;
  $youtube = get_post_meta( $post->ID, 'video_youtube', true );
  $dailymotion = get_post_meta( $post->ID, 'video_dailymotion', true );
  $vimeo = get_post_meta( $post->ID, 'video_vimeo', true );
  if ( $youtube != '' ):
    if ( preg_match('%(?:youtube(?:-nocookie)?\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i', $youtube, $match) ):
      echo '<iframe width="640" height="480" src="//www.youtube.com/embed/' . $match[1] . '" frameborder="0" allowfullscreen></iframe>';
    endif;
  elseif ( $vimeo != '' ):
    if ( preg_match('/\/\/(www\.)?vimeo.com\/(\d+)($|\/)/', $vimeo, $match) ):
      echo '<iframe src="http://player.vimeo.com/video/' . $match[2] . '?title=0&amp;byline=0&amp;portrait=0&amp;badge=0&amp;color=ffffff" width="640" height="360" frameborder="0" webkitAllowFullScreen mozallowfullscreen allowFullScreen></iframe>';
    endif;
  elseif ( $dailymotion != '' ):
    if ( preg_match('/^.+dailymotion.com\/((video|hub)\/([^_]+))?[^#]*(#video=([^_&]+))?/', $dailymotion, $match) ):
      echo '<iframe frameborder="0" width="640" height="360" src="http://www.dailymotion.com/embed/video/' . $match[3] . '"></iframe>';
    endif;
  endif;
}


function madeleine_comments( $comment, $args, $depth ) {
  $GLOBALS['comment'] = $comment;
  switch ( $comment->comment_type ):
    case 'pingback' :
    case 'trackback' :
  ?>
  <li class="pingback">
    <p><?php echo 'Pingback:'; ?> <?php comment_author_link(); ?><?php edit_comment_link( 'Edit', '<span class="comment-edit">', '</span>' ); ?></p>
  <?php
      break;
    default:
  ?>
  <li <?php comment_class(); ?> id="li-comment-<?php comment_ID(); ?>">
    <article id="comment-<?php comment_ID(); ?>" class="comment-article">
      <div class="comment-avatar">
        <?php $avatar_size = 60;
        if ( '0' != $comment->comment_parent )
          $avatar_size = 40;
        echo get_avatar( $comment, $avatar_size ); ?>
      </div>
      <div class="comment-content">
        <div class="comment-info vcard">
          <?php printf( '<span class="comment-author">%s</span>', get_comment_author_link() ) ?>
          <?php printf( '<a href="%1$s" class="comment-date"><time datetime="%2$s">%3$s</time></a>', esc_url( get_comment_link( $comment->comment_ID ) ), get_comment_time( 'c' ), sprintf( '%1$s at %2$s', get_comment_date(), get_comment_time() ) ); ?>
          <?php edit_comment_link( 'Edit' , '<span class="comment-edit">', '</span>' ); ?>
        </div>
        <?php if ( $comment->comment_approved == '0' ) : ?>
          <p class="comment-awaiting-moderation"><?php echo 'Your comment is awaiting moderation.'; ?></p>
        <?php endif; ?>
        <div class="comment-text"><?php comment_text(); ?></div>
      </div>
      <div class="comment-reply">
        <?php comment_reply_link( array_merge( $args, array( 'reply_text' => 'Reply <span>&darr;</span>', 'depth' => $depth, 'max_depth' => $args['max_depth'] ) ) ); ?>
      </div>
    </article>
  <?php
      break;
  endswitch;
}


function madeleine_posted_on() {
  $archive_year  = get_the_time('Y'); 
  $archive_month = get_the_time('m'); 
  $archive_day   = get_the_time('d'); 
  printf( 'by <strong class="entry-author vcard"><a href="%1$s" title="%2$s" rel="author">%3$s</a></strong> on <time class="entry-date" datetime="%4$s"><a href="%5$s" title="%4$s" rel="bookmark">%6$s</a></time>',
    esc_url( get_author_posts_url( get_the_author_meta( 'ID' ) ) ),
    esc_attr( sprintf( 'View all posts by %s', get_the_author() ) ),
    get_the_author(),
    esc_attr( get_the_date( 'c' ) ),
    esc_url( get_day_link( $archive_year, $archive_month, $archive_day ) ),
    get_the_date()
  );
}