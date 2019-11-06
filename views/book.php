<div class="container">
    <div class="pt-8"></div>

    <div class="" style="padding:10px"></div>
    <div class="ui centered grid">
        <div class="birds-slideshow six wide tablet sixteen wide mobile  six wide computer column">

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

            <!-- <div class="gallery-slideshow-container">


            </div> -->

            <h1>Incredibly Basic gallerySlider</h1>
            <div id="gallerySlider">
                <a class="control_next">></a>
                <a class="control_prev">
                    < </a> <ul>

                        </ul>
            </div>

            <div class="slider_option">
                <input type="checkbox" id="checkbox">
                <label for="checkbox">Autoplay Slider</label>
            </div>



        </div>
    </div>
</div>



<?php

$birdsGallery  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];
$birdsList  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];
$birdsLocation  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
$birdsLocationFilters  =  is_array(get_option('birds_location_filters')) ? get_option('birds_location_filters') : [];

?>
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

        locSlides += '<div class="locationSlides" style="' + showstyle + '">' + data[0] + '</div>';
    });

    locSlides += '<a class="prev" onclick="plusLocationSlides(-1)">&#10094;</a>' +
        '<a class="next" onclick="plusLocationSlides(1)">&#10095;</a>';
    jQuery('.location-slideshow-container').html(locSlides);



    function galleryView() {


        var birdsList = '';
        birds.forEach(data => {

            console.log('selected location = ' + selectedLocation.localeCompare(data.location) + ' :: ' + selectedLocation + " == " + data.location);

            if (selectedLocation.localeCompare(data.location) == 0 && selectedFilter.localeCompare(data.filter) == 0) {

                birdsList +=
                    '<div class="item inline-flex">' +
                    '<div class="content">' +
                    ' Description ' +
                    '<p>' + data.description + '</p>' +
                    '</div>' +
                    '<img class="ui small image" src="' + data.image + '">' +
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

                    gallerySlide += ' <li style="background: url(' + singleImage + ');"></li> ';
                });
            }


        });

        var bgimg = '';
        filterImages.forEach(fltr => {
            console.log("value of testsat "  + selectedLocation.localeCompare(fltr.location) + " {}{}{}{} " + selectedFilter.localeCompare(fltr.filter)  );
            if (selectedLocation.localeCompare(fltr.location) == 0 && selectedFilter.localeCompare(fltr.filter) == 0) {
                bgimg = fltr.image;
            }
        });

       
        jQuery('.locationSlides.current-location-slide').css({
            backgroundImage: bgimg
        });
        jQuery('#gallerySlider ul').html(gallerySlide);

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