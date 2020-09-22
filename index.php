<?php

// Hataları gizleme
error_reporting(0);

// Fonksiyon dosyası
require_once 'functions.php';

// Kullanım
$username = 'adamlaryazilim'; // hesap gizli olmamalı
$result = ListPostsByUsername($username);
print_r($result);
