<?php
/*
 * Prevent Duplicated Post Titles
 */
if ( is_admin() ) {
    add_action( 'save_post', 'tft_save_post', 11, 2 );
    add_action( 'admin_head-post.php', 'tft_check_for_notice' );
}

function tft_save_post( $post_id, $post ) {

    if ( ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE )
        or ! current_user_can( 'edit_post', $post_id )
        or wp_is_post_revision( $post )
        or empty( $post )
    ) {
        return;
    }

    if ( 'post' == $post->post_type && in_array( $post->post_status, array( 'publish', 'future' ) ) ) {
        // Fix problem with dash
        $title = str_replace( '–', '-', $post->post_title );

        $args = array(
            'post__not_in' => array( $post_id ),
            'post_type' => 'post',
            'post_status' => array( 'publish', 'future' ),
            'title' => $title,
        );
        
        $posts = get_posts( $args );
        if( count( $posts ) > 0 ) {
            // Unhook this function so it doesn't loop infinitely
            remove_action( 'save_post', 'tft_save_post' );
    
            // Change post status to draft
            wp_update_post( array( 'ID' => $post_id, 'post_status' => 'draft' ) );

            // Display error msg
            add_filter('redirect_post_location','tft_add_error_query_var');
            
            // re-hook this function
            add_action( 'save_post', 'tft_save_post', 11, 2 );
        }
    }
}

function tft_add_error_query_var( $location ) {
    remove_filter( 'redirect_post_location','tft_add_error_query_var' );
    return add_query_arg( 'duplicated_title', 'true', $location );
}

function tft_check_for_notice() {
    if( isset( $_GET['duplicated_title'] ) ) {
        add_action( 'admin_notices', 'tft_display_error_message' );
    }
}

function tft_display_error_message() {
    echo '<div class="error fade" style="background: #f23535;color: white;padding: 11px 15px;">Error - Título duplicado. Se ha detectado que este título de nota ya existe en sistema, favor de cambiarlo para poder continuar.</div>';
    remove_action( 'admin_notices', 'tft_display_error_message' );
}
