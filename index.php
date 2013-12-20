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
include $main_path . 'language/' . $language . '/categories.inc.php';

// Run cron according to SETTINGS
if ($system->SETTINGS['cron'] == 2)
{
	include_once 'cron.php';
}

if ($system->SETTINGS['loginbox'] == 1 && $system->SETTINGS['https'] == 'y' && $_SERVER['HTTPS'] != 'on')
{
	$sslurl = str_replace('http://', 'https://', $system->SETTINGS['siteurl']);
	$sslurl = (!empty($system->SETTINGS['https_url'])) ? $system->SETTINGS['https_url'] : $sslurl;
	header('Location: ' . $sslurl . 'index.php');
	exit;
}

$NOW = time();

function ShowFlags()
{
	global $system, $LANGUAGES;
	$counter = 0;
	$flags = '';
	foreach ($LANGUAGES as $lang => $value)
	{
		if ($counter > 3)
		{
			$flags .= '<br>';
			$counter = 0;
		}
		$flags .= '<a href="?lan=' . $lang . '"><img vspace="2" hspace="2" src="' . $system->SETTINGS['siteurl'] . 'includes/flags/' . $lang . '.gif" border="0" alt="' . $lang . '"></a>';
		$counter++;
	}
	return $flags;
}

// prepare categories list for templates/template
// Prepare categories sorting
if ($system->SETTINGS['catsorting'] == 'alpha')
{
	$catsorting = ' ORDER BY cat_name ASC';
}
else
{
	$catsorting = ' ORDER BY sub_counter DESC';
}

$query = "SELECT cat_id FROM " . $DBPrefix . "categories WHERE parent_id = -1";
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);

$query = "SELECT * FROM " . $DBPrefix . "categories
		  WHERE parent_id = " . mysql_result($res, 0) . "
		  " . $catsorting . "
		  LIMIT " . $system->SETTINGS['catstoshow'];
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);

while ($row = mysql_fetch_assoc($res))
{
	$template->assign_block_vars('cat_list', array(
			'CATAUCNUM' => ($row['sub_counter'] != 0) ? '(' . $row['sub_counter'] . ')' : '',
			'ID' => $row['cat_id'],
			'IMAGE' => (!empty($row['cat_image'])) ? '<img src="' . $row['cat_image'] . '" border=0>' : '',
			'COLOUR' => (empty($row['cat_colour'])) ? '#FFFFFF' : $row['cat_colour'],
			'NAME' => $category_names[$row['cat_id']]
			));
}

// get auctions
$query = "SELECT * from " . $DBPrefix . "auctions";
if($user->user_data['id']) {
    $query .= "
        LEFT JOIN
        	webid_auction_user u ON u.auction_id = id
        WHERE
        	(enableusergroups = 0 OR (enableusergroups = 1 AND " . $user->user_data['id'] . " = u.user_id))
        ";
} else {
    $query .= " 
        LEFT JOIN
            webid_auction_user u ON u.auction_id = id
        WHERE
            enableusergroups = 0
    ";
}

$query .= "
         AND closed = 0 AND suspended = 0
		 AND starts <= " . $NOW . "
		 ORDER BY starts DESC";
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);

$i = 0;
while ($row = mysql_fetch_assoc($res))
{
	$template->assign_block_vars('auctions', array(
		'BGCOLOUR' => (!($i % 2)) ? '' : 'class="alt-row"',
		'DATE' => ArrangeDateNoCorrection($row['starts'] + $system->tdiff),
		'IMAGE' => (!empty($row['pict_url'])) ? 'getthumb.php?w=480&fromfile=' . $uploaded_path . $row['id'] . '/' . $row['pict_url'] : 'images/email_alerts/default_item_img.jpg',				//
		'ID' => $row['id'],
		'TITLE' => $row['title'],
		'STARTS' => ArrangeDateNoCorrection($row['starts']),
		'ENDS' => ArrangeDateNoCorrection($row['ends']),
		'BID' => $system->print_money($row['current_bid']),
		'CAT' => $row['category'],
		'CAT_COLOR' => $row['category'] == 198 ? 'green' : 'red'
		));
	$i++;
}

$auc_last = ($i > 0) ? true : false;

// Build list of help topics
$query = "SELECT id, category FROM " . $DBPrefix . "faqscat_translated WHERE lang = '" . $language . "' ORDER BY category ASC";
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);
$i = 0;
while ($faqscat = mysql_fetch_assoc($res))
{
	$template->assign_block_vars('helpbox', array(
			'ID' => $faqscat['id'],
			'TITLE' => $faqscat['category']
			));
	$i++;
}

$helpbox = ($i > 0) ? true : false;
// Build news list
if ($system->SETTINGS['newsbox'] == 1)
{
	$query = "SELECT n.title As t, n.new_date, t.* FROM " . $DBPrefix . "news n
			LEFT JOIN " . $DBPrefix . "news_translated t ON (t.id = n.id)
			WHERE t.lang = '" . $language . "' AND n.suspended = 0
			ORDER BY new_date DESC, id DESC LIMIT " . $system->SETTINGS['newstoshow'];
	$res = mysql_query($query);
	$system->check_mysql($res, $query, __LINE__, __FILE__);
	while ($new = mysql_fetch_assoc($res))
	{
		$template->assign_block_vars('newsbox', array(
				'ID' => $new['id'],
				'DATE' => FormatDate($new['new_date']),
				'TITLE' => (!empty($new['title'])) ? $new['title'] : $new['t']
				));
	}
}

$template->assign_vars(array(
		'FLAGS' => ShowFlags(),
		'B_HELPBOX' => ($helpbox && $system->SETTINGS['helpbox'] == 1),
		'B_MULT_LANGS' => (count($LANGUAGES) > 1),
		'B_LOGIN_BOX' => ($system->SETTINGS['loginbox'] == 1),
		'B_NEWS_BOX' => ($system->SETTINGS['newsbox'] == 1),
		'B_LIST' => (isset($_GET['view']) && $_GET['view'] == 'list')
		));
		
$template->assign_vars(array('B_USER_AUTHENTICATED' => (isset($_SESSION['WEBID_LOGGED_IN']) && $_SESSION['WEBID_LOGGED_IN'] > 0)));

include 'header.php';
$template->set_filenames(array(
		'body' => 'home.tpl'
		));
$template->display('body');
include 'footer.php';

unset($_SESSION['loginerror']);
?>
