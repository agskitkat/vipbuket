<?

// Класс создания заказа
class AgsssOrder {
	const CART_COOKIE_NAME = "cart_cookie_buket";
	
	static protected $fields = [
		'userName',
		'userEmail',
		'userPhone',
		'deliveryCity',
		'deliveryStreet',
		'deliveryHouse',
		'deliveryApart',
		'deliveryType',
		'paymentType',
		'policy'
	];
	
	protected $errors = [];

	
	// Получение данных о заказе 
	function __construct() {
		$this->cart = new AgsssCart();
		$this->getOrderVars();
		$this->recalculateOrder();
	}
	
	// Получение данных о заказе 
	function getOrderVars() {
		foreach(self::$fields as $field) {
			$this->{$field} = isset($_COOKIE[self::CART_COOKIE_NAME."-".$field])?$_COOKIE[self::CART_COOKIE_NAME."-".$field]:"";
		}
	}
	
	// Записать новые значения в хранилище
	function setOrderVars() {
		$time = time()+60*60*24*365*10;
		foreach(self::$fields as $field) {
			if(isset($this->{$field})) {
				setcookie(self::CART_COOKIE_NAME."-".$field, $this->{$field},$time, '/', $_SERVER['HTTP_HOST']);
			}
		}
	}
	
	// Получаем новые значения из POST
	function getOrderVarsFromPost() {
		foreach(self::$fields as $field) {
			if(isset($_POST['data'][$field])) {
				$this->{$field} = $_POST['data'][$field];
			}
		}
	}
	
	// пересчитывает всю подноготную заказа
	function recalculateOrder() {
		$this->sum = $this->cart->sum;
		$this->old_sum = $this->cart->old_sum;
		$this->sale_sum = $this->cart->sale_sum;
		$this->mass = $this->cart->mass;
		
		$deliveryResult = AgsssDelivery::calculateDelivery($this);
		$this->deliveryPrice = $deliveryResult['price'];
		$this->deliveryResultMessaage = $deliveryResult['message'];
	}
	
	// Выполнить заказ
	function complite() {
		
		foreach(self::$fields as $field) {
			if(empty($_POST[$field])) {
				$this->errors[$field] = "Ошибка при заполнении поля";
			} else {
				$compliteFields[$field] = htmlspecialchars(trim($_POST['data'][$field]));
			}
		}
		
	/* 	
		echo "<pre>";
		print_r($this);
		echo "</pre>";  */
	
		
		if(count($this->errors)) {
			$this->render('html');
			return false; 
		}
		
		// сохраняем, отправляем данные.
		$post_id = wp_insert_post(  
			wp_slash( 
				array(
					'post_title'    => "Заказ от " . date("Y-m-d h:i"),
					'post_status'   => 'publish',
					'post_type'     => 'agsss_order',
					'post_author'   => 1,
					'ping_status'   => get_option('default_ping_status'),
					'post_parent'   => 0,
					'menu_order'    => 0
				) 
			) 
		);
		
		$this->orderId = $post_id;
		
		update_field("agsss-order-delivery", 		$this->deliveryType, 	$post_id);
		update_field("agsss-order-pyment", 			$this->paymentType, 	$post_id);
		
		update_field('agsss-order-userName',		$this->userName, 			$post_id);
        update_field('agsss-order-userEmail',		$this->userEmail, 			$post_id);
		update_field('agsss-order-userPhone',		$this->userPhone, 			$post_id);
        update_field('agsss-order-deliveryCity',	$this->deliveryCity, 		$post_id);
		update_field('agsss-order-deliveryStreet',	$this->deliveryStreet, 		$post_id);
        update_field('agsss-order-deliveryHouse',	$this->deliveryHouse, 		$post_id);
		update_field('agsss-order-deliveryApart',	$this->deliveryApart, 		$post_id);
		
		update_field('agsss-order-cart', serialize($this->cart->getItems()), 	$post_id);
		
		
		// Отправить заказ юзеру
		$tmp_delivery = AgsssDelivery::getDeliveryById($this->deliveryType);
		//print_r($tmp_delivery);
		$calc = AgsssDelivery::calculateDelivery($this);
		//print_r($calc); // { ["price"]=> int(0) ["message"]=> string(0) "" }
		
		//print_r($this->cart->mass);
		$payment = AgsssPyment::getPymentById($this->paymentType);
		//print_r($this);
		
		ob_start();
		include(__DIR__ . "/../templates/mail/order.php");
		$str = ob_get_clean();
		
		add_filter( 'wp_mail_content_type', function($content_type){
			return "text/html";
		});

		wp_mail( 
			$this->userEmail, 
			"Оформлен заказ в интернет магазине", 
			$str
		);
		
		wp_mail( 
			"info@lentaibant.ru", 
			"Оформлен заказ в интернет магазине", 
			$str
		);
		
		
		// рендер завершения закза
		$this->render('orderEnd');
	} 
	
