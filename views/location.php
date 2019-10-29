<?php


if (isset($_POST)) {
    $locationArray = [];
    if (isset($_POST['location']) && !empty($_POST['location'])) {


        // this line of code checks if the option is an array or not

        $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
        // In case of multidimentional array you can push array to an array
        array_push($locationArray, $_POST['location']);
        // print_r($locationArray);
        update_option('birds_location', $locationArray);
    }
    if (isset($_POST['del']) && !empty($_POST['del'])) {



        $locationArray  =  is_array(get_option('birds_location')) ? get_option('birds_location') : [];
        $newArray = [];
        
        // print_r($locationArray);

        foreach($locationArray as $value) {
            if(trim($value)  == trim($_POST['del'])) {
                 
            }else {
                array_push($newArray,$value);
            }
            
        }

        update_option('birds_location', $newArray);

        // In case of multidimentional array you can push array to an array

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


                    </div>
                </div>

                <div class="extra content">
                    <div class="ui divider"></div>
                    <div class="right floated author">
                        <button class="ui button" type="submit">Add Location</button>
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

                foreach ($locationArray as $value) { ?>
                    <div class="item">
                        <div class="right floated content">
                            <form class="ui form" method="post">
                                <div class="right floated author">
                                    <input type="hidden" name="del" value=" <?php echo $value; ?>" />
                                    <button class="circular ui icon button" type="submit">
                                        <i class="icon x"></i>
                                    </button>
                                </div>
                            </form>
                        </div>
                        <img class="ui avatar image" src="https://images.vexels.com/media/users/3/145644/isolated/preview/8270b5eba72189d3eb154e100556da94-exotic-bird-illustration-by-vexels.png">
                        <div class="content">
                            <?php echo $value; ?>
                        </div>
                    </div>

            <?php }
            }

            ?>

        </div>

    </div>
</div>