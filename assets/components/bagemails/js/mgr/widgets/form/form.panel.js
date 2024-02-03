bagemails.panel.Form = function (config) {
    config = config || {};

    let header = _('bagemails') + ' :: ' + _('bagemails.form.create.header');
    if(config.isUpdate){
        header = _('bagemails') + ' :: ' + _('bagemails.form.update.header');
    }

    
    Ext.apply(config, {
        id: 'bagemails-panel-form'
        ,cls: 'container'
        ,url: bagemails.config.connectorUrl
        ,baseParams: {
            action: 'mgr/form/create'
        }
        
        ,listeners: {
            'setup': {
                fn: this.setup
                ,scope: this
            }
            ,'success': {
                fn: this.success
                ,scope: this
            }
        }   
        ,items: [{
            html: '<h2>' + header + '</h2>',
            cls: 'modx-page-header',
        },{
            name: 'id'
            ,xtype: 'hidden'
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
            ,items: this.getItems(config)
        }]
    });
    bagemails.panel.Form.superclass.constructor.call(this, config);
};
Ext.extend(bagemails.panel.Form, MODx.FormPanel,{
    getItems: function(config){
        let fields = [{      
            border: false
            ,height: 100
            ,defaults: {
                layout: 'form'
                ,labelAlign: 'top'
                ,labelSeparator: ''
                ,anchor: '100%'
                ,border: false
            }
            ,items: [{
                border: false
                ,defaults: {
                    msgTarget: 'under'
                }
                ,items: [{
                    xtype: 'textfield'
                    ,fieldLabel: _('bagemails.form.name')
                    ,allowBlank: true
                    ,id: 'bagemails-panel-form-field'
                    ,name: 'name'
                    ,anchor: '100%'
                }]
            }]
        }];


        if(config.isUpdate){
            fields.push({ 
                bodyStyle: 'margin-top: 20px;'
                ,border: false
                ,defaults: {
                    msgTarget: 'under'
                }
                ,items: [{
                    xtype: 'bagemails-grid-form'
                    ,fieldLabel: _('bagemails.form.grid')
                    ,anchor: '100%'
                }] 
            });
        }

        return  [{
            defaults: {
                msgTarget: 'side'
                ,autoHeight: true
            }
            ,cls: 'form-with-labels'
            ,border: false
            ,items: fields,
        }]
    }
    ,setup: function() {
        if (this.config.isUpdate) {
            MODx.Ajax.request({
                url: this.config.url
                ,params: {
                    action: 'mgr/form/get'
                    ,id: MODx.request.id
                },
                listeners: {
                    'success': {
                        fn: function(r) {      
                            console.log(r.object)            
                            this.getForm().setValues(r.object);
                            this.fireEvent('ready', r.object);
                            MODx.fireEvent('ready');
                        },
                        scope: this
                    }
                }
            });
        } else {
            this.fireEvent('ready');
            MODx.fireEvent('ready');
        }
    }
    ,success: function(o, r) {
        if (this.config.isUpdate == false) {
            MODx.loadPage('update', 'namespace=bagemails&id='+ o.result.object.id);
        }
    }
});
Ext.reg('bagemails-panel-form', bagemails.panel.Form);
