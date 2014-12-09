<?php
require_once 'configuration.php';

$level = mysql_real_escape_string(trim($_GET['level']));
$id = (int) mysql_real_escape_string(trim($_GET['id']));


if (isset($_GET['level']) && isset($_GET['id'])) {
    if ($level == "div") {
        $division_name = getDivisionNameFromId($id);
        $sql = "SELECT
                        *
                FROM
                        `dghshrml4_facilities`
                WHERE
                        division_id = '$id'
                AND is_active = 1";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        
//        $code_array = array ('code' => $code);
//        $total_result_count = getTotalOrgListCount($level, $code_array);
        $total_result_count = mysql_num_rows($result);
        
        
        if (mysql_num_rows($result) > 0) {
            $showReportTable = TRUE;
        }
    } else if ($level == "dis") {
        $division_id = getDivisionIdFromDistrictId($id); 
        $division_name = getLocationNameFromId($division_id, 'divisions');
        $district_name = getLocationNameFromId($id, 'districts');
        $sql = "SELECT
                        *
                FROM
                        `dghshrml4_facilities`
                WHERE
                        district_id = '$id'
                AND is_active = 1";
//        $sql = "SELECT
//                        organization.org_code,
//                        organization.org_name,
//                        organization.org_type_code,
//                        org_type.org_type_name,	
//                        organization.org_level_code,
//                        org_level.org_level_name,
//                        organization.org_photo,
//                        organization.email_address1,
//                        organization.mobile_number1,
//                        organization.land_phone1
//                FROM
//                        `organization`
//                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
//                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
//                WHERE
//                        organization.district_code = $code
//                AND organization.active LIKE 1 ";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        
//        $code_array = array ('code' => $code);
//        $total_result_count = getTotalOrgListCount($level, $code_array);
        
        $total_result_count = mysql_num_rows($result);
        
        if (mysql_num_rows($result) > 0) {
            $showReportTable = TRUE;
        }
    } else if ($level == "upa") {
        
        $upazila_name = getLocationNameFromId($id, 'upazilas');
        $district_id = getDistrictIdFromUpazilaId($id);
        $district_name = getLocationNameFromId($district_id, 'districts');        
        $division_id = getDivisionIdFromDistrictId($district_id); 
        $division_name = getLocationNameFromId($division_id, 'divisions');
        
//        $district_name = getLocationNameFromId($id, 'districts');
        
//        $upa_info = getDisDivNameCodeFromUpazilaAndDistrictCode($code, $dis_code);
//        $division_name = $upa_info['district_name'];
//        $division_code = $upa_info['upazila_division_code'];
//        $district_name = $upa_info['division_name'];
//        $district_code = $upa_info['upazila_district_code'];
//        $upazila_name = $upa_info['upazila_name'];
        
        $sql = "SELECT
                        *
                FROM
                        `dghshrml4_facilities`
                WHERE
                        upazila_id =  '$id'
                AND is_active = 1";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        
//        $code_array = array ('code' => $code, 'dis_code' => $dis_code);
//        $total_result_count = getTotalOrgListCount($level, $code_array);
        
        $total_result_count = mysql_num_rows($result);
        
        
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
        <title>Facility Registry</title>
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
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                        <span class="icon-bar"></span>
                    </button>
                </div>
                <div class="navbar-collapse collapse">
                    <ul class="nav navbar-nav">
                        <li class="active"><a href="index.php"><i class="fa fa-home fa-lg"></i> Home</a></li>
                        <!--<li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>-->
                        <li><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Reports</a></li>
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

                        <div id="org_list" style="min-height:280px;">
                            <ul>
                                <li id="tree_root">
                                    <a href="#" onclick="window.open('org_list.php?level=country', '_self');">Bangladesh</a>
                                    <ul>
                                        <?php
                                        $sql = "SELECT
                                                    `id`,
                                                    `name`,
                                                    `code`
                                                FROM
                                                    `dghshrml4_divisions`
                                                WHERE
                                                    is_active = 1";
                                        $div_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:1 || Query:</b><br />___<br />$sql</p>");
                                        while ($div_data = mysql_fetch_assoc($div_result)):
                                            ?>
                                            <li id="div_<?php echo $div_data['id']; ?>">
                                                <a href="#" onclick="window.open('org_list.php?level=div&id=<?php echo $div_data['id']; ?>', '_self');"><?php echo $div_data['name']; ?></a>
                                                <ul>
                                                    <?php
                                                    $sql = "SELECT
                                                                    `id`,
                                                                    `name`,
                                                                    `code`,
                                                                    `division_id`
                                                            FROM
                                                                    `dghshrml4_districts`
                                                            WHERE
                                                                    division_id = " . $div_data['id'] . "
                                                                AND is_active = 1";
                                                    $dis_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:2 || Query:</b><br />___<br />$sql</p>");
                                                    while ($dis_data = mysql_fetch_assoc($dis_result)):
                                                        ?>
                                                        <li id="div_<?php echo $dis_data['id']; ?>">
                                                            <a href="#" onclick="window.open('org_list.php?level=dis&id=<?php echo $dis_data['id']; ?>', '_self');"><?php echo $dis_data['name']; ?></a>
                                                            <ul>
                                                                <?php
                                                                $sql = "SELECT
                                                                                `id`,
                                                                                `name`,
                                                                                `code`
                                                                        FROM
                                                                                `dghshrml4_upazilas`
                                                                        WHERE
                                                                                district_id = " . $dis_data['id'] . "
                                                                        AND is_active = 1";
                                                                $uni_result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:3 || Query:</b><br />___<br />$sql</p>");
                                                                while ($uni_data = mysql_fetch_assoc($uni_result)):
                                                                    ?>
                                                                    <li id="upa_<?php echo $uni_data['id']; ?>">
                                                                        <a href="#" onclick="window.open('org_list.php?level=upa&id=<?php echo $uni_data['id']; ?>', '_self');"><?php echo $uni_data['name']; ?></a>
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
                    <div class="well sidebar-nav">
                        <?php 
                        $sql = "SELECT id FROM `dghshrml4_facilities` WHERE is_active = 1";
                        $r = mysql_query($sql) or die(mysql_error() . "<p><b>Code:1 || Query:</b><br />___<br />$sql</p>");
                        $org_count = mysql_num_rows($r);
                        ?>
                        <strong><em>Total Organizations: <?php echo number_format($org_count); ?></em></strong>
                    </div>
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
                        All Facilities under 
                        <em>
                            <?php if ($level == "div") { echo "<strong>" . $division_name . "</strong> division"; } ?>
                            <?php if ($level == "dis") { echo "<strong>" . $division_name . "</strong> division" . " under " . "<strong>" .  $district_name . "</strong> district"; } ?>
                            <?php if ($level == "upa") { 
                                echo "<strong>" . $division_name . "</strong> division";
                                echo " under " . "<strong>" .  $district_name . "</strong> district"; 
                                echo " under " . "<strong>" .  $upazila_name . "</strong> upazia"; 
                            } ?>
                        </em> <br />
                        Total <em><strong><?php echo $total_result_count; ?></strong></em> organization(s) found.
                    </div>
                        <table class="table table-striped table-bordered table-hover">
                            <thead>
                                <tr>
                                    <td><strong>#</strong></td>
                                    <td><strong>Facility Name (Code)</strong></td>
                                    <td><strong>Org Type</strong></td>
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
                                        <td><a href="org_profile.php?org_code=<?php echo $row['code']; ?>" target="_blank"><?php echo $row['name']; ?> (Code:<?php echo $row['code']; ?>)</a></td>
                                        <td><?php echo $row['facilitytype_name']; ?></td>
                                        <td><?php echo $row['email1']; ?></td>
                                        <td><?php echo $row['landphone1']; ?> / <?php echo $row['mobile1']; ?></td>
                                        <td>
                                            <?php if ($row['photo'] != ""): ?>
                                            <a href="<?php echo $hrm_root_dir; ?>/uploads/<?php echo $row['photo']; ?>" rel="lightbox" title="<?php echo $row['name']; ?>"><i class="fa fa-picture-o fa-lg"></i> </a>
                                            <?php endif; ?>
                                        </td>
                                    </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
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
        
        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php';?>
    </body>
</html>
