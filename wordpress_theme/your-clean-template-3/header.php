<!DOCTYPE html>
<?
	$frontend = "0.2";
	$cart = new AgsssCart();
	$summary = $cart->getCartSummary();
?>


<html <?php language_attributes(); // вывод атрибутов языка ?>><html>
<head>
    <meta charset="<?php bloginfo( 'charset' ); // кодировка ?>">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
	<meta name="google-site-verification" content="9_vRqDao1nexTMy0Nzs3i26UsQUFvzzLR0j-VThmTwg" />
	<meta name="yandex-verification" content="bc7bb00741d09790" />
	
	
	<?php /* RSS и всякое */ ?>
	<!-- 
	<link rel="alternate" type="application/rdf+xml" title="RDF mapping" href="<?php bloginfo('rdf_url'); ?>">
	<link rel="alternate" type="application/rss+xml" title="RSS" href="<?php bloginfo('rss_url'); ?>">
	<link rel="alternate" type="application/rss+xml" title="Comments RSS" href="<?php bloginfo('comments_rss2_url'); ?>">
	-->
	<link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>" />
	

	
	<title><?=get_custom_meta('title')?></title>
	<meta name="description" content="<?=get_custom_meta('description')?>">
	<meta name="keywords" content="<?=get_custom_meta('keywords')?>" />
	<?
		$link = $_SERVER['REQUEST_URI']; 
	?>
	<?if(strpos($link, '?order=') !== false ) {
		$arLink = explode("?", $link);
	?><link rel="canonical" href="https://lentaibant.ru/<?=$arLink[0]?>" /><?}?>
 
	<?php /* Все скрипты и стили теперь подключаются в functions.php */ ?>

	<!--[if lt IE 9]>
	<script src="//html5shiv.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
	
	<?php wp_head(); // необходимо для работы плагинов и функционала ?>
	
   
    <link rel="stylesheet" href="<?=get_template_directory_uri()?>/production/style.min.css?v=<?=$frontend?>" />
	<link rel="stylesheet" href="<?=get_template_directory_uri()?>/style.css?v=<?=$frontend?>" />
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="<?=get_template_directory_uri()?>/production/scripts.min.js?v=<?=$frontend?>"></script>
	<style>
		.separator {
			content: "";
			display:block;
			height: 1px;
			border-bottom:1px solid #DDD;
			overflow:hidden; 
			margin:10px 0px;
			
		}
		.mobile__menu-container .mobile__menu-content .mobile__menu-list .parent-category li .child li.separator {
			margin:0px 0px;
			margin-bottom:20px;
			padding:0px;
			line-height: 0px;
			height: 0px;
		}
		.mobile__menu-container .mobile__menu-content .mobile__menu-list .parent-category li .child .separator a {
			display:none;
		}
	</style>
</head>
<body <?php body_class(); // все классы для body ?>>


