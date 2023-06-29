<?php
  // Add a number to the file name, when one with same name already exist in the directory. Replace unsupported characters with '-'.
  function create_filename($filename, $upload_path) {
    $basename = pathinfo($filename, PATHINFO_FILENAME);
    $extension = pathinfo($filename, PATHINFO_EXTENSION);
    $basename = preg_replace('/[^A-z0-9]/', '-', $basename);
    $i = 0;
    while (file_exists($upload_path . $filename)) {
      $i = $i + 1;
      $filename = $basename . $i . '.' . $extension;
    }
    return $filename;
  }
?>
