flashBlockDetect = function(callbackMethod){
	
  var 
  		return_value = 0,
		videoNotSupport=$('<div class="VideoNotSupport">Video format not supported on your device</div>')
		;
	if ( navigator.plugins["Shockwave Flash"] ) {
		if (navigator.userAgent.indexOf('MSIE') > -1) {
			try {
			  new ActiveXObject('ShockwaveFlash.ShockwaveFlash');
			} catch (e) {
			  return_value = 2;
			}
		} else {
			
		  embed_length = $('embed').length;
			object_length = $('object').length;
			if( (embed_length > 0) || (object_length > 0) ){
				/* Mac / Chrome using FlashBlock + Mac / Safari using AdBlock */
//				alert("embed or object tag found \nembed_length:"+embed_length+"\nobject_length:"+object_length);
				$('object, embed').each(function() {
				  if( $(this).css('display') === 'none' ){
				    return_value = 2;
					}
				});			
			} else {
				/* Mac / Firefox using FlashBlock */
				if( $('div[bginactive]').length > 0 ){
				  return_value = 2;
				}
			}
		}
	} else {
//		alert('flash is not installed');
		/* If flash is not installed */  
		return_value = 1;
	}
	
	//(return_value==0) ? $('html').addClass('flash') : (return_value<2) ? $('html').addClass('no-flash') : $('html').addClass('flashBlocked') ;

	if(return_value==0) { 
		$('html').addClass('flash');
	}
	else if(return_value==1) { 
		$('html').addClass('no-flash');
		//$('.ArticleImg embed, .ArticleImg object').parent('.ArticleImg').after(videoNotSupport);
		$('.ArticleImg embed, .ArticleImg object').parent('.ArticleImg').remove();
		$('#no_flash_block').show();
		$('#flash_block').show();
		
	}else{ 
		$('html').addClass('flashBlocked');
	}
	  //return return_value;
}

$(window).load(function(){flashBlockDetect();});
