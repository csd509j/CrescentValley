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
function cvhs_theme_enqueue_styles() {
    
	$parent_style = 'csdschools';
	
	wp_enqueue_style( $parent_style, get_template_directory_uri() . '/style.css' );
	wp_enqueue_style( 'cvhs-style',
	    get_stylesheet_directory_uri() . '/style.css',
	    array( $parent_style ),
	    wp_get_theme()->get('Version')
	);

}
add_action( 'wp_enqueue_scripts', 'cvhs_theme_enqueue_styles' );

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

/**
 * Add option menus
 *
 * @since CSD Schools 1.0
 */
 
if( function_exists('acf_add_options_page') ) {
	
	acf_add_options_page();
	
}
if( function_exists('acf_add_options_sub_page') ) {
    acf_add_options_sub_page( 'General' );
    acf_add_options_sub_page( 'Pages' );
    acf_add_options_sub_page( 'Calendar' );
    acf_add_options_sub_page( 'Footer' );
    acf_add_options_sub_page( '404 Page' );
    acf_add_options_sub_page( 'District Info' );
}

/**
 * Load sidebar select fields with callout blocks from options
 *
 * @since CSD Schools 1.0
 */
 
 function acf_load_sidebar_callout_blocks_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // if has rows
    if( have_rows('callout_blocks', 'option') ) {
        
        // while has rows
        while( have_rows('callout_blocks', 'option') ) {
            
            // instantiate row
            the_row();
            
            // vars
            $value = get_sub_field('callout_block_heading');
            $label = get_sub_field('callout_block_heading');

            
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
        
    }

    // return the field
    return $field;
    
} 
add_filter('acf/load_field/name=sidebar_callout_blocks', 'acf_load_sidebar_callout_blocks_field_choices');

/**
 * Load sidebar select fields with contact blocks from options
 *
 * @since CSD Schools 1.0
 */
 
function acf_load_sidebar_contact_blocks_field_choices( $field ) {
    
    // reset choices
    $field['choices'] = array();

    // if has rows
    if( have_rows('contact_blocks', 'option') ) {
        
        // while has rows
        while( have_rows('contact_blocks', 'option') ) {
            
            // instantiate row
            the_row();
            
            // vars
            $value = get_sub_field('contact_name');
            $label = get_sub_field('contact_name');

            
            // append to choices
            $field['choices'][ $value ] = $label;
            
        }
        
    }

    // return the field
    return $field;
    
} 
add_filter('acf/load_field/name=sidebar_contact_block', 'acf_load_sidebar_contact_blocks_field_choices');

/**
 * Set featured image from ACF field
 *
 * @since CSD Schools 1.0
 */
 
function acf_set_featured_image( $value, $post_id, $field  ){
	
	$id = $value;
	
	if( ! is_numeric( $id ) ){
		
		$data = json_decode( stripcslashes($id), true );
		$id = $data['cropped_image'];
	
	}
	
	update_post_meta( $post_id, '_thumbnail_id', $id );
	
	return $value;
}
// acf/update_value/name={$field_name} - filter for a specific field based on it's name
add_filter( 'acf/update_value/name=featured_image', 'acf_set_featured_image', 10, 3 );
