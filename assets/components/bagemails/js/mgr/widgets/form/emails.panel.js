bagemails.panel.Emails = function (config) {
    config = config || {};
    
    Ext.apply(config, {
        id: 'bagemails-panel-emails'
        ,cls: 'container'
        ,items: [{
            html: '<h2>' + _('bagemails') + ' :: ' + _('bagemails_emails') + '</h2>',
            cls: 'modx-page-header',
        }, {
            deferredRender: false
            ,border: true
            ,defaults: {
                autoHeight: true
                ,layout: 'form'
                ,labelWidth: 150
                ,bodyCssClass: 'main-wrapper'
                ,layoutOnTabChange: true
            }
            ,items: [{
                border: false
                ,defaults: {
                    msgTarget: 'under'
                }
                ,items: [{
                    xtype: 'bagemails-form-emails'
                   
                },{
                    xtype: 'bagemails-grid-emails'
                    ,id: 'bagemails-grid-emails'
                    ,fields: config.dataGrid.fields
                    ,hColumns: config.dataGrid.columns
                    ,preventRender: true
                }] 
            }]
        }]
    });
    bagemails.panel.Emails.superclass.constructor.call(this, config);
};
Ext.extend(bagemails.panel.Emails, MODx.Panel);
Ext.reg('bagemails-panel-emails', bagemails.panel.Emails);
