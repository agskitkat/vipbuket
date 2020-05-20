<?php
/**
 * Шаблон отдельной записи (single.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
get_header(); // подключаем header.php ?>
<section>
	
	<?php 
	if ( have_posts() ) while ( have_posts() ) : the_post(); // старт цикла 
	
		$id = $post->ID;
		$link = get_post_permalink( $id );
		
		$price = get_field("price", $id);
		$sale = get_field("sale", $id);
		$article = get_field("article", $id);
		
		$hit = get_field("hit", $id);
		$hit = $hit ? $hit : false;
		
		$new = get_field("new", $id);
		$new = $new ? $new : false;
		
		$old_price = $sale?search_start_value($price, $sale):false;
		
		$video = get_field("video", $id);
		
		$content_post = get_post($id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
		
		$image_id = get_post_thumbnail_id($post);
		$thumbnail = wp_get_attachment_image_src( $image_id,  array(360, 360) );
		$thumbnail_mini = wp_get_attachment_image_src( $image_id,  array(60, 60) );
		$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		
		// Images
		$images = acf_photo_gallery('images', $post->ID);
		
		$buys = [];
		$recommendes = [];
		
		$cart = new AgsssCart();
		$item = $cart->getItemByCart($id);
		$quantity = isset($item)?$item['quantity']:1;
	
		
		// Единый стандарт отображения товара
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

		// Родительская категория
		$arTerms = wp_get_object_terms($id, 'taxonomy');
		if(count($arTerms )) {
			$name = $arTerms[0]->name;
			$url = $arTerms[0]->slug;
		}
		
		
		if($video) {
			$arUrl = parse_url($video);
			$path = str_replace("/", "", $arUrl['path']);
			$video = "https://www.youtube.com/embed/".$path."?controls=1";
		}
	?>
	<div class="container">
		<?if( count($arTerms) ) {?>
			<a href="/<?=$url?>/" class="back-link-good"><img src="/wp-content/themes/your-clean-template-3/images/svg/good-arrow-back.svg" class="just" alt=""> <?=$name?></a>
		<?}?>
		<div class="full-good">
			<div class="full-good__layout">

				<div class="image-slider-layout">
				
					<?if($video) {?>
						<div data-video="<?=$video?>" class="image-video-play js-play-youtube">
							<img class="just" src="/wp-content/themes/your-clean-template-3/images/svg/play.svg" />
						</div>
					<?}?>
					
					<div class="image-slider-control">
						<?
						if( count($images) ) {
							foreach($images as $image) {
								$full_image_url = $image['full_image_url'];
								$full_image_url = acf_photo_gallery_resize_image($full_image_url, 60, 60);
						?>
							<div class="image-slide">
								<div class="image" style="background-image: url('<?=$full_image_url;?>')"></div>
							</div>
						<?}?>
						<?} else {?>
							<div class="image-slide">
								<div class="image" style="background-image: url('<?=$thumbnail_mini[0];?>')"></div>
							</div>
						<?}?>
					</div>
					
					<div class="image-slider">
						<?
						if( count($images) ) {
							foreach($images as $image) {
								$full_image_url= $image['full_image_url'];
								$full_image_url = acf_photo_gallery_resize_image($full_image_url, 360, 360);
						?>
							<div class="image-slide">
								<div class="image" style="background-image: url('<?=$full_image_url;?>')"></div>
							</div>
							<?}?>
						<?} else {?>
							<div class="image-slide">
								<div class="image" style="background-image: url('<?=$thumbnail[0];?>')"></div>
							</div>
						<?}?>
					</div>
				</div>


				<div class="full-good__container">
					<div class="icons-block">
						<?if($sale){?>
							<div class="icon icon__sale">-<?=$sale?>%</div>
						<?}?>
						
						<? if($new) {?>
						<div class="icon icon__new">Новинка</div>
						<?}?>
						
						<? if($hit) {?>
							<div class="icon icon__hit">Хит продаж</div>
						<?}?>
						<div class="icon"></div>
					</div>

					<h1 class="good-title"><?php the_title(); // заголовок поста ?></h1>

					<div class="good-article"><?=$article?></div>

					<div class="good-price-block">
						<span class="good-price"><?=nf($price)?> ₽</span>
						<?if($sale){?>
							<span class="good-old-price"><?=nf($old_price)?> ₽</span>
						<?}?>
					</div>

					<div class="good-quantity-block">
						<div class="quantity-header">Кол-во шт.</div>
						<div class="quantity-buttons">
							<button class="js-decrement s-btn s-btn_block s-btn_h40 s-btn_control">-</button>
							<div data-quantity="1" class="quantity-number">1</div>
							<button class="js-increment s-btn s-btn_block s-btn_h40 s-btn_control">+</button>
						</div>
						<div class="quantity-price-item" data-one-item-price="<?=$price?>"><?=nf($price)?> ₽</div>
					</div>

					<div class="good-add-block">
						<button class="s-btn s-btn_second s-btn_block s-btn_h40 js-action-add-to-cart"  data-good='<?=json_encode($good)?>'>Добавить в корзину</button>
						<button class="s-btn s-btn_block s-btn_h40 js-action-add-to-cart" data-redirect='/order/' data-good='<?=json_encode($good)?>'>Быстрый заказ</button>
					</div>
				</div>

			</div>

			<div class="full-good__container">
				<div class="good-info">
					<?=$content;?>
				</div>

				<?if($buys) {?>
					<div class="good-slider">
						<span class="header">С этим товаром покупают</span>
						<div class="slider-static-width">
							//= modules/slider-good.html
							//= modules/slider-good.html
							//= modules/slider-good.html
							//= modules/slider-good.html
						</div>
					</div>
				<?}?>

				<?if($recommendes) {?>
					<div class="good-slider">
						<span class="header">Рекомендуем также</span>
						<div class="slider-static-width">
							//= modules/slider-good.html
							//= modules/slider-good.html
							//= modules/slider-good.html
							//= modules/slider-good.html
						</div>
					</div>
				<?}?>

			</div>
		</div>
	</div>
	<?php endwhile; // конец цикла ?>
	
</section>

<div class="s-modal s-video" id="video-play">
    <div class="s-modal-content">
        <div class="s-modal-close js-close-modal"></div>
		
		<div class="iframe-block">
			<iframe src="<?=$video['EMBED']?>" allow="encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
		</div>
	</div>
</div>

<?php get_footer(); // подключаем footer.php ?>