<div class="container-1200">
    <header class="header-lb">
        <div class="mobile-lb">

            <div class="top-line">
                <a class="logo-link" href="/"><img class="just logo" src="<?=get_template_directory_uri()?>/images/logo.svg" alt=""></a>
                <a class="phone" href="tel:8(499)347-07-97">8(499)347-07-97</a>
            </div>

            <div class="main-line">
                <a href="#menu_0" class="header-ld-menu" data-target-menu="#menu">
                    <img
                            class="just"
                            src="<?=get_template_directory_uri()?>/images/svg/menu-button-lb.svg"
                            alt="">
                </a>
				<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ) ?>">
					<div class="header-search">
						<input type="hidden" name="post_type" value="buket">
						<input value="<?php echo get_search_query() ?>" name="s" class="header-search__input" placeholder="Поиск подарка" type="text">
						<button type="submit" class="header-search__btn">Найти</button>
					</div>
				</form>
                <a href="/cart/" class="header-cart ">
					<?//if($summary['cart_count']){?>
						<span class="cart-count"><?=$summary['cart_count']?:0?></span>
					<?//}?>
                    <img class="just" src="<?=get_template_directory_uri()?>/images/svg/cart-icon-header.svg" alt="">
                </a>
            </div>

        </div>
        <div class="desktop-lb">
            <div class="top-line">
                <a class="logo-link" href="/">
                    <img class="just logo" src="<?=get_template_directory_uri()?>/images/logo.svg" alt="">
                </a>

                <div class="menu-left">
                    <a href="/oplata/">Оплата</a>
                    <a href="/kontakty/">Контакты</a>
                    <a href="/dostavka/">Доставка</a>
                </div>

                <div class="menu-right">
                    <span class="work-time">Ежедневно 09:00-18:00</span>
                    <a href="/bukety-skidki">Скидки</a>
                    <a href="/">Новинки</a>
                    <a href="/luchshie-bukety">Хит продаж</a>
                </div>
            </div>
            <div class="main-header-block">
                <div class="block-left">
                    <div id="open-desktop-catalog-menu" class="open-catalog">
                        <img src="<?=get_template_directory_uri()?>/images/svg/header-white-menu.svg" class="just open" alt="">
                        <img src="<?=get_template_directory_uri()?>/images/svg/header-blue-menu-close.svg" class="just close" alt="">
                        Каталог
                    </div>
                    <nav id="odcm" class="s-catalog-desktop">
						<?php 
							wp_nav_menu( [
								'theme_location'  => '',
								'menu'            => '', 
								'container'       => '', 
								'container_class' => '', 
								'container_id'    => '',
								'menu_class'      => 'top-level', 
								'menu_id'         => '',
								'echo'            => true,
								'fallback_cb'     => 'wp_page_menu',
								'before'          => '',
								'after'           => '',
								'link_before'     => '',
								'link_after'      => '',
								'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
								'depth'           => 2,
								'walker'          => new True_Walker_Nav_Menu(),
							] );
						?>
                    </nav>
                </div>
                <a class="logo-link" href="/"><img class="just logo" src="<?=get_template_directory_uri()?>/images/logo.svg" alt=""></a>
                <div class="block-right">

                    <div class="phones">
                        <a href="tel:8 (495) 005-18-47">8 (495) 005-18-47</a>
                        <br>
                        <a href="tel:8 (499) 347-07-97">8 (499) 347-07-97</a>
                    </div>
					<form role="search" method="get" id="searchform" action="<?php echo home_url( '/' ) ?>">
						<div class="header-search">
							<input type="hidden" name="post_type" value="buket">
							<input value="<?php echo get_search_query() ?>" name="s" class="header-search__input" placeholder="Поиск подарка" type="text">
							<button type="submit" class="header-search__btn">Найти</button>
						</div>
					</form>
                    <a href="/cart/" class="header-cart">
						<?if($summary['cart_count']){?>
							<span id="js-cart-count" class="cart-count"><?=$summary['cart_count']?></span>
						<?} else {?>
							<span id="js-cart-count" class="cart-count">0</span>
							<? /* <span id="js-cart-count" class="cart-count empty">0</span> */ ?>
						<?}?>
                        <img class="just" src="<?=get_template_directory_uri()?>/images/svg/cart-icon-header.svg" alt="">
                    </a>
                </div>
            </div>
        </div>
    </header>
</div>

<div class="s-catalog-mobile-menu">
    <div class="menu-header">
        <div class="flex-menu-btn">
			<span class="menu-back-link">
			   <img class="just" src="<?=get_template_directory_uri()?>/images/svg/menu-sub-back-arrow.svg" alt="">
			</span>
			Меню
		</div>
		<a href="#"><img class="just " src="<?=get_template_directory_uri()?>/images/svg/menu-close-gray.svg" alt=""></a>
    </div>

    

    <div class="viewport">
        <ul id="menu_viewport">

        </ul>
    </div>

    <nav class="display-items-menu">

		
	<?php 
		wp_nav_menu( [
			'theme_location'  => '',
			'menu'            => '', 
			'container'       => '', 
			'container_class' => '', 
			'container_id'    => 'menu_0',
			'menu_class'      => 'parent-category', 
			'menu_id'         => 'menu_0',
			'echo'            => true,
			'fallback_cb'     => 'wp_page_menu',
			'before'          => '',
			'after'           => '',
			'link_before'     => '',
			'link_after'      => '',
			'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
			'depth'           => 2,
			'walker'          => new mobile_Walker_Nav_Menu(),
		] );
	?>

    </nav>

</div>

