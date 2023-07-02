<?php
error_reporting(E_ALL | E_PARSE);
ini_set('display_errors', '1');
//include('config.php');

$DB_host = 'localhost';
$DB_user = 'fistvpnc_l2panel';
$DB_pass = 'harulenz00';
$DB_name = 'fistvpnc_l2panel';

$mysqli = new MySQLi($DB_host,$DB_user,$DB_pass,$DB_name);
if ($mysqli->connect_error) {
    die('Error : ('. $mysqli->connect_errno .') '. $mysqli->connect_error);
}

$data = '';

#Active Accounts
$premium_active = "is_validated=1 AND is_active=1 AND is_freeze=0 AND is_offense=0 AND duration > 0";
$vip_active = "is_validated=1 AND is_active=1 AND is_freeze=0 AND is_offense=0 AND vip_duration > 0";
$private_active = "is_validated=1 AND is_active=1 AND is_freeze=0 AND is_offense=0 AND private_duration > 0";

$query = $mysqli->query("SELECT * FROM users
WHERE ".$premium_active." OR ".$vip_active." OR ".$private_active." ORDER by user_id DESC");


if($query->num_rows > 0)
{
    
	while($row = $query->fetch_assoc())
	{
		$data .= '';
		
		$output = false;
        
		$encrypt_method = "AES-256-CBC";
		//pls set your unique hashing key
		$secret_key = $this->encrypt_key(md5('eugcar'));
		$secret_iv = $this->encrypt_key(md5('sanchez'));
		
		// hash
		$key = hash('sha256', $secret_key);
		
		// iv - encrypt method AES-256-CBC expects 16 bytes - else you will get a warning
		$iv = substr(hash('sha256', $secret_iv), 0, 16);
        $string = $row['user_pass'];
        $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);

		$username = $row['user_name'];
		$user_pass = $output;
		$data .= 'useradd -p $(openssl passwd -1 '.$user_pass.') -M '.$username.''.PHP_EOL;
	}
}
$location = '/home/fistvpnc/cdn.octaviavpn.com/octavia_active2.txt';
$fp = fopen($location, 'w');
fwrite($fp, $data) or die("Finish!");
fclose($fp);



#In-Active and Invalid Accounts
$data2 = '';
$premium_deactived = "is_active=1 AND duration <= 0";
$vip_deactived = "is_active=1 AND vip_duration <= 0";
$private_deactived = "is_active=1 AND private_duration <= 0";
$is_validated = "is_validated=0";
$is_activate = "is_active=0";
$freeze = "is_freeze=1";
$suspend = "is_offense=1";

$query2 = $mysqli->query("SELECT * FROM users 
WHERE ".$is_validated." OR ".$is_activate." OR ".$freeze." OR ".$suspend." OR  ".$premium_deactived ." AND ".$vip_deactived." AND ".$private_deactived."
");
if($query2->num_rows > 0)
{
	while($row2 = $query2->fetch_assoc())
	{
		$data2 .= '';
		$toadd = $row2['user_name'];	
		$data2 .= 'userdel '.$toadd.''.PHP_EOL;
	}
}
$location2 = '/home/fistvpnc/cdn.octaviavpn.com/octavia_inactive2.txt';
$fps = fopen($location2, 'w');
fwrite($fps, $data2) or die("Finish!");
fclose($fps);
?>