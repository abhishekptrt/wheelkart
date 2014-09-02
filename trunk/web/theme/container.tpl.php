<div class="layout">
<section class="AddSection" id ="div-atf"> 
	<script type='text/javascript'>
	googletag.cmd.push(function() { googletag.display('div-atf'); });
	</script>
 </section>
  <?php echo $header;?>    
   <?php 
      if ( isset($array_middle_modules) and (count( $array_middle_modules ) > 0) ) {
        foreach ( $array_middle_modules as $middle_module ) {
          echo $middle_module;
        }
      }
    ?>    
  <?php echo $footer;?>
    
</div>
</body>
<?php if(!empty($_GET['id'])) { ?>
<script type="text/javascript">if(typeof wabtn4fg==="undefined"){wabtn4fg=1;h=document.head||document.getElementsByTagName("head")[0],s=document.createElement("script");s.type="text/javascript";s.src="//www.whatsapp-sharing.com/button";h.appendChild(s);}</script>
<div id="fb-root"></div>
	<script>(function(d, s, id) {
	  var js, fjs = d.getElementsByTagName(s)[0];
	  if (d.getElementById(id)) return;
	  js = d.createElement(s); js.id = id;
	  js.src = "//connect.facebook.net/en_US/sdk.js#xfbml=1&appId=117787264903013&version=v2.0";
	  fjs.parentNode.insertBefore(js, fjs);
	}(document, 'script', 'facebook-jssdk'));</script>
<?php } ?>
<?php
$js = array();
$js[] = 'script.js'; 
$js[] = 'jquery.flexslider.js';
$js[] = 'main.js';
$js[] = 'jquery.dd.js';
echo cache_js($js);
?>