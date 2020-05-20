<?php
/**
 * Главная страница (index.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); // подключаем header.php ?> 

<?
	global $wp_query; // get the query object
	$cat_obj = $wp_query->get_queried_object();
	
	$cat = $cat_obj->taxonomy.'_'.$cat_obj->term_id;
	
	$title_h1 = get_field('title_h1', $cat);
	$seo_text = get_field('seo_text', $cat);
	
	$noindex = get_field('noindex', $cat);
	
	if($noindex) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); 
		exit();
	}
	
	$ar_Seo_Text = explode('<!--more-->', $seo_text);
	
?>

<div class="container seotext" style="padding-bottom:10px;padding-top:40px;">
    <h1 class="header"><?=$title_h1?></h1>
    <?=$ar_Seo_Text[0]?>
</div>

<div class="container">
	<section class="good-category">
		<?
			$oredr = (trim($_GET['order']) === 'ASC' )?'ASC':'DESC';
		?>
		<noindex>
			<div class="category-order">
				<?if($oredr === 'DESC') {?>
					<a rel="nofollow" href="?order=ASC">Сначала дешевле</a>
				<?} else {?>
					<a rel="nofollow" href="?order=DESC">Сначала дороже</a>
				<?}?>
			</div>
		</noindex>
		<? /* <div class="header"><?php single_cat_title(); // название категории ?></div> */ ?>
		<div class="category-filter"></div>
		<div class="content-grid">
		<?
		global $posts;
		
		$meta_key		= 'price';
		$meta_order		= $_GET['order']?trim($_GET['order']):'DESC';
		$meta_type 		= 'NUMERIC';
		
		if(empty($_GET['order'])) {
			$meta_key		= 'article';
			$meta_order		= 'ASC';//DESC  ASC
			$meta_type 		= 'CHAR';
		}
		
		$posts = get_posts(array(
			'post_type'			=> 'buket',
			'posts_per_page'	=> -1,
			'tax_query' => array(
				array(
					'taxonomy' => $cat_obj->taxonomy,
					'terms' => $cat_obj->term_id,
					'field' => 'term_id'
				)
			),
			'meta_key'			=> $meta_key,
			'orderby'			=> 'meta_value',
			'order'				=> $meta_order,
			'meta_type'			=> $meta_type
		));
		
		foreach( $posts as $post ){
			setup_postdata($post);
		
			
			$id = $post->ID;
			$link = get_post_permalink( $id );
			
			$price = get_field("price", $id);
			$sale = get_field("sale", $id);
			$article = get_field("article", $id);
			$hit = get_field("hit", $id);
			$hit = $hit ? $hit : false;
			
			$old_price = $sale?search_start_value($price, $sale):false;
			
			$video = get_field("video", $id);
			
			$content_post = get_post($id);
			$content = $content_post->post_content;
			$content = apply_filters('the_content', $content);
			$content = str_replace(']]>', ']]&gt;', $content);
			
			$image_id = get_post_thumbnail_id($post);
			$thumbnail = wp_get_attachment_image_src( $image_id,  array(300, 300) );
			$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		
					
			$str = '<div class="good-wrap">
					<div class="good">
							<div class="options">';
							$str .= $old_price ? '<div class="sale">-'.$sale.'%</div>':'';
							$str .= $hit?'<div class="hit">Хит</div>':'';
						 $str .= '</div>
						<div class="video-desktop desktop-only js-target-view-video" data-video="'.$video.'">
							<img data-src="'.get_template_directory_uri().'/images/svg/good-video.svg" data-srcset="'.get_template_directory_uri().'/images/svg/good-video.svg 0w">
						</div>
						<a href="'.$link.'" class="image">
							<img alt="'.$image_title.'" data-src="'.$thumbnail[0].'" data-srcset="'.$thumbnail[0].' 0w">
						</a>

						<div class="desktop-only">
							<div class="name-article">
								<a href="'.$link.'" class="name">
									'.get_the_title($id).'
								</a>
								<div class="article">
									'.$article.'
								</div>
							</div>

							<div class="about">
								<span>описание ></span>
								<div class="expend">
									'.$content.'
								</div>
							</div>
							<div class="price-to-cart">
								<div class="prices">
									<span class="price'.($old_price?' red':'').'">'.$price.' ₽</span>';
									$str .= $old_price?'<span class="old-price">'.$old_price.' ₽</span>':'';
								$str .= '</div>
								<div class="to-cart" data-good-id="'.$id.'">
									<button class="green-button">
										<img 
										data-src="'.get_template_directory_uri().'/images/svg/good-cart.svg" 
										data-srcset="'.get_template_directory_uri().'/images/svg/good-cart.svg 0w">
									</button>
								</div>
							</div>

						</div> 
						<div class="mobile-only">
							<div class="article-line">
								<div class="article-line__article">
									'.$article.'
								</div>
								<div class="article-line__old-price">';
									$str .= $old_price?'<span class="old-price">'.$old_price.' ₽</span>':'';
								$str .= '</div>
							</div> 

							<div class="name-line">
								<a href="'.$link.'" class="name-line__name">
									'.get_the_title($id).'
								</a>
								<div class="name-line__price'.($old_price?' red':'').'">
									'.$price.' ₽
								</div>
							</div>
 
							<div class="mobile-expend-block">
								<div class="not-expend-text hide">
									<div class="content">
										'.$content.'
									</div>
								</div>
								<!--<div class="expend-fade"></div>

								 <span class="expend-button">ещё</span> -->
							</div>

							<div class="gry-line"></div>
						</div>
					</div>
				</div>';
			echo $str;
			wp_reset_postdata();
		}
		?>
		</div>
	</section>
</div>
<?if(isset($ar_Seo_Text[1])){?>
	<div class="container seotext">
	   <?=$ar_Seo_Text[1]?>
	</div>
<?}?>
<?php get_footer(); // подключаем footer.php ?>