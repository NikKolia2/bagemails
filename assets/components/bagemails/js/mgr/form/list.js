bagemails.panel.ListForms = function (config) {
    config = config || {};
    Ext.apply(config, {
        cls: 'container',
        components: [{
            xtype: 'bagemails-page-list-forms'
        }],
        items: [{
            html: '<h2>' + _('bagemails') + ' :: ' + _('bagemails_form') + '</h2>',
            cls: 'modx-page-header',
        }, {
            xtype: 'modx-tabs',
            id: 'bagemails-list-forms-tabs',
            stateful: true,
            stateId: 'bagemails-list-forms-tabs',
            stateEvents: ['tabchange'],
            cls: 'bagemails-panel',
            getState: function () {
                return {
                    activeTab: this.items.indexOf(this.getActiveTab())
                };
            },
            items:[
                {
                    deferredRender: false
                    ,title: _('bagemails.form.list')
                    ,border: true
                    ,defaults: {
                        autoHeight: true
                        ,layout: 'form'
                        ,labelWidth: 150
                        ,bodyCssClass: 'main-wrapper'
                        ,layoutOnTabChange: true
                    }
                    ,items: [{
                        defaults: {
                            msgTarget: 'side'
                            ,autoHeight: true
                        }
                        ,cls: 'form-with-labels'
                        ,border: false
                        ,items: [{
                            
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
                                    xtype: 'bagemails-grid-list-forms'
                                    ,preventRender: true
                                }] 
                            }]
                        }]
                    }]
                }  
            ]
        }]
    });
    bagemails.panel.ListForms.superclass.constructor.call(this, config);
};
Ext.extend(bagemails.panel.ListForms, MODx.Panel);
Ext.reg('bagemails-page-list-forms', bagemails.panel.ListForms);
