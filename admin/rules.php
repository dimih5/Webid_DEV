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
$current_page = 'contents';
include '../common.php';
include $include_path . 'functions_admin.php';
include 'loggedin.inc.php';
include $main_path . 'ckeditor/ckeditor.php';

unset($ERR);

if (isset($_POST['action']) && $_POST['action'] == 'update')
{
	// Update database
	$query = "UPDATE " . $DBPrefix . "settings SET
			rulestext = '" . mysql_real_escape_string($_POST['rulestext']) . "'";
	$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
	$system->SETTINGS['rulestext'] = $_POST['rulestext'];
	$ERR .= '<br/>' . $MSG['P9'];
}
//loadblock($MSG['403'], $MSG['405'], 'yesno', 'privacypolicy', $system->SETTINGS['privacypolicy'], array($MSG['030'], $MSG['029']));

$CKEditor = new CKEditor();
$CKEditor->basePath = $main_path . 'ckeditor/';
$CKEditor->returnOutput = true;
$CKEditor->config['width'] = 550;
$CKEditor->config['height'] = 400;

loadblock($MSG['404'], $MSG['5080'], $CKEditor->editor('rulestext', stripslashes($system->SETTINGS['rulestext'])));

$template->assign_vars(array(
		'ERROR' => (isset($ERR)) && !is_array($ERR) ? $ERR : '',
		'SITEURL' => $system->SETTINGS['siteurl'],
		'TYPENAME' => $MSG['25_0018'],
		'PAGENAME' => $MSG['P8']
		));

$template->set_filenames(array(
		'body' => 'adminpages.tpl'
		));
$template->display('body');
?>
