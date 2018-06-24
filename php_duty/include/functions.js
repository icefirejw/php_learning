// JavaScript Document
function update_user_edit_form(acc, user, tel1, tel2, addr, onduty)
{
	document.userinfo.acc.value = acc;
	document.userinfo.user.value = user;
	document.userinfo.tel1.value = tel1;
	document.userinfo.tel2.value = tel2;
	document.userinfo.addr.value = addr;
	document.userinfo.onduty.value = onduty;
	
	document.userinfo.acc.readonly = true;
	document.userinfo.ae_button.value = "更新";
	document.userinfo.action = "user_aed.php?a='002'";
	
}

function delete_user_submit(acc)
{
	document.userinfo.acc.value = acc;
	document.userinfo.user.value = "123456";
	document.userinfo.tel1.value = "123456";
	document.userinfo.onduty.value = "1";
	document.userinfo.action = "user_aed.php?a='003'";
	document.userinfo.submit();
}

