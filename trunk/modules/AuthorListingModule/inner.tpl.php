<?php
$thumbnail = getResizedThumb($author_data['thumbnail'], '150x150'); 
if(empty($author_data['thumbnail'])){
	$thumbnail = IMAGES.'/noimage_author.jpg'; 
}
$bg_thumbnail = getResizedThumb($author_data['author_big_thumbnail'], '150x150'); 
if(empty($author_data['author_big_thumbnail'])){
	$bg_thumbnail = IMAGES.'/auther_bg.jpg';
}
?>
<section class="newsSet">
        	<div class="image"><img src="<?php echo $bg_thumbnail;?>"/></div>
            <div class="clr"></div>
            <div class="bg_grey">&nbsp;</div>
            <div class="brand_top">
                <a href="javascript:;" class="auther_logo lc"><img src="<?php echo $thumbnail?>" /></a>
                <h1><?php echo $author_data['name']?></h1>
                <div class="social rc">
                    <a class="fbs sprite_img" target="_blank" href="<?php echo $author_data['facebook'];?>">facebook</a>
                    <a class="twitters sprite_img" target="_blank" href="<?php echo $author_data['twitter'];?>">twitter</a>
                    <a class="gpluss sprite_img" target="_blank" href="<?php echo $author_data['websiteurl'];?>">gplus</a> 					
               </div>
                
            </div>
            
        </section>
        <div class="clr"></div>
       <section class="newsSet sky_bd">
           <?php if(!empty($articles)) {?>
        	<ul>
			 <?php foreach($articles as $key =>$content){
			        $content_thumbnail = getResizedThumb($content['thumbnail'], '122x66');	 
			 ?>
               <li><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><img src="<?php echo $content_thumbnail; ?>" alt="<?php echo $content['thumbnail_alt'];?>"></a>
                        <figcaption>
                         <a href="<?php echo $content['guid']?>"><h3><?php echo $content['carousal_headline']?></h3></a>
                        </figcaption>
               </figure></li>
             <?php } ?>
            </ul>
			<?php } ?>
            <div class="clr"></div>
             <div class="pagenume sky_pagi">
                <?php 
			    echo $page_links;				
				?> 
            </div>
        </section>