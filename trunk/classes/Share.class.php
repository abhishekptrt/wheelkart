<?php

class Share {

    public $db = null;

    public function __construct() {
        $this->db = Database::Instance();
    }

    /*
      share via email
     */

    public function viaEmail($param) {
        $arrTo = array();
        $subject = $param['subject'];
        $body = $param['message'];
        $fromEmail = $param['fromEmail'];
        $toMail = $param['toEmail'];
        if (strstr($toMail, ',')) {
            $arrTo = explode(',', $toMail);
            if (!empty($arrTo)) {
                foreach ($arrTo as $k => $v) {
                    sendHTMLMail(trim($v), $subject, $body, $fromEmail);
                }
            }
        } else {
            sendHTMLMail($toMail, $subject, $body, $fromEmail);
        }

        if(isset($param['img_id']))
		{
			unset($param['message']);
			unset($param['subject']);
			$objCnt = new ShutterBugVotes();
			$objCnt->saveShared($param);
		}
		else
		{
			$objCnt = new ContentCount();
			$objCnt->updateCounters($param['id'], array('shared' => 1, 'preview' => 0));
		}
    }

    public function getTemplateForEmail($param) {
        $strHeadline = '';
        $strFName = '';
        $strEmail = '';
        $param['recommended_txt'] = (isset($param['recommended_txt']) && !empty($param['recommended_txt'])) ? $param['recommended_txt'] : 'Recommended';
		$param['related_txt'] = (isset($param['related_txt']) && !empty($param['related_txt'])) ? $param['related_txt'] : 'Related Stories';

        $strMessage = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"><html xmlns="http://www.w3.org/1999/xhtml">
	<head><meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Share Mailer</title>
	</head><body>
	<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#e6e6e5">
	<tr><td valign="top"><table width="560" border="0" cellspacing="0" cellpadding="0">
	<tr><td height="61" valign="middle" bgcolor="#333333" style="border-top:1px solid #414141; border-bottom:1px solid #414141;">
	<table width="600" border="0" cellspacing="0" cellpadding="0"><tr><td width="305"><a href="' . SITEPATH . '"><img src="' . MEDIASERVERPATH . '/images/itlogo.gif" alt="Indiatimes" width="205" height="43" hspace="20" border="0" /></a></td> ';
        if (isset($param['fromEmail']) && ($param['fromEmail'] != '')) {
            $strMessage .= '<td width="295"><font face="Arial, Helvetica, sans-serif" size="2" color="#ffffff">Your friend <br /><a style="color:#5093d6; text-decoration:none">' . $param['fromEmail'] . '</a> has shared a link with you.</font> </td> ';
            $strEmail = $param['fromEmail'];
        }
        $strMessage .= ' </tr></table></td></tr><tr><td valign="top"><table width="560" border="0" align="center" cellpadding="0" cellspacing="0"><tr><td style="padding:13px 0 12px 0; border-bottom:1px solid #e6e6e5; font-size:11px"><font face="Arial, Helvetica, sans-serif" color="#000000"> <strong>Message from ';
        if (isset($param['fromName']) && ($param['fromName'] != '')) {
            $strMessage .= $param['fromName'] . ':<br />';
            $strFName = $param['fromName'];
        }
        $strMessage .= '<span style="font-size:12px;">' . $param['message'] . '</span> </strong></font> </td> ';

        $strMessage .= '</tr><tr><td height="12"></td></tr><tr><td valign="top"><table width="560" border="0" cellspacing="0" cellpadding="0"><tr><td style="font-size:22px; line-height:22px; padding-bottom:7px" colspan="2"><font face="Arial, Helvetica, sans-serif"><strong> ';
        $strMessage .= '<a href="' . $param['strUrl'] . '" style="color:#333333; text-decoration:none" title="' . $param['strHeadline'] . '">' . $param['strHeadline'] . '</a></strong></font></td>';

        $strMessage .= '</tr><tr><td width="167" rowspan="2"><a href="' . $param['strUrl'] . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">
	   <img src="' . $param['strThumbnail'] . '" alt="' . $param['strHeadline'] . '" width="151" height="113" border="0" /></a></td>
	   <td width="393" valign="top" style="font-size:11px; line-height:16px"><font face="Tahoma, Arial, Helvetica, sans-serif" color="#000000">' . $param['strSummary'] . '</font><br /><br />
	   <span style="float:left"><a href="' . $param['strUrl'] . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">
	   <img src="' . MEDIASERVERPATH . '/images/readbtn.jpg" alt="Read more" border="0" /></a> </span><span style="float:left; margin-left:15px"><font face="Arial, Helvetica, sans-serif" size="1" color="#333333"><strong>(Read ' . $param['strVisits'] . ' Times)</strong></font></span>
	   </td></tr><tr> <td valign="top" style="font-size:11px; line-height:16px">&nbsp;</td></tr></table></td></tr>';

        $strMessage .= '<tr><td height="16"></td></tr><tr><td><img src="' . MEDIASERVERPATH . '/images/line.gif" width="560" height="12" /></td></tr><tr><td><table width="560" border="0" cellspacing="0" cellpadding="0">
	<tr><td width="286" style="border-right:1px solid #e5e5e5; font-size:16px; color:#333333; padding-top:10px"><font face="Arial, Helvetica, sans-serif"><strong>'.$param['related_txt'].'</strong></font></td>
	<td width="274" style="font-size:16px; padding-left:31px; color:#333333; padding-top:10px"><font face="Arial, Helvetica, sans-serif"><strong>'.$param['recommended_txt'].'</strong></font></td></tr>';

        $strMessage .= '<tr><td valign="top" style="border-right:1px solid #e5e5e5; font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333"><ul style="padding:0 0 10px 16px; width:250px">' . $param['recommended'] . '<ul></td>';

        $strMessage .= '<td valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:12px; color:#333333"><ul style="padding:0 0 10px 48px">' . $param['related_stories'] . '</ul></td>';

        $strMessage .= '</tr></table></td></tr></table></td></tr>
	<tr><td height="1" valign="top" bgcolor="#fff" style="border-top:1px solid #e5e5e5"></td></tr><tr><td valign="top" bgcolor="#f2f2f2" style="border-top:1px solid #ffffff; padding-top:10px; padding-bottom:10px"><table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr><td><span style="font-size:14px; color:#707070">Get more of your favourite content from www.indiatimes.com. Get weekly updates from the world of <a href="' . TOP_NEWS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">Entertainment</a>, <a href="' . TOP_NEWS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">News</a>, <a href="' . SPORTS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">Sports</a>, <a href="' . TOP_NEWS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">Technology</a>,  <a href="' . HEAVY_METAL_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">Boyz Toyz</a>, <a href="' . LIFE_STYLE_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=sharesubs">Lifestyle</a> and <a href="' . SITEPATH . '">much more.</a></span></td></table></td></tr>
	<tr><td align="center" valign="top" style="border-top:1px solid #ffffff; padding-top:10px; padding-bottom:10px"><table width="210" border="0" align="center" cellpadding="0" cellspacing="0">
	<tr><td width="36%"><img src="' . MEDIASERVERPATH . '/images/connect.gif" alt="Connect With Us" width="107" height="33" /></td><td width="16%"><a href="https://twitter.com/indiatimes"><img src="' . MEDIASERVERPATH . '/images/twitter.gif" alt="Twitter" width="26" height="27" border="0" /></a> </td><td width="16%"> <a href="https://www.facebook.com/indiatimes"><img src="' . MEDIASERVERPATH . '/images/facebook.gif" alt="Facebook" width="24" height="24" border="0" /></a> </td><td width="40%"> <a href="https://plus.google.com/+indiatimes/posts"><img src="' . MEDIASERVERPATH . '/images/gplus.gif" alt="gplus" width="24" height="24" border="0" /></a></td>
	</tr></table></td></tr></table></td></tr></table></body></html>';

        $returnParam = array('subject' => 'Indiatimes.com : ' . $param['strHeadline'], 'message' => $strMessage);
        return $returnParam;
    }

// eof getTemplateForEmail

