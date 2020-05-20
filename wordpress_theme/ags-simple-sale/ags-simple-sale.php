<?php
const STORETITLE = "Простейший интернет магазин";
const MENUNAME = "Магазин";
const PNAME = "ags-simple-sale";

/*
 * Plugin Name: Простейший интернет магазин
 * Plugin URI: https://topm.ru
 * Description: Простейший интернет магазин - установи и торгуй
 * Version: 1.1.1
 * Author: Гошаныч
 * Author URI: https://topm.ru
 * License: GPLv2 or later
*/

function agsss_nf($n) {
	return number_format(+$n, 0, '', ' ');
}

/* ЗАКЗАЫ */
function create_post_type() {
	$POST_TYPE_NAME = "agsss_order";	
	// Регистрация заказов
	$labels = array(
		'name' => 'Заказ',
		'singular_name' => 'Заказ', // админ панель Добавить->Функцию
		'add_new' => 'Новый заказ',
		'add_new_item' => 'Добавить', // заголовок тега <title>
		'edit_item' => 'Редактировать заказ',
		'new_item' => 'Новый заказ',
		'all_items' => 'Все заказы',
		'view_item' => 'Просмотр заказа на сайте',
		'search_items' => 'Искать заказ',
		'not_found' =>  'Заказа не найдено.',
		'not_found_in_trash' => 'В корзине нет заказа.',
		'menu_name' => 'Заказы' // ссылка в меню в админке
	);
	$args = array(
		'labels' => $labels,
		'public' => false,
		'show_ui' => true, // показывать интерфейс в админке
		'has_archive' => true, 
		'menu_icon' => __DIR__ .'/../images/icon_slide.png', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports' => array( 'title', 'custom-fields')
	);
    register_post_type($POST_TYPE_NAME, $args);
}
add_action( 'init', 'create_post_type' ); 



function my_fields() {
	add_meta_box( 
		"extra_fields", 
		"Дополнительные поля", 
		"agsss_form_display_callback",
		"agsss_order", 
		"normal", 
		"high" 
	);
}
add_action("admin_init", "my_fields", 1); 

/**
 * Save meta box content.
 *
 * @param int $post_id Post ID
 */
function agsss_save_meta_box( $post_id ) {
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) return;
    if ( $parent_id = wp_is_post_revision( $post_id ) ) {
        $post_id = $parent_id;
    }
    $fields = [
        'agsss-order-delivery',
        'agsss-order-pyment',
		'agsss-order-userName',
        'agsss-order-userEmail',
		'agsss-order-userPhone',
        'agsss-order-deliveryCity',
		'agsss-order-deliveryStreet',
        'agsss-order-deliveryHouse',
		'agsss-order-deliveryApart',
		'agsss-order-cart',
    ];
    foreach ( $fields as $field ) {
        if ( array_key_exists( $field, $_POST ) ) {
            update_post_meta( $post_id, $field, sanitize_text_field( $_POST[$field] ) );
        }
     }
}
add_action( 'save_post', 'agsss_save_meta_box' );

