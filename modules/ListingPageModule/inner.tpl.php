<section> 
            <div class="quiz_container mO">
                <div class="quiz_section">
                    <div class="big_image">
                    </div>
                    <div class="quiz_qust">
                        <div class="quiz_qusts txt_shadow">
						<?php if(!empty($_GET['type']) && ($_GET['type'] == 'gallery')){
							    echo "Photogallery";
							  } else if(!empty($_GET['type']) && ($_GET['type'] == 'quiz')) {
								  echo "Quizzes";
							  }
							
							?>
						
						</div>
                    </div>
                    <div class="clr"> </div>
                </div>
              </div>  
         </section>
         
         <section class="newsSet sky_bd">
         	<div class="grey_conts">&nbsp;</div>            
			<?php $i = 0;
			   if($sub_sectiondata['data_count'] > 0){ ?>			   
            <ul>
			<?php foreach($sub_sectiondata['data'] as $key=> $content){ 
		             $content_thumbnail = getResizedThumb($content['thumbnail'], '122x66');		   
					 
		   ?>
               <li><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><img src="<?php echo $content_thumbnail;?>" alt="<?php echo $content['thumbnail_alt']?>"></a>
                        <figcaption>
                         <a href="<?php echo $content['guid']?>"><h3><?php echo $content['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>
			   <?php } ?>              
            </ul>
			<?php } ?>
            <div class="pagenume sky_pagi">                
				<?php echo $page_links; ?>
            </div>            
         </section>