    public function getTemplateForNewLetter() {
        
    }

    public function getTemplateForTopicEmail($param) {
        $strHeadline = '';
        $strFName = '';
        $strMessage = '<strong>This page was sent to you by:</strong> ';
        if (isset($param['fromName']) && ($param['fromName'] != '')) {
            $strMessage .= $param['fromName'];
            $strFName = $param['fromName'];
        }

        if (isset($param['fromEmail']) && ($param['fromEmail'] != '')) {
            $strMessage .= ', ' . $param['fromEmail'];
        }

        $strMessage .= '<br/>--------------------------------<br/>';

        $strUrl = $param['topic_url'];
        $strHeadline = $param['topic'];
        $strSummary = $param['topic_desc'];

        if ($strUrl != '') {
            $strMessage .= '<a href="' . $strUrl . '"';
        } else {
            $strMessage .= '<a href="#"';
        }

        if ($strHeadline != '') {
            $strMessage .= ' target="_blank" title="' . $strHeadline . '">' . $strHeadline . '</a>';
        } else {
            $strMessage .= ' title=""></a>';
        }
        $strMessage .= '<br/>';

        if ($strSummary != '') {
            $strMessage .= substr($strSummary, 0, 100);
        }

        $strMessage .= '<br/><br/>Log on to : <a href="http://www.indiatimes.com">http://www.indiatimes.com</a><br/>----------------------------------<br/>';

        if (isset($param['message']) && ($param['message'] != '')) {
            $strMessage .= 'Message from ' . $strFName . ':<br/>' . $param['message'] . '<br/>----------------------------------<br/>';
        }

        $strMessage .= '<br/>** Disclaimer **<br/><br/>This is a public forum provided by TimesofIndia.com for its users to share their views with friends/public at large. TimesofIndia.com is not responsible for the content of this email. Anything written in this email does not necessarily reflect the views or opinions of TimesofIndia.com. Please note that neither the email address nor the name of the sender has been verified.';

        $returnParam = array('subject' => 'Indiatimes.com : ' . $strHeadline, 'message' => $strMessage);
        return $returnParam;
    }

// eof getTemplateForTopicEmail

