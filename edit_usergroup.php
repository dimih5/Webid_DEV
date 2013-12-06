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

//retrieve usergroup data
$id = isset($_GET['id']) ? $_GET['id'] : (isset($_POST['id']) ? $_POST['id'] : 0);
$query = "SELECT * FROM " . $DBPrefix . "usergroups WHERE id=" . $id . " AND user_id=" . $user->user_data['id'];
$result = mysql_query($query);
$system->check_mysql($result, $query, __LINE__, __FILE__);
$USERGROUP = mysql_fetch_assoc($result);

if(!$USERGROUP) {
    $user->setMessage('edit_usergroups', 'Group does not exist, or user is not owner of group.', 'error');
    $user->redirect('edit_usergroups.php');
}

if (isset($_POST['action']) && $_POST['action'] == 'save') {
    $count = count($_POST['users']);
    
    $query = "SELECT count(id) as count FROM " . $DBPrefix . "users WHERE id IN(" . implode(",", $_POST['users']) . ")";
    $result = mysql_query($query);
    $system->check_mysql($result, $query, __LINE__, __FILE__);
    $found = mysql_result($result, 0, 'count');
        
    if($count == $found) {
        // Delete all relations
        $query = "DELETE FROM " . $DBPrefix . "usergroup_user WHERE usergroup_id = " . $USERGROUP['id'];
        $result = mysql_query($query);
        $system->check_mysql($result, $query, __LINE__, __FILE__);
        
        if($count > 0) {
            $query = "INSERT INTO " . $DBPrefix . "usergroup_user (usergroup_id, user_id) VALUES ";
            foreach($_POST['users'] as $userid) {
                $query .=  "(" . $USERGROUP['id'] . ", " . $userid . "),";
            }
            $result = mysql_query(rtrim($query, ','));
            $system->check_mysql($result, $query, __LINE__, __FILE__);
        }
        
        $user->setMessage('edit_usergroups', 'Changes saved succesfully.');
        $user->redirect('edit_usergroups.php');
    } else {
        $user->setMessage('edit_usergroups', 'Invalid data provided, please try again.', 'error');
        $user->redirect('edit_usergroups.php');
    }
}

$users = array();
$query = "SELECT user_id FROM " . $DBPrefix . "usergroup_user WHERE usergroup_id = " . $USERGROUP['id'];
$result = mysql_query($query);
$system->check_mysql($result, $query, __LINE__, __FILE__);
while($row = mysql_fetch_assoc($result)) {
    $users[] = $row['user_id'];
}

//retrieve user data
$query = "SELECT * FROM " . $DBPrefix . "users";
$result = mysql_query($query);
$system->check_mysql($result, $query, __LINE__, __FILE__);
while($row = mysql_fetch_assoc($result)) {
    $template->assign_block_vars('users', array(
        'ID' => $row['id'],
        'COMPANY' => $row['company'],
        'NAME' => $row['name'],
        'EMAIL' => $row['email'],
        'COUNTRY' => $row['country'],
        'CHECKED' => in_array($row['id'], $users) ? 'checked' : ''
    ));
}

$template->assign_vars(array(
    'ID' => $USERGROUP['id'],
    'NAME' => $USERGROUP['name'],
));

include 'header.php';
include $include_path . 'user_cp.php';
$template->set_filenames(array(
		'body' => 'edit_usergroup.tpl'
		));
$template->display('body');
include 'footer.php';
?>