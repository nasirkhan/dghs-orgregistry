<?php

require_once '../configuration.php';

$dis_id = mysql_real_escape_string($_POST['dis_id']);

$sql = "SELECT
                `id`,
                `name`,
                `code`
        FROM
                `dghshrml4_upazilas`
        WHERE
                district_id = '$dis_id'
        ORDER BY
                `name`";
$result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_upazila_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

$data = array();
$data[] = array(
    'text' => "Select Upazila",
    'value' => 0
);
while ($row = mysql_fetch_array($result)) {
    $data[] = array(
        'text' => $row['name'],
        'value' => $row['id']
    );
}
$json_data = json_encode($data);

print_r($json_data);
?>
