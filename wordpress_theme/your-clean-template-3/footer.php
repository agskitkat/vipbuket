<footer class="lb-footer">
    <div class="container-1200">
        <div class="lb-footer__content">

            <div class="lb-footer__right">
                <a href="/">
                    <img class="just lb-footer__logo" src="<?=get_template_directory_uri()?>/images/logo.svg" alt="">
                </a>
                <div class="work-time">Ежедневно 09:00-18:00</div>
                <div class="phone">8 (495) 005-18-47</div>
                <div class="phone">8 (499) 347-07-97</div>

                <div class="lb-footer__social">
                    <div class="header">Присоединяйтесь к нам в социальных сетях</div>
                    <div class="social__images">
                        <a href="#"><img class="just" src="<?=get_template_directory_uri()?>/images/social/vk.png" alt=""></a>
                        <a href="#"><img class="just" src="<?=get_template_directory_uri()?>/images/social/inst.png" alt=""></a>
                        <a href="#"><img class="just" src="<?=get_template_directory_uri()?>/images/social/fb.png" alt=""></a>
                        <a href="#"><img class="just" src="<?=get_template_directory_uri()?>/images/social/yt.png" alt=""></a>
                        <a href="#"><img class="just" src="<?=get_template_directory_uri()?>/images/social/tw.png" alt=""></a>
                    </div>
                </div>
            </div>

            <div class="lb-footer__center">
                <div class="goods-category">
					<div class="header">Каталог</div>
					<?
					wp_nav_menu( [
						'theme_location'  => 'bottom',
						'menu'            => '', 
						'container'       => '', 
						'container_class' => '', 
						'container_id'    => '',
						'menu_class'      => '', 
						'menu_id'         => 'bottom',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 2,
						'walker'          => "",
					] );
					?>
                </div>
                <div class="other-menu">
					<div class="menu">
					<div class="header">Топ</div>
					<?
					wp_nav_menu( [
						'theme_location'  => 'bottom-2',
						'menu'            => '', 
						'container'       => '', 
						'container_class' => '', 
						'container_id'    => '',
						'menu_class'      => '', 
						'menu_id'         => 'bottom',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 2,
						'walker'          => "",
					] );
					?>
					</div>
					<div class="menu">
					<div class="header">Информация</div>
					<?
					wp_nav_menu( [
						'theme_location'  => 'bottom-3',
						'menu'            => '', 
						'container'       => '', 
						'container_class' => '', 
						'container_id'    => '',
						'menu_class'      => '', 
						'menu_id'         => 'bottom',
						'echo'            => true,
						'fallback_cb'     => 'wp_page_menu',
						'before'          => '',
						'after'           => '',
						'link_before'     => '',
						'link_after'      => '',
						'items_wrap'      => '<ul id="%1$s" class="%2$s">%3$s</ul>',
						'depth'           => 2,
						'walker'          => "",
					] );
					?>
					</div>
                </div>
            </div>

            <div class="lb-footer__left">
                <div class="payments">
                    <div class="header">Способы оплаты</div>
                    <img src="<?=get_template_directory_uri()?>/images/payments.png" alt="" class="just">
                </div>
                <a class="policy" href="/politika-v-otnoshenii-obrabotki-personalnyh-dannyh/">
                    Политика обработки<br>персональных данных
                </a>
            </div>

        </div>
        <div class="lb-footer__copyright">ЛентаБант© <?=date('Y')?>. Все права защищены</div>
    </div>
</footer>


<div class="form-order">
    <div class="form-block">
		<form class="form__form">
			<div class="close">
				<img class="just" src="<?=get_template_directory_uri()?>/images/svg/form-close.svg">
			</div>
			<div class="content">
				<div class="header">Оформление заказа</div>
				<div class="good">
					<div class="name">Букет с розывыми тюльпанами</div>
					<div class="article-price">
						<div class="article">А1010</div>
						<div class="price">
							<span class="old">4 590 ₽</span><span class="new">2 990 ₽</span>
						</div>
					</div>
					<input type="hidden" class="image">
				</div>
				<div class="order-options">
					<ul>
						<li>
							<label class="container-checkbox">
								<input name="is_postcard" id="postcard" type="checkbox">
								<span class="checkmark"></span>
								<span class="label">Открытка с пожеланиями</span>
							</label>
							149 ₽
						</li>
						 <li class="inputs" style="display: none;">
                            <div class="input">
                                <input name="postcard_text" type="text" placeholder="Введите текст">
                            </div>
                        </li>
						<li>
							<label class="container-checkbox">
								<input name="decoration" type="checkbox">
								<span class="checkmark"></span>
								<span class="label">Украшение для букета Топпер</span>
							</label>
							99 ₽
						</li>
					</ul>
				</div>
				<div class="delivery-free">
					<span>
						Доставка по Москве в пределах МКАД
					</span>
					<span>
						Бесплатно
					</span>
				</div>
				<div class="inputs">
					<div class="input">
						<input name="user_name" type="text" placeholder="Имя">
					</div>
					<div class="input">
						<input name="user_phone" type="text" placeholder="Телефон">
					</div>
					<div class="input">
						<input name="user_email" type="email" placeholder="Email">
					</div>
					<div class="input">
						<img data-src="<?=get_template_directory_uri()?>/images/svg/form-timetable.svg" data-srcset="<?=get_template_directory_uri()?>/images/svg/form-timetable.svg 0w" class="img-opacity"><input name="user_date" type="text" placeholder="Дата получения">
					</div>
					<div class="input">
						<input name="user_addr" type="text" type="" placeholder="Адрес доставки">
					</div>
				</div>
				<div class="errors">
				
				</div>
				<div class="order-btn">
					<button type="submit" id="do_order" class="green-btn" data-wait-text="Оформление...">Заказать</button>
				</div>
			</div>
		</form>
    </div>
