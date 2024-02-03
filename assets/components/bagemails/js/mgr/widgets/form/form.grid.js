bagemails.grid.Form = function(config) {
    config = config || {};
   
    Ext.applyIf(config,{
        title: _('bagemails.form.grid')
        ,url: bagemails.config.connectorUrl
        ,baseParams: {
            action: 'mgr/form/option/getList',
            form_id: MODx.request.id
        }
        ,multi_select: true
        ,fields: ['id', 'key', 'name']
        ,emptyText: _('bagemails.form.grid.none')
        ,columns: [{
            header: _('bagemails.form.grid.key')
            ,dataIndex: 'key'
            ,sortable: true
            ,width: "100%"
        },{
            header: _('bagemails.form.grid.name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: "100%"
        }]

        ,tbar: [{
            text: _('bagemails.form.grid.add')
            ,handler: this.createOption
            ,scope: this
        }]
    });
    bagemails.grid.Form.superclass.constructor.call(this,config);

};
Ext.extend(bagemails.grid.Form,MODx.grid.Grid,{
    getMenu: function() {
        var m = [];

        m.push({
            text: _('bagemails.form.grid.edit')
            ,handler: this.edit
        });
        
        m.push("-");

        m.push({
            text: _('bagemails.form.grid.delete')
            ,handler: this.delete
        });
        
        return m;
    }

    ,createOption:function(btn, e){
        var w = Ext.getCmp('bagemails-window-option-create');
        if (w) {
            w.hide().getEl().remove();
        }

        w = MODx.load({
            xtype: 'bagemails-window-option-create',
            id: 'bagemails-window-option-create',
            record: [],
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
       
        w.show(e.target);
    }

    ,edit: function (btn, e, row) {
        
        if (typeof(row) != 'undefined') {
            this.menu.record = row.data;
        }

        var w = Ext.getCmp('bagemails-window-option-update');
        if (w) {
            w.close();
        }
       
        MODx.Ajax.request({
            url: this.config.url,
            params: {
                action: 'mgr/form/option/get',
                id: this.menu.record.id
            },
            listeners: {
                success: {
                    fn: function (r) {
                        console.log(r.object)
                        w = MODx.load({
                            xtype: 'bagemails-window-option-update',
                            id: 'bagemails-window-option-update',
                            record: r.object,
                            listeners: {
                                success: {
                                    fn: function () {
                                        this.refresh();
                                    }, scope: this
                                }
                            }
                        });
                        w.fp.getForm().reset();
                        w.fp.getForm().setValues(r.object);
                        w.show(e.target);
                    }, scope: this
                }
            }
        });
    },

    delete: function () {
        if (!this.menu.record) {
            return false;
        }

        MODx.msg.confirm({
            title: _('bagemails_menu_remove') + '"' + this.menu.record.key + '"',
            text: _('bagemails_menu_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/form/option/delete',
                id: this.menu.record.id,
            },
            listeners: {
                success: {
                    fn: function () {
                        this.refresh();
                    }, scope: this
                }
            }
        });
    },
});
Ext.reg('bagemails-grid-form',bagemails.grid.Form);