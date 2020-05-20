<? include("header.php") ?>

<?
foreach($this->cart->getItems() as $item) {
	$itemsList .= '<!-- ITEM -->
			<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 20px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
				<tr>
					<td width="55px" vertical-align="middle">
						<a href="#" target="_blank">
							<img src="'.$item['img'].'" style="width: 120px;">
						</a>
					</td>
					<td style="padding-left:10px;">
						<p style="font-size: 13px; margin-top:0px;margin-bottom:0px;">['.$item['article'].']</p>
						<p style="font-size: 13px; margin-top:10px;margin-bottom:0px;">'.$item['title'].'</p>
						
						<table style="width:100%">
							<tr>
								<td>
									<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>'.$item['price'].' ₽ x '.intval($item['quantity']).'</b></p>
								</td>
								<td align="right">
									<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>'.$item['sum'].' ₽</b></p>
								</td>
							</tr>';

			if($item['sale']) {
				$itemsList .= '				
					<tr>
						<td>
							<p style="font-size: 13px; margin-top:0px;margin-bottom:0px;color:#FF0000"><b>'.$item['sale'].'%‬</b></p>
						</td>
						<td align="right">
							<p style="font-size: 13px; margin-top:0px;margin-bottom:0px;color:#FF0000"><b>'.($item['price'] - $item['old_price_sum']).'‬ ₽</b></p>
						</td>
					</tr>	
				';
			}
			
			$itemsList .= '		
							</table>
						</td>
					</tr>
				</table>
			';
			
			$itemsList .= '
			<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 20px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
				<tr>
					<td>
						<!-- GRAY ROW -->
						<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" 
						style="background-color: #FFFFFF; max-width: 600px; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; padding-right: 0px; width: 100%;">
							<tr>
								<td width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #E8E8E8; height:1px;"></td>
							</tr>
						</table>
					</td>
				</tr>
			</table>
		<!-- /ITEM -->';
}


	$str .= '<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 10px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
		<tr>
			<td align="left" width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #FFF;">
				<p style="font-size: 14px; margin-top:10px;margin-bottom:0px;">Уважаемый(ая)</p>
				<p style="font-size: 18px; margin-top:10px;margin-bottom:0px;"><b>'.$this->userName.'</b></p>
			</td>
		</tr>
	</table>';

	$str .= '<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 10px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
		<tr>
			<td align="center" width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #FFF;">
				<p style="font-size: 18px; margin-top:10px;margin-bottom:0px;"><b>Ваш заказ № '.$this->orderId.'</b></p>
				<p style="font-size: 13px; margin-top:10px;margin-bottom:0px;">от '.date("Y-m-d H:i").' принят.</p>
			</td>
		</tr>
	</table>';


	$str .= '<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 10px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
		<tr>
			<td align="left" width="50%" style="padding-top: 0px; padding-bottom: 0px; background: #FFF;">
				<p style="font-size: 13px; margin-top:10px;margin-bottom:0px;"><b>Продукция</b></p>
			</td>
			<td align="right" width="50%" style="padding-top: 0px; padding-bottom: 0px; background: #FFF;">
				<p style="font-size: 13px; margin-top:10px;margin-bottom:0px;"><b>Сумма</b></p>
			</td>
		</tr>
	</table>';

	$str .= '<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 20px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
		<tr>
			<td>
				<!-- GRAY ROW -->
				<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" 
				style="background-color: #FFFFFF; max-width: 600px; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; padding-right: 0px; width: 100%;">
					<tr>
						<td width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #E8E8E8; height:1px;"></td>
					</tr>
				</table>
			</td>
		</tr>
	</table>';


	$str .= $itemsList;


	$str .= '<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 20px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
		<tr>
			<td align="right" width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #FFF;">
				<table>
				
					<tr>
						<td style="padding-right:10px"><p style="font-size: 14px; margin-top:0px;margin-bottom:0px;">Вес</p></td>
						<td><p style="font-size: 14px; margin-top:0px;margin-bottom:0px;"><b>~'.round(($this->cart->mass / 1000), 2).' кг</b></p></td>
					</tr>';
					
					if($this->sale_sum){
						$str .= '<tr>
							<td style="padding-right:10px"><p style="font-size: 14px; margin-top:0px;margin-bottom:0px;">Сумма скидки</p></td>
							<td><p style="font-size: 14px; margin-top:0px;margin-bottom:0px;"><b>'.$this->sale_sum.' ₽</b></p></td>
						</tr>';
					}
					
					$str .= '<tr>
						<td style="padding-right:10px"><p style="font-size: 14px; margin-top:0px;margin-bottom:0px;">Итого к оплате</p></td>
						<td><p style="font-size: 14px; margin-top:0px;margin-bottom:0px;"><b>'.$this->sum.' ₽‬</b></p></td>
					</tr>';
			

			
	$str .= 	'</table>
			</td>
		</tr>
	</table>';

	$str .= '
	<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" style="padding-bottom: 20px;  padding-top:0px;  padding-left: 20px; padding-right: 20px; background-color: #FFFFFF; max-width: 600px;  width: 100%;">
		<tr>
			<td align="left" width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #FFF;">
				<!-- GRAY ROW -->
				<table align="center" class="content" cellpadding="0" cellspacing="0" border="0" 
				style="background-color: #FFFFFF; max-width: 600px; padding-bottom: 0px; padding-top: 0px; padding-left: 0px; padding-right: 0px; width: 100%;">
					<tr>
						<td width="100%" style="padding-top: 0px; padding-bottom: 0px; background: #E8E8E8; height:1px;"></td>
					</tr>
				</table>

				<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>Способ получения товара:</b></p>
				<p style="font-size: 13px; margin-top:0px;margin-bottom:20px;">'.$tmp_delivery['name'].'</p>';

	$str .= '			
				<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>Стоимость доставки:</b></p>
				<p style="font-size: 13px; margin-top:0px;margin-bottom:20px;">'.($calc->price?:"Бесплатно").'</p>';
				


	$str .= '			
				<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>Метод оплаты:</b></p>
				<p style="font-size: 13px; margin-top:0px;margin-bottom:20px;">'.$payment['name'].'</p>
				
				<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>Контактная информация:</b></p>
				<p style="font-size: 13px; margin-top:0px;margin-bottom:20px;">'.$this->userPhone.', '.$this->userName.'</p>';
				
				
				
	$str .= '
				<p style="font-size: 13px; margin-top:10px;margin-bottom:10px;"><b>Адрес доставки:</b></p>
				<p style="font-size: 13px; margin-top:0px;margin-bottom:20px;">'.$this->deliveryCity . ", " . $this->deliveryStreet . ", " . $this->deliveryHouse. ", " .$this->deliveryApart.'</p>
';
				
				
				
				$str .= '
				<p style="font-size: 13px; margin-top:0px;margin-bottom:0px;">Спасибо за покупку!<br>С уважением,<br> администрация <a href="https://lentaibant.ru/">Интернет-магазина</a></p>
				
			</td>
		</tr>
	</table>
	';
	
	echo $str;
?>
<? include("footer.php") ?>