<?php
/**
 * Функции шаблона (function.php)
 * @package WordPress
 * @subpackage your-clean-template-3
 */
// add_theme_support('title-tag'); // теперь тайтл управляется самим вп


register_nav_menus(array( // Регистрируем 2 меню
	'top' => 'Верхнее', // Верхнее
	'bottom' => 'Внизу 1', // Внизу
	'bottom-2' => 'Внизу 2', 
	'bottom-3' => 'Внизу 3',
	'bottom-4' => 'Внизу 4', 
	'bottom-5' => 'Внизу 5'
));

add_theme_support('post-thumbnails'); // включаем поддержку миниатюр
set_post_thumbnail_size(250, 150); // задаем размер миниатюрам 250x150
add_image_size('big-thumb', 400, 400, true); // добавляем еще один размер картинкам 400x400 с обрезкой

add_image_size('slide-0', 210, 200);
add_image_size('slide-768', 338, 250 );

register_sidebar(array( // регистрируем левую колонку, этот кусок можно повторять для добавления новых областей для виджитов
	'name' => 'Сайдбар', // Название в админке
	'id' => "sidebar", // идентификатор для вызова в шаблонах
	'description' => 'Обычная колонка в сайдбаре', // Описалово в админке
	'before_widget' => '<div id="%1$s" class="widget %2$s">', // разметка до вывода каждого виджета
	'after_widget' => "</div>\n", // разметка после вывода каждого виджета
	'before_title' => '<span class="widgettitle">', //  разметка до вывода заголовка виджета
	'after_title' => "</span>\n", //  разметка после вывода заголовка виджета
));

if (!class_exists('clean_comments_constructor')) { // если класс уже есть в дочерней теме - нам не надо его определять
	class clean_comments_constructor extends Walker_Comment { // класс, который собирает всю структуру комментов
		public function start_lvl( &$output, $depth = 0, $args = array()) { // что выводим перед дочерними комментариями
			$output .= '<ul class="children">' . "\n";
		}
		public function end_lvl( &$output, $depth = 0, $args = array()) { // что выводим после дочерних комментариев
			$output .= "</ul><!-- .children -->\n";
		}
	    protected function comment( $comment, $depth, $args ) { // разметка каждого комментария, без закрывающего </li>!
	    	$classes = implode(' ', get_comment_class()).($comment->comment_author_email == get_the_author_meta('email') ? ' author-comment' : ''); // берем стандартные классы комментария и если коммент пренадлежит автору поста добавляем класс author-comment
	        echo '<li id="comment-'.get_comment_ID().'" class="'.$classes.' media">'."\n"; // родительский тэг комментария с классами выше и уникальным якорным id
	    	echo '<div class="media-left">'.get_avatar($comment, 64, '', get_comment_author(), array('class' => 'media-object'))."</div>\n"; // покажем аватар с размером 64х64
	    	echo '<div class="media-body">';
	    	echo '<span class="meta media-heading">Автор: '.get_comment_author()."\n"; // имя автора коммента
	    	//echo ' '.get_comment_author_email(); // email автора коммента, плохой тон выводить почту
	    	echo ' '.get_comment_author_url(); // url автора коммента
	    	echo ' Добавлено '.get_comment_date('F j, Y в H:i')."\n"; // дата и время комментирования
	    	if ( '0' == $comment->comment_approved ) echo '<br><em class="comment-awaiting-moderation">Ваш комментарий будет опубликован после проверки модератором.</em>'."\n"; // если комментарий должен пройти проверку
	    	echo "</span>";
	        comment_text()."\n"; // текст коммента
	        $reply_link_args = array( // опции ссылки "ответить"
	        	'depth' => $depth, // текущая вложенность
	        	'reply_text' => 'Ответить', // текст
				'login_text' => 'Вы должны быть залогинены' // текст если юзер должен залогинеться
	        );
	        echo get_comment_reply_link(array_merge($args, $reply_link_args)); // выводим ссылку ответить
	        echo '</div>'."\n"; // закрываем див
	    }
	    public function end_el( &$output, $comment, $depth = 0, $args = array() ) { // конец каждого коммента
			$output .= "</li><!-- #comment-## -->\n";
		}
	}
}

