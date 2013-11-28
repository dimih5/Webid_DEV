<?php
include '../common.php';
if (!$user->is_logged_in())
{	
	//if your not logged in you shouldn't be here
	//Send error message to the file uploader
	$content = array(
	'files' => array(
	array(
	'name' => "ERROR",
	'size' => "1337",
	'delete_url' => "",
	'delete_type' => "delete",
	'error' => "You are not logged in"
	)));
	echo json_encode($content);
	exit;
}
else
{
include $include_path . 'UploadHandler.php';

$type = isset($_POST['type']) ? $_POST['type'] : 'image';

$options = array();
$maxfiles = 0;
switch($type) {
    case 'image':
        $options['upload_dir'] = dirname($_SERVER['SCRIPT_FILENAME']).'/'.session_id().'/';
        $options['session_key'] = 'UPLOADED_PICTURES';
        $options['inline_file_types'] = '/\.(gif|jpe?g|png)$/i';
        $options['accept_file_types'] = '/.(gif|jpe?g|png)$/i';
        $maxfiles = $system->SETTINGS['maxpictures'];
        break;
    case 'contract':
        $options['upload_dir'] = dirname($_SERVER['SCRIPT_FILENAME']).'/'.session_id().'/contracts/';
        $options['session_key'] = 'UPLOADED_CONTRACTS';
        $options['inline_file_types'] = '';
        $options['accept_file_types'] = '/.(pdf)$/i';
        $maxfiles = $system->SETTINGS['maxcontracts'];
        break;
    case 'condition':
        $options['upload_dir'] = dirname($_SERVER['SCRIPT_FILENAME']).'/'.session_id().'/conditions/';
        $options['session_key'] = 'UPLOADED_CONDITIONS';
        $options['inline_file_types'] = '';
        $options['accept_file_types'] = '/.(pdf)$/i';
        $maxfiles = $system->SETTINGS['maxtermsandconditions'];
        break;
    default:
        break;
}

/*
$contract = isset($_POST['type']) && $_POST['type'] == 'contract';
$options = array(
    'upload_dir' => dirname($_SERVER['SCRIPT_FILENAME']).'/'.session_id().'/' . ($contract ? 'contracts/' : ''),
    'session_key' => $contract ? 'UPLOADED_CONTRACTS' : 'UPLOADED_PICTURES',
    'inline_file_types' => $contract ? '' : '/\.(gif|jpe?g|png)$/i',
    'accept_file_types' => $contract ? '/.(pdf)$/i' : '/.(gif|jpe?g|png)$/i', // TODO: Add more filetypes
);
*/

$upload_handle = new UploadHandler($system->SETTINGS['maxuploadsize'], $maxfiles, $options);
}