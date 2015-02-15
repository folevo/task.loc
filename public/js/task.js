
//устновка конфигураций
Ext.Loader.setConfig({enabled: true});
//установка пути
Ext.Loader.setPath('Ext', '/ext-4.2.1.883');
//подключение компонентов
Ext.require([
'Ext.grid.*',
'Ext.data.*',
'Ext.selection.CheckboxModel'
]);

//выполнение после загрузки всех элеметов
Ext.onReady(function() {
    var city='empty';
    var education='empty';
    var name='empty';

    //определние модели и определение свойств модели
    Ext.define('myModel', {
    idProperty: 'id',
    extend: 'Ext.data.Model',
    fields: ['name','city','view_education','user_id']

    });
//получение содержимого Ext.form
var fm = Ext.form;
//задание количества элементов выводимых на странице
var itemsPerPage = 2

//создание объекта data store для использования в грид,которы получает данные с сервера посредством AJAX
var store = Ext.create('Ext.data.JsonStore', {
//указываем модель которую іспользовать
    model: 'myModel',
    //задаем поля
    fields: ['city', 'name', 'view_education','user_id'],
    //указывает колічество данных выводімых на странице
    pageSize: itemsPerPage,
    //указываем каким способом получаем данные
    proxy: {
    type: 'ajax',
    url: url_data,
    actionMethods: {
    read: 'POST'
    },
reader: {
    type: 'json',
    root: 'data'

    }



},
//указываем диапазон данных выбираемых из бд
autoLoad: {start: 0, limit: 5},
//установка сортировки
remoteSort: true


});
//создание выпадающего списка для городов
new Ext.form.ComboBox({
    //событие срабатывающие при выборе из списка города
    listeners: {

    select: function(combo, record, index) {
    //полученние города выброного из списка
    city=combo.value;

//проверка выбранны ли значенние в других выпадающих списках
    if(name=='empty' && education=='empty'){

//загрузка параметров для AJAX
    grid.store.load({
    params:{
    start:0,
    limit: 2,
    city: city
    }
});
}else if(name!='empty' && education=='empty'){
    //загрузка параметров для AJAX
    grid.store.load({
        params:{
            start:0,
            limit: 2,
            city: city,
            view_education:education
        }
});
}else if(name=='empty' && education!='empty'){
//загрузка параметров для AJAX
    grid.store.load({
        params:{
            start:0,
            limit: 2,
            city: city,
            view_education:education

        }
});
}else{
    //загрузка параметров для AJAX
    grid.store.load({
        params:{
            start:0,
            limit: 2,
            city: city,
            view_education:education,
            name: name
        }
});
}
//перезагрузка грид
grid.getStore().reload({
    callback: function(){
    grid.getView().refresh();
    }
});
}
},

queryMode:'local',
//указывает куда выводить выпадающий список
renderTo: 'grid4',
//задает название которое стоит напротив поля
fieldLabel: 'Страна',

store:  {            // конфигурация хранилища

    fields: [ 'city','id'],

    data: ajax_city

    },

displayField: 'city', // это текстовое значение <option>…</option>

valueField: 'city'   // а это значение поля <option value=»…»>



});
//создание выпадающего списка для ученной степени
new Ext.form.ComboBox({
    //событие срабатывающие при выборе из списка ученных степеней
    listeners: {
    select: function(combo, record, index) {
//полученние  ученной степени выброного из списка
    education=combo.value;



//проверка выбранны ли значенние в других выпадающих списках
    if(city=='empty' && name=='empty'){

//загрузка параметров для AJAX
    grid.store.load({
    params:{
    start:0,
    limit: 2,
    view_education:education
    }
});
}else if(name=='empty' && city!='empty'){
                        //загрузка параметров для AJAX
                        grid.store.load({
                            params:{
                                start:0,
                                limit: 2,
                                city: city,
                                view_education:education
                            }
                        });
                    }else if(name!='empty' && city=='empty'){
                        //загрузка параметров для AJAX
                        grid.store.load({
                            params:{
                                start:0,
                                limit: 2,

                                view_education:education,
                                name: name
                            }
                        });
                    }else{
                        //загрузка параметров для AJAX
                        grid.store.load({
                            params:{
                                start:0,
                                limit: 2,
                                city: city,
                                view_education:education,
                                name: name
                            }
                        });
                    }
                    //перезагрузка грид
                    grid.getStore().reload({
                        callback: function(){
                            grid.getView().refresh();
                        }
                    });
                }
            },
            queryMode:'local',
//указывает куда выводить выпадающий список
            renderTo: 'grid4',
//задает название которое стоит напротив поля
            fieldLabel: 'Ученная степень',

            store:  {            // конфигурация хранилища

                fields: [ 'view_education'],

                data: ajax_view_education

            },

            displayField: 'view_education', // это текстовое значение <option>…</option>

            valueField: 'view_education'   // а это значение поля <option value=»…»>



        });


//загрузка параметров для AJAX
    store.load({
    params:{
        start:0,
        limit: itemsPerPage
    }



});


       /* var store = Ext.data.StoreManager.lookup('userStore');
        var userfilter = new Ext.util.Filter({
            tyle:'list',
            property: 'name',
            value: 'dimas',
            anyMatch: true,
            caseSensitive: false,
            data: 'data'
        });*/
       // store.filter(userfilter);
      /****  var filters = {
            ftype: 'filters',
            encode: true,
            local: false
        };*/

//создание объекта грид панели
    var grid = Ext.create('Ext.grid.Panel', {
            //установка значеие свойства store
            store: store,


//установка плагина для редактирования ячеек таблицы
            plugins: [
                Ext.create('Ext.grid.plugin.CellEditing', {
                    clicksToEdit : 1
                })
            ],
//установка опций для колонок таблиц
            columns: [

                {

                    text     : 'Имя',
                    dataIndex: 'name',
                    filter: {
                        type: 'string'
                    }

                },



                {
                    text     : 'Город',
                    dataIndex: 'city',
                    filter: {
                        type: 'string'
                    }
                },
                {

                    text     : 'Ученная степень',

                    dataIndex: 'view_education',
                    editor: new fm.TextField({
                        allowBlank: false,
                        //установка слушателя на изменение ячейки ученная степень
                        listeners : {
                            change : function(field, e) {
//AJAX запрос на изменение данных в бд
                                Ext.Ajax.request({
                                    url: url_update, // your backend url
                                    method: 'POST',
                                    params: {
                                        'id': grid.getSelectionModel().getSelection()[0].data.user_id,
                                        'view_education': field.getValue()
                                    }
                                });
                            }
                        }
                    })

                }

            ],
            //настройка отображения таблицы
            height: 350,
            width: 800,
            title: 'Простейшая статическая таблица grid',
            //в каком элементе выводить таблицу
            renderTo: 'grid4',
            //установка пагинации
          dockedItems: [{
              xtype: 'pagingtoolbar',
              store: store,   // same store GridPanel is using
              dock: 'bottom',
              displayInfo: true
          }]
        });

    });

