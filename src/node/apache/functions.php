<?php


function backup($dir, $file){
	$z = new ZipArchive();
	$z->open($file, ZIPARCHIVE::CREATE);
	folderToZip($dir, $z);
	$z->close();
}


function getRandomName($name){
	return  date("Y-m-d-H-i-s",time()) .'_'.rand(1, 999999).'_'. $name;
}

function delDirAndFile($dirName){
    if($handle = opendir("$dirName")){
        while(false !== ($item = readdir($handle))){
           if($item != "." && $item != ".."){
               if(is_dir("$dirName/$item")){
                    delDirAndFile("$dirName/$item");
               }else{
                    unlink("$dirName/$item");
               }
           }
        }
        closedir($handle);
        return rmdir($dirName);
    }
    return 0;
}


function folderToZip($folder, &$zipFile, $subfolder = 'deploy') {
    if ($zipFile == null) {
        // no resource given, exit
        return false;
    }
    // we check if $folder has a slash at its end, if not, we append one
    $folder .= end(str_split($folder)) == "/" ? "" : "/";
    $subfolder .= end(str_split($subfolder)) == "/" ? "" : "/";
    // we start by going through all files in $folder
    $handle = opendir($folder);
    while ($f = readdir($handle)) {
        if ($f != "." && $f != "..") {
            if (is_file($folder . $f)) {
                // if we find a file, store it
                // if we have a subfolder, store it there
                //if ($subfolder != null)
                $zipFile->addFile($folder . $f, $subfolder . $f);
                //else
                //    $zipFile->addFile($folder . $f);
            } elseif (is_dir($folder . $f)) {
                // if we find a folder, create a folder in the zip
				// if($subfolder==null)
				// {
					// $zipFile->addEmptyDir($f);
					// and call the function again
					// folderToZip($folder . $f, $zipFile, $f);
				// }
				// else
				//{
				$zipFile->addEmptyDir($subfolder . $f);
				// and call the function again
				folderToZip($folder . $f, $zipFile, $subfolder . $f);
				//}
                
                
            }
        }
    }
}

?>