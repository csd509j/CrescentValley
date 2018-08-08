<?php 
/*
 * Theme update checker
 *
 * @since CVHS 1.0
 */
require WP_CONTENT_DIR . '/plugins/plugin-update-checker-master/plugin-update-checker.php';
$myUpdateChecker = Puc_v4_Factory::buildUpdateChecker(
	'https://github.com/csd509j/CrescentValley',
	__FILE__,
	'CrescentValley'
);

$myUpdateChecker->setBranch('master'); 

/*
 * Setup style sheets
 *
 * @since CVHS 1.0
 */
function lpms_theme_enqueue_styles() {
    
	$parent_style = 'csdschools';
	
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'lpms-style',
	    get_stylesheet_directory_uri() . '/style.css',
	    array( $parent_style ),
	    wp_get_theme()->get('Version')
	);

}
add_action( 'wp_enqueue_scripts', 'lpms_theme_enqueue_styles' );

function languages_toggle(){
/*
	global $wp;
	$url = $wp->request;
  	$languages = icl_get_languages('skip_missing=1');
*/
  	
  	$google_languages = array(
	  	'googtrans(en|es)' => 'Spanish',
	  	'googtrans(en|ar)' => 'ترجمه',
	  	'googtrans(en|zh-CN)' => 'Chinese',
	  	'googtrans(en|fr)' => 'French',
	  	'googtrans(en|de)' => 'German',
	  	'googtrans(en|ko)' => 'Korean',
	  	'googtrans(en|vi)' => 'Vietnamese'
  	);
  	
/*
	if(1 < count($languages)){
		foreach($languages as $l) {
			if($l['active']) {
				$active = $l['native_name'];
			}
		}
	} else {
*/
		if(strpos($url, "#") === false) {
			$active = "English";
		} else {
			$key = explode("#", $url)[0];
			$active = $google_languages[$key];
		}		
// 	}
	?>

  	<div class="translated-btn">
		<div class="dropdown">
			<button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
				<i class="fa fa-comment"></i> Translate <span class="caret"></span>
			</button>
			<ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
<!--
			<?php if(1 < count($languages)): ?>
				<?php foreach($languages as $l): ?>
					<?php if(!$l['active']): ?>
						<li><a href="<?php echo $l['url']; ?>"><?php echo $l['translated_name']; ?></a></li>
					<?php endif; ?>
				<?php endforeach; ?>
			<?php endif; ?>
-->
			
			<?php foreach($google_languages as $key => $val): ?>
				<li><a href="<?php echo home_url(); ?>/#<?php echo $key; ?>" target="_blank"><?php echo $val; ?></a></li>
			<?php endforeach; ?>
			</ul>
		</div>
	</div>
	
<?php
}