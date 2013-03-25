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
 /***************************************************************************
 *   MOD MADE BY MAIKELB
 ***************************************************************************/
 define('InAdmin', 1);
 $current_page = 'settings';
 include '../common.php';
 include $include_path . 'functions_admin.php';
 include 'loggedin.inc.php';
 
 unset($ERR);
 
 if (isset($_POST['VIPemailStatus']) && isset($_POST['VIPemail']))
 {
	$query = "UPDATE " . $DBPrefix . "settings SET 
	VIPemailStatus = '" . $_POST['VIPemailStatus'] . "',
	VIPemail = '" . $_POST['VIPemail'] . "'";
	$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
	$system->SETTINGS['VIPemailStatus'] = $_POST['VIPemailStatus'];
	$system->SETTINGS['VIPemail'] = $_POST['VIPemail'];
	
	$ERR = $MSG['CM_2026_0027'];
 }
 
 loadblock($MSG['CM_2026_0022'], $MSG['CM_2026_0023'], 'yesno', 'VIPemailStatus', $system->SETTINGS['VIPemailStatus'], array($MSG['030'], $MSG['029']));
 loadblock($MSG['CM_2026_0024'], $MSG['CM_2026_0025'], 'text', 'VIPemail', $system->SETTINGS['VIPemail']);
 
  $template->assign_vars(array(
		'ERROR' => (isset($ERR)) ? $ERR : '',
		'SITEURL' => $system->SETTINGS['siteurl'],
		'TYPENAME' => $MSG['CM_2026_0007'],
		'PAGENAME' => $MSG['CM_2026_0009'],
		'B_TITLES' => true
		));
		
$template->set_filenames(array(
		'body' => 'adminpages.tpl'
		));
$template->display('body');
?>