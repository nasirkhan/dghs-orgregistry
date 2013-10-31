<?php
require_once 'configuration.php';

$query = mysql_real_escape_string(trim($_REQUEST['query']));

if (isset($_REQUEST['query']) && count($_REQUEST['query']) > 0) {
    $sql = "SELECT
                organization.org_name,
                organization.org_code,
                organization.org_type_code,
                org_type.org_type_name,
                organization.division_code,
                organization.district_code,
                organization.upazila_thana_code,
                organization.email_address1,
                organization.mobile_number1
            FROM
                    organization
            LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
            LEFT JOIN admin_division ON admin_division.division_bbs_code = organization.division_code
        WHERE
                organization.org_code = \"$query\"
        OR organization.org_name LIKE \"%$query%\"
        ORDER BY
                org_name";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:orgSearch || Query:</b><br />___<br />$sql</p>");


    if (mysql_num_rows($result) > 0) {
        $showReportTable = TRUE;
    }
}
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
                        <li><a href="#">Home</a></li>
                        <li><a href="#">Apply for registration</a></li>
                        <li><a href="report.php">Report</a></li>
                        <li class="active"><a href="search.php">Search</a></li>
                    </ul>
                    <form name="search-form" class="navbar-form navbar-right" action="search.php" method="post">
                        <div class="form-group">
                            <input name="query" type="text" placeholder="Enter keyward" class="form-control">
                        </div>                       
                        <button type="submit" class="btn btn-success">Search</button>
                    </form>
                </div><!--/.navbar-collapse -->
                <!--</div>-->
            </div>
        </div>

        <div class="container">
            <!-- Example row of columns -->
            <div class="row">
                <div class="col-md-12">
                    <h2>Search Organization Registry</h2>
                    <?php if ($showReportTable): ?>                 
                        <div class="alert alert-info">
                            Total <em><strong><?php echo mysql_num_rows($result); ?></strong></em> organization(s) found.
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><strong>#</strong></td>
                                    <td><strong>Organization Name</strong></td>
                                    <td><strong>Organization Code</strong></td>
                                    <td><strong>Org Type</strong></td>
                                    <td><strong>Division Name</strong></td>
                                    <td><strong>District Name</strong></td>
                                    <td><strong>Email</strong></td>
                                    <td><strong>Phone</strong></td>
                                    <!--<td><strong>Upazila Name</strong></td>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php while ($row = mysql_fetch_assoc($result)): ?>
                                    <?php $i++; ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><?php echo $row['org_name']; ?></td>
                                        <td><?php echo $row['org_code']; ?></td>
                                        <td><?php echo $row['org_type_name']; ?></td>
                                        <td><?php echo getDivisionNameFromCode($row['division_code']); ?></td>
                                        <td><?php echo getDistrictNameFromCode($row['district_code']); ?></td>
                                        <td><?php echo $row['email_address1']; ?></td>
                                        <td><?php echo $row['mobile_number1']; ?></td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    <?php elseif ($query == ""): ?>
                        <div class="row">
                            <div class="col-md-12">
                                You can search <em>Organization Registry</em> by using the organization name or organization code. Use the following search box. 
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-offset-4 col-md-4">
                                <form name="search-form" class="navbar-form navbar-right" action="search.php" method="post">
                                    <div class="form-group">
                                        <input name="query" type="text" placeholder="Enter keyward" class="form-control input-lg">
                                    </div>                       
                                    <button type="submit" class="btn btn-success btn-lg">Search</button>
                                </form>
                            </div>

                        </div>


                    <?php else: ?>
                        <div class="alert alert-warning">
                            No result found.<br />
                            Search query was <em><strong><?php echo $query; ?></strong></em>.
                            <br /><br />
                            <?php // echo $sql; ?>
                        </div>
                        <div class="row">

                            <div class="col-md-12">
                                Use another keyword and search again.
                            </div>
                        </div>
                        <div class="row">

                            <div class="col-md-offset-4 col-md-4">
                                <form name="search-form" class="navbar-form navbar-right" action="search.php" method="post">
                                    <div class="form-group">
                                        <input name="query" type="text" placeholder="Enter keyward" class="form-control input-lg">
                                    </div>                       
                                    <button type="submit" class="btn btn-success btn-lg">Search</button>
                                </form>
                            </div>

                        </div>


                    <?php endif; ?>


                </div>

            </div>

            <hr>

            <footer>
                <p>&copy; Company 2013</p>
            </footer>
        </div> <!-- /container -->        


        <!-- Bootstrap core JavaScript
        ================================================== -->

<!--        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
<script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>-->

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
