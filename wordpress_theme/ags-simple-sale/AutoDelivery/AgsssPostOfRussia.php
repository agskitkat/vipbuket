<?
class AgsssPostOfRussia implements AgsssAutoDelivery {

	
	// Метод публичный
	public function getPriceByOrder(AgsssOrder $order) {
		$address = $order->deliveryCity . ", " .$order->deliveryStreet .", " .$order->deliveryHouse;
		
		$deliveryPrice 	= 	490;
		$errorMessage 	= 	"";
		return ['price' => $deliveryPrice, "message" => $errorMessage];
		
		
		// TODO: Делать точный расчёт !
		
		if(empty($address)) {
			$errorMessage  = "Для расчёта доставки нужен адрес.";
		}
		
		
		if( !$postIndex = self::getPostIndex($address) ) {
			$postIndex = 0;
			return "Ошибка расчёта стоимости доставки";
		}; 
	
	
		// Кеширование запроса 
		if(isset($_COOKIE["CHACHADDRESS"])) {
			
		}
		//$ar = self::getPostPrice("105043", $order->mass);
		
		// TODO:
		try {
			
			$array = json_decode($postIndex);
			//print_r($array);
			if(isset($array[0]->postal_code)) {
				$postal_code = $array[0]->postal_code;
				$ar = self::getPostPrice($postal_code, $order->mass);
				$deliveryPrice = $ar['price'];
				$errorMessage  = $ar['message'];
			} else {
				$errorMessage  =  "Не удалось определить индекс";
			}
			
		} catch(Exception $e) {
			$errorMessage  =  "Не удалось определить индекс";
		}
		
		return ['price' => $deliveryPrice, "message" => $errorMessage];
	}
	
	// Узнаём почтовый индекс
	protected function getPriceFromServer() {
		/* $query = [
			"viewPost" =>
			"viewPostName" =>
			"typePost" => "ground",//("ground" => 1, "avia")
			"typePostName" => "SALE_DH_RUSSIANPOST_" . $profile, // TODO, что такое профиль ?
			"countryCode" => "643"
			"countryCodeName" => 'Российская Федерация',
			"weight" => $weight,
			"value1" => "0",
			"postOfficeId" => 
		]; */
	}
	
	// Узнаём почтовый индекс
	protected function getPostIndex($address) {
		$API_KEY = "604f16ffd9b776c74f15e9f0489d30731ba9c9fb";
		$SECRET = "6019d55e6ddd89e1f0ad4a05578d4021b27476a5";
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, 'https://suggestions.dadata.ru/suggestions/api/4_1/rs/suggest/address');
		//curl_setopt($curl, CURLOPT_URL, 'https://cleaner.dadata.ru/api/v1/clean/address');
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($curl, CURLOPT_ENCODING ,"");
		curl_setopt($curl, CURLOPT_POST, true);
		/* curl_setopt($curl, CURLOPT_POSTFIELDS,	[
			"query" => $address
		]); */ 
		//curl_setopt($curl, CURLOPT_POSTFIELDS, '[ "'.$address.'" ]');
		curl_setopt($curl, CURLOPT_POSTFIELDS, '{ "query": "'.$address.'", "count": 2 }');
		curl_setopt($curl, CURLOPT_HTTPHEADER, array(
			"Content-Type: application/json",
			"Accept: application/json",
			"Authorization: Token $API_KEY",
			"X-Secret: $SECRET",
		));
		$out = curl_exec($curl);
		//print_r($out);
		curl_close($curl);
		return $out;
	}
	
	// Определяем стоимость 
	protected function getPostPrice($postal_code, $mass) {
		$url = "https://postprice.ru/engine/russia/api.php?from=101000&to=".$postal_code."&mass=".$mass."&valuation=0&vat=0";
		
		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, $url);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
		$out = curl_exec($curl);
		curl_close($curl);
		
		$deliveryPrice = 0;
		$errorMessage  = "";
		
		if(!$out) {
			$errorMessage = "Ошибка почтового сервиса.";
		}
		
		try {
			$object = json_decode($out);
			
			if(isset($object->message)) {
				return $object->message;
			}
			
			$deliveryPrice = $object->pkg;
		} catch(Exception $e) {
			$errorMessage = "Ошибка почтового сервиса.";
		}
		
		return ['price' => $deliveryPrice, "message" => $errorMessage];
	}
	
}
?>