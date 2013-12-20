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

unset($ERR);
$ERR;

// Delete request
if($_REQUEST['action'] && $_REQUEST['action'] == 'delete') {
    $query = "DELETE FROM " . $DBPrefix . "user_user WHERE id =" . $_REQUEST['id'];
    $res = mysql_query($query);
    $system->check_mysql($res, $query, __LINE__, __FILE__);
    $ERR .= '<br/>' . $MSG['P15'];
}

// Add request
if($_REQUEST['action'] && $_REQUEST['action'] == 'add') {
    $userA = isset($_REQUEST['user_a']) ? $_REQUEST['user_a'] : 0;
    $userB = isset($_REQUEST['user_b']) ? $_REQUEST['user_b'] : 0;
    
    // Incomplete information received
    if($userA == 0 || $userB == 0) {
        $ERR .= '<br/>' . $MSG['P16'];
    }
    
    // UserA cannot be equal to userB
    if($userA == $userB) {
        $ERR .= '<br/>' . $MSG['P17'];
    }

    // Check if relation already exists in DB    
    if(!$ERR) {
        $query = "
            SELECT
            	uu.id,
            	ua.id as user_a_id,
            	ua.company as user_a_company,
            	ua.email as user_a_email,
            	ub.id as user_b_id,
            	ub.company as user_b_company,
            	ub.email as user_b_email
            FROM
            	" . $DBPrefix . "user_user uu
            LEFT JOIN
            	" . $DBPrefix . "users ua ON ua.id = user_a_id
            LEFT JOIN
            	" . $DBPrefix . "users ub ON ub.id = user_b_id";
        $res = mysql_query($query);
        $system->check_mysql($res, $query, __LINE__, __FILE__);
        while ($row = mysql_fetch_assoc($res)) {

            if($row['user_a_id'] == $userA && $row['user_b_id'] == $userB) {
                $ERR .= '<br/>' . $MSG['P18'];
            }
            if($row['user_a_id'] == $userB && $row['user_b_id'] == $userA) {
                $ERR .= '<br/>' . $MSG['P18'];
            }
        }
    }
    
    // No errors, let's add the new duplicate relation
    if(!$ERR) {
        $query = "
            INSERT INTO
                " . $DBPrefix . "user_user(user_a_id, user_b_id) 
                VALUES (". $userA . ", " . $userB . ")";
        $res = mysql_query($query);
        $system->check_mysql($res, $query, __LINE__, __FILE__);
        $ERR .= '<br/>' . $MSG['P19'];
        
        // Requery the list so it shows our new entry
    }
}

// Get table data
$query = "
    SELECT
    	uu.id,
    	ua.id as user_a_id,
    	ua.company as user_a_company,
    	ua.email as user_a_email,
    	ub.id as user_b_id,
    	ub.company as user_b_company,
    	ub.email as user_b_email
    FROM
    	" . $DBPrefix . "user_user uu
    LEFT JOIN
    	" . $DBPrefix . "users ua ON ua.id = user_a_id
    LEFT JOIN
    	" . $DBPrefix . "users ub ON ub.id = user_b_id";
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);
while ($row = mysql_fetch_assoc($res)) {
    $template->assign_block_vars('users', array(
		'ID' => $row['id'],
		'USER_A_ID' => $row['user_a_id'],
		'USER_A_COMPANY' => $row['user_a_company'],
		'USER_A_EMAIL' => $row['user_a_email'],
		'USER_B_ID' => $row['user_b_id'],
		'USER_B_COMPANY' => $row['user_b_company'],
		'USER_B_EMAIL' => $row['user_b_email'],
	));
}


// Get form data
$query = "
    SELECT
        u.id,
        u.company,
        u.email
    FROM
        " . $DBPrefix . "users u";
$res = mysql_query($query);
$system->check_mysql($res, $query, __LINE__, __FILE__);
while ($row = mysql_fetch_assoc($res)) {
    $template->assign_block_vars('form_users', array(
		'ID' => $row['id'],
		'COMPANY' => $row['company'],
		'EMAIL' => $row['email'],
	));
}

$template->assign_vars(array(
		'ERROR' => (isset($ERR)) && !is_array($ERR) ? $ERR : '',
		'TOTALUSERS' => $TOTALUSERS,
		'USERFILTER' => (isset($_SESSION['usersfilter'])) ? $_SESSION['usersfilter'] : '',

		'PREV' => ($PAGES > 1 && $PAGE > 1) ? '<a href="' . $system->SETTINGS['siteurl'] . 'admin/listusers.php?PAGE=' . $PREV . '"><u>' . $MSG['5119'] . '</u></a>&nbsp;&nbsp;' : '',
		'NEXT' => ($PAGE < $PAGES) ? '<a href="' . $system->SETTINGS['siteurl'] . 'admin/listusers.php?PAGE=' . $NEXT . '"><u>' . $MSG['5120'] . '</u></a>' : '',
		'PAGE' => $PAGE,
		'PAGES' => $PAGES
		));
		
$template->set_filenames(array(
		'body' => 'duplicates.tpl'
		));
$template->display('body');
?>
