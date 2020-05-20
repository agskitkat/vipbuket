<?

class AgsssCart {
	const CART_COOKIE_NAME = "cart_cookie_buket";
	protected $cart = [];
	public $sum = 0, $old_sum = 0, $sale_sum = 0;
	
	// Получаем крозину пользователя из куков при создании экземпляра
	public function __construct() {
		$this->cart = $this->get_cart();
	}
	
	
	// Получает корзину из куков
	protected function get_cart() {
		$cart = [];

		//print_r($_COOKIE);

		if( isset($_COOKIE[self::CART_COOKIE_NAME])) {
			foreach ($_COOKIE[self::CART_COOKIE_NAME] as $name => $value) {
				$name = htmlspecialchars($name);
				$value = htmlspecialchars($value);
				
				$cart[$name] = $this->getCartItemById($name, $value);
			}
		}
		
		
		$this->sum = 0;
		$this->old_sum = 0;
		$this->sale_sum = 0;
		$this->mass = 0;
		
		$result = $cart;
		foreach($result as $good) {
			$this->sum += $good['sum'];
			$this->old_sum += $good['old_price_sum'];
			
			$this->mass += 500; //Масса в граммах
			
			if($good['sale'] > 0) {
				$this->sale_sum += ($good['old_price_sum'] - $good['sum']);
			}
		}
		
		return $cart;
	}
	
	// Номализуем данные товара
	protected function getCartItemById($id, $quantity) {
		$post = get_post($id);
		$title = $post->post_title;

		// TODO: уйти от AFC 
		$price = get_field("price", $id);
		$sale = get_field("sale", $id);
		$article = get_field("article", $id);
		
		$image_id = get_post_thumbnail_id($post);
		$thumbnail = wp_get_attachment_image_src( $image_id,  array(300, 300) );
			
		$old_price = $sale?search_start_value($price, $sale):false;
		
		return [
			"id" => $id,
			"quantity" => $quantity,
			"price" => $price,
			"old_price" => $old_price,
			"old_price_sum" => $quantity * $old_price,
			"sale" => $sale,
			"article" => $article,
			"title" => $title,
			"sum" => $quantity * $price,
			"img" => $thumbnail[0],
			"mass" => 500,
			"url" => get_post_permalink($id)
		];
	}
	
	// Кладём в корзину товар $id количества $quantity
	public function update($id, $quantity, $method = false) {
		$id = intval($id?:false);
		$quantity = intval($quantity?:0);
		
		// невозможно положить товар без ID
		if(!$id) { 
			return false;
		}
		
		$result = [];
		
		if($method === "addition") {
			//print_r($_COOKIE);
			if(isset($_COOKIE[self::CART_COOKIE_NAME][$id])) {
				$quantity += intval($_COOKIE[self::CART_COOKIE_NAME][$id]);
			}
		}
		
		if($quantity >= 1) {
			setcookie(self::CART_COOKIE_NAME."[$id]", $quantity, time()+(3600*24), '/', $_SERVER['HTTP_HOST']);
			$cart = $this->get_cart();
			
			if(isset($cart[$id])) {
				$cart[$id] = $this->getCartItemById($id, $quantity);
				$result = $cart;
			} else {
				$result = array_merge($cart, [$id => $this->getCartItemById($id, $quantity) ]);
			}
			
		} else {
			setcookie(self::CART_COOKIE_NAME."[$id]", $quantity, time()-(3600*24), '/', $_SERVER['HTTP_HOST']);
			$cart = $this->get_cart();
			// Удаление кукса
			unset($cart[$id]);
			$result = $cart;
		}
		
		$this->cart = $result;	
	}
	
	public function getItems() {
		return $this->cart;
	}
	
