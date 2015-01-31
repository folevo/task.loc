
Ext.Loader.setConfig({enabled: true});
Ext.Loader.setPath('Ext', '/ext-5.0.1');
Ext.require([
    'Ext.grid.*',
    'Ext.data.*'
]);

Ext.onReady(function(){
    Ext.create('Ext.data.Store', {
        storeId:'simpsonsStore',

        fields:['name', 'email', 'phone'],
        data:{'items':[
            {"name":"Lisa", "email":"lisa@simpsons.com", "phone":"555-111-1224"},
            {"name":"Bart", "email":"bart@simpsons.com", "phone":"555-222-1234"},
            {"name":"Homer", "email":"home@simpsons.com", "phone":"555-222-1244"},
            {"name":"Marge", "email":"marge@simpsons.com", "phone":"555-222-1254"}
        ]},
        proxy: {
            type: 'memory',
            reader: {
                type: 'json',
                root: 'items'
            }
        }
    });

    Ext.create('Ext.grid.Panel', {
        cls: 'custom-grid',
        columnLines: true,
        title: 'Simpsons',
        store: Ext.data.StoreManager.lookup('simpsonsStore'),
        columns: [
            {header: 'Name',  dataIndex: 'name', editor: 'textfield'},
            {header: 'Email', dataIndex: 'email', flex:1,
                editor: {
                    xtype: 'textfield',
                    allowBlank: false
                }
            },
            {header: 'Phone', dataIndex: 'phone'}
        ],
        selType: 'cellmodel',
        plugins: [
            Ext.create('Ext.grid.plugin.CellEditing', {
                clicksToEdit: 1
            })
        ],
        height: 200,
        width: 800,
        renderTo: Ext.getBody()


    });
});
