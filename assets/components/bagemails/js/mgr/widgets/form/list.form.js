bagemails.grid.ListForms = function(config) {
    config = config || {};

    Ext.applyIf(config,{
        title: _('bagemails.form')
        ,url: bagemails.config.connectorUrl
        ,baseParams: {
            action: 'mgr/form/getList'
        }
        
        ,autosave: true
        ,preventSaveRefresh: false
        ,fields: ['id','name']
        ,paging: true
        ,remoteSort: true
        ,emptyText: _('bagemails.form.list.none')
        ,columns: [{
            header: 'id'
            ,dataIndex: 'id'
            ,sortable: true
            ,width: "10%"
        },{
            header: _('bagemails.form.list.name')
            ,dataIndex: 'name'
            ,sortable: true
            ,width: "90%"
        }]
        ,tbar: [{
            text: _('bagemails.form.list.add')
            ,handler: this.create
            ,scope: this
        }]
    });
    bagemails.grid.ListForms.superclass.constructor.call(this,config);
};

Ext.extend(bagemails.grid.ListForms,MODx.grid.Grid,{
    getMenu: function() {
        var m = [];

        m.push({
            text: _('bagemails.form.list.emails')
            ,handler: this.emails
        });

        m.push("-");

        m.push({
            text: _('bagemails.form.grid.edit')
            ,handler: this.edit
        });
        
        return m;
    }

    ,edit: function() {
        MODx.loadPage('update', 'namespace=bagemails&id='+ this.menu.record.id);
    }
    
    ,create:function(btn, e){
        MODx.loadPage('create', 'namespace=bagemails');
    }

    ,emails:function(btn, e){
        MODx.loadPage('emails', 'namespace=bagemails&id='+this.menu.record.id);
    }

    ,delete: function(btn,e) {
        if (!this.menu.record) return false;

        MODx.msg.confirm({
            title: _('bagemails.form.remove')
            ,text: _('bagemails.form.remove_confirm')
            ,url: this.config.url
            ,params: {
                action: 'mgr/form/delete'
                ,id: this.menu.record.id
            }
            ,listeners: {
                'success': {fn:function(r) { this.refresh(); },scope:this}
            }
        });

        return true;
    }

});
Ext.reg('bagemails-grid-list-forms',bagemails.grid.ListForms);