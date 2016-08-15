<?php

// include bmkg.php
require('lib/bmkg.php');

// object
$bmkg = new Bmkg();

// array
// menampilkan prakiraan cuaca
$cuaca = $bmkg->cuaca();

/* menampilkan informasi gempa
$gempa = $bmkg->gempa();
*/

// output JSON
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
echo json_encode($cuaca, JSON_PRETTY_PRINT);
