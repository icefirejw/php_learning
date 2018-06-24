<?php

/*
description:
	the functions of this websites;
	
history:
	20110809: create this file by wangsenbo 
*/

function get_header( $desc = 'duty' ){
	$ret = '
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>'.$desc.'</title>
<script type="text/javascript" src="include/funcitons.js"></script>
<link href="include/styles.css" rel="stylesheet" type="text/css" /> 
</head>
<body>
	 <div align="center" class="title"><h1><a href = "index.php">网络安全部值班表</a></h1></div>';
	return $ret;
}

function get_tailer(){
	$ret  = ' 
	<hr>
	<div width="100%" align="center">created by w092861</div>
</body>
</html>
	';
	
	return $ret;
}

function get_weekday_name($day, $short, $lang)
{
	$weekday_arr_en = array('Sunday', 'Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday');
	$weekday_arr_cn = array('星期日','星期一','星期二','星期三','星期四','星期五','星期六');
	$weekday_small_arr_en = array('Sun', 'Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat');
	$weekday_small_arr_cn = array('日','一','二','三','四','五','六');
	
	if ($lang == 'en') {
		if ($short) {
			return $weekday_small_arr_en[$day];
		}
		else {
			return $weekday_arr_en[$day];
		}
	}
	else {
		if ($short) {
			return $weekday_small_arr_cn[$day];
		}
		else {
			return $weekday_arr_cn[$day];
		}
	}
}

