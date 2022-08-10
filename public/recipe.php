<?php

include __DIR__ . '/../connection.php';
include __DIR__ . '/../utils/nest.php';

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $query = 'SELECT * FROM `recipes` WHERE `id` = ?';
} elseif (isset($_GET['friendly_id'])) {
  $id = $_GET['friendly_id'];
  // $query = 'SELECT * FROM `recipes` WHERE `friendly_id` = ?';
  $query = '
  SELECT 
    recipes.*, 
    writers.name AS `writer`,
    writers.friendly_id AS `writer_friendly_id`
  FROM recipes
  LEFT JOIN writers
  ON recipes.writer_id = writers.id
  WHERE recipes.friendly_id = ?
  ';
} else {
  $query = 'SELECT * FROM `recipes` ORDER BY RAND() LIMIT 1';
  $result = mysqli_query($con, $query);
  $data = mysqli_fetch_assoc($result);
  $response["success"] = true;
  $response['body'] = nest_flags($data);
  http_response_code(200);
  echo json_encode($response);
  exit;
};

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 's', $id);
mysqli_stmt_execute($stmt);
$query_result = mysqli_stmt_get_result($stmt);
$data = mysqli_fetch_assoc($query_result);

if ($data != null) {
  $response = nest_flags($data);
  http_response_code(200);
  echo json_encode($response);
} else {
  $response = 'recipe not found';
  http_response_code(404);
  echo json_encode($response);
};

exit;

?>