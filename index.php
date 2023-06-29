<?php
  require 'utilities/delete_all_files.php';


  $moved         = false;
  $message       = '';
  $error         = '';
  $upload_path   = 'temp/upload/';
  $edit_path     = 'temp/edit/';
  $max_size      = 5242880;
  $allowed_types = ['image/jpeg', 'image/png', 'image/gif',];
  $allowed_exts  = ['jpeg', 'jpg', 'png', 'gif'];
  

  // Get list of all files in 'upload' directory.
  $all_images_upload   = scandir($upload_path);
  // Get the only existing file from 'upload' directory.
  $uploaded_image_path = (count($all_images_upload) == 3) ? $all_images_upload[2] : '';

  // Use GD getimagesize() to gather the image data.
  $image_data = $uploaded_image_path ? getimagesize($upload_path . $uploaded_image_path) : null;

  // Image details.
  $img_width  = $image_data ? $image_data[0] : null;
  $img_height = $image_data ? $image_data[1] : null;
  $img_ratio  = $image_data ? round($img_width / $img_height, 2) : null;
  $img_size   = $uploaded_image_path ? filesize($upload_path . $uploaded_image_path) : null;
  

  // When form is submited.
  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Check if the size of the file is not too big.
    $error = ($_FILES['image']['error'] === 1) ? 'The file is too big.' : '';

    // If no errors
    if ($_FILES['image']['error'] == 0) {
      // Check the size.
      $error .= ($_FILES['image']['size'] <= $max_size) ? '' : 'The file is too big. ';
      
      // Check if the media type is included in '$allowed_types' array.
      $type = mime_content_type($_FILES['image']['tmp_name']);
      $error .= in_array($type, $allowed_types) ? '' : 'Unsupported media type. ';

      // Check if the file etention is included in '$allowed_exts' array.
      $ext = strtolower(pathinfo($_FILES['image']['name'], PATHINFO_EXTENSION));
      $error .= in_array($ext, $allowed_exts) ? '' : 'Unsupported file extention. ';
      
      // If no errors, create the new filepath and move the file.
      if (!$error) {
        // Clear folder from previous files.
        delete_all_files($upload_path);
        delete_all_files($edit_path);
        
        // Add uploaded file to 'temp/upload'.
        $filename     = $_FILES['image']['name']; 
        $destination  = $upload_path . $filename;
        $moved        = move_uploaded_file($_FILES['image']['tmp_name'], $destination);
        
        // Refresh page so the changes are visible.
        header("Refresh:0");
      }
    }
  }
  

  // If upload was succesfull copy image to 'edit' directory.
  if ($uploaded_image_path and count(scandir($edit_path)) != 3) {
    copy($upload_path . $uploaded_image_path, $edit_path . $uploaded_image_path);
  }
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="description" content="Resize and crop you image.">
  <title>Image Editor</title>
  <link rel="stylesheet" href="styles/style.css">
</head>
<body>
  <div class="content-wrapper">
    <nav class="nav">
      <ul>
        <li class="nav-link nav-link--active"><a href="index.php">upload</a></li>
        <li class="nav-link <?= !$uploaded_image_path ? 'nav-link--disable' : '' ?>"><a href="edit.php">edit</a></li>
        <li class="nav-link"><a href="images.php">your images</a></li>
      </ul>
    </nav>
    <hr>
    <main class="upload-image">
      <p class="<?= !$error ? 'upload-image__alert--disable' : '' ?>">This image can not be uploaded. <?= $error ?></p>
      <form class="upload-image__form" method="POST" action="index.php" enctype="multipart/form-data">
        <div class="upload-image__form__choose-file">
          <label for="image">Upload file:</label>
          <input type="file" name="image" accept="image/*" id="image">
        </div>  
        <input class="button" type="submit" value="upload">
      </form>
      <hr>
      <section class="upload-result">
        <section class="upload-result__image">
          <?php 
            if ($uploaded_image_path) {
              echo '<img src="temp/upload/' . $uploaded_image_path . '" alt="Image uploaded by user.">';
            }
          ?>
          <a class="button <?= !$uploaded_image_path ? 'button--disable' : '' ?>" href="edit.php">edit</a>
        </section>
        <section class="upload-result__data">
          <ul>
            <li>file name: <?= $uploaded_image_path ?></li>
            <li>size: <?= $img_size ?> bytes</li>
            <li>width: <?= $img_width ?></li>
            <li>height: <?= $img_height ?></li>
            <li>ratio: <?= $img_ratio ?></li>
          </ul>
          <a class="button <?= !$uploaded_image_path ? 'button--disable' : '' ?>" href="edit.php">edit</a>
        </section>
      </section>
    </main>
  </div>
  <?php echo("<script>console.log('');</script>"); ?>
</body>
</html>