function agsss_form_display_callback( $post ) {
	$deliverys 	= 	AgsssDelivery::getDeliverys();
	$pyments 	= 	AgsssPyment::getPyments();
	//$goods 		=	AgsssCart::get_cart();
?>
<div class="agsss_fields">
	<table class="form-table" role="presentation">
		<tr>
			<th scope="row">
				<label>Имя заказчика</label>
			</th>
			<td>
				<input name="agsss-order-userName" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-userName', true )?>" class="regular-text">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Почта</label>
			</th>
			<td>
				<input name="agsss-order-userEmail" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-userEmail', true )?>" class="regular-text">
			</td>
		</tr>		
		<tr>
			<th scope="row">
				<label>Телефон</label>
			</th>
			<td>
				<input name="agsss-order-userPhone" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-userPhone', true )?>" class="regular-text">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Город</label>
			</th>
			<td>
				<input name="agsss-order-deliveryCity" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-deliveryCity', true )?>" class="regular-text">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Улица</label>
			</th>
			<td>
				<input name="agsss-order-deliveryStreet" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-deliveryStreet', true )?>" class="regular-text">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Дом</label>
			</th>
			<td>
				<input name="agsss-order-deliveryHouse" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-deliveryHouse', true )?>" class="regular-text">
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Номер квартиры\офиса</label>
			</th>
			<td>
				<input name="agsss-order-deliveryApart" type="text" value="<?=get_post_meta( get_the_ID(), 'agsss-order-deliveryApart', true )?>" class="regular-text">
			</td>
		</tr>
	
		<tr>
			<th scope="row">
				<label>Доставка</label>
			</th>
			<td>
				<select name="agsss-order-delivery">
					<?foreach($deliverys as $delivery){?>
						<option value="<?=$delivery['id']?>" 
						<? if(+get_post_meta( get_the_ID(), 'agsss-order-delivery', true ) === +$delivery['id']){ ?>
							selected
						<?}?>>
						<?=$delivery['name']?>
						</option>
					<?}?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Оплата</label>
			</th>
			<td>
				<select name="agsss-order-pyment">
					<?foreach($pyments as $pyment){?>
						<option value="<?=$pyment['id']?>" 
						<? if(+get_post_meta( get_the_ID(), 'agsss-order-pyment', true ) === +$pyment['id']){ ?>
							selected
						<?}?>>
						<?=$pyment['name']?>
						</option>
					<?}?>
				</select>
			</td>
		</tr>
		<tr>
			<th scope="row">
				<label>Заказ</label>
			</th>
			<td>
				<?
				$elements = unserialize(get_post_meta( get_the_ID(), 'agsss-order-cart', true ));
				if(empty($elements)) {
				?>
					<h3>Нет товаров</h3>
				<?
				} else {
					$sum = 0;
					foreach($elements as $element) {
					$sum += +$element['sum'];
				?>
					<div style="clear:both; padding-bottom: 10px;">
						<img style="width:120px; float:left; margin-right: 5px;" src="<?=$element['img']?>">
						<b>Артикул: <?=$element['title']?><br></b>
						Артикул: <?=$element['article']?><br>
						Количество: <?=$element['quantity']?> шт.<br>		
						Сумма: <?=$element['sum']?> ₽<br>
						<div style="clear:both;"></div>
					</div>
				<?
					}
					?>
					<h3>Сумма товаров: <?=$sum?> ₽</h3>
					<h3>Доставка: <?=$sum?> ₽</h3>
					<?
				}
				?>
			</td>
		</tr>
	</table>
</div>
<?
}

/* /ЗАКЗАЫ */

$sittings = [];

add_action( 'admin_menu', 'register_my_page' );
add_filter( 'option_page_capability_'. PNAME . '-menu', 'my_page_capability' );

// Добавим видимость пункта меню для Редакторов
function register_my_page(){
	add_menu_page( 
		STORETITLE, 
		MENUNAME, 
		'edit_others_posts', 
		PNAME . '-menu', 
		'pluginContent', 
		plugins_url( PNAME . '/resource/images/online-store-20x20.png' ), 
		20 
	);
}

// Изменим права
function my_page_capability( $capability ) {
	return 'edit_others_posts';
}

// Интерфейс автоматической службы доставки
interface AgsssAutoDelivery {
	/**
	*	Возвращает цену доставки
	* 	AgsssOrder $order - Объект заказа, собирает инфу о заказе. 
	* 	return Intager
	*/
	public function getPriceByOrder(AgsssOrder $order);
}

include("Classes/delivery.php");
include("Classes/order.php");
include("Classes/cart.php");
include("Classes/pyment.php");

$message = ""; //  Сообщуля

// Роутер действий
if(isset($_GET['action'])) 
if($_GET['action'] === "addDelivery") {
	AgsssDelivery::addDelivery();
} 

if(isset($_GET['action'])) 
if($_GET['action'] === "removeDelivery") {
	if($id = intval($_GET['id'])) {
		if(AgsssDelivery::removeDeliveryID($id)) {
			$message = "Удалено !";
		} else {
			$message = "Fail removing !";
		};
	} else {
		$message = "Fail id !";
	}
}

if(isset($_GET['action'])) 
if($_GET['action'] === "addPyment") {
	AgsssPyment::addPyment();
}

if(isset($_GET['action'])) 
if($_GET['action'] === "removePayment") {
	
	if($id = intval($_GET['id'])) {
		if(AgsssPyment::removePayment($id)) {
			$message = "Удалено !";
		} else {
			$message = "Fail removing !";
		};
	} else {
		$message = "Fail id !";
	}
	
}

