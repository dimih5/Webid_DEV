<?php
/***************************************************************************
 *   copyright				: (C) 2008 - 2013 WeBid
 *   site					: http://www.webidsupport.com/
 ***************************************************************************/

/***************************************************************************
 *   This program is free software; you can redistribute it and/or modify
 *   it under the terms of the GNU General Public License as published by
 *   the Free Software Foundation; either version 2 of the License, or
 *   (at your option) any later version. Although none of the code may be
 *   sold. If you have been sold this script, get a refund.
 ***************************************************************************/

include 'common.php';

// check recaptcha is enabled
include $include_path . 'captcha/recaptchalib.php';
include $include_path . 'captcha/securimage.php';

if (isset($_REQUEST['id']))
{
	$_SESSION['CURRENT_ITEM'] = $_REQUEST['id'];
}

$id = intval($_SESSION['CURRENT_ITEM']);

$TPL_error_text = '';
$emailsent = 1;
$spam_html = '';
if ($system->SETTINGS['spam_register'] == 1)
{
	$resp = new Securimage();
	$spam_html = $resp->show_html();
}

if (isset($_POST['action']) && $_POST['action'] == 'sendmail')
{
	// check errors
	if (empty($_POST['contact_name']) || empty($_POST['contact_email']) || empty($_POST['contact_details']) || empty($_POST['contact_description']))
	{
		$TPL_error_text = $ERRMSG['031'];
	}

	if (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i', $_POST['contact_email']))
	{
		$TPL_error_text = $ERRMSG['008'];
	}
	
	if ($system->SETTINGS['spam_sendtofriend'] == 2)
	{
		$resp = recaptcha_check_answer($system->SETTINGS['recaptcha_private'], $_SERVER['REMOTE_ADDR'], $_POST['recaptcha_challenge_field'], $_POST['recaptcha_response_field']);
		if (!$resp->is_valid)
		{
			$TPL_error_text = $MSG['752'];
		}
	}
	elseif ($system->SETTINGS['spam_sendtofriend'] == 1)
	{
		if (!$resp->check($_POST['captcha_code']))
		{
			$TPL_error_text = $MSG['752'];
		}
	}
	

	if (!empty($TPL_error_text))
	{
		$emailsent = 1;
	}
	else
	{	
		$emailsent = 0;
		$emailer = new email_handler();
		$emailer->assign_vars(array(
				'S_NAME' => $user->user_data['company'],
				'S_EMAIL' => $_POST['email'],
				
				'F_DESCRIPTION' => $_POST['contact_description'],
				'F_DETAILS' => $_POST['contact_details'],
				'F_NAME' => $_POST['contact_name'],
				'F_EMAIL' => $_POST['contact_email'],
				
				'SITENAME' => $system->SETTINGS['sitename'],
				'SITEURL' => $system->SETTINGS['siteurl'],
				'ADMINEMAIL' => $system->SETTINGS['adminmail']
				));
		$emailer->email_sender($system->SETTINGS['adminmail'], 'friendmail.inc.php', $MSG['905']);
	}
}

if ($system->SETTINGS['spam_sendtofriend'] == 2)
{
	$capcha_text = recaptcha_get_html($system->SETTINGS['recaptcha_public']);
}
elseif ($system->SETTINGS['spam_sendtofriend'] == 1)
{
	$capcha_text = $spam_html;
}

$template->assign_vars(array(
		'ERROR' => $TPL_error_text,
		'ID' => intval($_REQUEST['id']),
		'CAPTCHATYPE' => $system->SETTINGS['spam_register'],
		'CAPCHA' => (isset($capcha_text)) ? $capcha_text : '',
		'TITLE' => $TPL_item_title,
		'CONTACT_NAME' => (isset($_POST['contact_name'])) ? $_POST['contact_name'] : '',
		'CONTACT_EMAIL' => (isset($_POST['contact_email'])) ? $_POST['contact_email'] : '',
		'CONTACT_DETAILS' => (isset($_POST['contact_details'])) ? $_POST['contact_details'] : '',
		'CONTACT_DESCRIPTION' => (isset($_POST['contact_description'])) ? $_POST['contact_description'] : '',
		'EMAILSENT' => $emailsent
		));

include 'header.php';
$template->set_filenames(array(
		'body' => 'friend.tpl'
		));
$template->display('body');
include 'footer.php';
?>
