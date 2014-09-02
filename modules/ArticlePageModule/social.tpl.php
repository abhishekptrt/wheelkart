<?php
$fb_title = $article['headline1'];
$fb_desc = $article['summary'];
$share_count = formatNumber($article['ShareCount'])
?>
<div class="social share" id="social_<?php echo $article['id']?>">
                <?php if(intval($article['ShareCount']) > 100){ ?>
           		<span class="like <?php echo getsectioncolor($article['section_parentid'])?>"><?php echo formatNumber($article['ShareCount'])?></span>
                <span class="shares">shares</span>
				<?php } ?>
                <a href="#" data-social='{"type":"facebook", "url":"<?php echo $article['guid']; ?>", "text": "<?php echo htmlspecialchars($fb_title, ENT_QUOTES) ?>", "image":"<?php echo $fb_img; ?>","desc":"<?php echo htmlspecialchars($fb_desc, ENT_QUOTES); ?>"}' class="fb_art sprite_img">facebook</a>
            	<a href="#" data-social='{"type":"twitter", "url":"<?php echo $article['guid']; ?>", "text": "<?php echo $article['headline1'] ?>"}' class="twitter_art sprite_img">twitter</a>
                <!-- <a href="javascript:void(0);" class="gmail sprite_img" onclick="showMessage(this);">gmail</a> -->

				<a class="gplus_art sprite_img wa_btn wa_btn_s" data-href="<?php echo "http://www.indiatimes.com". str_replace('?call=ajax', '',$_SERVER['REQUEST_URI'])?>" data-text="Check out this link: " href="whatsapp://send" style="display:none;"></a>
				<script type="text/javascript">if(typeof wabtn4fg==="undefined"){wabtn4fg=1;h=document.head||document.getElementsByTagName("head")[0],s=document.createElement("script");s.type="text/javascript";s.src="//whatsapp-sharing.com/button";h.appendChild(s);}</script>
           </div>
		   <div class="clr"></div>
		   