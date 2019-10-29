 <?php


    if (isset($_POST)) {

        if (isset($_POST['del'])) {

            $galleryArray  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];
            $newArray = [];

            // print_r($locationArray);

            foreach ($galleryArray as $gallery) {
                if (trim($gallery['id'])  == trim($_POST['del'])) { } else {
                    array_push($newArray, $gallery);
                }
            }

            // print_r($newArray);
            update_option('birds_gallery', $newArray);
        } else if (isset($_POST['gallery_location']) && !empty($_POST['gallery_location'])) {


            $datum = [
                "id" => sanitize_text_field($_POST['gallery_id']),
                "name" => sanitize_text_field($_POST['gallery_name']),
                "location" => sanitize_text_field($_POST['gallery_location']),
                "filter" => sanitize_text_field($_POST['gallery_filter']),
                "images" => sanitize_text_field($_POST['gallery_images']),
            ];

            $missing = '';
            foreach ($datum as $key => $value) {
                if (empty($value)) {
                    $missing = $key;
                }
            }
            if ($missing) {
                echo '<span style="margin-top:20px;" class="ui red tag label">' . $missing .  ' is missing </span>';
                return;
            }

            $galleryArray  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];


            if (sizeof($galleryArray) > 0) {
                array_push($galleryArray, $datum);
            } else {
                $galleryArray = [$datum];
            }

            update_option('birds_gallery', $galleryArray);
        }
    }

    ?>


 <div class="ui grid">
     <div class="eight wide column">

         <form class="ui form" method="post">

             <div class="red card">
                 <div class="content">
                     <!-- <div class="header">Bird Name</div>
                    <div class="meta">
                        <span class="category">location</span>
                    </div> -->
                     <div class="description">

                         <div class="field">
                             <input type="hidden" name="gallery_id" value="<?php echo time(); ?>">
                         </div>

                         <div class="field">
                             <input type="text" name="gallery_name" placeholder="Gallery Name">
                         </div>

                         <div class="field">

                             <select name="gallery_location" class="ui dropdown" required>
                                 <option value="">Select Location</option>
                                 <?php

                                    $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];


                                    foreach ($locationArray as $value) { ?>
                                     <option value="<?php echo $value; ?>"><?php echo $value; ?></option>
                                 <?php }
                                    ?>


                             </select>
                         </div>

                         <div class="field">
                             <?php $filters = [
                                    "Spring", "Summer", "Autumn", "Winter"
                                ]; ?>
                             <select class="ui dropdown" required name="gallery_filter">
                                 <option value="">Select Filter</option>
                                 <?php foreach ($filters as $filter) { ?>
                                     <option value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                                 <?php }
                                    ?>
                             </select>
                         </div>




                     </div>


                 </div>

                 <div class="extra content">
                     <div class="ui divider"></div>
                     <div class="field">

                         <div class="field">

                             <?php
                                if (isset($_POST['submit_image_selector']) && isset($_POST['gallery_images'])) :
                                    update_option('media_selector_attachment_id', absint($_POST['gallery_images']));
                                endif;
                                wp_enqueue_media();
                                ?>
                             <input type="hidden" name='gallery_images' id='gallery_images' required></textarea>

                             <div class="ui tiny images" id="bird_gallery">
                             </div>


                             <input id="upload_image_button" type="button" class="button" value="<?php _e('Upload image'); ?>" />

                         </div>


                         <div class="extra content">
                             <div class="ui divider"></div>
                             <div class="field">
                                 <button class="ui button right floated " type="submit">Add Gallery</button>
                             </div>

                         </div>
                     </div>

                 </div>
             </div>
         </form>
     </div>

     <div class="six wide column">
         <div class="" style="padding:10px"></div>

         <?php

            $galleryList  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];

            if (!empty($galleryList)) {

                foreach ($galleryList as $gallery) {
                    $images = explode(",", $gallery['images']);
                    ?>
                 <div class="card">
                     <div>
                     <div class="ui top attached">[bird_gallery g="<?php echo $gallery['id']; ?>" l="<?php echo $gallery['location']; ?>" f="<?php echo $gallery['filter']; ?>"]</div>
                         <span class="ui basic image label">
                             <i class="map marker icon"></i>
                             <?php echo $gallery['name']; ?>
                         </span>
                         <span class="ui teal image label">
                             <i class="map marker icon"></i>
                             <?php echo $gallery['location']; ?>
                         </span>
                         <span class="ui red  image label">
                             <i class="map marker icon"></i>
                             <?php echo $gallery['filter']; ?>
                         </span>

                         <form class="ui form" method="post">
                             <div class="right floated author">
                                 <input type="hidden" name="del" value="<?php echo $gallery['id']; ?>" />
                                 <button class="circular ui icon button  right floated " type="submit">
                                     <i class="icon x"></i>
                                 </button>
                             </div>
                         </form>

                     </div>

                     <br>
                     <br>

                     <div class="ui small images segment">
                         <?php
                                    foreach ($images as $value) { ?>

                             <img src="<?php echo $value; ?>" style="height: 85px;width:auto;">


                         <?php }

                                    ?>
                     </div>
                 </div>

         <?php }
            }

            ?>
     </div>

 </div>



 </div>

 <div class="four wide column">

 </div>


 </div>


 <?php
    $my_saved_attachment_post_id = get_option('media_selector_attachment_id', 0);
    ?><script type='text/javascript'>
     jQuery(document).ready(function($) {
         // Uploading files 
         var file_frame;
         var wp_media_post_id = wp.media.model.settings.post.id; // Store the old id
         var set_to_post_id = <?php echo $my_saved_attachment_post_id; ?>; // Set this
         jQuery('#upload_image_button').on('click', function(event) {
             event.preventDefault();
             // If the media frame already exists, reopen it.
             if (file_frame) {
                 // Set the post ID to what we want
                 file_frame.uploader.uploader.param('post_id', set_to_post_id);
                 // Open frame
                 file_frame.open();
                 return;
             } else {
                 // Set the wp.media post id so the uploader grabs the ID we want when initialised
                 wp.media.model.settings.post.id = set_to_post_id;
             }
             // Create the media frame.
             file_frame = wp.media.frames.file_frame = wp.media({
                 title: 'Select a image to upload',
                 button: {
                     text: 'Use this image',
                 },
                 multiple: true // Set to true to allow multiple files to be selected
             });
             // When an image is selected, run a callback.
             file_frame.on('select', function() {
                 // We set multiple to false so only get one image from the uploader
                 attachment = file_frame.state().get('selection').first().toJSON();

                 galleryItem = '';
                 imagesUrl = [];
                 //  $('#image_list').html(file_frame.state().get('selection'));
                 datum = file_frame.state().get('selection').toJSON();
                 console.log(file_frame.state().get('selection'));


                 datum.forEach(data => {

                     galleryItem += '<img class="ui image" src="' + data.url + '">';
                     imagesUrl.push(data.url);

                 });

                 $("#bird_gallery").html(galleryItem);

                 $('input:hidden[name=gallery_images]').val(imagesUrl);
                 // Do something with attachment.id and/or attachment.url here
                 //  $('#image-preview').attr('src', attachment.url); 
                 // Restore the main post ID
                 wp.media.model.settings.post.id = wp_media_post_id;
             });
             // Finally, open the modal
             file_frame.open();
         });
         // Restore the main ID when the add media button is pressed
         jQuery('a.add_media').on('click', function() {
             wp.media.model.settings.post.id = wp_media_post_id;
         });
     });
 </script>