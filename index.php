<?php
require_once 'configuration.php';

$level = mysql_real_escape_string(trim($_GET['level']));
$code = (int) mysql_real_escape_string(trim($_GET['code']));
$dis_code = (int) mysql_real_escape_string(trim($_GET['dis_code']));

if (isset($_GET['level']) && isset($_GET['code'])) {
    if ($level == "div") {
        $division_name = getDivisionNameFromCode($code);
        $sql = "SELECT
                    organization.org_name,
                    organization.org_code,
                    organization.org_type_code,
                    org_type.org_type_name,
                    org_level.org_level_name,
                    organization.org_level_code
                FROM
                    `organization`
                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
                WHERE
                    organization.division_code = $code
                AND organization.active LIKE 1";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        if (mysql_num_rows($result) > 0) {
            $showReportTable = TRUE;
        }
    } else if ($level == "dis") {
        $division_name = getDivisionNameFromDistrictCode($code);
        $division_code = getDivisionCodeFromDistrictCode($code);
        $district_name = getDistrictNameFromCode($code);
        $sql = "SELECT
                        organization.org_code,
                        organization.org_name,
                        organization.org_type_code,
                        org_type.org_type_name,	
                        organization.org_level_code,
                        org_level.org_level_name
                FROM
                        `organization`
                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
                WHERE
                        organization.district_code = $code
                AND organization.active LIKE 1";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        if (mysql_num_rows($result) > 0) {
            $showReportTable = TRUE;
        }
    } else if ($level == "upa") {
        
        $upa_info = getDisDivNameCodeFromUpazilaAndDistrictCode($code, $dis_code);
        $division_name = $upa_info['district_name'];
        $division_code = $upa_info['upazila_division_code'];
        $district_name = $upa_info['division_name'];
        $district_code = $upa_info['upazila_district_code'];
        $upazila_name = $upa_info['upazila_name'];
        
        $sql = "SELECT
                    organization.org_code,
                    organization.org_name,
                    organization.org_type_code,
                    org_type.org_type_name,	
                    organization.org_level_code,
                    org_level.org_level_name
                FROM
                    `organization`
                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
                WHERE
                    organization.upazila_thana_code = $code
                AND organization.district_code = $dis_code            
                AND organization.active LIKE 1";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        if (mysql_num_rows($result) > 0) {
            $showReportTable = TRUE;
        }
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
                <div class="col-md-3">
                    <div class="well sidebar-nav">

                        <div id="org_list" style="min-height:300px;">
                            <ul>
                                <li id="tree_root">
                                    <a href="#" onclick="window.open('org_list.php?level=country', '_self');">Bangladesh</a>
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
                                        while ($div_data = mysql_fetch_assoc($div_result)):
                                            ?>
                                            <li id="div_<?php echo $div_data['division_bbs_code']; ?>">
                                                <a href="#" onclick="window.open('org_list.php?level=div&code=<?php echo $div_data['division_bbs_code']; ?>', '_self');"><?php echo $div_data['division_name']; ?></a>
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
                                                    while ($dis_data = mysql_fetch_assoc($dis_result)):
                                                        ?>
                                                        <li id="div_<?php echo $dis_data['district_bbs_code']; ?>">
                                                            <a href="#" onclick="window.open('org_list.php?level=dis&code=<?php echo $dis_data['district_bbs_code']; ?>', '_self');"><?php echo $dis_data['district_name']; ?></a>
                                                            <ul>
                                                                <?php
                                                                $sql = "SELECT
                                                                            upazila_name,
                                                                            upazila_bbs_code,
                                                                            upazila_district_code
                                                                        FROM
                                                                            `admin_upazila`
                                                                        WHERE
                                                                            upazila_district_code = " . $dis_data['district_bbs_code'] . "
                                                                        AND upazila_active LIKE 1;";
                                                                $uni_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:3 || Query:</b><br />___<br />$sql</p>");
                                                                while ($uni_data = mysql_fetch_assoc($uni_result)):
                                                                    ?>
                                                                    <li id="upa_<?php echo $uni_data['upazila_bbs_code']; ?>">
                                                                        <a href="#" onclick="window.open('org_list.php?level=upa&code=<?php echo $uni_data['upazila_bbs_code']; ?>&dis_code=<?php echo $uni_data['upazila_district_code']; ?>', '_self');"><?php echo $uni_data['upazila_name']; ?></a>
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
                    <?php if ($showReportTable): ?>
                        <ol class="breadcrumb">
                            <li>Bangladesh</li>
                            <?php if ($level == "div"): ?>
                                <li class="active"><?php echo $division_name; ?></li>
                            <?php endif; ?>
                            <?php if ($level == "dis"): ?>
                                <li><a href="org_list.php?level=div&code=<?php echo $division_code; ?>"><?php echo $division_name; ?></a></li>
                                <li class="active"><?php echo $district_name; ?></li>
                            <?php endif; ?>
                            <?php if ($level == "upa"): ?>
                                <li><a href="org_list.php?level=div&code=<?php echo $division_code; ?>"><?php echo $division_name; ?></a></li>
                                <li><a href="org_list.php?level=dis&code=<?php echo $district_code; ?>"><?php echo $district_name; ?></a></li>
                                <li class="active"><?php echo $upazila_name; ?></li>
                            <?php endif; ?>    
                        </ol>
                        <!--<h2><?php echo $division_name; ?></h2>-->
                    <div class="alert alert-info">
                        All Organizations under 
                        <em>
                            <?php if ($level == "div") { echo "<strong>" . $division_name . "</strong> division"; } ?>
                            <?php if ($level == "dis") { echo "<strong>" . $division_name . "</strong> division" . " under " . "<strong>" .  $district_name . "</strong> district"; } ?>
                            <?php if ($level == "upa") { 
                                echo "<strong>" . $division_name . "</strong> division";
                                echo " under " . "<strong>" .  $district_name . "</strong> district"; 
                                echo " under " . "<strong>" .  $district_name . "</strong> district"; 
                            } ?>
                        </em> <br />
                        Total <em><strong><?php echo mysql_num_rows($result); ?></strong></em> organization(s) found.
                    </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><strong>#</strong></td>
                                    <td><strong>Organization Name</strong></td>
                                    <td><strong>Organization Code</strong></td>
                                    <td><strong>Org Type</strong></td>
                                    <td><strong>Org Level</strong></td>
                                    <!-- 
                                    <td></td>
                                    <td></td>
                                    -->
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
                                        <td><?php echo $row['org_level_name']; ?></td>
                                        
                                        <!-- 
                                        <td></td>
                                        <td></td>
                                        -->
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>

                    <?php endif; ?>


                </div>

            </div>

            <hr>

            <footer>
                <p>&copy; MIS, DGHS 2013</p>
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

        <script type="text/javascript">
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
