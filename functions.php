<?php
/* WP Bootstrap 5 Sass Custom functions, support, custom post types and more.
 *  Author: ahmadzein
 *  URL: https://github.com/ahmadzein
 *  Forked from on:
 *  WP Bootstrap 5 Sass Custom functions, support, custom post types and more.
 *  Author: tone4hook
 *  URL: https://github.com/tone4hook
 *
 */

/*------------------------------------*\
	External Modules/Files
\*------------------------------------*/

// Load any external files you have here

/*------------------------------------*\
	Theme Support
\*------------------------------------*/

if (!isset($content_width))
{
    $content_width = 900;
}

if (function_exists('add_theme_support'))
{
    // Add Menu Support
    add_theme_support('menus');

    // Add Thumbnail Theme Support
    add_theme_support('post-thumbnails');
    add_image_size('large', 700, '', true); // Large Thumbnail
    add_image_size('medium', 250, '', true); // Medium Thumbnail
    add_image_size('small', 120, '', true); // Small Thumbnail
    add_image_size('custom-size', 700, 200, true); // Custom Thumbnail Size call using the_post_thumbnail('custom-size');

    // Enables post and comment RSS feed links to head
    add_theme_support('automatic-feed-links');

    // Localisation Support
    load_theme_textdomain('wpbootstrapsass', get_template_directory() . '/languages');
}

/*------------------------------------*\
	Functions
\*------------------------------------*/

// WP Bootstrap Sass navigation
function wpbootstrapsass_nav()
{
	wp_nav_menu(
	array(
		'theme_location'  => 'header-menu',
        'menu'            => '',
        'container'       => 'div',
        'container_class' => 'collapse navbar-collapse',
        'container_id'    => 'bs-example-navbar-collapse-1',
        'menu_class'      => 'nav navbar-nav',
        'menu_id'         => '',
        'echo'            => true,
        'before'          => '',
        'after'           => '',
        'link_before'     => '',
        'link_after'      => '',
        'items_wrap'      => '<ul class="nav navbar-nav navbar-right">%3$s</ul>',
        )
	);
}

// add bootstrap css class to menu <li> element
function atg_menu_classes($classes, $item, $args) {
    if ($args->theme_location == 'header-menu') {
      $classes[] = 'nav-item';
    }
    return $classes;
}
add_filter('nav_menu_css_class', 'atg_menu_classes', 1, 3);

// add bootstrap css class to menu <a> element
function add_specific_menu_location_atts( $atts, $item, $args ) {
    // check if the item is in the header menu
    if( $args->theme_location == 'header-menu' ) {
      // add the desired attributes:
      $atts['class'] = 'nav-link';
    }
    return $atts;
}
add_filter( 'nav_menu_link_attributes', 'add_specific_menu_location_atts', 10, 3 );

// Load WP Bootstrap Sass scripts (header.php)
function wpbootstrapsass_header_scripts()
{
    if ($GLOBALS['pagenow'] != 'wp-login.php' && !is_admin()) {

        // Custom scripts
        wp_register_script('wpbootstrapsassscripts', get_template_directory_uri() . '/dist/main.bundle.js', array('jquery'), '1.0.0');

        // Enqueue it!
        wp_enqueue_script( array('wpbootstrapsassscripts') );

    }
}

// Add attributes to the script tag
// async or defer
// *** for CDN integrity and crossorigin attributes ***
function add_script_tag_attributes($tag, $handle)
{
    switch ($handle) {

    	// adding async to main js bundle
    	// for defer, replace async="async" with defer="defer"
    	case ('wpbootstrapsassscripts'):
    		return str_replace( ' src', ' async="async" src', $tag );
    	break;

    	// example adding CDN integrity and crossorigin attributes
    	// Note: popper.js is loaded into the main.bundle.js from npm
    	// This is just an example
        case ('popper-js'):
            return str_replace( ' min.js', 'min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"', $tag );
        break;

    	// example adding CDN integrity and crossorigin attributes
    	// Note: bootstrap.js is loaded into the main.bundle.js from npm
    	// This is just an example
        case ('bootstrap-js'):
            return str_replace( ' min.js', 'min.js" integrity="sha384-a5N7Y/aK3qNeh15eJKGWxsqtnX/wWdSZSKp+81YjTmS15nvnvxKHuzaWwXHDli+4" crossorigin="anonymous"', $tag );
        break;

        default:
            return $tag;

    } // /switch
}

