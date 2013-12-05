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

if (isset($_POST['action']) && $_POST['action'] == 'save') {
    $count = count($_POST['users']);
    
    $query = "SELECT count(id) as count FROM " . $DBPrefix . "users WHERE id IN(" . implode(",", $_POST['users']) . ")";
    $result = mysql_query($query);
    $system->check_mysql($result, $query, __LINE__, __FILE__);
    $found = mysql_result($result, 0, 'count');
        
    if($count == $found) {
        $query = "INSERT INTO " . $DBPrefix . "usergroups(user_id, name) VALUES (" . $user->user_data['id'] .", '" . $_POST['name'] . "')";
        $result = mysql_query($query);
        $system->check_mysql($result, $query, __LINE__, __FILE__);        
        $id = mysql_insert_id();
        
        if($count > 0) {
            $query = "INSERT INTO " . $DBPrefix . "usergroup_user (usergroup_id, user_id) VALUES ";
            foreach($_POST['users'] as $userid) {
                $query .=  "(" . $id . ", " . $userid . "),";
            }
            $result = mysql_query(rtrim($query, ','));
            $system->check_mysql($result, $query, __LINE__, __FILE__);
        }
        
        $user->setMessage('edit_usergroups', 'Group "' . $_POST['name'] . '" successfully created.');
        $user->redirect('edit_usergroups.php');
    } else {
        $user->setMessage('edit_usergroups', 'Invalid data provided, please try again.', 'error');
        $user->redirect('edit_usergroups.php');
    }
}

//retrieve user data
$query = "SELECT * FROM " . $DBPrefix . "users";
$result = mysql_query($query);
$system->check_mysql($result, $query, __LINE__, __FILE__);
while($row = mysql_fetch_assoc($result)) {
    $template->assign_block_vars('users', array(
        'ID' => $row['id'],
        'COMPANY' => 'NOT YET IMPLEMENTED',
        'NAME' => $row['name'],
        'EMAIL' => $row['email'],
        'COUNTRY' => $row['country'],
        'CHECKED' => in_array($row['id'], $users) ? 'checked' : ''
    ));
}

include 'header.php';
include $include_path . 'user_cp.php';
$template->set_filenames(array(
		'body' => 'create_usergroup.tpl'
		));
$template->display('body');
include 'footer.php';
?>