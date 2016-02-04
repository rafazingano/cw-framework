<?php

echo str_replace(array('index.php', $_SERVER['DOCUMENT_ROOT']), '', $_SERVER['SCRIPT_FILENAME']);

print_r('<pre>');
print_r($_SERVER);


