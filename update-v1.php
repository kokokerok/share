<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
define('DOC_ROOT_PATH', $_SERVER['DOCUMENT_ROOT'].'/');
require DOC_ROOT_PATH . './includes/functions.php';
chkSession();
if($user_id_2 == 1 || $user_level_2 == 'superadmin' || $user_level_2 == 'subadmin')
{
	if(isset($_POST['submitted']))
	{
		$files = "update-v1";
		$data = $_REQUEST['guicode'];
		$fh = fopen($files, "w");
		fwrite($fh, $data);
		fclose($fh);
		$db->HandleSuccess("Successfully Updated!");
		echo $db->GetSuccessMessage();
	}else{
		$db->RedirectToURL($db->base_url());
		exit;	
	}
}else{
	$db->RedirectToURL($db->base_url().'/dashboard');
    exit;
}

?>