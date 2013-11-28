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
include $include_path . 'countries.inc.php';
include $include_path . 'membertypes.inc.php';

if (!isset($_REQUEST['id']))
{
	header('location: listusers.php?');
	exit;
}
//PAGE DATA
$query = "SELECT * FROM " . $DBPrefix . "usersupdate WHERE userid = " . intval($_GET['id']);
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);
$update_data = mysql_fetch_assoc($res);

$query = "SELECT * FROM " . $DBPrefix . "users WHERE id = " . intval($_GET['id']);
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);
$user_data = mysql_fetch_assoc($res);
//


/*
if (isset($_POST['action']) && $_POST['action'] == $MSG['030'])
{
	if ($_POST['mode'] == 'approve')
	{
*/
		$query = "UPDATE " . $DBPrefix . "users SET 
					email='" . $update_data['email']  . "', 
					birthdate='" . $update_data['birthdate'] . "', 
					address='" . $update_data['address'] . "',
					city='" . $update_data['city'] . "',
					prov='" . $update_data['prov'] . "',
					country='" . $update_data['country'] . "',
					zip='" . $update_data['zip'] . "',
					phone='" . $update_data['phone'] . "',
					timecorrection='" . $update_data['timecorrection'] . "',
					emailtype='" . $update_data['emailtype'] . "',
					active_conditions='" . $update_data['active_conditions'] . "',
					nletter='" . $update_data['nletter'] . "'";
					$query .= ", paypal_email='" . $update_data['paypal_email'] . "'";
					$query .= ", authnet_id='" . $update_data['authnet_id'] . "', authnet_pass = '" . $update_data['authnet_pass'] . "'";
					$query .= ", worldpay_id='" . $update_data['worldpay_id'] . "'";
					$query .= ", moneybookers_email='" . $update_data['moneybookers_email'] . "'";
					$query .= ", toocheckout_id='" . $update_data['toocheckout_id'] . "' WHERE id = " . $_GET['id'];
		$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);
				$query = "DELETE FROM " . $DBPrefix . "usersupdate WHERE userid=" . $_GET['id'];
		$system->check_mysql(mysql_query($query), $query, __LINE__, __FILE__);

		include $include_path . 'email_user_approved.php';
/* 	} */
	header('location: listusers.php?PAGE=' . intval($_POST['offset']));
	exit;
/*
}
elseif (isset($_POST['action']) && $_POST['action'] == $MSG['029'])
{
	header('location: listusers.php?PAGE=' . intval($_POST['offset']));	
    exit;
}
*/

// load the page
$birth_day = substr($update_data['birthdate'], 6, 2);
$birth_month = substr($update_data['birthdate'], 4, 2);
$birth_year = substr($update_data['birthdate'], 0, 4);

if ($system->SETTINGS['datesformat'] == 'USA')
{
	$birthdate = $birth_month . '/' . $birth_day . '/' . $birth_year;
}
else
{
	$birthdate = $birth_day . '/' . $birth_month . '/' . $birth_year;
}
$mode = 'approve';
$template->assign_vars(array(
		'ACTION' => $action,
		'REALNAME' => $user_data['name'],
		'USERNAME' => $user_data['nick'],
		'EMAIL' => $update_data['email'],
		'ADDRESS' => $update_data['address'],
		'PROV' => $update_data['prov'],
		'ZIP' => $update_data['zip'],
		'COUNTRY' => $update_data['country'],
		'PHONE' => $update_data['phone'],
		'DOB' => $birthdate,
		'QUESTION' => $question,
		'MODE' => $mode,
		'ID' => $_GET['id'],
		'OFFSET' => $_GET['offset']
		));

$template->set_filenames(array(
		'body' => 'evaluateuserchanges.tpl'
		));
$template->display('body');
?>