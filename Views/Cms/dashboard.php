<!DOCTYPE html>
<html class="no-js" ng-app="AdminApp">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <style>
        .size-info.list-group {
            margin-top: 4px;
        }

        .flyout-parent{
            position: relative;
        }

        .flyout{
            position: absolute;
            display: none;
            right: -50px;
            z-index: 100;
        }

        .flyout:hover{
            display: block !important;
        }
        .flyout-parent:hover + .flyout,.flyout-parent:active + .flyout{
            display: block !important;
        }

    </style>
</head>
<body>


<div class="navbar navbar-plain">
    <div class="container-fluid">
        <div class="navbar-header">
        </div>

        <ul class="nav navbar-nav">
        </ul>
    </div>
</div>

<div class="container">
    <div class="col-md-3">
        <div class="list-group">
            <a ui-sref="allDevices" ui-sref-active="active" class="list-group-item flyout-parent">
                All Devices <span class="glyphicon glyphicon-chevron-down pull-right"></span>
            </a>
            <div class="flyout">
                <ul class="list-group">
                    <li class="list-group-item"><a href="/devices">All</a></li>
                    <?php if(isset($models) && is_array($models)){
                        foreach($models as $value){
                            ?> <li class="list-group-item"><a href="?model=<?= $value->id ?>"><?= $value->name ?></a></li>
                        <?php
                        }
                    } ?>
                </ul>
            </div>
            <a ui-sref="allUsers" ui-sref-active="active" class="list-group-item">Users</a>
            <a ui-sref="allNetworks" ui-sref-active="active" class="list-group-item">Networks</a>
        </div>

        <div class="list-group">
            <a class="list-group-item" ui-sref-active="active" ui-sref="addMaker">Add Make</a>
            <a class="list-group-item" ui-sref-active="active" ui-sref="addDevice">Add Device</a>
            <a class="list-group-item" ui-sref-active="active" ui-sref="addNetwork">Add Network</a>
        </div>
    </div>

    <div class="col-md-9">
        <div  class="row" ui-view>
        </div>
    </div>
</div>
</body>

<script id="AllDevices.html" type="text/ng-template">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Devices</h2>
        </div>
        <table class="table table-striped">
            <thead>
                <tr>
                    <td>Make</td>
                    <td>Model</td>
                    <td>Sizes</td>
                    <td>Colors</td>
                    <td>Baseline Price</td>
                    <td>Conditions</td>
                </tr>
            </thead>
            <tbody>
            <?php if(isset($devices)){
                foreach($devices as $value){
                    $maker = GadgetMaker::find($value->gadget_maker_id);
                    ?>
                    <tr>
                        <td><?= $maker->name ?></td>
                        <td><?= $value->model ?></td>
                        <td>
                            <ul class="list-unstyled">
                                <?php
                                    foreach($value->sizes as $s){
                                        ?>
                                        <li> <?= $s->value ?></li>
                                    <?php
                                    }
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-unstyled">
                                <?php
                                    foreach($value->colors as $s){
                                        ?>
                                        <li> <?= $s->value ?></li>
                                    <?php
                                    }
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-unstyled">
                                <?php
                                    foreach($value->base_line_prices as $s){
                                        ?>
                                        <li><?= $s->size ?>: N<?= $s->value ?></li>
                                    <?php
                                    }
                                ?>
                            </ul>
                        </td>
                        <td>
                            <ul class="list-unstyled">
                                <li>Normal - <?= $maker->normal_condition ?>%</li>
                                <li>Faulty - <?= $maker->scratched_condition ?>%</li>
                                <li>Bad - <?= $maker->bad_condition ?>%</li>
                            </ul>
                        </td>
                        <td>
                            <a ng-click="deleteItem(<?=$value->id ?>)" class="btn btn-xs btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php
                }
            } ?>
            </tbody>
        </table>
    </div>
</script>

