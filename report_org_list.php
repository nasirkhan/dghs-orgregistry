<?php
require_once 'configuration.php';



/* * *
 * 
 * POST
 */
//print_r($_REQUEST);
$export = (int) mysql_real_escape_string(trim($_REQUEST['export']));


$div_code = (int) mysql_real_escape_string(trim($_REQUEST['admin_division']));
$dis_code = (int) mysql_real_escape_string(trim($_REQUEST['admin_district']));
$upa_code = (int) mysql_real_escape_string(trim($_REQUEST['admin_upazila']));
$agency_code = (int) mysql_real_escape_string(trim($_REQUEST['org_agency']));
//$type_code = (int) mysql_real_escape_string(trim($_REQUEST['org_type']));
$type_code = array();
$type_code = $_REQUEST['org_type'];
$type_code_count = count($type_code);
$form_submit = (int) mysql_real_escape_string(trim($_REQUEST['form_submit']));



if ($form_submit == 1 && isset($_REQUEST['form_submit'])) {
//print_r($type_code);
//die();
    /*
     * 
     * query builder to get the organizatino list
     */
    $query_string = "";
    if ($div_code > 0 || $dis_code > 0 || $upa_code > 0 || $agency_code > 0 || $type_code_count > 0) {
        $query_string .= " WHERE ";

        if ($agency_code > 0) {
            $query_string .= "organization.agency_code = $agency_code";
        }
        if ($upa_code > 0) {
            if ($agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.upazila_thana_code = $upa_code";
        }
        if ($dis_code > 0) {
            if ($upa_code > 0 || $agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.district_code = $dis_code";
        }
        if ($div_code > 0) {
            if ($dis_code > 0 || $upa_code > 0 || $agency_code > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "organization.division_code = $div_code";
        }
        if ($type_code_count > 0 && $type_code[0] != "multiselect-all") {
            if ($div_code > 0 || $dis_code > 0 || $upa_code > 0 || $agency_code > 0) {
                $query_string .= " AND ";
            }
            $org_type_selected_array = "";
            for ($i = 0; $i < $type_code_count; $i++) {
                $org_type_selected_array .= " organization.org_type_code = '" . $type_code[$i] . "'";
                if ($i >= 0 && $i != $type_code_count - 1) {
                    $org_type_selected_array .= " OR ";
                }
            }
            $query_string .= " ( $org_type_selected_array ) ";
        } else if ($type_code[0] == "multiselect-all") {
//            if ($div_code > 0 || $dis_code > 0 || $upa_code > 0 || $agency_code > 0) {
//                $query_string .= " AND ";
//            }
//            $query_string .= "";
        }
    } else if (($div_code == 0 && $dis_code == 0 && $upa_code == 0 && $agency_code == 0) && $type_code > 0) {
        $org_type_selected_array = "";
        for ($i = 0; $i < $type_code_count; $i++) {
            $org_type_selected_array .= " organization.org_type_code = '" . $type_code[$i] . "'";
            if ($i >= 0 && $i != $type_code_count - 1) {
                $org_type_selected_array .= " OR ";
            }
        }
        $query_string .= " ( $org_type_selected_array ) ";
    }

    if ($_REQUEST['export'] != "excel") {
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
                organization.org_function_code,
                organization.org_level_code,
                organization.mobile_number1,
                organization.email_address1,
                org_type.org_type_name,
                org_type.org_type_code,
                organization.org_photo
            FROM
                organization
            LEFT JOIN admin_division ON organization.division_code = admin_division.division_bbs_code
            LEFT JOIN admin_district ON organization.district_code = admin_district.district_bbs_code
            LEFT JOIN org_agency_code ON organization.agency_code = org_agency_code.org_agency_code
            LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code 
                $query_string  ORDER BY org_name";
        $org_list_result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_org_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
        $org_list_result_count = mysql_num_rows($org_list_result);
        if ($org_list_result_count > 0) {
            $showReportTable = TRUE;
//        echo "<pre>";
//        print_r($sql);
//        echo "</pre>";
//        die();
        }
    }



    if ($export == "excel" && $form_submit == 1 && isset($_REQUEST['export'])) {
        $sql = "SELECT
                organization.org_name,
                organization.org_code,
                organization.upazila_thana_code,
                admin_division.division_name,
                admin_division.division_bbs_code,
                admin_district.district_name,
                admin_district.district_bbs_code,
                org_agency_code.org_agency_name,
                org_organizational_functions.org_organizational_functions_name,
                org_level.org_level_name,
                organization.mobile_number1,
                organization.email_address1,
                org_source_of_electricity_main.electricity_source_name,
                organization.approved_bed_number,
                org_type.org_type_name,
                org_type.org_type_code,
                organization.org_photo
            FROM
                organization
            LEFT JOIN admin_division ON organization.division_code = admin_division.division_bbs_code
            LEFT JOIN admin_district ON organization.district_code = admin_district.district_bbs_code
            LEFT JOIN org_agency_code ON organization.agency_code = org_agency_code.org_agency_code
            LEFT JOIN org_level ON organization.org_level_code = org_level.org_level_code
            LEFT JOIN org_source_of_electricity_main ON organization.source_of_electricity_main_code = org_source_of_electricity_main.electricity_source_code
            LEFT JOIN org_organizational_functions ON organization.org_function_code = org_organizational_functions.org_organizational_functions_code
            LEFT JOIN org_type ON organization.org_type_code = org_type.org_type_code $query_string";
        $org_list_result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_org_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

//        echo "<pre>";
//        print_r($sql);
//        echo "</pre>";
//        die();
        error_reporting(E_ALL);
        ini_set('display_errors', TRUE);
        ini_set('display_startup_errors', TRUE);
        date_default_timezone_set('Asia/Dhaka');
        $report_export_datetime = date("Y-m-d H:i:s");

        if (PHP_SAPI == 'cli') {
            die('This example should only be run from a Web Browser');
        }

        /** Include PHPExcel */
        require_once 'library/PHPExcel/Classes/PHPExcel.php';


        // Create new PHPExcel object
        $objPHPExcel = new PHPExcel();

        // Set document properties
        $objPHPExcel->getProperties()->setCreator("Nasir Khan Saikat")
                ->setLastModifiedBy("Nasir Khan Saikat")
                ->setTitle("Organization Registry Report Export")
                ->setSubject("Organization Registry Report Export")
                ->setDescription("Ministry of Health and Family Welfare Organization Registry Report Export")
                ->setKeywords("office 2007 openxml php")
                ->setCategory("Organization Registry Export");

        /**
         * --------------------------------------------------------------------
         * 
         * Writing date to excel file.
         * 
         * @todo Excel Export
         * @todo Enable cache
         * @todo set print header and footer
         * --------------------------------------------------------------------
         */
        /**
         * 
         *          Start writing
         * *********************************
         */
//        $echo_string = "";
//        if ($div_code > 0) {
//            $echo_string .= " Division: <strong>" . getDivisionNamefromCode(getDivisionCodeFormId($div_code)) . "</strong><br>";
//        }
//        if ($dis_code > 0) {
//            $echo_string .= " District: <strong>" . getDistrictNamefromCode(getDistrictCodeFormId($dis_code)) . "</strong><br>";
//        }
//        if ($upa_code > 0) {
//            $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode($upa_code, $dis_code) . "</strong><br>";
//        }
//        if ($agency_code > 0) {
//            $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyCode($agency_code) . "</strong><br>";
//        }
//        if ($type_code > 0) {
//            for ($i = 0; $i < $type_code_count; $i++) {
//                $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_code[$i]) . "</strong><br>";
//            }
//        }
//        echo "$echo_string";
        $row_number = 0;
//        Writing excel headings
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue('A3', 'Government of People\'s Republic of Bangladesh')
                ->setCellValue('A4', 'Ministry of Health and Family Welfare')
                ->setCellValue('A6', 'Report Exported on:')
                ->setCellValue('B6', "$report_export_datetime");



        $objRichText = new PHPExcel_RichText();
        $objRichText->createText(' ');

        $objPayable = $objRichText->createTextRun('Organization Registry Report');
        $objPayable->getFont()->setBold(true);

        $objRichText->createText(' ');

        $objPHPExcel->getActiveSheet()->getCell('A1')->setValue($objRichText);



        $objPHPExcel->getActiveSheet()->mergeCells('A1:G1');
        $objPHPExcel->getActiveSheet()->mergeCells('A2:G2');
        $objPHPExcel->getActiveSheet()->mergeCells('A3:G3');
        $objPHPExcel->getActiveSheet()->mergeCells('A4:G4');
//        writing report infromation
        $row_number = 8;

        // start writing data values
        $row_number++;

        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue("A$row_number", "Organization Name")
                ->setCellValue("B$row_number", "Organization Code")
                ->setCellValue("C$row_number", "Division")
                ->setCellValue("D$row_number", "District")
                ->setCellValue("E$row_number", "Upazila")
                ->setCellValue("F$row_number", "Agency")
                ->setCellValue("G$row_number", "Org Type")
                ->setCellValue("H$row_number", "Org Function")
                ->setCellValue("I$row_number", "Org Level")
                ->setCellValue("J$row_number", "Mobile Number")
                ->setCellValue("K$row_number", "Email Address")
                ->setCellValue("L$row_number", "Bed Number")
                ->setCellValue("M$row_number", "Electricity Source");

        while ($data = mysql_fetch_assoc($org_list_result)) {
            $row_number++;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$row_number", $data['org_name'])
                    ->setCellValue("B$row_number", $data['org_code'])
                    ->setCellValue("C$row_number", $data['division_name'])
                    ->setCellValue("D$row_number", $data['district_name'])
                    ->setCellValue("E$row_number", getUpazilaNamefromCode($data['upazila_thana_code'], $data['district_bbs_code']))
                    ->setCellValue("F$row_number", $data['org_agency_name'])
                    ->setCellValue("G$row_number", $data['org_type_name'])
                    ->setCellValue("H$row_number", $data['org_organizational_functions_name'])
                    ->setCellValue("I$row_number", $data['org_level_name'])
                    ->setCellValue("J$row_number", $data['mobile_number1'])
                    ->setCellValue("K$row_number", $data['email_address1'])
                    ->setCellValue("L$row_number", $data['approved_bed_number'])
                    ->setCellValue("M$row_number", $data['electricity_source_name']);
        }

        /**
         * 
         *          END writing
         * *********************************
         */
        // Rename worksheet
        $objPHPExcel->getActiveSheet()->setTitle('Organization Registry Report');


        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $objPHPExcel->setActiveSheetIndex(0);


        // Redirect output to a client’s web browser (Excel5)
        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="Organization Registry Report.xls"');
        header('Cache-Control: max-age=0');
        // If you're serving to IE 9, then the following may be needed
        header('Cache-Control: max-age=1');

        // If you're serving to IE over SSL, then the following may be needed
        header('Expires: Mon, 26 Jul 1990 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        $objWriter->save('php://output');
        exit;
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
                        <li><a href="index.php"><i class="fa fa-home fa-lg"></i> Home</a></li>
                        <li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
                        <li class="active"><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Reports</a></li>
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
                    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="get" role="form">
                        <div class="row">
                            <!--<div class="form-group">-->
                            <div class="col-md-4 form-group">
                                <select id="admin_division" name="admin_division" class="form-control">
                                    <option value="0">Select Division</option>
                                    <?php
                                    /**
                                     * @todo change old_visision_id to division_bbs_code
                                     */
                                    $sql = "SELECT admin_division.division_name, admin_division.division_bbs_code FROM admin_division";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadDivision:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['division_bbs_code'] . "\">" . $rows['division_name'] . "</option>";
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
                                <select id="org_type" name="org_type[]" class="form-control multiselect"  multiple="multiple">
                                    <!--<option value="0">Select Org Type</option>-->
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
                                <button id="btn_show_org_list" type="submit" class="btn btn-info">Show Report</button>
                                <a href="report_org_list.php" type="submit" class="btn btn-default">Reset</a>

                                <a id="loading_content" href="#" class="btn btn-warning disabled" style="display:none;"><i class="fa fa-spinner fa-spin fa-lg"></i> Loading content...</a>
                            </div>
                        </div>
                    </form>
                    <?php if ($form_submit == 1 && isset($_REQUEST['form_submit'])) : ?>
                        <?php if ($showReportTable) : ?>
                            <div class="alert alert-success" id="info-area"> 
                                <div class="row">
                                    <div class="col-md-10">
                                        Report displaying form: <br>
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
                                        if ($agency_code > 0) {
                                            $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyCode($agency_code) . "</strong><br>";
                                        }
                                        if ($type_code > 0 && $type_code[0] != "multiselect-all") {
                                            for ($i = 0; $i < $type_code_count; $i++) {
                                                $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_code[$i]) . "</strong><br>";
                                            }
                                        } else {
                                            $echo_string .= " Org Type: <strong>All Types</strong><br>";
                                        }
                                        echo "$echo_string";
                                        ?>
                                        <br />
                                        <blockquote>
                                            Total <strong><em><?php echo mysql_num_rows($org_list_result); ?></em></strong> organization found.<br />
                                        </blockquote>
                                    </div>
                                    <div class="col-md-2">
                                        <!--<button type="button" class="btn btn-primary">Export Excel</button>-->
                                        <p>
                                        <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" role="form">
                                            <input type="hidden" name="admin_division" value="<?php echo $div_code; ?>" >
                                            <input type="hidden" name="admin_district" value="<?php echo $dis_code; ?>" >
                                            <input type="hidden" name="admin_upazila" value="<?php echo $upa_code; ?>" >
                                            <input type="hidden" name="org_agency" value=<?php echo $agency_code; ?>"" >
                                            <?php for ($i = 0; $i < $type_code_count; $i++): ?>
                                                <input type="hidden" name="org_type[]" value="<?php echo $type_code[$i]; ?>" >
                                            <?php endfor; ?>
                                            <input type="hidden" name="form_submit" value="<?php echo $form_submit; ?>" >
                                            <button type="submit" class="btn btn-primary btn-block" name="export" value="excel">Export Excel</button>
                                        </form>
                                        </p>
                                    </div>
                                </div>
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
                                        <td><strong>Photo</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysql_fetch_assoc($org_list_result)): ?>
                                        <tr>
                                            <td><a href="org_profile.php?org_code=<?php echo $data['org_code']; ?>" target="_blank"><?php echo $data['org_name']; ?></a></td>
                                            <td><?php echo $data['org_code']; ?></td>
                                            <td><?php echo $data['division_name']; ?></td>
                                            <td><?php echo $data['district_name']; ?></td>
                                            <td><?php echo getUpazilaNamefromCode($data['upazila_thana_code'], $data['district_bbs_code']); ?></td>
                                            <td><?php echo $data['org_agency_name']; ?></td>
                                            <td><?php echo $data['org_type_name']; ?></td>
                                            <td>
                                                <?php if ($data['org_photo'] != ""): ?>
                                                    <a href="<?php echo $hrm_root_dir; ?>/uploads/<?php echo $data['org_photo']; ?>" rel="lightbox" title="<?php echo $data['org_name']; ?>"><i class="fa fa-picture-o fa-lg"></i> </a>
                                                <?php endif; ?>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                </tbody>
                            </table>
                        <?php else: ?>
                            <div class="alert alert-warning" id="info-area"> 
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
                                if ($agency_code > 0) {
                                    $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyCode($agency_code) . "</strong><br>";
                                }
                                if ($type_code > 0 && $type_code[0] != "multiselect-all") {
                                    for ($i = 0; $i < $type_code_count; $i++) {
                                        $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_code[$i]) . "</strong><br>";
                                    }
                                } else {
                                    $echo_string .= " Org Type: <strong>All Types</strong><br>";
                                }
                                echo "$echo_string";
                                ?>
                                <br />
                                <blockquote>
                                    Total <strong><em><?php echo $org_list_result_count; ?></em></strong> organization found.<br />
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


<!--        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.js"></script>
        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.cookie.js"></script>
        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.hotkeys.js"></script>
        <script type="text/javascript" src="library/jstree-bootstrap-theme-master/jquery.jstree.js"></script>-->

        <script src="js/vendor/bootstrap.min.js"></script>

        <script src="js/plugins.js"></script>
        <script src="js/main.js"></script>

        <script src="library/slimbox-2.05/js/slimbox2.js"></script> 
        <script src="library/bootstrap-multiselect/js/bootstrap-multiselect.js"></script>

        <script type="text/javascript">
            // load division
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

            // load district 
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
        <script type="text/javascript">
            $(document).ready(function() {
                $('.multiselect').multiselect({
                    nonSelectedText: "Select Organization Type",
                    maxHeight: 300,
                    includeSelectAllOption: true
                });
            });
        </script>

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php'; ?>
    </body>
</html>
