<section class="article">
<?php
$image = showImage($article); 
if(!empty($image)){ ?>
	<div class="image"><?php echo $image;?></div>
<?php } ?>
           <?php     include "top.tpl.php"; ?>
            <div class="clr"></div>
           
					<p>
					<?php echo $article['summary']; ?>
					</p>
            <!-- quiz question start-->
                  <?php 
					$all_qs = array();  
					if(!empty($quizs)){
					foreach ($quizs as $key=>$quiz) { 
						$all_qs[]= $quiz[0]['ques_id'];
					?>
			     
                    <div class="quiz_container" id="quiz_<?php echo $quiz[0]['ques_id'];?>">
                        <div class="quiz_section">                        	
                            <div class="big_image">
                                 <span class="fadeBg"></span>
                                 <?php  if($quizs[$key][0]['display_type']=='2' || $quizs[$key][0]['display_type']=='3') { ?>
								<img src="<?php  echo  getResizedThumb($quizs[$key][0]['question_image'], '219x108'); ?>" />
								
								<?php  } ?>
                      		</div>
                            <div class="quiz_qust">                            	
								<?php  if($quizs[$key][0]['display_type']=='1' || $quizs[$key][0]['display_type']=='3') { ?>
                            	<h2 class="quiz_qusts"><?php  echo  $quizs[$key][0]['question']; ?></h2>
								<?php  } ?>
                            </div>
                            <div class="clr"> </div>
                        </div>
                        <div class="clr"></div>
						  <?php  
                            $i = 1;
							foreach($quiz as $option){
							
							 if(!empty($option['option_image']) || !empty($option['options'])){
							     if(empty($option['option_image'])){ 
                                 $class = ($i % 2 == 0) ? 'mr_right' : null;
								 
						   ?>
                            <!-- first conatiner start -->
                            <div class="option_cont wths">
                                <div class="checkbox wths" data-ques_id ="<?php echo $option['ques_id']?>">
                                  <input type="radio" name='<?php echo $option['ques_id']?>' value='<?php echo $option['score']?>' /><label for="first"></label>
                                <span><?php echo $option['options'];  ?></span>
                                </div>
                            </div>
							<?php } else {  ?>
                             <div class="option_cont <?php echo $class;?> " data-ques_id ="<?php echo $option['ques_id']?>">
                                <div class="checkbox" data-ques_id ="<?php echo $option['ques_id']?>">
                                 <input type="radio" name='<?php echo $option['ques_id']?>' value='<?php echo $option['score']?>' /><label for="first"></label>
                                    <div class="checkbox_txt"><?php echo $option['options'];  ?></div>
                                    <img src="<?php  echo  getResizedThumb($option['option_image'],'262x166'); ?>"/>
                                </div>
                            </div>
							<?php $i++; } } } ?>
                            <!-- first conatiner closed -->
                          </div>
                     <?php } } ?>
                    <!-- quiz question closed-->
           
           <div class="clr"></div>
			<?php  if(!empty($answers['data'])){
                        $all_ans = array();
						$i = 1;
						foreach($answers['data'] as $k=>$v) {  
                         $all_ans[] = $v['id']; 
					?>
            <div class="result_container"  id ="ans_<?php echo $v['id']?>" style="display:none;">
                <h2><?php  echo $v['title']; ?></h2>
                <p><?php  echo $v['description']; ?></p>
				<?php if(!empty($v['thumbnail'])){ ?>
                <span><img src="<?php echo getResizedThumb($v['thumbnail'], '350X350');?>"/></span>
                 <?php } 
				   $fb_title =  $v['fbtitle'];
				   $fb_desc  =  $v['fbdescription']='';
				   $fb_img   =  $v['thumbnail']='';	
				 ?> 
				<div class="social share">
           		<a href="#" data-social='{"type":"facebook", "url":"<?php echo $article['guid']; ?>", "text": "<?php echo htmlspecialchars($fb_title, ENT_QUOTES) ?>", "image":"<?php echo $fb_img; ?>","desc":"<?php echo htmlspecialchars($fb_desc, ENT_QUOTES); ?>"}' class="fb_art sprite_img">facebook</a>
            	<a href="#" data-social='{"type":"twitter", "url":"<?php echo $article['guid']; ?>", "text": "<?php echo $article['headline1'] ?>"}' class="twitter_art sprite_img">twitter</a>
               </div>
				
             </div>
			 <?php } } $str_ans = implode(',',$all_ans);
						$str_qs = implode(',',$all_qs); 
			 ?>
            <input type="hidden" name="all_ans" id="all_ans" value="<?php echo $str_ans;?>">
			<input type="hidden" name="all_qs" id="all_qs" value="<?php echo $str_qs;?>">
           <div class="clr"></div>
		    <div class="result" id="result" style="display:none;"><a href="javascript:void(0);" onclick="window.location.reload();">Retake Quiz</a></div>
           <div class="clr"></div>

           <!-- <div class="image"><img src="images/facebook_c.jpg" ></div> -->           
        </section>
		 <section class="AddSection" id="div-btf">
		  <script type='text/javascript'>
		  googletag.cmd.push(function() { googletag.display('div-btf'); });
		 </script>
        </section>
		<?php include "related.tpl.php"; ?>
	<script> 
