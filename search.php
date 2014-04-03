<?php
require_once 'configuration.php';

$query = mysql_real_escape_string(trim($_REQUEST['query']));

if (isset($_REQUEST['query']) && count($_REQUEST['query']) > 0 && $query != "") {
    $sql = "SELECT
                organization.org_name,
                organization.org_code,
                organization.org_type_code,
                org_type.org_type_name,
                organization.division_code,
                organization.division_name,
                organization.district_code,
                organization.district_name,
                organization.upazila_thana_code,
                organization.upazila_thana_name,
                organization.email_address1,
                organization.mobile_number1,
                organization.land_phone1,
                organization.org_photo
            FROM
                    organization
            LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
            LEFT JOIN admin_division ON admin_division.division_bbs_code = organization.division_code
        WHERE
                organization.org_code = \"$query\"
        OR organization.org_name LIKE \"%$query%\"
        OR organization.division_name LIKE \"%$query%\"
        OR organization.district_name LIKE \"%$query%\"
        OR organization.upazila_thana_name LIKE \"%$query%\"    
        ORDER BY
                organization.division_name,
                organization.district_name,
                organization.upazila_thana_name";
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
        <link rel="stylesheet" href="library/font-awesome/css/font-awesome.min.css">
        <link rel="stylesheet" href="library/slimbox-2.05/css/slimbox2.css">
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
                <div class="navbar-header visible-xs">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                    <a class="navbar-brand" href="#">DGHS</a>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li><a href="index.php"><i class="fa fa-home fa-lg"></i> Home</a></li>
                        <li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
                        <li><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Reports</a></li>
                        <li class="active"><a href="search.php"><i class="fa fa-search fa-lg"></i> Search</a></li>
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
                    <h2>Search Organization Registry</h2>
                    <?php if ($showReportTable): ?>                 
                        <div class="alert alert-info" id="info-area">
                            <p class="lead">
                                <i class="fa fa-search"></i><em>Search Keyword :</em> <strong><?php echo "$query";?></strong>
                                &nbsp;<br />
                            </p>
                            <blockquote>
                                Total <em><strong><?php echo mysql_num_rows($result); ?></strong></em> organization(s) found.
                            </blockquote>
                        </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><strong>#</strong></td>
                                    <td><strong>Organization Name (Code)</strong></td>
                                    <td><strong>Org Type</strong></td>
                                    <td><strong>Division Name</strong></td>
                                    <td><strong>District Name</strong></td>
                                    <td><strong>Upazila Name</strong></td>
                                    <td><strong>Email</strong></td>
                                    <td><strong>Phone / Mobile</strong></td>
                                    <td><strong>Photo</strong></td>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $i = 0; ?>
                                <?php while ($row = mysql_fetch_assoc($result)): ?>
                                    <?php $i++; ?>
                                    <tr>
                                        <td><?php echo $i; ?></td>
                                        <td><a href="org_profile.php?org_code=<?php echo $row['org_code']; ?>" target="_blank"><?php echo $row['org_name']; ?> (Code:<?php echo $row['org_code']; ?>)</a></td>
                                        <td><?php echo $row['org_type_name']; ?></td>
                                        <td><?php echo $row['division_name']; ?></td>
                                        <td><?php echo $row['district_name']; ?></td>
                                        <td><?php echo $row['upazila_thana_name']; ?></td>
                                        <td><?php echo $row['email_address1']; ?></td>
                                        <td><?php echo $row['land_phone1']; ?> / <?php echo $row['mobile_number1']; ?></td>
                                        <td>
                                            <?php if ($row['org_photo'] != ""): ?>
                                            <a href="<?php echo $hrm_root_dir; ?>/uploads/<?php echo $row['org_photo']; ?>" rel="lightbox" title="<?php echo $row['org_name']; ?>"><i class="fa fa-picture-o fa-lg"></i> </a>
                                            <?php endif; ?>
                                        </td>
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
                <p>
                    <!-- Copyright info -->
                    <?php include_once 'include/footer_copyright_info.php';?>
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
        
        <script src="library/slimbox-2.05/js/slimbox2.js"></script>        

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php';?>
    </body>
</html>

