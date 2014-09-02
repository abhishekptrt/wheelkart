<?php 
		    $slab_tags[1]['START_TAG'] = '__TEXTSLABSTART__';
			$slab_tags[1]['END_TAG'] = '__TEXTSLABEND__';
			$slab_tags[2]['START_TAG'] = '__FLUIDSLABSTART__';
			$slab_tags[2]['END_TAG'] = '__FLUIDSLABEND__';
			$slab_tags[3]['START_TAG'] = '__FIXEDSLABSTART__';
			$slab_tags[3]['END_TAG'] = '__FIXEDSLABEND__';
			$slab_tags[4]['START_TAG'] = '__RELATEDSTART__';
			$slab_tags[4]['END_TAG'] = '__RELATEDEND__';
?>
<script>
var data = '<?php echo $related_slabs ?>';
</script>
<div id="article">
<section class="article">
           
            <?php include "top.tpl.php"; ?>             
           <div class="clr"></div>
             <!-- grey container start -->	
			 <?php
			     if (!empty($article['content_slab_list'])) {
                    $i = 1;
                    foreach ($article['content_slab_list'] as $key => $slab) { 
                        if (empty($slab))
                            continue; 
                        $slab_text = getTextBetweenTags($slab, $slab_tags[1]['START_TAG'], $slab_tags[1]['END_TAG']);						
                        $slab_fluid_text = getTextBetweenTags($slab, $slab_tags[2]['START_TAG'], $slab_tags[2]['END_TAG']);
                        $slab_fixed_text = getTextBetweenTags($slab, $slab_tags[3]['START_TAG'], $slab_tags[3]['END_TAG']);
                        $slab_related_text = getTextBetweenTags($slab, $slab_tags[4]['START_TAG'], $slab_tags[4]['END_TAG']);
                        print $slab_text ? '<div class="grey_cont">' . $slab_text . '</div>' : '';
                        print $slab_fluid_text ? '<div class="grey_cont">' . $slab_fluid_text . '</div>' : '';
                        print $slab_fixed_text ? '<div class="grey_cont">' . $slab_fixed_text . '</div>' : '';
                        if (!empty($slab_related_text)) {
                            $pattern = '/>__CONTENT__(.*)__/';
                            preg_match($pattern, $slab_related_text, $matches);
                            $related_content_id = $matches[1];
                            $related_link = Content::getContentlink($related_content_id);
                            echo $related_link ? '<div class="grey_cont">
							<span class="story">Related Story</span> <span class="story_txt">' . $related_link . '</span></div>' : '';
                        }
                        $i++;
                    }
                }
				?>            
            <div class="clr"></div>
            <!-- grey container closed -->
            <?php include "social.tpl.php"; 
			      include "outbrain.tpl.php"; 
			 ?> 
           
        </section>    
	</div>	
        <div class="clr"></div>
        <section class="AddSection" id="div-fbn">
		  <script type='text/javascript'>
		  googletag.cmd.push(function() { googletag.display('div-fbn'); });
		 </script>
        </section>      
		<div id="container">
		</div>
		<div id="loading" style="text-align: center;margin:0 auto;">
		<img src="<?php echo IMAGES.'/loading-anim.gif'?>" />
		</div>
<script type="text/javascript">
var googletag = googletag || {};
googletag.cmd = googletag.cmd || [];
(function() {
	var gads = document.createElement("script");
	gads.async = true;
	gads.type = "text/javascript";
	var useSSL = "https:" == document.location.protocol;
	gads.src = (useSSL ? "https:" : "http:") + "//www.googletagservices.com/tag/js/gpt.js";
	var node =document.getElementsByTagName("script")[0];
	node.parentNode.insertBefore(gads, node);
})();
</script>
<script type='text/javascript'>
googletag.cmd.push(function() { 
 <!-- Audience Segment Targeting --> 
 var _auds = new Array(); 
 if(typeof(_ccaud)!='undefined') { 
 for(var i=0;i<_ccaud.Profile.Audiences.Audience.length;i++) 
 if(i<200) 
 _auds.push(_ccaud.Profile.Audiences.Audience[i].abbr); 
 } 
 <!-- End Audience Segment Targeting --> 
 <!-- Contextual Targeting --> 
 var _HDL = ''; 
 var _ARC1 = ''; 
 var _Hyp1 = ''; 
 var _article = ''; 
 var _tval = function(v) { 
 if(typeof(v)=='undefined') return ''; 
 if(v.length>100) return v.substr(0,100); 
 return v; 
 }

googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_News/Indiatimes_MWeb_News_AS/IT_ROS_ATF_NWS_AS', [320, 50], 'div-atf').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_News/Indiatimes_MWeb_News_AS/IT_ROS_BTF_NWS_AS', [320, 50], 'div-btf').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_News/Indiatimes_MWeb_News_AS/IT_ROS_FBN_NWS_AS', [[320, 50], [320, 192]], 'div-fbn').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_News/Indiatimes_MWeb_News_AS/IT_ROS_MTF_NWS_AS', [320, 50], 'div-mtf').addService(googletag.pubads());
googletag.pubads().setTargeting('sg', _auds).setTargeting('HDL', _tval(_HDL)).setTargeting('ARC1', _tval(_ARC1)).setTargeting('Hyp1', _tval(_Hyp1)).setTargeting('article', _tval(_article));
googletag.pubads().enableSingleRequest();
googletag.pubads().setTargeting('article', '');
googletag.pubads().collapseEmptyDivs();
googletag.enableServices();
});
</script>
