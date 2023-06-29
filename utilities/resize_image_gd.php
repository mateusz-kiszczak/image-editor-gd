<?php 
  function resize_image_gd($base_path, $new_path, $new_width, $new_height) {
    $image_data = getimagesize($base_path);
    $base_width = $image_data[0];
    $base_height = $image_data[1];
    $media_type = $image_data['mime'];

    // Choose function to open image.
    switch($media_type) {
      case 'image/gif' : $base = imagecreatefromgif($base_path); break;
      case 'image/jpeg' : $base = imagecreatefromjpeg($base_path); break;
      case 'image/png' : $base = imagecreatefrompng($base_path); break;
    }

    if ($new_width and $new_height) {
      // Create a blank image.
      $new = imagecreatetruecolor($new_width, $new_height);
      
      // Copy base to new image.
      imagecopyresampled($new, $base, 0, 0, 0, 0, $new_width, $new_height, $base_width, $base_height);
      
      // Save image.
      switch($media_type) {
        case 'image/gif' : $result = imagegif($new, $new_path); break;
        case 'image/jpeg' : $result = imagejpeg($new, $new_path); break;
        case 'image/png' : $result = imagepng($new, $new_path); break;
      }
      
      return $result;
    }
  }
?>
