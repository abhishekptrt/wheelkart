<section class="slider">
          <div class="flexslider">
            <?php if(!empty($top_box_data)){?>
            <ul class="slides">
			  <?php foreach($top_box_data as $key => $slide){
			    $slide_image = getResizedThumb($slide['thumbnail'], '340x188');	  
			  ?>
              <li><a href="<?php echo $slide['guid']?>"><span class="sprite_img"></span> <img src="<?php echo $slide_image ?>" /> </a> <a href="<?php echo $slide['guid']?>" class="slidertxt"><?php echo $slide['headline1']?></a> </li>
			  <?php } ?>              
            </ul>
			<?php } ?>
          </div>
        </section>
        <div class="clr"></div>        
         <section class="AddSection" id="div-mtf">
        </section>
        <?php if(!empty($latest_video)){ ?>
         <section class="video" id ="section_recent">
        	<h2><span class="orange">recent</span></h2>
        	<ul>
			 <?php foreach($latest_video  as $key => $content){
				      $content_thumbnail = getResizedThumb($content['thumbnail'], '122x66');
			  ?>
               <li style="display:<?php echo ($counter > 4)? 'none':'block' ?>;"><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><span class="sprite_img"></span><img src="<?php echo $content_thumbnail;?>" alt=""></a>
                        <figcaption>
                         <a href="javascript:;"><h3><?php echo $content['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>
			   <?php } ?>
            </ul>

            <div class="clr"></div>
		    <?php if(count($latest_video) > 4){ ?>
            <div class="load_more orange_load" data-id="recent"><a href="javascript:;">load more</a></div>
			<?php } ?>
			
        </section>
		<?php } ?>
        
         <section class="AddSection" id="div-btf">
        </section>
        <?php 
			 if(!empty($childSectionArr['data'])){ 
			 foreach( $childSectionArr['data'] as $key => $section){ 
			  $url = getsectionurl($section['id'], $section['name']);			 
			 ?> 
        <section class="video" id ="section_<?php echo $section['id']; ?>">
        	<h2><span class="orange"><?php echo $section['name'];?></span></h2>
        	<ul>
			<?php foreach($section['sub_section_data']['data'] as $k=> $v){
				    $headline = empty($v['carousal_headline']) ? $v['headline1'] :  $v['carousal_headline'];
					$thumbnail = getResizedThumb($v['thumbnail'], '122x66'); ?>
               <li><figure class="container">
                    	<a href="<?php echo $v['guid']?>" class="FigImg"><span class="sprite_img"></span><img src="<?php echo $thumbnail;?>" alt="<?php echo $v['thumbnail_alt'];?>"></a>
                        <figcaption>
                         <a href="<?php echo $v['guid']?>"><h3><?php echo $headline?></h3></a>
                        </figcaption>
               </figure></li>	
			   <?php } ?>
            </ul>
			
            <div class="clr"></div>
			<?php if(count($section['sub_section_data']['data']) > 4){ ?>
            <div class="load_more" data-id ="<?php echo $section['id']; ?>" ><a href="javascript:;">load more</a></div>
			<?php } ?>
        </section>
		<?php } } ?>