 <section class="article">           
		              
			<?php						
			$image = showImage($article); 
			if(!empty($image)){ ?>
           <div class="image"><?php echo $image;?></div>
		   <?php } ?>	
		 <?php  include "top.tpl.php"; ?>
           <div class="clr"></div>
           <p><?php echo $article['summary']; ?></p>
          
		   <?php   if (!empty($article['current_list'])) {
            foreach ($article['current_list'] as $key => $list) {
			    $list = preg_replace_callback("/(<img[^>]*src *= *[\"']?)([^\"']*)(.*?\/>)/i", getURL, $list);
		   ?>
           <div class="heading_ro"> 
           		<?php echo $list['count'].'.'; ?>
           		<?php echo $list['title'] ?>
           </div>
		     <?php echo $list['content']; ?>
		   <?php } } ?>
          
          <?php  include "social.tpl.php"; 
		         include "outbrain.tpl.php"; 
		   ?>  
           <div class="image" id="facebook_comment"></div>           
        </section>
		<?php include "related.tpl.php"; ?>