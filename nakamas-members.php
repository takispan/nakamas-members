<?php
/**
 * Plugin Name:       Nakamas Members
 * Description:       Custom wp members dashboard & functionality.
 * Version:           1.0
 * Requires at least: 5.2
 * Author:            Nakamas
 * License:           GPL v2 or later
 * License URI:       https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       nkms-members

 * {Plugin Name} is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 2 of the License, or
 * any later version.

 * {Plugin Name} is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.

 * You should have received a copy of the GNU General Public License
 * along with {Plugin Name}. If not, see {License URI}.
 */

defined( 'ABSPATH' ) or die( 'No script kiddies please!' );

require_once( plugin_dir_path( __FILE__ ) . 'nkms-class-page-template.php' );
add_action( 'plugins_loaded', array( 'Nakamas_PageTemplater', 'get_instance' ) );
?>
