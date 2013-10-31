<?php
require_once 'configuration.php';

if (isset($_GET['org_code']) && $_GET['org_code'] != "") {
    $org_code = (int) mysql_real_escape_string($_GET['org_code']);
    $org_name = getOrgNameFormOrgCode($org_code);
    $org_type_name = getOrgTypeNameFormOrgCode($org_code);
    $echoAdminInfo = " | Administrator";
    $isAdmin = TRUE;
}

$sql = "SELECT * FROM organization WHERE  org_code =$org_code LIMIT 1";
$result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>sql:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

// data fetched form organization table
$data = mysql_fetch_assoc($result);


/**
 * check if the organization type is Community clinic or not
 */
$org_type_code = getOrgTypeCodeFromOrgCode($org_code);
// Community Clinic type code is 1039
if ($org_type_code == 1039) {
    $isCommunityClinic = TRUE;
} else {
    $isCommunityClinic = FALSE;
}

$showSanctionedBed = showSanctionedBed($org_type_code);
?>
<!DOCTYPE html>
<!--[if lt IE 7]>      <html class="no-js lt-ie9 lt-ie8 lt-ie7"> <![endif]-->
<!--[if IE 7]>         <html class="no-js lt-ie9 lt-ie8"> <![endif]-->
<!--[if IE 8]>         <html class="no-js lt-ie9"> <![endif]-->
<!--[if gt IE 8]><!--> <html class="no-js"  lang="en"> <!--<![endif]-->
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
        <title>Organization Registry</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width">

        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="library/font-awesome-4.0.1/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="container">

            <!-- Main jumbotron for a primary marketing message or call to action -->
            <div class="jumbotron">
                <h1>Organization Registry</h1>
                <p>
                    Government of People's Republic of Bangladesh <br />
                    Directorate General of Health Services
                </p>
            </div>
            <div class="navbar navbar-inverse navbar-default">
                <!--<div class="container">-->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">DGHS</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php"><i class="fa fa-home fa-lg"></i> Home</a></li>
                        <li><a href="#"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
                        <li><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Report Dashboard</a></li>
                        <li><a href="search.php"><i class="fa fa-search fa-lg"></i> Search</a></li>
                    </ul>
                    <form name="search-form" class="navbar-form navbar-right" action="search.php" method="post">
                        <div class="form-group">
                            <input name="query" type="text" placeholder="Enter keyward" class="form-control">
                        </div>                       
                        <button type="submit" class="btn btn-success"><i class="fa fa-search"></i> Search</button>
                    </form>
                </div><!--/.navbar-collapse -->
                <!--</div>-->
            </div>
        </div>

        <div class="container">
            <!-- Example row of columns -->
            <div class="row">

                <div class="col-md-12">
                    <!--<h2><?php echo "$org_name"; ?></h2>-->
                    <div class="page-header">
                        <h1><?php echo "$org_name"; ?></h1>
                        <h3><?php echo "$org_type_name ($org_type_code)"; ?></h3>
                    </div>

                    <!-- Nav tabs -->


                    <ul class="nav nav-tabs nav-tab-ul">
                        <li class="active">
                            <a href="#basic-info" data-toggle="tab"><i class="fa fa-hospital"></i> Basic Information</a>
                        </li>
                        <li class="">
                            <a href="#ownership-info" data-toggle="tab"><i class="fa fa-building"></i> Ownership Info</a>
                        </li>
                        <li class="">
                            <a href="#contact-info" data-toggle="tab"><i class="fa fa-envelope"></i> Contact Info</a>
                        </li>
                        <li class="">
                            <a href="#land-info" data-toggle="tab"><i class="fa fa-th-list"></i> Land Info</a>
                        </li>
                        <li class="">
                            <a href="#permission_approval-info" data-toggle="tab"><i class="fa fa-qrcode"></i> Permission/Approval Info</a>
                        </li>                            
                        <li class="">
                            <a href="#facility-info" data-toggle="tab"><i class="fa fa-shield"></i> Facility Info</a>
                        </li>
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="basic-info">
                            x  
                        </div>
                        <div class="tab-pane" id="ownership-info">
                            a
                        </div>
                        <div class="tab-pane" id="permission_approval-info">
                            b
                        </div>
                        <div class="tab-pane" id="contact-info">
                            c
                        </div>
                        <div class="tab-pane" id="facility-info">
                            <table class="table table-striped table-hover table-bordered">
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Source of Electricity</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Main source of electricity</td>
                                    <td><?php echo getElectricityMainSourceNameFromCode($data['source_of_electricity_main_code']); ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Alternate source of electricity</td>
                                    <td><?php echo getElectricityAlterSourceNameFromCode($data['source_of_electricity_alternate_code']); ?></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Source of water Supply</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Main water supply</td>
                                    <td><?php echo getWaterMainSourceNameFromCode($data['source_of_water_supply_main_code']); ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Alternate water supply</td>
                                    <td><?php echo getWaterAlterSourceNameFromCode($data['source_of_water_supply_alternate_code']); ?></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Toilet Facility</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Toilet type</td>
                                    <td><?php echo getToiletTypeNameFromCode($data['toilet_type_code']); ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Toilet adequacy</td>
                                    <td><?php echo getToiletAdequacyNameFromCode($data['toilet_adequacy_code']); ?></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Fuel Source</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Fuel source</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Laundry System</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Laundry system</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Autoclave System</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Autoclave System</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Waste Disposal System</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Waste disposal</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Sanctioned Assets</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Sanctioned Office equipment</td>
                                    <td><?php echo $data['sanctioned_office_equipment']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Sanctioned vehicles</td>
                                    <td><?php echo $data['sanctioned_vehicles']; ?></td>
                                </tr>
                                <?php if ($showSanctionedBed): ?>
                                    <tr>
                                        <td width="50%">Sanctioned Bed No</td>
                                        <td><?php echo $data['sanctioned_bed_number']; ?></td>
                                    </tr>
                                <?php endif; ?>
                                <tr>
                                    <td width="50%">Other miscellaneous issues</td>
                                    <td><?php echo $data['other_miscellaneous_issues']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane" id="land-info">
                            <table class="table table-striped table-hover table-bordered">
                                <!--
                                <tr>
                                    <td width="50%"><strong>Land Information</strong></td>
                                    <td><?php echo $data['land_info_code']; ?></td>
                                </tr>
                                -->
                                <tr>
                                    <td width="50%"><strong>Land size (in decimal)</strong></td>
                                    <td><?php echo $data['land_size']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mouza name</strong></td>
                                    <td><?php echo $data['land_mouza_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Geocode of Mouza</strong></td>
                                    <td><?php echo $data['land_mouza_geo_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>JL No.</strong></td>
                                    <td><?php echo $data['land_jl_number']; ?></td>
                                </tr>
                                <!--
                                <tr>
                                    <td width="50%"><strong>Functional Code</strong></td>
                                    <td><?php echo $data['land_functional_code']; ?></td>
                                </tr>
                                -->

                                <tr>
                                    <td width="50%"><strong>SA Dag No</strong></td>
                                    <td><?php echo $data['land_ss_dag_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>RS Dag No</strong></td>
                                    <td><?php echo $data['land_rs_dag_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Khatian No.</strong></td>
                                    <td><?php echo $data['land_kharian_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mutation No.</strong></td>
                                    <td><?php echo $data['land_other_info']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Other land information.</strong></td>
                                    <td><?php echo $data['land_mutation_number']; ?></td>
                                </tr>
                            </table>

                        </div>
                    </div>
                </div>

            </div>

            <hr>

            <footer>
                <p>&copy; Company 2013</p>
            </footer>
        </div> <!-- /container -->        


        <!-- Bootstrap core JavaScript
        ================================================== -->

        <!--        
        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>
        -->

        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.js"></script>
        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.cookie.js"></script>
        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.jstree.js"></script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script type="text/javascript" class="source below">
            $(function() {
                $("#org_list").jstree({
                    "plugins": ["themes", "html_data", "ui", "crrm", "hotkeys"],
                    "core": {
                        "animation": 100
                    },
                    "themes": {
                        "theme": "proton"
                    },
                })
            });
        </script>
        <script>
            $(function() {
                $('.nav-tab-ul #basic-info').tab('show');
            });
        </script>
        <!--
        <script>
            var _gaq = [['_setAccount', 'UA-XXXXX-X'], ['_trackPageview']];
            (function(d, t) {
                var g = d.createElement(t), s = d.getElementsByTagName(t)[0];
                g.src = '//www.google-analytics.com/ga.js';
                s.parentNode.insertBefore(g, s)
            }(document, 'script'));
        </script>
        -->
    </body>
</html>
