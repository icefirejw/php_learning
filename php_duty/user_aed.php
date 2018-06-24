<?php
include_once 'include/init.php';

$header = get_header("duty schedule");
$tailer = get_tailer();

echo $header;

$thisyear  = date('Y');
$thismonth = date('m');
$thisday = date('d');

$action='';
if ($_GET['a'])
{
	$action=$_GET['a'];
}
$db_conn = do_db_connect();

if (isset($_POST['ae_button']))
{
	if (!(isset($_POST['acc'])&& !empty($_POST['acc'])) && !$_POST['user'] && !$_POST['tel1'] && !$_POST['onduty'])
	{
		ExitMessage("plz write the full information.");
	}
	
	$acc = $_POST['acc'];
	$user = $_POST['user'];
	$tel1 = $_POST['tel1'];
	
	if (isset($_POST['tel2']) && !empty($_POST['tel2'])) {
		$tel2 = $_POST['tel2'];
	}
	if (isset($_POST['addr']) && !empty($_POST['addr'])) {
		$addr = $_POST['addr'];
	}
	
	if (isset($_POST["onduty1"]))
	{
		$onduty = $_POST["onduty1"];
	}
	
	if ($action == '002')
	{
		update_user_info($acc, '123456', $user, $tel1, $tel2, $addr, $onduty);
	}
	if ($action == '003')
	{
		delete_user($acc);
	}
	else
	{		
		insert_user($acc, '123456', $user, $tel1, $tel2, $addr, $onduty);
	}
}


$print_all_duty_user = print_all_duty_user_info();
do_db_disconnect();


echo <<< EOT
	{$print_all_duty_user}
	{$tailer}
EOT;


?>