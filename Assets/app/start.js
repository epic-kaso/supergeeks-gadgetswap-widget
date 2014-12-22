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

app.run(function ($rootScope, CurrentGadget,GadgetsInfoServ) {
    GadgetsInfoServ.get();
    $rootScope.currentGadget = CurrentGadget.fetch();
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
            'Networks': function ($rootScope) {
                if ($rootScope.currentGadget.make == '' ||
                    $rootScope.currentGadget.model == '' ||
                    $rootScope.currentGadget.size == ''
                ) {
                    $rootScope.$broadcast('required:size');
                }

                return [
                    {
                        name: 'Airtel',
                        image_url: 'airtel.jpg'
                    },
                    {
                        name: 'Mtn',
                        image_url: 'mtn.jpg'
                    },
                    {
                        name: 'Glo',
                        image_url: 'glo.jpg'
                    },
                    {
                        name: 'Etisalat',
                        image_url: 'etisalat.jpg'
                    }
                ]
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
        .state(success)
});