<?php


/**
 * Get the <b>Organization Name</b> from the <b>Organization Code</b><b></b>
 * @param int $org_code Organization Code
 * @return String org_name Organization Name
 */
function getOrgNameFromCode($org_code) {
    if(!$org_code > 0){
        return "";
    }
    $sql = "SELECT
                    *
            FROM
                    `dghshrml4_facilities`
            WHERE
                    dghshrml4_facilities.`code` = $org_code";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgNameFormOrgCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_data = mysql_fetch_assoc($result);
    return $org_data['name'];
}

function getOrgTypeNameFromOrgCode($org_code) {
    if (!$org_code > 0) {
        return "";
    }

    $sql = "SELECT
                    facilitytype_name
            FROM
                    `dghshrml4_facilities`
            WHERE
                    dghshrml4_facilities.`code` = $org_code  LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeNameFromOrgCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_type_data = mysql_fetch_assoc($result);
    $org_type_name = $org_type_data['facilitytype_name'];
    return $org_type_name;
}
/**
 * Get <b>Organizaition Type Code</b> form the <b>Organization Code</b>
 * @param INT $org_code
 * @return INT org_type_code
 */
function getOrgTypeCodeFromOrgCode($org_code) {
    if(!$org_code > 0){
        return "";
    }
    $sql = "SELECT
                    facilitytype_code
            FROM
                    `dghshrml4_facilities`
            WHERE
                    dghshrml4_facilities.`code` = $org_code  LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeCodeFromOrgCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_type_data = mysql_fetch_assoc($result);
    return $org_type_data['org_type_code'];
}
/**
 * Get Division Name form id (Division id)
 * 
 * @param INT $id
 * @return string name
 */
function getDivisionNameFromId($id) {
    if(!$id > 0){
        return "";
    }
    $sql = "SELECT `name` FROM dghshrml4_divisions WHERE id = $id AND is_active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDivisionNameFromid || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['name'];
    } else {
        return "";
    }    
}

/**
 * Get Location Name form Id (Location Id)
 * 
 * @param INT $id Location ID
 * @param STRING $level_name Options: divisions, districts, upazilas
 * @return string division_name
 */
function getLocationNameFromId($id, $level_name) {
    if(!$id > 0 || $level_name == ""){
        return "";
    }
    $sql = "SELECT `name` FROM dghshrml4_$level_name WHERE id = $id AND is_active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:get$level_name NameFromid || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['name'];
    } else {
        return "";
    }    
}

/**
 * Get the Agency Name form the Agency Code
 * @param type $agency_id
 * @return type
 */
function getAgencyNameFromAgencyId($agency_id) {
    if(!$agency_id > 0){
        return "";
    }
    $sql = "SELECT
                    *
            FROM
                    `dghshrml4_facilityagencies`
            WHERE
                    id = $agency_id
            ORDER BY
                    `name`";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getAgencyNameFromAgencyCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_data = mysql_fetch_assoc($result);
    $org_agency_code_name = $org_data['name'];
    return $org_agency_code_name;
}

/**
 * Get the organization type Name form the Organization type Id
 * @param type $org_type_id
 * @return string org_type_name
 */
function getOrgTypeNameFormOrgTypeId($org_type_id) {
    if(!$org_type_id > 0){
        return "";
    }
    $sql = "SELECT
                    *
            FROM
                    `dghshrml4_facilitytypes`
            WHERE
                    id = $org_type_id
            LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeNameFormOrgTypeCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_type_data = mysql_fetch_assoc($result);
    $org_type_name = $org_type_data['name'];
    return $org_type_name;
}

/**
 * Get Division Name form id (Division id)
 * 
 * @param INT $div_code
 * @return string division_name
 */
function getDivisionIdFromDistrictId($id) {
    if(!$id > 0){
        return "";
    }
    $sql = "SELECT * FROM dghshrml4_districts WHERE id = $id AND is_active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDivisionNameFromid || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['division_id'];
    } else {
        return "";
    }    
}


/**
 * Get Division Name form id (Division id)
 * 
 * @param INT $div_code
 * @return string division_name
 */
function getDistrictIdFromUpazilaId($id) {
    if(!$id > 0){
        return "";
    }
    $sql = "SELECT * FROM dghshrml4_upazilas WHERE id = $id AND is_active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getDistrictIdFromUpazilaId || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);

    if (mysql_num_rows($result) > 0) {
        return $data['district_id'];
    } else {
        return "";
    }    
}

