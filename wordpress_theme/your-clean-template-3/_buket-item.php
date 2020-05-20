<?php

$id = $post->ID;
$id = get_the_ID();

$link = get_post_permalink( $id );
        
$price = get_field("price", $id);
$sale = get_field("sale", $id);
$article = get_field("article", $id);
$hit = get_field("hit", $id);
$hit = $hit ? $hit : false;

$video = get_field("video", $id);

$content_post = get_post($id);
$content = $content_post->post_content;
$content = apply_filters('the_content', $content);
$content = str_replace(']]>', ']]&gt;', $content);

$image_id = get_post_thumbnail_id($post);
$thumbnail = wp_get_attachment_image_src( $image_id,  array(300, 300) );
$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
/*
$str = '<div class="good-wrap" id="item-'.$id.'">
			<div class="good">
				<div class="options">';
					$str .= $old_price ? '<div class="sale">-'.$sale.'%</div>':'';
					$str .= $hit?'<div class="hit">Хит</div>':'';
				 $str .= '</div>
				<div class="video-desktop desktop-only js-target-view-video" data-video="'.$video.'">
					<img data-src="'.get_template_directory_uri().'/images/svg/good-video.svg" data-srcset="'.get_template_directory_uri().'/images/svg/good-video.svg 0w">
				</div>
				<div class="image">
					<img alt="'.$image_title.'" data-src="'.$thumbnail[0].'" data-srcset="'.$thumbnail[0].' 0w">
				</div>
				<div class="name-article">
					<div class="name">
						'.get_the_title($id).'
					</div>
					<div class="article">
						'.$article.'
					</div>
				</div>
				<div class="video mobile-only js-target-view-video" data-video="'.$video.'">
					<img data-src="'.get_template_directory_uri().'/images/svg/good-video.svg" data-srcset="'.get_template_directory_uri().'/images/svg/good-video.svg 0w"> Видео
				</div>
				<div class="about">
					<span>описание ></span>
					<div class="expend">
						'.$content.'
					</div>
				</div>
				<div class="price-to-cart">
					<div class="prices">
						<span class="price">'.$price.' ₽</span>';
						$str .= $old_price ? '<span class="old-price">'.$old_price.' ₽</span>':'';  
			$str .='</div>
					<div class="to-cart">
						<button class="green-button">
							<img data-src="'.get_template_directory_uri().'/images/svg/good-cart.svg" data-srcset="'.get_template_directory_uri().'/images/svg/good-cart.svg 0w">
						</button>
					</div>
				</div>
			</div>
		</div>';
echo $str;
*/
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