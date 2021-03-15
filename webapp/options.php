<?php
//exit();
$filename = "options";
$change = explode(":", $_GET['change']);
$newData = "";
$handle = fopen($filename, "r");
if ($handle) {
    while (($line = fgets($handle)) !== false) {
        $setting = explode("=", $line);
        if ($setting[0] == $change[0]){
            if ($setting[0]=='IGNORE'){
                $newData .= $change[0] . "=\"" . str_replace("_", "\\\|", $change[1]) . "\"\n";    
            } else {
            $newData .= $change[0] . "=" . $change[1] . "\n";
            echo $change[0] . "=" . $change[1];
            }
        } else {
            $newData .= $line;
        }
    }
    fclose($handle);
} else {
    // error opening the file.
}
file_put_contents("$filename", $newData);