<script id="AllNetworks.html" type="text/ng-template">
    <div class="panel panel-default">
        <div class="panel-heading">
            <h2>Network providers</h2>
        </div>
        <table class="table table-striped">
            <tbody>
            <?php if(isset($networks)){
                $index = 0;
                foreach($networks as $value){
                    $index++;
                    ?>
                    <tr>
                        <td><?= $index ?></td>
                        <td>
                            <h4><?= $value->name ?></h4>
                            <p>
                                <?= $value->description ?>
                            </p>
                        </td>
                        <td>
                            <a ng-click="deleteItem(<?=$value->id ?>)" class="btn btn-xs btn-danger">Delete</a>
                        </td>
                    </tr>
                <?php
                }
            } ?>
            </tbody>
        </table>
    </div>
</script>

<script id="AddNetwork.html" type="text/ng-template">
    <div>
        <form action="devices/add-network" method="post">
            <div class="col-md-6">
                <div  class="form-group">
                    <label>Network Name</label>
                    <input type="text" name="name" placeholder="e.g Mtn Ng"  class="form-control"/>
                </div>
                <div class="form-group">
                    <label>Bonus Information</label>
                    <textarea class="form-control" name="description" placeholder="Get Extra 1 Gigabyte of data"></textarea>
                </div>
                <div  class="form-group">
                    <input type="submit" value="Submit"  class="btn"/>
                </div>
            </div>
        </form>
    </div>
</script>

<script id="AddMaker.html" type="text/ng-template">
<div>
    <form action="devices/add-maker" method="post">
        <div class="col-md-6">
            <div  class="form-group">
                <input type="text" name="name" placeholder="Make"  class="form-control"/>
            </div>
            <div class="form-group">
                <label for="scratched-condition">Normal Condition Value (%):</label>
                <input type="text" value="100" id="scratched-condition" name="normal_condition" placeholder="%" class="form-control">
            </div>
            <div class="form-group">
                <label for="scratched-condition">Scratched Condition Value (%):</label>
                <input type="text" id="scratched-condition" name="scratched_condition" placeholder="%" class="form-control">
            </div>
            <div class="form-group">
                <label for="bad-condition">Bad Condition Value (%):</label>
                <input type="text" id="bad-condition" name="bad_condition" placeholder="%" class="form-control">
            </div>
            <div  class="form-group">
                <input type="submit" value="Submit"  class="btn"/>
            </div>
        </div>
    </form>
</div>
</script>
<script id="AddDevice.html" type="text/ng-template">
    <div class="col-md-6">
        <form action="devices/add-device" method="post">
            <div  class="form-group">
                <label>Select Device Make</label>
                <select class="form-control" name="gadget_maker_id">
                    <?php if(isset($models) && is_array($models)){
                        foreach($models as $value){
                            ?> <option value="<?= $value->id ?>"><?= $value->name ?></option>
                    <?php
                        }
                    } ?>
                </select>
            </div>

            <div class="form-group">
                <label>Provide Device Model</label>
                <input type="text" placeholder="e.g Galaxy S5" name="model" class="form-control"/>
            </div>

            <div  class="form-group">
                <label>Add Memory Sizes</label>
                <div class="input-group size-info">
                    <input type="text" name="device-size" ng-model="device_size" class="form-control" placeholder="e.g 16gb">
                    <span class="input-group-btn">
                        <button class="btn btn-default" type="button" ng-click="addToSizes(device_size)">Add</button>
                    </span>
                </div>
                <ul class="size-info list-group" ng-if="sizes.length > 0">
                    <li class="size-info list-group-item" ng-repeat="size in sizes">{{ size }} <span class="btn pull-right btn-xs btn-danger" ng-click="removeSize($index)">X</span></li>
                </ul>

                <textarea style="display: none" name="sizes" ng-model="sizes_string" placeholder="Sizes, seperate each with a comma" class="form-control"></textarea>
            </div>

            <div  class="form-group">
                <label ng-if="sizes.length > 0">Provide Device BaseLine Price</label>
                <table class="size-info table" ng-if="sizes.length > 0">
                    <tr class="size-info" ng-repeat="size in sizes">
                        <td colspan="2">{{ size }}</td>
                        <td>
                          <input type="number"
                                 name="base_line_price"
                                 ng-change="updateBaseLineString()"
                                 ng-model="baseLinePrice[size]"
                                 ng-blur="updateBaseLineString()"
                                 class="form-control"
                                 placeholder="e.g 20000">
                        </td>
                    </tr>
                </table>
                <textarea name="baselines" style="display: none;" placeholder="baseline price eg 16gb: 10000,32gb: 20000" ng-model="baseLinePriceString" class="form-control"></textarea>
            </div>

            <div  class="form-group">
                <input type="submit" value="Submit" class="btn"/>
            </div>

        </form>
    </div>
