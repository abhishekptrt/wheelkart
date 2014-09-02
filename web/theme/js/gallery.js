$( document ).ready(function() {
       function showHideClick(){ 
            var
                count_elem = $("#image_container"),				
                cnt = parseInt(count_elem.data("current")),
                total = parseInt(count_elem.data("total"))
            ; 
			var index = parseInt(cnt - 1);
			try{ 
		     var media =  $("#media_data").val(); 
			 var obj = jQuery.parseJSON( media ); 
			}catch(e){ console.log(e.message)}
            $('#counter_text').html(cnt+'/'+total);	
            $('#img_caption').html(obj[index].caption);			
            $('#main_img').attr('src', obj[index].thumbnail);		            
            changeUrl(obj[index].page_url);
            refreshAds();            
        }
        function changeUrl(page_url){
			 history.pushState(null, null, page_url);
        }
        function nextSlide(){ 
            var count_elem = $("#image_container"),				
                cnt = parseInt(count_elem.data("current")),
                total = parseInt(count_elem.data("total"));			
            if(cnt<total){
                cnt=cnt+1;				
                count_elem.data('current',''+cnt);
				showHideClick();
            }
        }
        function prevSlide(){
            var count_elem = $("#image_container"),				
                cnt = parseInt(count_elem.data("current")), 
                total = parseInt(count_elem.data("total"));			
            if(cnt>1){
                cnt=cnt-1;                
                count_elem.data('current',''+cnt);
				showHideClick();
            }
        }           
		$('.previous, .left_arrow' ).click(function(){
            prevSlide();
        });
 	    $('.next, .right_arrow').click(function(){ 
            nextSlide();
        });        
		var ic = document.getElementById("image_container");
		var mc = Hammer(ic);		
		mc.on("swipeleft",function(){
		  nextSlide();
	    });
        mc.on("swiperight",function(){
			  prevSlide();
		  });
 });