if (!function_exists('pagination')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
	function pagination() { // функция вывода пагинации
		global $wp_query; // текущая выборка должна быть глобальной
		$big = 999999999; // число для замены
		$links = paginate_links(array( // вывод пагинации с опциями ниже
			'base' => str_replace($big,'%#%',esc_url(get_pagenum_link($big))), // что заменяем в формате ниже
			'format' => '?paged=%#%', // формат, %#% будет заменено
			'current' => max(1, get_query_var('paged')), // текущая страница, 1, если $_GET['page'] не определено
			'type' => 'array', // нам надо получить массив
			'prev_text'    => 'Назад', // текст назад
	    	'next_text'    => 'Вперед', // текст вперед
			'total' => $wp_query->max_num_pages, // общие кол-во страниц в пагинации
			'show_all'     => false, // не показывать ссылки на все страницы, иначе end_size и mid_size будут проигнорированны
			'end_size'     => 15, //  сколько страниц показать в начале и конце списка (12 ... 4 ... 89)
			'mid_size'     => 15, // сколько страниц показать вокруг текущей страницы (... 123 5 678 ...).
			'add_args'     => false, // массив GET параметров для добавления в ссылку страницы
			'add_fragment' => '',	// строка для добавления в конец ссылки на страницу
			'before_page_number' => '', // строка перед цифрой
			'after_page_number' => '' // строка после цифры
		));
	 	if( is_array( $links ) ) { // если пагинация есть
		    echo '<ul class="pagination">';
		    foreach ( $links as $link ) {
		    	if ( strpos( $link, 'current' ) !== false ) echo "<li class='active'>$link</li>"; // если это активная страница
		        else echo "<li>$link</li>"; 
		    }
		   	echo '</ul>';
		 }
	}
}

add_action('wp_footer', 'add_scripts'); // приклеем ф-ю на добавление скриптов в футер
if (!function_exists('add_scripts')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
	function add_scripts() { // добавление скриптов
	    if(is_admin()) return false; // если мы в админке - ничего не делаем
	    wp_deregister_script('jquery'); // выключаем стандартный jquery
	}
}

add_action('wp_print_styles', 'add_styles'); // приклеем ф-ю на добавление стилей в хедер
if (!function_exists('add_styles')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
	function add_styles() { // добавление стилей
	    if(is_admin()) return false; // если мы в админке - ничего не делаем
	}
}

if (!class_exists('bootstrap_menu')) {
	class bootstrap_menu extends Walker_Nav_Menu { // внутри вывод 
		private $open_submenu_on_hover; // параметр который будет определять раскрывать субменю при наведении или оставить по клику как в стандартном бутстрапе

		function __construct($open_submenu_on_hover = true) { // в конструкторе
	        $this->open_submenu_on_hover = $open_submenu_on_hover; // запишем параметр раскрывания субменю
	    }

		function start_lvl(&$output, $depth = 0, $args = array()) { // старт вывода подменюшек
			$output .= "\n<ul class=\"dropdown-menu\">\n"; // ул с классом
		}
		function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) { // старт вывода элементов
			$item_html = ''; // то что будет добавлять
			parent::start_el($item_html, $item, $depth, $args); // вызываем стандартный метод родителя
			if ( $item->is_dropdown && $depth === 0 ) { // если элемент содержит подменю и это элемент первого уровня
			   if (!$this->open_submenu_on_hover) $item_html = str_replace('<a', '<a class="dropdown-toggle" data-toggle="dropdown" role="button" aria-haspopup="true" aria-expanded="false"', $item_html); // если подменю не будет раскрывать при наведении надо добавить стандартные атрибуты бутстрапа для раскрытия по клику
			   $item_html = str_replace('</a>', ' <b class="caret"></b></a>', $item_html); // ну это стрелочка вниз
			}
			$output .= $item_html; // приклеиваем теперь
		}
		function display_element($element, &$children_elements, $max_depth, $depth = 0, $args, &$output) { // вывод элемента
			if ( $element->current ) $element->classes[] = 'active'; // если элемент активный надо добавить бутстрап класс для подсветки
			$element->is_dropdown = !empty( $children_elements[$element->ID] ); // если у элемента подменю
			if ( $element->is_dropdown ) { // если да
			    if ( $depth === 0 ) { // если li содержит субменю 1 уровня
			        $element->classes[] = 'dropdown'; // то добавим этот класс
			        if ($this->open_submenu_on_hover) $element->classes[] = 'show-on-hover'; // если нужно показывать субменю по хуверу
			    } elseif ( $depth === 1 ) { // если li содержит субменю 2 уровня
			        $element->classes[] = 'dropdown-submenu'; // то добавим этот класс, стандартный бутстрап не поддерживает подменю больше 2 уровня по этому эту ситуацию надо будет разрешать отдельно
			    }
			}
			parent::display_element($element, $children_elements, $max_depth, $depth, $args, $output); // вызываем стандартный метод родителя
		}
	}
}

if (!function_exists('content_class_by_sidebar')) { // если ф-я уже есть в дочерней теме - нам не надо её определять
	function content_class_by_sidebar() { // функция для вывода класса в зависимости от существования виджетов в сайдбаре
		if (is_active_sidebar( 'sidebar' )) { // если есть
			echo 'col-sm-9'; // пишем класс на 80% ширины
		} else { // если нет
			echo 'col-sm-12'; // контент на всю ширину
		}
	}
}



