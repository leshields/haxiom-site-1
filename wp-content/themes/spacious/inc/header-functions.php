<?php
/**
 * Contains all the fucntions and components related to header part.
 *
 * @package 		ThemeGrill
 * @subpackage 		Spacious
 * @since 			Spacious 1.0
 */

/****************************************************************************************/

add_filter( 'wp_title', 'spacious_filter_wp_title' );
if ( ! function_exists( 'spacious_filter_wp_title' ) ) :
/**
 * Modifying the Title
 *
 * Function tied to the wp_title filter hook.
 * @uses filter wp_title
 */
function spacious_filter_wp_title( $title ) {
	global $page, $paged;
	
	// Get the Site Name
   $site_name = get_bloginfo( 'name' );

   // Get the Site Description
   $site_description = get_bloginfo( 'description' );

   $filtered_title = ''; 

	// For Homepage or Frontpage
   if(  is_home() || is_front_page() ) {		
		$filtered_title .= $site_name;	
		if ( !empty( $site_description ) )  {
        	$filtered_title .= ' &#124; '. $site_description;
		}
   }
	elseif( is_feed() ) {
		$filtered_title = '';
	}
	else{	
		$filtered_title = $title . $site_name;
	}

	// Add a page number if necessary:
	if( $paged >= 2 || $page >= 2 ) {
		$filtered_title .= ' &#124; ' . sprintf( __( 'Page %s', 'spacious' ), max( $paged, $page ) );
	}
	
	// Return the modified title
   return $filtered_title;
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'spacious_render_header_image' ) ) :
/**
 * Shows the small info text on top header part
 */
function spacious_render_header_image() {
	$header_image = get_header_image();
	if( !empty( $header_image ) ) {
	?>
		<img src="<?php echo esc_url( $header_image ); ?>" class="header-image" width="<?php echo get_custom_header()->width; ?>" height="<?php echo get_custom_header()->height; ?>" alt="<?php echo esc_attr( get_bloginfo( 'name', 'display' ) ); ?>">
	<?php
	}
}
endif;

/****************************************************************************************/

if ( ! function_exists( 'spacious_featured_image_slider' ) ) :
/**
 * display featured post slider
 */
function spacious_featured_image_slider() {
	global $post;
	?>
		<section id="featured-slider">
			<div class="slider-cycle">
				<?php
				for( $i = 1; $i <= 5; $i++ ) {
					$spacious_slider_title = of_get_option( 'spacious_slider_title'.$i , '' );
					$spacious_slider_text = of_get_option( 'spacious_slider_text'.$i , '' );
					$spacious_slider_image = of_get_option( 'spacious_slider_image'.$i , '' );
					$spacious_slider_link = of_get_option( 'spacious_slider_link'.$i , '#' );
					if( !empty( $spacious_header_title ) || !empty( $spacious_slider_text ) || !empty( $spacious_slider_image ) ) {
						if ( $i == 1 ) { $classes = "slides displayblock"; } else { $classes = "slides displaynone"; }
						?>
						<div class="<?php echo $classes; ?>">
							<figure>
								<img alt="<?php echo esc_attr( $spacious_slider_title ); ?>" src="<?php echo esc_url( $spacious_slider_image ); ?>">
							</figure>
							<div class="entry-container">
								<?php if( !empty( $spacious_slider_title ) || !empty( $spacious_slider_text ) ) { ?>
								<div class="entry-description-container">
									<?php if( !empty( $spacious_slider_title ) ) { ?>
									<div class="slider-title-head"><h3 class="entry-title"><a href="<?php echo esc_url( $spacious_slider_link ); ?>" title="<?php echo esc_attr( $spacious_slider_title ); ?>"><span><?php echo $spacious_slider_title; ?></span></a></h3></div>
									<?php
									}
									if( !empty( $spacious_slider_text ) ) {
										?>
									<div class="entry-content"><p><?php echo $spacious_slider_text; ?></p></div>
									<?php 
									}
									?>
								</div>
								<?php } ?>
								<div class="clearfix"></div>
								<a class="slider-read-more-button" href="<?php echo esc_url( $spacious_slider_link ); ?>" title="<?php echo esc_attr( $spacious_slider_title ); ?>"><?php _e( 'Read more', 'spacious' ); ?></a>
							</div>
						</div>
						<?php
					}
				}
				?>
			</div>
			<nav id="controllers" class="clearfix"></nav>
		</section>

		<?php
}
endif;



