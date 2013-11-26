<?php
require_once 'configuration.php';



/* * *
 * 
 * POST
 */
//print_r($_REQUEST);
$div_code = (int) mysql_real_escape_string(trim($_REQUEST['admin_division']));
$dis_code = (int) mysql_real_escape_string(trim($_REQUEST['admin_district']));
$upa_code = (int) mysql_real_escape_string(trim($_REQUEST['admin_upazila']));

$report_org_group = (int) mysql_real_escape_string(trim($_REQUEST['report_org_group']));

$form_submit = (int) mysql_real_escape_string(trim($_REQUEST['form_submit']));



if ($form_submit == 1 && isset($_REQUEST['form_submit'])) {

    /*
     * 
     * query builder to get the organizatino list
     */
    $query_string = "";
    $sql = "";
    if (($div_code > 0 || $dis_code > 0 || $upa_code > 0) && ($report_org_group > 0)) {

        if ($upa_code > 0) {            
            $sql = "SELECT
                            org_name,
                            division_name,
                            division_code,
                            district_name,
                            district_code,
                            upazila_thana_name,
                            upazila_thana_code,
                            COUNT(*) AS org_count
                    FROM
                            `organization`
                    WHERE
                            organization.upazila_thana_code = '$upa_code'
                    and organization.district_code = '$dis_code'
                    AND organization.org_type_code = '$report_org_group'
                    GROUP BY
                    organization.upazila_thana_code";
        }
        else if ($dis_code > 0) {
            $sql = "SELECT
                            org_name,
                            division_name,
                            division_code,
                            district_name,
                            district_code,
                            upazila_thana_name,
                            upazila_thana_code,
                            COUNT(*) AS org_count
                    FROM
                            `organization`
                    WHERE
                            organization.district_code = '$dis_code'
                    AND organization.org_type_code = '$report_org_group'
                    GROUP BY
                    organization.upazila_thana_code";
        }
        else if ($div_code > 0) {
            $sql = "SELECT
                            org_name,
                            division_name,
                            division_code,
                            district_name,
                            district_code,
                            upazila_thana_name,
                            upazila_thana_code,
                            COUNT(*) AS org_count
                    FROM
                            `organization`
                    WHERE
                            organization.division_code = '$div_code'
                    AND organization.org_type_code = '$report_org_group'
                    GROUP BY
                    organization.district_code";
        }
    }
    else if (($div_code == 0 && $dis_code == 0 && $upa_code == 0) && ($report_org_group > 0)){
        $sql = "SELECT
                        org_name,
                        division_name,
                        division_code,
                        district_name,
                        district_code,
                        upazila_thana_name,
                        upazila_thana_code,
                        COUNT(*) AS org_count
                FROM
                        `organization`
                WHERE
                        organization.org_type_code = '$report_org_group'
                GROUP BY
                organization.division_code";
    }

    
    $row_count = 0;
    if ($sql != ""){
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
                        <li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
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
                                                    division_name,
                                                    division_bbs_code
                                            FROM
                                                    admin_division
                                            WHERE
                                                    division_active LIKE 1
                                            ORDER BY
                                                    division_bbs_code";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>Select Division:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['division_bbs_code']; ?>"><?php echo $row['division_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
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
                            <div class="col-md-4 form-group">
                                <select id="report_org_group" name="report_org_group" class="form-control">
                                    <option value="0">__ Select Org Type __</option>
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
                    <?php if ($form_submit == 1 && isset($_REQUEST['form_submit'])) : ?>
                        <?php if ($showReportTable) : ?>
                            <div class="alert alert-success"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_code > 0) {
                                    $echo_string .= " Division: <strong>" . getDivisionNamefromCode($div_code) . "</strong><br>";
                                }
                                if ($dis_code > 0) {
                                    $echo_string .= " District: <strong>" . getDistrictNamefromCode($dis_code) . "</strong><br>";
                                }
                                if ($upa_code > 0) {
                                    $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode($upa_code, $dis_code) . "</strong><br>";
                                }
                                if ($report_org_group > 0) {
                                    $echo_string .= " Org Type: <strong>" . getOrgTypeNameFromCode($report_org_group) . "</strong><br>";
                                }
                                echo "$echo_string";
                                ?>
                                <br />
                                <blockquote>
                                    Total <strong><em><?php echo mysql_num_rows($org_list_result); ?></em></strong> result(s) found.<br />
                                </blockquote>
                            </div>
                            <table class="table table-striped table-bordered">
                                <thead>
                                    <tr>
                                        <?php 
                                            if ($upa_code > 0){
                                                $area_name = "Upazila";
                                            }
                                            else if ($dis_code > 0){
                                                $area_name = "Upazila";
                                            }
                                            else if ($div_code > 0){
                                                $area_name = "District";
                                            }
                                            else {
                                                $area_name = "Division";
                                            }
                                            ?>
                                        <td><strong><?php echo $area_name; ?> Name(s)</strong></td>
                                        <td><strong><?php echo getOrgTypeNameFromCode($report_org_group); ?></strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total_org_count = 0;
                                    while($data = mysql_fetch_assoc($org_list_result)):
                                        $total_org_count += $data['org_count'];?>
                                    <tr>
                                        <td>
                                            <?php 
                                            if ($upa_code > 0){
                                                echo $data['upazila_thana_name']; 
                                            }
                                            else if ($dis_code > 0){
                                                echo $data['upazila_thana_name']; 
                                            }
                                            else if ($div_code > 0){
                                                echo $data['district_name']; 
                                            }
                                            else {
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
                            <div class="alert alert-warning"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_code > 0) {
                                    $echo_string .= " Division: <strong>" . getDivisionNamefromCode($div_code) . "</strong><br>";
                                }
                                if ($dis_code > 0) {
                                    $echo_string .= " District: <strong>" . getDistrictNamefromCode($dis_code) . "</strong><br>";
                                }
                                if ($upa_code > 0) {
                                    $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode($upa_code, $dis_code) . "</strong><br>";
                                }
                                if ($report_org_group > 0) {
                                    $echo_string .= " Org Type: <strong>" . getOrgTypeNameFromCode($report_org_group) . "</strong><br>";
                                }
                                echo "$echo_string";
                                ?>
                                <br />
                                <blockquote>
                                    Total <strong><em><?php echo $row_count; ?></em></strong> organization found.<br />
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

        <script type="text/javascript">
            // load district
            $('#admin_division').change(function() {
                $("#loading_content").show();
                var div_code = $('#admin_division').val();
                $.ajax({
                    type: "POST",
                    url: 'get/get_districts.php',
                    data: {div_code: div_code},
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

            // load upazila
            $('#admin_district').change(function() {
                var dis_code = $('#admin_district').val();
                $("#loading_content").show();
                $.ajax({
                    type: "POST",
                    url: 'get/get_upazilas.php',
                    data: {dis_code: dis_code},
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
        <?php include_once 'include/ga_code.php'; ?>
    </body>
</html>