// хук для регистрации
add_action( 'init', 'create_taxonomy' );
function create_taxonomy(){
	// список параметров: http://wp-kama.ru/function/get_taxonomy_labels
	register_taxonomy('taxonomy', array('buket'), array(
		'label'                 => 'Catalog VIP-Buket', // определяется параметром $labels->name
		'labels'                => array(
			'name'              => 'Категория',
			'singular_name'     => 'Категория',
			'search_items'      => 'Поиск категории',
			'all_items'         => 'Все категории',
			'view_item '        => 'Просмотр категории',
			'parent_item'       => 'Родительская категория',
			'parent_item_colon' => 'Родительская категория:',
			'edit_item'         => 'Редактировать категорию',
			'update_item'       => 'Обновить категорию',
			'add_new_item'      => 'Добавить категорию',
			'new_item_name'     => 'Название категории',
			'menu_name'         => 'Категория',
		),
		'description'           => '', // описание таксономии
		'public'                => true,
		'publicly_queryable'    => null, // равен аргументу public
		'show_in_nav_menus'     => true, // равен аргументу public
		'show_ui'               => true, // равен аргументу public
		'show_in_menu'          => true, // равен аргументу show_ui
		'show_tagcloud'         => true, // равен аргументу show_ui
		'show_in_rest'          => null, // добавить в REST API
		'rest_base'             => null, // $taxonomy 
		'hierarchical'          => true,
		//'update_count_callback' => '_update_post_term_count',
		'rewrite'               => true,
		//'query_var'             => $taxonomy, // название параметра запроса
		'capabilities'          => array(),
		'meta_box_cb'           => null, // html метабокса. callback: `post_categories_meta_box` или `post_tags_meta_box`. false — метабокс отключен.
		'show_admin_column'     => true, // Позволить или нет авто-создание колонки таксономии в таблице ассоциированного типа записи. (с версии 3.5)
		'_builtin'              => false,
		'show_in_quick_edit'    => null, // по умолчанию значение show_ui
	) );
	
	
	
	
}



add_action( 'init', 'true_register_post_type_init' ); // Использовать функцию только внутри хука init
 
function true_register_post_type_init() {
	$labels = array(
		'name' => 'Каталог цветов',
		'singular_name' => 'Букет, цветы', // админ панель Добавить->Функцию
		'add_new' => 'Добавить букет, цветы',
		'add_new_item' => 'Добавить', // заголовок тега <title>
		'edit_item' => 'Редактировать букет, цветы',
		'new_item' => 'Новая букет, цветы',
		'all_items' => 'Все',
		'view_item' => 'Просмотр букета, цветов на сайте',
		'search_items' => 'Искать букет, цветы',
		'not_found' =>  'Букета или цветов не найдено.',
		'not_found_in_trash' => 'В корзине нет букета или цветов.',
		'menu_name' => 'Букет, цветы' // ссылка в меню в админке
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true, // показывать интерфейс в админке
		'has_archive' => true, 
		'menu_icon' => get_stylesheet_directory_uri() .'/images/icon.png', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports' => array( 'title', 'editor', 'comments', 'author', 'thumbnail')
	);
	register_post_type('buket', $args);
    
    
 
    
    $labels = array(
		'name' => 'Слайдер',
		'singular_name' => 'Слайдер', // админ панель Добавить->Функцию
		'add_new' => 'Добавить новый слайд',
		'add_new_item' => 'Добавить', // заголовок тега <title>
		'edit_item' => 'Редактировать слайд',
		'new_item' => 'Новый слайд',
		'all_items' => 'Все слайды',
		'view_item' => 'Просмотр слайда на сайте',
		'search_items' => 'Искать слайд',
		'not_found' =>  'Слайдов не найдено.',
		'not_found_in_trash' => 'В корзине нет слайдов.',
		'menu_name' => 'Слайдер' // ссылка в меню в админке
	);
	$args = array(
		'labels' => $labels,
		'public' => true,
		'show_ui' => true, // показывать интерфейс в админке
		'has_archive' => true, 
		'menu_icon' => get_stylesheet_directory_uri() .'/images/icon_slide.png', // иконка в меню
		'menu_position' => 20, // порядок в меню
		'supports' => array( 'title', 'editor', 'comments', 'author', 'thumbnail')
	);
	register_post_type('slider', $args);
}

function search_start_value($price, $sale) {
  return round($price * 100 / (100 - $sale));
}


//  сортировку записей по в WordPress
function change_order_post_list( $query ){
	
	//print_r($query->query['taxonomy']);
	
    if( !is_admin() && $query->is_main_query() && isset($query->tax_query->queries[0]["taxonomy"]) && $query->tax_query->queries[0]["taxonomy"] == 'taxonomy' ) {
		
        $query->set('meta_key'  , 'article');
        $query->set('orderby'   , 'meta_value');
        $query->set('order'     , 'ASC');
    }
}
add_action('pre_get_posts', 'change_order_post_list', 1 );


