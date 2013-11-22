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
include $include_path . 'countries.inc.php';
$ERR;
// If user is not logged in redirect to login page
if (!$user->is_logged_in())
{
	$_SESSION['REDIRECT_AFTER_LOGIN'] = 'edit_data.php';
	header('location: user_login.php');
	exit;
}
//retrieve user data
$query = "SELECT * FROM " . $DBPrefix . "users WHERE id = " . $user->user_data['id'];
$result = mysql_query($query);
$system->check_mysql($result, $query, __LINE__, __FILE__);
$USER = mysql_fetch_assoc($result);
// Retrieve users signup settings
$MANDATORY_FIELDS = unserialize($system->SETTINGS['mandatory_fields']);

function generateSelect($name = '', $options = array())
{
	global $selectsetting;
	$html = '<select name="' . $name . '">';
	foreach ($options as $option => $value)
	{
		if ($selectsetting == $option)
		{
			$html .= '<option value=' . $option . ' selected>' . $value . '</option>';
		}
		else
		{
			$html .= '<option value=' . $option . '>' . $value . '</option>';
		}
	}
	$html .= '</select>';
	return $html;
}

$TIMECORRECTION = array();
for ($i = 12; $i > -13; $i--)
{
	$TIMECORRECTION[$i] = $MSG['TZ_' . $i];
}

$query = "SELECT * FROM " . $DBPrefix . "gateways LIMIT 1";
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);
$gateway_data = mysql_fetch_assoc($res);