if ( ! function_exists( 'spacious_header_title' ) ) :
/**
 * Show the title in header
 */
function spacious_header_title() {
	if( is_archive() ) {
		if ( is_category() ) :
			$spacious_header_title = single_cat_title( '', FALSE );

		elseif ( is_tag() ) :
			$spacious_header_title = single_tag_title( '', FALSE );

		elseif ( is_author() ) :
			/* Queue the first post, that way we know
			 * what author we're dealing with (if that is the case).
			*/
			the_post();
			$spacious_header_title =  sprintf( __( 'Author: %s', 'spacious' ), '<span class="vcard">' . get_the_author() . '</span>' );
			/* Since we called the_post() above, we need to
			 * rewind the loop back to the beginning that way
			 * we can run the loop properly, in full.
			 */
			rewind_posts();

		elseif ( is_day() ) :
			$spacious_header_title = sprintf( __( 'Day: %s', 'spacious' ), '<span>' . get_the_date() . '</span>' );

		elseif ( is_month() ) :
			$spacious_header_title = sprintf( __( 'Month: %s', 'spacious' ), '<span>' . get_the_date( 'F Y' ) . '</span>' );

		elseif ( is_year() ) :
			$spacious_header_title = sprintf( __( 'Year: %s', 'spacious' ), '<span>' . get_the_date( 'Y' ) . '</span>' );

		elseif ( is_tax( 'post_format', 'post-format-aside' ) ) :
			$spacious_header_title = __( 'Asides', 'spacious' );

		elseif ( is_tax( 'post_format', 'post-format-image' ) ) :
			$spacious_header_title = __( 'Images', 'spacious');

		elseif ( is_tax( 'post_format', 'post-format-video' ) ) :
			$spacious_header_title = __( 'Videos', 'spacious' );

		elseif ( is_tax( 'post_format', 'post-format-quote' ) ) :
			$spacious_header_title = __( 'Quotes', 'spacious' );

		elseif ( is_tax( 'post_format', 'post-format-link' ) ) :
			$spacious_header_title = __( 'Links', 'spacious' );

		else :
			$spacious_header_title = __( 'Archives', 'spacious' );

		endif;
	}
	elseif( is_404() ) {
		$spacious_header_title = __( 'Page NOT Found', 'spacious' );
	}
	elseif( is_search() ) {
		$spacious_header_title = __( 'Search Results', 'spacious' );
	}
	elseif( is_page()  ) {
		$spacious_header_title = get_the_title();
	}
	elseif( is_single()  ) {
		$spacious_header_title = get_the_title();
	}
	else {
		$spacious_header_title = '';
	}

	return $spacious_header_title;

}
endif;

/****************************************************************************************/

if ( ! function_exists( 'spacious_breadcrumb' ) ) :
/**
 * Display breadcrumb on header.
 *
 * If the page is home or front page, slider is displayed.
 * In other pages, breadcrumb will display if breadcrumb NavXT plugin exists.
 */
function spacious_breadcrumb() {
	if( function_exists( 'bcn_display' ) ) {
		echo '<div class="breadcrumb">'; 
		echo '<span class="breadcrumb-title">'.__( 'You are here:', 'spacious' ).'</span>';           
		bcn_display();               
		echo '</div> <!-- .breadcrumb -->'; 
	}   
}
endif;

?>