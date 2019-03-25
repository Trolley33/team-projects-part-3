<?php
add_shortcode( 'all-tickets', 'wpas_sc_all_tickets' );
/**
 * Registration page shortcode.
 */
function wpas_sc_all_tickets() {

	global $wpas_tickets, $post;

	$wpas_tickets = wpas_get_all_tickets_for_shortcode();

	/* Get the ticket content */
	ob_start();

	/**
	 * wpas_frontend_plugin_page_top is executed at the top
	 * of every plugin page on the front end.
	 */
	do_action( 'wpas_frontend_plugin_page_top', $post->ID, $post );

	/**
	 * wpas_before_tickets_list hook
	 */
	do_action( 'wpas_before_tickets_list' );

    /**
     * Get the custom template.
     */
    wpas_get_template( 'list' );

	/**
	 * wpas_after_tickets_list hook
	 */
	do_action( 'wpas_after_tickets_list' );

	/**
	 * Finally get the buffer content and return.
	 * 
	 * @var string
	 */
	$content = ob_get_clean();

	return $content;

}
/**
 * Get the list of tickets that should be shown in the [all-tickets] shortcode.
 *
 * @since 4.4.0
 *
 * @param none
 * 
 * @return array post array of tickets found
 */
function wpas_get_all_tickets_for_shortcode() {
	
	global $current_user, $post;

	/**
	 * For some reason when the user ID is set to 0
	 * the query returns posts whose author has ID 1.
	 * In order to avoid that (for non logged users)
	 * we set the user ID to -1 if it is 0.
	 * 
	 * @var integer
	 */
	$author = ( 0 !== $current_user->ID ) ? $current_user->ID : -1;
	
	$args = array(
	    'author'                 => 'any',
		'post_type'              => 'ticket',
		'post_status'            => 'any',
		'order'                  => 'DESC',
		'orderby'                => 'date',
		'posts_per_page'         => - 1,
		'no_found_rows'          => false,
		'cache_results'          => false,
		'update_post_term_cache' => false,
		'update_post_meta_cache' => false,
	) ;

//	/* Maybe only show open tickets */
//	if ( true === boolval( wpas_get_option( 'hide_closed_fe', false) ) ) {
//		$args_meta = array(
//			'meta_query' => array(
//				array(
//					'key'     => '_wpas_status',
//					'value'   => 'closed',
//					'compare' => '!=',
//				),
//			),
//		) ;
//
//		$args = array_merge($args, $args_meta);
//	}
	
	$args = apply_filters( 'wpas_tickets_shortcode_query_args', $args );

	$wpas_tickets_found = new WP_Query( $args );	

	return $wpas_tickets_found ;
	
}