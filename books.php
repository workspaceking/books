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
// 1) arrows not changed yet
// 2) body background color -> #fff
// 3) min-height of birds section
// 4) add same margin/padding between last bird and gallery,
// as it's between first bird and filter -> photo attached
 
require_once __DIR__ . '/includes/class-birdbook-admin-pages.php';

new \birdbook\birdbooksearch(new \birdbook\BirdBookBook(), new \birdbook\BirdBookGoogleBookApi());
new \birdbook\BirdBookAdminPages();
function loadStylesandScripts()
{

    wp_enqueue_style('gallery-css',  plugin_dir_url(__FILE__) . 'css/book.css');
    wp_enqueue_style('semantic-ui',  plugin_dir_url(__FILE__) .  'css/semantic.min.css');
    wp_enqueue_style('gallery-fonts',   'https://fonts.googleapis.com/css?family=Titillium+Web&display=swap');

    wp_enqueue_script('semantic-ui-script',  plugin_dir_url(__FILE__) .  'js/semantic.min.js', ['jquery'], '', true);
}
add_action('wp_enqueue_scripts', 'loadStylesandScripts');

// function showGallery($atts)
// {
//     // return  $atts['g'];

//     $galleryList  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];
//     $selectedGallery = '';
//     if (!empty($galleryList)) {

//         foreach ($galleryList as $gallery) {
//             $images = explode(",", $gallery['images']);
//             $g = '';
//             if (isset($atts['g']))
//                 $g = $atts['g'];
//             if ($gallery['id'] == $g || $gallery['location'] == $atts['l'] || $gallery['location'] == $atts['l'] && $gallery['filter'] == $atts['f']) {
//                 $selectedGallery .=  ' <div class="card">
//             <div>


//                 <form class="ui form" method="post">
//                     <div class="right floated author">
//                         <input type="hidden" name="del" value="' . $gallery['id'] . '" />
//                         <button class="circular ui icon button  right floated " type="submit">
//                             <i class="icon x"></i>
//                         </button>
//                     </div>
//                 </form>

//             </div> 

//             <div class="ui small images segment">

//                 ' . getImages($images) . ' ' . $g . '

//             </div>
//         </div>
//          ';
//             }
//         }
//     }
//     return '<div class="row"> ' . $selectedGallery . '</div>';
// }



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
                    
                    </div>
                </div> </div>';
            }
        }
    }




    $value = ' <div class="gallery ui centered grid">
            
        <div class="row">
        ' . $locations . ' 
        </div>
        
        <div  class="row">
                        
                ' . $filterValues . '
            
        </div>
        <div class="six wide tablet sixteen wide mobile  twelve wide computer column">
 


            <div style="padding: 10px;"></div>
            <div  id="gallery">
 
                <div class="ui cards centered"> 
                    ' . $birds . '
                </div>
 
            </div>

        </div>
    </div>
';
    $birdsGallery  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];
    $birdsList  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];
    $birdsLocation  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
    $birdsLocationFilters  =  is_array(get_option('birds_location_filters')) ? get_option('birds_location_filters') : [];

    ?>

    <script>
        var locations = <?php echo (json_encode($birdsLocation)); ?>;
        var filterImages = <?php echo (json_encode($birdsLocationFilters)); ?>;
        var birds = <?php echo (json_encode($birdsList)); ?>;
        var gallery = <?php echo (json_encode($birdsGallery)); ?>;
    </script>
    <?php
        $shortcode = '';
        if (isset($_GET['location']) && isset($_GET['filter'])) {
            $shortcode =  '[bird_gallery  l="' .  $_GET['location'] . '" f="' . $_GET['filter'] . '"]';
            // echo $shortcode;
        }
        $value .= '<div class="gallery ui centered grid">
    <div class="six wide tablet sixteen wide mobile  twelve wide computer column">
        <div id="bird_gallery">
            <div class="ui cards centered">
            ' . do_shortcode($shortcode) . '
            </div>
        </div>
    </div>
