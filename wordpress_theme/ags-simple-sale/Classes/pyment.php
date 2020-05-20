<?
class AgsssPyment {
	static $key = 'AgsssPymentsList';
	
	// Новые способы оплаты
	static function addPyment() {
		$pyments = self::getPyments();
		
		// Определяет последний ID
		$last = 0;
		if(count($pyments)) {
			foreach($pyments as $pyment) {
				if($last < +$pyment['id']) {
					$last = +$pyment['id'];
				}
			}
		}
		
		$pyments[] = ["id"=>++$last];
		
		
		self::updatePyments($pyments);
	}
	
	// Обновить 
	static function updatePyments($pyments) {
		$pyments = serialize($pyments);
		update_option(self::$key, $pyments);
		return true;
	}
	
	// Получить все способы оплаты
	static function getPyments() {
		$pyments = [];
		$jsonPyments = get_option(self::$key);
		
		try {
			if(!empty($jsonPyments)) {
				if($object = unserialize($jsonPyments)) {
					$pyments = $object;
				};
			}
		} catch(Exception $e) {
			
		}
		
		return $pyments;
	}
	
	// Удалить 
	static function removePayment($id) {
		
		$pyments = self::getPyments();

		foreach($pyments as $key => &$pyment) {
			
			if(+$pyment['id'] === +$id) {
				unset($pyments[$key]);
				self::updatePyments($pyments);
				return true;
			}
			
		}
		return false;
	}

	static function getPymentById($id) {
		$pyments = self::getPyments();
		foreach($pyments as $key => &$pyment) {
			if(+$pyment['id'] === +$id) {
				return $pyment;
			}
		}
	}
	
}