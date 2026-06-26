<?php
$lines = file('storage/logs/laravel.log');
foreach ($lines as $line) {
    if (strpos($line, '[ERROR]') !== false || strpos($line, '.ERROR') !== false) {
        echo $line;
    }
}