    public function getTemplateForWelcomeEmail($param) {
        $date = date('Y-m-d H:i:s');
        $from_date = date('Y-m-d H:i:s', strtotime('-1 days'));
        //$from_date =  date('Y-m-d H:i:s', strtotime('2 years ago'));
        $daterange = " AND publishdate between '$from_date' AND '$date' ";
        $userName = $param['toName'];
        $toEmail = $param['toEmail'];
        //echo '<pre>';print_r($param);echo '</pre>';
        $param['section_idArr'];
        if (in_array(0, $param['section_idArr'])) {
            $headerMsg = ' <a href="' . TOP_NEWS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">News</a>, 
                        <a href="' . MOVIES_ENTERTAINMENT_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Entertainment</a>, 
                        <a href="' . SPORTS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Sports</a>, 
                        <a href="' . TECH_KNOW_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Technology</a>, 
                        <a href="' . LIFE_STYLE_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Lifestyle</a>, 
                        <a href="' . HEAVY_METAL_REVIEW_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Boyz Toyz</a>';
        } else {
            if (in_array(19, $param['section_idArr'])) {
                $headerMsg = '<a href="' . TOP_NEWS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">News</a>';
            }
            if (in_array(1, $param['section_idArr'])) {
                $headerMsg .= ', <a href="' . MOVIES_ENTERTAINMENT_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Entertainment</a>';
            }
            if (in_array(2, $param['section_idArr'])) {
                $headerMsg .= ', <a href="' . SPORTS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Sports</a>';
            }
            if (in_array(4, $param['section_idArr'])) {
                $headerMsg .= ', <a href="' . TECH_KNOW_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Technology</a>';
            }
            if (in_array(3, $param['section_idArr'])) {
                $headerMsg .= ', <a href="' . LIFE_STYLE_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Lifestyle</a>';
            }
            if (in_array(112, $param['section_idArr'])) {
                $headerMsg .= ', <a href="' . HEAVY_METAL_REVIEW_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Boyz Toyz</a>';
            }
        }
        $strMessage .= '<br/>';
        //$strMessage .= 'Thank you for subscribing to Indiatimes Newsletter. Your inbox just got trendier with weekly updates from the world of Entertainment, News, Sports, Technology, Lifestyle and much more.';

        $strMessage .='<table width="600" border="1" align="center" cellpadding="0" cellspacing="0" bordercolor="#e6e6e5">
  <tr>
    <td valign="top"><table width="560" border="0" cellspacing="0" cellpadding="0">
        <tr>
          <td height="61" valign="middle" bgcolor="#333333" style="border-top:1px solid #414141; border-bottom:1px solid #414141;">
          <table width="600" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td width="305"><a href="' . SITEPATH . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs"><img src="' . IMAGESITEPATH . '/itlogo.gif" alt="Indiatimes" width="205" height="43" hspace="20" border="0" /></a></td>
                <td width="295">&nbsp; </td>
              </tr>
            </table>
            </td>
        </tr>
        <tr>
          <td valign="top">
          <table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
              <tr>
                <td height="12"></td>
              </tr>
              <tr>
                <td valign="top">
                <table width="560" border="0" cellspacing="0" cellpadding="0">
                    <tr>
                      <td style="font-size:22px; line-height:22px; padding-bottom:7px"><font face="Arial, Helvetica, sans-serif"><strong>Dear ' . $userName . '</strong></font></td>
                    </tr>
                    <tr>
                      <td width="393" valign="top" style="font-size:14px; line-height:16px">
                      <font face="Tahoma, Arial, Helvetica, sans-serif" color="#000000">
                      Thank you for subscribing to Indiatimes Newsletter. Your inbox just got trendier with weekly updates from the world of 
                       ' . $headerMsg . '
                            and much more
                      </font><br />
                      </td>
                    </tr>
                  </table>
                  </td>
              </tr>
              <tr>
                <td height="16"></td>
              </tr>
              <tr>
                <td><img src="' . IMAGESITEPATH . '/line.gif" width="560" height="12" /></td>
              </tr>
              
              <tr>
               <td width="286" style="font-size:16px; color:#333333; padding-top:10px"><font face="Arial, Helvetica, sans-serif"><strong>Take a look at the Top Stories of the Day:</strong></font></td>
              </tr>
              <tr>
                <td valign="top" style="font-size:11px; line-height:16px">&nbsp;</td>
              </tr>
              <tr>
                <td valign="top" style="font-size:11px; line-height:16px">
	<table width="100%" border="0" cellspacing="0" cellpadding="0">';

        $resultset = $this->db->getDataFromTable(array('sqlclause' => 'status=1 AND publishdate <= NOW() AND (expirydate IS NULL OR expirydate > NOW() ) ' . $daterange), 'content JOIN content_counts ON content.id=content_counts.content_id', $fields = 'headline1,guid,visits,thumbnail', 'visits desc', '5');
        //echo '<pre>';print_r($resultset);echo '</pre>';
        if (count($resultset['data'])) {
            foreach ($resultset['data'] as $item) {
                //$strMessage .= '<a href="'.$item['guid'].'">'.$item['headline1'].'</a> Read '.$item['visits'].' times already<br/>';
                $strMessage .='<tr>
                            <td width="14%"><img src="' . getResizedThumb($item['thumbnail'], '72x54') . '" width="72" height="54" /></td>
                            <td width="86%" valign="top"><font face="Arial, Helvetica, sans-serif" size="2">
                                <strong>
                                <a href="' . $item['guid'] . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#333; text-decoration:none">' . $item['headline1'] . '</a>
                                </strong>
                            <br /><span style="font-size:11px; color:#2692ff">(Read ' . $item['visits'] . ' Times)</span></font></td>
                       </tr>
                  
                      <tr>
                        <td>&nbsp;</td>
                        <td>&nbsp;</td>
                      </tr>';
            }
        }
        $url = 'http://graph.facebook.com/?ids=http://www.facebook.com/indiatimes&fields=likes';
        $JSON = file_get_contents($url);
        $data = json_decode($JSON, true);
        $fblikes = $data['http://www.facebook.com/indiatimes']['likes'];


        //$strMessage .= '<br/>For more on the buzz happening around you in the world of News, Entertainment, Sports, Technology, Lifestyle, Boyz Toyz all links with urls linking back to section landing pages) and much more, log on to <a href="www.indiatimes.com" title="Indiatimes">www.indiatimes.com</a><br/>';
        //$strMessage .= 'You can also find us on  <a href="https://www.facebook.com/indiatimes"><img src="'.IMAGESITEPATH.'/facebook.gif" border="0" /></a>'.$fblikes.' likes <br/>' ;
        //$strMessage .= 'You can find us on <a href="https://twitter.com/indiatimes"><img src="'.IMAGESITEPATH.'/twitter.gif" border="0" /></a> <a href="https://plus.google.com/+indiatimes/posts" ><img src="'.IMAGESITEPATH.'/gplus.gif" border="0" /></a><br/>';
        //$strMessage .= 'We welcome your suggestions and feedback. Please write to us on editors.indiatimes@indiatimes.com<br/><br/>' ;
        //$strMessage .= 'Thank you,<br/>Team Indiatimes.';

        $strMessage .='</table>
                                 </td>
                  </tr>
                  <tr>
                    <td valign="top" style="font-size:11px; line-height:16px">&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top" style="font-size:14px; line-height:16px">
                    <font face="Tahoma, Arial, Helvetica, sans-serif" color="#000000">For more on the buzz happening around you in the world of 
                        <a href="' . TOP_NEWS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">News</a>, 
                        <a href="' . MOVIES_ENTERTAINMENT_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Entertainment</a>, 
                        <a href="' . SPORTS_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Sports</a>, 
                            <a href="' . TECH_KNOW_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Technology</a>, 
                            <a href="' . LIFE_STYLE_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Lifestyle</a>, 
                            <a href="' . HEAVY_METAL_REVIEW_LANDING_URL . '?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">Boyz Toyz</a>
                            and much more, log on to 
                            <a href="http://www.indiatimes.com/?utm_source=newsletter&utm_medium=email&utm_campaign=welcomesubs" style="color:#2692ff">www.indiatimes.com</a>
                    </font>
                      </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>

                  <tr>
                    <td>&nbsp;</td>
                  </tr>
                  <tr>
                    <td valign="top" style="font-size:14px; line-height:16px">
                    <font face="Tahoma, Arial, Helvetica, sans-serif" color="#000000">We welcome your suggestions and feedback. Please write to us on 
                    <a href="mailto:editors.indiatimes@indiatimes.com" style="color:#2692ff"> editors.indiatimes@indiatimes.com</a>
                    </font>
                    </td>
                  </tr>
                  <tr>
                    <td>&nbsp;</td>
                  </tr>
              </table>
              </td>
            </tr>
            <tr>
              <td height="1" valign="top" bgcolor="#fff" style="border-top:1px solid #e5e5e5"></td>
            </tr>

            <tr>
              <td valign="top" bgcolor="#ffffff" style="border-top:1px solid #ffffff; padding-top:10px; padding-bottom:10px">
              <table width="560" border="0" align="center" cellpadding="0" cellspacing="0">
                <tr>
                    <td style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:16px; color:#666666;">
                    
                    </td>
                </tr>
                  <tr>
                    <td colspan="2" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:16px; color:#666666;">
                    &nbsp;
                    </td>
                    <td width="295" style="font-family:Tahoma, Arial, Helvetica, sans-serif; font-size:16px; color:#666666;">
                    <table width="210" border="0" align="right" cellpadding="0" cellspacing="0">
                  <tr>
                    <td width="36%"><img src="' . IMAGESITEPATH . '/connect.gif" alt="Connect With Us" width="107" height="33" /></td>
                    <td width="15%"><a href="https://twitter.com/indiatimes"><img src="' . IMAGESITEPATH . '/twitter.gif" alt="Twitter" width="26" height="27" border="0" /></a></td>
                    <td width="15%"><a href="https://www.facebook.com/indiatimes"><img src="' . IMAGESITEPATH . '/facebook.gif" alt="Facebook" width="24" height="24" border="0" /></a></td>
                    <td width="40%"><a href="https://plus.google.com/+indiatimes/posts"><img src="' . IMAGESITEPATH . '/gplus.gif" alt="gplus" width="24" height="24" border="0" /></a></td>
                  </tr>
                </table>                </td>
                  </tr>
                </table>
                </td>
            </tr>
        </table>
        </td>
      </tr>
    </table>';
        $param['subject'] = 'Welcome to Indiatimes..Your inbox just got trendier..!!';
        $param['message'] = $strMessage;
        //echo '<pre>';print_r($param);echo '</pre>';
        //echo $strMessage.'<hr>';
        $this->viaEmail($param);
    }

