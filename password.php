<?php
error_reporting(E_ERROR | E_PARSE);
ini_set('display_errors', '1');
require_once 'includes/functions.php';

$qry = $db->sql_query("SELECT * FROM users WHERE user_id=1");
$row = $db->sql_fetchrow($qry);

$user_name = $row['user_name'];
$user_pass = $db->decrypt_key($row['user_pass']);
$user_pass = $db->encryptor('decrypt',$user_pass);

echo 'Username: '.$user_name.'<br>';
echo 'Password: '.$user_pass.'';
?>