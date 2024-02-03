bagemails.grid.Emails = function(config) {
    config = config || {};
    console.log(config)
    Ext.applyIf(config,{
        title: _('bagemails.form')
        ,url: bagemails.config.connectorUrl
        ,baseParams: {
            action: 'mgr/emails/getList'
            ,form_id: MODx.request.id
        }
        ,autosave: true
        ,preventSaveRefresh: false
        ,paging: true
        ,remoteSort: true
        ,emptyText: _('bagemails.emails.grid.none')
        ,columns: this.getColumns(config)
        ,tbar: []
    });
    bagemails.grid.Emails.superclass.constructor.call(this,config);
};

Ext.extend(bagemails.grid.Emails,MODx.grid.Grid,{
    getMenu: function() {
        var m = [];

        m.push({
            text: _('bagemails.form.grid.delete')
            ,handler: this.delete
        });
        
        return m;
    }
    ,getColumns: function(config){
        let columns = [];
        config.hColumns.forEach((item, index) => {
            columns.push(
                {
                    header: item
                    ,dataIndex: config.fields[index]
                    ,sortable: true
                    ,width: "10%"
                }
            )
        });

        return columns;
    }
    ,delete: function () {
        if (!this.menu.record) {
            return false;
        }

        MODx.msg.confirm({
            title: _('bagemails_menu_remove'),
            text: _('bagemails_menu_remove_confirm'),
            url: this.config.url,
            params: {
                action: 'mgr/emails/delete',
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
Ext.reg('bagemails-grid-emails',bagemails.grid.Emails);