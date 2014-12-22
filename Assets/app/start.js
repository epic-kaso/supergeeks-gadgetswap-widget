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

app.run(function ($rootScope, CurrentGadget,GadgetsInfoServ,TimeLine,$location) {
    GadgetsInfoServ.get();
    $rootScope.currentGadget = CurrentGadget.fetch();
    $rootScope.next = {};
    $rootScope.next.url = TimeLine.next();

    $rootScope.$on('$stateChangeStart',function(event,toState,toParams,fromState,fromParams){
        console.log(toState);
        switch (toState){
            case 'device-model':
                var p = toParams.device_make;
                if(p == '' || angular.isUndefined(p)){
                    event.preventDefault();
                }
                break;
            case 'device-size':
                if(
                    angular.isUndefined($rootScope.currentGadget.make) ||
                    angular.isUndefined($rootScope.currentGadget.model)
                ){
                    event.preventDefault();
                }
        }
    });

});

app.constant('ViewBaseURL','/Assets/app/views');

app.factory('TimeLine',function($state){
    var timeline = [
            'device-model',
            'device-size',
            'device-network',
            'device-condition',
            'device-reward'
        ],
        current = false;
    var prev = [
        'device-make'
    ];
    return {
        next: function(){
            if(current != false){
                prev.unshift(current);
            }
            current =  timeline.shift();
            return current;
        },
        prev: function(){
            current = prev.shift();
            timeline.unshift(current);
            return current;
        }
    }
});

app.config(function ($stateProvider, $urlRouterProvider,ViewBaseURL) {
    //
    // For any unmatched url, redirect to /state1
    $urlRouterProvider.otherwise("/device-make");
    //
    // Now set up the states

    var deviceMake = {
        name: 'device-make',
        url: "/device-make",
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
        url: "/device-model/:device_make",
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
        url: "/device-size",
        templateUrl: ViewBaseURL+"/device-size.html",
        controller: 'DeviceSizeController',
        resolve: {
            'validDevice': function ($rootScope, $location) {

            }
        }
    };

    var deviceNetwork = {
        name: 'device-network',
        url: "/device-network",
        templateUrl: ViewBaseURL+"/device-network.html",
        controller: 'DeviceNetworkController',
        resolve: {
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
        url: "/device-condition",
        templateUrl: ViewBaseURL+"/device-condition.html",
        controller: 'DeviceConditionController',
        resolve: {
            'validDevice': function ($rootScope, $state) {
                if (
                    $rootScope.currentGadget.make == '' ||
                    $rootScope.currentGadget.model == '' ||
                    $rootScope.currentGadget.network == '' ||
                    $rootScope.currentGadget.size == ''
                ) {
                    $rootScope.$broadcast('required:network');
                } else {
                    return true;
                }
            }
        }
    };

    var deviceReward = {
        name: 'device-reward',
        url: "/device-reward",
        templateUrl: ViewBaseURL+"/device-reward.html",
        controller: 'DeviceRewardController',
        resolve: {
            'validDevice': function ($rootScope, $state) {
                if ($rootScope.currentGadget.make == '' ||
                    $rootScope.currentGadget.model == '' ||
                    $rootScope.currentGadget.network == '' ||
                    $rootScope.currentGadget.size == '' ||
                    $rootScope.currentGadget.condition == ''
                ) {
                    $rootScope.$broadcast('required:condition');
                } else {
                    return true;
                }
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