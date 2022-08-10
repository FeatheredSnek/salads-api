<?php

include __DIR__ . '/../connection.php';
include __DIR__ . '/../utils/nest.php';

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
LEFT JOIN featured
ON recipes.id = featured.featured_recipe
LEFT JOIN writers
ON recipes.writer_id = writers.id
WHERE featured.featured_recipe = recipes.id
';

$stmt = mysqli_prepare($con, $query);
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