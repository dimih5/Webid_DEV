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

if (!$user->is_logged_in())
{
	//if your not logged in you shouldn't be here
	header("location: user_login.php");
	exit;
}
unset($ERR);

// Process delete
if (isset($_GET['action']) && $_GET['action'] == 'delete' && isset($_GET['contr']))
{
	if ($_SESSION['SELL_contr_url_temp'] == $_SESSION['UPLOADED_CONTRACTS'][intval($_GET['contr'])])
	{
		unlink($upload_path . session_id() . '/contracts/' . $_SESSION['SELL_contr_url']);
		unset($_SESSION['SELL_contr_url']);
	}
	unlink($upload_path . session_id() . '/contracts/' . $_SESSION['UPLOADED_CONTRACTS'][intval($_GET['contr'])]);
	unset($_SESSION['UPLOADED_CONTRACTS'][intval($_GET['contr'])]);
	unset($_SESSION['UPLOADED_CONTRACTS_SIZE'][intval($_GET['contr'])]);
}

// close window
if (!empty($_POST['creategallery']))
{
	echo '<script type="text/javascript">window.close()</script>';
	exit;
}
// PROCESS UPLOADED FILE
if (isset($_POST['uploadpicture']))
{
	if (empty($_FILES['userfile']['tmp_name']) && $_FILES['userfile']['tmp_name'] != 'none')
	{
		if (!isset($_SESSION['UPLOADED_CONTRACTS']) || !is_array($_SESSION['UPLOADED_CONTRACTS'])) $_SESSION['UPLOADED_CONTRACTS'] = array();
		if (!isset($_SESSION['UPLOADED_CONTRACTS_SIZE']) || !is_array($_SESSION['UPLOADED_CONTRACTS_SIZE'])) $_SESSION['UPLOADED_CONTRACTS_SIZE'] = array();
		$filename = $_FILES['userfile']['name'];
		$nameparts = explode('.', $filename);
		$ext_key = count($nameparts) - 1;
		$file_ext = strtolower($nameparts[$ext_key]);
		$file_types = array('docx', 'pdf');

		// clean the name
		unset($nameparts[$ext_key]);
		$newname = implode('_', $nameparts);

		$newname = preg_replace('/[^a-zA-Z0-9_]/', '', $newname);
		$newname .= '.' . $file_ext;

		if ($_FILES['userfile']['size'] > $system->SETTINGS['maxuploadsize'])
		{
			$ERR = $ERR_709 . '&nbsp;' . ($system->SETTINGS['maxuploadsize'] / 1024) . '&nbsp;' . $MSG['672'];
		}
		elseif (!in_array($file_ext, $file_types))
		{
			$ERR = $ERR_710 . ' (' . $file_ext . ')';
		}
		elseif (in_array($newname, $_SESSION['UPLOADED_CONTRACTS']))
		{
			$ERR = $MSG['2__0054'] . ' (' . $_FILES['userfile']['name'] . ')';
		}
		else
		{
			// Create a TMP directory for this session (if not already created)
			if (!file_exists($upload_path . session_id() . "/contracts/"))
			{
				umask(0);
				if(!is_dir($upload_path . session_id())) //If there are no pics uploaded yet
				{
					mkdir($upload_path . session_id(), 0777);
					chmod($upload_path . session_id(), 0777); //incase mkdir fails
				}
				
				mkdir($upload_path . session_id() . "/contracts", 0777);
				chmod($upload_path . session_id() . "/contracts", 0777); //incase mkdir fails
				
			}
			// Move uploaded file into TMP directory & rename
			if ($system->move_file($_FILES['userfile']['tmp_name'], $upload_path . session_id() . '/contracts/' . $newname))
			{
				// Populate arrays
				array_push($_SESSION['UPLOADED_CONTRACTS'], $newname);
				$fname = $upload_path . session_id() . '/contracts/' . $newname;
				array_push($_SESSION['UPLOADED_CONTRACTS_SIZE'], filesize($fname));
			}
		}
	}
}
// built gallery
foreach ($_SESSION['UPLOADED_CONTRACTS'] as $k => $v)
{
	$template->assign_block_vars('contracts', array(
			'CTRNAME' => $v,
			'ID' => $k,
			'DEFAULT' => ($v == $_SESSION['SELL_contr_url_temp']) ? 'selected.gif' : 'unselected.gif',
			'CONTRACT' => "images/document.png"
			));
}

if ($system->SETTINGS['fees'] == 'y')
{
	$query = "SELECT value FROM " . $DBPrefix . "fees WHERE type = 'picture_fee'";
	$res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);
	$contract_fee = mysql_result($res, 0);
}
else
{
	$contract_fee = 0;
}

// get decimals for javascript rounder
$decimals = '';
for ($i = 0; $i < $system->SETTINGS['moneydecimals']; $i++)
{
	$decimals .= 0;
}

$template->assign_vars(array(
		'SITENAME' => $system->SETTINGS['sitename'],
		'THEME' => $system->SETTINGS['theme'],
		'ERROR' => (isset($ERR)) ? $ERR : '',
		'ERRORMSG' => sprintf($MSG['674'], $system->SETTINGS['maxcontracts']),
		'MAXCONTR' => $system->SETTINGS['maxcontracts'],
		'MAXCONTRSIZE' => $system->SETTINGS['maxuploadsize'],
		'SESSION_ID' => session_id(),
		'UPLOADED' => intval(count($_SESSION['UPLOADED_CONTRACTS']))
		));
$template->set_filenames(array(
		'body' => 'upldcontract.tpl'
		));
$template->display('body');
?>
