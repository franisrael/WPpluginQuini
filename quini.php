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

//		$plugin_file = plugin_basename( __FILE__ );

		define( 'CD_PLUGIN_PATH', plugin_dir_path( __FILE__ ) );

		require_once (CD_PLUGIN_PATH.'class.lya.php');

		$lya = new Lya();


	}


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

