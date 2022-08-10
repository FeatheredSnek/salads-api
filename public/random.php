<?php

include __DIR__ . '/../connection.php';

$query = 'SELECT * FROM `recipes` ORDER BY RAND() LIMIT 1';
$result = mysqli_query($con, $query);
$data = mysqli_fetch_assoc($result);
$response["success"] = true;
$response["recipe"] = $data;
echo json_encode($response);
exit;

?>