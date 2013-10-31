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