// Load WP Bootstrap Sass conditional scripts
function wpbootstrapsass_conditional_scripts()
{
    if (is_page('pagenamehere')) {
        wp_register_script('scriptname', get_template_directory_uri() . '/js/scriptname.js', array('jquery'), '1.0.0'); // Conditional script(s)
        wp_enqueue_script('scriptname'); // Enqueue it!
    }
}

// Load WP Bootstrap Sass styles
function wpbootstrapsass_styles()
{
    // Normalize is loaded in Bootstrap and both are imported into the style.css via Sass
    wp_register_style('wpbootstrapsass', get_template_directory_uri() . '/dist/style.min.css', array(), '1.0.0', 'all');
    wp_enqueue_style('wpbootstrapsass'); // Enqueue it!
}

// Register WP Bootstrap Sass Navigation
function register_wpbootstrapsass_menu()
{
    register_nav_menus(array( // Using array to specify more menus if needed
        'header-menu' => __('Header Menu', 'wpbootstrapsass'), // Main Navigation
        'sidebar-menu' => __('Sidebar Menu', 'wpbootstrapsass'), // Sidebar Navigation
        'extra-menu' => __('Extra Menu', 'wpbootstrapsass') // Extra Navigation if needed (duplicate as many as you need!)
    ));
}

// Remove the <div> surrounding the dynamic navigation to cleanup markup
function my_wp_nav_menu_args($args = '')
{
    $args['container'] = false;
    return $args;
}

// Remove Injected classes, ID's and Page ID's from Navigation <li> items
function my_css_attributes_filter($var)
{
    return is_array($var) ? array() : '';
}

// Remove invalid rel attribute values in the categorylist
function remove_category_rel_from_category_list($thelist)
{
    return str_replace('rel="category tag"', 'rel="tag"', $thelist);
}

// Add page slug to body class, love this - Credit: Starkers Wordpress Theme
function add_slug_to_body_class($classes)
{
    global $post;
    if (is_home()) {
        $key = array_search('blog', $classes);
        if ($key > -1) {
            unset($classes[$key]);
        }
    } elseif (is_page()) {
        $classes[] = sanitize_html_class($post->post_name);
    } elseif (is_singular()) {
        $classes[] = sanitize_html_class($post->post_name);
    }

    return $classes;
}

// If Dynamic Sidebar Exists
if (function_exists('register_sidebar'))
{
    // Define Sidebar Widget Area 1
    register_sidebar(array(
        'name' => __('Widget Area 1', 'wpbootstrapsass'),
        'description' => __('Description for this widget-area...', 'wpbootstrapsass'),
        'id' => 'widget-area-1',
        'before_widget' => '<div id="%1$s" class="%2$s card mb-2"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3 class="card-title">',
        'after_title' => '</h3>'
    ));

    // Define Sidebar Widget Area 2
    register_sidebar(array(
        'name' => __('Widget Area 2', 'wpbootstrapsass'),
        'description' => __('Description for this widget-area...', 'wpbootstrapsass'),
        'id' => 'widget-area-2',
        'before_widget' => '<div id="%1$s" class="%2$s card mb-2"><div class="card-body">',
        'after_widget' => '</div></div>',
        'before_title' => '<h3 class="card-title">',
        'after_title' => '</h3>'
    ));
}

// Remove wp_head() injected Recent Comment styles
function my_remove_recent_comments_style()
{
    global $wp_widget_factory;
    remove_action('wp_head', array(
        $wp_widget_factory->widgets['WP_Widget_Recent_Comments'],
        'recent_comments_style'
    ));
}

