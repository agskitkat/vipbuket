<?php
/**
 * Template Name: Шаблон обычной страницы (page.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); // подключаем header.php ?>

<div class="container seotext">
    <h1 class="header"><?php the_title(); // заголовок поста ?></h1>
    <?php if (have_posts()) : while (have_posts()) : the_post(); ?>
		<?php the_content(); ?>
	<?php endwhile; endif; ?>
</div>


<?php get_footer(); // подключаем footer.php ?>