<?php
/**
 * @file
 * The primary PHP file for this theme.
 */
function _helper_image_style_exists($style, $src) {
	$image_style = image_style_load($style, $src);
	
	if(empty($image_style)) {
		return false;
	}
	
	//Check if image style image has been created yet
	$dest = image_style_path($style, $src);
	if(!file_exists($dest)) {
		// Create image from image style
		image_style_create_derivative($image_style, $src, $dest);
	}
	
	return $dest;
}

function chdstatelibrary_file_link($variables) {
  $file = $variables['file'];
  $icon_directory = $variables['icon_directory'];
  $link_title = $file->filename;
  $url = file_create_url($file->uri);
  $icon = theme('file_icon', array('file' => $file,'alt'=>$link_title, 'icon_directory' => $icon_directory));

  // Set options as per anchor format described at
  // http://microformats.org/wiki/file-format-examples
  $options = array(
    'attributes' => array(
      'type' => $file->filemime . '; length=' . $file->filesize,
    ),
  );

  // Use the description as the link text if available.
  if (empty($file->description)) {
    $link_text = $file->filename;
  }
  else {
    $link_text = $file->description;
    $options['attributes']['title'] = check_plain($file->filename);
  }

  //open files of particular mime types in new window
  //$new_window_mimetypes = array('application/pdf','text/plain');
  //if (in_array($file->filemime, $new_window_mimetypes)) {
    $options['attributes']['target'] = '_blank';
  //}

  return '<span class="file">' . $icon . ' ' . l($link_text, $url, $options) . '</span>';
}


