<section class="newsSet sky_bd">
         	<div class="search"><?php echo '" '.$query.' "'; echo ($contents->numFound > 0) ? ' / '.$contents->numFound .' results' : '';?></div>
            <?php if($contents->numFound > 0 ){?>
            <ul>
			 <?php foreach($contents->docs as $key => $content){
				 // <?php if($content['section_parentid']<130){
			      $thumbnail = getResizedThumb($content->thumbnail, '122x66'); 
			 ?> 
               <li><figure class="container">
                    	<a href="<?php echo SITEPATH.'/'.$content->guid?>" class="FigImg"><img src="<?php echo $thumbnail ?>" alt=""></a>
                        <figcaption>
                         <a href="<?php echo SITEPATH.'/'.$content->guid?>"><h3><?php echo $content->headline1?></h3></a>
                        </figcaption>
               </figure></li>
              <?php } ?>
            </ul>
			<?php } ?>
            <div class="pagenume sky_pagi">
               <?php 
			    echo $page_links;				
				?> 
            </div>
            
         </section>