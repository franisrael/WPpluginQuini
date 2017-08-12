<?php

//defined( 'ABSPATH' ) or die();

class Lya {

	/**
	 * Hook onto all of the actions and filters needed by the plugin.
	 */
	public function __construct() {

		//$plugin_file = plugin_basename( __FILE__ );

		// and make sure it's called whenever WordPress loads
		add_action('wp', array($this, 'cronstarter_activation'));
		register_deactivation_hook (__FILE__, 'cronstarter_deactivate');
		// Cron interval	
		add_filter( 'cron_schedules', array($this, 'cron_add_five_minute'));
		// hook that function onto our scheduled event:

		add_action ('the_content', array($this, 'get_lya_draws'));
	}

	/*
	 *
	*/
	public function get_lya_draws (){
		$uriQuini = "/es/la-quiniela";
		$body = $this->retrive_loteriasyapuestas_body( $uriQuini );
		$this->quini_draw_regex($body);
	}


	/* Get any loteriasyapuestas body
	 * Argument uri to exact page
	*/
	public function retrive_loteriasyapuestas_body($uri = "/es/la-quiniela"){
		$quiniurl = "http://www.loteriasyapuestas.es";
		$urix = $quiniurl.$uri;
		echo "<a href='$urix' target='_blank'>Go!</a>";
		echo $urix;
		$response = wp_remote_get( $quiniurl.$uri );
		$body = wp_remote_retrieve_body($response);
		return $body;
	}

	/* Get the future draws from lya web
	 * 
	*/
	public function quini_draw_regex($body){

		/* Get the future draws dates
		*/
		$regex = '~tituloRegion">[.|\r|\n|\r\n|\t]*?<h2>[.|\n|\r\n|\t]*?(Jornada.*)<~';
		$hits = preg_match_all($regex, $body, $matches_dates, PREG_PATTERN_ORDER);
		//print_r($matches_dates);
		
		/* Get the url of future draws
		*/
		$regex2 = '~vermas">[.|\n|\r\n|\t]*?<a href="(.*)"~';
		$hits = preg_match_all($regex2, $body, $matches_urls, PREG_PATTERN_ORDER);

		echo date('Y-M-D')."<br>";
		//print_r($matches_dates);
		//print_r($matches_urls);

		for ($i=0; $i < $hits ; $i++) {
			global $wpdb;
			$result = $wpdb->get_results('SELECT COUNT(*) as title FROM wp_posts WHERE post_title="$condition"');
			//print_r($result[0]->title);
			if ($result[0]->title <= 0) {
				
				$draw = trim($matches_dates[1][$i]);
				echo $draw;
				echo "<br>";
				$uri = trim($matches_urls[1][$i]);
				
				
				$uri = preg_replace('~[.|\s|\n|\r\n|\t|\r]*?~', '', $uri); // remove all control chars
				$uri = trim($uri);
				$len = strlen($uri);
				$uri = html_entity_decode($uri);
				echo $len." uri lenght <br>";
				echo "<pre>$uri</pre>";
				var_dump($uri);
				
				echo "<br>";

				$uri2 = "/es/buscador?startDate=13/08/2017&gameId=06&type=search&endDate=13/08/2017";
				$len2 = strlen($uri2);
				echo $len2." uri lenght <br>";
				echo "<pre>$uri2</pre>";
				var_dump($uri2);
				echo "<br>";
				echo "The two string are = ".strcmp($uri, $uri2);
				echo "<br>";

				$body = $this->retrive_loteriasyapuestas_body( $uri );
				$regex3 = '~anteriores"\shref="(.*)"~';
			//	$hits2 = preg_match_all($regex3, $body, $matches_urls2, PREG_PATTERN_ORDER);
			//	print_r($matches_urls2);
				echo "<br>";
			}
		}
	}

	/* Check if exists
	 *
	*/
	public function date_exists($table, $column, $condition){
		global $wpdb;
		$result = $wpdb->get_results('SELECT COUNT(1) FROM table 1 WHERE some_condition');
	}


	// here's the function we'd like to call with our cron job
	function my_repeat_function() {
		echo $input."<p> Hello this is a try</p>";	
		// do here what needs to be done automatically as per your schedule
		// in this example we're sending an email
		//add_filter( 'the_content', array($this, 'hello_talcual' ) );
		//add_filter( 'the_content', 'hello_talcual' );
	}
	// create a scheduled event (if it does not exist already)
	public function cronstarter_activation() {
		if( !wp_next_scheduled( 'quinicronjob' ) ) {  
		   wp_schedule_event( time(), 'everyfiveminute', 'quinicronjob' );  
		}
	}
	// unschedule event upon plugin deactivation
	public function cronstarter_deactivate() {	
		// find out when the last event was scheduled
		$timestamp = wp_next_scheduled ('quinicronjob');
		// unschedule previous event if any
		wp_unschedule_event ($timestamp, 'quinicronjob');
	}
	// add custom interval
	public function cron_add_five_minute( $schedules ) {
		// Adds once every minute to the existing schedules.
	    $schedules['everyfiveminute'] = array(
		    'interval' => 300,
		    'display' => __( 'Once Every 5 Minute' )
	    );
	    return $schedules;
	}

	/**
	 * A function used to programmatically create a post in WordPress. The slug, author ID, and title
	 * are defined within the context of the function.
	 *
	 * @returns -1 if the post was never created, -2 if a post with the same title exists, or the ID
	 *          of the post if successful.
	 */
	public function create_quini_draw($type, $draw) {

		// Initialize the page ID to -1. This indicates no action has been taken.
		$post_id = -1;

		// Setup the author, slug, and title for the post
		$author_id = 1;
		$slug = 'post-quinitry';
		$title = 'Quini Posttry';

		// Setup the varibles
		$title = $draw[0];
		$slug = $draw[1];
		$bote = $draw[2];
		$games = $draw[3];
		$gameDateResult = $draw[4];
		$gameTimeResult = $draw[5];
		$create_post($title,$slug, $author_id);
	}

	public function create_post($title, $slug, $author_id){
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
//					'date'				=>	'Jornada prueba'
				)
			);
			add_post_meta($post_id, 'flavor', 'vanilla');

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
//CrontrolQuini::init();

