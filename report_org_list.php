<?php
require_once 'configuration.php';



/* * *
 * 
 * POST
 */
//print_r($_POST);
$div_id = (int) mysql_real_escape_string(trim($_POST['admin_division']));
$dis_id = (int) mysql_real_escape_string(trim($_POST['admin_district']));
$upa_id = (int) mysql_real_escape_string(trim($_POST['admin_upazila']));
$agency_code = (int) mysql_real_escape_string(trim($_POST['org_agency']));
$type_code = (int) mysql_real_escape_string(trim($_POST['org_type']));
$form_submit = (int) mysql_real_escape_string(trim($_POST['form_submit']));

if ($form_submit == 1 && isset($_POST['form_submit'])) {

    /*
     * 
     * query builder to get the organizatino list
     */
    $query_string = "";
    if ($div_id > 0 || $dis_id > 0 || $upa_id > 0 || $agency_code > 0 || $type_code > 0) {
        $query_string .= " WHERE ";

        if ($agency_code > 0) {
            $query_string .= "organization.agency_code = $agency_code";
        }
        if ($upa_id > 0) {
            if ($agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.upazila_id = $upa_id";
        }
        if ($dis_id > 0) {
            if ($upa_id > 0 || $agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.district_id = $dis_id";
        }
        if ($div_id > 0) {
            if ($dis_id > 0 || $upa_id > 0 || $agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.division_id = $div_id";
        }
        if ($type_code > 0) {
            if ($div_id > 0 || $dis_id > 0 || $upa_id > 0 || $agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.org_type_code = $type_code";
        }
    }

    $query_string .= " ORDER BY org_name";

    $sql = "SELECT
                organization.org_name,
                organization.org_code,
                organization.upazila_thana_code,
                admin_division.division_name,
                admin_division.division_bbs_code,
                admin_district.district_name,
                admin_district.district_bbs_code,
                org_agency_code.org_agency_name,
                org_agency_code.org_agency_code,
                org_type.org_type_name,
                org_type.org_type_code
            FROM
                organization
            LEFT JOIN admin_division ON organization.division_code = admin_division.division_bbs_code
            LEFT JOIN admin_district ON organization.district_code = admin_district.district_bbs_code
            LEFT JOIN org_agency_code ON organization.agency_code = org_agency_code.org_agency_code
            LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code $query_string";
    $org_list_result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_org_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    if (mysql_num_rows($org_list_result) > 0) {
        $showReportTable = TRUE;
    }
echo "$sql";
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
                        <li><a href="index.php"><i class="fa fa-home fa-lg"></i> Home</a></li>
                        <li><a href="#"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
                        <li class="active"><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Report Dashboard</a></li>
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
            <div class="row">
                <div class="col-md-12">
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form">
                        <div class="row">
                            <!--<div class="form-group">-->
                            <div class="col-md-4 form-group">
                                <select id="admin_division" name="admin_division" class="form-control">
                                    <option value="0">Select Division</option>
                                    <?php
                                    /**
                                     * @todo change old_visision_id to division_bbs_code
                                     */
                                    $sql = "SELECT admin_division.division_name, admin_division.old_division_id FROM admin_division";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadDivision:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['old_division_id'] . "\">" . $rows['division_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="admin_district" name="admin_district" class="form-control">
                                    <option value="0">Select District</option>                                        
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="admin_upazila" name="admin_upazila" class="form-control">
                                    <option value="0">Select Upazila</option>                                        
                                </select>
                            </div>
                            <!--</div>-->
                        </div>
                        <div class="row">
                            <!--<div class="form-group">-->
                            <div class="col-md-4 form-group">
                                <select id="org_agency" name="org_agency" class="form-control">
                                    <option value="0">Select Agency</option>
                                    <?php
                                    $sql = "SELECT
                                                    org_agency_code.org_agency_code,
                                                    org_agency_code.org_agency_name
                                                FROM
                                                    org_agency_code
                                                ORDER BY
                                                    org_agency_code.org_agency_code";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadorg_agency:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['org_agency_code'] . "\">" . $rows['org_agency_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="org_type" name="org_type" class="form-control">
                                    <option value="0">Select Org Type</option>
                                    <?php
                                    $sql = "SELECT
                                                org_type.org_type_code,
                                                org_type.org_type_name
                                            FROM
                                                org_type
                                            ORDER BY
                                                org_type.org_type_name ASC";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadorg_type:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['org_type_code'] . "\">" . $rows['org_type_name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <input name="form_submit" value="1" type="hidden" />
                            <!--</div>-->
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <button id="btn_show_org_list" type="submit" class="btn btn-info btn-lg">Show Report</button>

                                <a id="loading_content" href="#" class="btn btn-warning disabled  btn-lg" style="display:none;"><i class="fa fa-spinner fa-spin fa-lg"></i> Loading content...</a>
                            </div>
                        </div>
                    </form>
                    <?php if ($form_submit == 1 && isset($_POST['form_submit'])) : ?>
                        <?php if ($showReportTable) : ?>
                            <div class="alert alert-success"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_id > 0) {
                                    $echo_string .= " Division: <strong>" . getDivisionNamefromCode(getDivisionCodeFormId($div_id)) . "</strong><br>";
                                }
                                if ($dis_id > 0) {
                                    $echo_string .= " District: <strong>" . getDistrictNamefromCode(getDistrictCodeFormId($dis_id)) . "</strong><br>";
                                }
                                if ($upa_id > 0) {
                                    $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode(getUpazilaCodeFormId($upa_id)) . "</strong><br>";
                                }
                                if ($agency_code > 0) {
                                    $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyCode($agency_code) . "</strong><br>";
                                }
                                if ($type_code > 0) {
                                    $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_code) . "</strong><br>";
                                }
                                echo "$echo_string";
                                ?>
                                <br />
                                <blockquote>
                                    Total <strong><em><?php echo mysql_num_rows($org_list_result); ?></em></strong> organization found.<br />
                                </blockquote>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <td><strong>Organization Name</strong></td>
                                        <td><strong>Organization Code</strong></td>
                                        <td><strong>Division</strong></td>
                                        <td><strong>District</strong></td>
                                        <td><strong>Upazila</strong></td>
                                        <td><strong>Agency</strong></td>
                                        <td><strong>Org Type</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysql_fetch_assoc($org_list_result)): ?>
                                        <tr>
                                            <td><a href="org_profile.php?org_code=<?php echo $data['org_code']; ?>" target="_blank"><?php echo $data['org_name']; ?></a></td>
                                            <td><?php echo $data['org_code']; ?></td>
                                            <td><?php echo $data['division_name']; ?></td>
                                            <td><?php echo $data['district_name']; ?></td>
                                            <td><?php echo getUpazilaNamefromCode($data['upazila_thana_code']); ?></td>
                                            <td><?php echo $data['org_agency_name']; ?></td>
                                            <td><?php echo $data['org_type_name']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_id > 0) {
                                    $echo_string .= " Division: <strong>" . getDivisionNamefromCode(getDivisionCodeFormId($div_id)) . "</strong><br>";
                                }
                                if ($dis_id > 0) {
                                    $echo_string .= " District: <strong>" . getDistrictNamefromCode(getDistrictCodeFormId($dis_id)) . "</strong><br>";
                                }
                                if ($upa_id > 0) {
                                    $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode(getUpazilaCodeFormId($upa_id)) . "</strong><br>";
                                }
                                if ($agency_code > 0) {
                                    $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyCode($agency_code) . "</strong><br>";
                                }
                                if ($type_code > 0) {
                                    $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_code) . "</strong><br>";
                                }
                                echo "$echo_string";
                                ?>
                                <br />
                                <blockquote>
                                    Total <strong><em><?php echo mysql_num_rows($org_list_result); ?></em></strong> organization found.<br />
                                </blockquote>
                            </div>
                        <?php endif; ?>
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

        <script type="text/javascript">
            // load division
            $('#admin_division').change(function() {
                $("#loading_content").show();
                var div_id = $('#admin_division').val();
                $.ajax({
                    type: "POST",
                    url: 'get/get_district_list.php',
                    data: {div_id: div_id},
                    dataType: 'json',
                    success: function(data)
                    {
                        $("#loading_content").hide();
                        var admin_district = document.getElementById('admin_district');
                        admin_district.options.length = 0;
                        for (var i = 0; i < data.length; i++) {
                            var d = data[i];
                            admin_district.options.add(new Option(d.text, d.value));
                        }
                    }
                });
            });

            // load district 
            $('#admin_district').change(function() {
                var dis_id = $('#admin_district').val();
                $("#loading_content").show();
                $.ajax({
                    type: "POST",
                    url: 'get/get_upazila_list.php',
                    data: {dis_id: dis_id},
                    dataType: 'json',
                    success: function(data)
                    {
                        $("#loading_content").hide();
                        var admin_upazila = document.getElementById('admin_upazila');
                        admin_upazila.options.length = 0;
                        for (var i = 0; i < data.length; i++) {
                            var d = data[i];
                            admin_upazila.options.add(new Option(d.text, d.value));
                        }
                    }
                });
            });
        </script>

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php';?>
    </body>
</html>
