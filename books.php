<?php
/*
Plugin Name: Bird Book
Plugin URI:  https://begaak.com
Description: WordPress plugin for birds 
Version:     0.1
Author:     Rashid Iqbal
Author URI:  https://begaak.com/our-team/
License:     GPL2
License URI: https://www.gnu.org/licenses/gpl-2.0.html
Text Domain: birdbook
Domain Path: /languages
*/

require_once __DIR__ . '/includes/class-birdbook-book-search.php';
require_once __DIR__ . '/includes/class-birdbook-admin-pages.php';

new \birdbook\birdbookearch( new \birdbook\BirdBookBook(), new \birdbook\BirdBookGoogleBookApi() );
new \birdbook\BirdBookAdminPages();