function view_category($id = 0, $count = 9, $name = "") {
    
    
    $posts = get_posts( 
        array(
            'numberposts' => $count,
            'post_type'   => 'buket',
			'meta_type'   => 'CHAR',
			'meta_key'	  => 'article',
			'orderby'     => 'meta_value',
			'order'       => 'ASC',
            'suppress_filters' => true, // подавление работы фильтров изменения SQL запроса
            'tax_query' => array(
                array(
                  'taxonomy' => 'taxonomy',
                  'field' => 'id',
                  'terms' => $id
                )
            )
        ) 
    );

    $str = '<div class="container"><section class="good-category">';
	
	if(!empty($name)) {
		$str .= '<div class="header">'.$name.'</div>';
	}
	
	$links = [];
	
	$relationships = get_field('relationships', "taxonomy_".$id);
	
	if(count($relationships)) {
		foreach($relationships as $rid) {
			$term = get_term( $rid, "taxonomy" );
			$links[] = [$term->name, "/".$term->slug."/"];
		}
	}
	if(count($links)) {
		$str .= '<div class="category-filter">';
						foreach($links as $link){
							$str .= '<a href="'.$link[1].'" class="item-filter">'.$link[0].'</a>';
						}
					$str .='</div>';
		}
	$str .= '<div class="content-grid">';
    
    
    foreach( $posts as $post ){
        $id = $post->ID;
		$link = get_post_permalink( $id );
		
        $price = get_field("price", $id);
        $sale = get_field("sale", $id);
        $article = get_field("article", $id);
        $hit = get_field("hit", $id);
        $hit = $hit ? $hit : false;
		 
		$video = get_field("video", $id);
        
		$old_price = $sale?search_start_value($price, $sale):false;
     
		$image_id = get_post_thumbnail_id($post);
		$thumbnail = wp_get_attachment_image_src( $image_id,  array(300, 300) );
		$image_title = get_post_meta( $image_id, '_wp_attachment_image_alt', true );
		 
		$content_post = get_post($id);
		$content = $content_post->post_content;
		$content = apply_filters('the_content', $content);
		$content = str_replace(']]>', ']]&gt;', $content);
	
	
				
		$str .= '
			<div class="good-wrap">
				<div class="good">
						<div class="options">';
						$str .= $old_price ? '<div class="sale">-'.$sale.'%</div>':'';
						$str .= $hit?'<div class="hit">Хит</div>':'';
					 $str .= '</div>
					<div class="video-desktop desktop-only js-target-view-video" data-video="'.$video.'">
						<img data-src="'.get_template_directory_uri().'/images/svg/good-video.svg" data-srcset="'.get_template_directory_uri().'/images/svg/good-video.svg 0w">
					</div>
					<a href="'.$link.'" class="image">
						<img alt="'.$image_title.'" data-src="'.$thumbnail[0].'" data-srcset="'.$thumbnail[0].' 0w">
					</a>

					<div class="desktop-only">
						<div class="name-article">
							<a href="'.$link.'" class="name">
								'.get_the_title($id).'
							</a>
							<div class="article">
								'.$article.'
							</div>
						</div>

						<div class="about">
							<span>описание ></span>
							<div class="expend">
								'.$content.'
							</div>
						</div>
						<div class="price-to-cart">
							<div class="prices">
								<span class="price'.($old_price?' red':'').'">'.$price.' ₽</span>
								';
								$str .= $old_price?'<span class="old-price">'.$old_price.' ₽</span>':'';
							$str .= '
							</div>
							<div class="to-cart" data-good-id="'.$id.'">
								<button class="green-button">
									<img 
									data-src="'.get_template_directory_uri().'/images/svg/good-cart.svg" 
									data-srcset="'.get_template_directory_uri().'/images/svg/good-cart.svg 0w">
								</button>
							</div>
						</div>

					</div>
					<div class="mobile-only">
						<div class="article-line">
							<div class="article-line__article">
								'.$article.'
							</div>
							<div class="article-line__old-price">';
								$str .= $old_price?'<span class="old-price">'.$old_price.' ₽</span>':'';
							$str .= '</div>
						</div>

						<div class="name-line">
							<a href="'.$link.'" class="name-line__name">
								'.get_the_title($id).'
							</a>
							<div class="name-line__price'.($old_price?' red':'').'">
								'.$price.' ₽
							</div>
						</div>

						<div class="mobile-expend-block">
							<div class="not-expend-text hide">
								<div class="content">
								'.$content.'
								</div>
							</div>
							<!-- <div class="expend-fade"></div>

							<span class="expend-button">ещё</span> -->
						</div>

						<div class="gry-line"></div>
					</div>
				</div>
				</div>';
    }
	
	
	
    $str .=  '</div></section></div>';
    return $str;
}



// МЕНЮ
register_nav_menus(
	array(
		'head_menu' => 'Шапка сайта'
	)
);

// SVG
function cc_mime_types($mimes) {
  $mimes['svg'] = 'image/svg+xml';
  return $mimes;
}
add_filter('upload_mimes', 'cc_mime_types');