</div>';
        return $value;
    }


    function showGallery($attr)
    { ?>

    <div class="container">
        <div class="pt-8"></div>

        <div class=""  ></div>
        <div class="ui centered grid">
            <div class="birds-slideshow  wide  column">

                <div class="location-slideshow-container"> </div>


                <div class="filters">
                    <a class="ui small label"> Spring </a>
                    <a class="ui small label"> Summer </a>
                    <a class="ui small label"> Autumn </a>
                    <a class="ui small label"> Winter </a>
                </div>

                <div class="ui middle aligned divided list birds-list">
                    <div class="item">

                        <img class="ui avatar image" src="/images/avatar2/small/molly.png">
                        <div class="content">
                            Molly
                        </div>
                    </div>
                </div>
                <div id="gallerySlider">
                    <a class="control_next"> </a>
                    <a class="control_prev">
                          </a> <ul id="location-gallery">

                            </ul>
                </div>

                <!-- <div class="slider_option">
                    <input type="checkbox" id="checkbox">
                    <label for="checkbox">Autoplay Slider</label>
                </div> -->



            </div>
        </div>
    </div>



    <?php

        $birdsGallery  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];
        $birdsList  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];
        $birdsLocation  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
        $birdsLocationFilters  =  is_array(get_option('birds_location_filters')) ? get_option('birds_location_filters') : [];

        ?>


    <!-- The Modal -->
    <div id="lightbox-modal" class="modal">
        <span class="close">&times;</span>
        <img class="modal-content" id="modal-image">
        <div id="caption"></div>
    </div>

    <script>
        function previewImage(e) {
            var modal = document.getElementById("lightbox-modal");

            // Get the image and insert it inside the modal - use its "alt" text as a caption
            var modalImage = document.querySelectorAll('.birds-list div.image');
            var modalImg = document.getElementById("modal-image");
            var captionText = document.getElementById("caption");


            // Get the <span> element that closes the modal
            var span = document.getElementsByClassName("close")[0];

            // When the user clicks on <span> (x), close the modal
            span.onclick = function() {
                modal.style.display = "none";
            }
            modal.style.display = "block";
            modalImg.src = e.getAttribute('data');
            captionText.innerHTML = e.getAttribute('alt');
            console.log(e.getAttribute('data'))

        }
    </script>
    <script>
        var selectedLocation = '';

        var selectedFilter = 'Summer';
        var locations = <?php echo (json_encode($birdsLocation)); ?>;
        var filterImages = <?php echo (json_encode($birdsLocationFilters)); ?>;
        var birds = <?php echo (json_encode($birdsList)); ?>;
        var gallery = <?php echo (json_encode($birdsGallery)); ?>;
        var locSlides = '';
        var show = 0;
        locations.forEach(data => {
            var showstyle = "";
            if (show == 0) {
                showstyle = "display:block";
            } else {
                showstyle = '';
            }
            show = 1;

            locSlides += '<div class="locationSlides" style="' + showstyle + ';background:url(' + data[1] + ');background-size: cover;background-position: center;">' + data[0] + '</div>';
        });

        locSlides += '<a class="prev" onclick="plusLocationSlides(-1)"></a>' +
            '<a class="next" onclick="plusLocationSlides(1)"></a>';
        jQuery('.location-slideshow-container').html(locSlides);
        var galleryHTML = '';


        function galleryView() {


            var birdsList = '';
            birds.forEach(data => {

                console.log('selected location = ' + selectedLocation.localeCompare(data.location) + ' :: ' + selectedLocation + " == " + data.location);

                if (selectedLocation.localeCompare(data.location) == 0 && selectedFilter.localeCompare(data.filter) == 0) {

                    birdsList +=
                        '<div class="item inline-flex">' +
                        '<div class="content">' +
                        '<h3 style="text-transform: capitalize;">' + data.name + '</h3>' +
                        ' Description ' +
                        '<p>' + data.description + '</p>' +
                        '</div>' +
                        '<div class="image" onclick="previewImage(this)" data="' + data.image + '" alt="' + data.name + '" style="background: url(' + data.image + ');background-size: cover;background-repeat: no-repeat;background-position: center;height:auto"></div>' +
                        '</div>';
                }


            });

            jQuery('.birds-list').html(birdsList);



            var gallerySlide = '';
            var show = 0;
            gallery.forEach(data => {


                console.log('selected location = ' + selectedLocation.localeCompare(data.location) + ' :: ' + selectedLocation + " == " + data.location);

                if (selectedLocation.localeCompare(data.location) == 0 && selectedFilter.localeCompare(data.filter) == 0) {

                    var showstyle = "";
                    if (show == 0) {
                        showstyle = "display:block";
                    } else {
                        showstyle = '';
                    }
                    show = 1;
                    var imgs = gallery[0].images.split(',');
                    console.log(imgs);
                    imgs.forEach(singleImage => {

                        gallerySlide += ' <li style="background: url(' + singleImage + ');background-position: center;background-size: cover;"></li> ';
                    });
                }


            });

            var bgimg = '';
            filterImages.forEach(fltr => {
                console.log("value of testsat " + selectedLocation.localeCompare(fltr.location) + " {}{}{}{} " + selectedFilter.localeCompare(fltr.filter));
                if (selectedLocation.localeCompare(fltr.location) == 0 && selectedFilter.localeCompare(fltr.filter) == 0) {
                    bgimg = fltr.image;

                }
            });


            jQuery('#gallerySlider ul').html(gallerySlide);

            galleryHTML = jQuery('#gallerySlider ul').html();

            if (galleryHTML.length > 0) {
                jQuery('#gallerySlider').removeClass("hidden");

            } else {
                jQuery('#gallerySlider').addClass("hidden");
            }

        }

        galleryView();

        var slideIndex = 1;
        currentLocationSlide(slideIndex);

        function plusLocationSlides(n) {
            currentLocationSlide(slideIndex += n);

        }


        function currentLocationSlide(n) {
            var i;
            var slides = jQuery(".location-slideshow-container .locationSlides");

            if (n > slides.length) {
                slideIndex = 1
            }
            if (n < 1) {
                slideIndex = slides.length;
            }
            for (i = 0; i < slides.length; i++) {
                slides[i].style.display = "none";
                jQuery(slides[i]).removeClass('current-location-slide');
            }

            slides[slideIndex - 1].style.display = "block";

            jQuery(slides[slideIndex - 1]).addClass('current-location-slide');

            selectedLocation = jQuery('.locationSlides.current-location-slide').html();

            galleryView();
        }
    </script>



    <script>
        jQuery(document).ready(function($) {

            $('#checkbox').change(function() {
                setInterval(function() {
                    moveRight();
                }, 3000);
            });

            var slideCount = $('#gallerySlider ul li').length;
            var slideWidth = $('#gallerySlider ul li').width();
            var slideHeight = $('#gallerySlider ul li').height();
            var gallerySliderUlWidth = slideCount * slideWidth;

            $('#gallerySlider').css({
                width: slideWidth,
                height: slideHeight
            });



            $('#gallerySlider ul li:last-child').prependTo('#gallerySlider ul');

            function moveLeft() {
                $('#gallerySlider ul').animate({
                    left: +slideWidth
                }, 200, function() {
                    $('#gallerySlider ul li:last-child').prependTo('#gallerySlider ul');
                    $('#gallerySlider ul').css('left', '');
                });
            };

            function moveRight() {
                $('#gallerySlider ul').animate({
                    left: -slideWidth
                }, 200, function() {
                    $('#gallerySlider ul li:first-child').appendTo('#gallerySlider ul');
                    $('#gallerySlider ul').css('left', '');
                });
            };

            $('a.control_prev').click(function() {
                moveLeft();
            });

            $('a.control_next').click(function() {
                moveRight();
            });

        });
        jQuery('.filters .label').click(function() {
            selectedFilter = jQuery(this).text().trim();
            galleryView();
        });
    </script>
<?php
}
// add_shortcode('birds_list', 'showBirds');
add_shortcode('bird_gallery', 'showGallery');

?>