	// Рендер
	public function render($render = 'html') {
		
		if($render === "orderEnd") {
			?>
			<div class="container">
				<div style="text-align:center;padding: 48px;">
					<h1 style="font-size:32px;line-height:40px;padding-bottom:32px;">Заказ №<?=$this->orderId?> оформлен !</h1>
					<p style="font-size:16px;line-height:20px;">Ваш заказ успешно оформлен. Мы свяжемся с вами в ближайшее время.</p>
				</div>
			</div>
			<?
		}
		
		if($render === "ajax") {
			echo json_encode($this);
		}
		
		if($render === "html") { 
			?>
<script>
	$(function(){
		$('#agsss-order-form input').change(function(){
			var form = {};
			
			$('#agsss-order-form input').each(function(key, input){
				var val = $(input).val();
				var name = $(input).attr('name');
				
				var type = $(input).attr('type');
				
				if(type === "radio" && $(input).is(":checked")) {
					form[name] = val;
				}
				
				if(type != "radio" && type != "checkbox") {
					form[name] = val;
				}
			
				if(type === "checkbox") {
					if($(input).is(":checked")) {
						form[name] = true;
					} else {
						form[name] = false;
					}
				}
			});
			
			 var data = {
				action: 'AgsssRecalculateOrder',
				data: form
			 };
			 $.post("/wp-admin/admin-ajax.php", data, function (response) {
				 response = JSON.parse(response);
				 
				 var sum = +response.sum;
				 var sale_sum = +response.sale_sum;
				 var deliveryPrice = +response.deliveryPrice;
				 var deliveryResultMessaage = response.deliveryResultMessaage;
				 
				 $("#agsss-old-sum").html(sum+sale_sum + " ₽");
				 $("#agsss-sale").html(sale_sum + " ₽");
				 
				 
				 if(deliveryPrice === 0) {
					$("#agsss-delivery").html("Бесплатно");
				 } else {
					$("#agsss-delivery").html(deliveryPrice + " ₽");
				 }
				 
				 if(deliveryResultMessaage) {
					 $("#agsss-delivery").html(deliveryResultMessaage);
				 }
				 
				 
				 $("#agsss-sum").html(sum+deliveryPrice + " ₽");
			 });
		});
	}); 
</script>
<div class="container">
    <div id="agsss-order-form" class="order">
		<form method="POST" action="?doOrder=true">
        <div class="row">
            <div class="col-12 col-l-6 form-block">
                <span class="sub-header">Контактные данные</span>
                <div class="input-border<?=$this->errors['userName']?" input-border_error":""?>">
                    <label>
                        <span class="label">Имя</span>
                        <input type="text" name="userName" value="<?=$this->userName?>">
						
                    </label>
					<?if($this->errors['userName']){?>
						<p class="error-message"><?=$this->errors['userName']?></p>
					<?}?>
                </div>
                <div class="input-border<?=$this->errors['userEmail']?" input-border_error":""?>">
                    <label>
                        <span class="label">Email</span>
                        <input type="text" name="userEmail" value="<?=$this->userEmail?>">
						
                    </label>
					<?if($this->errors['userEmail']){?>
						<p class="error-message"><?=$this->errors['userEmail']?></p>
					<?}?>
                </div>
                <div class="input-border<?=$this->errors['userPhone']?" input-border_error":""?>">
                    <label>
                        <span class="label">Телефон</span>
                        <input type="text" name="userPhone" value="<?=$this->userPhone?>">
						
                    </label>
					<?if($this->errors['userPhone']){?>
						<p class="error-message"><?=$this->errors['userPhone']?></p>
					<?}?>
                </div>
            </div>

            <div class="col-12 col-l-6 form-block">
                <span class="sub-header">Адрес доставки</span>

                <div class="input-border<?=$this->errors['deliveryCity']?" input-border_error":""?>">
                    <label>
                        <span class="label">Город</span>
                        <input type="text" name="deliveryCity" value="<?=$this->deliveryCity?>">
						
                    </label>
					<?if($this->errors['deliveryCity']){?>
						<p class="error-message"><?=$this->errors['deliveryCity']?></p>
					<?}?>
                </div>

                <div class="input-border<?=$this->errors['deliveryStreet']?" input-border_error":""?>">
                    <label>
                        <span class="label">Улица</span>
                        <input type="text" name="deliveryStreet" value="<?=$this->deliveryStreet?>">
						
					</label>
					<?if($this->errors['deliveryStreet']){?>
						<p class="error-message"><?=$this->errors['deliveryStreet']?></p>
					<?}?> 
                </div>

                <div class="row">
                    <div class="col-6">
                        <div class="input-border<?=$this->errors['deliveryHouse']?" input-border_error":""?>">
                            <label>
                                <span class="label">Дом</span>
                                <input type="text" name="deliveryHouse" value="<?=$this->deliveryHouse?>">
								 
							</label>
							<?if($this->errors['deliveryHouse']){?>
								<p class="error-message"><?=$this->errors['deliveryHouse']?></p>
							<?}?> 
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="input-border<?=$this->errors['deliveryApart']?" input-border_error":""?>">
                            <label>
                                <span class="label">Кв.</span>
                                <input type="text" name="deliveryApart" value="<?=$this->deliveryApart?>">
								  
							</label>
							<?if($this->errors['deliveryApart']){?>
								<p class="error-message"><?=$this->errors['deliveryApart']?></p>
							<?}?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="row">
            <div class="col-12 col-l-6 form-block">
                <span class="sub-header">Способ доставки</span>

				<?
				$deliverys = AgsssDelivery::getDeliverys();
				foreach($deliverys as $delivery) {?>
				<div class="input-border input-border-radio<?=$this->errors['deliveryType']?" input-border_error":""?>">
					<label>
						<input
							value="<?=$delivery['id']?>"  
							name="deliveryType" 
							type="radio"
							<?=$delivery['id']===$this->deliveryType?"checked":""?>
						>
						<span class="radio"></span>
						<span class="label"><?=$delivery['name']?></span>
					</label>
					
				</div>
				<?}?>
				<?if($this->errors['deliveryType']){?>
					<p class="error-message"><?=$this->errors['deliveryType']?></p>
				<?}?>
                
            </div>

            <div class="col-12 col-l-6 form-block">
                <span class="sub-header">Способ оплаты</span>
				<?
				$pyments = AgsssPyment::getPyments();
				foreach($pyments as $pyment) {
				?>
					<div class="input-border input-border-radio<?=$this->errors['paymentType']?" input-border_error":""?>">
						<label>
							<input 
								value="<?=$pyment['id']?>" 
								name="paymentType" type="radio" 
								<?=(+$pyment['id']===+$this->paymentType)?"checked":""?>
								
							>
							<span class="radio"></span>
							<span class="label"><?=$pyment['name']?></span>
						</label>
					</div>
				<?}?>
				<?if($this->errors['paymentType']){?>
					<p class="error-message"><?=$this->errors['paymentType']?></p>
				<?}?>
            </div>


        </div>

        <div class="dotted-sting-list">
            <div class="dotted-sting">
                <span>
                    Сумма
                </span>
                <span class="dotted"></span>
                <span id="agsss-old-sum">
                    <?=agsss_nf($this->sum + $this->sale_sum)?> ₽
                </span>
            </div>
            <div class="dotted-sting">
                <span>
                    Скидка
                </span>
                <span class="dotted"></span>
                <span class="red" id="agsss-sale">
                    <?=agsss_nf($this->sale_sum)?> ₽
                </span>
            </div>
            <div class="dotted-sting">
                <span>
                    Доставка
                </span>
                <span class="dotted"></span>
                <span class="red" id="agsss-delivery">
					<?if($this->deliveryPrice) {?>
						<?=agsss_nf($this->deliveryPrice)?> ₽
					<?} else {?>
						<?if(!$this->deliveryResultMessaage) {?>
							Бесплатно
						<?} else {?>
							<?=$this->deliveryResultMessaage?>
						<?}?>
					<?}?>
                </span>
            </div>
            <div class="dotted-sting">
                <span>
                    Итого
                </span>
                <span class="dotted"></span>
                <span id="agsss-sum">
                    <?=agsss_nf($this->sum + $this->deliveryPrice)?> ₽
                </span>
            </div>
        </div>

        <div class="order-policy">

            <label class="s-checkbox">
                <input name="policy" type="checkbox" checked>
                <span class="checkmark"></span>
                <div class="label-group">
                    <span>Согласен с условиями обработки<br> персональных данных.</span>
                    <a href="/politika-v-otnoshenii-obrabotki-personalnyh-dannyh/">Политика конфиденциальности</a>
					<?if($this->errors['policy']){?>
						<p class="error-message">Для заказа необходимо принять условия.</p>
					<?}?>
				</div>
            </label>
        </div>
			

        <div class="order-process">
            <button type="submit" class="s-btn s-btn_block">Заказать</button>
        </div>
		</form>
    </div>
</div>
			<?
		}
	}
}

?>