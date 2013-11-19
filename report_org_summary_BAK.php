<?php
require_once 'configuration.php';



/* * *
 * 
 * POST
 */
//print_r($_POST);
$div_code = (int) mysql_real_escape_string(trim($_POST['admin_division']));
$dis_code = (int) mysql_real_escape_string(trim($_POST['admin_district']));
$upa_code = (int) mysql_real_escape_string(trim($_POST['admin_upazila']));

$report_org_group = (int) mysql_real_escape_string(trim($_POST['report_org_group']));

$form_submit = (int) mysql_real_escape_string(trim($_POST['form_submit']));



if ($form_submit == 1 && isset($_POST['form_submit'])) {

    /*
     * 
     * query builder to get the organizatino list
     */
    $query_string = "";
    if ($div_code > 0 || $dis_code > 0 || $upa_code > 0) {
        $query_string .= " WHERE ";

        if ($upa_code > 0) {
            $query_string .= "organization.upazila_code = $upa_code";
        }
        if ($dis_code > 0) {
            if ($upa_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.district_code = $dis_code";
        }
        if ($div_code > 0) {
            if ($dis_code > 0 || $upa_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.division_code = $div_code";
        }
    }


    switch ($report_org_group) {
        case 1: echo "Report by Organizaion Type<br>";
            $sql = "SELECT
                            org_type.org_type_code,
                            org_type.org_type_name
                    FROM
                            org_type
                    WHERE
                            active LIKE 1
                    ORDER BY 
                            org_type.org_type_name";
            $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_div_list:1<br />Query:</b><br />___<br />$sql</p>");
            $data_heading = array();
            if (mysql_num_rows($result) > 0):
                while ($row = mysql_fetch_assoc($result)):
                    $data_heading[] = array(
                        'name' => $row['org_type_name'],
                        'value' => $row['org_type_code']
                    );
                endwhile;
            endif;

//            print_r($data_heading);
//            echo "$sql";
//            die();
            break;
        default : echo "Default<br>";
            $sql = "SELECT
                                organization.org_type_name,
                                COUNT(*) AS org_count
                        FROM
                                organization                        
                        GROUP BY
                                organization.org_type_code";

            echo "$sql";
    }

    $query_string .= " ORDER BY org_name";

    $org_list_result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_org_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    if (mysql_num_rows($org_list_result) > 0) {
        $showReportTable = TRUE;
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
                            <div class="col-md-12 form-group">
                                Show Report By
                            </div>
                        </div>
                        <div class="row">
                            <!--<div class="form-group">-->
                            <div class="col-md-4 form-group">
                                <select id="report_org_group" name="report_org_group" class="form-control">
                                    <option value="0">__ Select from the list __</option>
                                    <option value="1">Organization Type</option>
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
                                if ($div_code > 0) {
                                    $echo_string .= " Division: <strong>" . getDivisionNamefromCode(getDivisionCodeFormId($div_code)) . "</strong><br>";
                                }
                                if ($dis_code > 0) {
                                    $echo_string .= " District: <strong>" . getDistrictNamefromCode(getDistrictCodeFormId($dis_code)) . "</strong><br>";
                                }
                                if ($upa_code > 0) {
                                    $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode(getUpazilaCodeFormId($upa_code)) . "</strong><br>";
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
                                        <td><strong>Report Type</strong></td>
                                        <?php
                                        if (!$div_code > 0 && !$dis_code > 0 && !$upa_code > 0) :
                                            $sql = "SELECT
                                                            division_name,
                                                            division_bbs_code
                                                    FROM
                                                            admin_division
                                                    WHERE
                                                            division_active LIKE 1
                                                    ORDER BY
                                                            division_bbs_code";
                                            $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_div_list:1<br />Query:</b><br />___<br />$sql</p>");
                                            $data_table_heading = array();
                                            if (mysql_num_rows($result) > 0):
                                                while ($row = mysql_fetch_assoc($result)):
                                                    $data_table_heading[] = array(
                                                        'name' => $row['division_name'],
                                                        'value' => $row['division_bbs_code']
                                                    );
                                                endwhile;
                                            endif;
                                            for ($i = 0; $i < count($data_table_heading); $i++):
                                                ?>
                                                <td><strong><?php echo $data_table_heading[$i]['name']; ?></strong></td>
                                            <?php endfor; ?>
                                        <?php endif; ?>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $tr_count = count($data_heading);
                                    for ($i = 0; $i < $tr_count; $i++):
                                        ?>                                    
                                        <tr>
                                            <td><?php echo $data_heading[$i]['name']; ?>(<?php echo $data_heading[$i]['value']; ?>)</td>
                                            <?php
                                            if (!$div_code > 0 && !$dis_code > 0 && !$upa_code > 0) :
                                                $sql = "SELECT
                                                                o2.division_name,
                                                                count(o1.org_code) AS count
                                                        FROM
                                                                (
                                                                        SELECT DISTINCT
                                                                                `division_name`,
                                                                                `division_code`
                                                                        FROM
                                                                                organization
                                                                ) o2
                                                        LEFT JOIN organization o1 ON o1.`division_code` = o2.`division_code`
                                                        AND o1.org_type_code = " . $data_heading[$i]['value'] . "
                                                        AND o1.org_type_code > 0
                                                        GROUP BY
                                                                o1.division_code
                                                        ORDER BY
                                                                o2.division_name ASC";
                                                $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_div_list:2<br />Query:</b><br />___<br />$sql</p>");
                                                if (mysql_num_rows($result) > 0):
                                                    while ($row = mysql_fetch_assoc($result)) {
                                                        echo "<td>" . $row['count'] ."</td>";
                                                    }
                                                else:
                                                    ?>
                                                    <td>0</td>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </tr>
                                    <?php endfor; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning"> 
                                Report displaying form:<br>
                                <?php
                                $echo_string = "";
                                if ($div_code > 0) {
                                    $echo_string .= " Division: <strong>" . getDivisionNamefromCode(getDivisionCodeFormId($div_code)) . "</strong><br>";
                                }
                                if ($dis_code > 0) {
                                    $echo_string .= " District: <strong>" . getDistrictNamefromCode(getDistrictCodeFormId($dis_code)) . "</strong><br>";
                                }
                                if ($upa_code > 0) {
                                    $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode(getUpazilaCodeFormId($upa_code)) . "</strong><br>";
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
