<html>
<head>
    <title>Compress JPEG Images for Vanilla Forum</title>
    
</head>

<body>
<?php
// CONFIGURE HERE
$domain = 'https://jgauroraforum.com/';
$startdir=getcwd().'/uploads';

function get_extension($file) {
 $extension = end(explode(".", $file));
 return $extension ? $extension : false;
}
 
    
function listFolderFiles($dir,$startdir,$manualselect){
    
    $imgextarray = array("jpg", "jpeg", "png", "gif");
    $largeimgsize = 800000;
    $fileisimg = 0;
    $imgoversizetargetratio = 1.2;

    $ffs = scandir($dir);

    unset($ffs[array_search('.', $ffs, true)]);
    unset($ffs[array_search('..', $ffs, true)]);

    // prevent empty ordered elements
    if (count($ffs) < 1)
        return;

    echo '<ol>';
    foreach($ffs as $ff){
        $isdir = is_dir($dir.'/'.$ff);
        if($isdir){
            //echo '<li>'.$ff.' '.$dir.'</li>';
            listFolderFiles($dir.'/'.$ff,$startdir,$manualselect);
        }
        else {
            $fileext = get_extension($ff);
            if (in_array($fileext,$imgextarray)) {
                $fileisimg = 1;
                $imgfilepath = $dir.'/'.$ff;
                $uploadpath =substr($dir,strlen($startdir)).'/'.$ff;
                $imgurl = $domain.'uploads'.$uploadpath;
                $imgfilesize=filesize($imgfilepath);
                if ($imgfilesize > $largeimgsize ) {
                    $imgsizedetails=getimagesize($imgfilepath);
                    $estimgsize = $imgsizedetails[0]*$imgsizedetails[1]/8;
                    $imgoversizeratio = $imgfilesize/$estimgsize;
                    if ($imgoversizeratio > $imgoversizetargetratio){
                        if ($fileext=='jpg' || $fileext=='jpeg'){
                            if ( $manualselect == 1){
                                echo '<li>'.'<a href=\''.$imgurl.'\'>'.$ff.'</a> , size='.$imgfilesize. ' bytes, ratio='.$imgoversizeratio.' compressed=<a href=\''.$domain.'compressimage.php?autodelete=1&path='.$uploadpath.'\'>click</a></li>';
                            }else{
                                if (strpos($ff, "_originalimg.".$fileext) == true) {
                                    unlink($imgfilepath) or die("Couldn't delete file");
                                } 
                                else {
                                    header('Location: '.$domain.'compressimage.php?autodelete=1&path='.$uploadpath);
                                    die();
                                }
                            }
                        }
                    }
                }
            }
        }
    }
    echo '</ol>';
}

$manualselect = (int)$_GET["manual"];
if ($manualselect == 1){echo "Running in manual mode. ";}

$haveoutput = 0;
listFolderFiles($startdir,$startdir,$manualselect);
if ($haveoutput==0){echo "No images were found that required compression!";}

?>

</body>
</html>