<section class="newsSet <?php echo getsectioncolor($article['section_parentid'])?>_bd">
        	<h2><span class="<?php echo getsectioncolor($article['section_parentid'])?>">spotlight</span></h2>
        	<ul>    
			  <?php 
			  $counter = 1; 
			  
			  foreach($spotlight_contents as $key =>$content){  
				        $content_thumbnail = getResizedThumb($content['thumbnail'], '122x66');
						
			  ?>
               <li style="display:<?php echo ($counter > 4)? 'none':'block' ?>;"><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><img src="<?php echo $content_thumbnail; ?>" alt="<?php echo $content['thumbnail_alt'];?>"></a>
                        <figcaption>
                         <a href="<?php echo $content['guid']?>"><h3><?php echo $content['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>
              <?php $counter++; } ?>
                
            </ul>
            <div class="clr"></div>
        </section>

 
 <?php if(count($contents) > 0){?>
         <section class="newsSet <?php echo getsectioncolor($article['section_parentid'])?>_bd" id="section_related">
		   <h2><span class="<?php echo getsectioncolor($article['section_parentid'])?>">related <?php if($article[ 'contype_id' ] == PHOTOGALLERY || $article[ 'contype_id' ] == PICTURESTORY){ echo 'galleries'; }else { echo 'Stories'; }?></span></h2>        	
        	<ul>
			<?php
	             $counter =1;
                 foreach($contents as $key => $content ){
				 $content_thumbnail = getResizedThumb($content->thumbnail, '122x66');

			?>
                 <li style="display:<?php echo ($counter > 4)? 'none':'block' ?>;"><figure class="container">
                    	<a href="<?php echo SITEPATH.'/'.$content->guid?>" class="FigImg"><img src="<?php echo $content_thumbnail ;?>" alt="<?php echo $content->thumbnail_alt?>"></a>
                        <figcaption>
                         <a href="<?php echo SITEPATH.'/'.$content->guid?>"><h3><?php echo $content->headline1?></h3></a>
                        </figcaption>
               </figure></li>
			   <?php  $counter++; } ?>
            </ul>
			<div class="clr"></div>
            <div class="load_more <?php echo getsectioncolor($article['section_parentid'])?>_load"  data-id="related"><a href="javascript:;">load more</a></div>			
            <div class="clr"></div>            
        </section>

		<?php  } ?>