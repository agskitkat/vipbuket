<?php
/**
 * Template Name: Шаблон главной страницы
 */
get_header(); // подключаем header.php ?> 

<div class="slider">
<?
    // параметры по умолчанию
    $posts = get_posts( array(
        'numberposts' => 5,
        'category'    => 0,
        'orderby'     => 'date',
        'order'       => 'DESC',
        'include'     => array(),
        'exclude'     => array(),
        'meta_key'    => '',
        'meta_value'  =>'',
        'post_type'   => 'slider',
        'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
    ) );

    foreach( $posts as $post ){
        setup_postdata($post);
        $slider0 = get_field( "slider-0", $post->ID );
        $slider768 = get_field( "slide-768", $post->ID );
        $text = get_field( "text", $post->ID );
		$color = get_field( "bg_color", $post->ID );
		$relaptionship = (int) get_field("relaptionship", $post->ID);
		if($relaptionship) {
			$term_link = get_term_link($relaptionship, 'taxonomy');
		} else {
			$term_link = "#";
		}
		

		?>
        
        <div class="slide" data-post-id="<?=$post->ID?>" style="background-color:<?=$color?>">
            <div class="container" >
				<a href="<?=$term_link?>">
					<div class="text"><?=$text?></div>
					<div class="image">
						<img data-src="<?=$slider768?>" 
						data-srcset="<?=$slider0?> 0w, <?=$slider768?> 768w">
					</div>
				</a>
            </div>
        </div>
        
        <?
    }
    wp_reset_postdata(); // сброс 
?>
</div>

<div class="container">
    <ul class="mobile-check-best">
        <li>Быстрое оформление заказа</li>
        <li>Бесплатная доставка по городу</li>
        <li>Анонимная доставка букетов</li>
        <li>Фото букета с получателем</li>
        <li>100% гарантия свежести</li>
    </ul>
</div>


<?

$co = get_extended( $post->post_content );

?>

<div class="container seotext">
    <h1 class="header"><?php the_title(); // заголовок поста ?></h1>	
	<p><?=$co['main']?></p>
</div>

<?=view_category(49,9, "Популярные букеты")?>

<?=view_category(13,9, "Недорогие букеты")?>

<?=view_category(50,9, "Корзины цветов")?>

<?=view_category(53,9, "Цветы")?>

<?=view_category(51,9, "Вкусные букеты")?>

<?=view_category(52,9, "Мужские букеты")?>


<div class="container seotext">
    <p><?=$co['extended']?></p>
</div>

<?php get_footer(); // подключаем footer.php ?>