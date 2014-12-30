<?php
require_once 'configuration.php';
set_time_limit(6000); 
ini_set("memory_limit", -1);


/* * *
 * 
 * POST
 */
//print_r($_REQUEST);
$export = (int) mysql_real_escape_string(trim($_REQUEST['export']));


$div_id = (int) mysql_real_escape_string(trim($_REQUEST['admin_division']));
$dis_id = (int) mysql_real_escape_string(trim($_REQUEST['admin_district']));
$upa_id = (int) mysql_real_escape_string(trim($_REQUEST['admin_upazila']));
$agency_id = (int) mysql_real_escape_string(trim($_REQUEST['org_agency']));
//$type_id = (int) mysql_real_escape_string(trim($_REQUEST['org_type']));
$type_id = array();
$type_id = $_REQUEST['org_type'];
$type_id_count = count($type_id);
$form_submit = (int) mysql_real_escape_string(trim($_REQUEST['form_submit']));



if ($form_submit == 1 && isset($_REQUEST['form_submit'])) {
//print_r($type_id);
//die();
    /*
     * 
     * query builder to get the organizatino list
     */
    $query_string = "";
    if ($div_id > 0 || $dis_id > 0 || $upa_id > 0 || $agency_id > 0 || $type_id_count > 0) {
        $query_string .= " WHERE ";

        if ($agency_id > 0) {
            $query_string .= "dghshrml4_facilities.facilityagency_id = $agency_id";
        }
        if ($upa_id > 0) {
            if ($agency_id > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "dghshrml4_facilities.upazila_id = $upa_id";
        }
        if ($dis_id > 0) {
            if ($upa_id > 0 || $agency_id > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "dghshrml4_facilities.district_id = $dis_id";
        }
        if ($div_id > 0) {
            if ($dis_id > 0 || $upa_id > 0 || $agency_id > 0) {
                $query_string .= " AND ";
            }
            $query_string .= "dghshrml4_facilities.division_id = $div_id";
        }
        if ($type_id_count > 0 && $type_id[0] != "multiselect-all") {
            if ($div_id > 0 || $dis_id > 0 || $upa_id > 0 || $agency_id > 0) {
                $query_string .= " AND ";
            }
            $org_type_selected_array = "";
            for ($i = 0; $i < $type_id_count; $i++) {
                $org_type_selected_array .= " dghshrml4_facilities.facilitytype_id = '" . $type_id[$i] . "'";
                if ($i >= 0 && $i != $type_id_count - 1) {
                    $org_type_selected_array .= " OR ";
                }
            }
            $query_string .= " ( $org_type_selected_array ) ";
        } else if ($type_id[0] == "multiselect-all") {
//            if ($div_id > 0 || $dis_id > 0 || $upa_id > 0 || $agency_id > 0) {
//                $query_string .= " AND ";
//            }
//            $query_string .= "";
        }
    } else if (($div_id == 0 && $dis_id == 0 && $upa_id == 0 && $agency_id == 0) && $type_id > 0) {
        $org_type_selected_array = "";
        for ($i = 0; $i < $type_id_count; $i++) {
            $org_type_selected_array .= " dghshrml4_facilities.facilitytype_id = '" . $type_id[$i] . "'";
            if ($i >= 0 && $i != $type_id_count - 1) {
                $org_type_selected_array .= " OR ";
            }
        }
        $query_string .= " ( $org_type_selected_array ) ";
    }

    if ($_REQUEST['export'] != "excel") {
        $sql = "SELECT
                        dghshrml4_facilities.id,
                        dghshrml4_facilities.`name`,
                        dghshrml4_facilities.`code`,
                        dghshrml4_facilities.division_id,
                        dghshrml4_facilities.division_code,
                        dghshrml4_facilities.division_name,
                        dghshrml4_facilities.district_id,
                        dghshrml4_facilities.district_code,
                        dghshrml4_facilities.district_name,
                        dghshrml4_facilities.upazila_id,
                        dghshrml4_facilities.upazila_code,
                        dghshrml4_facilities.upazila_name,
                        dghshrml4_facilities.paurasava_id,
                        dghshrml4_facilities.paurasava_code,
                        dghshrml4_facilities.paurasava_name,
                        dghshrml4_facilities.union_id,
                        dghshrml4_facilities.union_code,
                        dghshrml4_facilities.union_name,
                        dghshrml4_facilities.ward_id,
                        dghshrml4_facilities.ward_code,
                        dghshrml4_facilities.ward_name,
                        dghshrml4_facilities.village_code,
                        dghshrml4_facilities.house_number,
                        dghshrml4_facilities.latitude,
                        dghshrml4_facilities.longitude,
                        dghshrml4_facilities.photo,
                        dghshrml4_facilities.mailing_address,
                        dghshrml4_facilities.landphone1,
                        dghshrml4_facilities.landphone2,
                        dghshrml4_facilities.landphone3,
                        dghshrml4_facilities.mobile1,
                        dghshrml4_facilities.mobile2,
                        dghshrml4_facilities.mobile3,
                        dghshrml4_facilities.email1,
                        dghshrml4_facilities.email2,
                        dghshrml4_facilities.email3,
                        dghshrml4_facilities.fax1,
                        dghshrml4_facilities.fax2,
                        dghshrml4_facilities.fax3,
                        dghshrml4_facilities.websiteurl,
                        dghshrml4_facilities.facebookurl,
                        dghshrml4_facilities.googleplusurl,
                        dghshrml4_facilities.twitterurl,
                        dghshrml4_facilities.youtubeurl,
                        dghshrml4_facilities.facilitytype_id,
                        dghshrml4_facilities.facilitytype_code,
                        dghshrml4_facilities.facilitytype_name,
                        dghshrml4_facilities.facilityagency_id,
                        dghshrml4_facilities.facilityagency_code,
                        dghshrml4_facilities.facilityagency_name,
                        dghshrml4_facilities.facilityfunction_id,
                        dghshrml4_facilities.facilityfunction_code,
                        dghshrml4_facilities.facilityfunction_name,
                        dghshrml4_facilities.facilitylevel_id,
                        dghshrml4_facilities.facilitylevel_code,
                        dghshrml4_facilities.facilitylevel_name,
                        dghshrml4_facilities.facilityhealthcarelevel_id,
                        dghshrml4_facilities.facilityhealthcarelevel_code,
                        dghshrml4_facilities.facilityhealthcarelevel_name,
                        dghshrml4_facilities.facilitylocationtype_id,
                        dghshrml4_facilities.facilitylocationtype_code,
                        dghshrml4_facilities.facilitylocationtype_name,
                        dghshrml4_facilities.facilityownership_id,
                        dghshrml4_facilities.facilityownership_code,
                        dghshrml4_facilities.facilityownership_name,
                        dghshrml4_facilities.main_electricitysourceoption_id,
                        dghshrml4_facilities.main_electricitysourceoption_code,
                        dghshrml4_facilities.main_electricitysourceoption_name,
                        dghshrml4_facilities.alt_electricitysourceoption_id,
                        dghshrml4_facilities.alt_electricitysourceoption_code,
                        dghshrml4_facilities.alt_electricitysourceoption_name,
                        dghshrml4_facilities.main_watersourceoption_id,
                        dghshrml4_facilities.main_watersourceoption_code,
                        dghshrml4_facilities.main_watersourceoption_name,
                        dghshrml4_facilities.alt_watersourceoption_id,
                        dghshrml4_facilities.alt_watersourceoption_code,
                        dghshrml4_facilities.alt_watersourceoption_name,
                        dghshrml4_facilities.toiletoption_id,
                        dghshrml4_facilities.toiletoption_code,
                        dghshrml4_facilities.toiletoption_name,
                        dghshrml4_facilities.toiletadequacytype_id,
                        dghshrml4_facilities.toiletadequacytype_code,
                        dghshrml4_facilities.toiletadequacytype_name,
                        dghshrml4_facilities.fuelsourceoption_id,
                        dghshrml4_facilities.fuelsourceoption_code,
                        dghshrml4_facilities.fuelsourceoption_name,
                        dghshrml4_facilities.laundrysystemoption_id,
                        dghshrml4_facilities.laundrysystemoption_code,
                        dghshrml4_facilities.laundrysystemoption_name,
                        dghshrml4_facilities.autoclavesystemoption_id,
                        dghshrml4_facilities.autoclavesystemoption_code,
                        dghshrml4_facilities.autoclavesystemoption_name,
                        dghshrml4_facilities.wastedisposeoption_id,
                        dghshrml4_facilities.wastedisposeoption_code,
                        dghshrml4_facilities.wastedisposeoption_name,
                        dghshrml4_facilities.permission_approval_license_number,
                        dghshrml4_facilities.permission_approval_license_next_renewal_date,
                        dghshrml4_facilities.permission_approval_license_conditions,
                        dghshrml4_facilities.permission_approval_license_info_code,
                        dghshrml4_facilities.permission_approval_license_info_date,
                        dghshrml4_facilities.permission_approval_license_type,
                        dghshrml4_facilities.permission_approval_license_aithority,
                        dghshrml4_facilities.land_info_code,
                        dghshrml4_facilities.land_size_decimal,
                        dghshrml4_facilities.land_mouza_name,
                        dghshrml4_facilities.land_mouza_geo_code,
                        dghshrml4_facilities.land_jl_number,
                        dghshrml4_facilities.land_functional_code,
                        dghshrml4_facilities.land_rs_dag_number,
                        dghshrml4_facilities.land_ss_dag_number,
                        dghshrml4_facilities.land_kharian_number,
                        dghshrml4_facilities.land_other_info,
                        dghshrml4_facilities.land_mutation_number,
                        dghshrml4_facilities.additional_chcp_name,
                        dghshrml4_facilities.additional_chcp_contact,
                        dghshrml4_facilities.additional_community_group_info,
                        dghshrml4_facilities.additional_chairnam_name,
                        dghshrml4_facilities.additional_chairman_contact,
                        dghshrml4_facilities.additional_chairman_community_support_info,
                        dghshrml4_facilities.additional_csg_1_name,
                        dghshrml4_facilities.additional_csg_1_contact,
                        dghshrml4_facilities.additional_csg_2_name,
                        dghshrml4_facilities.additional_csg_2_contact,
                        dghshrml4_facilities.sanctioned_office_equipment,
                        dghshrml4_facilities.sanctioned_vehicles,
                        dghshrml4_facilities.approved_bed_number,
                        dghshrml4_facilities.revenue_bed_number,
                        dghshrml4_facilities.development_bed_number,
                        dghshrml4_facilities.physical_structure_value,
                        dghshrml4_facilities.other_miscellaneous_issues,
                        dghshrml4_facilities.financial_revenue_code,
                        dghshrml4_facilities.establishmentyear,
                        dghshrml4_facilities.updated_by,
                        dghshrml4_facilities.created_at,
                        dghshrml4_facilities.updated_at,
                        dghshrml4_facilities.is_active
                FROM
                        `dghshrml4_facilities`
                        
                $query_string
                    
                ORDER BY
                        `name`";
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
                        dghshrml4_facilities.id,
                        dghshrml4_facilities.`name`,
                        dghshrml4_facilities.`code`,
                        dghshrml4_facilities.division_id,
                        dghshrml4_facilities.division_code,
                        dghshrml4_facilities.division_name,
                        dghshrml4_facilities.district_id,
                        dghshrml4_facilities.district_code,
                        dghshrml4_facilities.district_name,
                        dghshrml4_facilities.upazila_id,
                        dghshrml4_facilities.upazila_code,
                        dghshrml4_facilities.upazila_name,
                        dghshrml4_facilities.paurasava_id,
                        dghshrml4_facilities.paurasava_code,
                        dghshrml4_facilities.paurasava_name,
                        dghshrml4_facilities.union_id,
                        dghshrml4_facilities.union_code,
                        dghshrml4_facilities.union_name,
                        dghshrml4_facilities.ward_id,
                        dghshrml4_facilities.ward_code,
                        dghshrml4_facilities.ward_name,
                        dghshrml4_facilities.village_code,
                        dghshrml4_facilities.house_number,
                        dghshrml4_facilities.latitude,
                        dghshrml4_facilities.longitude,
                        dghshrml4_facilities.photo,
                        dghshrml4_facilities.mailing_address,
                        dghshrml4_facilities.landphone1,
                        dghshrml4_facilities.landphone2,
                        dghshrml4_facilities.landphone3,
                        dghshrml4_facilities.mobile1,
                        dghshrml4_facilities.mobile2,
                        dghshrml4_facilities.mobile3,
                        dghshrml4_facilities.email1,
                        dghshrml4_facilities.email2,
                        dghshrml4_facilities.email3,
                        dghshrml4_facilities.fax1,
                        dghshrml4_facilities.fax2,
                        dghshrml4_facilities.fax3,
                        dghshrml4_facilities.websiteurl,
                        dghshrml4_facilities.facebookurl,
                        dghshrml4_facilities.googleplusurl,
                        dghshrml4_facilities.twitterurl,
                        dghshrml4_facilities.youtubeurl,
                        dghshrml4_facilities.facilitytype_id,
                        dghshrml4_facilities.facilitytype_code,
                        dghshrml4_facilities.facilitytype_name,
                        dghshrml4_facilities.facilityagency_id,
                        dghshrml4_facilities.facilityagency_code,
                        dghshrml4_facilities.facilityagency_name,
                        dghshrml4_facilities.facilityfunction_id,
                        dghshrml4_facilities.facilityfunction_code,
                        dghshrml4_facilities.facilityfunction_name,
                        dghshrml4_facilities.facilitylevel_id,
                        dghshrml4_facilities.facilitylevel_code,
                        dghshrml4_facilities.facilitylevel_name,
                        dghshrml4_facilities.facilityhealthcarelevel_id,
                        dghshrml4_facilities.facilityhealthcarelevel_code,
                        dghshrml4_facilities.facilityhealthcarelevel_name,
                        dghshrml4_facilities.facilitylocationtype_id,
                        dghshrml4_facilities.facilitylocationtype_code,
                        dghshrml4_facilities.facilitylocationtype_name,
                        dghshrml4_facilities.facilityownership_id,
                        dghshrml4_facilities.facilityownership_code,
                        dghshrml4_facilities.facilityownership_name,
                        dghshrml4_facilities.main_electricitysourceoption_id,
                        dghshrml4_facilities.main_electricitysourceoption_code,
                        dghshrml4_facilities.main_electricitysourceoption_name,
                        dghshrml4_facilities.alt_electricitysourceoption_id,
                        dghshrml4_facilities.alt_electricitysourceoption_code,
                        dghshrml4_facilities.alt_electricitysourceoption_name,
                        dghshrml4_facilities.main_watersourceoption_id,
                        dghshrml4_facilities.main_watersourceoption_code,
                        dghshrml4_facilities.main_watersourceoption_name,
                        dghshrml4_facilities.alt_watersourceoption_id,
                        dghshrml4_facilities.alt_watersourceoption_code,
                        dghshrml4_facilities.alt_watersourceoption_name,
                        dghshrml4_facilities.toiletoption_id,
                        dghshrml4_facilities.toiletoption_code,
                        dghshrml4_facilities.toiletoption_name,
                        dghshrml4_facilities.toiletadequacytype_id,
                        dghshrml4_facilities.toiletadequacytype_code,
                        dghshrml4_facilities.toiletadequacytype_name,
                        dghshrml4_facilities.fuelsourceoption_id,
                        dghshrml4_facilities.fuelsourceoption_code,
                        dghshrml4_facilities.fuelsourceoption_name,
                        dghshrml4_facilities.laundrysystemoption_id,
                        dghshrml4_facilities.laundrysystemoption_code,
                        dghshrml4_facilities.laundrysystemoption_name,
                        dghshrml4_facilities.autoclavesystemoption_id,
                        dghshrml4_facilities.autoclavesystemoption_code,
                        dghshrml4_facilities.autoclavesystemoption_name,
                        dghshrml4_facilities.wastedisposeoption_id,
                        dghshrml4_facilities.wastedisposeoption_code,
                        dghshrml4_facilities.wastedisposeoption_name,
                        dghshrml4_facilities.permission_approval_license_number,
                        dghshrml4_facilities.permission_approval_license_next_renewal_date,
                        dghshrml4_facilities.permission_approval_license_conditions,
                        dghshrml4_facilities.permission_approval_license_info_code,
                        dghshrml4_facilities.permission_approval_license_info_date,
                        dghshrml4_facilities.permission_approval_license_type,
                        dghshrml4_facilities.permission_approval_license_aithority,
                        dghshrml4_facilities.land_info_code,
                        dghshrml4_facilities.land_size_decimal,
                        dghshrml4_facilities.land_mouza_name,
                        dghshrml4_facilities.land_mouza_geo_code,
                        dghshrml4_facilities.land_jl_number,
                        dghshrml4_facilities.land_functional_code,
                        dghshrml4_facilities.land_rs_dag_number,
                        dghshrml4_facilities.land_ss_dag_number,
                        dghshrml4_facilities.land_kharian_number,
                        dghshrml4_facilities.land_other_info,
                        dghshrml4_facilities.land_mutation_number,
                        dghshrml4_facilities.additional_chcp_name,
                        dghshrml4_facilities.additional_chcp_contact,
                        dghshrml4_facilities.additional_community_group_info,
                        dghshrml4_facilities.additional_chairnam_name,
                        dghshrml4_facilities.additional_chairman_contact,
                        dghshrml4_facilities.additional_chairman_community_support_info,
                        dghshrml4_facilities.additional_csg_1_name,
                        dghshrml4_facilities.additional_csg_1_contact,
                        dghshrml4_facilities.additional_csg_2_name,
                        dghshrml4_facilities.additional_csg_2_contact,
                        dghshrml4_facilities.sanctioned_office_equipment,
                        dghshrml4_facilities.sanctioned_vehicles,
                        dghshrml4_facilities.approved_bed_number,
                        dghshrml4_facilities.revenue_bed_number,
                        dghshrml4_facilities.development_bed_number,
                        dghshrml4_facilities.physical_structure_value,
                        dghshrml4_facilities.other_miscellaneous_issues,
                        dghshrml4_facilities.financial_revenue_code,
                        dghshrml4_facilities.establishmentyear,
                        dghshrml4_facilities.updated_by,
                        dghshrml4_facilities.created_at,
                        dghshrml4_facilities.updated_at,
                        dghshrml4_facilities.is_active
                FROM
                        `dghshrml4_facilities`
                        
                $query_string
                    
                ORDER BY
                        `name`";
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
//        if ($div_id > 0) {
//            $echo_string .= " Division: <strong>" . getDivisionNamefromCode(getDivisionCodeFormId($div_id)) . "</strong><br>";
//        }
//        if ($dis_id > 0) {
//            $echo_string .= " District: <strong>" . getDistrictNamefromCode(getDistrictCodeFormId($dis_id)) . "</strong><br>";
//        }
//        if ($upa_id > 0) {
//            $echo_string .= " Upazila: <strong>" . getUpazilaNamefromCode($upa_id, $dis_id) . "</strong><br>";
//        }
//        if ($agency_id > 0) {
//            $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyCode($agency_id) . "</strong><br>";
//        }
//        if ($type_id > 0) {
//            for ($i = 0; $i < $type_id_count; $i++) {
//                $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_id[$i]) . "</strong><br>";
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
                ->setCellValue("L$row_number", "Approved Bed Number")
				 ->setCellValue("M$row_number", "Revenue Bed Number")
				  ->setCellValue("N$row_number", "Development Bed Number")
                ->setCellValue("O$row_number", "Electricity Source");

        while ($data = mysql_fetch_assoc($org_list_result)) {
            $row_number++;
            $objPHPExcel->setActiveSheetIndex(0)
                    ->setCellValue("A$row_number", $data['name'])
                    ->setCellValue("B$row_number", $data['code'])
                    ->setCellValue("C$row_number", $data['division_name'])
                    ->setCellValue("D$row_number", $data['district_name'])
                    ->setCellValue("E$row_number", $data['upazila_name'])
                    ->setCellValue("F$row_number", $data['facilityagency_name'])
                    ->setCellValue("G$row_number", $data['facilitytype_name'])
                    ->setCellValue("H$row_number", $data['facilityfunction_name'])
                    ->setCellValue("I$row_number", $data['facilitylevel_name'])
                    ->setCellValue("J$row_number", $data['mobile1'])
                    ->setCellValue("K$row_number", $data['email1'])
                    ->setCellValue("L$row_number", $data['approved_bed_number'])
					->setCellValue("M$row_number", $data['revenue_bed_number'])
					->setCellValue("N$row_number", $data['development_bed_number'])
                    ->setCellValue("O$row_number", $data['main_electricitysourceoption_name']);
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
                        <!--<li><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>-->
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
                                                    `id`,
                                                    `name`,
                                                    `code`
                                            FROM
                                                    `dghshrml4_facilityagencies`";
                                    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>loadorg_agency:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

                                    while ($rows = mysql_fetch_assoc($result)) {
                                        echo "<option value=\"" . $rows['id'] . "\">" . $rows['name'] . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-4 form-group">
                                <select id="org_type" name="org_type[]" class="form-control multiselect"  multiple="multiple">
                                    <!--<option value="0">Select Org Type</option>-->
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
                                        if ($div_id > 0) {
                                            $echo_string .= " Division: <strong>" . getLocationNameFromId($div_id, 'divisions') . "</strong><br>";
                                        }
                                        if ($dis_id > 0) {
                                            $echo_string .= " District: <strong>" . getLocationNameFromId($dis_id, 'districts') . "</strong><br>";
                                        }
                                        if ($upa_id > 0) {
                                            $echo_string .= " Upazila: <strong>" . getLocationNameFromId($upa_id, 'upazilas') . "</strong><br>";
                                        }
                                        if ($agency_id > 0) {
                                            $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyId($agency_id) . "</strong><br>";
                                        }
                                        if ($type_id > 0 && $type_id[0] != "multiselect-all") {
                                            for ($i = 0; $i < $type_id_count; $i++) {
                                                $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_id[$i]) . "</strong><br>";
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
                                            <input type="hidden" name="admin_division" value="<?php echo $div_id; ?>" >
                                            <input type="hidden" name="admin_district" value="<?php echo $dis_id; ?>" >
                                            <input type="hidden" name="admin_upazila" value="<?php echo $upa_id; ?>" >
                                            <input type="hidden" name="org_agency" value=<?php echo $agency_id; ?>"" >
                                            <?php for ($i = 0; $i < $type_id_count; $i++): ?>
                                                <input type="hidden" name="org_type[]" value="<?php echo $type_id[$i]; ?>" >
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
                                        <td><strong>Paurasava</strong></td>
                                        <td><strong>Union</strong></td>
                                        <td><strong>Ward</strong></td>
                                        <td><strong>Agency</strong></td>
                                        <td><strong>Org Type</strong></td>
                                        <td><strong>Photo</strong></td>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php while ($data = mysql_fetch_assoc($org_list_result)): ?>
                                        <tr>
                                            <td><a href="org_profile.php?org_code=<?php echo $data['code']; ?>" target="_blank"><?php echo $data['name']; ?></a></td>
                                            <td><?php echo $data['code']; ?></td>
                                            <td><?php echo $data['division_name']; ?></td>
                                            <td><?php echo $data['district_name']; ?></td>
                                            <td><?php echo $data['upazila_name']; ?></td>
                                            <td><?php echo $data['paurasava_name']; ?></td>
                                            <td><?php echo $data['union_name']; ?></td>
                                            <td><?php echo $data['ward_code']; ?></td>
                                            <td><?php echo $data['facilityagency_name']; ?></td>
                                            <td><?php echo $data['facilitytype_name']; ?></td>
                                            <td>
                                                <?php if ($data['photo'] != ""): ?>
                                                    <a href="<?php echo $hrm_root_dir; ?>/uploads/<?php echo $data['photo']; ?>" rel="lightbox" title="<?php echo $data['name']; ?>"><i class="fa fa-picture-o fa-lg"></i> </a>
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
                                if ($div_id > 0) {
                                    $echo_string .= " Division: <strong>" . getLocationNameFromId($div_id, 'divisions') . "</strong><br>";
                                }
                                if ($dis_id > 0) {
                                    $echo_string .= " District: <strong>" . getLocationNameFromId($dis_id, 'districts') . "</strong><br>";
                                }
                                if ($upa_id > 0) {
                                    $echo_string .= " Upazila: <strong>" . getLocationNameFromId($upa_id, 'upazilas') . "</strong><br>";
                                }
                                if ($agency_id > 0) {
                                    $echo_string .= " Agency: <strong>" . getAgencyNameFromAgencyId($agency_id) . "</strong><br>";
                                }
                                if ($type_id > 0 && $type_id[0] != "multiselect-all") {
                                    for ($i = 0; $i < $type_id_count; $i++) {
                                        $echo_string .= " Org Type: <strong>" . getOrgTypeNameFormOrgTypeCode($type_id[$i]) . "</strong><br>";
                                    }
                                } else {
                                    $echo_string .= " Org Type: <strong>All Types</strong><br>";
                                }
                                echo "$echo_string";
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
            // Load Districs 
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

            // load upazila lsit 
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
