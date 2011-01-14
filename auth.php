<?php

function LDAPconnect(){
	global $config;
	$ldap = $config['ldap'];
	
    $ds = ldap_connect($ldap['server'], $ldap['port']);
    if (!$ds) {
        return FALSE;
    }
    if (!ldap_set_option($ds, LDAP_OPT_PROTOCOL_VERSION, 3)) {
        @ldap_close($ds);
        return FALSE;
    }
    return $ds;
}


function LDAPauth($rdn, $passwd){
	global $config;
	$ldap = $config['ldap'];
	
    $ds = LDAPconnect();
    $dn = "uid=".$rdn .",". $ldap['top'];

    if (!ldap_bind($ds, $dn, $passwd)) {
        return FALSE;
    }
    return $ds;
} 

@$ldapconn = LDAPconnect();

if(@$_SESSION['authenticated'] !== true){
	if(@$ldapconn){
		if (@$_POST['auth_user'] && @$_POST['auth_pass']){
			$ldapbind = @LDAPauth($_POST['auth_user'], $_POST['auth_pass']);
		
			if ($ldapbind){
				$_SESSION['authenticated'] = true;
				
				foreach($user[0]['memberof'] as $group){
					$temp = substr($group, 0, stripos($group, ","));
					$temp = strtolower(str_replace("CN=", "", $temp));
				
					echo "{$temp}<br />";   // Print out Group’s name
					$groups[] .= $temp;
				}
				exit();
				
				
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