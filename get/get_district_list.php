<?php

require_once '../configuration.php';

$div_id = mysql_real_escape_string(trim($_POST['div_id']));

$sql = "SELECT
                `id`,
                `name`,
                `code`
        FROM
                `dghshrml4_districts`
        WHERE
                `dghshrml4_districts`.division_id = $div_id
        ORDER BY
                `name`";
$result = mysql_query($sql) or die(mysql_error() . "<br /><br />Code:<b>get_district_list:1</b><br /><br /><b>Query:</b><br />___<br />$sql<br />");

$data = array();
$data[] = array(
    'text' => "Select District",
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
