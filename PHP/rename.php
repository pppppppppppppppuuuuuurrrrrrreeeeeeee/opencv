<?php
$sc = array_diff(scandir('./Babol'), ['.', '..']);
$sc = array_values($sc);
// echo count($sc).PHP_EOL;
for($i = 0 ; $i < count($sc); $i++) {
    $newName = ($i + 1).'.jpg';
    echo $newName.PHP_EOL;
    rename('./Babol/'.$sc[$i], $newName);
}
