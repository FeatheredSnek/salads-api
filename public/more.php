<?php

include __DIR__ . '/../connection.php';
include __DIR__ . '/../utils/nest.php';

if (isset($_GET['current'])) {
  $current = $_GET['current'];
} else {
  $current = 'null';
}

if (isset($_GET['count'])) {
  $count = $_GET['count'];
  $query = 'SELECT * FROM `recipes` WHERE `id` = ?';
} else {
  $count = 4;
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
WHERE recipes.friendly_id <> ?
ORDER BY RAND() LIMIT ?
';

$stmt = mysqli_prepare($con, $query);
mysqli_stmt_bind_param($stmt, 'si', $current, $count);
mysqli_stmt_execute($stmt);
$query_result = mysqli_stmt_get_result($stmt);
$response = array();
while ($row = mysqli_fetch_assoc($query_result)) {
  array_push($response, nest_flags($row));
};

if ($query_result == null || count($response) == 0) {
  $response = 'no recipes found';
  http_response_code(404);
  echo json_encode($response);
} else {
  http_response_code(200);
  echo json_encode($response);
}

exit;

?>
