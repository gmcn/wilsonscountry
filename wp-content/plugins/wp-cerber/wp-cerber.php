<?php
/*
	Plugin Name: WP Cerber Security & Antispam
	Plugin URI: https://wpcerber.com
	Description: Protects WordPress against brute force attacks, bots and hackers. Antispam protection with the Cerber antispam engine and reCAPTCHA. Comprehensive control of user and bot activity. Restrict login by IP access lists. Limit login attempts. Know more: <a href="https://wpcerber.com">wpcerber.com</a>.
	Author: Gregory
	Author URI: https://wpcerber.com
	Version: 6.7
	Text Domain: wp-cerber
	Domain Path: /languages
	Network: true

 	Copyright (C) 2015-18 CERBER TECH INC., http://cerber.tech
    Copyright (C) 2015-18 Gregory Markov, https://wpcerber.com

    Licenced under the GNU GPL.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

define( 'CERBER_VER', '6.7' );

function cerber_plugin_file() {
	return __FILE__;
}
function cerber_plug_in() {
	return plugin_basename( __FILE__ );
}
function cerber_plugin_data() {
	return get_plugin_data( __FILE__ );
}
function cerber_plugin_dir_url() {
	return plugin_dir_url( __FILE__ );
}
function cerber_get_plugins_dir() {
	return dirname( __FILE__ , 2 );
}

require_once( dirname( __FILE__ ) . '/cerber-load.php' );
cerber_init();

