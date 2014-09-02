/* Author : Times Internet Ltd.
Coded by: Vinay Rajput
Started:16th APril  january 2013 */

$(document).ready(function(){
		 $(".MoreLoad").click(function(){
   			 $(".MoreSites").toggle(500);
			 $(this).remove();
  		});

		try{				
					articleText=function(){
					$('.inc').click(function() {
						var curFontSize = $('.ArticleCont p').css('font-size');
						$('.ArticleCont p').css('font-size', parseInt(curFontSize)+1);
						$(window).resize();
					});
					$('.dec').click(function() {
						var curFontSize = $('.ArticleCont p').css('font-size');
						$('.ArticleCont p').css('font-size', parseInt(curFontSize)-1);
						$(window).resize();
					});
				}
			
				resizeVideo=function(){
						var obj=$('iframe.youtube-player, .ArticleImg embed, .ArticleImg iframe, .ArticleCont iframe');
						var videoW=obj.attr('width'); 
						var videoH=obj.attr('height');
						var videoProspectiveRatio=obj.width()/videoW;
						obj.height(videoH*videoProspectiveRatio);
				}
				$(document).ready(resizeVideo);
				$(document).ready(articleText);
				$(window).resize(resizeVideo);

				$("div.SocialShare a.message").live('click',function(e){
					$("div.EmailMessage").slideDown("fast");
					$(this).addClass('clicked');
					})
				$("div.EmailMessage a.closebtn").live('click',function(e){
					$('div.EmailMessage').slideUp('fast');
					$("div.SocialShare a.message").removeClass('clicked');
					})

			/*$("div.SocialShare a.message").click(function () {
				$("div.EmailMessage").slideDown("fast");
				$(this).addClass('clicked');
			});
						
			$('div.EmailMessage a.closebtn').click(function(){
				$('div.EmailMessage').slideUp('fast');
				$("div.SocialShare a.message").removeClass('clicked');
			});*/

		}catch(e){}
		 
});