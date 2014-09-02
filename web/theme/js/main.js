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


var is_loading = 0;
var counter = 0;

$(document).ready(function() {		        
/* Slab scroll code */
$(window).scroll(function() {
var scroll_pos = $(window).scrollTop();
if(scroll_pos){
	if (scroll_pos >= 0.9 * ($(document).height() - $(window).height())) {
		if (is_loading == 0) {
			var obj = jQuery.parseJSON( data );						
			if(obj[counter].guid){
				loadPage(obj[counter].guid);
				history.pushState(null, null, obj[counter].guid);
				counter++;							
			}
		}
	}
	if (Math.abs(scroll_pos - last_scroll) > $(window).height() * 0.1) {
		last_scroll = scroll_pos;
	}
}
});
/* Facebook comment we need to just create id="facebook_comment" */
if ($('#facebook_comment').length) { 
	var url = $(location).attr('href');      
	var html = '<div class="fb-comments" data-href="' + url + '" data-numposts="5" data-colorscheme="light"></div>';
	$(html).appendTo("#facebook_comment");
}
/* Invoking share plugin */
$(".share a").jqSocialSharer();

/* hide extra li's in Load mode on section and home page */
if($('[id^="section_"]').length){
	$('[id^="section_"]').each(function() {		  
		   $(this).children('ul').children().slice(4).hide(); 
	});
}
/*Load more common code */
$( ".load_more" ).each(function() {
 var x =4; y=4;
  $(this).click(function(){  
	 var id = $(this).attr("data-id"); 
	 var div_id ='section_'+id;
     size_li = $("#"+div_id+" li").size(); 
	 if($('#'+div_id+' li:last-child').is(':hidden')){
		    x = ( x+y <= size_li) ? x+y : size_li;
			$('#'+div_id+' li:lt('+x+')').show();
			
        }
		/*Remove load more in case of lifestyle and videocafe latest section block*/
		if($('#'+div_id+' li:last-child').is(':visible'))
		{
			$('#'+div_id+' .load_more').remove();
		}		
   }); 
});

/* Subscription Form Validation Javascript*/
$('#btn-submit').click(function() {
var hasError = false;
var emailReg = /^([\w-\.]+@([\w-]+\.)+[\w-]{2,4})?$/;
var emailaddressVal = $("#sub_email").val();
if (emailaddressVal == '' || $.trim(emailaddressVal) == 'Enter email for updates') {
	$("#sub_email").addClass('er');			
	$("#sub_email").val('');
	hasError = true;
} else if (!emailReg.test(emailaddressVal)) {			
	$("#sub_email").addClass('er');			
	$("#sub_email").val('');
	hasError = true;
}
if (hasError == true) {
	return false;
} else {			
	$.post( base_url+'/ajax/saveNewsletter.php',
		{
			email : emailaddressVal
		},
		function(data) {	
			var res = eval("("
					+ data
					+ ")");
			if (res.result == 'success') {
				$('#subs_message').html('<span class="green-error">Subscription successful</span>');
			} else if (res.result == 'before') {
				$('#subs_message').html('<span class="green-error">Already subscribed</span>');
			} else {
				$('#subs_message').html('<span class="red-error">Subscription failed</span>');
			}
			$("#sub_email").val('Please Enter Email Address');
		});
}
});
	// jQuery("img.lazy").lazy({ delay: 2000 });
  
});

var loadedIDsArray = [];
function loadPage(url){ 
	is_loading = 1;
	$('#loading').show();
	$.get(url, 		
	function(data) {	
		loadedIDsArray.push(url);
		$('#container').append($(data).find('#article').html());			
		is_loading = 0;		
		$('#loading').hide();
		$(".share a").jqSocialSharer();
	});
}


function multiEmail(email_field) {
var email = email_field.split(',');
ctr = email.length;
if(ctr > 10){
	alert('Only 10 email ids allowed.');
	return false;
}
for (var i = 0; i < ctr; i++) {
	strEmail = email[i].replace(/^\s+|\s+$/g,"");
	if (!isValidEmail(strEmail)) {
		return false;
	}
}
return true;
} 

function isValidEmail(str) {
	var emailRe = /^\w+([\.-]?\w+)*@\w+([\.-]?(\w)+)*\.(\w{2}|(com|net|org|edu|int|mil|gov|arpa|biz|aero|name|coop|info|pro|museum))$/;
	if (!emailRe.test(str))
		return false;
	else
		return true;
}

function submitSearchForm(){ 
  var query = $('#search').val();
  if(query !=''){ 
   var url = base_url+'/search/'+query;
    window.location.href= url;
  }
  return false;
}


