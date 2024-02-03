bagemails.window.CreateOption = function (config) {
    config = config || {};
    
    Ext.applyIf(config, {
        title: _('bagemails.form.option.create'),
        width: 800,
        baseParams: {
            action: 'mgr/form/option/create',
        },
    });
    bagemails.window.CreateOption.superclass.constructor.call(this, config);
};

Ext.extend(bagemails.window.CreateOption, bagemails.window.Default, {
    getFields: function (config) {
        return [{
            layout: 'form',
            items: this.getForm(config)
        }];
    },

    getForm: function (config) {
        return [{
            xtype: 'hidden',
            name: 'id',
            id: config.id + '-id',
        },{
            xtype: 'hidden',
            name: 'form_id',
            value: MODx.request.id,
            id: config.id + '-form-id',
        },{
            xtype: 'textfield',
            fieldLabel: _('bagemails.form.option.name'),
            name: 'name',
            anchor: '99%',
            id: config.id + '-name',
            allowBlank: false,
            
        },
        {
            xtype: 'textfield',
            fieldLabel: _('bagemails.form.option.key'),
            name: 'key',
            anchor: '99%',
            id: config.id + '-key',
            allowBlank: false,
            
        }];
    }
});
Ext.reg('bagemails-window-option-create', bagemails.window.CreateOption);


bagemails.window.UpdateOption = function (config) {
    config = config || {};

    Ext.applyIf(config, {
        title: _('bagemails_menu_update'),
        baseParams: {
            action: 'mgr/form/option/update',
        }
    });
    bagemails.window.UpdateOption.superclass.constructor.call(this, config);
};
Ext.extend(bagemails.window.UpdateOption, bagemails.window.CreateOption);
Ext.reg('bagemails-window-option-update', bagemails.window.UpdateOption);