</div>

<div class="modal-container video">
    <div class="modal-content"></div>
</div>

<div class="modal-container order-complite">
    <div class="modal-content">
		<div class="order-complite-block">
			<h3>Ваш заказ принят.</h3><br>
			В ближайшее время с Вами свяжется менеджер. Если у Вас возникли какие-либо вопросы, Вы можете связаться с нами по телефону +7 (495) 005-18-47
		</div>
	</div>
</div>


<div id="imageView-desctop" class="modal-container">
    <div class="modal-content image">
        <div class="close">
            <img class="just" src="<?=get_template_directory_uri()?>/images/svg/form-close.svg">
        </div>
        <img class="image" src="">
    </div>
</div>

<div id="imageView" class="modal-container">
    <div class="modal-content good">
        <div class="good-wrap">
            <div class="good">
                <div class="options">
                    <div class="sale js-sale">-30%</div>
                    <div class="hit js-hit">Хит</div>
                </div>
                <div class="video-desktop desktop-only">
                    <img class="just" src="<?=get_template_directory_uri()?>/images/svg/good-video.svg">
                </div>
                <div class="image">
                    <img class="just good-image js-image" src="<?=get_template_directory_uri()?>/images/good/good.png">
                </div>
        
                <div class="desktop-only">
                    <div class="name-article">
                        <div class="name js-name">
                            Букет с розывыми тюльпанами
                        </div>
                        <div class="article js-article">
                            А1010
                        </div>
                    </div>
        
                    <div class="about">
                        <span>описание ></span>
                        <div class="expend js-content">
                            Благоухающий букет наполнит ароматом Ваш дом и согреет сердце. Изысканное сочетание нежности
                            прекрасно подобранных цветов окутает своим уютом! Размеры букета 30 Х 50 см.
                        </div>
                    </div>
                    <div class="price-to-cart">
                        <div class="prices">
                            <span class="price js-price">2 990 ₽</span>
                            <span class="old-price js-old-price">4 590 ₽</span>
                        </div>
                        <div class="to-cart">
                            <button class="green-button">
                                <img class="just" src="<?=get_template_directory_uri()?>/images/svg/good-cart.svg">
                            </button>
                        </div>
                    </div>
        
                </div>
                <div class="mobile-only">
                    <div class="article-line">
                        <div class="article-line__article js-article">
                            А1010
                        </div>
                        <div class="article-line__old-price js-old-price">
                            4 590 ₽
                        </div>
                    </div>
        
                    <div class="name-line">
                        <div class="name-line__name js-name">
                            Вкусный букет из фруктов и роз «Красный бархат»
                        </div>
                        <div class="name-line__price js-price">
                            4 590 ₽
                        </div>
                    </div>
        
                    <div class="mobile-expend-block">
                        <div class="expend-text hide js-content">
                            В составе букета: свежая клубника, черника, гранат, красные розы В составе букета: свежая клубника,
                            черника, гранат, красные розы
                        </div>
                        <div class="expend-fade"></div>
        
                        <span class="expend-button">ещё</span>
                    </div>
        
                    <div class="gry-line"></div>
                </div>
            </div>
        </div>
        <div class="modal-order-button green-button rect to-cart">Заказать</div>
    </div>
</div>



<div class="s-modal" id="add-to-cart-mobile" data-id="">
    <div class="s-modal-content">
        <div class="s-modal-close js-close-modal"></div>
        <div class="s-modal-good">
            <div class="header">Товар добавлен в корзину</div>

            <div class="info-block-layout">
                <div class="image">
                    <img id="atc-image" class="just" src="../images/good/good-2.png" alt="">
                </div>
                <div class="info">
                    <div class="icons-block">
                        <div id="atc-sale" class="icon icon__sale">sale</div>
                    </div>
                    <div id="atc-name" class="name">
                        name
                    </div>
                    <div id="atc-article" class="article">article</div>
                    <div class="price-block">
                        <div id="atc-price" class="price">price</div>
                        <div id="atc-old-price" class="old-price">old-price</div>
                    </div>
                </div>
            </div>

            <div class="good-quantity-block">
                <div class="quantity-header">Кол-во шт.</div>
                <div class="quantity-buttons">
                    <button class="js-decrement s-btn s-btn_block s-btn_h40 s-btn_control">-</button>
                    <div id="atc-quantity" data-quantity="1" class="quantity-number">1</div>
                    <button class="js-increment s-btn s-btn_block s-btn_h40 s-btn_control">+</button>
                </div>
                <div id="atc-one-price" class="quantity-price-item">price_one</div>
            </div>

            <div class="button-block">
                <button class="s-btn s-btn_second s-btn_block s-btn_h40 js-close-modal">Продолжить покупки</button>
                <a href="/cart/" class="s-btn s-btn_block s-btn_h40">Перейти в корзину</a>
            </div>
        </div>
    </div>
</div>

<div class="splash-screen">
    <div class="wheel">
        <img class="just" src="<?=get_template_directory_uri()?>/images/loading.gif" alt="">
    </div>
</div>

<?php wp_footer(); // необходимо для работы плагинов и функционала  ?>


<!-- Yandex.Metrika counter -->
<script type="text/javascript" >
   (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
   m[i].l=1*new Date();k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
   (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

   ym(56815717, "init", {
        clickmap:true,
        trackLinks:true,
        accurateTrackBounce:true,
        webvisor:true
   });
</script>
<noscript><div><img src="https://mc.yandex.ru/watch/56815717" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->

</body>
</html>