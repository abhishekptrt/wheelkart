/* black menu */
   flag = 0;
   $('.icon-menu').on('click', function(){
		if(flag == 0){
			$('.menu_overlay').slideDown(200);
			$('.slidingDiv').css('display','none')
			flag = 1;
		}
		else{
			$('.menu_overlay').slideUp(200);
			flag = 0;
		}
   });
   
    gmail = 0;
   $('.gmail').on('click', function(){
		if(gmail == 0){
			$('.EmailMessage').slideDown(200);
			gmail = 1;
		}
		else{
			$('.EmailMessage').slideUp(200);
			gmail = 0;
		}
   });
   $('.closebtn').on('click', function(){
	   $('.EmailMessage').slideUp(200);
	 });
   
      flags = 0;
   $('.icon-search').on('click', function(){
		if(flags == 0){
			$('.slidingDiv').slideDown(200);
			$('.menu_overlay').css('display','none')
			flags = 1;
		}
		else{
			$('.slidingDiv').slideUp(200);
			flags = 0;
		}
   });

/* black menu */

/* white menu */
   flagw = 0;
   $('.icon-menuw').on('click', function(){
		if(flagw == 0){
			$('.menu_overlay').slideDown(200);
			$('.slidingDiv').css('display','none')
			flagw = 1;
		}
		else{
			$('.menu_overlay').slideUp(200);
			flagw = 0;
		}
   });
   
      flagss = 0;
   $('.icon-searchw').on('click', function(){
		if(flagss == 0){
			$('.slidingDivs').slideDown(200);
			flagss = 1;
		}
		else{
			$('.slidingDivs').slideUp(200);
			flagss = 0;
		}
   });

/* white menu */
/* slider  */
$(window).load(function(){
	  $('.flexslider').flexslider({
		animation: "slide",
		start: function(slider){
		  $('body').removeClass('loading');
		}
	  });
});
/* slider  */

$("#quiz1 .checkbox").click(function(){
		$("#quiz1  .checkbox_selected").each(function(){
			$(this).removeClass("checkbox_selected");
			$(this).addClass("checkbox");
			})
		$(this).toggleClass("checkbox_selected checkbox");
		$(".checkbox_selected input").prop('checked', true);

	})
	
$("#quiz2 .checkbox").click(function(){
		$("#quiz2  .checkbox_selected").each(function(){
			$(this).removeClass("checkbox_selected");
			$(this).addClass("checkbox");
			})
		$(this).toggleClass("checkbox_selected checkbox");
		$(".checkbox_selected input").prop('checked', true);
		$(".checkbox div").removeClass( "checkbox_txt_select" );
		$(".checkbox_selected div").addClass( "checkbox_txt_select" );
	})
	
   	$("#poll .checkbox").click(function(){
		$("#poll .checkbox_selected").each(function(){
			$(this).removeClass("checkbox_selected");
			$(this).addClass("checkbox");
			})
		$(this).toggleClass("checkbox_selected checkbox");
		$(".checkbox_selected input").prop('checked', true);

	}) 
	
	
	

 /* menu start  */  
    $(function($) {
		var
        allPanels = $('.leftMenu > dd').hide(),
        leftMenu = $("#leftMenu"),
        headerMenu = $('#headerMenu'),
        header = $('header'),
        win = $(window),
        disabledBg = $('#disabledBg'),
        winH=win.height(),
		headerH = header.height(),
        scrollTop,
        body = $('body, html')
      ;

        win.scroll(function(){
			posMenu();
        })

        function posMenu(){
        	scrollTop = win.scrollTop()
        	if(scrollTop == 0){
        		headerH = header.height() + $('section .top_links').height();
        	} else {
        		headerH = header.height();
        	}
        	leftMenu.css({'top': headerH});
        }

        headerMenu.click(function(){
            var
                oM = $(this)
            ;
            if( !oM.hasClass('active') ){
                oM.addClass('active');
                openMenu();
            }
            else{
                oM.removeClass('active');
                closeLeftMenu();
            }

        });

        openMenu = function(){
            winH=win.height(),
            disabledBg.fadeIn('slow');
            disabledBg.height(winH);
            leftMenu.height( winH - headerH );
            leftMenu.css({'top': headerH});
            leftMenu.animate({'left':0});
            posMenu();
            body.css({'overflow':'hidden'})
        }

        closeLeftMenu = function(){
            disabledBg.fadeOut('fast');
            leftMenu.animate({'left': ( leftMenu.width() * -1 )});
            headerMenu.removeClass('active');
            body.css({'overflow':''})
        }

        disabledBg.click(function(){
           if($(this).is(':visible'))
            closeLeftMenu();
        })

        win.resize(function(){

            disabledBg.height(win.height());
            leftMenu.height( win.height() - header.height() );
            posMenu();

        })


      $('.leftMenu > dt > .arrow').click(function() {
        var thisA=$(this);
        if( !thisA.hasClass('active') ){
            thisA.parents('.leftMenu').find('dt > .arrow.active').removeClass('active');
            thisA.addClass('active');
            allPanels.slideUp();
            thisA.parent().next().slideDown();
            return false;
        } else{
            thisA.parent().next().slideUp();
            thisA.removeClass('active');
        }
      });

    })(jQuery);
	
 /* menu closed  */  
 
