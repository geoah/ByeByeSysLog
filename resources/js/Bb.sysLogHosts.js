Ext.ns('Bb');
Ext.onReady(function(){
	new Ext.tree.TreePanel({
		id: 'sysLogHosts',
		useArrows: true,
		autoScroll: true,
		animate: true,
		enableDD: false,
		containerScroll: true,
		dataUrl: 'hosts.php',
		rootVisible: false,
		root: {
			nodeType: 'async',
			text: 'Hosts',
			draggable: false,
			id: 'src',
			expanded: true
		},
		listeners: {
			click: function(node, event){
				if(node.attributes.table){
					if(Ext.getCmp('tab-'+node.attributes.host+'-'+node.attributes.table)){
						Ext.getCmp('tab-'+node.attributes.host+'-'+node.attributes.table).show();
					}else{
						Ext.getCmp('sysLogTabs').add(
							new Bb.sysLogGrid({
								table: node.attributes.table,
								host: node.attributes.host,
								title: node.attributes.fulltext,
								closable: true,
								id: 'tab-'+node.attributes.host+'-'+node.attributes.table
							})
						).show();
					}
				}
			}
		},
		region: 'west',
		width: 200,
		title: 'Hosts'
	});
});