<?php

include 'common.php';
include $include_path . 'datacheck.inc.php';

if (!$user->is_logged_in())
{
	$_SESSION['REDIRECT_AFTER_LOGIN'] = 'bid.php?id=' . $id;
	header('location: user_login.php');
	exit;
}

$id = intval($_REQUEST['id']);
$days = intval($_REQUEST['days']);
$errmsg = 'You will receive an email in ' . $days .  ' days';

if($days > 0 && $days < 10) {
    
    $query = "SELECT * FROM " . $DBPrefix . "auctions WHERE id = " . $id . " LIMIT 1";
    $res = mysql_query($query);
    $system->check_mysql($res, $query, __LINE__, __FILE__);
    if (mysql_num_rows($res) > 0) {
        $auction = mysql_fetch_assoc($res);
        $senddate = date('Y-m-d', strtotime('-' . $days .' day', $auction['ends']));
        $senddate_raw = strtotime($senddate);

        if($senddate_raw > time()) {
        
            $query = "SELECT id FROM " . $DBPrefix . "reminders WHERE user_id = " . $user->getId() . " AND auction_id = " . $id . " AND date = " . $senddate_raw . ' LIMIT 1';
            $res = mysql_query($query);
            $system->check_mysql($res, $query, __LINE__, __FILE__);
            if (mysql_num_rows($res) == 0) {
            
                $query = "INSERT INTO " . $DBPrefix . "reminders (user_id, auction_id, date) VALUES (" . $user->getId() . ", " . $id . ", " . $senddate_raw . ");";
                $res = mysql_query($query);
                $system->check_mysql($res, $query, __LINE__, __FILE__);
                
                $errmsg = 'A reminder will be send to your email address on ' . $senddate;    
            } else {
                $errmsg = 'The system already planned to remind you on ' . $senddate;
            }
            
        } else {
            $errmsg = 'The provided date is in the past.';
        }
        
    } else {
        $errmsg = 'Invalid auction provided.';
    }
    
} else {
    $errmsg = 'Invalid days provided.';
}




$template->assign_vars(array(
    'ERROR' => $errmsg,
    'ID' => $id,
));

include 'header.php';
$template->set_filenames(array(
		'body' => 'remind.tpl'
		));
$template->display('body');
include 'footer.php';