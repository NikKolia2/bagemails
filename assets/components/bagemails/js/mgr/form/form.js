
bagemails.page.Form = function (config) {
    config = config || {};

    config.isUpdate = (MODx.request.id) ? true : false;

    Ext.apply(config, {
        formpanel: 'bagemails-panel-form',
        cls: 'container',
        buttons:  [{
            text: _('save')
            ,method: 'remote'
            ,process: config.isUpdate ? 'mgr/form/update' : 'mgr/form/create'
            ,keys: [{
                key: MODx.config.keymap_save || 's'
                ,ctrl: true
            }]
        },{
            text: _('cancel')
            ,params: {a:'list', namespace:'bagemails'}
        }],
        components: [{
            xtype: 'bagemails-panel-form'
            ,isUpdate: config.isUpdate
        }]
    });
    bagemails.page.Form.superclass.constructor.call(this, config);
};
Ext.extend(bagemails.page.Form,MODx.Component);
Ext.reg('bagemails-page-form', bagemails.page.Form);
