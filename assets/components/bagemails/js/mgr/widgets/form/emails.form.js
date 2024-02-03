bagemails.panel.EmailsForm = function (config) {
    config = config || {};
    if (!config.id) {
        config.id = 'bagemails-form-emails';
    }

    Ext.apply(config, {
        layout: 'form',
        cls: 'main-wrapper',
        defaults: {msgTarget: 'under', border: false},
        anchor: '100% 100%',
        border: false,
        items: this.getFields(config),
        listeners: this.getListeners(config),
        url: bagemails.config.connectorUrl
    });
    bagemails.panel.EmailsForm.superclass.constructor.call(this, config);
};

Ext.extend(bagemails.panel.EmailsForm, MODx.FormPanel, {
    grid:null
    ,getFields: function(config){
        return [{
            layout: 'column',
            items: [{
                columnWidth: .308,
                layout: 'form',
                defaults: {anchor: '100%', hideLabel: true},
                items: [{
                    xtype: 'datefield',
                    id: config.id + '-begin',
                    emptyText: _('bagemails.form.emails.date.begin'),
                    name: 'date_start',
                    format: MODx.config['manager_date_format'] || 'Y-m-d',
                    startDay: +MODx.config['manager_week_start'] || 0,
                    listeners: {
                        select: {
                            fn: function () {
                                this.fireEvent('change');
                            }, scope: this
                        },
                    },
                },
                {
                    xtype: 'datefield',
                    id: config.id + '-end',
                    emptyText: _('bagemails.form.emails.date.end'),
                    name: 'date_end',
                    format: MODx.config['manager_date_format'] || 'Y-m-d',
                    startDay: +MODx.config['manager_week_start'] || 0,
                    listeners: {
                        select: {
                            fn: function () {
                                this.fireEvent('change');
                            }, scope: this
                        },
                    },
                },{
                    bodyStyle:"margin-top:10px"
                    ,items:[{
                        xtype: 'button'
                        ,margin:'10 0'
                        , text: _('bagemails.form.emails.btn_export')
                        , cls: 'primary-button'
                        , scope: this
                        , handler: this.export
                    }]
                }],
            }]
        }];
    }
    
    ,getListeners: function () {
        return {
            beforerender: function () {
                this.grid = Ext.getCmp('bagemails-grid-emails');
            },
            
            change: function () {
                this.submit();
            },
        }
    }

    ,submit: function () {
        var store = this.grid.getStore();
        var form = this.getForm();

        var values = form.getFieldValues();
        for (var i in values) {
            if (i != undefined && values.hasOwnProperty(i)) {
                
                store.baseParams[i] = values[i];
                
            }
        }
       
        this.refresh();
    }

    ,refresh: function () {
        this.grid.getBottomToolbar().changePage(1);
    }

    ,export: function(){
        let date = this.getForm().getFieldValues();
        let date1 = this.getForm().getValues();
        let date_start = date.date_start == ''? '': date1['date_start'];
        let date_end = date.date_end == ''? '': date1['date_end'];
       
        var  url = bagemails.config.exportUrl + '?form_id='+MODx.request.id+'&date_start='+date_start+'&date_end='+date_end;
        url += '&token=' + bagemails.config.token;
        
        window.open(url);
        return false;
     }
});

Ext.reg('bagemails-form-emails', bagemails.panel.EmailsForm);
