<?php
define ('PAGE_GENERAL_PAGE',1);
define ('PAGE_SECTION_PAGE',2);
define ('PAGE_ARTICLE_PAGE', 3);
define ('PAGE_SUB_SECTION_PAGE',4);
define ('PAGE_SUPPORT_PAGE',5);
define ('PAGE_FEEDBACK_PAGE',6);
define ('PAGE_SEARCH_PAGE',7);
define ('PAGE_ERROR_PAGE',8);
define('PAGE_STATIC_PAGE', 9);
define('PAGE_AUTHOR_PAGE', 10); 
define('PAGE_LISTING_PAGE', 11); 


global $settings_new;         

$settings_new = array(PAGE_GENERAL_PAGE=> array('page_id'=>PAGE_GENERAL_PAGE,
                        'page_name'=>'index.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'IndexPageModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 
						 PAGE_SECTION_PAGE=> array('page_id'=>PAGE_SECTION_PAGE,
                        'page_name'=>'section.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'SectionPageModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ),  
						 PAGE_ARTICLE_PAGE=> array('page_id'=>PAGE_ARTICLE_PAGE,
                        'page_name'=>'article.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'ArticlePageModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 		
               PAGE_SUB_SECTION_PAGE=> array('page_id'=>PAGE_SUB_SECTION_PAGE,
                        'page_name'=>'sub_section.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'SubSectionModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 	
                PAGE_SUPPORT_PAGE=> array('page_id'=>PAGE_SUPPORT_PAGE,
                        'page_name'=>'iphone.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ),
                  PAGE_FEEDBACK_PAGE=> array('page_id'=>PAGE_FEEDBACK_PAGE,
                        'page_name'=>'feedback.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'FeedbackModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 	 
                  PAGE_SEARCH_PAGE=> array('page_id'=>PAGE_SEARCH_PAGE,
                        'page_name'=>'search.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'SearchModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 	
                     PAGE_ERROR_PAGE=> array('page_id'=>PAGE_ERROR_PAGE,
                        'page_name'=>'search.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'ErrorModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ), 
						PAGE_STATIC_PAGE=> array('page_id'=>PAGE_STATIC_PAGE,
                        'page_name'=>'staticpage.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'StaticModule'
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ),
						 PAGE_AUTHOR_PAGE=> array('page_id'=>PAGE_AUTHOR_PAGE,
                        'page_name'=>'author_contents.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'AuthorListingModule',
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ),	
						 PAGE_LISTING_PAGE=> array('page_id'=>PAGE_LISTING_PAGE,
                        'page_name'=>'listing.php',
                        'data'=> array(
                                      'left'=>array(
                                        
                                        ),
                                       'middle'=>array( 'ListingPageModule',
                                                      ),
                                       'right'=>array(                                                  
                                                      
                                                      )
                                       ),
                         ),
                  );
?>