class True_Walker_Nav_Menu extends Walker_Nav_Menu {
	/*
	 * Позволяет перезаписать <ul class="sub-menu">
	 */
	function start_lvl(&$output, $depth = 0, $args = array()) {
		/*
		 * $depth – уровень вложенности, например 2,3 и т д
		 */ 
		
		$output .= '<div class="child"><ul>';
		
	}
	
	
	function end_lvl(&$output, $depth = 0, $args = array()) {
		/*
		 * $depth – уровень вложенности, например 2,3 и т д
		 */ 
		
			$output .= '</ul></div>';
		
	}
	/**
	 * @see Walker::start_el()
	 * @since 3.0.0
	 *
	 * @param string $output
	 * @param object $item Объект элемента меню, подробнее ниже.
	 * @param int $depth Уровень вложенности элемента меню.
	 * @param object $args Параметры функции wp_nav_menu
	 */
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;           
		/*
		 * Некоторые из параметров объекта $item
		 * ID - ID самого элемента меню, а не объекта на который он ссылается
		 * menu_item_parent - ID родительского элемента меню
		 * classes - массив классов элемента меню
		 * post_date - дата добавления
		 * post_modified - дата последнего изменения
		 * post_author - ID пользователя, добавившего этот элемент меню
		 * title - заголовок элемента меню
		 * url - ссылка
		 * attr_title - HTML-атрибут title ссылки
		 * xfn - атрибут rel
		 * target - атрибут target
		 * current - равен 1, если является текущим элементом
		 * current_item_ancestor - равен 1, если текущим (открытым на сайте) является вложенный элемент данного
		 * current_item_parent - равен 1, если текущим (открытым на сайте) является родительский элемент данного
		 * menu_order - порядок в меню
		 * object_id - ID объекта меню
		 * type - тип объекта меню (таксономия, пост, произвольно)
		 * object - какая это таксономия / какой тип поста (page /category / post_tag и т д)
		 * type_label - название данного типа с локализацией (Рубрика, Страница)
		 * post_parent - ID родительского поста / категории
		 * post_title - заголовок, который был у поста, когда он был добавлен в меню
		 * post_name - ярлык, который был у поста при его добавлении в меню
		 */
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		/*
		 * Генерируем строку с CSS-классами элемента меню
		 */
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
 
		// функция join превращает массив в строку
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . ' depth-'.$depth.'"';
 
		/*
		 * Генерируем ID элемента
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
 
		/*
		 * Генерируем элемент меню
		 */
		$output .= $indent . '<li' . $id . $value . $class_names .'>';
 
		// атрибуты элемента, title="", rel="", target="" и href=""
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		$attributes .= ! empty( $item->url )        ? ' href="'   . esc_attr( $item->url        ) .'"' : '';
 
		// ссылка и околоссылочный текст
		if($depth == 0) {
			
			$value = get_field( "icon_menu", $item );
			$img = '<img data-src="'.$value.'" data-srcset="'.$value.' 0w">';
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			//$item_output .= $img;
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
			
		} else {
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

		}
 
 		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}



class mobile_Walker_Nav_Menu extends Walker_Nav_Menu {
	protected $id = 1;
	function start_lvl(&$output, $depth = 0, $args = array()) {
		
		$back = "";
		if($depth === 0) {
			//$back = '<img  data-src="'.get_template_directory_uri().'/images/svg/menu-sub-back-arrow.svg" data-srcset="'.get_template_directory_uri().'/images/svg/menu-sub-back-arrow.svg 0w" class="img-opacity js-event-click-hide-sub-menu">';
		}
		
		//$output .= '<div class="child depth-'.$depth.'" id="menu_'.$this->id++.'">'.$back.'<ul id="menu_'.$this->id++.'">';
		$output .= '<ul id="menu_'.$this->id++.'">';
	}
	
	
	function end_lvl(&$output, $depth = 0, $args = array()) {
		//$output .= '</ul></div>';
		$output .= '</ul>';
	}
	
	function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0) {
		global $wp_query;           
		
		$indent = ( $depth ) ? str_repeat( "\t", $depth ) : '';
 
		/*
		 * Генерируем строку с CSS-классами элемента меню
		 */
		$class_names = $value = '';
		$classes = empty( $item->classes ) ? array() : (array) $item->classes;
		$classes[] = 'menu-item-' . $item->ID;
 
		// функция join превращает массив в строку
		$class_names = join( ' ', apply_filters( 'nav_menu_css_class', array_filter( $classes ), $item, $args ) );
		$class_names = ' class="' . esc_attr( $class_names ) . ' depth-'.$depth.'"';
 
		/*
		 * Генерируем ID элемента
		 */
		$id = apply_filters( 'nav_menu_item_id', 'menu-item-'. $item->ID, $item, $args );
		$id = strlen( $id ) ? ' id="' . esc_attr( $id ) . '"' : '';
 
		/*
		 * Генерируем элемент меню
		 */
		$output .= $indent . '<li "'.$class_names.'">';
		
		// атрибуты элемента, title="", rel="", target="" и href=""
		$attributes  = ! empty( $item->attr_title ) ? ' title="'  . esc_attr( $item->attr_title ) .'"' : '';
		$attributes .= ! empty( $item->target )     ? ' target="' . esc_attr( $item->target     ) .'"' : '';
		$attributes .= ! empty( $item->xfn )        ? ' rel="'    . esc_attr( $item->xfn        ) .'"' : '';
		
		if($depth != 0) {
			$attributes .= ! empty( $item->url ) ? ' href="'   . esc_attr( $item->url ) .'"' : '';
		} else {
			if(strpos($class_names, "menu-item-has-childre")) {
				$attributes .= ! empty( $item->url ) ? ' href="'   . esc_attr( '#menu_'.$this->id) .'"' : '';
			} else {
				$attributes .= ! empty( $item->url ) ? ' href="'   . esc_attr( $item->url ) .'"' : '';
			}
		}
 
		// ссылка и околоссылочный текст
		if($depth === 0) {
			
			$value = get_field( "icon_menu", $item );
			$img = '<img data-src="'.$value.'" data-srcset="'.$value.' 0w">';
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			//$item_output .= $img;
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;
			
		} else {
			
			$item_output = $args->before;
			$item_output .= '<a'. $attributes .'>';
			$item_output .= $args->link_before . apply_filters( 'the_title', $item->title, $item->ID ) . $args->link_after;
			$item_output .= '</a>';
			$item_output .= $args->after;

		}
 
 		$output .= apply_filters( 'walker_nav_menu_start_el', $item_output, $item, $depth, $args );
	}
}


