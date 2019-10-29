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

new \birdbook\birdbookearch(new \birdbook\BirdBookBook(), new \birdbook\BirdBookGoogleBookApi());
new \birdbook\BirdBookAdminPages();
function loadStylesandScripts()
{

    wp_enqueue_style('gallery-css',  plugin_dir_url(__FILE__) . 'css/book.css');
    wp_enqueue_style('semantic-ui', 'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.css');
    wp_enqueue_script('semantic-ui-script', 'https://cdnjs.cloudflare.com/ajax/libs/semantic-ui/2.4.1/semantic.min.js', ['jquery'], '', true);
}
add_action('wp_enqueue_scripts', 'loadStylesandScripts');

function showGallery($atts)
{
    // return  $atts['g'];

    $galleryList  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];
    $selectedGallery = '';
    if (!empty($galleryList)) {

        foreach ($galleryList as $gallery) {
            $images = explode(",", $gallery['images']);
            $g = '';
            if(isset($atts['g']))
             $g = $atts['g'];
            if ($gallery['id'] ==$g || $gallery['location'] == $atts['l'] || $gallery['location'] == $atts['l'] && $gallery['filter'] == $atts['f']) {
                $selectedGallery .=  ' <div class="card">
            <div>
         
        
                <form class="ui form" method="post">
                    <div class="right floated author">
                        <input type="hidden" name="del" value="' . $gallery['id'] . '" />
                        <button class="circular ui icon button  right floated " type="submit">
                            <i class="icon x"></i>
                        </button>
                    </div>
                </form>
        
            </div> 
        
            <div class="ui small images segment">
        
                ' . getImages($images) . ' ' . $g . '
        
            </div>
        </div>
         ';
            }
        }
    }
    return '<div class="row"> ' . $selectedGallery . '</div>';
}



function getImages($images)
{
    $imgs = '';
    foreach ($images as $value) {
        $imgs .= '<img src="' . $value . '" style="height: 85px;width:auto;">';
    }
    return $imgs;
}
function showBirds($atts)
{
    $locations = '';
    $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
    if (!empty($locationArray)) {

        foreach ($locationArray as $value) {

            $locations .= '<a class="ui red horizontal mini label" href="?location=' . $value . '">  <i class="map marker icon"></i> ' . $value . ' </a> ';
        }
    }

    $filters = [
        "Spring", "Summer", "Autumn", "Winter"
    ];
    $filterValues = '';
    foreach ($filters as $filter) {
        $location_arg = '';
        if (isset($_GET['location'])) {
            $location_arg = '?location=' . $_GET['location'];
        }
        if (isset($_GET['location']))
            $filterValues .= '<a class="ui blue horizontal mini label" href="' . $location_arg . '&filter=' . $filter . '">' . $filter . '</a>';
    }


    $birdsList  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];
    $birds = '';
    if (!empty($birdsList)) {

        foreach ($birdsList as $bird) {
            if (isset($_GET['location'])) {
                if ($bird['location'] == $_GET['location']) {
                    if (isset($_GET['filter'])) {
                        if ($bird['filter'] == $_GET['filter']) {
                            $birds .= '
                    <div class="ui card ">  <div class="content">
                        <img class="small ui image floated right"
                            src="' . $bird['image'] . '">
                        <a class="header">' . $bird['name'] . '</a>
                        <div class="meta"> 
                            ' . $bird['description'] . '
                            foo ooooo = ' . $atts['foo'] . ' = ' . $_GET['location'] . ' = ' . $bird['location'] . '
                        </div>
                    </div> </div>';
                        }
                    } else {
                        $birds .= '
                    <div class="ui card ">  <div class="content">
                        <img class="small ui image floated right"
                            src="' . $bird['image'] . '">
                        <a class="header">' . $bird['name'] . '</a>
                        <div class="meta"> 
                            ' . $bird['description'] . '
                            foo ooooo = ' . $atts['foo'] . ' = ' . $_GET['location'] . ' = ' . $bird['location'] . '
                        </div>
                    </div> </div>';
                    }
                }
            } else {

                $birds .= '
                <div class="ui card ">  <div class="content">
                    <img class="small ui image floated right"
                        src="' . $bird['image'] . '">
                    <a class="header">' . $bird['name'] . '</a>
                    <div class="meta"> 
                        ' . $bird['description'] . '
                        foo ooooo = ' . $atts['foo'] . ' 
                    </div>
                </div> </div>';
            }
        }
    }




    $value = ' <div class="gallery ui centered grid">
            
        <div class="row">
        ' . $locations . ' 
        </div>
        
        <div  class=" ">
                        
                ' . $filterValues . '
            
        </div>
        <div class="six wide tablet sixteen wide mobile  sixteen wide computer column">
 


            <div style="padding: 10px;"></div>
            <div  id="gallery">
 
                <div class="ui cards centered"> 
                    ' . $birds . '
                </div>
 
            </div>

        </div>
    </div>
';
    $shortcode = '';
    if (isset($_GET['location']) && isset($_GET['filter'])) {
        $shortcode =  '[bird_gallery  l="' .  $_GET['location']. '" f="' . $_GET['filter'] . '"]';
        // echo $shortcode;
    }
    $value .= '<div class="gallery ui centered grid">
    <div class="six wide tablet sixteen wide mobile  sixteen wide computer column">
        <div id="bird_gallery">
            <div class="ui cards centered">
            ' . do_shortcode($shortcode) . '
            </div>
        </div>
    </div>
</div>';
    return $value;
}

add_shortcode('footag', 'showBirds');
add_shortcode('bird_gallery', 'showGallery');