    public function shutterbugShareEmail($param) {
//echo '<br>--------- header --------- <br>';
        $strMessage .='
<table width="600" border="0" align="center" cellpadding="0" cellspacing="0">
  <tr>
    <td width="274" height="63" align="left" valign="top" bgcolor="#333333">
        <a href="'.SITEPATH.'/shutterbug/"><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/logo.jpg"  border="0" /></a>
    </td>
    <td width="312" height="63" align="left" valign="middle" bgcolor="#333333" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#fff;">A destination to share your slice of life through the lens of your camera! Start clicking n sharing!</td>
    <td width="14" align="left" valign="top" bgcolor="#333333">&nbsp;</td>
  </tr>
';
        $id = $param['id'];
        $shObj = new ShutterBug();
        $imdArr = $shObj->getImageDetails($id);
//echo '<pre>'; print_r($imdArr);echo '</pre>';
        $imgPath = uihelper_resize_img($imdArr['data'][0]['img_path'], 325, 325);
        $param['caption'] = $caption = $imdArr['data'][0]['caption'];
        $param['theme_name'] = $theme_name = $imdArr['data'][0]['theme_name'];
        $param['theme_id'] = $theme_id = $imdArr['data'][0]['theme_id'];
        $param['img_id'] = $imgId = $imdArr['data'][0]['img_id'];
        $user_name = $imdArr['data'][0]['name'];

        $voteCount = $shObj->getVoteCount($imgId);
        $url = get_shutterbug_link($param, $type = 'theme-photo-detail');
//echo '<pre>';print_r($_GET);echo '</pre>';
        $strMessage .='
  <tr>
    <td colspan="3" align="center" valign="top"  style="border:0px solid green;">
	<table width="562" border="0" cellspacing="0" cellpadding="0">
      <tr>
        <td width="60%" height="60" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">Your friend Karan shared an image with you.<br/> 
        <strong>Message:</strong> '.$param['message'].'</td>
        <td width="40%" height="60" align="center" valign="middle">
		<table width="220" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="220" height="30" align="center" valign="middle" bgcolor="#515050" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; text-transform:uppercase; font-weight:bold;">
            <a href="' . SITEPATH . '/shutterbug/" style="color:#76ddff; text-decoration:none;">click here to see more</a>
            </td>
          </tr>
        </table>
    </td>
      </tr>
    </table>
	</td>
    </tr>
  <tr>
    <td colspan="3" align="center" valign="top">
        <img src="' . IMAGESITEPATH . '/shutterbugShareEmail/shadow_line.jpg" width="562" height="7" />
    </td>
  </tr>    
';


        $strMessage .='
<tr>
        <td colspan="3" align="center" valign="top" style="border:0px solid green;">
		<table width="100%" border="0" cellspacing="0" cellpadding="0">
          <tr>
            <td width="27%" height="167" align="left" valign="middle">
		<a href="' . $url . '">
		<img src="' . $imgPath . '" border="0"  width="133" height="133"/>
		</a>
	    </td>
            <td width="73%" height="167" align="left" valign="middle">
            	<table width="100%" border="0" cellspacing="0" cellpadding="0">
                  <tr>
                     <td height="50" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; text-transform:uppercase; line-height:25px; font-weight:bold;">
                        <a href="javascript:;" style="color:#333333; text-decoration:none;">
                        ' . $caption . '
                        </a>
                     </td>
                    </tr>
                  <tr>
                    <td height="40" align="left" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333;">
                    By: <span style="color:#0058cf;">' . $user_name . '</span>
                    &nbsp; |&nbsp;   In: <span style="color:#0058cf;">' . $theme_name . '</span>  &nbsp; |&nbsp;   
			<img src="'.IMAGESITEPATH.'/shutterbugShareEmail/thumb.jpg"/> ' . $voteCount . ' Votes</td>
                    </tr>
                  <tr>
                    <td height="40" align="left" valign="middle">
                    <table width="220" border="0" cellspacing="0" cellpadding="0">
                      <tr>
                        <td width="220" height="30" align="center" valign="middle" bgcolor="#515050" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#76ddff; text-transform:uppercase; font-weight:bold;">
                            <a href="' . $url . '" style="color:#76ddff; text-decoration:none;">click here to see more</a>
                        </td>
                      </tr>
                    </table>
			</td>
                    </tr>
                </table>
		 </td>
          </tr>
        </table>
		</td>
        </tr>
   <tr>
    <td colspan="3" align="center" valign="top"><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/shadow_line.jpg" width="562" height="7" /></td>
  </tr>    
';
//echo '<br>--------- Most Popular Images --------- <br>';
        $popularArr = $shObj->getPopularImages($start = 0, $limit = 5, $cnt = false);

        if (count($popularArr['data'])) {
            foreach ($popularArr['data'] as $itemArr) {
                //echo '<pre>';print_r($itemArr);echo '</pre>';
                $param['caption'] = $itemArr['caption'];
                $param['theme_name'] = $itemArr['theme_name'];
                $param['theme_id'] = $itemArr['theme_id'];
                $param['img_id'] = $itemArr['img_id'];
                $imgPath = uihelper_resize_img($itemArr['img_path'], 325, 325);
                $url = get_shutterbug_link($param, $type = 'theme-photo-detail');
                //$strMessage .= '<a href="'.$item['guid'].'">'.$item['headline1'].'</a> Read '.$item['visits'].' times already<br/>';
                $strPopularImg .='
                                            <td width="19%" align="left" valign="top" height="110">
                                                <a href="' . $url . '">
                                                    <img src="' . $imgPath . '" border="0" width="105" height="105"/>
                                                </a>
                                            </td>
                                            <td width="10" align="left" valign="top" height="110">&nbsp;</td>
                                    ';
            }
        }
        if (count($popularArr['data'])) {
            foreach ($popularArr['data'] as $item) {
                //$strMessage .= '<a href="'.$item['guid'].'">'.$item['headline1'].'</a> Read '.$item['visits'].' times already<br/>';
                $strPopularHeading .='
                                            <td height="40" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:13px;">
                                                <a href="javascript:;"  style="color:#333; text-decoration:none;">' . $item['caption'] . '</a>
                                            </td>
                                            <td align="left" valign="top">&nbsp;</td>
                                    ';
            }
        }

        $strMessage .='
<tr>
    <td colspan="3" align="center" valign="top"><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/shadow_line.jpg" width="562" height="7" /></td>
  </tr>
  
  
  <tr>
	<td height="40" colspan="3" align="center" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#333333; text-transform:uppercase;">Most Popular Images</td>
  </tr>
  
  
  <tr>
        <td colspan="3" align="center" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px solid green;">
          <tr>' . $strPopularImg . '</tr>
          <tr>' . $strPopularHeading . '</tr> 
        </table>
        </td>
        </tr>    
';

//echo '<br>-------- More from indiatimes ------- <br>';
        $strMessage .='
	<tr>
            <td colspan="3" align="center" valign="middle" height="10" ><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/shadow_line.jpg" width="562" height="7" /></td>
        </tr>    
	<tr>
            <td height="40" colspan="3" align="center" valign="middle" style="font-family:Arial, Helvetica, sans-serif; font-size:18px; color:#333333; text-transform:uppercase;">More from indiatimes</td>
	</tr>    
        <tr>
        <td colspan="3" align="center" valign="top">
        <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border:0px solid green;">
          <tr>
            <td width="260" align="left" valign="top">
                <table width="100%" border="0" cellspacing="0" cellpadding="0" style="border-right:1px solid #ccc;">
                  <tr>
                    <td colspan="2" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333333; text-transform:uppercase; line-height:25px; font-weight:bold;">Popular Photo Galleries</td>
                    </tr>
                  <tr>
                    <td colspan="2" align="left" valign="top">';

        $contentObj = new Content();
        $from_date = date('Y-m-d H:i:s', strtotime('last Friday'));
        $photos = $contentObj->getMailerContentList(array( 'debug'=>1,'contype_id_in' => PHOTOGALLERY, 'limit' => 3, 'order_by' => array('cc.visits' => 'desc')));
        $strMessage .='<ul style="margin:0px; padding:0px;">';
        foreach ($photos['data'] as $key => $photosArr) {
            if (empty($photosArr['carousal_headline'])) {
                $headline = str_stop($photosArr['headline1'], 40);
            } else {
                $headline = str_stop($photosArr['carousal_headline'], 40);
            }

            $strMessage .='
                            <li>
                                <span style="font-size:12px;font-family:Arial; line-height:12px;"><a href="'.$photosArr['guid'].'" style="color:#333; text-decoration:none; vertical-align:2px">' . $headline . '</a></span><br/>
                                <span style="font-size:7.5pt;font-family:Arial;color:#707070">(Read '.$photosArr['visits'].' Times)</span>
                            </li>
                        ';
        }
        $strMessage .='</ul>';

        $strMessage .='</td>
                  </tr>
                  </table>
              </td>
              <td width="32"></td>
            <td width="260" align="right" valign="top"><table width="100%" border="0" cellspacing="0" cellpadding="0">
              <tr>
                <td colspan="2" align="left" valign="top" style="font-family:Arial, Helvetica, sans-serif; font-size:14px; color:#333333; text-transform:uppercase; line-height:25px; font-weight:bold;">Popular Articles</td>
              </tr>
              <tr>
                <td colspan="2" align="left" valign="top">';
        $strMessage .='<ul style="margin:0px; padding:0px;">';
        
        $from_date = date('Y-m-d H:i:s', strtotime('last Friday'));
        $NewStories = $topNewStories = $contentObj->getMailerContentList(array('debug'=>1, 'section_parent_id' => TOP_NEWS_SECTION, 'start' => 0, 'limit' => 3, 'order_by' => array('cc.visits' => 'desc'), 'contype_id_not_in' => LISTS));
//        echo '<pre>';
//        print_r($NewStories);
//        echo '</pre>';
        foreach ($NewStories['data'] as $key => $valueArr) {
            if ($valueArr['news_letter_headline'] != '') {
                $newsHeadLine = str_stop($valueArr['news_letter_headline'], 45);
            } else {
                $newsHeadLine = empty($valueArr['carousal_headline']) ? str_stop($valueArr['headline1'], 45) : $valueArr['carousal_headline'];
            }
            $strMessage .='<li>
                                <span style="font-size:12px;font-family:Arial; line-height:12px;"><a href="'.$valueArr['guid'].'" style="color:#333; text-decoration:none;  vertical-align:2px">'.$newsHeadLine.'</a></span><br/>
                                <span style="font-size:7.0pt;font-family:Arial;color:#707070">(Read '.$valueArr['visits'].' Times)</span>
                            </li>';
        }
        $strMessage .='</ul>';
        $strMessage .='</td>
              </tr>
            </table></td>
          </tr>
          </table></td>
          
        </tr>';

//echo '<br>--------- footer --------- <br>';
        $strMessage .='
            <tr>
                <td height="15" colspan="3" align="center" valign="top"></td>
            </tr>   
	<tr>
            <td width="299" align="center" valign="top">&nbsp;</td>
            <td width="263" align="center" valign="top">&nbsp;</td>
	</tr>            
	<tr>
        <td colspan="3" align="center" valign="top">
        	<span style="font-family:Arial, Helvetica, sans-serif; font-size:14px; font-weight:bold; vertical-align:8px; color:#5c5c5c; text-transform:uppercase;">CONNECT WITH US</span>
            <a href="javascript:;"><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/twiter.jpg" border="0"/></a>
            <a href="javascript:;"><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/fb.jpg" border="0"/></a>
            <a href="javascript:;"><img src="'.IMAGESITEPATH.'/shutterbugShareEmail/g_plus.jpg" border="0"/></a>
        </td>
        </tr>
</table>        
            ';
        //$objVotes = new ShutterBugVotes();
        $params['img_id']=$id;
        //$objVotes->saveShared($params);
        $params['subject'] = 'Welcome to Indiatimes..Your inbox just got trendier..!!';
        $params['message'] = $strMessage;
        //echo '<pre>';print_r($param);echo '</pre>';
        
        //echo $strMessage . '<hr>';
        $this->viaEmail($params);
    }

}