// Pagination for paged posts, Page 1, Page 2, Page 3, with Next and Previous Links, No plugin
function wpbootstrapsass_pagination()
{
    global $wp_query;
    $big = 999999999;
    $links = paginate_links(array(
        'base' => str_replace($big, '%#%', get_pagenum_link($big)),
        'format' => '?paged=%#%',
        'current' => max(1, get_query_var('paged')),
        'total' => $wp_query->max_num_pages,
        'prev_text' => '<span class="border p-1">&lt;</span>',
        'next_text' => '<span class="border p-1">&gt;</span>',
        'before_page_number' => '<span class="border p-1">',
        'after_page_number' => '</span>',
    ));

    if ( $links ) :

        echo $links;

    endif;

}

// Custom Excerpts
function wpbootstrapsass_index($length) // Create 20 Word Callback for Index page Excerpts, call using wpbootstrapsass_excerpt('wpbootstrapsass_index');
{
    return 20;
}

// Create 40 Word Callback for Custom Post Excerpts, call using wpbootstrapsass_excerpt('wpbootstrapsass_custom_post');
function wpbootstrapsass_custom_post($length)
{
    return 40;
}

// Create the Custom Excerpts callback
function wpbootstrapsass_excerpt($length_callback = '', $more_callback = '')
{
    global $post;
    if (function_exists($length_callback)) {
        add_filter('excerpt_length', $length_callback);
    }
    if (function_exists($more_callback)) {
        add_filter('excerpt_more', $more_callback);
    }
    $output = get_the_excerpt();
    $output = apply_filters('wptexturize', $output);
    $output = apply_filters('convert_chars', $output);
    $output = '<p>' . $output . '</p>';
    echo $output;
}

// Custom View Article link to Post
function wpbootstrapsass_view_article($more)
{
    global $post;
    return '... <p><a class="view-article btn btn-secondary" href="' . get_permalink($post->ID) . '" role="button">' . __('Read more', 'wpbootstrapsass') . ' </a></p>';
}

// Remove Admin bar
function remove_admin_bar()
{
    return false;
}

// Remove 'text/css' from our enqueued stylesheet
function wpbootstrapsass_style_remove($tag)
{
    return preg_replace('~\s+type=["\'][^"\']++["\']~', '', $tag);
}

// Remove thumbnail width and height dimensions that prevent fluid images in the_thumbnail
function remove_thumbnail_dimensions( $html )
{
    $html = preg_replace('/(width|height)=\"\d*\"\s/', "", $html);
    return $html;
}

// Custom Gravatar in Settings > Discussion
function wpbootstrapsassgravatar ($avatar_defaults)
{
    $myavatar = get_template_directory_uri() . '/img/gravatar.jpg';
    $avatar_defaults[$myavatar] = "Custom Gravatar";
    return $avatar_defaults;
}

// Threaded Comments
function enable_threaded_comments()
{
    if (!is_admin()) {
        if (is_singular() AND comments_open() AND (get_option('thread_comments') == 1)) {
            wp_enqueue_script('comment-reply');
        }
    }
}

// Custom Comments Callback
function wpbootstrapsasscomments($comment, $args, $depth)
{
	$GLOBALS['comment'] = $comment;
	extract($args, EXTR_SKIP);

	if ( 'div' == $args['style'] ) {
		$tag = 'div';
		$add_below = 'comment';
	} else {
		$tag = 'li';
		$add_below = 'div-comment';
	}
?>
    <!-- heads up: starting < for the html tag (li or div) in the next line: -->
    <<?php echo $tag ?> <?php comment_class(empty( $args['has_children'] ) ? '' : 'parent') ?> id="comment-<?php comment_ID() ?>">
	<?php if ( 'div' != $args['style'] ) : ?>
	<div id="div-comment-<?php comment_ID() ?>" class="comment-body">
	<?php endif; ?>
	<div class="comment-author vcard">
	<?php if ($args['avatar_size'] != 0) echo get_avatar( $comment, $args['avatar_size'] ); ?>
	<?php printf(__('<cite class="fn">%s</cite> <span class="says">says:</span>'), get_comment_author_link()) ?>
	</div>
<?php if ($comment->comment_approved == '0') : ?>
	<em class="comment-awaiting-moderation"><?php _e('Your comment is awaiting moderation.') ?></em>
	<br />
<?php endif; ?>

	<div class="comment-meta commentmetadata"><a href="<?php echo htmlspecialchars( get_comment_link( $comment->comment_ID ) ) ?>">
		<?php
			printf( __('%1$s at %2$s'), get_comment_date(),  get_comment_time()) ?></a><?php edit_comment_link(__('(Edit)'),'  ','' );
		?>
	</div>

	<?php comment_text() ?>

	<div class="reply">
	<?php comment_reply_link(array_merge( $args, array('add_below' => $add_below, 'depth' => $depth, 'max_depth' => $args['max_depth']))) ?>
	</div>
	<?php if ( 'div' != $args['style'] ) : ?>
	</div>
	<?php endif; ?>
<?php }

