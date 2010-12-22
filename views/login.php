<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>byebyesyslog.</title>
<style>
	h1{margin-top:10px;}
	body { background-color:#D7D7D7; }
	.b {
		margin-top: 10%;
-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px;
background-color:#FFFFFF;

-moz-box-shadow: 0 0 5px #888;
-webkit-box-shadow: 0 0 5px#888;
box-shadow: 0 0 5px #888;

padding: 10px;
font: 12px Arial,Verdana,sans-serif;
color:#888;

width: 450px;
height: 210px;
margin-left:auto;
margin-right:auto;
text-align: left;
}
label {display:block;margin-top:5px; margin-bottom:2px;}
.i {background: #FAFAFA;
border: 1px solid #EEE;
font-size: 18px;
line-height: 20px;
margin: 0;
padding: 3px;
width: 440px;
}
.a {
	padding:20px;
	padding-top:5px;
	padding-bottom:5px;
	
-webkit-border-radius: 25px;
-moz-border-radius: 25px;
border-radius: 25px;
background-color:#FFFFFF;
border: 0px;
margin-top: 18px;
float: right;
-moz-box-shadow: 0 2px 3px #888;
-webkit-box-shadow: 0 2px 3px #888;
box-shadow: 0 2px 3px #888;
}
.e {color:#900;padding-top: 8px;}
</style>
</head>

<body>
	<div class="b">
    	<h1>ByeByeSysLog Login.</h1>
        <form method="post" action="">
            <label>Username</label><input class="i" name="auth_user" type="text"/>
            <label>Password</label><input class="i" name="auth_pass" type="password"/>
            <input class="a" name="accept" type="submit" value="Continue"/>
        </form>
        <div class="e"><?php echo @$ldap_error; ?></div>
    </div>
</body>
</html>