</script>

<script src="/Assets/app/vendor/angular.min.js"></script>
<script src="/Assets/app/vendor/angular-animate.min.js"></script>
<script src="/Assets/app/vendor/angular-ui-router.min.js"></script>
<script>
    var app = angular.module('AdminApp',[
        'ui.router',
        'ngAnimate'
    ]);

    app.factory('ImageFetcher',function($http){
       var searchUrl = "https://www.googleapis.com/customsearch/v1?key=AIzaSyDa3bBU9KzRRnnj6KHKJlB6Pc1oc9Ivs7Y&cx=011505858740112002603:dap5yb7naau&q=";

        return {
            fetch: function(query){
                return $http.get(searchUrl+encodeURI(query));
            }
        }

    });
    app.config(function ($stateProvider, $urlRouterProvider) {
        //
        // For any unmatched url, redirect to /state1
        $urlRouterProvider.otherwise("/");
        //
        // Now set up the states
        var allDevices = {
            name: 'allDevices',
            url: "/",
            templateUrl: "AllDevices.html",
            controller: function($scope,$http,ImageFetcher){

                ImageFetcher.fetch('Samsung galaxy s5').then(function(response){
                    console.log(response);
                },function(response){
                    console.log(response);
                });

                $scope.deleteItem = function(id){
                    var current = window.location.href,
                    url = window.location.origin +
                          window.location.pathname +
                          '/delete-device/'+id;
                   $http.delete(url).then(function(response){
                       window.location.href = current;
                   },function(response){

                   });
                }
            }
        };

        var allUsers = {
            name: 'allUsers',
            url: "/all-users",
            templateUrl: "AllUsers.html",
            controller: function($scope){

            }
        };

        var allNetworks = {
            name: 'allNetworks',
            url: "/all-networks",
            templateUrl: "AllNetworks.html",
            controller: function($scope){

            }
        };

        var addNetwork = {
            name: 'addNetwork',
            url: "/add-network",
            templateUrl: "AddNetwork.html",
            controller: function($scope){

            }
        };

        var addMaker = {
            name: 'addMaker',
            url: "/add-maker",
            templateUrl: "AddMaker.html",
            controller: function($scope){

            }
        };

        var addDevice = {
            name: 'addDevice',
            url: "/add-device",
            templateUrl: "AddDevice.html",
            controller: function($scope){
                $scope.sizes = [];
                $scope.sizes_string = '';
                $scope.baseLinePrice = {};
                $scope.baseLinePriceString = '';

                function createStringVersion(){
                    $scope.sizes_string =  $scope.sizes.join();
                }

                $scope.addToSizes = function(device_size){
                    $scope.sizes.push(device_size);
                    $scope.device_size = null;
                    createStringVersion();
                };

                $scope.removeSize = function(index){
                    $scope.sizes.splice(index,1);
                    createStringVersion();
                };

                $scope.updateBaseLineString = function(){
                    var temp = [];
                    angular.forEach($scope.baseLinePrice,function(value,key){
                        temp.push(""+key+": "+value);
                    });
                    $scope.baseLinePriceString = temp.join();
                }
            }
        };

        $stateProvider
            .state(allDevices)
            .state(addDevice)
            .state(addMaker)
            .state(allNetworks)
            .state(allUsers)
            .state(addNetwork);

    });
</script>
</html>
