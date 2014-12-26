/**
 * Created by kaso on 11/6/2014.
 */

var app = angular.module('SupergeeksWidget.Controllers', []);

app.controller('NavbarController', [
    '$scope','$rootScope', function ($scope,$rootScope) {

        $rootScope.$on('progress-update-event', function (event, val) {
            console.log('Progress Update Event Received');
            $scope.progress = val;
        });
    }
]);

app.controller('DeviceMakeController', function ($scope,Makes,$rootScope) {
    $rootScope.tiny_heading = 'Swap your all Gadget for Awesome Rewards';
    $rootScope.big_heading = 'First, Choose your Make';
    $rootScope.device_make = 'others';

    $scope.makes = Makes;
    console.log(Makes);
    $scope.$emit('progress-update-event', '10%');

});

app.controller('DeviceModelController',
    function (CurrentMake,Models,$scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope) {

        $rootScope.big_heading = 'Secondly, Choose your Model';
        $scope.$emit('progress-update-event', '20%');

        $scope.currentGadget.make = $stateParams.device_make;
        $scope.currentGadget.current_make = CurrentMake;

        $scope.image_label = $scope.currentGadget.make;
        $scope.image_url = CurrentMake.image_url || 'smartphone.png';

        $scope.models = Models;

        $scope.selectModel = function ($event,model, state) {
            $scope.currentGadget.model = model;
            $scope.currentGadget.image_url = $scope.currentGadget.model.image_url || '/Assets/img/smartphone.png';
            $state.go(state,
                {
                    device_make: $stateParams.device_make,
                    device_model: model.slug
                });
            console.log($event);
            $event.preventDefault();
        }
    });

app.controller('DeviceSizeController',
    function ($scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope,GadgetsInfoServ) {
        $rootScope.big_heading = 'Ok, Select your Device Storage Size';

        $scope.$emit('progress-update-event', '54%');

        $scope.device_class = $rootScope.device_make = 'others';
        $scope.image_label = $scope.currentGadget.make +' '+
                             $scope.currentGadget.model.model;
        $scope.image_url = $scope.currentGadget.model.image_url || '/Assets/img/smartphone.png';

        $scope.sizes = $scope.currentGadget.model.sizes;

        $scope.selectSize = function ($event,size, state) {
            $scope.currentGadget.size = size;
            $scope.currentGadget.baseLinePrice = GadgetsInfoServ.getBaseLinePriceForSize($scope.currentGadget.model,size.value);
            $state.go(state,
                {
                    device_make: $stateParams.device_make,
                    device_model: $stateParams.device_model,
                    device_size: size.slug
                }
            );
            $event.preventDefault();
        }
    });


app.controller('DeviceNetworkController',
    function (Networks, $scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope) {
        $rootScope.big_heading = 'Please, Select your Network Provider';

        $scope.$emit('progress-update-event', '65%');

        $scope.device_class = $rootScope.device_make = 'others';
        $scope.image_label = $scope.currentGadget.make +' '+
        $scope.currentGadget.model.model;
        $scope.image_url = $scope.currentGadget.model.image_url || '/Assets/img/smartphone.png';
        $scope.networks = Networks;

        $scope.selectNetwork = function ($event, network, state) {
            $scope.currentGadget.network = network;
            $state.go(state,
                {
                    device_make:  $stateParams.device_make,
                    device_model: $stateParams.device_model,
                    device_size:  $stateParams.device_size,
                    device_network: network.slug
                });
            $event.preventDefault();
        }
    });

app.controller('DeviceConditionController',
    function (CurrentModel,$scope, $stateParams, GadgetsInfoServ, $state,$rootScope) {
        $scope.conditions = GadgetsInfoServ.getConditions();
        $scope.image_url = $scope.currentGadget.model.image_url || '/Assets/img/smartphone.png';
        $rootScope.big_heading = 'Lastly, Tell Us Your Device\'s Condition';

        $scope.$emit('progress-update-event', '70%');

        $scope.$watch('radioModel', function (newv, oldv) {
            for(var i = 0;i < $scope.conditions.length;i++){
                if($scope.conditions[i].slug == newv) {
                    $scope.currentGadget.condition_value = $scope.conditions[i].value;
                    break;
                }
            }
            $scope.currentGadget.condition = newv;
            $scope.$emit('progress-update-event', '80%');
        });

        $scope.device_class = $rootScope.device_make = 'others';

        $scope.image_label = $scope.currentGadget.make +' '+
                             $scope.currentGadget.model.model+ ' '+
                             $scope.currentGadget.size.value + ' ';

        $scope.viewReward = function (state) {
            $scope.$emit('progress-update-event', '100%');
            $state.go(state,
                {
                    device_make:  $stateParams.device_make,
                    device_model: $stateParams.device_model,
                    device_size:  $stateParams.device_size,
                    device_network: $stateParams.device_network,
                    device_condition: $scope.currentGadget.condition
                }
            );
        }
    });

app.controller('DeviceRewardController',
    function ($scope,$rootScope,$filter) {
        $rootScope.big_heading = 'Yay!, Here\'s your Reward';

        var reward = $scope.currentGadget.getReward();
        if(angular.isNumber(reward)){
            $scope.reward_price = $filter('currency')(reward,'₦') + '*';
            $scope.reward_message = 'Estimated Value is';
            $scope.reward_disclaimer = '*this value is subject to final verification by our engineers.'
        }else{
            $scope.reward_price = reward;
        }
    });

app.controller(
    'BookAppointmentController',
    function ($scope, $stateParams, $cookieStore, MailServ,$state,$rootScope) {
        $rootScope.big_heading = "Book An Appointment";
        $scope.swap_center = $stateParams.swap_center.split('-').join(' ');

        $scope.sendMail = function (destination, message) {
            var promise = MailServ.send(message, destination);
            promise.then(function (data) {
                console.log(data);
                $state.go('success');
            }, function (response) {
                console.log(response);
                alert('Error Occured, try again');
            });
        }
    });