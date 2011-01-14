<?php
	require_once('config.php');
	
	if(@$_GET['logout']==true) {
		$_SESSION['authenticated'] = false;
		unset($_SESSION['authenticated']);
		header('Location: index.php');
		exit();
	}
	if(@$ldap){
		require_once('auth.php');
	}
?>
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ByeByeSysLog</title>

    <!-- ** CSS ** -->
    <!-- base library -->
    <link rel="stylesheet" type="text/css" href="resources/libs/ext/resources/css/ext-all.css" />
    <link rel="stylesheet" type="text/css" href="resources/libs/ext-ux/livegrid/css/ext-ux-livegrid.css" />

    <!-- overrides to base library -->

    <style type="text/css">
    	body { font-family: Verdana, Arial; font-size: small; }
		.x-grid3-row td { -moz-user-select: text; -webkit-user-select: text; }
	    .x-grid3-row-body pre { color: #444; padding: 5px;  white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word; }
        .x-grid3-row-alt { background-color: #edf1ff; border-bottom: 1px solid #CCC; }
        .x-grid3-row { border-width: 0px; border-bottom: 1px solid #CCC; }
        .x-grid3-row-over { background-image: none; border-width: 0px; border-bottom: 1px solid #CCC;}
        .row-red { color: maroon; }
        h1 { font-family: Verdana, Arial; font-size: 18px; line-height: 35px; font-weight: normal; padding-left: 5px; }
        
        .float-left { float: left; }
        
        .x-window-plain .x-window-body { background-color: #FFF !important; }
        
		.help { padding: 6px; padding-top: 1px; }
		.help h3 { margin: 0px; margin-top: 5px; font-size: 13px; }
		.help ul { margin: 0px; }
		.help ul { margin-left: 5px !important; }
		.help h3 small { color: #444; font-size: 11px !important; }
		.help ul small { color: #444; display: block; font-size: 11px !important; }
	</style>
    <!-- ** Javascript ** -->
    <!-- ExtJS library: base/adapter -->
    <script type="text/javascript" src="resources/libs/ext/adapter/ext/ext-base.js"></script>

    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="resources/libs/ext/ext-all.js"></script>

    <!-- overrides to base library -->

    <!-- extensions -->
    <script type="text/javascript" src="resources/libs/ext-ux/RowExpander.js"></script>
    <script type="text/javascript" src="resources/libs/ext-ux/SearchField.js"></script> 
    <script type="text/javascript" src="resources/libs/ext-ux/livegrid-all.js"></script> 
	
    <!-- page specific -->
	<script type="text/javascript" src="resources/js/Bb.sysLogGrid.js"></script>
	<script type="text/javascript" src="resources/js/Bb.sysLogHosts.js"></script>
	<script type="text/javascript">
		Ext.onReady(function(){
			new Ext.Viewport({
				layout: 'border',
				defaults: {
					split: true,
					collapsible: false
				},
				items: [
					{
						region: 'north',
						html: '<h1 class="float-left">ByeByeSysLog</h1><div class="float-left">(<a href="index.php?logout=true">logout</a>)</div>',
						height: 40
					},
					'sysLogHosts',
					new Ext.TabPanel({
						region: 'center',
						title: 'SysLog Messages',
						resizeTabs:true,
						minTabWidth: 180,
						enableTabScroll:true,
						id:'sysLogTabs'
					})
				]
				//new Bb.sysLogGrid({table: 'mail'})
			}).show();
			
			new Ext.Window({
				id: 'help-window',
				title: 'Query help',
				icon: 'resources/icons/help.png',
				width:300,
				height:500,
				closable: false,
				plain: true,
				resizable: true,
				resizeHandles: 'se',
				autoScroll: true,
				collapsible: true,
				collapsed: true,
				autoLoad: 'help.php'
			}).show().anchorTo('tr').collapse();
		});
	</script>
</head>
<body>
	<div id="tr" style="position: fixed; top: 6px; left: 100%;"></div>
</body>
</html>