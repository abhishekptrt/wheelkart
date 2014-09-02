 
 <section class="slider">
          <div class="flexslider">
		    <?php if(!empty($top_box_data)){?>
            <ul class="slides">
			  <?php foreach($top_box_data as $key => $slide){
			    $slide_image = getResizedThumb($slide['thumbnail'], '340x188');	  
			  ?>
              <li><a href="<?php echo $slide['guid']?>"> <img src="<?php echo $slide_image ?>" /> </a> <a href="<?php echo $slide['guid']?>" class="slidertxt"><?php echo $slide['carousal_headline']?></a> </li>
			  <?php } ?>              
            </ul>
			<?php } ?>
          </div>
        </section>
        <div class="clr"></div>        
        <section class="AddSection" id='div-mtf'>

			<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-mtf'); });
			</script>
		
        </section>        
        <section class="newsSet sky_bd" id="section_latest">
        	<h2><span class="sky">recent</span></h2>
			<?php if(!empty($latest_contents)){?>
        	<ul>    
			  <?php 
			  $counter = 1;
			  foreach($latest_contents as $key =>$content){
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
			<?php } ?>
            <div class="clr"></div>
            <div class="load_more sky_load"  data-id="latest"><a href="javascript:;">load more</a></div>
        </section>
        
         <section class="AddSection" id="div-btf">            
		 
			<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-btf'); });
			</script>
        </section>
        <div class="clr"></div>
        <section class="video orange_bd" id="section_video">
        	<h2><span class="orange">video</span></h2>
			<?php if(!empty($video_contents)){?>
        	<ul>
			 <?php 
			 $counter = 1;
			 foreach($video_contents as $key => $content){
			        $content_thumbnail = getResizedThumb($content['thumbnail'], '140x105');   	 
			  ?>
               <li style="display:<?php echo ($counter > 4)? 'none':'block' ?>;"><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><span class="sprite_img"></span><img src="<?php echo $content_thumbnail; ?>" alt="<?php echo $content['thumbnail_alt'];?>"></a>
                        <figcaption>
                         <a href="<?php echo $content['guid']?>"><h3><?php echo $content['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>
			   <?php $counter++;  } ?>
            </ul>
			<?php } ?>
            <div class="clr"></div>
             <div class="load_more orange_load"  data-id="video"><a href="javascript:;">load more</a></div>
        </section>
        
         <section class="AddSection" id="div-btf1">
		 
			<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-btf1'); });
			</script>
        </section>
        
        <section class="newsSet sky_bd" id="section_populer">
        	<h2><span class="sky">popular</span></h2>
			<?php if(!empty($populer_contents)){?>
			<ul>
        	<?php
			$counter = 1;
			foreach($populer_contents as $key => $content){
			        $content_thumbnail = getResizedThumb($content['thumbnail'], '140x105');   	 
			  ?>
               <li  style="display:<?php echo ($counter > 4)? 'none':'block' ?>;"><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><img src="<?php echo $content_thumbnail; ?>" alt="<?php echo $content['thumbnail_alt'];?>"></a>
                        <figcaption>
                         <a href="<?php echo $content['guid']?>"><h3><?php echo $content['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>
			   <?php $counter++; } ?>
			     </ul>
			<?php } ?>
            <div class="clr"></div>
            <div class="load_more sky_load"  data-id="populer"><a href="javascript:;">load more</a></div>
        </section>
        <section class="AddSection" id="div-btf2">		
			<script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('div-btf2'); });
			</script>
        </section>
        <section class="gallery dark_pink_bd"  id="section_gallery">
        	<h2><span class="dark_pink">gallery</span></h2>
			<?php if(!empty($latest_galleries)){?>
        	<ul>
			<?php
			 $counter = 1;
			 foreach($latest_galleries as $key => $gallery){
				   $gallery_thumbnail = getResizedThumb($gallery['thumbnail'], '140x105');
				?>
                <li style="display:<?php echo ($counter > 4)? 'none':'block' ?>;"><figure class="container">
                    	<a href="<?php echo $gallery['guid']?>" class="FigImg"><img src="<?php echo $gallery_thumbnail; ?>" alt="<?php echo $gallery['thumbnail_alt'];?>"></a>
                        <figcaption>
                         <a href="<?php echo $gallery['guid']?>"><h3><?php echo $gallery['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>   
			   <?php $counter++;} ?>
            </ul>
			<?php } ?>
            <div class="clr"></div>
            <div class="load_more dark_pink_load"  data-id="gallery"><a href="javascript:;">load more</a></div>
        </section>