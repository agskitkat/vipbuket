<?php
header("Cache-Control: private");
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Expires: " . date("r")); 
/**
 * Template Name: Корзина
 */
get_header(); // подключаем header.php 

$cart = new AgsssCart();
$cart->render();

get_footer(); // подключаем footer.php ?>