<?php 
$pg =  !empty($_GET['page']) ? $_GET['page']: 1; 
$index = ($pg -1);
 if($article['media']['data_count'] > 0){
   foreach($article['media']['data'] as $key => $data){	 
	   if(!empty($article['daylife_gallery_id'])) {	
		   $thumbnail = str_replace('650x','350x', $article['media']['data'][$key]['thumbnail']);
		} else {
			$thumbnail = getResizedThumb($article['media']['data'][$key]['thumbnail'], '350x350');
		}
       $article['media']['data'][$key]['caption'] = strip_tags($article['media']['data'][$key]['caption']);
	   $article['media']['data'][$key]['page_url'] = getPagingGuid( $article[ 'guid' ], intval($key +1));
	   $article['media']['data'][$key]['thumbnail'] = $thumbnail;
   }
}
  $thumbnail = null;
if(!empty($article['daylife_gallery_id'])) {	
   $thumbnail = str_replace('650x','350x', $article['media']['data'][$index]['thumbnail']);
} else {
	$thumbnail = $article['media']['data'][$index]['thumbnail'];
}

$data_current = intval($index + 1);
$media_json = json_encode($article['media']['data'], JSON_HEX_AMP);
?>
<section class="article">
          <?php include "top.tpl.php";?>
            <div class="clr"></div>
        </section>
        <div class="clr"></div>
        <section>
        	<div class="numbers"> 
            	<a class="lc previous" href="javascript:void(0);"><span class="left_a"></span> Previous</a>
                <a class="rc next" href="javascript:void(0);"><span class="right_a"></span> Next</a>
                <span id ="counter_text">&nbsp; </span>
           </div>
        	<div class="ArticleImg MImg" id="image_container" data-current="<?php echo $data_current?>" data-total="<?php echo count($article['media']['data']) ?>" >
            	<a class="left_arrow" href="javascript:void(0);"></a>
            	<a class="right_arrow" href="javascript:void(0);"></a>
            	<img id="main_img" src="<?php echo $thumbnail;?>" />
        	</div>
        </section>
        <div class="clr"></div>
          <section class="article">
		    <p id="img_caption"><?php echo $article['media']['data'][$index]['caption']; ?></p>
            <?php $social_bottom = 1; include "social.tpl.php"; ?>
  		   <?php	include "outbrain.tpl.php";  ?>
           <div class="image" id="facebook_comment"></div>
		   <input type="hidden" name="media_data" id="media_data" value="<?php echo htmlspecialchars($media_json); ?>"/>
           <div class="clr"></div>
        </section>
 	 	<?php include "related.tpl.php"; ?>

	