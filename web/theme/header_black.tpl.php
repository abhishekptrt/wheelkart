 <?php $logo_class = (!empty($section_name)) ? 'logo_videocafe' :'logow';  
  $section_parentid  = $article['section_parentid'] ?  $article['section_parentid'] : intval($_GET['section_id']);
 ?>


 <header>
      		<div class="white_hd overflow">
                <a href="javascript:;" class="icon-menuw sprite_img lc">menu</a>
                 <div class="head_cont lc <?php echo getsectioncolor($section_parentid)?>">
                	<a href="javascript:void(0);" class="<?php echo $logo_class;?> sprite_img">Logo</a>
					<?php if(!empty($section_name)){?>
                    <h1><?php echo $section_name;?></h1>
					<?php } ?>
                </div>
                <a href="javascript:;" class="icon-searchw sprite_img rc">search</a>	
            </div>		
	   </header>
		<?php include "nav_header.tpl.php";?>
        <div class="clr"></div>