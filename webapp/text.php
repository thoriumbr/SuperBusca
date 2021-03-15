<?php
header('Content-type: text/html');
echo str_replace( '&NewLine;', '<br />', htmlentities(file_get_contents('text/' . $_GET['path']), ENT_QUOTES | ENT_HTML5, 'UTF-8'));
?>
