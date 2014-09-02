;(function($){
  $.fn.jqSocialSharer = function(options){
    
    //settings
    var settings = $.extend({
      "popUpWidth" : 550,               /*Width of the Pop-Up Window*/
      "popUpHeight": 450,               /*Height of the Pop-Up Window*/
      "popUpTop"   : 100,               /*Top value for Pop-Up Window*/
      "useCurrentLocation" : false      /*Whether or not use current location for sharing*/
    }, options);
    
    /*Attach this plugin to each element in the DOM selected by jQuery Selector and retain statement chaining*/
    return this.each(function(index, value){
      
      /*Respond to click event*/
      $(this).bind("click", function(evt){ 
        
        evt.preventDefault();
        
        /*Define*/
        var social = $(this).data('social'),
            width=settings.popUpWidth,
            height=settings.popUpHeight,
            sHeight=screen.height, 
            sWidth=screen.width, 
            left=Math.round((sWidth/2)-(width/2)), 
            top=settings.popUpTop, 
            url,
            useCurrentLoc = settings.useCurrentLocation,
            socialURL = (useCurrentLoc) ? window.location : encodeURIComponent(social.url),
			emailURL = social.url,
            socialText = social.text,
            socialImage = encodeURIComponent(social.image);
        
        switch(social.type){
            case 'facebook':  
                FB.ui(
          {
            method: 'feed',
            name: social.text,
            link: social.url,
            picture: social.image,
            caption: social.caption,
            description: social.desc
          },
          function(response) {
            
          }
        );
                break;
            case 'twitter':
                url = 'http://twitter.com/share?url='+ socialURL + '&text=' + socialText;
			    window.open(url, '', 'left='+left+' , top='+top+', width='+width+', height='+height+', personalbar=0, toolbar=0, scrollbars=1, resizable=1');         
                break;
            case 'plusone':
                url = 'https://plusone.google.com/_/+1/confirm?hl=en&url=' + socialURL;
			    window.open(url, '', 'left='+left+' , top='+top+', width='+width+', height='+height+', personalbar=0, toolbar=0, scrollbars=1, resizable=1');         
                break; 
			case 'email':
				//shareEmail(socialURL);
				ModalBoxNew.open(emailURL, 460, 580);
				//window.open(emailURL, '', 'left='+left+' , top='+top+', width='+width+', height='+height+', personalbar=0, toolbar=0, scrollbars=1, resizable=1');         
				break;
        }   
        /*Finally fire the Pop-up*/                 

      });
    });
  };
}(jQuery));