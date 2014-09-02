<section class="article">
<?php			
$image = showImage($article); 
$article['description'] = preg_replace_callback("/(<img[^>]*src *= *[\"']?)([^\"']*)(.*?\/>)/i", getURL, $article['description']);
if(!empty($image)){ ?>
	<div class="image"><?php echo $image;?></div>
<?php } ?>

	<?php     include "top.tpl.php"; ?>
<div class="clr"></div>
	<?php print $article['description']; ?>
<?php
$social_bottom = 1;
include "social.tpl.php"; 
include "outbrain.tpl.php"; 

?>
<div class="image" id="facebook_comment"></div>           
</section>
 <section class="AddSection" id="div-btf">
  <script type='text/javascript'>
		  googletag.cmd.push(function() { googletag.display('div-btf'); });
 </script>
 </section>
<?php include "related.tpl.php"; ?>