// Переадресация

add_filter('request', 'rudr_change_term_request', 1, 1 );
 
function rudr_change_term_request($query){
  $tax_name = 'taxonomy'; // specify you taxonomy name here, it can be also 'category' or 'post_tag'\
  //print_r( $query );
  // Request for child terms differs, we should make an additional check
  $name = "";
  if( isset($query['attachment']) ) {
    $include_children = true;
	$name = $query['attachment'];
  } elseif(isset($query['name'])) {
    $include_children = false;
	$name = $query['name'];
  };
 
 
  $term = get_term_by('slug', $name, $tax_name); // get the current term to make sure it exists
 
  if (isset($name) && $term && !is_wp_error($term)): // check it here
 
  if( $include_children ) {
    unset($query['attachment']);
    $parent = $term->parent;
    while( $parent ) {
      $parent_term = get_term( $parent, $tax_name);
      $name = $parent_term->slug . '/' . $name;
      $parent = $parent_term->parent;
    }
  } else {
    unset($query['name']);
  }
 
  switch( $tax_name ):
  case 'category':{
        $query['category_name'] = $name; // for categories
        break;
      }
      case 'post_tag':{
        $query['tag'] = $name; // for post tags
        break;
      }
      default:{
        $query[$tax_name] = $name; // for another taxonomies
        break;
      }
      endswitch;
 
      endif;
 
      return $query;
    }
 
add_filter( 'term_link', 'rudr_term_permalink', 10, 3 );
 
function rudr_term_permalink( $url, $term, $taxonomy ){
 
  $taxonomy_name = 'taxonomy'; // your taxonomy name here
  $taxonomy_slug = 'taxonomy'; // the taxonomy slug can be different with the taxonomy name (like 'post_tag' and 'tag' )
 
  // exit the function if taxonomy slug is not in URL
  if ( strpos($url, $taxonomy_slug) === FALSE || $taxonomy != $taxonomy_name ) return $url;
 
  $url = str_replace('/' . $taxonomy_slug, '', $url);
 
  return $url;
}

// Замена заголовков 
function get_custom_meta($type) {
	$cat_obj = get_queried_object();
	
	// Страницы таксономий(категорий) букетов
	if($cat_obj->taxonomy == "taxonomy") {
		$cat = $cat_obj->taxonomy.'_'.$cat_obj->term_id;
		switch($type) {
			default:
			case 'title':
				$result = get_field('title', $cat);
			break;
			case 'description':
				
				$result = get_field('description', $cat);
			break;
			case 'keywords':
				
				$result = get_field('alts', $cat);
			break;
		}
	} 

	// Для страниц
	if(is_page()) {
		global $post;
		$cat = get_the_ID();
		switch($type) {
			default:
			case 'title':
				$result = get_field('title', $cat);
			break;
			case 'description':
				$result = get_field('description', $cat);
			break;
			case 'keywords':
				$result = get_field('alts', $cat);
			break;
		}
	} 
	
	if($result) {
		return $result;
	}
	
	// Если ничего не помогло
	switch($type) {
		default:
		case 'title':
			$result = get_the_title();
		break;
		case 'description':
			$result = get_the_title();
		break;
		case 'keywords':
			$result = get_the_title();
		break;
	}
	return $result;
}




// Метод оформления закза
add_action( 'wp_ajax_order', 'test_function' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_order', 'test_function' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей
 
function sendTelegrammMessage($messaggio, $post = []) {
	
	$post['chat_id'] = "-358282428"; // -XXXXXX
	$post['text'] = $messaggio;
	
	
    $url = "https://api.telegram.org/bot845991033:AAF2NTy98JzT4HeHE0V-1tZXPqimpitM1Hc/sendMessage";

    $result = "ОШИБКА";
	$proxy = "138.197.157.60:1080";
	//$proxy = "138.68.59.157:1210";
	// http://free-proxy.cz/ru/proxylist/country/all/socks5/ping/all
	
	$ch = curl_init();
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 3);
	curl_setopt($ch, CURLOPT_TIMEOUT, 15);
	curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
	curl_setopt($ch, CURLOPT_PROXY, "socks5://$proxy");
	curl_setopt($ch, CURLOPT_HEADER, false);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_POST, 1);
	curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); 
	$result = curl_exec($ch);
	if(empty($result)) {
		$result = curl_error($ch);
		
	}
	return $result;
	
}

