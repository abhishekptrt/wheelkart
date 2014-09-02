 <style>
		.flex-direction-nav{display:none}
</style>
  <section class="slider">
          <div class="flexslider">
            <?php if(!empty($top_box_data)){?>
            <ul class="slides">
			  <?php foreach($top_box_data as $key => $slide){
			    $slide_image = getResizedThumb($slide['thumbnail'], '340x188');	  
			  ?>
              <li><a href="<?php echo $slide['guid']?>"> <img src="<?php echo $slide_image ?>" /> </a> <a href="<?php echo $slide['guid']?>" class="slidertxt"><?php echo $slide['headline1']?></a> </li>
			  <?php } ?>              
            </ul>
			<?php } ?>
          </div>
        </section>
        <div class="clr"></div>
        
		 <?php 
		    $ad_class =array('div-mtf','div-btf','div-btf1','div-btf2','div-btf3');
			$ad_counter =0;
			 if(!empty($childSectionArr['data'])){ 
			 foreach( $childSectionArr['data'] as $key => $section){ 
			 $url = getsectionlink($section['id'], $section['name']);
			  $thumbnail = getResizedThumb($v['thumbnail'], '122x66');
			 ?>  
         <?php  $i = 0; if($section['sub_section_data']['data_count'] > 0){ ?>   
       <section class="AddSection" id="<?php echo $ad_class[$ad_counter]?>">
	     <script type='text/javascript'>
			googletag.cmd.push(function() { googletag.display('<?php echo $ad_class[$ad_counter]; ?>'); });
			</script>
        </section>
        <section class="newsSet <?php echo getsectioncolor(intval($_GET['section_id']))?>_bd" id ="section_<?php echo  $section['id'];?>">
        	<h2><a href="<?php echo $url ?>"><span class="<?php echo getsectioncolor(intval($_GET['section_id']))?>"><?php echo $section['name'];?></span> </a></h2>
			
        	<ul>
			 <?php
			 $counter = 1;
			   foreach($section['sub_section_data']['data'] as $k=> $v){
				    $headline = empty($v['carousal_headline']) ? $v['headline1'] :  $v['carousal_headline'];
					$thumbnail = getResizedThumb($v['thumbnail'], '122x66');
		    ?>
               <li style="display:<?php echo ($counter > 4)? 'none':'block' ?>;" ><figure class="container">
                    	<a href="<?php echo $v['guid']?>" class="FigImg"><img src="<?php echo $thumbnail;?>" alt=""></a>
                        <figcaption>
                         <a href="<?php echo $v['guid']?>"><h3><?php echo $headline;?></h3></a>
                        </figcaption>
               </figure></li>
             <?php $counter++; }  ?>
                
            </ul>
            <div class="clr"></div>
            <div class="load_more <?php echo getsectioncolor(intval($_GET['section_id']))?>_load"  data-id ="<?php echo $section['id']; ?>" ><a href="javascript:;" >load more</a></div>
			
        </section>                 
		<?php } ?>
		<?php $ad_counter++;}  } ?>