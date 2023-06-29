<?php
// Delete all the files from the directory.
  function delete_all_files($upload_path) {
    // Get all file names
    $files = glob($upload_path . '*');

    // Iterate files
    foreach($files as $file) { 
      if(is_file($file)) {
        // Delete file
        unlink($file);
      }
    }
  }
?>
