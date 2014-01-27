<?php
require_once 'configuration.php';

$level = mysql_real_escape_string(trim($_GET['level']));
$code = (int) mysql_real_escape_string(trim($_GET['code']));
$dis_code = (int) mysql_real_escape_string(trim($_GET['dis_code']));

$page = (int) mysql_real_escape_string(trim($_GET['page']));
$rows_per_page = (int) mysql_real_escape_string(trim($_GET['rpp']));


if (empty($_GET['page'])){
    $page = 1;
}
if (empty($_GET['rpp'])){
    $rows_per_page = 10;
}

/**
 * Set the pagination query limit
 */
if ($page == 1){
    $limit_start = $page;
    $limit_string = " LIMIT $limit_start,$rows_per_page";
    
    $page_first = 1;
    $page_current = $page;
    $page_next = $page + 1;
    $page_prev = 0;
} else if ($page > 1){
    $limit_start = $page * $rows_per_page;
    $limit_string = " LIMIT $limit_start,$rows_per_page";
    
    $page_current = $page;
    $page_next = $page + 1;
    $page_prev = $page - 1;
}


if (isset($_GET['level']) && isset($_GET['code'])) {
    if ($level == "div") {
        $division_name = getDivisionNameFromCode($code);
        $sql = "SELECT
                    organization.org_name,
                    organization.org_code,
                    organization.org_type_code,
                    org_type.org_type_name,
                    org_level.org_level_name,
                    organization.org_level_code,
                    organization.org_photo,
                    organization.email_address1,
                    organization.mobile_number1,
                    organization.land_phone1
                FROM
                    `organization`
                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
                WHERE
                    organization.division_code = $code
                AND organization.active LIKE 1 $limit_string";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        
        $code_array = array ('code' => $code);
        $total_result_count = getTotalOrgListCount($level, $code_array);
        
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
                        org_level.org_level_name,
                        organization.org_photo,
                        organization.email_address1,
                        organization.mobile_number1,
                        organization.land_phone1
                FROM
                        `organization`
                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
                WHERE
                        organization.district_code = $code
                AND organization.active LIKE 1 $limit_string";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        
        $code_array = array ('code' => $code);
        $total_result_count = getTotalOrgListCount($level, $code_array);
        
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
                    org_level.org_level_name,
                    organization.org_photo,
                    organization.email_address1,
                    organization.mobile_number1,
                    organization.land_phone1
                FROM
                    `organization`
                LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code
                LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
                WHERE
                    organization.upazila_thana_code = $code
                AND organization.district_code = $dis_code            
                AND organization.active LIKE 1 $limit_string";
        $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:divOrgList || Query:</b><br />___<br />$sql</p>");
        
        $code_array = array ('code' => $code, 'dis_code' => $dis_code);
        $total_result_count = getTotalOrgListCount($level, $code_array);
        
        if (mysql_num_rows($result) > 0) {
            $showReportTable = TRUE;
        }
    }
}

$total_pages = floor($total_result_count / $rows_per_page);
$_SESSION['total_pages'] = $total_pages;
$total_pages = $_SESSION['total_pages'];
if ($page_current <= 3){
    $pages = array(
        '1_num' => '1',
        '2_num' => '2',
        '3_num' => '3',
        '4_num' => '4',
        '5_num' => '5'
    );
} else if ($page_current == ($total_pages-1) || $page_current == $total_pages){
    $pages = array(
        '1_num' => $page_current - 4,
        '2_num' => $page_current - 3,
        '3_num' => $page_current - 2,
        '4_num' => $page_current - 1,
        '5_num' => $page_current
    );
} else if ($page_current > 3 && $page_current <= ($total_pages-2)){
    $pages = array(
        '1_num' => $page_current - 2,
        '2_num' => $page_current - 1,
        '3_num' => $page_current,
        '4_num' => $page_current + 1,
        '5_num' => $page_current + 2
    );
} else if ($page_current > $total_pages){
    $pages = array(
        '1_num' => '1',
        '2_num' => '2',
        '3_num' => '3',
        '4_num' => '4',
        '5_num' => '5'
    );
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
                        <li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
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
                    <div class="well sidebar-nav">
                        <strong><em>Total Organizations: <?php echo getTotalOrgCount(); ?></em></strong>
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
                        All Organizations under 
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
                                    <td><strong>Organization Name (Code)</strong></td>
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
                                        <td><a href="org_profile.php?org_code=<?php echo $row['org_code']; ?>" target="_blank"><?php echo $row['org_name']; ?> (Code:<?php echo $row['org_code']; ?>)</a></td>
                                        <td><?php echo $row['org_type_name']; ?></td>
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
                    
                    <?php 
                    $page_path = trim($_SERVER['PHP_SELF']) . "?" . "level=" . $_GET['level'] . "&code=" . $_GET['code'] . "&dis_code=" . $_GET['dis_code'];

                    
                    ?>
                    
                    <div class="row">
                        <div class="col-sm-12">
                            Displaying page <?php echo $page_current; ?> of <?php echo $total_pages; ?> pages
                        </div>
                    </div>
                    
                    <ul class="pagination">
                        <?php if($page_prev > 0): ?>
                        <li class="previous"><a href="<?php echo $page_path . "&page=" . $page_prev . "&rpp=10"; ?>">&larr; Previous</a></li>
                        <?php endif; ?>
                        <li class="<?php if ($pages['1_num'] == $page_current) echo 'active'; ?>"><a href="<?php echo $page_path . "&page=" . $pages['1_num'] . "&rpp=10"; ?>"><?php echo $pages['1_num']; ?></a></li>
                        <li class="<?php if ($pages['2_num'] == $page_current) echo 'active'; ?>"><a href="<?php echo $page_path . "&page=" . $pages['2_num'] . "&rpp=10"; ?>"><?php echo $pages['2_num']; ?></a></li>
                        <li class="<?php if ($pages['3_num'] == $page_current) echo 'active'; ?>"><a href="<?php echo $page_path . "&page=" . $pages['3_num'] . "&rpp=10"; ?>"><?php echo $pages['3_num']; ?></a></li>
                        <li class="<?php if ($pages['4_num'] == $page_current) echo 'active'; ?>"><a href="<?php echo $page_path . "&page=" . $pages['4_num'] . "&rpp=10"; ?>"><?php echo $pages['4_num']; ?></a></li>
                        <li class="<?php if ($pages['5_num'] == $page_current) echo 'active'; ?>"><a href="<?php echo $page_path . "&page=" . $pages['5_num'] . "&rpp=10"; ?>"><?php echo $pages['5_num']; ?></a></li>
                        <?php if($page_next <= $total_pages): ?>
                        <li class="next"><a href="<?php echo $_SERVER['PHP_SELF'] . "?" . $_SERVER['QUERY_STRING'] . "&page=" . $page_next . "&rpp=10"; ?>">Next &rarr;</a></li>
                        <?php endif; ?>
                    </ul>

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