	// Печатаем json ответ
	public function render($render = 'html') {
		$sum = 0;
		$old_sum = 0;
		$sale_sum = 0;
		$result = $this->cart;
		foreach($result as $good) {
			$sum += $good['sum'];
			$old_sum += $good['old_price_sum'];
			
			if($good['sale'] > 0) {
				$sale_sum += ($good['old_price_sum'] - $good['sum']);
			}
		}
		
		if($render === 'ajax') {
			
			echo '{"result":{
				"staus":"ok", 
				"message":"Корзина обновлена", 
				"sum":"'.agsss_nf($sum).'", 
				"old_sum":"'.agsss_nf($old_sum).'", 
				"sale_sum":"'.agsss_nf($sale_sum).'", 
				"cart_count":"'.count($result).'",
				"cart":'.json_encode($result).'}
			}';
			
		} 
		if($render === 'html'){
			$cart = $result;
			?>	
			
<div class="container">
	<?if(count($cart)){?>
    <div class="cart">
        <span class="cart__header">В корзине <span id="js-t-cart-header-items-count"><?=count($cart)?> товара</span></span>
        <div class="cart__layout">
            <div class="cart__items">

              <? 
			  $sum = 0;
			  $old_sum = 0;
			  $sale_sum = 0;
			  
			  
			  foreach($cart as $id => $item) {
				$sum += $item['sum'];
				$old_sum += $item['old_price_sum'];
				
				if($item['sale'] > 0) {
					$sale_sum += ($item['old_price_sum'] - $item['sum']);
				}
			  ?>
				<div class="item_cart" id="cart-item-<?=$id?>">
					<div class="item__layout">

						<div class="item__image">

							<img data-src="<?=$item['img']?>" data-srcset="<?=$item['img']?>">
						</div>

						<div class="item__text">

							<div class="item__icon-block">
								<?if($item['sale']){?>
								<div class="item__icon">
									<?=$item['sale']?"-".$item['sale']."%":""?>
								</div>
								<?}?>
							</div>

							<div class="flex-desktop">
								<div class="flex-tablet">
									<div class="item__price prices">
										<div class="price_actual">
											<?=agsss_nf($item['sum'])?> ₽
										</div>
										<div class="price_old-price">
										<?if($item['old_price_sum']){?>
											<?=agsss_nf($item['old_price_sum'])?> ₽
										<?}?>
										</div>
									</div>

									<div class="flex-table__column">
										<div class="item__article">
											<?=$item['article']?>
										</div>
										<div class="item__name">
											<?=$item['title']?>
										</div>
									</div>
								</div>


								<div class="item__quantity-block">
									<div class="quantity-block__control">
										<div class="quantity-block__measure">Кол-во шт.</div>
										<div class="flex">
											<div data-good-id="<?=$id?>" class="js-action-good button decrement">-</div>
											<div class="view-quantity">
												<?=$item['quantity']?>
											</div>
											<div data-good-id="<?=$id?>" class="js-action-good button increment">+</div>
										</div>
										<div class="price-by-one">
											<?=agsss_nf($item['price'])?> руб. /<span class="measure">шт.</span>
										</div>
									</div>
								</div>

								<div class="item__price prices display-desktop">
									<div class="price_actual">
										<?=agsss_nf($item['sum'])?> ₽
									</div>
									<div class="price_old-price">
										<?if($item['old_price_sum']){?>
											<?=agsss_nf($item['old_price_sum'])?> ₽
										<?}?>
									</div>
								</div>
							</div>

						</div>

						<div class="item__remove hover180 js-action-remove-item-from-cart" data-good-id="<?=$id?>">
							<img 
							data-srcset="<?=get_template_directory_uri()?>/images/svg/cart-item-remove.svg" 
							data-src="<?=get_template_directory_uri()?>/images/svg/cart-item-remove.svg" alt="">
						</div>

					</div>
				</div>
			  <? }?>

            </div>
            <div class="cart__summary"> 
                <div class="summary__border">
                    <div class="flex-row">
                        <span>Товары (<?=count($cart)?>)</span>
                        <span id="sum-old-price"><?=agsss_nf($old_sum)?> ₽</span>
                    </div>
                    <div class="flex-row">
                        <span>Скидка на товары</span>
                        <span id="sum-sale" class="red"><?=agsss_nf($sale_sum)?> ₽</span>
                    </div>
                    
                    <div class="summary__line"></div>
                    <div class="flex-row flex-row__end">
                        <span>Итого</span>
                        <span id="sum-price" class="price"><?=agsss_nf($sum)?> ₽</span>
                    </div>
                </div>
				<a href="/order/" class="summary__button">Перейти к оформлению</a>
            </div>
        </div>
    </div>
	<?} else {?>
		<div class="cart">
			<span class="cart__header">Корзина пуста</span>
		</div>
		
	<?}?>
</div>
			<?
		}
	}

	public function getItemByCart($id) {
		if(!isset($this->cart[$id])) {
			return false;
		}
		
		return $this->cart[$id];
	}

	public function getCartSummary() {
		$sum = 0;
		$old_sum = 0;
		$sale_sum = 0;
		$result = $this->cart;

		foreach($result as $good) {
			$sum += $good['sum'];
			$old_sum += $good['old_price_sum'];
			
			if($good['sale'] > 0) {
				$sale_sum += ($good['old_price_sum'] - $good['sum']);
			}
		}

		return [
			"sum" => $sum, 
			"old_sum" => $old_sum, 
			"sale_sum" => $sale_sum, 
			"cart_count" => count($result),
			"cart" => $result
		];

	}
}


// Добавляем в корзину
add_action( 'wp_ajax_addTocart', 'add_to_cart' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_addTocart', 'add_to_cart' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}


function add_to_cart() {
	$cart = new AgsssCart();
	$cart->update($_POST['id'], $_POST['quantity'], $_POST['method']?$_POST['method']:false);
	$cart->render('ajax');
	die();
}
?>