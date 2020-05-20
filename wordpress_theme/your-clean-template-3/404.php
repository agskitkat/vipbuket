<?php
/**
 * Страница 404 ошибки (404.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); // Подключаем header.php ?>
<section class="container" style="width:100%;text-align:center;padding:150px 0px;">
	<h1 style="font-size:40px;margin-bottom:30px;">ОШИБКА 404</h1>
	<p style="padding-bottom:10px;">Страница, которую Вы открыли, не существует</p>
	<a href="/">Главная страница</a>
</section>
<?php get_footer(); // подключаем footer.php ?>