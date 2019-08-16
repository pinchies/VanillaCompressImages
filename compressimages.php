<html>
<head>
    <title>Compress Images for Vanilla Forum</title>
    
</head>

<body>
<?php

$autocompress = (int)$_GET["manual"];
$haveoutput = 0;
 
 function get_extension($file) {
 $extension = end(explode(".", $file));
 return $extension ? $extension : false;
}
 
    
function listFolderFiles($dir,$startdir){
    
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
            listFolderFiles($dir.'/'.$ff,$startdir);
        }
        else {
            $fileext = get_extension($ff);
            //
            if (in_array($fileext,$imgextarray)) {
                $fileisimg = 1;
                $imgfilepath = $dir.'/'.$ff;
                $uploadpath =substr($dir,strlen($startdir)).'/'.$ff;
                $imgurl = $uploadurl.$uploadpath;
                $imgfilesize=filesize($imgfilepath);
                if ($imgfilesize > $largeimgsize && ($fileext=='jpg' || $fileext=='jpeg')) {
                    $imgsizedetails=getimagesize($imgfilepath);
                    $estimgsize = $imgsizedetails[0]*$imgsizedetails[1]/8;
                    $imgoversizeratio = $imgfilesize/$estimgsize;
                    if ($imgoversizeratio > $imgoversizetargetratio){
                        if ($autocompress==1){
                            header('Location: '.$domain.'compressimage.php?path='.$uploadpath);
                            die();
                            
                        }
                        echo '<li>'.'<a href=\''.$imgurl.'\'>'.$ff.'</a> , size='.$imgfilesize. ' bytes, ratio='.$imgoversizeratio.' compressed=<a href=\''.$domain.'compressimage.php?path='.$uploadpath.'\'>click</a></li>';
                    }
                    
                }
                
                if (strpos($ff, "_originalimg.jpg") != false) {
                    echo "WILL BE DELETED";
                    unlink($imgfilepath) or die("Couldn't delete file");
                } 
            }


        }

    }
    echo '</ol>';
}

$domain = 'https://jgauroraforum.com/';
$startdir='/home/jgaforum/public_html/uploads';

$uploadurl = $domain.'uploads';
listFolderFiles($startdir,$startdir);

if ($autocompress == 1){echo "Running in manual mode. ";}
if ($haveoutput==0){echo "No images were found that required compression!";}

?>

</body>
</html>