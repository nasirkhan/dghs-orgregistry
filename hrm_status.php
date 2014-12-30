<?php
require_once 'configuration.php';

$org_code = (int) mysql_real_escape_string(trim($_GET['org_code']));
$org_name = getOrgNameFromCode($org_code);
$org_id = getFacilityIdFromCode($org_code);
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
        <link rel="stylesheet" href="library/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="css/main.css">

        <script src="js/vendor/modernizr-2.6.2-respond-1.1.0.min.js"></script>
    </head>
    <body>
        <!--[if lt IE 7]>
            <p class="chromeframe">You are using an <strong>outdated</strong> browser. Please <a href="http://browsehappy.com/">upgrade your browser</a> or <a href="http://www.google.com/chromeframe/?redirect=true">activate Google Chrome Frame</a> to improve your experience.</p>
        <![endif]-->
        <div class="container">

            <!-- Page Header -->
            <?php include_once 'include/header_page_header.php'; ?>


            <div class="navbar navbar-inverse navbar-default">
                <!--<div class="container">-->
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <!--<a class="navbar-brand" href="#">DGHS</a>-->
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php"><i class="fa fa-home fa-lg"></i> Home</a></li>
                        <!--<li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>-->
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
                    <h2><em><?php echo "$org_name";?></em> HRM Status</h2>
                    <blockquote>
                        <p>Go back to <a href="org_profile.php?org_code=<?php echo "$org_code"; ?>">Organization Profile</a></p>
                    </blockquote>

                    <!-- info area
                    ================================================== -->

                    <?php
                    $sql = "SELECT
                                    dghshrml4_sanctionedposts.id AS 'sp_id',
                                    dghshrml4_sanctionedposts.`name` AS 'sp_name',
                                    dghshrml4_sanctionedposts.designation_id AS 'designation_id',
                                    dghshrml4_designations.`name` AS 'designation_name',
                                    dghshrml4_designations.`code` AS 'designation_code',
                                    dghshrml4_designations.`id` AS 'designation_id',
                                    dghshrml4_designations.designationpayscale_name,
                                    dghshrml4_designations.designationclass_name,
                                    dghshrml4_designations.designationdiscipline_name,
                                    dghshrml4_designations.designationgroup_name,
                                    dghshrml4_designations.designationbdprofcategory_name,
                                    dghshrml4_designations.designationwhoprofgroup_name,
                                    dghshrml4_sanctionedposts.sanctionedposttype_name,
                                    dghshrml4_sanctionedposts.sanctionedposttype_id,
                                    count(*) AS sp_count
                            FROM
                                    `dghshrml4_sanctionedposts`
                            LEFT JOIN dghshrml4_designations ON dghshrml4_sanctionedposts.designation_id = dghshrml4_designations.id
                            WHERE
                                    dghshrml4_sanctionedposts.facility_id = $org_id
                            AND dghshrml4_sanctionedposts.is_active = 1
                            GROUP BY
                                    dghshrml4_designations.designationclass_name,
                                    dghshrml4_designations.designationpayscale_name,
                                    dghshrml4_sanctionedposts.sanctionedposttype_name,
                                    dghshrml4_sanctionedposts.designation_id
                            ORDER BY
                                    dghshrml4_designations.rank";
                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>sql:2</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
                    $total_sanctioned_post = mysql_num_rows($result);


                    $total_sanctioned_post_count_sum = 0;
                    $total_sanctioned_post_existing_sum = 0;
                    $total_existing_male_sum = 0;
                    $total_existing_female_sum = 0;
                    ?>
                    <table class="table table-striped table-bordered">
                        <thead>
                            <tr>
                                <th>Designation</th>
                                <th>Type of post</th>
                                <th>Class</th>
                                <th>Pay Scale</th>
                                <th>Total Sanctioned Post(s)</th>
                                <th>Filled up Post(s)</th>
                                <th>Total Male</th>
                                <th>Total Female</th>
                                <th>Vacant Post(s)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            while ($row = mysql_fetch_assoc($result)) :
                                $sql = "SELECT
                                                dghshrml4_sanctionedposts.id AS 'sp_id',
                                                dghshrml4_sanctionedposts.`name` AS 'sp_name',
                                                dghshrml4_sanctionedposts.designation_id AS 'designation_id',
                                                count(*) AS existing_total_count
                                        FROM
                                                `dghshrml4_sanctionedposts`
                                        LEFT JOIN dghshrml4_providers ON dghshrml4_sanctionedposts.id = dghshrml4_providers.sanctionedpost_id
                                        WHERE
                                                dghshrml4_sanctionedposts.facility_id = $org_id
                                        AND dghshrml4_sanctionedposts.is_active = 1
                                        AND dghshrml4_sanctionedposts.designation_id = " . $row['designation_id'] . "
                                        AND dghshrml4_sanctionedposts.sanctionedposttype_id = " . $row['sanctionedposttype_id'] . "
                                        and dghshrml4_providers.sanctionedpost_id > 0";
                                $r = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>sql:2</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
                                $a = mysql_fetch_assoc($r);
                                $existing_total_count = $a['existing_total_count'];
                                
                                $sql = "SELECT
                                                dghshrml4_sanctionedposts.id AS 'sp_id',
                                                dghshrml4_sanctionedposts.`name` AS 'sp_name',
                                                dghshrml4_sanctionedposts.designation_id AS 'designation_id',
                                                count(*) AS existing_male_count
                                        FROM
                                                `dghshrml4_sanctionedposts`
                                        LEFT JOIN dghshrml4_providers ON dghshrml4_sanctionedposts.id = dghshrml4_providers.sanctionedpost_id
                                        WHERE
                                                dghshrml4_sanctionedposts.facility_id = $org_id
                                        AND dghshrml4_sanctionedposts.is_active = 1
                                        AND dghshrml4_sanctionedposts.designation_id = " . $row['designation_id'] . "
                                        AND dghshrml4_sanctionedposts.sanctionedposttype_id = " . $row['sanctionedposttype_id'] . "    
                                        and dghshrml4_providers.sex_id = 1
                                        and dghshrml4_providers.sanctionedpost_id > 0";
                                $r = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>sql:2</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
                                $a = mysql_fetch_assoc($r);
                                $existing_male_count = $a['existing_male_count'];

                                $existing_female_count = $existing_total_count - $existing_male_count;
                                $total_sanctioned_post_count_sum += $row['sp_count'];
                                $total_sanctioned_post_existing_sum += $existing_total_count;
                                $total_existing_male_sum += $existing_male_count;
                                $total_existing_female_sum += $existing_female_count;
                                ?>
                                <tr>
                                    <td>
                                        <?php echo $row['designation_name']; ?>
                                    </td>
                                    <td><?php echo $row['sanctionedposttype_name']; ?></td>
                                    <td><?php echo $row['designationclass_name']; ?></td>
                                    <td><?php echo $row['designationpayscale_name']; ?></td>
                                    <td><?php echo $row['sp_count']; ?></td>
                                    <td><?php echo $existing_total_count; ?></td>
                                    <td><?php echo $existing_male_count; ?></td>
                                    <td><?php echo $existing_female_count; ?></td>
                                    <td><?php echo $row['sp_count'] - $existing_total_count; ?></td>
                                </tr>
                            <?php endwhile; ?>
                            <tr class="warning">
                                <td colspan="4"><strong>Summary</strong></td>
                                <td><strong><?php echo $total_sanctioned_post_count_sum; ?></strong></td>
                                <td><strong><?php echo $total_sanctioned_post_existing_sum; ?></strong></td>
                                <td><strong><?php echo $total_existing_male_sum; ?></strong></td>
                                <td><strong><?php echo $total_existing_female_sum; ?></strong></td>
                                <td><strong><?php echo $total_sanctioned_post_count_sum - $total_sanctioned_post_existing_sum; ?></string></td>
                            </tr>
                        </tbody>
                    </table>

                </div>

            </div>

            <hr>

            <footer>
                <p>
                    <!-- Copyright info -->
                    <?php include_once 'include/footer_copyright_info.php'; ?>
                </p>
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

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php'; ?>
    </body>
</html>
