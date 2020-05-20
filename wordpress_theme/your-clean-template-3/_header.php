<!DOCTYPE html>
<?
	$frontend = "0.3";
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
                <!-- <a href="mailto:info@vipbouquet.ru">
					<img data-src="<?=get_template_directory_uri()?>/images/svg/email.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/email.svg">
				</a> -->
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