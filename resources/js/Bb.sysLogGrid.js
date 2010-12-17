Ext.ns('Bb');

Bb.sysLogGrid = Ext.extend(Ext.grid.GridPanel, {
	initComponent:function(){
		var store = new Ext.data.JsonStore({
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
		});

		var filters = new Ext.ux.grid.GridFilters({
			encode: false,
			filters: [
				{type: 'string', dataIndex: 'host' },
				{type: 'string', dataIndex: 'facility' },
				{type: 'string', dataIndex: 'level' },
				{type: 'date', dataIndex: 'datetime' }, 
				{type: 'string', dataIndex: 'program' }, { type: 'numeric', dataIndex: 'pid' }, 
				{type: 'string', dataIndex: 'msg' }
			]
		});

		var expander = new Ext.ux.grid.RowExpander({
			tpl : new Ext.Template(
				'<pre>{msg}</pre>'
			)
		});

		var config = {
			layout: 'fit',
			store: store,
			stripeRows: true,
			overCls: 'hover',
			columns: [
				expander,
				{dataIndex: 'datetime', header: 'Date', renderer: Ext.util.Format.dateRenderer('M j H:i:s'), width: 100 },
				{dataIndex: 'program', header: 'Program', width: 55}, 
				{dataIndex: 'host', header: 'Host', width: 55},
				{dataIndex: 'pid', header: 'PID', width: 45 },
				{dataIndex: 'facility', header: 'Facility', width: 45, align: 'right' }, 
				{dataIndex: 'level', header: 'Level', width: 40 }, 
				{dataIndex: 'msg', header: 'Message', id: 'col-'+this.table+'-'+this.host+'-msg'},
			], region: 'center',
			viewConfig:{
				enableRowBody: true,
				//forceFit: true,
				/*
				getRowClass: function(record, rowIndex, rp, ds){ // rp = rowParams
					if(record.get('msg')){
						var msg = record.get('msg').replace(/(\d{1,3})\.(\d{1,3})\.(\d{1,3})\.(\d{1,3})/gi, '<a href="#">$1.$2.$3.$4</a>');
						rp.body = '<p>'+msg+'</p>';
					}
					
					if(record.get('level') == 'warning'){
						return 'x-grid3-row-body expanded row-red';
					}else{
						return 'x-grid3-row-body';
					}
				}
				*/
			},
			loadMask: true,
			plugins: [expander,filters],
			autoExpandColumn: 'col-'+this.table+'-'+this.host+'-msg',
			bbar: new Ext.PagingToolbar({
				store: store,
				pageSize: 50,
				plugins: [filters]
			})
		};
		
		Ext.apply(this, Ext.apply(this.initialConfig, config));
		Bb.sysLogGrid.superclass.initComponent.apply(this, arguments);
	},
	
	onRender:function(){
		this.getStore().load({params: {start:0, limit:50}});
		Bb.sysLogGrid.superclass.onRender.apply(this, arguments);
	}
});