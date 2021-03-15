<?php
date_default_timezone_set("America/Sao_Paulo");
time();
$filename = $argv[1]; 
$json = [];
$json['data'] = file_get_contents($filename);
$json['filename'] = basename($filename);
$json['date'] = time();
$data_string = json_encode($json, JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://localhost:9200/capture/_doc");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($data_string))                                                                       
);
$result = curl_exec($ch);
//echo $result;
?>
