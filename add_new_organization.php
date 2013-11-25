<?php
require_once 'configuration.php';

$org_name = mysql_real_escape_string(trim($_REQUEST['org_name']));
$org_type = (int) mysql_real_escape_string(trim($_REQUEST['org_type']));
$org_agency = (int) mysql_real_escape_string(trim($_REQUEST['org_agency']));
$year_established = (int) mysql_real_escape_string(trim($_REQUEST['year_established']));
$org_location = (int) mysql_real_escape_string(trim($_REQUEST['org_location']));
$org_division = (int) mysql_real_escape_string(trim($_REQUEST['org_division']));
$org_district = (int) mysql_real_escape_string(trim($_REQUEST['org_district']));
$org_upazila = (int) mysql_real_escape_string(trim($_REQUEST['org_upazila']));
$org_ownership = (int) mysql_real_escape_string(trim($_REQUEST['org_ownership']));
$org_function = (int) mysql_real_escape_string(trim($_REQUEST['org_function']));
$org_level = (int) mysql_real_escape_string(trim($_REQUEST['org_level']));
$org_email = mysql_real_escape_string(trim($_REQUEST['org_email']));
$org_contact_number = mysql_real_escape_string(trim($_REQUEST['org_contact_number']));
$form_submit = (int) mysql_real_escape_string(trim($_REQUEST['form_submit']));

if ($form_submit == 1){
    require_once('./library/recaptcha-php-1.11/recaptchalib.php');
    $privatekey = "6Lf8VtoSAAAAANfOj7Q6iAsmOjV_U8mvj5XQoYs3";
    $resp = recaptcha_check_answer ($privatekey,
                                    $_SERVER["REMOTE_ADDR"],
                                    $_POST["recaptcha_challenge_field"],
                                    $_POST["recaptcha_response_field"]);

    if (!$resp->is_valid) {
        $captcha_passed = FALSE;
    } else {
//        echo "<pre>";
//        print_r($_REQUEST);
//        echo "</pre>";
        if($org_name != "" 
            && $org_type > 0 
            && $org_agency > 0 
            && $year_established > 0 
            && $org_division > 0 
            && $org_district > 0     
            && $org_upazila > 0     
            && $org_ownership > 0 
            && $org_function > 0  
            && $org_level > 0
            && $org_email != "" 
            && $org_contact_number != ""){
            $sql = "INSERT INTO `organization_requested` ("
                    . "`org_name`, "
                    . "`org_code`, "
                    . "`org_type_code`, "
                    . "`agency_code`, "
                    . "`org_function_code`, "
                    . "`org_level_code`, "
                    . "`org_level_name`, "
                    . "`year_established`, "
                    . "`org_location_type`, "
                    . "`division_code`, "
                    . "`division_name`, "
                    . "`district_code`, "
                    . "`district_name`, "
                    . "`upazila_thana_code`, "
                    . "`upazila_thana_name`, "
                    . "`latitude`, "
                    . "`longitude`, "
                    . "`ownership_code`, "
                    . "`mobile_number1`, "
                    . "`email_address1`, "
                    . "`updated_by`) VALUES ("
                    . "\"$org_name\", "
                    . "'0', "
                    . "'$org_type', "
                    . "'$org_agency', "
                    . "'$org_function', "
                    . "'$org_level', "
                    . "\"" . getOrgLevelNameFromCode($org_level) . "\", "                    
                    . "'$year_established', "
                    . "'$org_location', "
                    . "'$org_division', "
                    . "\"" . getDivisionNameFromCode($org_division) . "\", "
                    . "'$org_district', "
                    . "\"" . getDistrictNameFromCode($org_district) . "\", "
                    . "'$org_upazila', "
                    . "\"" . getUpazilaNamefromCode($org_upazila, $org_district) . "\", "                    
                    . "'$latitude', "
                    . "'$longitude', "
                    . "'$org_ownership', "
                    . "\"" . $org_contact_number . "\", "
                    . "\"" . $org_email . "\", "
                    . "'nasirkhan') ";
            $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>insert_new_org:1<br />Query:</b><br />___<br />$sql</p>");
            $captcha_passed = TRUE;
//            echo "ok";
            
            unset_all_values();
        }        
    }
}
    

