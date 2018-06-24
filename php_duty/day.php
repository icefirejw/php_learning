<?php
include_once 'include/init.php';


$header = get_header("Who's on duty today?");
$tailer = get_tailer();
echo $header;

$thisyear  = date('Y');
$thismonth = date('m');
$thisday = date('d');

if (($_GET['y']) && ($_GET['m']) && ($_GET['d'])) {
	$thisyear = $_GET['y'];
	$thismonth = $_GET['m'];
	$thisday =  $_GET['d'];
}

$db_conn = do_db_connect();

$thisdate = $thisyear.'-'.$thismonth.'-'.$thisday;
$duty_info = get_onduty_info($thisday);
$duty_user_info = get_user_onduty($thisyear, $thismonth, $thisday);
//print_r($duty_user_info);
$duty_user_id = $duty_user_info['No'];

if (isset($_POST['userselect']))
{
		$duty_user_id = $_POST['userselect'];
		//echo "#####".$duty_user_id."$$$$<br>";
		update_duty_user($thisdate, $duty_user_id);
}

if (isset($_POST['d_submit']))
{
	if (isset($_POST['dutymemo'])) {
		$duty_memo = $_POST['dutymemo'];
	}
	$thisday = date('Y-m-d');
	update_duty_memo($thisday, $duty_memo);
}

//echo '$$$$$'.$thisyear.$thismonth.$thisday.'####'.$duty_user_id.'$$$<br>';

$all_duty_users = get_duty_users();
$displayremind = print_day_remind($thisyear, $thismonth, $thisday);

$display_duty_user = print_select_duty_user($all_duty_users, $duty_user_id, $thisyear, $thismonth, $thisday);
do_db_disconnect();

$display_return_addr = print_return_month($thisyear, $thismonth, $thisday);

echo <<< EOT
	<table>
	<tr><td>
	{$display_return_addr}
	</td></tr>
	<tr><td>
		{$display_duty_user}
	</td></tr>
	<tr><td height="30px">
	&nbsp;
	</td></tr>
	<tr><td>
	{$displayremind}
	</td></tr>
	</table>	
	{$tailer}
EOT;

?>