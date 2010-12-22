Ext.ns('Bb');

Ext.override(Ext.grid.GridView, {
    templates: {
        cell: new Ext.Template(
            '<td class="x-grid3-col x-grid3-cell x-grid3-td-{id} {css}" style="{style}" tabIndex="0" {cellAttr}>',
            '<div class="x-grid3-cell-inner x-grid3-col-{id}" {attr}>{value}</div>',
            "</td>"
            )
    }
});

Bb.sysLogGrid = Ext.extend(Ext.grid.GridPanel, {
	initComponent:function(){
		/*var store = new Ext.data.JsonStore({
			autoDestroy: true,
			url: 'grid.php?table=' + this.table + '&host=' + this.host,
			remoteSort: true,
			sortInfo: {
				field: 'datetime',
				direction: 'DESC'
			},
			root: 'data',
			totalProperty: 'total',
			fields: [ 
				'host',
				'facility',
				'level',
				{name: 'datetime', type: 'date', dateFormat: 'Y-m-d H:i:s'},
				'program',
				'pid',
				'msg'
			]
		});*/
		
		var expander = new Ext.ux.grid.RowExpander({
			tpl : new Ext.Template(
				'<pre>{msg}</pre>'
			)
		});

		var config = {
			layout: 'fit',
			store: new Ext.data.JsonStore({
				url: 'grid.php?table=' + this.table + '&host=' + this.host,
				baseParams: {
					table: this.table,
					host: this.host
				},
				autoDestroy: true,
				sortInfo: {
					field: 'datetime',
					direction: 'DESC'
				},
				root: 'data',
				totalProperty: 'total',
				fields: [ 
					'host',
					'facility',
					'level',
					{name: 'datetime', type: 'date', dateFormat: 'Y-m-d H:i:s'},
					'program',
					'pid',
					'msg'
				],
				autoLoad:true,
				storeId: this.table
			}),
			stripeRows: true,
			overCls: 'hover',
			columns: [
				expander,
				{dataIndex: 'msg', header: 'Log', renderer: function(val,p,record){
					var r = '<pre>' +
							Ext.util.Format.dateRenderer('M j H:i:s')(record.get('datetime')) + ' ' +
							'<b>' + record.get('host') + '</b> ' +
							record.get('program') + ' [' +
							record.get('pid') + ' ' +
							record.get('facility') + '.' +
							record.get('level') + '] ' +
							record.get('msg') +
							'</pre>';
							
					r = r.replace(/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/gi, '<a href="#">$1.$2.$3.$4</a>');
					return r;
				}, width: 100 },
			], region: 'center',
			viewConfig:{
				enableRowBody: true,
				forceFit: true,
				getRowClass: function(record, rowIndex, rp, ds){ // rp = rowParams
					if(record.get('msg')){
						var msg = record.get('msg').replace(/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/gi, '<a href="#">$1.$2.$3.$4</a>');
						rp.body = '<p>'+msg+'</p>';
					}
				}
			},
			loadMask: true,
			plugins: [expander],
			bbar: new Ext.PagingToolbar({
				store: Ext.StoreMgr.get(this.table),
				pageSize: 50,
				displayInfo: true,
				plugins: []
			}),
			tbar: [
				new Ext.ux.form.SearchField({
					store: Ext.StoreMgr.get(this.table),
					width: 500
				})
			]
		};
		
		Ext.apply(this, Ext.apply(this.initialConfig, config));
		Bb.sysLogGrid.superclass.initComponent.apply(this, arguments);
	},
	
	onRender:function(){
		//this.getStore().load({params: {start:0, limit:50}});
		Bb.sysLogGrid.superclass.onRender.apply(this, arguments);
	}
});