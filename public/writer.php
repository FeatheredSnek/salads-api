<?php

include __DIR__ . '/../connection.php';
include __DIR__ . '/../utils/nest.php';

if (isset($_GET['id'])) {
  $writer_id = $_GET['id'];
  $writer_query = 'SELECT * FROM `writers` WHERE `id` = ?';
} elseif (isset($_GET['friendly_id'])) {
  $writer_id = $_GET['friendly_id'];
  $writer_query = 'SELECT * FROM `writers` WHERE `friendly_id` = ?';
} else {
  $response = 'no writer id';
  http_response_code(400);
  echo json_encode($response);
  exit;
};

$writer_stmt = mysqli_prepare($con, $writer_query);
mysqli_stmt_bind_param($writer_stmt, 's', $writer_id);
mysqli_stmt_execute($writer_stmt);
$writer_query_result = mysqli_stmt_get_result($writer_stmt);
$writer_data = mysqli_fetch_assoc($writer_query_result);

if ($writer_data != null) {
  $response = nest_socials($writer_data);
  http_response_code(200);
  echo json_encode($response);
} else {
  $response = 'writer not found';
  http_response_code(404);
  echo json_encode($response);
};

exit;

?>