// add Bootstrap 4 .img-fluid class to images inside post content
function add_class_to_image_in_content($content) 
{

	$content = mb_convert_encoding($content, 'HTML-ENTITIES', "UTF-8");
	$document = new DOMDocument();
	libxml_use_internal_errors(true);
	$document->loadHTML(utf8_decode($content));

	$imgs = $document->getElementsByTagName('img');
	foreach ($imgs as $img) {           
		$img->setAttribute('class','img-fluid');
	}

	$html = $document->saveHTML();
	return $html;  	

}

/*------------------------------------*\
	Actions + Filters + ShortCodes
\*------------------------------------*/

// Add Actions
add_action('init', 'wpbootstrapsass_header_scripts'); // Add Custom Scripts to wp_head
add_action('wp_print_scripts', 'wpbootstrapsass_conditional_scripts'); // Add Conditional Page Scripts
add_action('get_header', 'enable_threaded_comments'); // Enable Threaded Comments
add_action('wp_enqueue_scripts', 'wpbootstrapsass_styles'); // Add Theme Stylesheet
add_action('init', 'register_wpbootstrapsass_menu'); // Add WP Bootstrap Sass Menu
add_action('init', 'create_post_type_custom_post_type_demo'); // Add our WP Bootstrap Sass Custom Post Type
add_action('widgets_init', 'my_remove_recent_comments_style'); // Remove inline Recent Comment Styles from wp_head()
add_action('init', 'wpbootstrapsass_pagination'); // Add our wpbootstrapsass Pagination

// Remove Actions
remove_action('wp_head', 'feed_links_extra', 3); // Display the links to the extra feeds such as category feeds
remove_action('wp_head', 'feed_links', 2); // Display the links to the general feeds: Post and Comment Feed
remove_action('wp_head', 'rsd_link'); // Display the link to the Really Simple Discovery service endpoint, EditURI link
remove_action('wp_head', 'wlwmanifest_link'); // Display the link to the Windows Live Writer manifest file.
remove_action('wp_head', 'index_rel_link'); // Index link
remove_action('wp_head', 'parent_post_rel_link', 10, 0); // Prev link
remove_action('wp_head', 'start_post_rel_link', 10, 0); // Start link
remove_action('wp_head', 'adjacent_posts_rel_link', 10, 0); // Display relational links for the posts adjacent to the current post.
remove_action('wp_head', 'wp_generator'); // Display the XHTML generator that is generated on the wp_head hook, WP version
remove_action('wp_head', 'adjacent_posts_rel_link_wp_head', 10, 0);
remove_action('wp_head', 'rel_canonical');
remove_action('wp_head', 'wp_shortlink_wp_head', 10, 0);

