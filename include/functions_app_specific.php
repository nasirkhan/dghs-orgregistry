<?php

/**
 * Get Division Name form division_code (Division BBS Code)
 * 
 * @param INT $div_code
 * @return string division_name
 */
function getDivisionNameFromCode($div_code) {
    if(!$div_code > 0){
        return "";
    }
    $sql = "SELECT division_name FROM admin_division WHERE division_bbs_code = $div_code AND division_active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDivisionNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['division_name'];
    } else {
        return "";
    }    
}

function getDistrictNameFromCode($dis_code) {
    if(!$dis_code > 0){
        return "";
    }
    $sql = "SELECT district_name FROM admin_district WHERE district_bbs_code = $dis_code and active like 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDistrictNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['district_name'];
    } else {
        return "";
    }    
}

function getUpazilaNamefromCode($bbs_code) {
    if (empty($bbs_code)) {
        return "";
    }
    $sql = "SELECT upazila_name  FROM `admin_upazila` WHERE `upazila_bbs_code` = $bbs_code LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getUpazilaNamefromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['upazila_name'];
}

/**
 * Get Upazila BBS Code Form the Old Upazila Id
 * @param INT $upazila_id
 * @return INT upazila_bbs_code
 */
function getUpazilaCodeFormId($upazila_id) {
    if(!$upazila_id > 0){
        return "";
    }
    $sql = "SELECT upazila_bbs_code FROM admin_upazila WHERE old_upazila_id = $upazila_id LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>getUpazilaCodeFormId:1</p><p>Query:</b></br >___<p>$sql</p>");

    $data = mysql_fetch_assoc($result);

    return $data['upazila_bbs_code'];
}


/**
 * Get the Agency Name form the Agency Code
 * @param type $agency_code
 * @return type
 */
function getAgencyNameFromAgencyCode($agency_code) {
    if(!$agency_code > 0){
        return "";
    }
    $sql = "SELECT org_agency_name FROM org_agency_code WHERE org_agency_code = $agency_code LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getAgencyNameFromAgencyCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_data = mysql_fetch_assoc($result);
    $org_agency_code_name = $org_data['org_agency_name'];
    return $org_agency_code_name;
}
function getDivisionCodeFromDistrictCode($dis_code) {
    if(!$dis_code > 0){
        return "";
    }
    $sql = "SELECT division_bbs_code FROM admin_district WHERE district_bbs_code = $dis_code and active like 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDistrictNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['division_bbs_code'];
    } else {
        return "";
    }    
}

function getDivisionNameFromDistrictCode($dis_code) {
    if(!$dis_code > 0){
        return "";
    }
    $sql = "SELECT
                    admin_division.division_name
            FROM
                    admin_district
            LEFT JOIN admin_division on admin_district.division_bbs_code = admin_division.division_bbs_code
            WHERE
                    admin_district.district_bbs_code = $dis_code
            AND admin_district.active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDivisionNameFromDistrictCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['division_name'];
    } else {
        return "";
    }    
}

function getOrgTypeNameFromCode($org_type_code) {
    if(!$org_type_code > 0){
        return "";
    }
    $sql = "SELECT org_type_name FROM `org_type` WHERE org_type_code = $org_type_code AND active LIKE 1;";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgTypeNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);
    
    if (mysql_num_rows($result) > 0) {
        return $data['org_type_name'];
    } else {
        return "";
    }
}

function getOrgLevelNameFromCode($org_level_code) {
    if(!$org_level_code > 0){
        return "";
    }
    $sql = "SELECT org_level_name FROM `org_level` WHERE org_level_code = $org_level_code AND active LIKE 1;";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgLevelNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);
    
    if (mysql_num_rows($result) > 0) {
        return $data['org_level_name'];
    } else {
        return "";
    }
}

function getDisDivNameCodeFromUpazilaAndDistrictCode($upa_code, $dis_code) {
    if(!$upa_code > 0 || !$dis_code > 0){
        return "";
    }
    $sql = "SELECT
                admin_upazila.upazila_name,
                admin_upazila.upazila_district_code,
                admin_division.division_name,
                admin_upazila.upazila_division_code,
                admin_district.district_name
            FROM
                `admin_upazila`
            LEFT JOIN admin_division ON admin_upazila.upazila_division_code = admin_division.division_bbs_code
            LEFT JOIN admin_district ON admin_upazila.upazila_district_code = admin_district.district_bbs_code
            WHERE
                admin_upazila.upazila_bbs_code = $upa_code
            and admin_upazila.upazila_district_code = $dis_code
            AND upazila_active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgLevelNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);
    
    if (mysql_num_rows($result) > 0) {
        return $data;
    } else {
        return "";
    }
}

/**
 * Get Division BBS Code Form the Old Division Id
 * @param INT $division_id
 * @return INT division_bbs_code
 */
function getDivisionCodeFormId($division_id) {
    $sql = "SELECT division_bbs_code FROM admin_division WHERE old_division_id = $division_id LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getDivisionCodeFormId:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['division_bbs_code'];
}

/**
 * Get District BBS Code Form the Old District Id
 * @param INT $district_id
 * @return INT district_bbs_code
 */
function getDistrictCodeFormId($district_id) {
    $sql = "SELECT district_bbs_code FROM admin_district WHERE old_district_id = $district_id LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getDivisionCodeFormId:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['district_bbs_code'];
}

/**
 * Get the organization type Name form the Organization type Id
 * @param type $org_type_id
 * @return string org_type_name
 */
function getOrgTypeNameFormOrgTypeCode($org_type_code) {
    $sql = "SELECT org_type_id, org_type_code, org_type_name FROM org_type WHERE org_type_code = $org_type_code LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeNameFormOrgTypeCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_type_data = mysql_fetch_assoc($result);
    $org_type_name = $org_type_data['org_type_name'];
    return $org_type_name;
}
