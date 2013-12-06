<?php
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

if (isset($_POST['action']) && $_POST['action'] == 'delete') {
    $query = "DELETE FROM " . $DBPrefix . "usergroups WHERE id=" . $_POST['id'] . " AND user_id=" . $user->user_data['id'];   
    $result = mysql_query($query);
    $system->check_mysql($result, $query, __LINE__, __FILE__);
    
    if(mysql_affected_rows() > 0) {
        $user->setMessage('edit_usergroups', 'Group successfully removed.');
    } else {
        $user->setMessage('edit_usergroups', 'Deletion failed. Unknown group or insufficient permissions.', 'error');
    }
    
    die();
}

//retrieve user data
$query = "
    SELECT 
    	u.*, if(temp.Count >= 1,temp.Count,0) as userCount
    FROM 
    	" . $DBPrefix . "usergroups u
    LEFT JOIN
    	(SELECT 
    		u.id, COUNT(uu.usergroup_id) AS Count
    	FROM 
    		" . $DBPrefix . "usergroups u
    	LEFT JOIN " . $DBPrefix . "usergroup_user uu ON uu.usergroup_id = u.id
    	GROUP BY u.id) temp ON temp.Id = u.id
    WHERE
        u.user_id =" . $user->user_data['id'];
$result = mysql_query($query);
$system->check_mysql($result, $query, __LINE__, __FILE__);



while($row = mysql_fetch_assoc($result)) {
    $template->assign_block_vars('usergroups', array(
        'ID' => $row['id'],
        'NAME' => $row['name'],
        'USERCOUNT' => $row['userCount']
    ));
}

$template->assign_vars($user->getMessageVars('edit_usergroups'));


include 'header.php';
include $include_path . 'user_cp.php';
$template->set_filenames(array(
		'body' => 'edit_usergroups.tpl'
		));
$template->display('body');
include 'footer.php';
?>