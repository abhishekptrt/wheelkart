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
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_Home/Indiatimes_MWeb_Home_Home/IT_HP_ATF', [320, 50], 'div-atf').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_Home/Indiatimes_MWeb_Home_Home/IT_HP_BTF', [[320, 50], [320, 192]], 'div-btf').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_Home/Indiatimes_MWeb_Home_Home/IT_HP_BTF1', [[320, 50], [320, 192]], 'div-btf1').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_Home/Indiatimes_MWeb_Home_Home/IT_HP_BTF2', [[320, 50], [320, 192]], 'div-btf2').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_Home/Indiatimes_MWeb_Home_Home/IT_HP_FBN', [[320, 50], [320, 192]], 'div-fbn').addService(googletag.pubads());
googletag.defineSlot('/7176/INDIATIMES_MWeb/INDIATIMES_MWeb_Home/Indiatimes_MWeb_Home_Home/IT_HP_MTF', [[320, 50], [320, 192]], 'div-mtf').addService(googletag.pubads());
googletag.pubads().setTargeting('sg', _auds).setTargeting('HDL', _tval(_HDL)).setTargeting('ARC1', _tval(_ARC1)).setTargeting('Hyp1', _tval(_Hyp1)).setTargeting('article', _tval(_article));
googletag.pubads().enableSingleRequest();
googletag.pubads().setTargeting('article', '');
googletag.pubads().collapseEmptyDivs();
googletag.enableServices();
});
