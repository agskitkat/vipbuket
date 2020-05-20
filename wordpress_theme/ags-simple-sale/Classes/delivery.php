<?
// Класс формирования списка доставок
class AgsssDelivery {
	static $key = 'AgsssDeliverysList';
	
	// Получить все доставки
	static function getDeliverys() {
		$deliverys = [];
		$jsonDeliverys = get_option(self::$key);
		
		try {
			if(!empty($jsonDeliverys)) {
				
				if($object = unserialize($jsonDeliverys)) {
					$deliverys = $object;
				};
			}
		} catch(Exception $e) {
			
		}
		
		return $deliverys;
	}

	//Получить одну delivery по ID
	static  function getDeliveryById($id) {
		$deliverys = self::getDeliverys();
		foreach($deliverys as &$delivery) {
			if($delivery['id'] === $id) {
				return $delivery;
			}
		}
		return false;
	}

	// Добавить доставку
	static function addDelivery() {
		$deliverys = self::getDeliverys();
		
		// Определяет последний ID
		$last = 0;
		if(count($deliverys)) {
			foreach($deliverys as $delivery) {
				if($last < +$delivery['id']) {
					$last = +$delivery['id'];
				}
			}
		}
		
		$deliverys[] = ["id"=>++$last];
		self::updateDeliverys($deliverys);
	}
	
	// Обновить доставки
	/**
	*	$deliverys	
	= [
		[
			'id' => 1, // уникальный
			'name' => '',
			'functionName' => '',
			'deliveryPrice' => [
				[
					'sort' 	=> 	'sort',
					'min'	=>	'min',
					'max'	=> 	'max',
					'price'	=> 	'price'
				],
				...
			]
		],
		...
	]
	*/
	static function updateDeliverys($deliverys) {
		//print_r($deliverys);
		
		foreach($deliverys as $key => &$delivery) {
			if(!$delivery['id']) {
				unset($deliverys[$key]);
			}
		}
		
		$jsonDeliverys = serialize($deliverys);
		update_option(self::$key, $jsonDeliverys);
		return true;
	}
	
	// Удалить доставку по ID
	static function removeDeliveryID($id) {
		$deliverys = self::getDeliverys();
		//print_r($deliverys);
		foreach($deliverys as $key => &$delivery) {
			
			if(+$delivery['id'] === +$id) {
				unset($deliverys[$key]);
				
				self::updateDeliverys($deliverys);
				return true;
			}
			
		}
		
		return false;
	}

	// Расчитать доставку на основе заказа
	static function calculateDelivery(AgsssOrder $order) {
		$deliveryPrice = 0;
		$errorMessage = "";
		
		$id = $order->deliveryType;
		$sum = +$order->sum;
		
		$delivery = self::getDeliveryById($id);
		
		if($delivery) {
			// Есть ли автоообработчик
			if($className = $delivery['functionName']) {
				$classFile = __DIR__ . "/../AutoDelivery/".$className.".php";
				
				// Грузим класс и работаем с ним
				if(file_exists($classFile)) {
					include_once($classFile);
					$autoDelivery = new $className;
					$ar = $autoDelivery->getPriceByOrder($order);
					$deliveryPrice =  $ar['price'];
					$errorMessage = $ar['message'];
				} else {
					$errorMessage = "Ошибка расчёта стоимости доставки";
				}
				
			} else {
				// Проверяем сколько стоит
				foreach($delivery['deliveryPrice'] as $range) {
					if(floatval($range['min']) <= +$sum && floatval($range['max']) >= +$sum) {
						$deliveryPrice = +$range['price'];
						break;
					}
				}				
			}
			
			
		}
		
		return ['price' => $deliveryPrice, 'message' => $errorMessage];
	}
}
?>