if (isset($_POST['action']) && $_POST['action'] == 'update')
{
	// Check data
	if ($_POST['TPL_email'])
	{
		if (strlen($_POST['TPL_password']) < 6 && strlen($_POST['TPL_password']) > 0)
		{
			$ERR .= '<br/>' . $ERRMSG['011'];
		}
		elseif ($_POST['TPL_password'] != $_POST['TPL_repeat_password'])
		{
			$ERR .= '<br/>' . $ERRMSG['109'];
		}
		elseif (strlen($_POST['TPL_email']) < 5)
		{
			$ERR .= '<br/>' . $ERRMSG['110'];
		}
		elseif (!preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i', $_POST['TPL_email']))
		{
			$ERR .= '<br/>' . $ERRMSG['008'];
		}
		elseif (strlen($_POST['TPL_zip']) < 4 && $MANDATORY_FIELDS['zip'] == 'y')
		{
			$ERR .= '<br/>' . $ERRMSG['616'];
		}
		elseif (strlen($_POST['TPL_phone']) < 3 && $MANDATORY_FIELDS['tel'] == 'y')
		{
			$ERR .= '<br/>' . $ERRMSG['617'];
		}
		elseif ((empty($_POST['TPL_day']) || empty($_POST['TPL_month']) || empty($_POST['TPL_year'])) && $MANDATORY_FIELDS['birthdate'] == 'y')
		{
			$ERR .= '<br/>' . $MSG['948'];
		}
		elseif (!empty($_POST['TPL_day']) && !empty($_POST['TPL_month']) && !empty($_POST['TPL_year']) && !checkdate($_POST['TPL_month'], $_POST['TPL_day'], $_POST['TPL_year']))
		{
			$ERR .= '<br/>' . $ERRMSG['117'];
		}
		elseif ($gateway_data['paypal_required'] == 1 && (empty($_POST['TPL_pp_email']) || !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i', $_POST['TPL_pp_email'])))
		{
			$ERR .= '<br/>' . $MSG['810'];
		}
		elseif ($gateway_data['authnet_required'] == 1 && (empty($_POST['TPL_authnet_id']) || empty($_POST['TPL_authnet_pass'])))
		{
			$ERR .= '<br/>' . $MSG['811'];
		}
		elseif ($gateway_data['moneybookers_required'] == 1 && (empty($_POST['TPL_moneybookers_email']) || !preg_match('/^[_a-z0-9-]+(\.[_a-z0-9-]+)*@[a-z0-9-]+([\.][a-z0-9-]+)+$/i', $_POST['TPL_moneybookers_email'])))
		{
			$ERR .= '<br/>' . $MSG['822'];
		}
		elseif ($gateway_data['toocheckout_required'] == 1 && (empty($_POST['TPL_toocheckout_id'])))
		{
			$ERR .= '<br/>' . $MSG['821'];
		}
		elseif ($gateway_data['worldpay_required'] == 1 && (empty($_POST['TPL_worldpay_id'])))
		{
			$ERR .= '<br/>' . $MSG['823'];
		}
		else
		{
			if (!empty($_POST['TPL_day']) && !empty($_POST['TPL_month']) && !empty($_POST['TPL_year']))
			{
				$TPL_birthdate = $_POST['TPL_year'] . $_POST['TPL_month'] . $_POST['TPL_day'];
			}
			else
			{
				$TPL_birthdate = '';
			}
			$query = "SELECT * FROM " . $DBPrefix . "usersupdate WHERE userid='" . $user->user_data['id'] . "'";
			$res = mysql_query($query);
			$system->check_mysql($res, $query, __LINE__, __FILE__);
			$row = mysql_fetch_assoc($res);
			if($row == 0)
			{
				$query = "SELECT * FROM " . $DBPrefix . "users WHERE id='" . $user->user_data['id'] . "'";
				$res = mysql_query($query);
			    $system->check_mysql($res, $query, __LINE__, __FILE__);
				$row = $row = mysql_fetch_assoc($res);
			}
			
			while($row)
			{
				if(isset($row['userid']) && $row['userid'] == $user->user_data['id'])
				{
					$queryin = "UPDATE " . $DBPrefix . "usersupdate SET 
					email='" . $system->cleanvars($_POST['TPL_email']) . "', 
					birthdate='" . (empty($TPL_birthdate) ? 0 : $TPL_birthdate) . "', 
					address='" . $system->cleanvars($_POST['TPL_address']) . "',
					city='" . $system->cleanvars($_POST['TPL_city']) . "',
					prov='" . $system->cleanvars($_POST['TPL_prov']) . "',
					country='" . $system->cleanvars($_POST['TPL_country']) . "',
					zip='" . $system->cleanvars($_POST['TPL_zip']) . "',
					phone='" . $system->cleanvars($_POST['TPL_phone']) . "',
					timecorrection='" . $system->cleanvars($_POST['TPL_timezone']) . "',
					emailtype='" . $system->cleanvars($_POST['TPL_emailtype']) . "',
					nletter='" . $system->cleanvars($_POST['TPL_nletter']). "'";

					if ($gateway_data['paypal_active'] == 1)
					{
						$queryin .= ", paypal_email='" . $system->cleanvars($_POST['TPL_pp_email']) . "'";
					}

					if ($gateway_data['authnet_active'] == 1)
					{
						$queryin .= ", authnet_id='" . $system->cleanvars($_POST['TPL_authnet_id']) . "', authnet_pass = '" . $system->cleanvars($_POST['TPL_authnet_pass']) . "'";
					}

					if ($gateway_data['worldpay_active'] == 1)
					{
						$queryin .= ", worldpay_id='" . $system->cleanvars($_POST['TPL_worldpay_id']) . "'";
					}

					if ($gateway_data['moneybookers_active'] == 1)
					{
						$queryin .= ", moneybookers_email='" .  $system->cleanvars($_POST['TPL_moneybookers_email']) . "'";
					}

					if (strlen($_POST['TPL_password']) > 0)
					{
						$querypass = "UPDATE " . $DBPrefix . "usersupdate SET  password='" . md5($MD5_PREFIX . $_POST['TPL_password']) . "'";
						$res = mysql_query($querypass);
						$system->check_mysql($res, $query, __LINE__, __FILE__);
					}
					
					$queryin .= " WHERE userid='" . $user->user_data['id'] . "'";
					$res = mysql_query($queryin);
					$system->check_mysql($res, $queryin, __LINE__, __FILE__);
					$ERR .= '<br/>' . $MSG['183'];
					$row = null;
				}
				else
				{
					$querystart = "INSERT INTO " . $DBPrefix . "usersupdate";
					$querymiddle = "(id, email, userid, birthdate, address, city, prov, country, zip, phone, timecorrection, emailtype, nletter";
					$queryend = " VALUES (NULL, '" . $system->cleanvars($_POST['TPL_email']) . "', '" . $user->user_data['id'] . "', '" . (empty($TPL_birthdate) ? 0 : $TPL_birthdate) . "', '" . $system->cleanvars($_POST['TPL_address']) . "', '" . $system->cleanvars($_POST['TPL_city']) . "', '" . $system->cleanvars($_POST['TPL_prov']) . "', '" . $system->cleanvars($_POST['TPL_country']) . "', '" . $system->cleanvars($_POST['TPL_zip']) . "', '" . $system->cleanvars($_POST['TPL_phone']) . "', '" . $system->cleanvars($_POST['TPL_timezone']) . "', '" . $system->cleanvars($_POST['TPL_emailtype']) . "', '" . $system->cleanvars($_POST['TPL_nletter']);

					if ($gateway_data['paypal_active'] == 1)
					{
						$querymiddle .= ", paypal_email";
						$queryend .= "', '" . $system->cleanvars($_POST['TPL_pp_email']);
					}

					if ($gateway_data['authnet_active'] == 1)
					{
						$querymiddle .= ", authnet_id, authnet_pass";
						$queryend .= "', '" . $system->cleanvars($_POST['TPL_authnet_id']) . "', '" . $system->cleanvars($_POST['TPL_authnet_pass']);
					}

					if ($gateway_data['worldpay_active'] == 1)
					{
						$querymiddle .= ", worldpay_id";
						$queryend .= "', '" . $system->cleanvars($_POST['TPL_worldpay_id']);
					}

					if ($gateway_data['moneybookers_active'] == 1)
					{
						$querymiddle .= ", moneybookers_email" ;
						$queryend .= "', '" . $system->cleanvars($_POST['TPL_moneybookers_email']);
					}

					if ($gateway_data['toocheckout_active'] == 1)
					{
						$querymiddle .= ", toocheckout_id";
						$queryend .= "', '" . $system->cleanvars($_POST['TPL_toocheckout_id']);
					}

					if (strlen($_POST['TPL_password']) > 0)
					{
						$querypass = "UPDATE " . $DBPrefix . "usersupdate SET  password='" . md5($MD5_PREFIX . $_POST['TPL_password']) . "'";
						$res = mysql_query($querypass);
						$system->check_mysql($res, $querypass, __LINE__, __FILE__);
					}

					$query = $querystart . $querymiddle . ") " . $queryend . "')";
					
					$res = mysql_query($query);
					$system->check_mysql($res, $query, __LINE__, __FILE__);
					$ERR .= '<br/>' . $MSG['183'];
					$row = null;
				}
			}
			$emailer = new email_handler();
			$emailer->assign_vars(array(
			'SITENAME' => $system->SETTINGS['sitename'],
			'SITEURL' => $system->SETTINGS['siteurl'],
			'ADMINMAIL' => $system->SETTINGS['adminmail'],
			'APPROVEURL' => $system->SETTINGS['siteurl'] . "admin/" . 'evaluateuserchanges.php?id=' . $USER['id'],
			'C_NAME' => $USER['name']
			));
			$emailer->email_uid = $USER['id'];
			$emailer->email_sender($system->SETTINGS['adminmail'], 'admin_user_changes_mail.inc.php', $system->SETTINGS['sitename'] . ' ' . 'User changed');
            
		}
	}
	else
	{
		$ERR .= '<br/>' . $ERRMSG['112'];
	}
}

// Retrieve user's data
if ($USER['birthdate'] != 0)
{
	$TPL_day = substr($USER['birthdate'], 6, 2);
	$TPL_month = substr($USER['birthdate'], 4, 2);
	$TPL_year = substr($USER['birthdate'], 0, 4);
}
else
{
	$TPL_day = '';
	$TPL_month = '';
	$TPL_year = '';
}

$country = '';
foreach ($countries as $code => $name)
{
	$country .= '<option value="' . $name . '"';
	if ($name == $USER['country'])
	{
		$country .= ' selected';
	}
	$country .= '>' . $name . '</option>' . "\n";
}
$dobmonth = '<select name="TPL_month">
		<option value=""></option>
		<option value="01"' . (($TPL_month == '01') ? ' selected' : '') . '>' . $MSG['MON_001E'] . '</option>
		<option value="02"' . (($TPL_month == '02') ? ' selected' : '') . '>' . $MSG['MON_002E'] . '</option>
		<option value="03"' . (($TPL_month == '03') ? ' selected' : '') . '>' . $MSG['MON_003E'] . '</option>
		<option value="04"' . (($TPL_month == '04') ? ' selected' : '') . '>' . $MSG['MON_004E'] . '</option>
		<option value="05"' . (($TPL_month == '05') ? ' selected' : '') . '>' . $MSG['MON_005E'] . '</option>
		<option value="06"' . (($TPL_month == '06') ? ' selected' : '') . '>' . $MSG['MON_006E'] . '</option>
		<option value="07"' . (($TPL_month == '07') ? ' selected' : '') . '>' . $MSG['MON_007E'] . '</option>
		<option value="08"' . (($TPL_month == '08') ? ' selected' : '') . '>' . $MSG['MON_008E'] . '</option>
		<option value="09"' . (($TPL_month == '09') ? ' selected' : '') . '>' . $MSG['MON_009E'] . '</option>
		<option value="10"' . (($TPL_month == '10') ? ' selected' : '') . '>' . $MSG['MON_010E'] . '</option>
		<option value="11"' . (($TPL_month == '11') ? ' selected' : '') . '>' . $MSG['MON_011E'] . '</option>
		<option value="12"' . (($TPL_month == '12') ? ' selected' : '') . '>' . $MSG['MON_012E'] . '</option>
	</select>';
$dobday = '<select name="TPL_day">
		<option value=""></option>';
for ($i = 1; $i <= 31; $i++)
{
	$j = (strlen($i) == 1) ? '0' . $i : $i;
	$dobday .= '<option value="' . $j . '"' . (($TPL_day == $j) ? ' selected' : '') . '>' . $j . '</option>';
}
$dobday .= '</select>';

$selectsetting = $USER['timecorrection'];
$time_correction = generateSelect('TPL_timezone', $TIMECORRECTION);

$template->assign_vars(array(
		'COUNTRYLIST' => $country,
		'NAME' => $USER['name'],
		'NICK' => $USER['nick'],
		'EMAIL' => $USER['email'],
		'YEAR' => $TPL_year,
		'ADDRESS' => $USER['address'],
		'CITY' => $USER['city'],
		'PROV' => $USER['prov'],
		'ZIP' => $USER['zip'],
		'PHONE' => $USER['phone'],
		'DATEFORMAT' => ($system->SETTINGS['datesformat'] == 'USA') ? $dobmonth . ' ' . $dobday : $dobday . ' ' . $dobmonth,
		'TIMEZONE' => $time_correction,

		//payment stuff
		'PP_EMAIL' => $USER['paypal_email'],
		'AN_ID' => $USER['authnet_id'],
		'AN_PASS' => $USER['authnet_pass'],
		'WP_ID' => $USER['worldpay_id'],
		'TC_ID' => $USER['toocheckout_id'],
		'MB_EMAIL' => $USER['moneybookers_email'],

		'NLETTER1' => ($USER['nletter'] == 1) ? ' checked="checked"' : '',
		'NLETTER2' => ($USER['nletter'] == 2) ? ' checked="checked"' : '',
		'EMAILTYPE1' => ($USER['emailtype'] == 'html') ? ' checked="checked"' : '',
		'EMAILTYPE2' => ($USER['emailtype'] == 'text') ? ' checked="checked"' : '',

		'B_NEWLETTER' => ($system->SETTINGS['newsletter'] == 1),
		'B_PAYPAL' => ($gateway_data['paypal_active'] == 1),
		'B_AUTHNET' => ($gateway_data['authnet_active'] == 1),
		'B_WORLDPAY' => ($gateway_data['worldpay_active'] == 1),
		'B_TOOCHECKOUT' => ($gateway_data['toocheckout_active'] == 1),
		'B_MONEYBOOKERS' => ($gateway_data['moneybookers_active'] == 1)
		));

$TMP_usmenutitle = $MSG['509'];
include 'header.php';
include $include_path . 'user_cp.php';
$template->set_filenames(array(
		'body' => 'edit_data.tpl'
		));
$template->display('body');
include 'footer.php';
?>