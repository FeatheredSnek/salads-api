<?php

include __DIR__ . '/../connection.php';
include __DIR__ . '/../utils/nest.php';

if (isset($_GET['q'])) {
  $search_q = $_GET['q'];
} else {
  $search_q = '';
}

$prep_time = 999;
if (isset($_GET['preptime'])) {
  $prep_time = $_GET['preptime'];
}

$flags = array(
  'vegan' => 0,
  'vegetarian' => 0,
  'maindish' => 0,
  'hot' => 0
);

foreach ($flags as $key => $value) {
  if (isset($_GET[$key])) {
    $flags[$key] = (bool) $_GET[$key];
  }
};

$name = "%$search_q%";


$search_query = "
  SELECT 
    recipes.*, 
    writers.name AS `writer`,
    writers.friendly_id AS `writer_friendly_id`
  FROM recipes
  LEFT JOIN writers
  ON recipes.writer_id = writers.id
  WHERE 
    recipes.name LIKE ? 
    AND recipes.writer_friendly_id LIKE ? 
    AND recipes.flag_vegan >= ?
    AND recipes.flag_vegetarian >= ?
    AND recipes.flag_maindish >= ?
    AND recipes.flag_hot >= ?
    AND recipes.prep_time <= ?
  ORDER BY name
";

if (isset($_GET['writer'])) {
  $writer_q = $_GET['writer'];
  $writer = "%$writer_q%";
  $search_query = "
  SELECT 
    recipes.*, 
    writers.name AS `writer`,
    writers.friendly_id AS `writer_friendly_id`
  FROM recipes
  LEFT JOIN writers
  ON recipes.writer_id = writers.id
  WHERE 
    recipes.name LIKE ? 
    AND recipes.writer_friendly_id LIKE ? 
    AND recipes.flag_vegan >= ?
    AND recipes.flag_vegetarian >= ?
    AND recipes.flag_maindish >= ?
    AND recipes.flag_hot >= ?
    AND recipes.prep_time <= ?
  ORDER BY name
  ";
  $search_stmt = mysqli_prepare($con, $search_query);
  mysqli_stmt_bind_param(
    $search_stmt, 
    'ssiiiii', 
    $name,
    $writer,
    $flags['vegan'], 
    $flags['vegetarian'], 
    $flags['maindish'], 
    $flags['hot'],
    $prep_time
  );
} else {
  $search_query = "
  SELECT 
    recipes.*, 
    writers.name AS `writer`,
    writers.friendly_id AS `writer_friendly_id`
  FROM recipes
  LEFT JOIN writers
  ON recipes.writer_id = writers.id
  WHERE 
    recipes.name LIKE ? 
    AND recipes.flag_vegan >= ?
    AND recipes.flag_vegetarian >= ?
    AND recipes.flag_maindish >= ?
    AND recipes.flag_hot >= ?
    AND recipes.prep_time <= ?
  ORDER BY name
  ";
  $search_stmt = mysqli_prepare($con, $search_query);
  mysqli_stmt_bind_param(
    $search_stmt, 
    'siiiii', 
    $name,
    $flags['vegan'], 
    $flags['vegetarian'], 
    $flags['maindish'], 
    $flags['hot'],
    $prep_time
  );
}

mysqli_stmt_execute($search_stmt);
$search_query_result = mysqli_stmt_get_result($search_stmt);
$response = array();
while ($row = mysqli_fetch_assoc($search_query_result)) {
  array_push($response, nest_flags($row));
};
http_response_code(200);
echo json_encode($response);
exit;

?>
