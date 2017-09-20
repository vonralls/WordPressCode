<?php get_header();

?>

<div>

  <?php
  //this will return the attachment id
  function pippin_get_image_id($image_url) {
      global $wpdb;
      $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
          return $attachment[0];
    }
    //this was a test
    //$removetext = "Hello my name is Von";
    //set the arguments for our loop
    $args = array(
    'posts_per_page'   => -1,
    'post_type'        => 'post',
      );

    //execute
    $query = new WP_Query( $args );

    $posts = $query->posts;
        foreach ( $posts as $post ){
          //echo "Post ID: " . $post->ID . "<br>";
          $postid = $post->ID;

          //echo $post->post_title . "<br>";

          // get the full content of the post
          $content = $post->post_content;
          // find the first <img> tag and save to variable $imageUrl
          preg_match('/< *img[^>]*src *= *["\']?([^"\']*)/i', $content, $imageUrl);
          // [1] is just the image URL.
          $imageUrl = $imageUrl[1];
          echo "The image url: " . $imageUrl . "<br>";
          $removetext = '<img src="'.$imageUrl.'" alt=""/>';
          //echo "Looking for: " . $removetext . "<br>";
          $newcontent = str_replace($removetext, ' ', $content, $count);
          // Update post the post
          if ($count > 0 ) {
                  echo "Found url match in post: " . $postid . "<br>";
                  $my_post = array(
                      'ID'           => $postid,
                      'post_content' => $newcontent,
                  );

                // Update the post into the database
                echo "Removed url, now updating the post! <br>";
                  wp_update_post( $my_post );

              }
              //echo "Imageurl: " . $imageUrl . "<br>";
            if ($imageUrl){
              $imageId = pippin_get_image_id($imageUrl);
              echo "Setting post thumbnail for attachment id: " . $imageId . "<br>";
              set_post_thumbnail($postid, $imageId);
            }
      
    } //end foreach







   ?>



</div>


<?php get_footer(); ?>
