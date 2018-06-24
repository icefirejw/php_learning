<?php
$db_connect = '';

function do_db_connect()
{
	global $db_server, $db_usr, $db_pwd, $db_name, $db_connect;
	
	$db_connect = mysql_connect($db_server, $db_usr, $db_pwd) or
		die ("do_db_connect: failed to connect to database");
	mysql_select_db($db_name) or
		die ("do_db_connect: failed to select database");
		
	mysql_query("set names 'utf8'", $db_connect);	
	
	return $db_connect;

}

function do_db_disconnect($conn)
{
	mysql_close($conn);
}

function get_user_onduty($year='1971', $month='01', $day='01')
{
	
	$sql = "select * from user where no = (select onduty from duty where date1 = '".$year.'-'.$month.'-'.$day."')";
	//echo '###'.$sql.'<br>';
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res, MYSQL_BOTH);
	//print_r($row);

	return $row;
	
}

function get_onduty_info($d_date)
{
		
	$sql = "select * from duty where date1 = '".$d_date."'";
	//echo '###'.$sql.'<br>';
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res, MYSQL_BOTH);

	return $row;	
}

function insert_user($acc, $pwd, $user, $tel1, $tel2, $addr, $onduty)
{
	global $db_connect;
	
	$sql = "insert into user (Account, Password, Username, Tel1, Tel2, Address, onduty, Lastonduty) 
            values ('".$acc."','".$pwd."','".$user."','".$tel1."','".$tel2."','".$addr."','".$onduty."','20110101')";
			
	//echo $sql;

	$res = mysql_query($sql);
	$insert_num = mysql_affected_rows($db_connect);
	//echo 'insert row number'.$insert_num;	
	
	return $insert_num;
}

function update_user_info($acc, $pwd, $user, $tel1, $tel2, $addr, $ondute)
{	
	global $db_connect;
	
	$sql_user = "select * from user where Account='".$acc."'";
	$res = mysql_query($sql_user);
	$res_rows = mysql_num_rows($res);
	if ($res_rows != 1) {
		return -1;
	}
	
	$sql = "update duty set";
	if ($pwd)
	{
		$sql .= (" Password='".$pwd."'");
	}
	if ($tel1)
	{
		$sql .= (" Tel1='".$tel1."'");
	}
	if ($tel2)
	{
		$sql .= (" Tel2='".$tel2."'");
	}
	if ($addr)
	{
		$sql .= (" Address='".$pwd."'");
	}
	if ($ondute)
	{
		$sql .= (" onduty ='".$pwd."'");
	}
	$sql .= " where account='".$acc."'";
	echo '#####'.$sql.'<br>';
	
	$res = mysql_query($sql);
	$rows_num = mysql_affected_rows($db_connect);
	
	return $rows_num;
	
}

function delete_user($acc)
{
	global $db_connect;
	$sql_user = "delete from user where Account='".$acc."'";
	$res = mysql_query($sql);
	$insert_num = mysql_affected_rows($db_connect);

	return $insert_num;	
}

function get_duty_users()
{
	$sql_user = "select * from user where onduty='1' order by lastonduty";
	$res = mysql_query($sql_user);
	$res_rows = mysql_num_rows($res);
	$usr_arr = '';
	//echo '####'.$res_rows.'<br>';
	if ($res_rows > 0) {
		$arr_org = array(1);
		$usr_arr = array_pad($arr_org,$res_rows,'x');
		
		$i = 0;
		while ($row = mysql_fetch_array($res, MYSQL_BOTH))
		{
			$usr_arr[$i] = $row['No'];
			$i++;
		}
	}
	
	return $usr_arr;
}

function get_all_users()
{
	$sql_user = "select * from user order by lastonduty";
	$res = mysql_query($sql_user);
	$res_rows = mysql_num_rows($res);
	$usr_arr = '';
	//echo '####'.$res_rows.'<br>';
	if ($res_rows > 0) {
		$arr_org = array(1);
		$usr_arr = array_pad($arr_org,$res_rows,'x');
		
		$i = 0;
		while ($row = mysql_fetch_array($res, MYSQL_BOTH))
		{
			$usr_arr[$i] = $row['No'];
			$i++;
		}
	}
	
	return $usr_arr;
}

function get_userinfo_by_id($u_id)
{

	$sql = "select * from user where No = '".$u_id."'";
	//echo '###'.$sql.'<br>';
	$res = mysql_query($sql);
	$row = mysql_fetch_array($res, MYSQL_BOTH);
	//print_r($row);
	
	return $row;

}

function insert_duty_user($d_date, $d_userid)
{
	global $db_connect;
	
	$sql_user = "select * from user where No='".$d_userid."'";
	$res = mysql_query($sql_user);
	$res_rows = mysql_num_rows($res);
	if ($res_rows != 1) {
		return -1;
	}
	
	$sql = "insert into duty (date1, onduty, moddate, memo) 
            values ('".$d_date."','".$d_userid."',NOW(), 'no event')";
	$res = mysql_query($sql);
	$insert_num = mysql_affected_rows($db_connect);
	
	//update the user's duty date
	if ($insert_num > 0)
	{
		$sql = "update user set Lastonduty='".$d_date."' where no='".$d_userid."'";
		mysql_query($sql);
	}
	//echo '###'.$insert_num;
	return $insert_num;
}

function update_duty_user($d_date, $d_userid)
{
	global $db_connect;
	$sql_user = "select * from user where No='".$d_userid."'";
	$res = mysql_query($sql_user);
	$res_rows = mysql_num_rows($res);
	if ($res_rows != 1) {
		return -1;
	}
	
	$thisday = date('Y-m-d');		
	$sql = "update duty set onduty='".$d_userid."', moddate='".$thisday."' where date1='".$d_date."'";	
	//echo $sql;
	$res = mysql_query($sql);
	$rows_num = mysql_affected_rows($db_connect);
	
	return $rows_num;
}

function update_duty_memo($d_date, $d_memo)
{
	global $db_connect;
	$thisday = date('Y-m-d');		
	$sql = "update duty set memo='".$d_memo."', moddate='".$thisday."' where date1='".$d_date."'";	
	//echo $sql;
	$res = mysql_query($sql);
	$rows_num = mysql_affected_rows($db_connect);
	
	return $rows_num;
}

?>