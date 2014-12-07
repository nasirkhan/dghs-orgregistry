<?php
require_once 'configuration.php';

$start_time = microtime(true);

if (isset($_GET['org_code']) && $_GET['org_code'] != "") {
    $org_code = (int) mysql_real_escape_string(trim($_GET['org_code']));
    $org_name = getOrgNameFormOrgCode($org_code);
    $org_type_name = getOrgTypeNameFormOrgCode($org_code);
    $echoAdminInfo = " | Administrator";
    $isAdmin = TRUE;
}

$sql = "SELECT
            *
        FROM
            `dghshrml4_facilities`
        WHERE
            `code` = '$org_code'
        AND is_active = 1  LIMIT 1";
$result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>sql:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

// data fetched form organization table
$data = mysql_fetch_assoc($result);


/**
 * check if the organization type is Community clinic or not
 */
$org_type_code = getOrgTypeCodeFromOrgCode($org_code);
// Community Clinic type code is 1039
if ($org_type_code == 1039) {
    $isCommunityClinic = TRUE;
} else {
    $isCommunityClinic = FALSE;
}

$showSanctionedBed = showSanctionedBed($org_type_code);


$latitude = $data['latitude'];
$longitude = $data['longitude'];
$coordinate = $longitude . "," . $latitude;
if (!($latitude > 0) || !($longitude > 0)) {
    $map_popup = "";
} else {
    $map_popup = $org_name;
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
        <link rel="stylesheet" href="library/leaflet-0.6.4/leaflet.css" />        
        <!--[if lte IE 8]>
            <link rel="stylesheet" href="library/leaflet-0.6.4/leaflet.ie.css" />
        <![endif]-->
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

                <div class="col-md-12">
                    <!--<h2><?php echo "$org_name"; ?></h2>-->
                    <div class="page-header">
                        <h1><?php echo "$org_name"; ?></h1>
                        <h3><?php echo "<em>Org Code: $org_code</em>"; ?></h3>
                        <!--<h3><?php echo "$org_type_name ($org_type_code)"; ?></h3>-->
                    </div>

                    <!-- Nav tabs -->


                    <ul class="nav nav-tabs nav-tab-ul">
                        <li class="active">
                            <a href="#org-profile-home" data-toggle="tab"><i class="fa fa-qrcode"></i> At a glance</a>
                        </li>
                        <li class="">
                            <a href="#basic-info" data-toggle="tab"><i class="fa fa-hospital"></i> Basic Information</a>
                        </li>
                        <li class="">
                            <a href="#ownership-info" data-toggle="tab"><i class="fa fa-building"></i> Ownership Info</a>
                        </li>
                        <li class="">
                            <a href="#contact-info" data-toggle="tab"><i class="fa fa-envelope"></i> Contact Info</a>
                        </li>
                        <li class="">
                            <a href="#land-info" data-toggle="tab"><i class="fa fa-th-list"></i> Land Info</a>
                        </li>
                        <li class="">
                            <a href="#permission_approval-info" data-toggle="tab"><i class="fa fa-qrcode"></i> Permission/Approval Info</a>
                        </li>                            
                        <li class="">
                            <a href="#facility-info" data-toggle="tab"><i class="fa fa-shield"></i> Facility Info</a>
                        </li>
						  <li class="">
                            <a href="#other-info" data-toggle="tab"><i class="fa fa-book"></i> Other miscellaneous issues</a>
                        </li>
                        <!-- 
                        <li class="">
                            <a href="#org-hrm-status" data-toggle="tab"><i class="fa fa-group"></i> HRM Status</a>
                        </li>
                         -->
                    </ul>
                    <div class="tab-content">
                        <div class="tab-pane active" id="org-profile-home">
                            <div class="panel panel-default">
                                <div class="panel-body">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <?php
                                            $image_src = $data['org_photo'];
                                            $image_src = $hrm_root_dir . "/uploads/$image_src";

//                                          echo "$image_src";
                                            if ($data['org_photo'] != "") {
                                                echo "<img src=\"$image_src\" class=\"img-thumbnail\" />";
                                            } else {
                                                echo "<img data-src=\"holder.js/480x360\"  class=\"img-thumbnail\" />";
                                            }
                                            ?>
                                        </div>
                                        <div class="col-md-6">
                                            <div id="map" style="height: 350px"></div>
                                        </div>
                                    </div>
                                    <div class="row">
                                        <p class="lead"></p>                                        
                                    </div>
                                    <div class="row">
                                        <div class="col-md-12">
                                            <table class="table table-striped table-hover table-bordered">
                                                <tr>
                                                    <td width="50%"><strong>Organization Name</strong></td>
                                                    <td><?php echo "$org_name"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>Organization Type</strong></td>
                                                    <td><?php echo "$org_type_name"; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>Division Name</strong></td>
                                                    <td width="50%"><?php echo $data['division_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>District Name</strong></td>
                                                    <td width="50%"><?php echo $data['district_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>Upazilla Name</strong></td>
                                                    <td width="50%"><?php echo getUpazilaNamefromCode($data['upazila_thana_code'], $data['district_code']); ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>Union Name</strong></td>
                                                    <td width="50%"><?php echo $data['union_name']; ?></td>
                                                </tr>
                                                <tr>
                                                    <td width="50%"><strong>Email Address</strong></td>
                                                    <td width="50%"><?php echo $data['email1']; ?></td>
                                                </tr>                                                
                                                <tr>
                                                    <td width="50%"><strong>Mobile Number</strong></td>
                                                    <td width="50%"><?php echo $data['mobile1']; ?></td>
                                                </tr>                                                
                                                <?php if($showSanctionedBed): ?>
                                                    <tr>
                                                        <td width="50%"><strong>Approved Bed No.</strong></td>
                                                        <td><?php echo $data['approved_bed_number']; ?></td>
                                                    </tr>
													<tr>
                                                        <td width="50%"><strong>Revenue Bed No</strong></td>
                                                        <td><?php echo $data['revenue_bed_number']; ?></td>
                                                    </tr>
														<tr>
                                                        <td width="50%"><strong>Development Bed No</strong></td>
                                                        <td><?php echo $data['development_bed_number']; ?></td>
                                                    </tr>
                                                <?php endif; ?>
                                                <tr>
                                                    <td width="50%"><strong>Last updated on</strong></td>
                                                    <td width="50%"><?php echo $data['updated_at']; ?></td>
                                                </tr>
                                            </table>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="tab-pane" id="basic-info">
                            <table class="table table-striped table-hover table-bordered">
                                <tr>
                                    <td width="50%"><strong>Organization Name</strong></td>
                                    <td><?php echo "$org_name"; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Organization Code</strong></td>
                                    <td><?php echo $data['code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Agency code</strong></td>
                                    <td><?php echo $data['facilityagency_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Agency Name</strong></td>
                                    <td><?php echo $data['facilityagency_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Financial Code (Revenue Code)</strong></td>
                                    <td><?php echo $data['financial_revenue_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Year Established</strong></td>
                                    <td><?php echo $data['establishmentyear']; ?></td>
                                </tr>
                                <tr  class="success">
                                    <td width="50%" colspan="2"><strong>Urban/Rural Location Information of the Organization</strong></td>
                                    <!--<td><?php // echo $data['org_code'];           ?></td>-->
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Urban/Rural Location</strong></td>
                                    <td><?php // echo $data['org_code'];           ?></td>
                                </tr>
                                <tr  class="success">
                                    <td width="50%" colspan="2"><strong>Regional location of the organization</strong></td>
                                    <!--<td><?php // echo $data['org_code'];           ?></td>-->
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Division Name</strong></td>
                                    <td><?php echo $data['division_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Division Code</strong></td>
                                    <td><?php echo $data['division_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>District Name</strong></td>
                                    <td><?php echo $data['district_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>District Code</strong></td>
                                    <td><?php echo $data['district_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Upazila Name</strong></td>
                                    <td><?php echo $data['upazila_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Upazila Code</strong></td>
                                    <td><?php echo $data['upazila_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Union Name</strong></td>
                                    <td><?php echo $data['union_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Union Code</strong></td>
                                    <td><?php echo $data['union_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Ward</strong></td>
                                    <td><?php echo $data['ward_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Village/Street</strong></td>
                                    <td><?php echo $data['village_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>House No</strong></td>
                                    <td><?php echo $data['house_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Longitude</strong></td>
                                    <td><a href="#" class="text-input" id="longitude" ><?php echo $data['longitude']; ?></a></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Latitude</strong></td>
                                    <td><a href="#" class="text-input" id="latitude" ><?php echo $data['latitude']; ?></a></td>
                                </tr>
                            </table>  
                        </div>
                        <div class="tab-pane" id="ownership-info">
                            <table class="table table-striped table-hover table-bordered">
                                <tr>
                                    <td width="50%"><strong>Ownership</strong></td>
                                    <td width="50%"><?php echo $data['facilityownership_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Organization Type</strong></td>
                                    <td width="50%"><?php echo $data['facilitytype_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Organization Function</strong></td>
                                    <td width="50%"><?php echo $data['facilityfunction_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Organization Level</strong></td>
                                    <td width="50%"><?php echo $data['facilitylevel_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Health Care Level</strong></td>
                                    <td width="50%"><?php echo $data['facilityhealthcarelevel_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Special service / status of the hospital / clinic</strong></td>
                                    <td width="50%"></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane" id="permission_approval-info">
                            <table class="table table-striped table-hover table-bordered">
                                <!--
                                <tr>
                                    <td width="60%"><strong>Special service / status of the hospital / clinic</strong></td>
                                    <td><?php // echo $data['org_code'];         ?></td>
                                </tr>
                                -->

                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Permission/Approval/License information</strong></td>
                                </tr>

                                <tr>
                                    <td width="60%"><strong>Date of Permission/Approval/License information</strong></td>
                                    <td><?php echo $data['permission_approval_license_info_date']; ?></td>
                                </tr>
                                <tr>
                                    <td width="60%"><strong>Permission/Approval/License Type</strong></td>
                                    <td><?php echo $data['permission_approval_license_type']; ?></td>
                                </tr>
                                <tr>
                                    <td width="60%"><strong>Permission/ Approval/ License Authority</strong></td>
                                    <td><?php echo $data['permission_approval_license_aithority']; ?></td>
                                </tr>
                                <tr>
                                    <td width="60%"><strong>Permission/ Approval/ License No</strong></td>
                                    <td><?php echo $data['permission_approval_license_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="60%"><strong>Next renewal Date</strong></td>
                                    <td><?php echo $data['permission_approval_license_next_renewal_date']; ?></td>
                                </tr>
                                <tr>
                                    <td width="60%"><strong>Conditions given for permission/ approval/ license or renewal thereof </strong></td>
                                    <td><?php echo $data['permission_approval_license_conditions']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <div class="tab-pane" id="contact-info">
                            <table class="table table-striped table-hover table-bordered">
                                <tr>
                                    <td width="50%"><strong>Mailing Address</strong></td>
                                    <td><?php echo $data['mailing_address']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Land Phone Number 1</strong></td>
                                    <td><?php echo $data['landphone1']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Land Phone Number 2</strong></td>
                                    <td><?php echo $data['landphone2']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Land Phone Number 3</strong></td>
                                    <td><?php echo $data['landphone3']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mobile Phone Number 1</strong></td>
                                    <td><?php echo $data['mobile1']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mobile Phone Number 2</strong></td>
                                    <td><?php echo $data['mobile2']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mobile Phone Number 3</strong></td>
                                    <td><?php echo $data['mobile3']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Email Address 1</strong></td>
                                    <td><?php echo $data['email1']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Email Address 2</strong></td>
                                    <td><?php echo $data['email2']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Email Address 3</strong></td>
                                    <td><?php echo $data['email3']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Fax Number 1</strong></td>
                                    <td><?php echo $data['fax1']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Fax Number 2</strong></td>
                                    <td><?php echo $data['fax2']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Fax Number 3</strong></td>
                                    <td><?php echo $data['fax3']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Website URL</strong></td>
                                    <td><?php echo $data['websiteurl']; ?></td>
                                </tr>
                                <!--
                                <tr>
                                    <td><strong>Website2</strong></td>
                                    <td><?php // echo $data['org_code'];         ?></td>
                                </tr>
                                <tr>
                                    <td><strong>Website3</strong></td>
                                    <td><?php // echo $data['district_code'];         ?></td>
                                </tr>
                                -->
                                <tr>
                                    <td width="50%"><strong>Facebook</strong></td>
                                    <td><?php echo $data['facebookurl']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Google+</strong></td>
                                    <td><?php echo $data['googleplusurl']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Twitter</strong></td>
                                    <td><?php echo $data['twitterurl']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Youtube</strong></td>
                                    <td><?php echo $data['youtubeurl']; ?></td>
                                </tr>
                                <?php if ($isCommunityClinic): ?>
                                    <tr class="success">
                                        <td><strong>Additional Information</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Name of CHCP</strong></td>
                                        <td><?php echo $data['additional_chcp_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Contact no of CHCP</strong></td>
                                        <td><?php echo $data['additional_chcp_contact']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Community group information</strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Name of Chairman (CG)</strong></td>
                                        <td><?php echo $data['additional_chairnam_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Contact No (CG)</strong></td>
                                        <td><?php echo $data['additional_chairman_contact']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Community Support group information </strong></td>
                                        <td></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Name of Chairman (CSG-1)</strong></td>
                                        <td><?php echo $data['additional_csg_1_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Contact No (CSG-1)</strong></td>
                                        <td><?php echo $data['additional_csg_1_contact']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Name of Chairman (CSG-2)</strong></td>
                                        <td><?php echo $data['additional_csg_2_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td width="50%"><strong>Contact No (CSG-2)</strong></td>
                                        <td><?php echo $data['additional_csg_2_contact']; ?></td>
                                    </tr>
                                <?php endif; ?>
                            </table>
                        </div>
                        <div class="tab-pane" id="facility-info">
                            <table class="table table-striped table-hover table-bordered table-bordered">
							     <tr>
                                    <td width="50%">Physical Structure</td>
                                    <td><?php
									echo getPhysicalStructure($data['physical_structure_value']); ?></td>
                                </tr>
								
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Source of Electricity</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Main source of electricity</td>
                                    <td><?php echo getElectricityMainSourceNameFromCode($data['main_electricitysourceoption_code']); ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Alternate source of electricity</td>
                                    <td><?php echo getElectricityAlterSourceNameFromCode($data['alt_electricitysourceoption_code']); ?></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Source of water Supply</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Main water supply</td>
                                    <td><?php echo getWaterMainSourceNameFromCode($data['main_watersourceoption_code']); ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Alternate water supply</td>
                                    <td><?php echo getWaterAlterSourceNameFromCode($data['alt_watersourceoption_code']); ?></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Toilet Facility</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Toilet type</td>
                                    <td><?php echo getToiletTypeNameFromCode($data['toiletoption_code']); ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Toilet adequacy</td>
                                    <td><?php echo getToiletAdequacyNameFromCode($data['toiletadequacytype_code']); ?></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Fuel Source</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Fuel source</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Laundry System</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Laundry system</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Autoclave System</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Autoclave System</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Waste Disposal System</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Waste disposal</td>
                                    <td></td>
                                </tr>
                                <tr class="success">
                                    <td width="50%" colspan="2"><strong>Sanctioned Assets</strong></td>
                                </tr>
                                <tr>
                                    <td width="50%">Sanctioned Office equipment</td>
                                    <td><?php echo $data['sanctioned_office_equipment']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%">Sanctioned vehicles</td>
                                    <td><?php echo $data['sanctioned_vehicles']; ?></td>
                                </tr>
                                <?php if($showSanctionedBed): ?>
                                                    <tr>
                                                        <td width="50%"><strong>Approved Bed No.</strong></td>
                                                        <td><?php echo $data['approved_bed_number']; ?></td>
                                                    </tr>
													<tr>
                                                        <td width="50%"><strong>Revenue Bed No</strong></td>
                                                        <td><?php echo $data['revenue_bed_number']; ?></td>
                                                    </tr>
														<tr>
                                                        <td width="50%"><strong>Development Bed No</strong></td>
                                                        <td><?php echo $data['development_bed_number']; ?></td>
                                                    </tr>
                                 <?php endif; ?>
                              <!--  <tr>
                                    <td width="50%">Other miscellaneous issues</td>
                                    <td><?php echo $data['other_miscellaneous_issues']; ?></td>
                                </tr> -->
                            </table>
                        </div>
                        <div class="tab-pane" id="land-info">
                            <table class="table table-striped table-hover table-bordered">
                                <!--
                                <tr>
                                    <td width="50%"><strong>Land Information</strong></td>
                                    <td><?php echo $data['land_info_code']; ?></td>
                                </tr>
                                -->
                                <tr>
                                    <td width="50%"><strong>Land size (in decimal)</strong></td>
                                    <td><?php echo $data['land_size_decimal']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mouza name</strong></td>
                                    <td><?php echo $data['land_mouza_name']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Geocode of Mouza</strong></td>
                                    <td><?php echo $data['land_mouza_geo_code']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>JL No.</strong></td>
                                    <td><?php echo $data['land_jl_number']; ?></td>
                                </tr>
                                <!--
                                <tr>
                                    <td width="50%"><strong>Functional Code</strong></td>
                                    <td><?php echo $data['land_functional_code']; ?></td>
                                </tr>
                                -->
                                <tr>
                                    <td width="50%"><strong>SA Dag No</strong></td>
                                    <td><?php echo $data['land_ss_dag_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>RS Dag No</strong></td>
                                    <td><?php echo $data['land_rs_dag_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Khatian No.</strong></td>
                                    <td><?php echo $data['land_kharian_number']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Mutation No.</strong></td>
                                    <td><?php echo $data['land_other_info']; ?></td>
                                </tr>
                                <tr>
                                    <td width="50%"><strong>Other land information.</strong></td>
                                    <td><?php echo $data['land_mutation_number']; ?></td>
                                </tr>
                            </table>
                        </div>
                        <!--
                        <div class="tab-pane" id="org-hrm-status">
                            <div class="panel panel-default">
                                <div class="panel-body">                                    
                                    <div class="row">
                                        <div class="col-md-12">
                                            <p>Use the following link to get the Human Resource Management System (HRM) Summery status.</p>                                        
                                            <blockquote>
                                                <p><a href="hrm_status.php?org_code=<?php echo $org_code; ?>">View HRM Status of "<?php echo $org_name; ?>"</a></p>
                                            </blockquote>
                                        </div>                                        
                                    </div>                                    
                                </div>
                            </div>
                        </div>
                        -->
						      <div class="tab-pane" id="other-info">
                               <table class="table table-striped table-hover table-bordered">
							   <tr>
                                    <td width="50%">Other miscellaneous issues</td>
                                    <td><?php echo $data['other_miscellaneous_issues']; ?></td>
                                </tr>
							   </table>
							   </div>
						
						
                    </div>
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
        <script src="js/holder/holder.js"></script>

        <script src="library/leaflet-0.6.4/leaflet.js"></script>

        <script>
            $(function() {
                $('.nav-tab-ul #org-profile-home').tab('show');
            });
        </script>
        <script>

            var map = L.map('map').setView([<?php echo $coordinate; ?>], 7);

            L.tileLayer('http://{s}.tile.cloudmade.com/BC9A493B41014CAABB98F0471D759707/997/256/{z}/{x}/{y}.png', {
                maxZoom: 18,
                attribution: 'Map data &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors, <a href="http://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="http://cloudmade.com">CloudMade</a>'
            }).addTo(map);


            L.marker([<?php echo $coordinate; ?>]).addTo(map)
                    .bindPopup("<?php echo "$map_popup"; ?>").openPopup();




            var popup = L.popup();

            function onMapClick(e) {
                popup
                        .setLatLng(e.latlng)
                        .setContent("You clicked the map at " + e.latlng.toString())
                        .openOn(map);
            }

            map.on('click', onMapClick);

        </script>

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php'; ?>

        <?php
        /*
         * ******************************
         * 
         * Calculate the execution time
         * 
         * *******************************
         */
        $time_end = microtime(true);

        //dividing with 60 will give the execution time in minutes other wise seconds
        $execution_time = ($time_end - $start_time);
        
        $showExecutionTime = FALSE;
        ?>
        <?php if($showExecutionTime): ?>
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <p class="text-center">
                        <code>Execution time: <?php echo "$execution_time sec"; ?></code>
                    </p>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </body>
</html>
