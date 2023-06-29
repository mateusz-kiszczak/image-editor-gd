<?php 
  $images_path = 'images/';
  // Get list of all files in 'edit' directory.
  $all_saved_images = scandir($images_path);

  $edit_path = 'temp/edit/';
  // Get list of all files in 'edit' directory.
  $all_images_edit = scandir($edit_path);
  // Get the only existing file from 'edit' directory.
  $edit_image_path = (count($all_images_edit) == 3) ? $all_images_edit[2] : '';
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
        <li class="nav-link"><a href="index.php">upload</a></li>
        <li class="nav-link <?= !$edit_image_path ? 'nav-link--disable' : '' ?>"><a href="edit.php">edit</a></li>
        <li class="nav-link nav-link--active"><a href="images.php">your images</a></li>
      </ul>
    </nav>
    <hr>
    <main class="user-images">
      <section class="user-images__container">
        <?php 
          for ($image = 2; $image < count($all_saved_images); $image++) {
            echo 
            '<div>
              <img src="images/' . $all_saved_images[$image] . '" alt="User uploaded image.">
              <a class="download-button" href="images/' . $all_saved_images[$image] . '" download>
                <img src="icons/download.svg" alt="Download icon.">
              </a>
              <a class="zoom-button" href="images/' . $all_saved_images[$image] . '" target="_blank">
                <img src="icons/zoom.svg" alt="Open full size image in new window.">
              </a>
            </div>';
          }
        ?>
      </section>
    </main>
  </div>
</body>
</html>
