<?php

if (isset($_POST)) {

    if (isset($_POST['location_filter_id']) && !empty($_POST['location_filter_id'])) {
        if (isset($_POST['update_btn'])) {

            $locationFilters  =  is_array(get_option('birds_location_filters')) ? get_option('birds_location_filters') : [];
            $newArray = [];




            foreach ($locationFilters as $arr) {
                if ($arr['id'] == $_POST['location_filter_id']) {

                    $datum = [
                        "id" => $_POST['location_filter_id'],
                        "location" => $_POST['location_name'],
                        "filter" => $_POST['filter_name'],
                        "image" => $_POST['image_url'],
                    ];
                    array_push($newArray, $datum);
                } else { 
                    $datum = [
                        "id" => $arr['id'],
                        "location" => $arr['location'],
                        "filter" => $arr['filter'],
                        "image" => $arr['image'],
                    ];
                    array_push($newArray, $datum);
                    
                }
            }


            // print_r($newArray);
            // array_push($locationFilters, $datum);
            // print_r($locationFilters);
            update_option('birds_location_filters', $newArray);

        } else {

            $locationFilters  =  is_array(get_option('birds_location_filters')) ? get_option('birds_location_filters') : [];

            $datum = [
                "id" => $_POST['location_filter_id'],
                "location" => $_POST['location_name'],
                "filter" => $_POST['filter_name'],
                "image" => $_POST['image_url'],
            ];
            array_push($locationFilters, $datum);
            update_option('birds_location_filters', $locationFilters);
        }
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
                            <input type="hidden" value="<?php echo time(); ?>" name="location_filter_id">
                        </div>
                        <div class="field">

                            <select name="location_name" class="ui dropdown" required>
                                <option value="">Select Location</option>
                                <?php

                                $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];


                                foreach ($locationArray as $arr) { ?>
                                    <option value="<?php echo $arr[0]; ?>"><?php echo $arr[0]; ?></option>
                                <?php }
                                ?>


                            </select>
                        </div>

                        <div class="field">
                            <?php $filters = [
                                "Spring", "Summer", "Autumn", "Winter"
                            ]; ?>
                            <select class="ui dropdown" required name="filter_name">
                                <option value="">Select Filter</option>
                                <?php foreach ($filters as $filter) { ?>
                                    <option value="<?php echo $filter; ?>"><?php echo $filter; ?></option>
                                <?php }
                                ?>
                            </select>
                        </div>

                        <div class="field">
                            <div class='image-preview-wrapper' id="bg-filter">


                                <?php
                                if (isset($_POST['submit_image_selector']) && isset($_POST['image_url'])) :
                                    update_option('media_selector_attachment_id', absint($_POST['image_url']));
                                endif;
                                wp_enqueue_media();
                                ?>

                            </div>

                            <input type='hidden' name='image_url' id='image_url' value='<?php echo wp_get_attachment_url(get_option('media_selector_attachment_id')); ?>' required>


                        </div>

                        <div class="field">
                            <input id="upload_image_button" type="button" class="button" value="<?php _e('Upload image'); ?>" />
                            <input type='hidden' name='image_attachment_id' id='image_attachment_id' value='<?php echo get_option('media_selector_attachment_id'); ?>'>

                        </div>

                    </div>
                </div>

                <div class="extra content">
                    <div class="ui divider"></div>
                    <div class="field">
                        <button class="ui button right floated " name="update_btn" type="submit" disabled>Update Filter</button>
                        <button class="ui button right floated " name="add_btn" type="submit">Add Filter</button>
                    </div>
                </div>

            </div>
        </form>
    </div>
    <div class="four wide column">
        <div style="padding:10px"></div>
        <?php
        $locationFilters  =  is_array(get_option('birds_location_filters')) ? get_option('birds_location_filters') : [];

        foreach ($locationFilters as $arr) { ?>

            <div class="ui middle aligned birds divided list">


                <div class="item" id="bg-small">

                    <div class="ui raised segment" style="background-image:url(<?php echo $arr['image']; ?>)">

                        <form class="ui form" method="post">
                            <div class="right floated author">
                                <input type="hidden" name="del" value="1572994039">

                                <button class="circular ui icon button" type="submit">
                                    <i class="icon x"></i>
                                </button>
                            </div>
                        </form>
                        <button class="right floated circular ui icon button" onclick="editFiltersParams('<?php echo $arr['id']; ?>','<?php echo $arr['location']; ?>','<?php echo $arr['filter']; ?>','<?php echo $arr['image']; ?>',)">
                            <i class="icon edit"></i>
                        </button>


                        <div class="ui bottom attached label">

                            <span class="ui basic image label">
                                <i class="map marker icon"></i>
                                <?php echo $arr['location']; ?> </span>

                            <span class="ui basic image label">
                                <i class="sun icon"></i>
                                <?php echo $arr['filter']; ?> </span>

                        </div>


                    </div>
                </div>

            </div>

        <?php }

        ?>
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
                // Do something with attachment.id and/or attachment.url here
                // $('#image-preview').attr('src', attachment.url);
                $('#bg-filter').attr('style', 'background-image:url(' + attachment.url + ')');
                $('#image_url').val(attachment.url);
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