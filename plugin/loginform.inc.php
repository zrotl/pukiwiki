<?php

// PukiWiki - Yet another WikiWikiWeb clone
// Copyright (C) 2015 PukiWiki Development Team
// License: GPL v2 or (at your option) any later version
//
// "Login form" plugin

function plugin_loginform_inline()
{
	$logout_param = '?plugin=basicauthlogout';
	return '<a href="' . htmlsc(get_script_uri() . $logout_param) . '">Log out</a>';
}

function plugin_loginform_convert()
{
	return '<div>' . plugin_basicauthlogout_inline() . '</div>';
}

function plugin_loginform_action()
{
	global $auth_user, $auth_type, $_loginform_messages;
	$page = $_GET['page'];
	$pcmd = $_GET['pcmd'];
	$url_after_login = $_GET['url_after_login'];
	$page_after_login = $page;
	if (!$url_after_login) {
		$page_after_login = $page;
	}
	$action_url = get_script_uri() . '?plugin=loginform'
		. '&page=' . rawurlencode($page)
		. ($url_after_login ? '&url_after_login=' . rawurlencode($url_after_login) : '')
		. ($page_after_login ? '&page_after_login=' . rawurlencode($page_after_login) : '');
	$username = $_POST['username'];
	$password = $_POST['password'];
	if ($username && $password && form_auth($username, $password)) {
		// Sign in successfully completed
		form_auth_redirect($url_after_login, $page_after_login);
		return;
	}
	if ($pcmd === 'logout') {
		// logout
		switch ($auth_type) {
			case AUTH_TYPE_BASIC:
				header('WWW-Authenticate: Basic realm="Please cancel to log out"');
				header('HTTP/1.0 401 Unauthorized');
				break;
			case AUTH_TYPE_FORM:
			case AUTH_TYPE_EXTERNAL:
			default:
				$_SESSION = array();
				session_regenerate_id(); // require: PHP5.1+
				session_destroy();
				break;
		}
		$auth_user = '';
		return array(
			'msg' => 'Log out',
			'body' => 'Logged out completely<br>'
				. '<a href="'. get_script_uri() . '?' . pagename_urlencode($page) . '">'
				. $page . '</a>'
		);
	} else {
		// login
		$action_url_html = htmlsc($action_url);
		$username_html = htmlsc($username);
		$username_label_html = htmlsc($_loginform_messages['username']);
		$password_label_html = htmlsc($_loginform_messages['password']);
		$login_label_html = htmlsc($_loginform_messages['login']);
		$body = <<< EOT
<style>
  .loginformcontainer {
    text-align: center;
  }
  .loginform table {
    margin-top: 1em;
	margin-left: auto;
	margin-right: auto;
  }
  .loginform tbody td {
    padding: .5em;
  }
  .loginform .label {
    text-align: right;
  }
  .loginform .login-button-container {
    text-align: right;
  }
  .loginform .loginbutton {
    margin-top: 1em;
  }
</style>
<div class="loginformcontainer">
<form name="loginform" class="loginform" action="$action_url_html" method="post">
<div>
<table style="border:0">
  <tbody>
  <tr>
    <td class="label"><label for="_plugin_loginform_username">$username_label_html</label></td>
    <td><input type="text" name="username" value="$username_html" id="_plugin_loginform_username"></td>
  </tr>
  <tr>
  <td class="label"><label for="_plugin_loginform_password">$password_label_html</label></td>
  <td><input type="password" name="password" id="_plugin_loginform_password"></td>
  </tr>
  <tr>
    <td></td>
    <td class="login-button-container"><input type="submit" value="$login_label_html" class="loginbutton"></td>
  </tr>
  </tbody>
</table>
</div>
<div>
</div>
</form>
</div>
<script><!--
window.addEventListener && window.addEventListener("DOMContentLoaded", function() {
  var f = window.document.forms.loginform;
				console.log(f);
				console.log(f.username);
				console.log(f.password);
  if (f && f.username && f.password) {
    if (f.username.value) {
     f.password.focus && f.password.focus();
	} else {
     f.username.focus && f.username.focus();
	}
  }
});
//-->
</script>
EOT;
		return array(
			'msg' => $_loginform_messages['login'],
			'body' => $body,
			);
	}
}
