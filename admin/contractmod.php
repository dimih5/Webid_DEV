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
 
 if (isset($_POST['action']) && $_POST['action'] == 'update')
 {
	$query = "UPDATE " . $DBPrefix . "settings SET
	contractsmap = '" . $_POST['contractsmap'] . "',
	maxcontracts = '" . $_POST['maxcontracts'] . "'";
	$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
	$system->SETTINGS['contractsmap'] = $_POST['contractsmap'];
	$system->SETTINGS['maxcontracts'] = $_POST['maxcontracts'];
	$ERR = $MSG['CM_2026_0013'];
 }
 loadblock($MSG['CM_2026_0011'], $MSG['CM_2026_0012'], 'yesno', 'contractsmap', $system->SETTINGS['contractsmap'], array($MSG['030'], $MSG['029']));
 loadblock($MSG['CM_2026_0014'], $MSG['CM_2026_0015'], 'text', 'maxcontracts', $system->SETTINGS['maxcontracts'], array($MSG['030'], $MSG['029']));
 
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