<section> 
            <div class="quiz_container mO">
                <div class="quiz_section">
                    <div class="big_image">
					
					                <div class="listing_bg" <?php if(!empty($section_data['section_big_thumbnail']) && $section_data['section_big_thumbnail']!='null'){?>style="height:120px; background:url('<?php echo getResizedThumb($section_data['section_big_thumbnail'], '400x300');?>') no-repeat 40% 0" <?php } ?>>
                    </div>
                    <div class="quiz_qust">
                        <h1 class="quiz_qusts txt_shadow"><?php echo $section_data['name'];?></h1>
                    </div>
                    <div class="clr"> </div>
                </div>
              </div>  
         </section>
         
         <section class="newsSet <?php echo getsectioncolor($section_data['parentid'])?>_bd">
         	<div class="grey_conts">&nbsp;</div>
            <div class="centre">
            	<form method="post">
                    <select style="width:85%; background:#fff;" name="tech" id="tech" onchange="filterArticle(this.value);">
                      <option value="<?php echo $nav_all; ?>">All</option>
                      <option value="<?php echo $nav_all; ?>contype_article" <?php print ($contype_value == 'article') ? 'Selected=Selected' :''; ?> data-image="<?php echo IMAGES ?>/icons/article.png">Articles</option>
                       <option value="<?php echo $nav_all; ?>contype_photogallery"  <?php print ($contype_value == 'photogallery') ? 'Selected=Selected' :''; ?> data-image="<?php echo IMAGES ?>/icons/photo.png">Photos</option>
                      <option value="<?php echo $nav_all; ?>contype_quiz"  <?php print ($contype_value == 'quiz') ? 'Selected=Selected' :''; ?> data-image="<?php echo IMAGES ?>/icons/quiz.png">Quizzes</option>                     
                      <option value="<?php echo $nav_all; ?>contype_video"  <?php print ($contype_value == 'video') ? 'Selected=Selected' :''; ?> data-image="<?php echo IMAGES ?>/icons/video.png">Videos</option>
                    </select>
                </form>
            </div>
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
            <div class="pagenume <?php echo getsectioncolor($section_data['parentid'])?>_pagi" >                
				<?php echo $page_links; ?>
            </div>
            
         </section>