function get_month_content($timestamp, $thismonth, $detail)
{
	$ret = ' ';
	$tm = $timestamp;
	$dateday = '&nbsp;';
	
	//echo 'get_month_content'.$timestamp.' '.$thismonth.' '.$detail.'<br>';
	$ret .= ('
			<tr class="a1">');
	for ($i = 0; $i < 7; $i++) {
		$tm = $timestamp + ($i * 24 * 3600);
		if (date('m', $tm) == $thismonth) {
			$dateday = date('d', $tm);
		}
		else {
			$dateday = '&nbsp;';
		}
		//echo 'buf:'.$dateday.'<br>';
		$ret .= ('
				<td>'.$dateday.'</td>');
	}
	$ret .= ('
			</tr>');
	if ($detail) {
		$ret .= ('
			<tr class="a3">');
		for ($i = 0; $i < 7; $i++) {
			$tm = $timestamp + ($i * 24 * 3600);
			if (date('m', $tm) == $thismonth) {
				$year = date('Y', $tm);
				$month = date('m', $tm);
				$day = date('d', $tm); 
				$userinfo = get_user_onduty($year, $month, $day);
				//print_r($userinfo);
				$dateday = '<a href="day.php?y='.$year.'&m='.$month.'&d='.$day.'" class="a2">'.$userinfo['Username'].'</a>';
				//$dateday = '<a href="day.php?y='.$year.'&m='.$month.'&d='.$day.'" class="a2">'.'aaaaa'.'</a>';
			}
			else {
				$dateday = '&nbsp;';
			}
			//echo 'buf:'.$dateday.'<br>';
			$ret .= ('
					<td>'.$dateday.'</td>');
		}
		$ret .= ('
			</tr>');
	}	
	return $ret;		
}

function get_month_days( $year, $month) 
{
	$month_days_arr = array(31, 28, 31, 30, 31, 30, 31, 31, 30, 31, 30, 31);	
	$ret = 0;
	
	//echo $month.' '.$year.'<br>';
	if ($month == 2) {
		if (($year % 400 == 0) || ($year % 100 != 0) && ($year % 4 == 0)) {
			$ret = 29;
		}
		else
		{
			$ret = 28;
		}
	}
	else {
		$ret = $month_days_arr[$month-1];
	}
	//echo $ret;
	return $ret;
}

function create_month_duty($year, $month)
{
	$usr_arr = get_duty_users();
	$res_rows = count($usr_arr);

	if (is_array($usr_arr) && ($res_rows > 0)) {
		$days = get_month_days($year, $month);
		//echo '@@@@'.$days;	
		$i = 0;
		for ($day=1; $day<=$days; $day++)
		{
			$usr_arr_id = ($i % $res_rows);
			$i++;
			$d_date = $year.'-'.$month.'-'.$day;
			//echo '####'.$d_date.':'.$usr_arr[$usr_arr_id].'<br>';
			insert_duty_user($d_date, $usr_arr[$usr_arr_id]);
		}
	}		
}

/*
$month: the month to be displayed
$year:  in which the month to be displayed
$small: whether display in detail format or not 
*/
function print_month($month, $year, $small) 
{
	global $default_lang;

	$tmstart = mktime(0, 0, 0, $month, 1, $year);
	$tmend = mktime(0, 0, 0, $month+1, 0, $year);
	$wkstart = date('w',$tmstart);	
	$wkend = date('w', $tmend);
	$thismonth = date('m');
	$thisyear = date('Y');
	
	//echo $tmstart.'<br>'.$tmend.'<br>end:'.$dayend.'<br> weekstart:'.$wkstart.'<br>';
	
	$tmstart = $tmstart - $wkstart*24*3600; //get the first sunday's date
	$tmend = $tmend + (7 - $wkend)*24*3600; //get the last saturday's date
	//echo date('Ymd',$tmstart).'-'.date('Ymd',$tmend).'<br>';
	$tm = $tmstart;
	
	//maybe the css needed ----- ^o^
	$ret = '
		<table width="90%" cellspacing="0" cellpadding="0" class="t2">';
	
	$tmp = ' '.$year.'-'.$month.' ';
	if ($small) {
		$tmp = '<a href=month.php?m='.$month.'&y='.$year.'>'.$year.'-'.$month.'</a>';
	}
	if (($year*12+$month) - ($thisyear*12+$thismonth) > 1) {
			$tmp = ' '.$year.'-'.$month.' ';
	}		
	$ret .= '
			<tr class="a2">
				<td colspan="7">'.$tmp.'</td>
			</tr>';

	$ret .= '
			<thead>';

	for ($i = 0; $i < 7; $i++ ) {		
		$ret .= ('
				<th>'.get_weekday_name($i, $small, $default_lang).'</th>'); //maybe the css needed
	} 

	$ret .=	('	
			</thead>');
	
	for ($tm = $tmstart; $tm < $tmend; $tm+= 604800) { //604800 = 24*7*3600
		$ret .= (' 
				'.get_month_content($tm, $month, !$small).' 
				');
		//get_month_content($tm, $month, !$small);
	}
	$ret .= ('
		</table>');
	
	return $ret;
}

function print_year_month($year)
{
	//echo 'print_year_month'.$year;
	
	$ret = '
	<table width="90%" cellspacing="0" cellpadding="0" align="center" border="0">';
	
	for ($i = 1; $i<=12; $i++) {
		if ($i % 4 == 1) {
			$ret .= ('
		<tr>');
		}
		$displaymonth = print_month($i, $year, true);
		$ret .= ('
			<td align="center" valign="top">'.$displaymonth.'</td>');
		if ($i % 4 == 0){
			$ret .= ('
		</tr>
		<tr>
		<td colspan="4" height="30">&nbsp;</td>
		</tr>');		
		}		
	}
	
	$ret .= ('
	</table>');
	
	return $ret;
}

function print_onduty_today()
{
	$year = date('Y');
	$month = date('m');
	$day = date('d'); 
	$userinfo = get_user_onduty($year, $month, $day);
	
	$ret = '
			<div clase = "duty">
				<h1>今日值班</h1>
				<hr width = "40%">
				<table>
				<tr>
					<td>姓名：'.$userinfo['Username'].'<br>电话：'.$userinfo['Tel1'].'<br>电话：'.$userinfo['Tel2'].'</td>
				</tr></table>
			</div>';
			
	return $ret;
				
}

function print_day_remind($thisyear, $thismonth, $thisday)
{
	$year = date('Y');
	$month = date('m');
	$day = date('d'); 
	
	$today = mktime(12,0,0,$month,$day,$year);
	$prev = $today - 24*3600;
	$date = date('Y-m-d');
	$prevdate = date('Y-m-d', $prev);
	
	$duty_info_today = get_onduty_info($date);
	$duty_info_prev =  get_onduty_info($prevdate);
	
	//print_r($duty_info_today);
	//print_r($duty_info_today['memo']);
	$ret = '
			<form id="duty_memo" name="duty_memo" method="post" action="day.php?y='.$thisyear.'&m='.$thismonth.'&d='.$thisday.'">
			<table width="90%" class = "t3">
				<tr class="a1">
				<th>昨日提醒</th>
				<td>'.$duty_info_prev['memo'].'</td></tr>
				<tr class="a1">
				<th>今日提醒</th>
				<td><textarea name="dutymemo" cols="100%">'.$duty_info_today['memo'].'</textarea></td></tr>
				<tr class="a1">
				<td  colspan="2"><input type="submit" name="d_submit" value="更新提醒" /></td></tr>
				</table></form>';
			
	return $ret;
				
}


function print_remind()
{
	$year = date('Y');
	$month = date('m');
	$day = date('d'); 
	
	$today = mktime(12,0,0,$month,$day,$year);
	$prev = $today - 24*3600;
	$date = date('Y-m-d');
	$prevdate = date('Y-m-d', $prev);
	
	$duty_info_today = get_onduty_info($date);
	$duty_info_prev =  get_onduty_info($prevdate);
	
	$ret = '<table width="90%" class = "t3">
				<tr class="a1">
				<th>昨日提醒</th>
				<td>'.$duty_info_prev['memo'].'</td></tr>
				<tr class="a1">
				<th>今日提醒</th>
				<td>'.$duty_info_today['memo'].'</td></tr></table>';
			
	return $ret;
				
}

function print_all_user_select($user_arr, $select_id, $year, $month, $day)
{
	$user_count = 0;
	$ret = '<form action="day.php?y='.$year.'&m='.$month.'&d='.$day.'" method="post" name="SelectUser" id="usermenu"> 
			<label></label>
			<select name="userselect" id="userselect" onchange="document.SelectUser.submit()">';
	
	if (is_array($user_arr)) {
		$user_count = count($user_arr);
	}
	
	for ($i=0; $i<$user_count; $i++) {
		$userinfo = get_userinfo_by_id($user_arr[$i]);
		if (is_array($userinfo))
		{
			$userselected = '';
			if ($select_id == $user_arr[$i]) {
				$userselected = 'selected="selected"';
			}		
			$ret .= ('
				<option value="'.$userinfo['No'].'" '.$userselected.'>'.$userinfo['Username'].'</option>
				');
		}
	}
	
	$ret .= ('
		</select>
		</form>');
	
	return $ret;		
}

function print_return_month($year, $month, $day)
{
	$ret = '<table width="90%"><tr><td><a href="month.php?y='.$year.'&m='.$month.'">返回</a></td></tr></table>';
	
	return $ret;
}

function print_select_duty_user($user_arr, $select_id, $year, $month, $day)
{
	
	//echo "#######".$select_id."$$$$<br>";
	//print_r($user_arr);
	$userinfo = get_userinfo_by_id($select_id);	
	//print_r($userinfo);
	$ret = '	
	<table width="80%" cellspacing="0" cellpadding="0" class="t1">
		<thead>
		<th>姓名</th>
		<th>电话1</th>
		<th>电话2</th>
		<th>备注</th>
		</thead>
		<tr><td>'.print_all_user_select($user_arr, $select_id, $year, $month, $day).'</td>
		<td>'.$userinfo['Tel1'].'</td>
		<td>'.$userinfo['Tel2'].'</td>
		<td class="a1">注意：选择将修改值班人员</td>
		</tr>
	</table>';
	
	return $ret;
	
}

function print_admin_idx()
{
	$ret = '
	<table width="90%"><tr><td>
		<a href="user_aed.php">用户管理</a>&nbsp;
		<a href="duty_aed.php">值班管理</a>
    </td></tr></table>';
	
	return $ret;
}

function print_all_duty_user_info()
{
	$user_count = 0;
	
	$ret = '<form method="post" name="userinfo" id="userinfo" action="user_aed.php"> 
			<table width="90%" class="t2">
				<thead>
				<th></th>
				<th>员工号</th>
				<th>姓名</th>
				<th>电话1</th>
				<th>电话2</th>
				<th>住址</th>
				<th>是否值班</th>
				<th></th>
				</thead>
				<tr>
				<td></td>
				<td><input type="text" name="acc" /></td>
				<td><input type="text" name="user" /></td>
				<td><input type="text" name="tel1" /></td>
				<td><input type="text" name="tel2" /></td>
				<td><input type="text" name="addr" /></td>
				<td>
				<select name="onduty1" id="onduty">
					<option value="0">否</option>
					<option value="1" selected="selected">是</option>
				</select>
				</td>
				<td><input type="submit" name="ae_button" value="增加"/></td>
				</tr>
	';
	
	$user_arr = get_all_users();
	
	if (is_array($user_arr))
	{	
		//print_r($user_arr);	
		
		foreach ($user_arr as $userid)
		{
			$userinfo = get_userinfo_by_id($userid);
			//print_r($userinfo);			
			$ret .= ('
				<tr>
				<td><a href="#" onclick="update_user_edit_form("'.$userinfo['account'].'","'.$userinfo['Username '].'","'.$userinfo['Tel1'].'","'.$userinfo['Tel2'].'","'.$userinfo['Address'].'","'.$userinfo['onduty'].'")" class="a2">编辑</a></td>
				<td>'.$userinfo['Account'].'</td>
				<td>'.$userinfo['Username'].'</td>
				<td>'.$userinfo['Tel1'].'</td>
				<td>'.$userinfo['Tel2'].'</td>
				<td>'.$userinfo['Address'].'</td>
				<td>'.$userinfo['onduty'].'</td>
				<td><a href="#" onclick="delete_user_submit("'.$userinfo['account'].'")" class="a2">删除</a></td>
				</tr>');
		}
	}
	
	$ret .= ('
		</table>
		</form>
	');
	return $ret;	
}

function print_duty_creator()
{
	$ret = '
		<form method="post" name="dutyinfo" id="dutyinfo" action="duty_aed.php">
		<label>年：
		<input type="text" name="d_year" /></label>
		<label>月：
		<input type="text" name="d_month" /></label>
		<input type="submit" name="duty_aed" value="生成下月值班"/>
		</form>
	';
	return $ret;
}
?>
