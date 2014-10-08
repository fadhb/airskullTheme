<?php

// custom excerpt length
function themify_custom_excerpt_length( $length ) {
return 18;
}
add_filter( 'excerpt_length', 'themify_custom_excerpt_length', 999 );

//custom footer content
function theme_footer_end() {
echo '<div class="custom-footer-text">';
echo '<ul class="footernote"><li class="first"><a href="mailto:airskullhomeschool@gmail.com">Mail us</a></li>';
echo '<li>Copyright AirSkull 2014</li>';
echo '<li><a href="#header">Back to top </a></li>';
echo '<li>We use affiliate links see <a href="http://airskull.com/disclaimer/">here</a> for details</li></ul></div>';
}
add_action( 'themify_footer_end', 'theme_footer_end' );

// Call actions and filters in after_setup_theme hook
add_action( 'after_setup_theme', 'custom_themify_parent_theme_setup' );
function custom_themify_parent_theme_setup() {

// add more link to excerpt
function themify_custom_excerpt_more($more) {
global $post;
return ' <a href="'. get_permalink($post->ID) . '" class="more-link">Continue Reading</a>';
}
add_filter('excerpt_more', 'themify_custom_excerpt_more');
}

function airskull_subscribe($content) {
	if ( is_single() ) { 
      	return $content."<div>[jetpack_subscription_form]</div>";
	} else {
	return $content;
	}
}

function airskull_sharing_title($content) {
	if ( is_single() ) {
	return $content."<br><div class='asmeta-sharing-title'>Share This</div>";
	} else {
	return $content;
	}
}
function jptweak_remove_share() {
    remove_filter( 'the_content', 'sharing_display',19 );
    remove_filter( 'the_excerpt', 'sharing_display',19 );
    if ( class_exists( 'Jetpack_Likes' ) ) {
        remove_filter( 'the_content', array( Jetpack_Likes::init(), 'post_likes' ), 30, 1 );
    }
}
 
add_action( 'loop_start', 'jptweak_remove_share' );
add_filter( 'the_content', 'airskull_subscribe' );
add_filter( 'the_content', 'airskull_sharing_title', 8 );
add_filter( 'the_content', 'sharing_display', 9 );

// add the MOTD banner on the slider

function custom_homepage_slider_banner() {
	global $themify;

	if ( '' != $themify->query_post_type ) {
		echo '<a href="#body" id="hm_slider_banner"><i class="motd motd-a"></i><i class="motd motd-b"></i></a>';
	}
}

add_action( 'themify_header_end', 'custom_homepage_slider_banner' );

// handle custom subscription form

add_action( 'init', 'process_air_subscription_form' );
function process_air_subscription_form() {
    if ( isset( $_POST['jp-subscribe-action'] ) && $_POST['jp-subscribe-action'] == 'subscribe' ) {
        $email = $_POST['my-email'];
        $subscribe = Jetpack_Subscriptions::subscribe( $email, 0, false );
       
       if ( is_wp_error( $subscribe ) ) {
            $error = $subscribe->get_error_code();
        } else {
            $error = false;
            foreach ( $subscribe as $response ) {
                if ( is_wp_error( $response ) ) {
                    $error = $response->get_error_code();
                    break;
                }
            }
        }
        
        if ( $error ) {
            switch( $error ) {
                case 'invalid_email':
                    $redirect = add_query_arg( 'subscribe', 'invalid_email' );
                    break;
                case 'active': case 'pending':
                    $redirect = add_query_arg( 'subscribe', 'already' );
                    break;
                default:
                    $redirect = add_query_arg( 'subscribe', 'error' );
                    break;
            }
        } else {
            $redirect =  add_query_arg( 'subscribe', 'success', 'http://airskull.com/thanks-subscribing' ) ;
        }
        
        wp_safe_redirect( $redirect );
            }
}

?>
