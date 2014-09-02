         <?php if(!empty($article['label_name'])){?>  
         <div class="<?php echo getsectioncolor($article['section_parentid'])?> txt_head"><?php echo $article['label_name'];?> </div>
		 <?php } ?>            
           <h1><?php echo $article['headline1']; ?></h1>
           <div class="txt"> By <a href="<?php echo authorlinkdata($article['author_id'],$article['author_name'])?>" class="<?php echo getsectioncolor($article['section_parentid'])?>"><?php echo ' '.$article['author_name'];?></a> | Posted on <?php echo date("d M 'y",strtotime($article['publishdate'])); ?></div>
          <?php include "social.tpl.php"; ?>
           