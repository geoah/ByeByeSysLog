<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <title>ByeByeSysLog</title>

    <!-- ** CSS ** -->
    <!-- base library -->
    <link rel="stylesheet" type="text/css" href="resources/libs/ext/resources/css/ext-all.css" />

    <!-- overrides to base library -->
    <link rel="stylesheet" type="text/css" href="resources/libs/ext-ux/gridfilters/css/GridFilters.css" />
    <link rel="stylesheet" type="text/css" href="resources/libs/ext-ux/gridfilters/css/RangeMenu.css" />

    <style type="text/css">
		.x-grid3-row td { -moz-user-select: text; -webkit-user-select: text; }
	    .x-grid3-row-body pre { color: #444; padding: 5px;  white-space: pre-wrap; white-space: -moz-pre-wrap; white-space: -pre-wrap; white-space: -o-pre-wrap; word-wrap: break-word; }
        .x-grid3-row-alt { background-color: #edf1ff; border-bottom: 1px solid #CCC; }
        .x-grid3-row { border-width: 0px; border-bottom: 1px solid #CCC; }
        .x-grid3-row-over { background-image: none; border-width: 0px; border-bottom: 1px solid #CCC;}
        .row-red { color: maroon; }
        h1 { font-family: Verdana, Arial; font-size: 18px; line-height: 35px; font-weight: normal; padding-left: 5px; }
    </style> 

    <!-- ** Javascript ** -->
    <!-- ExtJS library: base/adapter -->
    <script type="text/javascript" src="resources/libs/ext/adapter/ext/ext-base.js"></script>

    <!-- ExtJS library: all widgets -->
    <script type="text/javascript" src="resources/libs/ext/ext-all.js"></script>

    <!-- overrides to base library -->

    <!-- extensions -->
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/menu/RangeMenu.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/menu/ListMenu.js"></script>
	
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/GridFilters.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/filter/Filter.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/filter/StringFilter.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/filter/DateFilter.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/filter/ListFilter.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/filter/NumericFilter.js"></script>
	<script type="text/javascript" src="resources/libs/ext-ux/gridfilters/filter/BooleanFilter.js"></script>
    <script type="text/javascript" src="resources/libs/ext-ux/RowExpander.js"></script> 

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
						html: '<h1>ByeByeSysLog</h1>',
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
		});
	</script>
</head>
<body>
</body>
</html>