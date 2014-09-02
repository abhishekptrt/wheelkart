<?php 
 $sectionObj = new Section();
 $psection_links = $sectionObj->getParentSections();
 $left_section_links = $sectionObj->getSectionTree();
 $sectionID = intval($_GET['section_id']);
?>
	<div style="display:none;" class="slidingDivs">
           <form action="" name="" id="" method="post" onsubmit="return submitSearchForm();">
                <input class="emailid" type="text" id="search" name="UserEmail" onblur="if(this.value=='' ){this.value= 'Search';}" onfocus="if(this.value=='Search' ){this.value= '';}" value="Search">
                <a href="javascript:;" onclick="return submitSearchForm();" class="SubscribeBt lc">GO</a>
            </form>
        </div>
        <div class="clr"></div>
		<nav style="display:none" class="menu menu_overlay">
			<dl id="leftMenu" class="leftMenu accordion">
			<dt><a href="<?php echo BASE_URL?>">Home</a></dt>
			 <?php foreach ($left_section_links as $id => $text) { ?>
                <dt data-color="<?php echo getsectioncolor($text['id']);?>_menu" <?php if(isset($sectionID) && $sectionID==$text['id']){echo 'class="'. getsectioncolor($text['id']).'_menu actv"';}?>><a href="<?php echo $text['guid'] ?>"><?php echo $text['name'] ?></a><a href="javascript:void(0)" class="arrow sprite_img"></a></dt>
                <?php if (!empty($text['subsection'])) { ?>
                <dd <?php if(isset($sectionID) && $sectionID==$text['id']){echo 'class="actv"';}?>>
                <?php foreach ($text['subsection'] as $cid => $child_text) { ?>             
                <a href='<?php echo $child_text['guid'] ?>'><?php echo $child_text['name']?></a>
                <?php } ?>
                </dd>
            <?php } } ?>
                
             </dl>
		</nav>
        <div class="clr"></div>