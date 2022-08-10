<?php

include __DIR__ . '/../connection.php';
include __DIR__ . '/../utils/nest.php';


if (isset($_GET['ids'])) {
  $ids_array = explode(',', $_GET['ids'], 10);
} else {
  $response = array();
  http_response_code(200);
  echo json_encode($response);
  die;
}

$query = '
SELECT 
  recipes.name, 
  recipes.blurb, 
  recipes.image, 
  recipes.friendly_id,
  recipes.flag_vegan,
  recipes.flag_vegetarian,
  recipes.flag_maindish,
  recipes.flag_hot,
  writers.name AS `writer`
FROM recipes
LEFT JOIN writers
ON recipes.writer_id = writers.id
WHERE recipes.friendly_id = ?
';

$response = array();

foreach ($ids_array as $key => $value) {
  $stmt = mysqli_prepare($con, $query);
  mysqli_stmt_bind_param($stmt, 's', $value);
  mysqli_stmt_execute($stmt);
  $query_result = mysqli_stmt_get_result($stmt);
  while ($row = mysqli_fetch_assoc($query_result)) {
    array_push($response, nest_flags($row));
  };
}

http_response_code(200);
echo json_encode($response);
exit;

?>