function unset_all_values(){
    unset($_REQUEST);
    $org_name = "";
    $org_type = 0;
    $org_agency = 0;
    $year_established = 0;
    $org_location = 0;
    $org_division = 0;
    $org_district = 0;
    $org_upazila = 0;
    $org_ownership = 0;
    $org_function = 0;
    $org_level = 0;
    $org_email = "";
    $org_contact_number = "";
    $form_submit = 0;
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
                        <li class="active"><a href="add_new_organization.php"><i class="fa fa-pencil fa-lg"></i> Apply for registration</a></li>
                        <li><a href="report.php"><i class="fa fa-calendar-o fa-lg"></i> Report Dashboard</a></li>
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
                    <h2>Apply for new organization</h2>
                    <script type="text/javascript">
                    var RecaptchaOptions = {
                       theme : 'clean'
                    };
                    
                    </script>
                    <?php if ($form_submit == 1) :?>
                        <?php if ($captcha_passed) :?>
                        <div class="alert alert-success">
                            Thank you for submitting new organization. 
                            We will inform you when the organization will be added to the <em>Organization Registry</em>
                        </div>
                        <?php endif; ?>
                        <?php if (!$captcha_passed) :?>
                        <div class="alert alert-danger">
                            You made some mistake. Please fill the form correctly and submit again.
                        </div>
                        <?php endif; ?>
                    <?php endif; ?>
                    <form class="form-horizontal" role="form" action="" method="POST">
                        <div class="form-group">
                            <label for="org_name" class="col-md-3 control-label">Organization Name</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" id="org_name" name="org_name" placeholder="Organization Name">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_type" class="col-md-3 control-label">Organization Type</label>
                            <div class="col-md-6">
                                <select id="org_type" name="org_type" class="form-control">
                                    <option value="0">__ Select an organization type __</option>
                                    <?php
                                    $sql = "SELECT `org_type_name`, `org_type_code` FROM `org_type` ORDER BY org_type_name";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['org_type_code']; ?>"><?php echo $row['org_type_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_agency" class="col-md-3 control-label">Agency Name</label>
                            <div class="col-md-6">
                                <select id="org_agency" name="org_agency" class="form-control">
                                    <option value="0">__ Select Agency Name __</option>
                                    <?php
                                    $sql = "SELECT `org_agency_code`, `org_agency_name` FROM `org_agency_code` ORDER BY org_agency_name";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['org_agency_code']; ?>"><?php echo $row['org_agency_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="year_established" class="col-md-3 control-label">Year Established</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="year_established" id="year_established" placeholder="Write the year only">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_location" class="col-md-3 control-label">Urban/Rural Location</label>
                            <div class="col-md-6">
                                <select id="org_location" name="org_location" class="form-control">
                                    <option value="0">__ Select Organization Location __</option>
                                    <?php
                                    $sql = "SELECT `org_location_type_code`, `org_location_type_name` FROM `org_location_type` ORDER BY org_location_type_name";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['org_location_type_code']; ?>"><?php echo $row['org_location_type_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_division" class="col-md-3 control-label">Division</label>
                            <div class="col-md-6">
                                <select id="org_division" name="org_division" class="form-control">
                                    <option value="0">__ Select Division __</option>
                                    <?php
                                    $sql = "SELECT division_name, division_bbs_code FROM admin_division";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['division_bbs_code']; ?>"><?php echo $row['division_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_district" class="col-md-3 control-label">District</label>
                            <div class="col-md-6">
                                <select id="org_district" name="org_district" class="form-control">
                                    <option value="0">__ Select District __</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_upazila" class="col-md-3 control-label">Upazila</label>
                            <div class="col-md-6">
                                <select id="org_upazila" name="org_upazila" class="form-control">
                                    <option value="0">__ Select Upazila __</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_ownership" class="col-md-3 control-label">Ownership Information</label>
                            <div class="col-md-6">
                                <select id="org_ownership" name="org_ownership" class="form-control">
                                    <option value="0">__ Select Ownership Type __</option>
                                    <?php
                                    $sql = "SELECT org_ownership_authority_code, org_ownership_authority_name FROM org_ownership_authority ORDER BY org_ownership_authority_name";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['org_ownership_authority_code']; ?>"><?php echo $row['org_ownership_authority_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_function" class="col-md-3 control-label">Organization Function</label>
                            <div class="col-md-6">
                                <select id="org_function" name="org_function" class="form-control">
                                    <option value="0">__ Select Organization Function __</option>
                                    <?php
                                    $sql = "SELECT `org_organizational_functions_code`, `org_organizational_functions_name` FROM `org_organizational_functions` ORDER BY org_organizational_functions_name";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['org_organizational_functions_code']; ?>"><?php echo $row['org_organizational_functions_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_level" class="col-md-3 control-label"> Organization Level</label>
                            <div class="col-md-6">
                                <select id="org_level" name="org_level" class="form-control">
                                    <option value="0">__ Select Organization Level __</option>
                                    <?php
                                    $sql = "SELECT `org_level_code`, `org_level_name` FROM `org_level` ORDER BY org_level_name";
                                    $result = mysql_query($sql) or die(mysql_error() . "<p>Code:<b>get_org_type_name:1<br />Query:</b><br />___<br />$sql</p>");
                                    if (mysql_num_rows($result) > 0):
                                        while ($row = mysql_fetch_assoc($result)):
                                            ?>
                                            <option value="<?php echo $row['org_level_code']; ?>"><?php echo $row['org_level_name']; ?></option>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </select>
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_email" class="col-md-3 control-label">Organization Email</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="org_email" id="org_email" placeholder="Email Address"  value="" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_email" class="col-md-3 control-label">Organization Contact Number</label>
                            <div class="col-md-6">
                                <input type="text" class="form-control" name="org_contact_number" id="org_contact_number" placeholder="Mobile Number"  value="" required="">
                            </div>
                        </div>
                        <div class="form-group">
                            <label for="org_email" class="col-md-3 control-label">Secutiry Text (CAPTCHA)</label>
                            <div class="col-md-6">
                                <?php
                                require_once('./library/recaptcha-php-1.11/recaptchalib.php');
                                $publickey = "6Lf8VtoSAAAAAH97FT3J2iwA0ilGqtwYxZOc7o5c"; 
                                echo recaptcha_get_html($publickey);
                                ?>
                            </div>
                        </div>

                        <div class="form-group">
                            <div class="col-md-offset-3 col-md-6">
                                <input type="hidden" name="form_submit" value="1">
                                <button type="submit" class="btn btn-lg btn-success">Submit</button>
                            </div>
                        </div>
                    </form>

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
<!--        <div class="container">
            
            <pre>
                <?php print_r($_REQUEST); ?>
            </pre>
            
        </div>-->

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
            $('#org_division').change(function() {
                $("#loading_content").show();
                var div_code = $('#org_division').val();
                $.ajax({
                    type: "POST",
                    url: 'get/get_districts.php',
                    data: {div_code: div_code},
                    dataType: 'json',
                    success: function(data)
                    {
                        $("#loading_content").hide();
                        var admin_district = document.getElementById('org_district');
                        admin_district.options.length = 0;
                        for (var i = 0; i < data.length; i++) {
                            var d = data[i];
                            admin_district.options.add(new Option(d.text, d.value));
                        }
                    }
                });
            });
            
            // load upazila
            $('#org_district').change(function() {
                var dis_code = $('#org_district').val();
                $("#loading_content").show();
                $.ajax({
                    type: "POST",
                    url: 'get/get_upazilas.php',
                    data: {dis_code: dis_code},
                    dataType: 'json',
                    success: function(data)
                    {
                        $("#loading_content").hide();
                        var admin_upazila = document.getElementById('org_upazila');
                        admin_upazila.options.length = 0;
                        for (var i = 0; i < data.length; i++) {
                            var d = data[i];
                            admin_upazila.options.add(new Option(d.text, d.value));
                        }
                    }
                });
            });
            <?php if(!$captcha_passed): ?>
            $("#org_name").val("<?php echo "$org_name"; ?>");
            $("#org_type").val("<?php echo "$org_type"; ?>");
            $("#org_agency").val("<?php echo "$org_agency"; ?>");
            $("#year_established").val("<?php echo "$year_established"; ?>");            
            $("#org_ownership").val("<?php echo "$org_ownership"; ?>");
            $("#org_function").val("<?php echo "$org_function"; ?>");
            $("#org_level").val("<?php echo "$org_level"; ?>");
            $("#org_email").val("<?php echo "$org_email"; ?>");
            $("#org_contact_number").val("<?php echo "$org_contact_number"; ?>");                        
            <?php endif; ?>


        </script>
        

        <!-- Google Analytics Code-->
        <?php include_once 'include/ga_code.php'; ?>
    </body>
</html>
