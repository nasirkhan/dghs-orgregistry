<?php
require_once 'configuration.php';
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
                        <li class="active"><a href="index.php">Home</a></li>
                        <li><a href="#">Apply for registration</a></li>
                        <li><a href="report.php">Report Dashboard</a></li>
                        <li><a href="search.php">Search</a></li>
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
                <div class="col-md-3">
                    <div class="well sidebar-nav">

                        <div id="org_list"  style="min-height:300px;">
                            <ul>
                                <li id="tree_root">
                                    <a href="#">Bangladesh</a>
                                    <ul>
                                        <?php
                                        $sql = "SELECT
                                                    admin_division.division_name,
                                                    admin_division.division_bbs_code
                                                FROM
                                                    `admin_division`
                                                WHERE
                                                    admin_division.division_active LIKE 1";
                                        $div_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:1 || Query:</b><br />___<br />$sql</p>");
                                        while ($div_data = mysql_fetch_assoc($div_result)): ?>
                                        <li id="div_<?php echo $div_data['division_bbs_code']; ?>">
                                            <a href="#"><?php echo $div_data['division_name']; ?></a>
                                            <ul>
                                                <?php
                                                $sql = "SELECT
                                                            district_name,
                                                            district_bbs_code
                                                        FROM
                                                            `admin_district`
                                                        WHERE
                                                            division_bbs_code = " . $div_data['division_bbs_code'] . "
                                                        AND active LIKE 1";
                                                $dis_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:2 || Query:</b><br />___<br />$sql</p>");
                                                while ($dis_data = mysql_fetch_assoc($dis_result)): ?>
                                                <li id="div_<?php echo $dis_data['district_bbs_code']; ?>">
                                                    <a href="#"><?php echo $dis_data['district_name']; ?></a>
                                                    <ul>
                                                    <?php
                                                    $sql = "SELECT
                                                        upazila_name,
                                                        upazila_bbs_code
                                                    FROM
                                                        `admin_upazila`
                                                    WHERE
                                                        upazila_district_code = " . $dis_data['district_bbs_code'] . "
                                                    AND upazila_active LIKE 1;";
                                                    $uni_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:3 || Query:</b><br />___<br />$sql</p>");
                                                    while ($uni_data = mysql_fetch_assoc($uni_result)): ?>
                                                    <li id="div_<?php echo $uni_data['upazila_bbs_code']; ?>">
                                                        <a href="#"><?php echo $uni_data['upazila_name']; ?></a>
                                                    </li>
                                                    <?php endwhile; ?>
                                                </ul>
                                                </li>
                                                <?php endwhile; ?>
                                            </ul>
                                        </li>
                                        <?php endwhile; ?>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </div><!--/.well -->
                </div>
                <div class="col-md-9">
                    <h2>Heading</h2>
                    
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