// Add Filters
add_filter('script_loader_tag', 'add_script_tag_attributes', 10, 2); // Add attributes to CDN script tag
add_filter('avatar_defaults', 'wpbootstrapsassgravatar'); // Custom Gravatar in Settings > Discussion
add_filter('body_class', 'add_slug_to_body_class'); // Add slug to body class (Starkers build)
add_filter('widget_text', 'do_shortcode'); // Allow shortcodes in Dynamic Sidebar
add_filter('widget_text', 'shortcode_unautop'); // Remove <p> tags in Dynamic Sidebars (better!)
add_filter('wp_nav_menu_args', 'my_wp_nav_menu_args'); // Remove surrounding <div> from WP Navigation
// add_filter('nav_menu_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected classes (Commented out by default)
// add_filter('nav_menu_item_id', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> injected ID (Commented out by default)
// add_filter('page_css_class', 'my_css_attributes_filter', 100, 1); // Remove Navigation <li> Page ID's (Commented out by default)
add_filter('the_category', 'remove_category_rel_from_category_list'); // Remove invalid rel attribute
add_filter('the_excerpt', 'shortcode_unautop'); // Remove auto <p> tags in Excerpt (Manual Excerpts only)
add_filter('the_excerpt', 'do_shortcode'); // Allows Shortcodes to be executed in Excerpt (Manual Excerpts only)
add_filter('excerpt_more', 'wpbootstrapsass_view_article'); // Add 'View Article' button instead of [...] for Excerpts
add_filter('show_admin_bar', 'remove_admin_bar'); // Remove Admin bar
add_filter('style_loader_tag', 'wpbootstrapsass_style_remove'); // Remove 'text/css' from enqueued stylesheet
add_filter('post_thumbnail_html', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to thumbnails
add_filter('image_send_to_editor', 'remove_thumbnail_dimensions', 10); // Remove width and height dynamic attributes to post images
// add .img-fluid class to images in the content
add_filter('the_content', 'add_class_to_image_in_content');

// Remove Filters
remove_filter('the_excerpt', 'wpautop'); // Remove <p> tags from Excerpt altogether

// Shortcodes
add_shortcode('wpbootstrapsass_shortcode_demo', 'wpbootstrapsass_shortcode_demo'); // You can place [wpbootstrapsass_shortcode_demo] in Pages, Posts now.
add_shortcode('wpbootstrapsass_shortcode_demo_2', 'wpbootstrapsass_shortcode_demo_2'); // Place [wpbootstrapsass_shortcode_demo_2] in Pages, Posts now.

// Shortcodes above would be nested like this -
// [wpbootstrapsass_shortcode_demo] [wpbootstrapsass_shortcode_demo_2] Here's the page title! [/wpbootstrapsass_shortcode_demo_2] [/wpbootstrapsass_shortcode_demo]

/*------------------------------------*\
	Custom Post Types
\*------------------------------------*/

// Create 1 Custom Post type for a Demo, called custom-post-type
function create_post_type_custom_post_type_demo()
{
    register_taxonomy_for_object_type('category', 'custom-post-type'); // Register Taxonomies for Category
    register_taxonomy_for_object_type('post_tag', 'custom-post-type');
    register_post_type('custom-post-type', // Register Custom Post Type
        array(
        'labels' => array(
            'name' => __('WP Bootstrap Sass Custom Post', 'wpbootstrapsass'), // Rename these to suit
            'singular_name' => __('WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'add_new' => __('Add New', 'wpbootstrapsass'),
            'add_new_item' => __('Add New WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'edit' => __('Edit', 'wpbootstrapsass'),
            'edit_item' => __('Edit WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'new_item' => __('New WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'view' => __('View WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'view_item' => __('View WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'search_items' => __('Search WP Bootstrap Sass Custom Post', 'wpbootstrapsass'),
            'not_found' => __('No WP Bootstrap Sass Custom Posts found', 'wpbootstrapsass'),
            'not_found_in_trash' => __('No WP Bootstrap Sass Custom Posts found in Trash', 'wpbootstrapsass')
        ),
        'public' => true,
        'hierarchical' => true, // Allows your posts to behave like Hierarchy Pages
        'has_archive' => true,
        'supports' => array(
            'title',
            'editor',
            'excerpt',
            'thumbnail'
        ), // Go to Dashboard Custom WP Bootstrap Sass post for supports
        'can_export' => true, // Allows export in Tools > Export
        'taxonomies' => array(
            'post_tag',
            'category'
        ) // Add Category and Post Tags support
    ));
}

/*------------------------------------*\
	ShortCode Functions
\*------------------------------------*/

// Shortcode Demo with Nested Capability
function wpbootstrapsass_shortcode_demo($atts, $content = null)
{
    return '<div class="shortcode-demo">' . do_shortcode($content) . '</div>'; // do_shortcode allows for nested Shortcodes
}

// Shortcode Demo with simple <h2> tag
function wpbootstrapsass_shortcode_demo_2($atts, $content = null) // Demo Heading H2 shortcode, allows for nesting within above element. Fully expandable.
{
    return '<h2>' . $content . '</h2>';
}

?>
