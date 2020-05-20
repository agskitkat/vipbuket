<?php
/**
 * Шаблон поиска (search.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); // подключаем header.php ?> 

<div class="container-1200">
	<section class="good-category">
		<div class="header"><?php printf('Поиск: %s', get_search_query()); // заголовок поиска ?></div>
		<div class="category-filter"></div>
		
		<div class="vitrine">
			<div class="vitrine__goods">
				<?php 
				
				$args = array(
					's' => $_GET['s'],
					'posts_per_page' => 12,
					'post_type' => 'buket'
				);
				
				$the_query = new WP_Query($args);
				
				if( $the_query->have_posts() ) {
					while( $the_query->have_posts() ){ 
						$the_query->the_post(); 
						include("_buket-item.php"); 
					}
				} else {
					?><b>Ничего не найдено</b><?
				}
				?>
			</div>
		</div> 

	</section>
</div>

<?php get_footer(); // подключаем footer.php ?>