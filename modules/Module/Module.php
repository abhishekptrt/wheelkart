<?php

/**
 * This class generates full html module i.e. section of page
 * This is inherited by all block modules that produce inner html
 * @package BlockModules
 * @subpackage Module
 */

class Module {

  public $module_type = 'base_class';
  public $module_placement = '';
  public $inner_HTML;  
  public $view_all_url;
  public $ajax_url;
  public $title;
  public $skipped;
 

  /**
   * $outer class - for the outer class of the outer templetes , this variable is used to reduce the number of OUTER TEMPLETES FILES in Modules
   * @var string
   */
  public $outer_class_name;

  /**
  * $group_owner - It is set to true, if current user is group moderator
  */
  public $group_owner;
   /**
  * $group_member - It is set to true, if current user is group's member
  */
  public $group_member;

  /**
  * $do_skip: If this variable is set to true then the render function will return 'skip' to the PageRenderer
  */
  public $do_skip;

   /**
  * $page_id: Current Page ID where from module rendered; added by: Z.Hron
  */
  public $page_id;

   /**
  * $column: module layout info; added by: Z.Hron
  */
  public $column;

   /**
  * $shared_data: data shared between all modlules on a page; added by: Z.Hron
  */
  public $shared_data;

  /**
  * Will contain the message, either failure of success which are to be displayed on the web page
  */
  public $message = NULL;

  /**
  * This attribute will mark whether the message is a error message or success message.
  * If TRUE = error or failure message,
  * FALSE = success
  */
  public $isError = FALSE;

  /**
  * These two parameters are used to set the message from with in the module into web page file.
  * These variables are used by set_web_variables function defined in functions.php
  */
  public $redirect2 = NULL;
  public $queryString = NULL;

  /**
  * The default constructor for MembersFacewallModule class.
  * It initializes the default values of vars
  */
  function __construct() {
    $this->do_skip = FALSE;
  }


  /**
  *  Function : render()
  *  Purpose  : produce html code of module. It generally uses two tpl files
  *             one for outer template and one for inner template
  *             inner template is produced by inherited module
  *  @return   type string
  *            returns rendered html code
  */
  function render() {

    if ($this->do_skip) return 'skip';//Module will be skipped if do_skip is true.
    $title = $this->title;
    $inner_HTML = $this->inner_HTML;
    $view_all_url = $this->view_all_url;
    $ajax_url = $this->ajax_url;
    $template_file = CURRENT_THEME_FSPATH.DS.$this->outer_template;
    $block = new Template($template_file);   
    $block->set('title', $title);
    $block->set('inner_HTML', $inner_HTML);
    $block->set('ajax_url', $ajax_url);
    $block->set('view_all_url', $view_all_url);
    $contents = $block->fetch();

   
    return $contents;              // Return the contents
  }

  function start_form($name_id, $method) {
    $ret = '<form method="'.$method.
      '" name="'.$name_id.
      '" id="'.$name_id.
      '" action="'.($this->post_url ? $this->post_url : "").
      '">';
    // when widgetized, the url specifies the module to post to.  if
    // not, we generate a hidden form_handler input.
    if (!$this->widgetized) {
      $ret .= '<input type="hidden" name="form_handler" value="'.get_class($this).'" />';
    }
    return $ret;
  }

  function input_tag($input_type, $name, $value) {
    return '<input type="'.$input_type.
      '" name="'.htmlspecialchars($this->param_prefix.$name).
      '" value="'.htmlspecialchars($value).
      '" />';
  }

  function textarea_tag($name, $value) {
    return '<textarea name="'.htmlspecialchars($this->param_prefix.$name).
      '">'.htmlspecialchars($value).'</textarea>';
  }

  function submit_tag($value) {
    return '<input type="submit" value="'.htmlspecialchars($value).'" />';
  }

  /**
  * Method will be used for setting the message in the web pages.
  */
  public function setWebPageMessage() {
    if (!empty($this->message)) {
      if (!$this->isError) {//Success
        $message = array('failure_msg'=>NULL, 'success_msg'=>$this->message);
      } else {//Message is a failure message
        $message = array('failure_msg'=>$this->message, 'success_msg'=>NULL);
      }
      @set_web_variables($message, $this->redirect2, $this->queryString);
    }
  }

}
?>
