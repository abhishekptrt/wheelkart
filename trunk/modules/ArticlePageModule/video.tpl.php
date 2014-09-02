<?php 
$thumbnail = getResizedThumb($article['thumbnail'], '240x180');  
     if(false === strpos($article['object_code'], '.swf' )){
		 
	    preg_match('/src="([^"]+)"/', $article['object_code'], $match);
		$srcUrl = $match[1];

		if (strpos($srcUrl, 'youtube') !== false) {
			$video_palyer = null;
		    $videoParts = explode('/', $srcUrl);
		    $video_id = end($videoParts);
			$video_playerscript = <<<EOF
			       <script>
					// create youtube player
					var player;
					function onYouTubePlayerAPIReady() {
					player = new YT.Player('no_flash_block', {
					height: '200',
					width: '100%',
					videoId: '$video_id',
					playerVars: {
					autoplay :0,
					autohide :1,
					fs : 1,
					rel : 0,
					iv_load_policy :3,
					modestbranding : 1,
					showinfo :0,
					hd : 1},
					events: {
					'onReady': onPlayerReady,
					'onStateChange': onPlayerStateChange
					}
					});
					}

					// autoplay video
					function onPlayerReady(event) {

					}

					// when video ends
					function onPlayerStateChange(event) {
					if(event.data === 0) {					
					}
					}
					</script> 
EOF;

		} elseif (strpos($srcUrl, 'vimeo') !== false) {
            $video_palyer = '<iframe id="iframe"  width="100%" height="480" src="' . $srcUrl . '" frameborder="0" allowfullscreen></iframe>';
		}

	   
	 } else{
		  $pattern = '/flashvars=\"(.*?)\"/';
		  preg_match($pattern, $article['object_code'], $matches);		
		  $params = explode('&', $matches[1]);
		  $key_array = explode('=', $params[0]); 
		  $video_key = $key_array[1]; 
		  $video_url = 'http://www.kaltura.com/p/303932/sp/0/playManifest/entryId/'.$video_key.'/format/url/video.mp4';   
		  $article['flash_object_code'] = '<video width="100%" controls><source src="'.$video_url.'" type="video/mp4"></video>';		   

	 }	 

?>
 <section class="article">
     <?php include "top.tpl.php"; ?>
            <div class="clr"></div>

           <div class="image" id="no_flash_block" ><?php echo $video_palyer;?></div>
		   <div class="image" id="flash_block" style="display:none;">
			<?php echo $article['flash_object_code'];?>            
           </div>
           <p><?php echo $article['summary']?></p>          
            <?php include "social.tpl.php"; ?> 
			<?php include "outbrain.tpl.php";  ?>
		   <div class="image" id="facebook_comment"></div>           
        </section>    
		 <section class="AddSection" id="div-mtf">
		 <script type='text/javascript'>
		  googletag.cmd.push(function() { googletag.display('div-mtf'); });
		 </script>
        </section>
        <section class="video">
            	<p>today's top five</p>
                <div class="orange videos">videocafe</div>
            <?php if(!empty($video_contents)){?>
        	<ul>
			  <?php foreach($video_contents as $key => $content){
				       $content_thumbnail = getResizedThumb($content['thumbnail'], '122x66');
			  ?>
               <li><figure class="container">
                    	<a href="<?php echo $content['guid']?>" class="FigImg"><span class="sprite_img"></span><img src="<?php echo $content_thumbnail; ?>" alt="<?php echo $content['thumbnail_alt']?>"></a>
                        <figcaption>
                         <a href="<?php echo $content['guid']?>"><h3><?php echo $content['headline1']?></h3></a>
                        </figcaption>
               </figure></li>
			   <?php } ?>                           
            </ul>
            <?php } ?>
            <div class="clr"></div>            
        </section>	
  	   <section class="AddSection" id="div-btf">
	    <script type='text/javascript'>
		  googletag.cmd.push(function() { googletag.display('div-btf'); });
		 </script>
       </section>
		<?php include "related.tpl.php"; ?>
		<?php echo $video_playerscript;?>
	<script>
window.YT && onYouTubePlayerAPIReady() || function(){ 
    var a=document.createElement("script");
    a.setAttribute("type","text/javascript");
    a.setAttribute("src","http://www.youtube.com/player_api");
    a.onload=onYouTubePlayerAPIReady;
    a.onreadystatechange=function(){
        if (this.readyState=="complete"||this.readyState=="loaded") onYouTubePlayerAPIReady()
    };
    (document.getElementsByTagName("head")[0]||document.documentElement).appendChild(a)
}();
	</script>