<?php

function get_extension($file) {
    $extension = end(explode(".", $file));
    return $extension ? $extension : false;
}

function compress_image($source_url, $destination_url, $quality) {

       $info = getimagesize($source_url);

        if ($info['mime'] == 'image/jpeg')
              $image = imagecreatefromjpeg($source_url);

        elseif ($info['mime'] == 'image/gif')
              $image = imagecreatefromgif($source_url);

      elseif ($info['mime'] == 'image/png')
              $image = imagecreatefrompng($source_url);

        imagejpeg($image, $destination_url, $quality);
    return $destination_url;
    }

$path = $_GET["path"];
$startdir='/home/jgaforum/public_html/uploads';
$quality = 50;
$imgextarray = array("jpg", "jpeg", "png", "gif");
$filepath = $startdir.$path;
$fileext = get_extension($filepath);
// /in_array($fileext,$imgextarray)
if ($fileext == 'jpg' || $fileext == 'jpeg') {
    $newpathsmall = substr($filepath,0,strlen($filepath)-strlen($fileext)-1).'_smallimg.'.$fileext;
    $newpathoriginal = substr($filepath,0,strlen($filepath)-strlen($fileext)-1).'_originalimg.'.$fileext;
}

if (!file_exists($newpathoriginal)) {
    compress_image($filepath,$newpathsmall,$quality);
    if (file_exists($newpathsmall)) {
        rename("$filepath", "$newpathoriginal");
        rename("$newpathsmall", "$filepath");
    }
}

 header('Content-Type: image/jpeg');
        header('Expires: 0');
        header('Cache-Control: must-revalidate');
        header('Pragma: public');
        header('Content-Length: ' . filesize($filepath));
        readfile($filepath);

        exit;
 
    ?>