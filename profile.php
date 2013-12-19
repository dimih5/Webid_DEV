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
include $include_path . 'dates.inc.php';
include $include_path . 'membertypes.inc.php';

foreach ($membertypes as $idm => $memtypearr)
{
	$memtypesarr[$memtypearr['feedbacks']] = $memtypearr;
}
ksort($memtypesarr, SORT_NUMERIC);

if(!isset($_GET['user_id']))
{
	$_GET['user_id'] = $user->user_data['id'];
}

if (!empty($_GET['user_id']) && is_string($_GET['user_id']))
{
	$sql = "SELECT * FROM " . $DBPrefix . "users WHERE nick = '" . $system->cleanvars($_GET['user_id']) . "'";
	$res = mysql_query($sql);
	$system->check_mysql($res, $sql, __LINE__, __FILE__);
}

if (!empty($_GET['user_id']))
{
	$sql = "SELECT * FROM " . $DBPrefix . "users WHERE id = " . intval($_GET['user_id']);
	$res = mysql_query($sql);
	$system->check_mysql($res, $sql, __LINE__, __FILE__);
}

if (@mysql_num_rows($res) == 1)
{
    $arr = mysql_fetch_assoc($res);
    $user_id = $arr['id'];
    
    $DATE = $arr['reg_date'] + $system->tdiff;
	$mth = 'MON_0'.gmdate('m', $DATE);
	
    $variables = array(
		'REGSINCE' => $MSG[$mth].' '.gmdate('d, Y', $DATE),
		'COUNTRY' => $arr['country'],
		'AUCTION_ID' => (isset($_GET['auction_id'])) ? $_GET['auction_id'] : '',
		'USER' => $arr['nick'],
		'USER_ID' => $user_id,
		'B_VIEW' => true,
		'B_AUCID' => (isset($_GET['auction_id'])),
		'B_CONTACT' => (($system->SETTINGS['contactseller'] == 'always' || ($system->SETTINGS['contactseller'] == 'logged' && $user->logged_in)) && (!$user->logged_in || $user->user_data['id'] != $TPL_user_id))
		);

    $auctions_count = 0;
    
    // Active auctions
    $NOW = time();
    $query = "SELECT * FROM " . $DBPrefix . "auctions
		WHERE user = " . $user_id . "
		AND closed = 0
		AND starts <= '" . $NOW . "'
		ORDER BY ends ASC";
    $res = mysql_query($query);
    $system->check_mysql($res, $query, __LINE__, __FILE__);
    
    while ($row = mysql_fetch_assoc($res))
    {
    	$bid = $row['current_bid'];
    	$starting_price = $row['current_bid'];
    
    	if (strlen($row['pict_url']) > 0)
    	{
    		$row['pict_url'] = $system->SETTINGS['siteurl'] . 'getthumb.php?w=' . $system->SETTINGS['thumb_show'] . '&fromfile=' . $uploaded_path . $row['id'] . '/' . $row['pict_url'];
    	}
    	else
    	{
    		$row['pict_url'] = get_lang_img('nopicture.gif');
    	}
    
    	// number of bids for this auction
    	$query_ = "SELECT bid FROM " . $DBPrefix . "bids WHERE auction=" . $row['id'];
    	$tmp_res = mysql_query($query_);
    	$system->check_mysql($tmp_res, $query_, __LINE__, __FILE__);
    	$num_bids = mysql_num_rows($tmp_res);
    
    	$difference = time() - $row['ends'];
    	$days_difference = intval($difference / 86400);
    	$difference = $difference - ($days_difference * 86400);
    
    	if (intval($difference / 3600) > 12) $days_difference++;
    
    	$template->assign_block_vars('auctionsa', array(
    			'BGCOLOUR' => (!($TOTALAUCTIONS % 2)) ? '' : 'class="alt-row"',
    			'ID' => $row['id'],
    			'PIC_URL' => $row['pict_url'],
    			'TITLE' => $row['title'],
    			'BNIMG' => get_lang_img(($row['bn_only'] == 'n') ? 'buy_it_now.gif' : 'bn_only.png'),
    			'BNVALUE' => $row['buy_now'],
    			'BNFORMAT' => $system->print_money($row['buy_now']),
    			'BIDVALUE' => $row['minimum_bid'],
    			'BIDFORMAT' => $system->print_money($row['minimum_bid']),
    			'NUM_BIDS' => $num_bids,
    			'TIMELEFT' => $days_difference . ' ' . $MSG['126a'],
    
    			'B_BUY_NOW' => ($row['buy_now'] > 0 && ($row['bn_only'] == 'y' || $row['bn_only'] == 'n' && ($row['num_bids'] == 0 || ($row['reserve_price'] > 0 && $row['current_bid'] < $row['reserve_price'])))),
    			'B_BNONLY' => ($row['bn_only'] == 'y')
    			));
    
    	$auctions_count++;
    }
    
    // Closed auctions
    $query = "SELECT * FROM " . $DBPrefix . "auctions
    		WHERE user = " . intval($user_id) . "
    		AND closed = 1
    		ORDER BY ends ASC";
    $res = mysql_query($query);
    $system->check_mysql($res, $query, __LINE__, __FILE__);
    
    while ($row = mysql_fetch_assoc($res))
    {
    	$bid = $row['current_bid'];
    	$starting_price = $row['current_bid'];
    
    	if (strlen($row['pict_url']) > 0)
    	{
    		$row['pict_url'] = $system->SETTINGS['siteurl'] . 'getthumb.php?w=' . $system->SETTINGS['thumb_show'] . '&fromfile=' . $uploaded_path . $row['id'] . '/' . $row['pict_url'];
    	}
    	else
    	{
    		$row['pict_url'] = get_lang_img('nopicture.gif');
    	}
    
    	// number of bids for this auction
    	$query_ = "SELECT bid FROM " . $DBPrefix . "bids WHERE auction=" . $row['id'];
    	$tmp_res = mysql_query($query_);
    	$system->check_mysql($tmp_res, $query_, __LINE__, __FILE__);
    	$num_bids = mysql_num_rows($tmp_res);
    
    	$difference = time() - $row['ends'];
    	$days_difference = intval($difference / 86400);
    	$difference = $difference - ($days_difference * 86400);
    
    	if (intval($difference / 3600) > 12) $days_difference++;
    
    	$template->assign_block_vars('auctions', array(
    			'BGCOLOUR' => (!($TOTALAUCTIONS % 2)) ? '' : 'class="alt-row"',
    			'ID' => $row['id'],
    			'PIC_URL' => $row['pict_url'],
    			'TITLE' => $row['title'],
    			'BNIMG' => get_lang_img(($row['bn_only'] == 'n') ? 'buy_it_now.gif' : 'bn_only.png'),
    			'BNVALUE' => $row['buy_now'],
    			'BNFORMAT' => $system->print_money($row['buy_now']),
    			'BIDVALUE' => $row['minimum_bid'],
    			'BIDFORMAT' => $system->print_money($row['minimum_bid']),
    			'NUM_BIDS' => $num_bids,
    			'TIMELEFT' => $days_difference . ' ' . $MSG['126a'],
    
    			'B_BUY_NOW' => ($row['buy_now'] > 0 && ($row['bn_only'] == 'y' || $row['bn_only'] == 'n' && ($row['num_bids'] == 0 || ($row['reserve_price'] > 0 && $row['current_bid'] < $row['reserve_price'])))),
    			'B_BNONLY' => ($row['bn_only'] == 'y')
    			));
    
    	$auctions_count++;
    }

}
else
{
	$variables = array(
		'B_VIEW' => false,
		'MSG' => $ERRMSG['025']
		);
}

$variables['SUM_FB'] = $auctions_count;
$template->assign_vars($variables);

include 'header.php';
$template->set_filenames(array(
		'body' => 'profile.tpl'
		));
$template->display('body');
include 'footer.php';
?>