function feedbackSubmit(){ 
  var errMsg = 0;    
  var strEmail =  $('#email').val(); 
	if(strEmail == 'Enter Your email id here' || strEmail == '' || (!isValidEmail(strEmail)) ){  
     $('#email').addClass('er');
		return false;
	}else{
      $('#email').removeClass('er');
      
  }
	var strName = $('#name').val(); 
    if(strName == '' ||  strName =='Enter your full name here'){        
		  $('#name').addClass('er');
		return false;
 }else{
      $('#name').removeClass('er');
      
  }
 var strMessage = $('#message').val(); 
    if($.trim(strMessage) == '' || strMessage == 'write your feedback here....'){        
		  $('#message').addClass('er');
		return false;
 }else{
     $('#message').removeClass('er');
 }
 $.ajax({
        type: "POST",
        url:  base_url +'/ajax/sendfeedback.php',
        data: $("#FeedbackForm").serialize(),
        success: function(htmlData){  
          $('#FeedbackForm').each (function(){
            this.reset();
          });
			    $('#thanks').show();
        } 
    });

	return false;
}

function Get_Cookie( name ){
  var start = document.cookie.indexOf( name + "=" );
  var len = start + name.length + 1;
  if ( ( !start ) && ( name != document.cookie.substring( 0, name.length ) ) ){
    return null;
  }
  if ( start == -1 ) return null;
  var end = document.cookie.indexOf( ";", len );
  if ( end == -1 ) end = document.cookie.length;
  return unescape( document.cookie.substring( len, end ) );
}
function Delete_Ckie(name,path,domain){
  if(Get_Cookie(name))document.cookie=name+"="+((path)? ";path="+path : "")+((domain)? ";domain="+domain : "")+";expires=Thu, 01-Jan-1970 00:00:01 GMT";
}
function readCookie(name) {
	var nameEQ = name + "=";
	var ca = document.cookie.split(';');
	for(var i=0;i < ca.length;i++) {
		var c = ca[i];
		while (c.charAt(0)==' ') c = c.substring(1,c.length);
		if (c.indexOf(nameEQ) == 0) return c.substring(nameEQ.length,c.length);
	}
	return null;
}
function Set_Cookie( name, value, expires, path, domain, secure ){
  var today = new Date();
  today.setTime( today.getTime() );
  if ( expires )
  {
    expires = expires * 1000 * 60 * 60 * 24;
  }
  var expires_date = new Date( today.getTime() + (expires) );
  document.cookie = name + "=" +escape( value ) +
  ( ( expires ) ? ";expires=" + expires_date.toGMTString() : "" ) +
  ( ( path ) ? ";path=" + path : "" ) +
  ( ( domain ) ? ";domain=" + domain : "" ) +
  ( ( secure ) ? ";secure" : "" );
}

$(document).ready(function(e) {		
	        if($("#tech").length > 0){
				$("select").msDropdown({roundedBorder:false});
				createByJson();
				$("#tech").data("dd");
			}
});


function filterArticle(val){	
	window.location.href = val;
}
function main_validateSubmit(){ 
	var errMsg = 0;    
	if($('#fromName').val() == ''){ 
		$('#errName').html('Please enter your name');
		$('#fromName').focus();
		return false;
	} else {
		$('#errName').html('&nbsp;');
	}
	var strEmail = $('#fromEmail').val(); 
    if(strEmail == ''){ 
        $('#errEmail').html('Please enter your email address');
		$('#fromEmail').focus();
		return false;
    } else if(multiEmail(strEmail) == false){ 
        $('#errEmail').html('Please enter valid email address');
		$('#fromEmail').focus();
		return false;
    } else {
		$('#errEmail').html('&nbsp;');
	}
	var strFrEmail = $('#toEmail').val();
    if(strFrEmail == ''){ 
         $('#errFrEmail').html('Please enter friend\'s email');
		$('#txtFrEmail').focus();
		return false;
    } else if(multiEmail(strFrEmail) == false){ 
        $('#errFrEmail').html('Please enter valid friend\'s email');
		$('#txtFrEmail').focus();
		return false;
    } else {
	 	$('#errFrEmail').html('&nbsp;');
	}
	if($('#message').val() == ''){
	 	$('#errMsg').html('Please enter message');
		$('#message').focus();
		return false;
	} else {
		$('#errMsg').html('&nbsp;');
	} 
  
	$.ajax({
        type: "POST",
        url:  base_url +'/ajax/sendemail.php',
        data: $("#EmailMessageForm").serialize(),
        success: function(htmlData){   
			$('#msg').html(htmlData);
			$('#EmailMessageForm').each (function(){ this.reset();}); 
			setTimeout(close_messagebox, 10000)
        }
    });

	return false;
}