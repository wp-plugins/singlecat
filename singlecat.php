<?php
/*
Plugin Name: SingleCat
Plugin URI: http://www.misternifty.com/singlecat
Description: Display a specified number of posts from a category using shortcodes.
Version: 1.0
Author: Brian Fegter
Author URI: http://www.misternifty.com
*/

//Actions
add_shortcode('singlecat', 'sc_shortcode');

function sc_shortcode($attr) {

    $level = $attr['view'];
    $cat = $attr['cat'];
    $posts = $attr['posts'];
    $view = 0;
    
    if($level != "public" && $level != "private"):
        $level = "private";
    endif;
    
    if($level == "public"):
        $view = 1;
    endif;
    
    if($level == "private"):
        if (is_user_logged_in()):
            $view = 1;
        else:
            $logerror = "<p>You must be logged in to view this page.</p>";
        endif;
    endif;
    
    if ($view == 1):
      global $wpdb;
      $catname = $wpdb->get_row("SELECT * FROM wp_terms WHERE term_id = '".$cat."'"); 
      $temp = $wp_query;
      $wp_query= null;
      $wp_query = new WP_Query();
      $wp_query->query('category_name='.$catname->name.'&showposts='.$posts.'&paged='.$paged);
    
      while ($wp_query->have_posts()) : $wp_query->the_post(); ?>
      
        <div class="post">
       
          <h2><a href="<?php the_permalink(); ?>"><?php the_title(); ?></a></h2>
       
          <small><?php the_time('F jS, Y'); ?></small>
       
          <div class="entry">
            <?php the_content(); ?>
          </div>
      
          <p class="postmetadata">Posted in <?php the_category(', '); ?></p>
        
        </div>
    
      <?php endwhile; ?>
    
      <div class="navigation">
        <div class="alignleft"><?php previous_posts_link('&laquo; Previous') ?></div>
        <div class="alignright"><?php next_posts_link('More &raquo;') ?></div>
      </div>
    
      <?php $wp_query = null; $wp_query = $temp;
    
    else:
        
        echo $logerror;
    
    endif;

}


?>