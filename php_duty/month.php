<?php
include_once 'include/init.php';


$header = get_header("month");
$tailer = get_tailer();

$thisyear  = date('Y');
$thismonth = date('m');

if (($_GET['y']) && ($_GET['m'])) {
	$thisyear = $_GET['y'];
	$thismonth = $_GET['m'];
}

$prev = mktime(0,0,0,$thismonth-1,1,$thisyear);
$prevmonthyear = date('Y',$prev);
$prevmonth = date('m',$prev);
$displayprev = print_month($prevmonth, $prevmonthyear, true);

$next = mktime(0,0,0,$thismonth+1,1,$thisyear);
$nextmonthyear = date('Y',$next);
$nextmonth = date('m',$next);
$displaynext = print_month($nextmonth, $nextmonthyear, true);

$db_conn = do_db_connect();
$displaymonth = print_month($thismonth, $thisyear, false);

$displayonduty = print_onduty_today();
$displayremind = print_remind();
do_db_disconnect();
echo <<< EOT
	{$header}
	<table width="80%" cellspacing="0" cellpadding="0" align="center" border="0">
	<tr>
		<td valign="top">{$displayprev}</td>
		<td valign="middle" align="center" width="40%">
		{$displayonduty}
		</td>
		<td valign="top">{$displaynext}</td>
	</tr>
	<tr>
		<td colspan="3" height="30">&nbsp;</td>
	</tr>
	<tr>
		<td colspan="3">
		{$displaymonth}
		</td>
	</tr>
	<tr>
		<td colspan="3" height="30">&nbsp;</td>
	</tr>	
	<tr>
		<td colspan="3">
		{$displayremind}
		</td>
	</tr>
	</table>
	{$tailer}
EOT;

?>