function test_function(){
	$headers = array('Content-Type: text/html; charset=UTF-8');
	/* 
		{name: "is_postcard", value: "on"}
		{name: "postcard_text", value: "123"}
		{name: "decoration", value: "on"}
		{name: "user_name", value: "Georg"}
		{name: "user_phone", value: "123123123"}
		{name: "user_email", value: "ags@asg.ru"}
		{name: "user_date", value: "12.12.12"}
		{name: "user_addr", value: "Nfrnjqnj flhtcc"}
	*/
	
	$arPost = [];
	$mail_body = "";
	$is_postcard = "";
	$decoration = "";
	
	foreach($_POST['data'] as $object) {
		$arPost[$object['name']] = $object['value'];
	}
	
	$is_postcard = "Открытка с пожеланиями ";
	if($arPost['is_postcard'] === 'on') {
		$is_postcard .= "нужна. <br>Текст:<br>";
		$is_postcard .= $arPost['postcard_text'] . "<br><br>";
	} else {
		$is_postcard .= "не нужна.<br><br>";
	}
	
	$decoration = "Украшение для букета Топпер: ";
	if($arPost['decoration'] === 'on') {
		$decoration .= "ДА<br><br>";
	} else {
		$decoration .= "НЕТ<br><br>";
	}
	
	$good = "Букет: <br>";
	
	$good .= "<img src='".$_POST['item']['img'] . "' style='width:200px;'><br><br>";
	$good .= $_POST['item']['name'] . "<br>";
	$good .= $_POST['item']['article'] . "<br>";
	$good .= $_POST['item']['price'] . "<br><br>";
	

	$mail_body .= "Имя: " .  $arPost['user_name'] . "<br>";
	$mail_body .= "Телефон: " .  $arPost['user_phone'] . "<br>";
	$mail_body .= "Почта: " .  $arPost['user_email'] . "<br>";
	$mail_body .= "Адрес: " .  $arPost['user_addr'] . "<br>";
	$mail_body .= "Доставка: " .  $arPost['user_date'] . "<br><br>";
	
	sendTelegrammMessage($mail_body . $good);
	
	$file = file_get_contents(dirname(__FILE__) . "/mails/mail.tpl");
	$mail = str_replace("#SERVER_NAME#" , "https://".$_SERVER['SERVER_NAME'], $file);
	$b = str_replace("#WORK_AREA#", ( $mail_body . $good . $is_postcard . $decoration), $mail);
	
	wp_mail('info@vipbouquet.ru', 'Заказ с сайта ' . $_SERVER['SERVER_NAME'], $b, $headers);
	
	
	
	$greeting = "Уважемый(ая), " . $arPost['user_name'] . " ! <br><br> Ваш заказ принят. В ближайшее время с Вами свяжется менеджер. Если у Вас возникли какие-либо вопросы, Вы можете связаться с нами по телефону +7 (495) 005-18-47<br><br>";
	
	$good = "";
	$good .= "<img src='".$_POST['item']['img'] . "' style='width:200px;'><br><br>";
	$good .= $_POST['item']['name'] . " ";
	$good .= $_POST['item']['article'] . " ";
	$good .= "<br><b>" . $_POST['item']['price'] . "</b><br><br>";
	$price = intval($_POST['item']['price']);
	
	$mail_body = "Адрес доставки: " .  $arPost['user_addr'] . "<br>";
	$mail_body .= "Желаемая дата доставки: " .  $arPost['user_date'] . "<br>";
	
	$decoration = "";
	if($arPost['decoration'] === 'on') {
		$decoration .= "Украшение для букета Топпер <b>(+99 ₽)</b><br><br>";
		$price += 99;
	}
	
	if($arPost['is_postcard'] === 'on') {
		$decoration .= "Открытка с пожеланиями <b>(+149 ₽)</b>:<br><i>";
		$decoration .= $arPost['postcard_text'] ."</i><br><br>";
		$price += 149;
	}
	
	if($price > intval($_POST['item']['price'])) {
		$decoration .= "<b>ИТОГО: " . $price . " ₽</b><br><br>";
	}
	
	$b = str_replace("#WORK_AREA#", ($greeting . "" . $good . "" . $decoration . "" . $mail_body), $mail);
	wp_mail($arPost['user_email'], 'Ваш заказ на сайте ' . $_SERVER['SERVER_NAME'], $b, $headers);
	
	echo true;
	
	die; // даём понять, что обработчик закончил выполнение
}





