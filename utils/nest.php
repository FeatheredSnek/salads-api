<?php

function nest($data, $keys, $prefix, $container_name) {
  $nested = array();
  foreach ($keys as &$value) {
    $col_name = $prefix . $value;
    $nested[$value] = $data[$col_name];
    unset($data[$col_name]);
  };
  $data[$container_name] = $nested;
  return $data;
}

function nest_flags($data) {
  $keys = ['vegan', 'vegetarian', 'maindish', 'hot'];
  $prefix = 'flag_';
  $container_name = 'flags';
  return nest($data, $keys, $prefix, $container_name);
}

function nest_socials($data) {
  $keys = ['facebook', 'instagram', 'twitter', 'tiktok'];
  $prefix = 'social_';
  $container_name = 'socials';
  return nest($data, $keys, $prefix, $container_name);
}

?>
