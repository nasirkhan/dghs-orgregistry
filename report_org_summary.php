<?php
require_once 'configuration.php';



/* * *
 * 
 * POST
 */
$div_id = (int) mysql_real_escape_string(trim($_REQUEST['admin_division']));
$dis_id = (int) mysql_real_escape_string(trim($_REQUEST['admin_district']));
$upa_id = (int) mysql_real_escape_string(trim($_REQUEST['admin_upazila']));
$report_org_group = array();
if ($_REQUEST['report_org_group'][0] == 'multiselect-all') {
    $report_org_group = $_REQUEST['report_org_group'];
    $report_org_group = array_merge(array_diff($report_org_group, array("multiselect-all")));
} else {
    $report_org_group = $_REQUEST['report_org_group'];
}
$report_org_group_count = count($report_org_group);



$form_submit = (int) mysql_real_escape_string(trim($_REQUEST['form_submit']));



if ($form_submit == 1 && isset($_REQUEST['form_submit'])) {

    /*
     * 
     * query builder to get the organizatino list
     */
    $query_string = "";
    $sql = "";
    $output_array = array();
    $row_count = 0;

    $org_type_selected_array = "";
    for ($i = 0; $i < $report_org_group_count; $i++) {
        $org_type_selected_array .= " dghshrml4_facilities.facilitytype_id = '" . $report_org_group[$i] . "'";
        if ($i >= 0 && $i != $report_org_group_count - 1) {
            $org_type_selected_array .= " OR ";
        }
    }


    if (($div_id > 0 || $dis_id > 0 || $upa_id > 0) && ($report_org_group_count > 0)) {

        if ($upa_id > 0) {
            $sql = "SELECT
                            division_name,
                            division_code,
                            district_name,
                            district_code,
                            upazila_name,
                            upazila_code,
                            COUNT(*) AS org_count
                    FROM
                            `dghshrml4_facilities`
                    WHERE
                            $org_type_selected_array
                            AND upazila_id = '$upa_id'
                            AND district_id = '$dis_id'                    
                    GROUP BY
                            upazila_code";
        } else if ($dis_id > 0) {
            $sql = "SELECT
                            division_name,
                            division_code,
                            district_name,
                            district_code,
                            upazila_name,
                            upazila_code,
                            COUNT(*) AS org_count
                    FROM
                            `dghshrml4_facilities`
                    WHERE
                            $org_type_selected_array
                            AND district_id = '$dis_id'
                    GROUP BY
                            upazila_code";
        } else if ($div_id > 0) {
            $sql = "SELECT
                            division_name,
                            division_code,
                            district_name,
                            district_code,
                            upazila_name,
                            upazila_code,
                            COUNT(*) AS org_count
                    FROM
                            `dghshrml4_facilities`
                    WHERE
                            $org_type_selected_array
                            AND division_id = '$div_id'                    
                    GROUP BY
                            district_code";
        }
    } else if (($div_id == 0 && $dis_id == 0 && $upa_id == 0) && ($report_org_group[0] > 0)) {

        $sql = "SELECT
                        division_name,
                        division_code,
                        COUNT(*) AS org_count
                FROM
                        `dghshrml4_facilities`
                WHERE
                        $org_type_selected_array
                GROUP BY
                        division_id";
        if ($sql != "") {
            $org_list_result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_org_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
            $row_count = mysql_num_rows($org_list_result);
            if ($row_count > 0) {
                $showReportTable = TRUE;
            }
        }
        for ($i = 0; $i < $report_org_group_count; $i++) {
            $org_type_selected_array .= " dghshrml4_facilities.facilitytype_id = '" . $report_org_group[$i] . "'";
            if ($i >= 0 && $i != $report_org_group_count - 1) {
                $org_type_selected_array .= " OR ";
            }
        }
    }



    if ($sql != "") {
        $org_list_result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_org_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
        $row_count = mysql_num_rows($org_list_result);
        if ($row_count > 0) {
            $showReportTable = TRUE;
        }
    }
//echo "$sql";
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
        <link rel="stylesheet" href="library/bootstrap-multiselect/css/bootstrap-multiselect.css">

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
                        <!--<li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>-->
                        <li class="active"><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Reports</a></li>
                        <li><a href="search.php"><i class="fa fa-search fa-lg"></i> Search</a></li>
                    </ul>
                    <form name="search-form" class="navbar-form navbar-right" action="search.php" method="get">
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
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" role="form">
                        <div class="row">
                            <!--<div class="form-group">-->
                            <div class="col-md-4 form-group">
                                <select id="admin_division" name="admin_division" class="form-control">
                                    <option value="0">__ Select Division __</option>
                                    <?php
                                    $sql = "SELECT
                                                    dghshrml4_divisions.id,
                                                    dghshrml4_divisions.`name`,
                                                    dghshrml4_divisions.combinedcode,
                                                    dghshrml4_divisions.`code`
                                            FROM
                                                    `dghshrml4_divisions`";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadDivision:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['id'] . "\">" . $rows['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="admin_district" name="admin_district" class="form-control">
                                    <option value="0">__ Select District __</option>                                        
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="admin_upazila" name="admin_upazila" class="form-control">
                                    <option value="0">__ Select Upazila __</option>                                        
                                </select>
                            </div>
                            <!--</div>-->
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                Show Report By
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="form-group">-->
                            <div class="col-md-12 form-group">
                                <select id="report_org_group" name="report_org_group[]" class="form-control"  multiple="multiple">
                                    <?php
                                    $sql = "SELECT
                                                    `id`,
                                                    `name`,
                                                    `code`
                                            FROM
                                                    `dghshrml4_facilitytypes`
                                            ORDER BY
                                                    `name`";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadorg_type:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['id'] . "\">" . $rows['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                                </select>
                            </div>
                            <input name="form_submit" value="1" type="hidden" />
                            <!--</div>-->
                        </div>
                        <div class="row">
                            <div class="col-md-12 form-group">
                                <button id="btn_show_org_list" type="submit" class="btn btn-info">Show Report</button>

                                <a id="loading_content" href="#" class="btn btn-warning disabled" style="display:none;"><i class="fa fa-spinner fa-spin fa-lg"></i> Loading content...</a>
                            </div>
                        </div>
                    </form>
                    <?php if ($form_submit == 1 && isset($_REQUEST['form_submit'])) : ?>
                        <?php if ($showReportTable) : ?>
                            <div class="alert alert-success" id="info-area"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_id > 0) {
                                    $echo_string .= " Division: <strong>" . getLocationNameFromId($div_id, 'divisions') . "</strong><br>";
                                }
                                if ($dis_id > 0) {
                                    $echo_string .= " District: <strong>" . getLocationNameFromId($dis_id, 'districts') . "</strong><br>";
                                }
                                if ($upa_id > 0) {
                                    $echo_string .= " Upazila: <strong>" . getLocationNameFromId($upa_id, 'upazilas') . "</strong><br>";
                                }
                                if ($report_org_group_count > 0) {
                                    $echo_string .= "Org Type: ";
                                    for ($i = 0; $i < $report_org_group_count; $i++) {
                                        $echo_string .= " <strong>" . getOrgTypeNameFromId($report_org_group[$i]) . "</strong>,";
                                    }
                                }

//                                if ($report_org_group[0] > 0) {
//                                    $echo_string .= " Org Type: <strong>" . getOrgTypeNameFromId($report_org_group[0]) . "</strong><br>";
//                                }
                                echo "$echo_string";
                                ?>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <?php
                                        if ($upa_id > 0) {
                                            $area_name = "Upazila";
                                        } else if ($dis_id > 0) {
                                            $area_name = "Upazila";
                                        } else if ($div_id > 0) {
                                            $area_name = "District";
                                        } else {
                                            $area_name = "Division";
                                        }
                                        ?>
                                        <td><strong><?php echo $area_name; ?> Name(s)</strong></td>
                                        <td><strong><?php echo 'Count'; ?></strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $total_org_count = 0;
                                    while ($data = mysql_fetch_assoc($org_list_result)):
                                        $total_org_count += $data['org_count'];
                                        ?>
                                        <tr>
                                            <td>
                                                <?php
                                                if ($upa_id > 0) {
                                                    echo $data['upazila_name'];
                                                } else if ($dis_id > 0) {
                                                    echo $data['upazila_name'];
                                                } else if ($div_id > 0) {
                                                    echo $data['district_name'];
                                                } else {
                                                    echo $data['division_name'];
                                                }
                                                ?>
                                            </td>
                                            <td><?php echo $data['org_count']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                    <tr  class="warning">
                                        <td><em>Total organization(s)</em></td>
                                        <td><strong><?php echo $total_org_count; ?></strong></td>
                                    </tr>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning" id="info-area"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_id > 0) {
                                    $echo_string .= " Division: <strong>" . getLocationNameFromId($div_id, 'divisions') . "</strong><br>";
                                }
                                if ($dis_id > 0) {
                                    $echo_string .= " District: <strong>" . getLocationNameFromId($dis_id, 'districts') . "</strong><br>";
                                }
                                if ($upa_id > 0) {
                                    $echo_string .= " Upazila: <strong>" . getLocationNameFromId($upa_id, 'upazilas') . "</strong><br>";
                                }
                                if ($report_org_group[0] > 0) {
                                    $echo_string .= " Org Type: <strong>" . getOrgTypeNameFromCode($report_org_group[0]) . "</strong><br>";
                                }
                                echo "$echo_string";
                                ?>
                            </div>
                        <?php endif; ?>
                    <?php endif; ?>
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

        <script src="//ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
        <script>window.jQuery || document.write('<script src="js/vendor/jquery-1.10.1.min.js"><\/script>')</script>

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script src="library/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>

        <script type="text/javascript">
            // Load Districs 
            $('#admin_division').change(function () {
                $("#loading_content").show();
                var div_id = $('#admin_division').val();
                $.ajax({
                    type: "POST",
                    url: 'get/get_district_list.php',
                    data: {div_id: div_id},
                    dataType: 'json',
                    success: function (data)
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

            // load upazila lsit 
            $('#admin_district').change(function () {
                var dis_id = $('#admin_district').val();
                $("#loading_content").show();
                $.ajax({
                    type: "POST",
                    url: 'get/get_upazila_list.php',
                    data: {dis_id: dis_id},
                    dataType: 'json',
                    success: function (data)
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

            $("#report_org_group").multiselect({
                includeSelectAllOption: true,
                maxHeight: 300,
                buttonWidth: '350px'
            });
        </script>

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php'; ?>
    </body>
</html>