$(document).ready(function(){ 
	  $(".checkbox").click(function(){ 
			$(this).parent().parent().find('div.checkbox_selected').each(function(){ 
					 $(this).removeClass("checkbox_selected");
					 $(this).addClass("checkbox");
				});
			$(this).toggleClass("checkbox_selected checkbox");
        	$(".checkbox_selected input").prop('checked', true);  
			curr_qs =  $(this).data('ques_id');
		//	alert(curr_qs);
			// getting next question 			
		    var qs_ids = $('#all_qs').val().split(',');  //alert(qs_ids);
			var all_answered = false;
            for(var j = 0; j<=(qs_ids.length-1) ; j++){  
					 if($("input[name='"+qs_ids[j]+"']:checked").length > 0){
                        all_answered  = true;
					 }else{
						all_answered  = false; 
						break;
					 }
            } 
           // alert(all_answered);
            if(all_answered){
				move_next(qs_id)
                getScore();
				return true;
			} else { //alert('else');
					i = qs_ids.indexOf(String(curr_qs)); 
					if(i < (qs_ids.length - 1) && i >= 0){
					  qs_id  = qs_ids[i+1];
					  if($("input[name='"+qs_id+"']:checked").length == 0){
						move_next(qs_id);
					  }else{
                          for(var j = 0; j<=(qs_ids.length-1) ; j++){  
							 if($("input[name='"+qs_ids[j]+"']:checked").length){
								
							 }else {
							   qs_id = qs_ids[j];   					   
							   break;
							 }
						 }
					   move_next(qs_id); 
					  }
					}else{		//alert('else2');		
						for(var j = 0; j<=(qs_ids.length-1) ; j++){  
							 if($("input[name='"+qs_ids[j]+"']:checked").length){
								
							 }else {
							   qs_id = qs_ids[j];   					   
							   break;
							 }
						 }
					   move_next(qs_id); 
					}
			}		
  });
});

function move_next(id){      
	$('html, body').animate({
        scrollTop: $('#quiz_'+id).offset().top
    }, 500);
}

  function getScore(){ 
	  var selectedVal = "";
		var selected = $("input[type='radio']:checked");
		if (selected.length > 0) {  
			var ans_ids = $('#all_ans').val().split(',');   
			var ans = [];			 
			 var  i = 0;
			selected.each(function(){ 
			   var	val = $(this).val().split(',');
			      console.log(val);  
				  for(var j = 0; j<=(ans_ids.length-1) ; j++){ 
					 ans[j]  = $.isNumeric(ans[j]) ? ans[j]: 0  ;
					 ans[j] += parseInt(val[j]);
				  }
				 i++;		
			});
			// Get the max value from the array    
			maxValue = Math.max.apply(this, ans);
			// Get the index of the max value, through the built in function inArray
		    key = $.inArray(maxValue,ans);
			console.log(ans);
			console.log(ans_ids);
			console.log(ans_ids[key]);
			$('[id^="ans_"]').hide();
			$('#ans_'+ans_ids[key]).show();
			$('#result').show();
			$('html, body').animate({
					scrollTop: $('#ans_'+ans_ids[key]).offset().top
				}, 500);
			}
			$('.checkbox').unbind('click'); 
			$('.checkbox_selected').unbind('click'); 
			$('div.checkbox').each(function(){
					$(this).removeClass("checkbox");
					$(this).addClass("checkbox_disable");
				});;
			
  }
  </script> 