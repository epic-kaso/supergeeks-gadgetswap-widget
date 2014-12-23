<!DOCTYPE html>
<html class="no-js" ng-app="SupergeeksWidget">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css">
    <link href="//maxcdn.bootstrapcdn.com/font-awesome/4.2.0/css/font-awesome.min.css" rel="stylesheet">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans:400,600,300,700' rel='stylesheet' type='text/css'>
    <link rel="stylesheet" href="/Assets/css/main.css">
    <script>
        window.gadget_swap = {};
        window.gadget_swap.data = <?= json_encode($objects) ?>;
    </script>
</head>
<body>
<div class="navbar navbar-plain">
    <div class="container-fluid">
        <div class="navbar-header">
            <img src="http://supergeeks.com.ng/img/supergeeks-logo-tagline.png" class="img-responsive"/>
        </div>
        <div class="info-header">
            <div class="tiny-heading text-muted"><h4 ng-bind="tiny_heading"></h4></div>
            <div class="big-heading"><h1 ng-bind="big_heading"></h1></div>
        </div>
    </div>
</div>

<div class="container">
    <div class="row">
        <div class="col-sm-12 col-lg-offset-2 col-lg-8">
            <div class="widget-canvas">
                <div ui-view class="v">
                    <div class="" style="
                        padding-top: 50px;
                    ">
                        <img src="/Assets/img/loading.gif" class="img-responsive" style="
                            margin-left: auto;
                            margin-right: auto;
                        ">
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="navbar navbar-default navbar-fixed-bottom" ng-controller="NavbarController">
    <div class="progress-bar" progress-bar val="progress"></div>
    <div class="container-fluid">
        <div class="progress-item back-button">
            <span class="glyphicon glyphicon-chevron-left back-button"></span>
        </div>
        <div class="progress-item">
            <a
                class="btn"
                ui-sref="device-make"
                ui-sref-active="active">
                <p>Device</p>
                <span ng-bind="currentGadget.make"></span>
            </a>
        </div>

        <div class="progress-item">
            <a
                class="btn"
                ng-class="{disabled: !currentGadget.make}"
                ui-sref="device-model({device_make: currentGadget.make})"
                ui-sref-active="active">
                <p>Model</p>
                <span ng-bind="currentGadget.model.model"></span>
            </a>
        </div>

        <div class="progress-item">
            <a
                class="btn"
                ui-sref="device-size({device_make: currentGadget.make,device_model: currentGadget.model.slug})"
                ng-class="{disabled: !currentGadget.make || !currentGadget.model.slug}"
                ui-sref-active="active">
                <p>Size</p>
                <span ng-bind="currentGadget.size.value"></span>
            </a>
        </div>

        <div class="progress-item">
            <a
                class="btn"
                ui-sref="device-network({device_make: currentGadget.make,device_model: currentGadget.model.slug,device_size: currentGadget.size.slug})"
                ng-class="{disabled: !currentGadget.make || !currentGadget.model.slug || !currentGadget.size.slug}"
                ui-sref-active="active">
                <p>Network</p>
                <span ng-bind="currentGadget.network.name"></span>
            </a>
        </div>

        <div class="progress-item">
            <a
                class="btn"
                ng-class="{disabled: !currentGadget.make || !currentGadget.model.slug || !currentGadget.size.slug || !currentGadget.network.name}"
                ui-sref="device-condition({device_make: currentGadget.make,device_model: currentGadget.model.slug,device_size: currentGadget.size.slug,device_network: currentGadget.network.slug})" ui-sref-active="active">
                <p>Condition</p>
                <span ng-bind="currentGadget.condition" ></span>
            </a>
        </div>
    </div>
</div>
<script src="/Assets/app/models/Gadget.js"></script>

<script src="/Assets/app/vendor/angular.min.js"></script>
<script src="/Assets/app/vendor/angular-cookies.min.js"></script>
<script src="/Assets/app/vendor/angular-animate.min.js"></script>
<script src="/Assets/app/vendor/angular-ui-router.min.js"></script>
<script src="/Assets/app/vendor/ui-bootstrap.js"></script>

<script src="/Assets/app/controllers/Controllers.js"></script>
<script src="/Assets/app/services/Services.js"></script>
<script src="/Assets/app/Directives/directives.js"></script>

<script src="/Assets/app/start.js"></script>
</body>
</html>
