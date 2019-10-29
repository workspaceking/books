<?php $img = 'https://images.vexels.com/media/users/3/145644/isolated/preview/8270b5eba72189d3eb154e100556da94-exotic-bird-illustration-by-vexels.png'; ?>

<div class="container">
<div class="pt-8"></div>

<div class="" style="padding:10px"></div>
<div class="ui grid">
     
 

         <?php

            $galleryList  =  is_array(get_option('birds_gallery')) ? get_option('birds_gallery') : [];

            if (!empty($galleryList)) {

                foreach ($galleryList as $gallery) {
                    $images = explode(",", $gallery['images']);
                    ?>
                 <div class="six wide column card">
                     <div>

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