/**
 * ----------------------------------------------------------------------------
 */

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

function getUpazilaNamefromCode($bbs_code, $dis_bbs_code) {
    if (empty($bbs_code) || empty($dis_bbs_code)) {
        return "";
    }
    $sql = "SELECT upazila_name  FROM `admin_upazila` WHERE `upazila_bbs_code` = $bbs_code AND upazila_district_code = '$dis_bbs_code' LIMIT 1";
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
    $sql = "SELECT name FROM `dghshrml4_facilitytypes` WHERE code = $org_type_code;";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgTypeNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);
    
    if (mysql_num_rows($result) > 0) {
        return $data['name'];
    } else {
        return "";
    }
}
function getOrgTypeNameFromId($org_type_id) {
    if(!$org_type_id > 0){
        return "";
    }
    $sql = "SELECT name FROM `dghshrml4_facilitytypes` WHERE id = $org_type_id;";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgTypeNameFromId || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);
    
    if (mysql_num_rows($result) > 0) {
        return $data['name'];
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

/**
 * Get organization function Name from code
 * @param type $function_code
 * @return string
 */
function getOrgFunctionNameFromCode($function_code) {
    if(!$function_code > 0){
        return "";
    }
    $sql = "SELECT
                    org_organizational_functions_name
            FROM
                    `org_organizational_functions`
            WHERE
                    org_organizational_functions_code = $function_code
            AND active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgFunctionNameFromCode || Query:</b><br />___<br />$sql</p>");

    $data = mysql_fetch_assoc($result);
    
    if (mysql_num_rows($result) > 0) {
        return $data['org_organizational_functions_name'];
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
    if(!$division_id > 0){
        return "";
    }
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
    if(!$district_id > 0){
        return "";
    }
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
    if(!$org_type_code > 0){
        return "";
    }
    $sql = "SELECT org_type_id, org_type_code, org_type_name FROM org_type WHERE org_type_code = $org_type_code LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeNameFormOrgTypeCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_type_data = mysql_fetch_assoc($result);
    $org_type_name = $org_type_data['org_type_name'];
    return $org_type_name;
}

function getOrgTypeNameFormOrgCode($org_code) {
    if (!$org_code > 0) {
        return "";
    }

    $sql = "SELECT org_code, org_name, org_type_code, organization_id FROM organization WHERE org_code = $org_code  LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeNameFormOrgCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);
    $org_type_code = $data['org_type_code'];

    if (!$org_type_code > 0) {
        return "";
    }

    $sql = "SELECT org_type_name  FROM `org_type` WHERE `org_type_code` = $org_type_code LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeNameFormOrgCode:2</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_type_data = mysql_fetch_assoc($result);
    $org_type_name = $org_type_data['org_type_name'];
    return $org_type_name;
}


/**
 * Get the <b>Organization Name</b> from the <b>Organization Code</b><b></b>
 * @param int $org_code Organization Code
 * @return String org_name Organization Name
 */
function getOrgNameFormOrgCode($org_code) {
    if(!$org_code > 0){
        return "";
    }
    $sql = "SELECT organization.id,organization.org_name FROM organization WHERE organization.org_code = $org_code";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgNameFormOrgCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $org_data = mysql_fetch_assoc($result);
    return $org_data['org_name'];
}

/**
 * Get <b>Organizaition Type Code</b> form the <b>Organization Code</b>
 * @param INT $org_code
 * @return INT org_type_code
 */
//function getOrgTypeCodeFromOrgCode($org_code) {
//    if(!$org_code > 0){
//        return "";
//    }
//    $sql = "SELECT organization.org_type_code FROM organization WHERE organization.org_code = $org_code LIMIT 1";
//    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgTypeCodeFromOrgCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");
//
//    $org_type_data = mysql_fetch_assoc($result);
//    return $org_type_data['org_type_code'];
//}
function getPhysicalStructure($id) {
   if (!$id > 0)
       return 0;
    $sql = "SELECT
                org_physical_structure.id,org_physical_structure.physical_structure_value
            FROM
                org_physical_structure
            WHERE
               org_physical_structure.id = $id LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getSalaryDrawTypeNameFromID:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['physical_structure_value'];
}
/**
 * Sanctioned bed input field will be displayed for some specific organization
 * types, here it checks the organization by org_code
 * @param INT $org_type_code
 * @return boolean
 */
function showSanctionedBed($org_type_code) {
    $org_type_code = (int) $org_type_code;
    if ($org_type_code >= 1012 && $org_type_code <= 1018) {
        return FALSE;
    } else if ($org_type_code >= 1019 && $org_type_code <= 1020) {
        return TRUE;
    } else if ($org_type_code == 1021) {
        return FALSE;
    } else if ($org_type_code >= 1022 && $org_type_code <= 1029) {
        return TRUE;
    } else if ($org_type_code >= 1030 && $org_type_code <= 1032) {
        return FALSE;
    } else if ($org_type_code >= 1033 && $org_type_code <= 1036) {
        return TRUE;
    } else if ($org_type_code >= 1037 && $org_type_code <= 1040) {
        return FALSE;
    } else if ($org_type_code == 1041) {
        return TRUE;
    } else if ($org_type_code == 1042) {
        return FALSE;
    } else if ($org_type_code >= 1043 && $org_type_code <= 1044) {
        return TRUE;
    } else if ($org_type_code >= 1045 && $org_type_code <= 1055) {
        return FALSE;
    } else if ($org_type_code == 1056) {
        return TRUE;
    } else if ($org_type_code >= 1057 && $org_type_code <= 1059) {
        return FALSE;
    } else if ($org_type_code >= 1060 && $org_type_code <= 1061) {
        return TRUE;
		}
	else if ($org_type_code == 1002) {              // added by rajib
        return TRUE;
		}
	else if ($org_type_code == 1005) {        // added by rajib
        return TRUE;	
    }
	else if ($org_type_code == 1007) {        // added by rajib
        return TRUE;	
    } 
	else if ($org_type_code == 1009) {        // added by rajib
        return TRUE;	
    }  
	else if ($org_type_code == 1062) {
        return FALSE;
    }
	else if ($org_type_code == 1062) {
        return FALSE;
    }
}

/**
 * Get <b>Electricity Main Source Name</b> From <b>Electricity Main Source Code</b>
 * @param type $electricity_main_source_code
 * @return type
 */
function getElectricityMainSourceNameFromCode($electricity_main_source_code) {
    if(!$electricity_main_source_code > 0){
        return "";
    }
    $sql = "SELECT `electricity_source_name` FROM `org_source_of_electricity_main` WHERE `electricity_source_code` = \"$electricity_main_source_code\" LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>checkPasswordIsCorrect:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['electricity_source_name'];
}

/**
 * Get <b>Electricity Alternative Source Name</b> From <b>Electricity Alternative Source Code</b>
 * @param type $electricity_main_source_code
 * @return type
 */
function getElectricityAlterSourceNameFromCode($electricity_alter_source_code) {
    if(!$electricity_alter_source_code > 0){
        return "";
    }
    $sql = "SELECT `electricity_source_name` FROM `org_source_of_electricity_main` WHERE `electricity_source_code` = \"$electricity_alter_source_code\" LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getElectricityAlterSourceNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['electricity_source_name'];
}

/**
 * Get <b>Water Main Source Name</b> From <b>Water Main Source Code</b>
 * @param type $water_source_code
 * @return type
 */
function getWaterMainSourceNameFromCode($water_source_code) {
    if(!$water_source_code > 0){
        return "";
    }
    $sql = "SELECT `water_supply_source_name` FROM `org_source_of_water_supply_main` WHERE `water_supply_source_code` = \"$water_source_code\" LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getWaterMainSourceNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['water_supply_source_name'];
}

/**
 * Get <b>Water Alternative Source Name</b> From <b>Water Alternative Source Code</b>
 * @param type $water_source_code
 * @return type
 */
function getWaterAlterSourceNameFromCode($water_source_code) {
    if(!$water_source_code > 0){
        return "";
    }
    $sql = "SELECT `water_supply_source_name` FROM `org_source_of_water_supply_main` WHERE `water_supply_source_code` = \"$water_source_code\" LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getWaterAlterSourceNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['water_supply_source_name'];
}


/**
 * Get <b>Toilet Type Name</b> From <b>Toilet Type Code</b>
 * @param type $toilet_type_code
 * @return type
 */
function getToiletTypeNameFromCode($toilet_type_code) {
    if(!$toilet_type_code > 0){
        return "";
    }
    $sql = "SELECT `toilet_type_name` FROM `org_toilet_type` WHERE `toilet_type_code` = \"$toilet_type_code\" LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getToiletTypeNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['toilet_type_name'];
}


/**
 * Get <b>Toilet Adequacy Name</b> From <b>Toilet Adequacy Code</b>
 * @param type $toilet_adequacy_code
 * @return type
 */
function getToiletAdequacyNameFromCode($toilet_adequacy_code) {
    if(!$toilet_adequacy_code > 0){
        return "";
    }
    $sql = "SELECT `toilet_adequacy_name` FROM `org_toilet_adequacy` WHERE `toilet_adequacy_code` = \"$toilet_adequacy_code\" LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getToiletAdequacyNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['toilet_adequacy_name'];
}

function getOrgOwnarshioNameFromCode($org_ownarship_code) {
    if(!$org_ownarship_code > 0){
        return "";
    }
    $sql = "SELECT                
                org_ownership_authority.org_ownership_authority_name
            FROM
                org_ownership_authority
            WHERE
                org_ownership_authority.org_ownership_authority_code = $org_ownarship_code LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getOrgOwnarshioNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);

    return $data['org_ownership_authority_name'];
}

/**
 * Get Organization Location Type Form code
 * @param INT $org_code
 * @return ARRAY
 */
function getOrgLocationTypeFromCode ($org_location_code){
    if (!$org_location_code > 0) {
        return null;
    }
    $sql = "SELECT
                    org_location_type_name
            FROM
                    org_location_type
            WHERE
                    org_location_type_code = $org_location_code
            AND active LIKE 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getOrgLocationTypeFromCode:1</p><p>Query:</b></p>___<p>$sql</p>");
    
    $data = mysql_fetch_assoc($result);
    
    return $data['org_location_type_name'];
}

function getTypeOfPostNameFromCode($type_of_post_code) {
    if ($type_of_post_code <= 0) {
        return null;
    }
    $sql = "SELECT
                sanctioned_post_type_of_post.type_of_post_name
            FROM
                sanctioned_post_type_of_post
            WHERE
                sanctioned_post_type_of_post.type_of_post_code = $type_of_post_code
            LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>getTypeOfPostNameFromCode:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

    $data = mysql_fetch_assoc($result);
    return $data['type_of_post_name'];
}

/**
 * Get total number of organizations 
 * @return STRING org_count
 */
function getTotalOrgCount(){
    $sql = "SELECT  org_code FROM organization WHERE active LIKE 1";
    $r = mysql_query($sql) or die(mysql_error() . "<p><b>Code:1 || Query:</b><br />___<br />$sql</p>");
    $org_count = number_format(mysql_num_rows($r));
    
    return $org_count;
}

function getTotalOrgListCount($level, $code_array) {
    if ($level == "div"){
        $code = $code_array['code'];
        $sql = "SELECT
                    count(*) row_count
                FROM
                    `organization`
                WHERE
                    organization.division_code = $code
                AND organization.active LIKE 1 ";
    } else if ($level == "dis"){
        $code = $code_array['code'];
        $sql = "SELECT
                    count(*) row_count
                FROM
                    `organization`
                WHERE
                    organization.district_code = $code
                AND organization.active LIKE 1 ";
    } else if ($level == "upa"){
        $code = $code_array['code'];
        $dis_code = $code_array['dis_code'];
        $sql = "SELECT
                    count(*) row_count
                FROM
                    `organization`
                WHERE
                    organization.upazila_thana_code = $code
                AND organization.district_code = $dis_code            
                AND organization.active LIKE 1 ";
    }
        
    $result = mysql_query($sql) or die(mysql_error() . "<p><b>Code:getTotalOrgListCount || Query:</b><br />___<br />$sql</p>");

    if (mysql_num_rows($result) > 0) {
        $a = mysql_fetch_assoc($result);
        $total_org_count = $a['row_count'];
        return $total_org_count;
    } else {
        return FALSE;
    }
}
function getUnionNameFromBBSCode($union_code, $upazila_code, $district_code){
    if (empty($union_code) || empty($upazila_code) || empty($district_code)) {
        return FALSE;
    }
    
    $sql = "SELECT
                    union_name
            FROM
                    `admin_union`
            WHERE
                    union_code = '$union_code'
            AND upazila_code = '$upazila_code'
            AND district_code = '$district_code'
            LIMIT 1";
    $result = mysql_query($sql) or die(mysql_error() . "<p>getUnionNameFromBBSCode:1</p><p>$sql</p>");
    
    if (mysql_num_rows($result)){
        $data = mysql_fetch_assoc($result);

        return $data['union_name'];
    } else{
        return FALSE;
    }
}