<?php
/**
 * Template part for displaying results in search pages
 *
 * @link https://codex.wordpress.org/Template_Hierarchy
 *
 * @package WP_Bootstrap_Starter
 */
$keysearch = $_GET['s'] ? trim($_GET['s']) : '';
$key_highligh = "<b>".$keysearch."</b>";

//Content
$page_builder = get_field('page_builder');
$content = '';
foreach ($page_builder as $key => $builder) {
	if($builder['acf_fc_layout'] == 'single_column_content'){
		$content = $builder['content'];
	}
}

$content = wp_trim_words(	$content , 25, '...' );
$content = str_replace($keysearch, $key_highligh , $content);

//Title
$title = wp_trim_words(	get_the_title() , 15 , '...' );
$title = str_replace($keysearch, $key_highligh , $title);

//Link
$link_text = trim(get_permalink());
$strlen = strlen($link_text);
$endtext =  substr( $link_text , -1 );
if($endtext == '/'){
	$link_text = substr( $link_text , 0, $strlen - 1 );
}
$link_text = substr( $link_text , 0 , 80 );
$link_text = str_replace( 'https://' , '' , $link_text );
$link_text = str_replace( 'www.' , '' , $link_text );
$link_text = str_replace( '/' , ' &#8250; ' , $link_text );
$link_text = 'https://'.$link_text;

if($strlen > 80 ){
	$link_text = $link_text.'...';
}


?>
<article id="post-<?php the_ID(); ?>" class="item-search">

	<h2 class="entry-title">
		<a href="<?php echo esc_url(get_permalink()); ?>" rel="bookmark"><?php echo $title; ?></a>
	</h2>

	<div class="entry-link-code">
		<a href="<?php echo esc_url(get_permalink()); ?>"><?php echo $link_text; ?></a>
	</div>

	<div class="entry-summary">
		<?php echo $content; ?>
	</div><!-- .entry-summary -->

</article><!-- #post-## -->
