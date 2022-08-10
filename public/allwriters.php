<?php

include __DIR__ . '/../connection.php';

$query = 'SELECT friendly_id FROM writers WHERE 1=1 ORDER BY friendly_id';
$response = array();
$result = mysqli_query($con, $query);
while ($row = mysqli_fetch_row($result)) {
  array_push($response, $row[0]);
}
http_response_code(200);
echo json_encode($response);
exit;
