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


	$banner_image_m = get_field('banner_m', $cat);
	$banner_image = get_field('banner', $cat);

	$recommendet_goods = get_field('recomendeds', $cat);


	// Субкатегории
	$sub_category = [];
	$relationships = get_field('relationships', $cat);
	if(count($relationships)) {
		foreach($relationships as $rid) {
			$term = get_term( $rid, "taxonomy");
			$image = get_field('kartinka_jelementa_svjazi',  "taxonomy_".$rid);
			$sub_category[] = [$term->name, "/".$term->slug."/", $image];
		}
	} 

	//var_dump($relationships);
	
	$noindex = get_field('noindex', $cat);
	
	if($noindex) {
		global $wp_query;
		$wp_query->set_404();
		status_header( 404 );
		get_template_part( 404 ); 
		exit();
	}
	
	//$seo_text = str_replace(chr(160), ' ', html_entity_decode($seo_text));
	$seo_text = html_entity_decode($seo_text);

	$ar_Seo_Text = explode('<!--more-->', $seo_text);
	foreach($ar_Seo_Text as &$text) {
		$text = strip_tags($text);
		$text = html_entity_decode($text);
	}

	//$ar_Seo_Text = [];
?>


<div class="vitrine">

	<?if($banner_image_m && $banner_image){?>
		<div class="container-1200">
			<div class="vitrine__banner">
				<div class="image mobile">
					<img class="just" src="<?=$banner_image_m?>" alt="">
				</div>
				<div class="image desktop">
					<img class="just" src="<?=$banner_image?>" alt="">
				</div>
			</div>
		</div>
	<?}?>

	<div class="container-1200">

		<div class="vitrine__back-catalog">
			<a class="back-link" href="/">
				<img src="<?=get_template_directory_uri()?>/images/svg/vitrine-back-blue.svg" class="just" alt="">
				В каталог
			</a>
		</div>

		<div class="vitrine__seo">
			<h1 class="header"><?=$title_h1?></h1>
			<div class="text">
				<?=wpautop($ar_Seo_Text[0])?>
			</div>
		</div>

		<?if(count($sub_category)) {?>
			<div class="vitrine__subcategory">
				<?foreach($sub_category as $caterory) {?>
					<a href="<?=$caterory[1]?>" class="subcategory">
						<div class="image">
							<img 
								src="<?=$caterory[2]?>" 
								alt="" 
								class="just">
						</div>
						<div class="text"><?=$caterory[0]?></div>
					</a>
				<?}?>
			</div>
		<?}?>

		<noindex>
			<?
			$oredr = (trim($_GET['order']) === 'ASC' )?'ASC':'DESC';

			if($oredr === 'DESC') {
				$active_desc = 'active';
				$active_asc = "";
			} else {
				$active_desc = '';
				$active_asc = 'active';
			}?>
			<div class="vitrine__sort sort-block">

				<div class="sort">
					Фильтры
					<div class="sort-hidden">
						<a href="?order=DESC" class="<?=$active_desc?>">По цене (убыв.)</a>
						<a href="?order=ASC" class="<?=$active_asc?>">По цене (возр.)</a>
					</div>
				</div>

			</div>
		</noindex>

		<div class="vitrine__goods">
		<?
			global $posts;
			
			$meta_key		= 'price';
			$meta_order		= $_GET['order']?trim($_GET['order']):'DESC';
			$meta_type 		= 'NUMERIC';
			
			if(empty($_GET['order'])) {
				$meta_key		= 'article';
				$meta_order		= 'ASC';
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
			
			foreach( $posts as $post ) { 
				setup_postdata($post);
		
				$id = $post->ID;
				$link = get_post_permalink( $id );
				
				$price = get_field("price", $id);
				$sale = get_field("sale", $id);
				$article = get_field("article", $id);
				$hit = get_field("hit", $id);
				$hit = $hit ? $hit : false;

				$new = get_field("new", $id);
				
				$old_price = $sale?search_start_value($price, $sale):false;
				
				$video = get_field("video", $id);
				
				$content_post = get_post($id);
				$content = $content_post->post_content;
				$content = apply_filters('the_content', $content);
				$content = str_replace(']]>', ']]&gt;', $content);
				$content = str_replace('&nbsp;', '', $content);
				
				$image_id = get_post_thumbnail_id($post);
				$thumbnail = wp_get_attachment_image_src( $image_id,  array(300, 300) );
				$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

				$good = [
					"id" => $id,
					"link" => $link,
					"price" => $price, 
					"sale"	=> $sale,
					"old_price" => $old_price,
					"article" => $article,
					"hit" => $hit,
					"new" => $new,
					"video" => $video,
					"img" => $thumbnail[0],
					"img_mini" => $thumbnail_mini[0],
					"name" => get_the_title(),
					"quantity" => $quantity,
					"new" => $new
				];
				
			?>
			
				<div class="micro-good vitrine__good">
					
					<a href="<?=$link?>">
						<img class="image" alt="<?=$image_title?>" data-src="<?=$thumbnail[0]?>" data-srcset="<?=$thumbnail[0]?> 0w">
					</a>

					<div class="icons-block">
						<?if($sale){?>
							<div class="icon icon__sale">-<?=$sale?>%</div>
						<?}?>

						<?if($new) {?>
							<div class="icon icon__new">Новинка</div>
						<?}?>

						<?if($hit){?>
							<div class="icon icon__hit">Хит продаж</div>
						<?}?>
					</div>

					<a href="<?=$link?>" class="good-title"><?=get_the_title($id)?></a>

					<div class="good-article"><?=$article?></div>

					<div class="good-price-block">
						<span class="good-price"><?=$price?> ₽</span>
						<?if($old_price) {?>
							<span class="good-old-price"><?=$old_price?> ₽</span>
						<?}?>
					</div>

					<button data-good='<?=json_encode($good)?>' class="js-action-add-to-cart good-add-to-cart s-btn s-btn_h40 s-btn_p12">
						В корзину
					</button>

				</div>
			<?}
		?>
		</div>

		<div class="vitrine__seo vitrine__seo_second">
			<div class="text">
				<?=wpautop ($ar_Seo_Text[1])?>
			</div>
		</div>

		<?if($recommendet_goods){?>
			<div class="good-slider">
				<span class="header">Рекомендуем также</span>
				<div class="slider-static-width sopmod-block-2">
					<?foreach($recommendet_goods as $good) {
						$id = $good->ID;
						$link = get_post_permalink( $id );
						$price = get_field("price", $id);
						$sale = get_field("sale", $id);
						$article = get_field("article", $id);
						$hit = get_field("hit", $id);
						$hit = $hit ? $hit : false;
						$new = get_field("new", $id);
						$old_price = $sale?search_start_value($price, $sale):false;

						$image_id = get_post_thumbnail_id( $id );
						$thumbnail = wp_get_attachment_image_src( $image_id,  array(300, 300) );
						$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );

						$jsgood = [
							"id" => $id,
							"link" => $link,
							"price" => $price, 
							"sale"	=> $sale,
							"old_price" => $old_price,
							"article" => $article,
							"hit" => $hit,
							"new" => $new,
							"video" => $video,
							"img" => $thumbnail[0],
							"img_mini" => $thumbnail_mini[0],
							"name" => get_the_title(),
							"quantity" => $quantity,
							"new" => $new
						];
					?>

					<div class="slider-slide">
						<div class="slide-content micro-good">
						
							<a href="<?=$link?>">
								<img class="image" 
									alt="<?=$image_title?>" 
									data-src="<?=$thumbnail[0]?>" 
									data-srcset="<?=$thumbnail[0]?> 0w"
									src="<?=$thumbnail[0]?>" 
								>
							</a>

							<div class="icons-block">
								<?if($sale){?>
									<div class="icon icon__sale">-<?=$sale?>%</div>
								<?}?>

								<?if($new) {?>
									<div class="icon icon__new">Новинка</div>
								<?}?>

								<?if($hit){?>
									<div class="icon icon__hit">Хит продаж</div>
								<?}?>
							</div>

							<a href="<?=$link?>" class="good-title"><?=get_the_title($id)?></a>

							<div class="good-article">А1211</div>

							<div class="good-price-block">
								<span class="good-price"><?=$price?> ₽</span>
								<?if($old_price) {?>
									<span class="good-old-price"><?=$old_price?> ₽</span>
								<?}?>
							</div>

							<button data-good='<?=json_encode($jsgood)?>' class="js-action-add-to-cart good-add-to-cart s-btn s-btn_h40 s-btn_p12">
								В корзину
							</button>
						</div>
					</div>

					<?}?>
				</div>
			</div>
		<?}?>

	</div>

</div>
<?php get_footer(); // подключаем footer.php ?>

<?

/*

WP_Post Object
(
    [ID] => 277
    [post_author] => 1
    [post_date] => 2019-07-24 00:06:10
    [post_date_gmt] => 2019-07-23 21:06:10
    [post_content] => Большой букет белых роз из 25 роз h-80 см.
    [post_title] => Большой букет белых роз
    [post_excerpt] => 
    [post_status] => publish
    [comment_status] => closed
    [ping_status] => closed
    [post_password] => 
    [post_name] => bolshoi-buket-belyh-roz
    [to_ping] => 
    [pinged] => 
    [post_modified] => 2019-08-07 14:25:03
    [post_modified_gmt] => 2019-08-07 11:25:03
    [post_content_filtered] => 
    [post_parent] => 0
    [guid] => https://vipbouquet.ru/?post_type=buket&p=277
    [menu_order] => 0
    [post_type] => buket
    [post_mime_type] => 
    [comment_count] => 0
    [filter] => raw
)
						
*/