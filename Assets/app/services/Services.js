/**
 * Created by kaso on 11/6/2014.
 */
var app = angular.module('SupergeeksWidget.Services', []);

app.factory('GadgetServ', function () {
    var models = {};
    var devices = {};

    return {
        getModels: function () {
            return models;
        },
        setModels: function(data){
            models = data;
        },
        setDevices: function(data){
            devices = data;
        }
    };
});

app.factory('MailServ', function ($http) {
    var url = 'https://mandrillapp.com/api/1.0/messages/send.json';
    var data = {
        "message": {
            "html": "",
            "text": "",
            "subject": "Supergeeks Widget",
            "from_email": "supergeeks-widget@supergeeks.com.ng",
            "from_name": "Supergeeks Widget",
            "to": [
                {
                    "email": "",
                    "type": "to"
                }
            ],
            "headers": {"Reply-To": "no-reply@supergeeks.com.ng"},
            "important": true
        },
        "key": "XZ6K0JEmpINZY4sw6_1F2g"
    };

    return {
        send: function(message,destination){
            data['message']['html'] = message;
            data['message']['text'] = message;
            data['message']['to'][0].email = destination;
            return $http.post(url,data);
        }
    }
});

app.factory('CurrentGadget',function(){
    var gadget = new Gadget({});
    return {
        fetch: function(){
            return gadget;
        }
    }
});

app.factory('GadgetsInfoServ',function($http,GadgetServ,$q){
    var cache;
    return {
        'get': function(){
            var p = $q.defer();
            if(cache){
                p.resolve(cache);
            }else{
                $http.get(window.location.origin + '/devices',{
                    'headers': {
                        'X_REQUESTED_WITH': 'XMLHttpRequest'
                    }
                }).then(function(response){
                    console.log('Success');
                    console.log(response.data);
                    GadgetServ.setModels(response.data.models);
                    GadgetServ.setDevices(response.data.devices);
                    cache = response.data;
                    p.resolve(response.data);

                },function(response){
                    console.log('Failure');
                    console.log(response);
                    p.reject(false);
                });
            }
            return p.promise;
        },
        'getModelsFor': function(maker){
            var result = [];

            angular.forEach(cache.models,function(value,key){
                if(value['name'] == maker){
                    var interestId = value['id'];
                    angular.forEach(cache.devices,function(v,k){
                        if(v['gadget_maker_id'] == interestId)
                            this.push(v);
                    },result);
                }
            });

            return result;
        },
        getMakeByName: function(device_make){
            var result = {data: ''};
            angular.forEach(cache.models,function(value,key){
                if(value['name'] == device_make) {
                    this.data = value;
                    return value;
                }

            },result);

            return result.data;
        }
    }
});