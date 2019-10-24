<?php $img = 'https://semantic-ui.com/images/wireframe/image.png'; ?>

 
 <div class="ui grid">
     <div class="four wide column"></div>
     <div class="eight wide column">
         <form class="ui form">


             <div class="red card">
                 <div class="content">
                     <!-- <div class="header">Bird Name</div>
                    <div class="meta">
                        <span class="category">location</span>
                    </div> -->
                     <div class="description">


                         <div class="field">
                             <label>Name</label>
                             <input type="text" name="location" placeholder="Bird Name">
                         </div>
                         <div class="field">
                             <label>Description</label>
                             <textarea placeholder="Tell us more" rows="3"></textarea>
                         </div>
                         <div class="field">
                             <select class="ui dropdown">
                                 <option value="">Location</option>
                                 <option value="1">Location a</option>
                                 <option value="2">Location b</option>
                             </select>
                         </div>


                         <div class="field">
                             <select class="ui dropdown">
                                 <option value="">Filter</option>
                                 <option value="1">Filter a</option>
                                 <option value="2">Filter b</option>
                             </select>
                         </div>

                         <div class="field">

                             <input type="file" (change)="fileEvent($event)" class="inputfile" id="embedpollfileinput" />

                             <label for="embedpollfileinput" class="ui huge green button">
                                 <i class="ui upload icon"></i>
                                 Upload image
                             </label>


                         </div>

                         <div class="field">
                             <img class="ui avatar image" src="<?php echo $img; ?>"> ...
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


 </div>