/* Author : Times Internet Ltd.
Coded by: Vinay Rajput
Started:16th APril  january 2013 */

try{
	$(".picCaption").hide();	

$(window).load(function(){
	var 
		picTrans= $('<span class="semiTrans"></span>'),
		clrSpan= $('<div class="clr"></div>'),
		easyCaptionDiv=$('<div class="easyCaptionDiv container"></div>'),
		img,
		parentDiv,
		hght
		;

	if($(".picCaption").length>0){
			$(".picCaption").show(function(){

				$(this).find('span:first').addClass('txtHolder').css({'width':'auto',height:'auto'})

				$(this).removeAttr('style').find('br').remove();

				if($(this).find(".semiTrans").length<1)
					$(this).append(picTrans);

				img=$(this).siblings('img')[0];
				
				$(img).wrap(easyCaptionDiv);
				
				parentDiv=$(this).parent().find('.easyCaptionDiv');
				
				parentDiv.prepend($(this)).after(clrSpan);

				hght=$(this).find('.txtHolder').outerHeight();

				$(this).height(hght);
				$(this).find(".semiTrans").height(hght).css({'margin-top':hght*-1,});
				$(this).css({'left':0})
				
		   });
		$(".picCaption").show();
		}
})

$(window).resize(function(){

	$(".picCaption").show(function(){
		hght=$(this).find('.txtHolder').outerHeight();
		$(this).height(hght);
		$(this).find(".semiTrans").height(hght).css({'margin-top':hght*-1,});

	})
})

}catch(e){}





