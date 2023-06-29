<?php
  require 'create_filename.php';

  $edit_path = '../temp/edit/';
  // Get list of all files in 'edit' directory.
  $all_images_edit = scandir($edit_path);
  // Get the only existing file from 'edit' directory.
  $edit_image_path = (count($all_images_edit) == 3) ? $all_images_edit[2] : '';

  $moved = false;
  $upload_path = '../images/';

  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $filename = create_filename($edit_image_path, $upload_path);
    $destination = $upload_path . $filename;
    $moved = copy($edit_path . $edit_image_path, $destination);

    header('Location: ../edit.php');
  }
?>