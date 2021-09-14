<?php
/*
 * This function is used to create a new Tribe Event using a Contact Form 7 in the frontend.
 *
 * Event plugin: https://wordpress.org/plugins/the-events-calendar/
 * Contact Form 7: https://wordpress.org/plugins/contact-form-7/
 *
 */
add_action("wpcf7_before_send_mail", "raci_wpcf7_add_event");
function raci_wpcf7_add_event( &$wpcf7_data )
{
	$submission = WPCF7_Submission::get_instance();

	$posted_data = $submission->get_posted_data();
    $file = $submission->uploaded_files();

    // skip sending the mail
    //$wpcf7_data->skip_mail = true;

	if( isset( $posted_data['form-name'] ) && $posted_data['form-name'] === 'new-event' &&
	    isset( $posted_data['title'] ) && ! empty( $posted_data['title'] ) &&
		isset( $posted_data['description'] ) && ! empty( $posted_data['description'] ) &&
		isset( $posted_data['start-date'] ) && ! empty( $posted_data['start-date'] ) &&
		isset( $posted_data['end-date'] ) && ! empty( $posted_data['end-date'] ) ) {

		// Prepare form data
		$start_date = new DateTime( $posted_data['start-date'] );
		$end_date = new DateTime( $posted_data['end-date'] );

		$description = $posted_data['description'];
		$link = '';
		if ( isset( $posted_data['link'] ) && ! empty( $posted_data['link'] ) ) {
			$description .= '<div class="event-desc-link"><br><br><a href="' . $posted_data['link'] . '" target="_blank"><?php _e( 'Event link', 'raci' ) ?></a></div>';
			$link = $posted_data['link'];
		}

		$organizer_id = '';
		if ( isset( $posted_data['is_raci'] ) && ! empty( $posted_data['is_raci'][0] ) ) {
			$organizer_id = 12345; // Organizer ID
		}

		// Create the event
		if ( function_exists('tribe_create_event') ) {
			$args = array(
				'post_title' => $posted_data['title'],
				'post_content' => $description,
				'post_status' => 'publish',
				'EventStartDate' => $start_date->format('Y-m-d'),
				'EventEndDate' => $end_date->format('Y-m-d'),
				'EventStartHour' => $start_date->format('H'),
				'EventStartMinute' => $start_date->format('i'),
				'EventEndHour' => $end_date->format('H'),
				'EventEndMinute' => $end_date->format('i'),
				'EventURL' => $link,
				'EventOrganizerID' => $organizer_id,
			);
	
			$event_id = tribe_create_event( $args );

			if ( ! is_wp_error( $event_id ) ) {
				// Check if there are an image
				if ( ! empty( $file['picture'] ) ) {
					
					// Prepare image
					$image_url = $file['picture'][0];
					$image = pathinfo( $image_url );
					$image_name = $image['basename'];
					$upload_dir = wp_upload_dir();
					$image_data = file_get_contents($image_url);
					$unique_file_name = wp_unique_filename($upload_dir['path'], $image_name);
					$filename = basename($unique_file_name);

					if ($image != '') {
						// Check folder permission and define file location
						if ( wp_mkdir_p( $upload_dir['path'] ) ) {
							$file = $upload_dir['path'] . '/' . $filename;
						} else {
							$file = $upload_dir['basedir'] . '/' . $filename;
						}
            
						// Create the image file on the server
						file_put_contents( $file, $image_data );
            
						// Check image file type
						$wp_filetype = wp_check_filetype( $filename, null );
            
						// Set attachment data
						$attachment = array(
							'post_mime_type' => $wp_filetype['type'],
							'post_title' => sanitize_file_name( $filename) ,
							'post_content' => '',
							'post_status' => 'inherit',
						);
            
						// Create the attachment
						$attach_id = wp_insert_attachment( $attachment, $file, $event_id );
            
						require_once ABSPATH . 'wp-admin/includes/image.php';
            
						$attach_data = wp_generate_attachment_metadata( $attach_id, $file );
            
						wp_update_attachment_metadata( $attach_id, $attach_data );
            
						$thumbnail = set_post_thumbnail( $event_id, $attach_id );
					}
				}
			}
		}
	}
}