// Метод оформления закза
add_action( 'wp_ajax_search', 'search_ajax_result' ); // wp_ajax_{ЗНАЧЕНИЕ ПАРАМЕТРА ACTION!!}
add_action( 'wp_ajax_nopriv_search', 'search_ajax_result' );  // wp_ajax_nopriv_{ЗНАЧЕНИЕ ACTION!!}
// первый хук для авторизованных, второй для не авторизованных пользователей
function search_ajax_result() {
	
	$args = array(
		's' => $_POST['term'],
		'posts_per_page' => 10,
		'post_type' => 'buket'
	);
	$the_query = new WP_Query($args);
	
    if( $the_query->have_posts() ) {
        while( $the_query->have_posts() ){ 
			$the_query->the_post(); 
			$id = get_the_ID();
			$category = $cur_terms = get_the_terms($id, 'taxonomy' );
		
			?>
				<div class="live-search__item">
					<?php
						if(has_post_thumbnail()) {
							the_post_thumbnail(array(40, 40));
						}
					?>
					<div class="name"><?php the_title(); ?></div>
					<a class="green-button js-target-go-to" href="/<?=$category[0]->slug?>#item-<?=$id?>">
						<img src="<?=get_template_directory_uri()?>/images/svg/good-cart.svg">
					</a>
				</div>
			<?
			wp_reset_postdata();
		}
    }
    die();
}

function search_filter( $query ) {
 //print_r($query);
  // Изолируем нужный запрос
  if ( !is_admin() && $query->is_main_query() && $query->is_search ) {
    
      // Передаем параметры подзапроса в основной запрос
      $query->set( 'post_type', [ $_GET['where'] ] );
  }
}
add_action( 'pre_get_posts', 'search_filter' );



function wpb_disable_feed() {
wp_die( __('No feed available,please visit our <a href="'. get_bloginfo('url') .'">homepage</a>!') );
}
add_action('do_feed', 'wpb_disable_feed', 1);
add_action('do_feed_rdf', 'wpb_disable_feed', 1);
add_action('do_feed_rss', 'wpb_disable_feed', 1);
add_action('do_feed_rss2', 'wpb_disable_feed', 1);
add_action('do_feed_atom', 'wpb_disable_feed', 1);
add_action('do_feed_rss2_comments', 'wpb_disable_feed', 1);
add_action('do_feed_atom_comments', 'wpb_disable_feed', 1);





// убираем dns-prefetch
remove_action( 'wp_head', 'wp_resource_hints', 2 );

// убираем стили и скрипт emoji
remove_action( 'wp_head', 'print_emoji_detection_script', 7 );
remove_action( 'wp_print_styles', 'print_emoji_styles' );
 
// + как бонус, удаление meta generator и короткой ссылки на материалы
remove_action( 'wp_head', 'wp_generator' );
remove_action( 'wp_head', 'wp_shortlink_wp_head' );


















// фильтр - добавим выпадающий список
add_action( 'restrict_manage_posts', 'add_event_table_filters');
function add_event_table_filters( $post_type ){
	
	$terms = get_terms( array(
		'taxonomy'      => array( 'taxonomy' ), // название таксономии с WP 4.5
		'orderby'       => 'id', 
		'order'         => 'ASC',
		'hide_empty'    => true, 
		'object_ids'    => null,
		'include'       => array(),
		'exclude'       => array(), 
		'exclude_tree'  => array(), 
		'number'        => '', 
		'fields'        => 'all', 
		'count'         => false,
		'slug'          => '', 
		'parent'         => '',
		'hierarchical'  => true, 
		'child_of'      => 0, 
		'get'           => '', // ставим all чтобы получить все термины
		'name__like'    => '',
		'pad_counts'    => false, 
		'offset'        => '', 
		'search'        => '', 
		'cache_domain'  => 'core',
		'name'          => '',    // str/arr поле name для получения термина по нему. C 4.2.
		'childless'     => false, // true не получит (пропустит) термины у которых есть дочерние термины. C 4.2.
		'update_term_meta_cache' => true, // подгружать метаданные в кэш
		'meta_query'    => '',
	) );


	echo '<select name="sel_season">';
	foreach( $terms as $term ){
		echo '<option value="'.$term->term_id.'">'.$term->name.'</option>';
	}
	echo '</select>';
	// для динамического построения селекта, можно использовать wp_dropdown_categories()
}

// Фильтрация: обработка запроса
add_action( 'pre_get_posts', 'add_event_table_filters_handler' );
function add_event_table_filters_handler( $query ) {
	if( ! is_admin() ) return; // выходим если не админка

	// убедимся что мы на нужной странице админки
	$cs = get_current_screen();
	if( empty($cs->post_type) || $cs->post_type != 'buket' || $cs->id != 'edit-buket' ) return;

	// сезон
	if( @ $_GET['sel_season'] != -1 ){
		$selected_id = $_GET['sel_season'] ?: -1;
		$query->set( 'tax_query', array([ 'taxonomy'=>'taxonomy', 'terms'=>$selected_id ]) );
	}

}

?>