<?php 
  require 'utilities/resize_image_gd.php';
  require 'utilities/crop_image_gd.php';


  $edit_path = 'temp/edit/';
  
  // Get list of all files in 'edit' directory.
  $all_images_edit = scandir($edit_path);
  // Get the only existing file from 'edit' directory.
  $edit_image_path = (count($all_images_edit) == 3) ? $all_images_edit[2] : '';

  $edit_image_data  = getimagesize($edit_path . $edit_image_path);
  $image_width      = $edit_image_data[0];
  $image_height     = $edit_image_data[1];
  $image_ratio      = intval($image_width / $image_height);
  $image_media_type = $edit_image_data['mime'];
  $image_channels   = $edit_image_data['channels'];
  $image_bits       = $edit_image_data['bits'];


  $user = [
    'resize_width'  => '',
    'resize_height' => '',
    'crop_x_start'  => '',
    'crop_x_end'    => '',
    'crop_y_start'  => '',
    'crop_y_end'    => '',
  ];


  if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Validate user input.
    $validation_filters['resize_width']['filter']               = FILTER_VALIDATE_INT;
    $validation_filters['resize_width']['options']['min_range'] = 1;
    $validation_filters['resize_width']['options']['max_range'] = 9999;

    $validation_filters['resize_height']['filter']               = FILTER_VALIDATE_INT;
    $validation_filters['resize_height']['options']['min_range'] = 1;
    $validation_filters['resize_height']['options']['max_range'] = 9999;

    $validation_filters['crop_x_start']['filter']                = FILTER_VALIDATE_INT;
    $validation_filters['crop_x_start']['options']['min_range']  = 1;
    $validation_filters['crop_x_start']['options']['max_range']  = 9999;

    $validation_filters['crop_x_end']['filter']                  = FILTER_VALIDATE_INT;
    $validation_filters['crop_x_end']['options']['min_range']    = 1;
    $validation_filters['crop_x_end']['options']['max_range']    = 9999;

    $validation_filters['crop_y_start']['filter']                = FILTER_VALIDATE_INT;
    $validation_filters['crop_y_start']['options']['min_range']  = 1;
    $validation_filters['crop_y_start']['options']['max_range']  = 9999;

    $validation_filters['crop_y_end']['filter']                  = FILTER_VALIDATE_INT;
    $validation_filters['crop_y_end']['options']['min_range']    = 1;
    $validation_filters['crop_y_end']['options']['max_range']    = 9999;

    $user = filter_input_array(INPUT_POST, $validation_filters);

    // Change user value to integer (in case previous methods failed).
    $user_width   = intval($user['resize_width']);
    $user_height  = intval($user['resize_height']);
    $user_x_start = intval($user['crop_x_start']);
    $user_x_end   = intval($user['crop_x_end']);
    $user_y_start = intval($user['crop_y_start']);
    $user_y_end   = intval($user['crop_y_end']);

    $resized = resize_image_gd($edit_path . $edit_image_path, $edit_path . $edit_image_path, $user_width, $user_height);
    $crop    = crop_image_gd($edit_path . $edit_image_path, $edit_path . $edit_image_path, $user_x_end, $user_y_end, $user_x_start, $user_y_start);

    // Refresh page so the changes are visible.
    header("Refresh:0");
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
        <li class="nav-link nav-link"><a href="index.php">upload</a></li>
        <li class="nav-link nav-link--active"><a href="edit.php">edit</a></li>
        <li class="nav-link"><a href="images.php">your images</a></li>
      </ul>
    </nav>
    <hr>
    <main class="edit-image">
      <section class="edit-image__user-panel">
        <div class="panel-wrapper">
          <section class="edit-image__user-panel__resize">
            <p class="edit-image__user-panel__label">resize</p>
            <form class="edit-image__form-resize" method="POST" action="edit.php">
              <div>
                <label for="resize-width">Width: </label>
                <input type="number" id="resize-width" name="resize_width" min="1" max="9999" step="1">
                <span> px</span>
              </div>
              <div>
                <label for="resize-height">Height: </label>
                <input type="number" id="resize-height" name="resize_height" min="1" max="9999" step="1">
                <span> px</span>
              </div>
              <input class="button" type="submit" value="update">
            </form>
          </section>
          <section class="edit-image__user-panel__resize">
            <p class="edit-image__user-panel__label">crop</p>
            <form class="edit-image__form-crop" method="POST" action="edit.php">
              <div class="x-start">
                <label for="crop-x-start">X start: </label>
                <input type="number" id="crop-x-start" name="crop_x_start" min="1" max="9999" step="1">
                <span> px</span>
              </div>
              <div class="x-end">
                <label for="crop-x-end">X end: </label>
                <input type="number" id="crop-x-end" name="crop_x_end" min="1" max="9999" step="1">
                <span> px</span>
              </div>
              <div class="y-start">
                <label for="crop-y-start">Y start: </label>
                <input type="number" id="crop-y-start" name="crop_y_start" min="1" max="9999" step="1">
                <span> px</span>
              </div>
              <div class="y-end">
                <label for="crop-y-end">Y end: </label>
                <input type="number" id="crop-y-end" name="crop_y_end" min="1" max="9999" step="1">
                <span> px</span>
              </div>
              <input class="button crop-update-button" type="submit" value="update">
            </form>
          </section>
        </div>
        <form method="POST" action="utilities/save_image.php">
          <input class="button" type="submit" value="save image">
        </form>
      </section>
      <hr>
      <section class="edit-image__canvas">
        <section class="edit-image__canvas__image">
        <?php 
            if ($edit_image_path) {
              echo '<img src="temp/edit/' . $edit_image_path . '" alt="Image edited by user.">';
            }
          ?>
        </section>
        <ul class="edit-image__canvas__data">
          <li>W: <span><?= $image_width ?></span><span class="edit-image__canvas__data__separator">|</span></li>
          <li>H: <span><?= $image_height ?></span><span class="edit-image__canvas__data__separator">|</span></li>
          <li>Ratio: <span><?= $image_ratio ?></span><span class="edit-image__canvas__data__separator">|</span></li>
          <li>Image type: <span><?= strtoupper(str_replace('image/', '', $image_media_type)) ?></span><span class="edit-image__canvas__data__separator">|</span></li>
          <li>Channels: <span><?= $image_channels == 3 ? 'RGB' : 'CMYK' ?></span><span class="edit-image__canvas__data__separator">|</span></li>
          <li>Bits: <span><?= $image_bits ?></span></li>
        </ul>
      </section>
    </main>
  </div>
  <?php echo("<script>console.log('');</script>"); ?>
</body>
</html>
