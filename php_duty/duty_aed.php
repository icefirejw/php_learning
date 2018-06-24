<?php
include_once 'include/init.php';

$header = get_header("duty management");
$tailer = get_tailer();

echo $header;
$thisyear = date('Y');
$thismonth = date('m');

$prev = mktime(0,0,0,$thismonth-1,1,$thisyear);
$prevmonthyear = date('Y',$prev);
$prevmonth = date('m',$prev);

$db_conn = do_db_connect();
$displaymonth = print_month($thismonth, $thisyear, false);
$displayremind = print_remind();

if (isset($_POST['duty_aed']))
{
	
	if (isset($_POST['d_year'])) {
		$cr_year = $_POST['d_year'];
	}
	if (isset($_POST['d_month'])) {
		$cr_month = $_POST['d_month'];
	}
	//echo "$$$$$$$$$$".$cr_year.' '.$cr_month.'<br>';
	create_month_duty($cr_year, $cr_month);
}

$create_form = print_duty_creator();
do_db_disconnect();


echo <<< EOT
	<table width="80%" cellspacing="0" cellpadding="0" align="center" border="0">
	<tr>
		<td colspan="3" height="30">
		{$create_form}
		</td>
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