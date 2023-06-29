<?php 
  function crop_image_gd($base_path, $new_path, $x_end, $y_end, $x_start, $y_start) {
    $image_data = getimagesize($base_path);
    $media_type = $image_data['mime'];

    // Choose function to open image.
    switch($media_type) {
      case 'image/gif' : $base = imagecreatefromgif($base_path); break;
      case 'image/jpeg' : $base = imagecreatefromjpeg($base_path); break;
      case 'image/png' : $base = imagecreatefrompng($base_path); break;
    }

    // Cropped image dimentions.
    $new_width = null;
    $new_height = null;

    // Check if width has positive value and asign it.
    if ($x_end > $x_start) {
      $new_width = $x_end - $x_start;
    } 

    // Check if height has positive value and asign it.
    if ($y_end > $y_start) {
      $new_height = $y_end - $y_start;
    }

    if ($new_width and $new_height) {
      // Crop.
      $new = imagecrop($base, ['x' => $x_start, 'y' => $y_start, 'width' => $new_width, 'height' => $new_height]);

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
