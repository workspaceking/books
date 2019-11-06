<?php


if (isset($_POST)) {
    $locationArray = [];
    if (isset($_POST['location']) && !empty($_POST['location'])) {


        if (isset($_POST['update_btn'])) {
            $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
            $newArray = [];
 
            foreach ($locationArray as $arr) {
                if (trim($arr[0])  == trim($_POST['location'])) { 
                    array_push($newArray, [$_POST['location'],  $_POST['location_image']]);
                } else { 
                    array_push($newArray, [$arr[0],  $arr[1]]);
                }
            } 
            update_option('birds_location', $newArray);
        } else {

            $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
            // In case of multidimentional array you can push array to an array
            array_push($locationArray, [$_POST['location'],  $_POST['location_image']]);
         
            update_option('birds_location', $locationArray);
        }
    }
    if (isset($_POST['del']) && !empty($_POST['del'])) {

        $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
        $newArray = [];

         

        foreach ($locationArray as $arr) {
            if (trim($arr[0])  == trim($_POST['del'])) { } else {

                array_push($newArray, $arr);
            }
        }
        
        update_option('birds_location', $newArray);
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
                            <label>Location</label>
                            <input type="text" name="location" placeholder="Location Name">
                        </div>
                        <div class="field">
                            <div class='image-preview-wrapper'>

                                <div class="ui small images segment">
                                    <?php
                                    if (isset($_POST['submit_image_selector']) && isset($_POST['location_image'])) :
                                        update_option('media_selector_attachment_id', absint($_POST['location_image']));
                                    endif;
                                    wp_enqueue_media();
                                    ?>

                                    <img id='image-preview' src='<?php echo wp_get_attachment_url(get_option('media_selector_attachment_id')); ?>' style="height: 170px;width:auto;">

                                </div>
                            </div>
                            <input type='hidden' name='location_image' id='location_image' value='<?php echo wp_get_attachment_url(get_option('media_selector_attachment_id')); ?>' required>


                        </div>

                        <div class="field">
                            <input id="upload_image_button" type="button" class="button" value="<?php _e('Upload image'); ?>" />
                            <input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option('media_selector_attachment_id'); ?>'>

                        </div>
                    </div>
                </div>

                <div class="extra content">
                    <div class="ui divider"></div>
                    <div class="right floated">
                        <button class="ui button" name="add_btn" type="submit">Add Location</button>
                        <button class="ui button" name="update_btn" type="submit" disabled>Update Location</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="four wide column">
        <div class="" style="padding:10px"></div>

        <div class="ui middle aligned birds divided list">
            <?php

            $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
            if (!empty($locationArray)) {

                foreach ($locationArray as $arr) { ?>

                    <div class="item" id="bg-small">
                        <div class="ui raised segment" style="background-image: url(<?php echo $arr[1]; ?>);">

                            <form class="ui form" method="post">
                                <div class="right floated author">
                                    <input type="hidden" name="del" value="<?php echo $arr[0]; ?>" />

                                    <button class="circular ui red icon button" type="submit">
                                        <i class="icon x"></i>
                                    </button>
                                </div>
                            </form>
                            <button class="right floated circular ui icon button" onclick="editParams('<?php echo $arr[0] ?>','<?php echo $arr[1] ?>')">
                                <i class="icon edit"></i>
                            </button>
                            <div class="ui bottom attached label block mt-4">
                                <span class="ui basic image ">
                                    <i class="map marker icon"></i>
                                    <?php echo $arr[0]; ?> </span>


                            </div>
                        </div>
                    </div>

            <?php }
            }

            ?>

        </div>

    </div>
</div>

<?php
if (isset($_POST['submit_image_selector']) && isset($_POST['image_attachment_id'])) :
    update_option('media_selector_attachment_id', absint($_POST['image_attachment_id']));
endif;
wp_enqueue_media();

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
                multiple: false // Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                // Do something with attachment.id and/or attachment.url here
                $('#image-preview').attr('src', attachment.url);
                $('#location_image').attr('value', attachment.url);
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