if(isset($_GET['action']))
if($_GET['action'] === "savePyments") {
	$pyments = [];
	if(!count($_POST['pyments'])) {
		return false;
	}
	foreach($_POST['pyments'] as $key => $pyment) {

		$pyments[] = [
			"id" => $key,
			"name" => $pyment['name']
		]; 
	
	}
	//print_r($pyments);
	AgsssPyment::updatePyments($pyments);
}

add_action( 'admin_head', 'agsss_admin_style' );
function agsss_admin_style(){ ?>
	<style>
		.agsss-deliverys {
			
		}
		.agsss-list {
			padding:10px 0px;
		}
		.agsss-delivery {
			border: 1px solid #DDD;
			padding: 20px;
		}
		.agsss-delivery h2 {
			margin: 0px;
		}
		.agsss-delivery .remove {
			
		}
		.agsss-delivery table table input {
			max-width: 120px; 
		}
	</style>
<?}

// AJAX update deliverys
add_action( 'wp_ajax_updateDelivery', 'updateDelivery' );
function updateDelivery() {
	if(count($_POST['data'])) {
		
		foreach($_POST['data'] as &$delivery) {
			$str = $delivery['deliveryPrice'];
			$str = str_replace("\\", "", $str);
			$arr = json_decode($str);
			foreach ($arr as &$item ) {
				$item = (array) $item;
			}
			$delivery['deliveryPrice'] = $arr;
		}
		
		echo AgsssDelivery::updateDeliverys($_POST['data']);
		die();
	}
}

//AJAX AgsssRecalculateOrder - перерасчёт ордера
add_action( 'wp_ajax_AgsssRecalculateOrder', 'AgsssRecalculateOrder' );
add_action( 'wp_ajax_nopriv_AgsssRecalculateOrder', 'AgsssRecalculateOrder' );
function AgsssRecalculateOrder() {
	$order = new AgsssOrder();
	$order->getOrderVarsFromPost();
	$order->setOrderVars();
	$order->recalculateOrder();
	$order->render('ajax');
	die();
}

// Выполнить заказ
add_action( 'wp_ajax_doorder', 'AgsssDoOrder' );
add_action( 'wp_ajax_nopriv_doorder', 'AgsssDoOrder' );
function AgsssDoOrder() {
	$order = new AgsssOrder(); 
}

add_action( 'admin_head', 'agsss_admin_script' );
function agsss_admin_script(){ ?>
	<script>
		function addDeliveryPrice(elem) {
			var delivery = jQuery(elem).closest('.agsss-delivery');
			var tbody = jQuery(delivery).find('.delivery-price-table tbody');
			jQuery(tbody)
			.append("<tr><td><input name='sort' type='number'></td><td><input name='min' type='number'></td><td><input name='max' type='number'></td><td><input name='price' type='number'></td><td><a onclick='removeDeliveryPrice(this)'>Удалить</a></td></tr>");
		}
		
		function removeDeliveryPrice(elem) {
			jQuery(elem).closest('tr').remove();
		}
		
		function removeDelivery(elem) {
			var delivery = jQuery(elem).closest('.agsss-delivery');
			var id = jQuery(delivery).attr("data-delivery-id");
		}
		
		function saveAllDelivery() {
			var deliverys = [];
			jQuery('#delivery-list .agsss-delivery').each(function(key, value){
				var delivery = jQuery(value);
				
				// ид доставки
				var id = delivery.attr("data-delivery-id"); 
				var tbody = delivery.find('.delivery-price-table tbody');
				
				
				var name 			= delivery.find('input[name=name]').val();
				var functionName 	= delivery.find('input[name=functionName]').val();
				
				var deliveryPrice = [];
				jQuery(tbody).find('tr').each(function(key, value){
					var tr = {
						'sort': jQuery(value).find('input[name=sort]').val(),
						'min': jQuery(value).find('input[name=min]').val(),
						'max': jQuery(value).find('input[name=max]').val(),
						'price': jQuery(value).find('input[name=price]').val()
					}
					
					
					deliveryPrice.push(tr);
				});
				
				delivery = {
					id: id,
					name: name,
					functionName: functionName,
					deliveryPrice: JSON.stringify(deliveryPrice)
				}
				deliverys.push(delivery);
			});	
			
			 var data = {
				action: 'updateDelivery',
				data: deliverys
			};
			
			jQuery.post('/wp-admin/admin-ajax.php', data, function (response) {
				if(response == 1) {
					alert("Сохранено !");
				}
			});
		}
	</script>
<?}


