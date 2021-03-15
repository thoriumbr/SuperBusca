<?php
    $q = $_GET['q'];

    $searchData = '{
        "query": {
            "match": {
                "data": {
                    "query":' . json_encode($q, JSON_HEX_QUOT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE) . '
                }
            }
        }
    }';
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL,"http://localhost:9200/_search");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $searchData);
curl_setopt($ch, CURLOPT_HTTPHEADER, array(                                                                          
    'Content-Type: application/json',                                                                                
        'Content-Length: ' . strlen($searchData))                                                                       
);
$result = curl_exec($ch);
echo $result;
?>