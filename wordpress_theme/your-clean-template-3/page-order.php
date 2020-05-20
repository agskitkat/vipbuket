<?php
/**
 * Template Name: Оформление заказа
 */
get_header(); // подключаем header.php 
$order = new AgsssOrder();

if($_GET['doOrder']) {
	// Обновляем поля
	$order->getOrderVarsFromPost();
	$order->setOrderVars();
	$order->recalculateOrder();
	// Завершаем
	$order->complite();
} else {
	$order->render();
}

?> 

<?php get_footer(); // подключаем footer.php ?>