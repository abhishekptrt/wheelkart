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