<? /*


<div class="container">
    <header>
        <div class="header__bottom-row">
            <div class="bottom-row__block">
                <a href="/kontakty">О компании</a>
                <a href="/dostavka">Доставка</a>
                <a href="/oplata">Оплата</a>
            </div>
            <div class="bottom-row__block">
                <a href="/bukety-skidki">Скидки</a>
                <a href="/modnye-bukety-tsvetov">Модные букеты</a>
                <a href="/luchshie-bukety">Хит продаж</a>
            </div>
            <div class="bottom-row__block">
                <a href="#">Блог</a>
            </div>
            <div class="bottom-row__block">
                <!-- 
				<a href="mailto:info@vipbouquet.ru">
					<img data-src="<?=get_template_directory_uri()?>/images/svg/email.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/email.svg">
				</a> 
				-->
            </div>
        </div>

        <div class="block-1">
            <div class="mobile">
                <a href="/" class="logo">
					<img data-src="<?=get_template_directory_uri()?>/images/svg/lentaibant-logo.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/lentaibant-logo.svg">
				</a>
            </div>
            <div class="menu-phone">
                <div class="menu js-event-click-view-menu">
                    <img data-src="<?=get_template_directory_uri()?>/images/svg/menu-button-humb.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/menu-button-humb.svg 0w">
                </div>
                <div class="phone">
                    <div class="phones">
                    <a href="tel:84950051847" class="desctop-phone">8(495)005-18-47</a>
                    <a href="tel:84993470797">8(499)347-07-97</a>
                    </div>
                    <span class="work-time">
                        <span class="week">ежедневно</span>
                        09:00-21:00
                    </span>
                </div>

            </div>
            <div class="right-block">
			
                <form class="search">
                    <input type="text" name="s" class="search__input js-target-live-search-input" placeholder="Поиск">
					<input type="hidden" value="buket" name="where">
                    <button type="submit" class="search__button">
                        <img data-src="<?=get_template_directory_uri()?>/images/svg/search-icom.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/search-icom.svg 0w">
                    </button>
					<div class="live-search hide">
                        <div class="live-search__container js-target-simplebar"  data-simplebar-auto-hide>
                            <div class="live-search__item">
								
                            </div>
                        </div>
                    </div>
                </form>
				
                <a href="/cart/" class="cart" id="mini-cart">
                    <img 
						data-src="<?=get_template_directory_uri()?>/images/svg/cart-icon.svg" 
						data-srcset="<?=get_template_directory_uri()?>/images/svg/cart-icon.svg 0w">
						<?php
							$cart = new AgsssCart();
							$summary = $cart->getCartSummary();
						?>
					<span class="items">
						<?echo $summary['sum']?"В корзине: ".$summary['cart_count']:"Корзина"?>
					</span>
					<?if(!$summary['sum']){?>
						<div class="cart-info">Выберите товар и нажмите на кнопку <b>купить</b>.</div>
					<?}else{?>
						<div class="cart-info">На сумму <?=$summary['sum']?> ₽.</div>
					<?}?>
                </a>
            </div>
        </div>
    </header>

    <div class="desktop-menu">

        <?php 
			wp_nav_menu( [
				'theme_location'  => '',
				'menu'            => '', 
				'container'       => 'nav', 
				'container_class' => '', 
				'container_id'    => '',
				'menu_class'      => 'parent-category', 
				'menu_id'         => '',
				'echo'            => true,
				'fallback_cb'     => 'wp_page_menu',
				'before'          => '',
				'after'           => '',
				'link_before'     => '',
				'link_after'      => '',
				'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
				'depth'           => 2,
				'walker'          => new True_Walker_Nav_Menu(),
			] );
		?>

    </div>

    <div class="mobile__menu-container" id="js-target-mobile-menu">
        <div class="mobile__menu-content">
            <div class="mobile__menu-header">
                Меню<img class="js-event-click-hide-menu" data-src="<?=get_template_directory_uri()?>/images/svg/menu-close.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/menu-close.svg 0w">
            </div>
            <div class="mobile__menu-list">

				<?php 
					wp_nav_menu( [
						'theme_location'  => '',
						'menu'            => '', 
						'container'       => '', 
						'container_class' => '', 
						'container_id'    => '',
						'menu_class'      => 'parent-category', 
						'menu_id'         => '',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul class="%2$s">%3$s</ul>',
						'depth'           => 2,
						'walker'          => new mobile_Walker_Nav_Menu(),
					] );
				?>
                
            </div>
        </div>
    </div>

    <div class="tablet-menu">
        <div class="menu">
            <img class="js-event-click-view-menu" data-src="<?=get_template_directory_uri()?>/images/svg/menu-button.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/menu-button.svg 0w"> Меню
        </div>
    </div>
</div>

*/
?>