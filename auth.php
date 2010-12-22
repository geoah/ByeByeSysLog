<?php

$ldapconn = @ldap_connect($ldap_url);

if(@$_SESSION['authenticated'] !== true){
	if(@$ldapconn){
		if (@$_POST['auth_user'] && @$_POST['auth_pass']){
			$ldapbind = @ldap_bind($ldapconn, $_POST['auth_user'].'@'.$ldap_domain, $_POST['auth_pass']);
		
			if ($ldapbind){
				$_SESSION['authenticated'] = true;
				header('Location: index.php');
				exit();
			} else {
				$_SESSION['authenticated'] = false;
				$ldap_error = 'Authentication failed';
			}
		}else if(@$_POST){
			$ldap_error = 'User or password missing';
		}
	}else{
		$ldap_error = 'Could not connect to LDAP server';
	}
	
	if(@$_SESSION['authenticated'] !== true){
		require_once('views/login.php');
		exit();
	}
}