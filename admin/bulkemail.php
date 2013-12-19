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

define('InAdmin', 1);
$current_page = 'users';
include '../common.php';
include $include_path . 'functions_admin.php';
include 'loggedin.inc.php';

if(isset($_POST['action']) && $_POST['action'] == 'bulkemail') {
    $msg = $_POST['msg'];
    $subject = $_POST['subject'];
    
    $usercount = 0;
    $query = "SELECT id, email FROM " . $DBPrefix . "users";
    $res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);
	while ($row = mysql_fetch_assoc($res)) {
    	$emailer = new email_handler();
        $emailer->email_uid = $row['id'];
        $emailer->email_basic($subject, $row['email'], nl2br($msg), $system->SETTINGS['sitename'] . '<'. $system->SETTINGS['adminmail'] . '>');
        
        $usercount++;
	}    
	
	$user->setMessage('bulkemail', 'Successfully send ' . $usercount . ' emails.');
}

$template->assign_vars($user->getMessageVars('bulkemail'));
$template->assign_vars(array(
    'CURRENT_PAGE' => 'users'
		));
		
$template->set_filenames(array(
		'body' => 'bulkemail.tpl'
		));

$template->display('body');

?>
