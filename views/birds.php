<?php $img = 'https://semantic-ui.com/images/wireframe/image.png'; ?>

<?php


if (isset($_POST)) {

    if (isset($_POST['del'])) {

        $birdsList  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];
        $newArray = [];

        // print_r($locationArray);

        foreach ($birdsList as $bird) {
            if (trim($bird['id'])  == trim($_POST['del'])) { } else {
                array_push($newArray, $bird);
            }
        }

        // print_r($newArray);
        update_option('birds_list', $newArray);
    } else if (isset($_POST['bird_location']) && !empty($_POST['bird_location'])) {


        $datum = [
            "id" => sanitize_text_field($_POST['bird_id']),
            "name" => sanitize_text_field($_POST['bird_name']),
            "description" => sanitize_text_field($_POST['bird_description']),
            "location" => sanitize_text_field($_POST['bird_location']),
            "filter" => sanitize_text_field($_POST['bird_filter']),
            "image" => sanitize_text_field($_POST['bird_image']),
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

        $birdsArray  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];


        if (sizeof($birdsArray) > 0) {
            array_push($birdsArray, $datum);
        } else {
            $birdsArray = [$datum];
        }

        update_option('birds_list', $birdsArray);
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
                    <div class="descriptionription">


                        <div class="field">
                            <input type="hidden" name="bird_id" value="<?php echo time(); ?>">
                        </div>

                        <div class="field">
                            <label>Bird Name</label>
                            <input type="text" name="bird_name" placeholder="Bird Name" required>
                        </div>


                        <div class="field">
                            <label>description</label>
                            <textarea name="bird_description" placeholder="Tell us more" rows="3" required></textarea>
                        </div>
                        <div class="field">

                            <select name="bird_location" class="ui dropdown" required>
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
                            <select class="ui dropdown" required name="bird_filter">
                                <option value="">Select Filter</option>
                                <?php foreach ($filters as $filter) { ?>
                                    <option value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>

                        <div class="field">

                            <?php
                            if (isset($_POST['submit_image_selector']) && isset($_POST['bird_image'])) :
                                update_option('media_selector_attachment_id', absint($_POST['bird_image']));
                            endif;
                            wp_enqueue_media();
                            ?>
                            <input type='hidden' name='bird_image' id='bird_image' value='<?php echo wp_get_attachment_url(get_option('media_selector_attachment_id')); ?>' required>

                            <img id='image-preview' src='<?php echo wp_get_attachment_url(get_option('media_selector_attachment_id')); ?>' class="ui medium bordered image">

                            <input id="upload_image_button" type="button"  class="button" value="<?php _e('Upload image'); ?>" />

                        </div>



                    </div>


                </div>

                <div class="extra content">
                    <div class="ui divider"></div>
                    <div class="field">
                        <button class="ui button right floated " type="submit">Add Location</button>
                    </div>

                </div>
            </div>


        </form>

    </div>
    <div class="four wide column">
        <div class="" style="padding:10px"></div>

        <div class="ui middle aligned birds divided list">
            <?php

            $birdsList  =  is_array(get_option('birds_list')) ? get_option('birds_list') : [];
            if (!empty($birdsList)) {

                foreach ($birdsList as $bird) {      ?>
 

                    <div class="item">

                        <div class="ui raised segment">
                            <div class="right floated content">
                                <form class="ui form" method="post">
                                    <div class="right floated author">
                                        <input type="hidden" name="del" value="<?php echo $bird['id']; ?>" />
                                        <button class="circular ui icon button" type="submit">
                                            <i class="icon x"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <span class="ui red ribbon label"><?php echo $bird['name']; ?></span>
                            <img class="ui middle aligned tiny image" src="<?php echo $bird['image']; ?>">

                            <div class="ui bottom attached label">

                                <span class="ui basic image label">
                                    <i class="map marker icon"></i>
                                    <?php echo $bird['location']; ?>
                                </span>

                                <span class="ui basic image label">
                                    <i class="sun icon"></i>
                                    <?php echo $bird['filter']; ?>
                                </span>

                            </div>


                            <div class="content">
                                <?php echo $bird['description']; ?>
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
                multiple: true// Set to true to allow multiple files to be selected
            });
            // When an image is selected, run a callback.
            file_frame.on('select', function() {
                // We set multiple to false so only get one image from the uploader
                attachment = file_frame.state().get('selection').first().toJSON();
                // Do something with attachment.id and/or attachment.url here
                $('#image-preview').attr('src', attachment.url);
                $('#bird_image').attr('value', attachment.url);
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