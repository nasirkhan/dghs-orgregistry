<?php

require_once '../configuration.php';

$dis_code = mysql_real_escape_string($_POST['dis_code']);
$upa_code = mysql_real_escape_string($_POST['upa_code']);

$sql = "SELECT union_name, union_code FROM `admin_union` WHERE upazila_code = $upa_code AND district_code = $dis_code";
$result = mysql_query($sql) or die(mysql_error() . "<p>Code:get_unions:1</p><p>Query:<br />___<br />$sql</p>");

$data = array();
$data[] = array(
    'text' => "__ Select Union __",
    'value' => 0
);
while ($row = mysql_fetch_array($result)) {
    $data[] = array(
        'text' => $row['union_name'],
        'value' => $row['union_code']
    );
}
$json_data = json_encode($data);

print_r($json_data);
?>
