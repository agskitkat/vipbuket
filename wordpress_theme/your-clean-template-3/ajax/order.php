<?

// Добавляем в корзину
add_action( 'wp_ajax_doorder', 'doOrder' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_doorder', 'doOrder' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}

function doOrder() {
	
}