// Отображение настроек плагина
function pluginContent() {
	$deliverys 	= 	AgsssDelivery::getDeliverys();
	$pyments 	= 	AgsssPyment::getPyments();
	?>
	<div class="wrap">
		<?if(!empty($message)) {?>
			<?=$message;?>
		<?}?>
		<h1>Настройки интернет магазина</h1>
	
		<h2>Способы доставки</h2>
		<div class="agsss-deliverys">
			<a class="button button-primary" href="?page=<?=PNAME . '-menu'?>&action=addDelivery">Добавить доставку</a>
			<div id="delivery-list" class="agsss-list">
				<?foreach($deliverys as $delivery) {?>
					<div data-delivery-id="<?=$delivery['id']?>" id="delivery-<?=$delivery['id']?>" class="agsss-delivery">
						<a href="?page=<?=PNAME . '-menu'?>&action=removeDelivery&id=<?=$delivery['id']?>" class="delete">Удалить доставку</a>
						<table class="form-table" role="presentation">
							<tr>
								<th scope="row">
									<label>Название доставки</label>
								</th>
								<td>
									<input name="name" type="text" value="<?=$delivery['name']?>" class="regular-text">
									<p class="description">Доставка без названия отображаться не будет</p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>Функция обработчик</label>
								</th>
								<td>
									<input name="functionName" type="text" value="<?=$delivery['functionName']?>" class="regular-text">
									<p class="description">Специальный класс интерфеса AgsssAutoDelivery</p>
								</td>
							</tr>
							<tr>
								<th scope="row">
									<label>Цены на доставку</label>
								</th>
								<td>
									<p class="description">Цена доставки в зависимости от общей стоимости заказа</p>
									<table class="delivery-price-table form-table">
										<thead>
											<tr>
												<td>Порядок</td>
												<td>MIN сумма заказа</td>
												<td>MAX сумма заказа</td>
												<td>Стоимость доставки</td>
												<td>Удалить</td>
											</tr>
										</thead>
										<tbody>
											<?foreach($delivery['deliveryPrice'] as $tr) {?>
											<tr>
												<td>
													<input name='sort' value="<?=$tr['sort']?>" type='number'>
												</td>
												<td>
													<input name='min' value="<?=$tr['min']?>" type='number'>
												</td>
												<td>
													<input name='max' value="<?=$tr['max']?>" type='number'>
												</td>
												<td>
													<input name='price' value="<?=$tr['price']?>" type='number'>
												</td>
												<td>
													<a onclick='removeDeliveryPrice(this)'>Удалить</a>
												</td>
											</tr>
											<?}?>
										</tbody>
									</table>
									<button class="button button-primary" onclick="addDeliveryPrice(this)">Добавить цену доставку</button>
								</td>
							</tr> 
						</table>
					</div>
				<?}?>
				<p></p>
				<button class="button button-primary" onclick="saveAllDelivery()">Сохранить изменения в доставках</button>						
			</div>
		</div>
	
		
		<h2>Способы оплаты</h2>
		<div class="agsss-payments">
			<a class="button button-primary" href="?page=<?=PNAME . '-menu'?>&action=addPyment">Добавить способ оплаты</a>
			<form action="?page=<?=PNAME . '-menu'?>&action=savePyments" method="POST">
				<div id="delivery-list" class="agsss-list">
					<?foreach($pyments as $pyment) {?>
						<div data-pyment-id="<?=$pyment['id']?>" id="pyment-<?=$pyment['id']?>" class="agsss-delivery">
							<a href="?page=<?=PNAME . '-menu'?>&action=removePayment&id=<?=$pyment['id']?>" class="delete">Удалить способ оплаты</a>
							<table class="form-table" role="presentation">
								<tr>
									<th scope="row">
										<label>Название оплаты</label>
									</th>
									<td>
										<input name="pyments[<?=$pyment['id']?>][name]" type="text" value="<?=$pyment['name']?>" class="regular-text">
										<p class="description">Олата без названия отображаться не будет</p>
									</td>
								</tr>
							</table>
						</div>
						<br>
					<?}?>
				</div>
				<button class="button button-primary" type="submit">Сохранить оплаты</button>
			</form>
		</div>
		
	</div>
	<?
}
