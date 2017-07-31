<?php
/*
 * Plugin Name: La quiniela 
 * Plugin URI:  https://wordpress.org/plugins/#
 * Description: 
 * Author:      Francisco Israel Fernandez
 * Author URI:  
 * Version:     0.1
 * Text Domain: quini
 * Domain Path: /languages/
 * License:     GPL v2 or later
 */

/**
 * quini stores games, results, game dates, bet dates and prices.
 *
 * LICENSE
 * This file is part of quini.
 *
 * WP Crontrol is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License
 * as published by the Free Software Foundation; either version 2
 * of the License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
 *
 * @package    WP Crontrol
 * @author     Edward Dale <scompt@scompt.com> & John Blackbourn <john@johnblackbourn.com>
 * @copyright  Copyright 2008 Edward Dale, 2012-2017 John Blackbourn
 * @license    http://www.gnu.org/licenses/gpl.txt GPL 2.0
 * @link       https://wordpress.org/plugins/wp-crontrol/
 * @since      0.2
 */

defined( 'ABSPATH' ) or die();

class CrontrolQuini {

	/**
	 * Hook onto all of the actions and filters needed by the plugin.
	 */
	protected function __construct() {

		add_filter('the_content', 'hello_talcual');
	}

	// This just echoes the chosen line, we'll position it later
function hello_talcual($input) {
	echo $input."<p Hello this is a try</p>";
}


	/* Get any loteriasyapuestas body
	 * Argument uri to exact page
	*/
	protected function retrive_loteriasyapuestas_body($uri = "/es/la-quiniela"){
		$baseurl = "http://www.loteriasyapuestas.es";
		$response = wp_remote_get( 'http://www.loteriasyapuestas.es'.$uri );
		$body = wp_remote_retrieve_body($response);
	}

	/**
	 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
	 * are defined within the context of the function.
	 *
	 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
	 *          of the post if successful.
	 */
	function programmatically_create_post() {

		// Initialize the page ID to -1. This indicates no action has been taken.
		$post_id = -1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug = 'post-quini';
		$title = 'Quini Post';

		// If the page doesn't already exist, then create it
		if( null == get_page_by_title( $title ) ) {

			// Set the post ID so that we know the post was created successfully
			$post_id = wp_insert_post(
				array(
					'comment_status'	=>	'open',
					'ping_status'		=>	'open',
					'post_author'		=>	$author_id,
					'post_name'			=>	$slug,
					'post_title'		=>	$title,
					'post_status'		=>	'publish',
					'post_type'			=>	'post',
					'date'				=>	'Jornada prueba'
				)
			);

		// Otherwise, we'll stop
		} else {

	    		// Arbitrarily use -2 to indicate that the page with the title already exists
	    		$post_id = -2;

		} // end if

	} // end programmatically_create_post

	public static function init() {

		static $instance = null;

		if ( ! $instance ) {
			$instance = new CrontrolQuini;
		}

		return $instance;

	}
}
// Get this show on the road
CrontrolQuini::init();