<?php
 /*
 * Hide slug on new post, edit post and post list for editors
 */
function hide_edit_slug_for_editors() {
    global $pagenow;

    // Make sure we are on the backend
    if ( ! is_admin() ) 
        return false;

    $current_user = wp_get_current_user();
    $is_editor = in_array( 'editor', $current_user->roles ) && ! in_array( 'administrator', $current_user->roles );
    $action = $_GET['action'] ?? '';
    $style = '';

    if ( 'post-new.php' == $pagenow && $is_editor ) {

        // New post (hide temporal permalink and slug metabox)

        $style = '
        #titlediv div.inside {
            display: none !important;
        }
        #slugdiv {
            display: none !important;
        }
        ';
    
    } elseif ( 'post.php' == $pagenow && 'edit' == $action && $is_editor ) {

        // Edit post (hide button Edit and slug metabox)

        $style = '
        #edit-slug-buttons {
            display: none !important;
        }
        #slugdiv {
            display: none !important;
        }';

    } elseif ( 'edit.php' == $pagenow && $is_editor ) {

        // Posts list (hide slug in quick editor)

        $script = '  
        jQuery(document).ready( function($) {
            $(\'span:contains("Slug")\').each(function (i) {
                $(this).parent().remove();
            });
        });';
        wp_register_script( 'hide-slug-quick-edit', false );
        wp_enqueue_script( 'hide-slug-quick-edit' );
        wp_add_inline_script( 'hide-slug-quick-edit', $script );
    }

    if ( ! empty( $style ) ) {
        wp_register_style( 'hide-slug', false );
        wp_enqueue_style( 'hide-slug' );
        wp_add_inline_style( 'hide-slug', $style );
    }
}
add_action( 'admin_init', 'hide_edit_slug_for_editors', 20 );
