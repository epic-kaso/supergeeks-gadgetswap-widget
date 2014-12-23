/**
 * Created by kaso on 11/6/2014.
 */

var app = angular.module('SupergeeksWidget',
    [
        'ui.router',
        'ui.bootstrap',
        'ngCookies',
        'ngAnimate',
        'SupergeeksWidget.Controllers',
        'SupergeeksWidget.Services',
        'SupergeeksWidget.directives'
    ]);

app.run(function ($rootScope, CurrentGadget,GadgetsInfoServ,$location) {
    GadgetsInfoServ.get();
    $rootScope.currentGadget = CurrentGadget.fetch();
    $rootScope.updateAndGetCurrentModel = function($stateParams,$rootScope,GadgetsInfoServ){
        //:device_make/:device_model/:device_size/:device_network/:device_condition
        angular.forEach($stateParams,function(value,key){
            switch (key){
                case 'device_make':
                    if(!$rootScope.currentGadget.make) {
                        $rootScope.currentGadget.make = value;
                        $rootScope.currentGadget.current_make = GadgetsInfoServ.getMakeByName(value);
                    }
                    break;
                case 'device_model':
                    if(!$rootScope.currentGadget.model)
                        $rootScope.currentGadget.model = GadgetsInfoServ.getModelBySlug(value);
                    break;
                case 'device_size':
                    if(!$rootScope.currentGadget.size)
                        $rootScope.currentGadget.size = GadgetsInfoServ.getSizeModelBySlug(value,$rootScope.currentGadget.model);
                    break;
                case 'device_network':
                    if(!$rootScope.currentGadget.network)
                        $rootScope.currentGadget.network = GadgetsInfoServ.getNetworkBySlug(value);
                    break;
                case 'device_condition':
                    if(!$rootScope.currentGadget.condition)
                        $rootScope.currentGadget.condition = value;
                    break;
            }
        });
    };

    $rootScope.$on('$stateChangeStart',function(event,toState,toParams,fromState,fromParams){
        $rootScope.updateAndGetCurrentModel(toParams,$rootScope,GadgetsInfoServ);
    });

    $rootScope.$on('$stateNotFound',function(){
        $location.path('/devices');
    })
});

app.constant('ViewBaseURL','/Assets/app/views');

app.config(function ($stateProvider, $urlRouterProvider,ViewBaseURL) {
    $urlRouterProvider.otherwise("/devices");

    var deviceMake = {
        name: 'device-make',
        url: "/devices",
        templateUrl: ViewBaseURL+"/device-make.html",
        controller: "DeviceMakeController",
        resolve: {
            'Makes': function (GadgetsInfoServ) {
                return GadgetsInfoServ.get();
            }
        }
    };

    var deviceModel = {
        name: 'device-model',
        url: "/devices/:device_make",
        templateUrl: ViewBaseURL+"/device-model.html",
        controller: 'DeviceModelController',
        resolve: {
            'Models': function($state,$stateParams,GadgetsInfoServ){
                var p = $stateParams.device_make;
                return GadgetsInfoServ.getModelsFor(p);
            },
            'CurrentMake': function($stateParams,GadgetsInfoServ){
                return GadgetsInfoServ.getMakeByName($stateParams.device_make);
            }
        }
    };


    var deviceSize = {
        name: 'device-size',
        url: "/devices/:device_make/:device_model",
        templateUrl: ViewBaseURL+"/device-size.html",
        controller: 'DeviceSizeController',
        resolve: {
            'CurrentModel': function($stateParams,GadgetsInfoServ){
                return GadgetsInfoServ.getModelByName($stateParams.device_model);
            }
        }
    };

    var deviceNetwork = {
        name: 'device-network',
        url: "/devices/:device_make/:device_model/:device_size",
        templateUrl: ViewBaseURL+"/device-network.html",
        controller: 'DeviceNetworkController',
        resolve: {
            'CurrentModel': function($stateParams,GadgetsInfoServ){
                return GadgetsInfoServ.getModelByName($stateParams.device_model);
            },
            'Networks': function ($rootScope,GadgetsInfoServ) {
                return GadgetsInfoServ.getNetworks();
            }
        }
    };

    var deviceCondition = {
        name: 'device-condition',
        url: "/devices/:device_make/:device_model/:device_size/:device_network",
        templateUrl: ViewBaseURL+"/device-condition.html",
        controller: 'DeviceConditionController',
        resolve: {
            'CurrentModel': function($stateParams,GadgetsInfoServ){
                return GadgetsInfoServ.getModelByName($stateParams.device_model);
            }
        }
    };

    var deviceReward = {
        name: 'device-reward',
        url: "/devices/:device_make/:device_model/:device_size/:device_network/:device_condition",
        templateUrl: ViewBaseURL+"/device-reward.html",
        controller: 'DeviceRewardController',
        resolve: {
            'CurrentModel': function($stateParams,GadgetsInfoServ){
                return GadgetsInfoServ.getModelByName($stateParams.device_model);
            }
        }
    };

    var bookAppointment = {
        name: 'book-appointment',
        url: "/book-appointment",
        templateUrl: ViewBaseURL+"/book-appointment.html",
        controller: 'BookAppointmentController'
    };

    var success = {
        name: 'success',
        url: "/successful-booking",
        templateUrl: "app/views/book-success.html",
        controller: function ($scope) {

        }
    };

    $stateProvider
        .state(deviceMake)
        .state(deviceModel)
        .state(deviceSize)
        .state(deviceNetwork)
        .state(deviceCondition)
        .state(deviceReward)
        .state(bookAppointment)
        .state(success);
});