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

app.controller('DeviceMakeController', function ($scope,Makes,$rootScope,TimeLine) {
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
        $scope.image_label = $scope.currentGadget.make;
        $scope.image_url = CurrentMake.image_url || 'smartphone.png';

        $scope.models = Models;

        $scope.selectModel = function (model, state) {
            $scope.currentGadget.model = model;
            $state.go(state);
        }
    });

app.controller('DeviceColorController',
    function ($scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope) {
        $rootScope.big_heading = 'Now, Choose your Device Color';

        $scope.$emit('progress-update-event', '42%');
        $scope.image_label = $scope.currentGadget.make + ' ' +$scope.currentGadget.model.model;

        $scope.colors = $scope.currentGadget.model.colors;

        $scope.selectColor = function (color, state) {
            $scope.currentGadget.color = color;
            $state.go(state);
        }

    });

app.controller('DeviceSizeController',
    function ($scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope) {
        $rootScope.big_heading = 'Ok, Select your Device Storage Size';

        $scope.$emit('progress-update-event', '54%');

        $scope.device_class = $rootScope.device_make = 'others';
        $scope.image_label = $scope.currentGadget.make +' '+
                             $scope.currentGadget.model.model+ ' '+
                             $scope.currentGadget.color.value;

        $scope.sizes = $scope.currentGadget.model.sizes;

        $scope.selectSize = function (size, state) {
            $scope.currentGadget.size = size;
            $state.go(state);
        }
    });


app.controller('DeviceNetworkController',
    function (Networks, $scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope) {
        $rootScope.big_heading = 'Please, Select your Network Provider';

        $scope.$emit('progress-update-event', '65%');

        $scope.device_class = $rootScope.device_make = 'others';
        $scope.image_label = $scope.currentGadget.make +' '+
        $scope.currentGadget.model.model+ ' '+
        $scope.currentGadget.color.value;

        $scope.networks = Networks;

        $scope.selectNetwork = function (network, state) {
            $scope.currentGadget.network = network;
            $state.go(state);
        }
    });

app.controller('DeviceConditionController',
    function ($scope, $stateParams, $cookieStore, GadgetServ, $state,$rootScope) {
        $rootScope.big_heading = 'Lastly, Tell Us Your Device\'s Condition';

        $scope.$emit('progress-update-event', '70%');

        $scope.$watch('radioModel', function (newv, oldv) {
            $scope.currentGadget.condition = newv;
            $scope.$emit('progress-update-event', '80%');
        });

        $scope.device_class = $rootScope.device_make = 'others';

        $scope.image_label = $scope.currentGadget.make +' '+
                             $scope.currentGadget.model.model+ ' '+
                             $scope.currentGadget.color.value + ' '+
                             $scope.currentGadget.size.value + ' ';

        $scope.viewReward = function (state,data) {
            $scope.$emit('progress-update-event', '100%');
            $state.go(state,data);
        }
    });

app.controller('DeviceRewardController',
    function ($scope,$rootScope) {
        $rootScope.big_heading = 'Yay!, Here\'s your Reward';

        $condition = $scope.currentGadget.condition;
        if($condition == 'Like New' || $condition == 'Normal'){
            $scope.reward_price = 'Upto N15,000 in Supergeeks Benefits';
        }else{
            $scope.reward_price = 'You really need to come in, so we can evaluate your device';
        }
    });

app.controller(
    'BookAppointmentController',
    function ($scope, $stateParams, $cookieStore, MailServ,$state,$rootScope) {
        $rootScope.big_heading = "Book An Appointment";

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