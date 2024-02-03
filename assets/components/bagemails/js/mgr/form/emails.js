bagemails.page.Emails = function (config) {
    config = config || {};
    
    Ext.apply(config, {
        cls: 'container',
        formpanel: 'bagemails-panel-emails',
        buttons:  [{
            text: _('cancel')
            ,params: {a:'list', namespace:'bagemails'}
        }],

        components: [{
            xtype: 'bagemails-panel-emails'
            ,dataGrid: config.dataGrid
        }],
    });
    bagemails.page.Emails.superclass.constructor.call(this, config);
};

Ext.extend(bagemails.page.Emails, MODx.Component);
Ext.reg('bagemails-page-emails', bagemails.page.Emails);
