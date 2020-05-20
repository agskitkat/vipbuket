<?php
/**
 * Template Name: Шаблон новой главной страницы
 */
get_header(); // подключаем header.php 

$text1 = get_field( "text-1", $post->ID );
$text2 = get_field( "text-2", $post->ID );
$text3 = get_field( "text-3", $post->ID );
$text4 = get_field( "text-4", $post->ID );
$text5 = get_field( "text-5", $post->ID );

?> 

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
		<div class="slide">
			<div class="container-1200">
				<a href="<?=$term_link?>" style="display:block;" class="image-full">
					<img data-src="<?=$slider768?>" data-srcset="<?=$slider0?> 0w, <?=$slider768?> 760w">
				</a>
			</div>
		</div>
		<?
    }
    wp_reset_postdata(); // сброс 
	?>
</div>

<div class="after-slider"></div>

<div class="container-1200">
    <section class="seotext">
        <h1 class="header-main-page">Доставка букетов в Москве</h1>
        <p class="p-main-page">
			<?=$text1?>
		</p>
        
    </section>

    <div class="spc-btn-1-list">
		<?
			$catBlock = get_field( "cat-block-1", $post->ID );
			foreach($catBlock as $cat) {
				$img = get_field( 'circle-img', 'taxonomy_'.$cat);
				//echo $cat;
				
				$posts = get_posts( 
					array(
						'post_type'   => 'buket',
						'meta_type'   => 'CHAR',
						'meta_key'	  => 'article',
						'orderby'     => 'meta_value',
						'order'       => 'ASC',
						'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
						'tax_query' => array(
							array(
							  'taxonomy' => 'taxonomy',
							  'field' => 'id',
							  'terms' => $cat
							)
						)
					) 
				);
				
				
				$real_price = false;
				foreach( $posts as $p ) {
					//var_dump($p);
					
					$price = get_field( 'price', $p->ID);
					if(!$real_price) {
						$real_price = $price;
					}
					
					if($real_price > $price) {
						$real_price = $price;
					}
				}
				
				$term = get_term( $cat );
				//print_r( $term );
	
			?>
				<a href="/<?=$term->slug?>/" class="spc-btn-1">
					<img data-src="<?=$img?>" data-srcset="<?=$img?> 0w" alt="">
					<div class="text">
						<div class="name"> 
							<?=$term->name?>
						</div>
						<div class="price">
							от <?=$real_price?> ₽
						</div>
						<img class="back-arrow" data-src="<?=get_template_directory_uri()?>/images/svg/arrow.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/arrow.svg 0w" alt="">
					</div>
				</a>
			<?
			}
		?>
		
    </div>

    <section class="seotext">
        <p class="p-main-page"><?=$text2?></p>
        <div class="header-main-page">Заказать букет цветов с доставкой в Москве</div>
        <p class="p-main-page"><?=$text3?></p>
    </section>

    <div class="spc-btn-2-list">
			
			<?
			$catBlock = get_field( "cat-block-2", $post->ID );
			foreach($catBlock as $cat) {
				$img = get_field( 'second-img', 'taxonomy_'.$cat);
				//echo $cat;
				
				$fon = get_field( 'fon', 'taxonomy_'.$cat);
				
				$term = get_term( $cat );
				//print_r( $term );
	
			?>
			 <a href="/<?=$term->slug?>/" class="spc-btn-2" style="background-color:<?=$fon?>">
				<div class="img-block">
					<img data-src="<?=$img?>" data-srcset="<?=$img?> 0w" alt="">
				</div>
				<div class="text">
					<div class="name">
						<?=$term->name?>
					</div>
					<img class="back-arrow" data-src="<?=get_template_directory_uri()?>/images/svg/arrow.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/arrow.svg 0w" alt="">
				</div>
			</a>
			<?
			}
			?>
    
        
    </div>

    <div class="good-slider good-slider_main-page">
        <span class="header">
            <img data-src="<?=get_template_directory_uri()?>/images/svg/pop-buket.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/pop-buket.svg 0w" alt="">
        </span>
        <div class="slider-static-width">
		
			<?
			$goods = get_field( "goods", $post->ID );
			
			//var_dump($goods);
	

			foreach ($goods as $p) :
				$price = get_field( 'price', $p->ID);
				$sale = get_field("sale", $p->ID);
				$old_price = $sale?search_start_value($price, $sale):false;
				
				$link = get_post_permalink( $p->ID );
				
				$article = get_field( 'article', $p->ID);
				
				$image_id = get_post_thumbnail_id($p);
				$thumbnail = wp_get_attachment_image_src( $image_id,  array(360, 360) );
				$thumbnail_mini = wp_get_attachment_image_src( $image_id,  array(60, 60) );
				$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
				//var_dump($image);
				
				
				$hit = get_field("hit", $p->ID);
				$hit = $hit ? $hit : false;
				
				$new = false;
				
				$video = get_field("video", $p->ID);
				
				$cart = new AgsssCart();
				$item = $cart->getItemByCart($p->ID);
				$quantity = isset($item)?$item['quantity']:1;
				
				// Единый стандарт отображения товара
				$good = [
					"id" => $p->ID,
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
					"name" => $p->post_title,
					"quantity" => $quantity
				];
			?>
				<div class="slider-slide">
					<div class="slide-content micro-good">
						<img class="image just" data-lazy="<?=$thumbnail[0]?>" src="<?=$thumbnail[0]?>">
						
						<div class="icons-block">
							<?if($sale) {?>
								<div class="icon icon__sale">-<?=$sale?>%</div>
							<?}?>
							
							<?if($new) {?>
								<div class="icon icon__new">Новинка</div>
							<?}?>
							
							<?if($hit) {?>
								<div class="icon icon__hit">Хит продаж</div>
							<?}?>
						</div>
						
						
						<div class="good-title"><?=$p->post_title?></div>
						
						<div class="good-article"><?=$article?></div>
						
						<div class="good-price-block">
							<span class="good-price"><?=$price?> ₽</span>
							<?if($sale) {?>
								<span class="good-old-price"><?=$old_price?> ₽</span>
							<?}?>
							
						</div>
						
						<button class="good-add-to-cart s-btn s-btn_h40 s-btn_p12 js-action-add-to-cart" data-good='<?=json_encode($good)?>'>В корзину</button>
					</div>
				</div>
			<?
		       //post!
			endforeach;
			?>
        </div>
    </div>

    <section class="seotext">
        <div class="header-main-page">
            <img data-src="<?=get_template_directory_uri()?>/images/svg/pop-ukr.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/pop-ukr.svg" alt="">
        </div>
        <p class="p-main-page"><?=$text4?></p>

    </section>


    <div class="spc-btn-2-list">
	
		<?
			$catBlock = get_field( "cat-block-3", $post->ID );
			foreach($catBlock as $cat) {
				$img = get_field( 'second-img', 'taxonomy_'.$cat);
				//echo $cat;
				
				$fon = get_field( 'fon', 'taxonomy_'.$cat);
				
				
				$term = get_term( $cat );
				//print_r( $term );
	
			?>
			
			<a href="/<?=$term->slug?>/" class="spc-btn-2" style="background-color:<?=$fon?>">
				<div class="img-block">
					<img data-src="<?=$img?>" data-srcset="<?=$img?> 0w" alt="">
				</div>
				<div class="text">
					<div class="name">
						<?=$term->name?>
					</div>
					<img class="back-arrow" data-src="<?=get_template_directory_uri()?>/images/svg/arrow.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/arrow.svg 0w" alt="">
				</div>
			</a>
			<?
			}
			?>

    </div>

    <section class="seotext">
        <div class="header-main-page">
            <img data-src="<?=get_template_directory_uri()?>/images/svg/pop-buk-dos.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/pop-buk-dos.svg" alt="">
        </div>
        <p class="p-main-page"><?=$text5?></p>
    </section>
</div>

<?php get_footer(); // подключаем footer.php ?>