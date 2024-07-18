<?php
/**
 * Title:       Hero Section
 * Slug:        stanicky/hero-section
 * Categories:  header
 * Description: A hero section with a large background image, heading, and button.
 * Keywords:    hero, header, banner
 */
$image_url = esc_url(get_template_directory_uri() . '/assets/images/hero-image.jpg');
?>

<!-- wp:cover {"url":"<?php echo $image_url; ?>","dimRatio":50,"overlayColor":"black","isDark":false,"className":"is-light"} -->
<div class="wp-block-cover is-light has-background-dim"><span aria-hidden="true" class="wp-block-cover__background has-black-background-color has-background-dim"></span><img class="wp-block-cover__image-background" alt="" src="<?php echo $image_url; ?>" data-object-fit="cover"/><div class="wp-block-cover__inner-container"><!-- wp:heading {"textAlign":"center","level":1,"textColor":"white"} -->
<h1 class="has-text-align-center has-white-color has-text-color">Welcome to Our Website</h1>
<!-- /wp:heading -->

<!-- wp:buttons {"align":"center"} -->
<div class="wp-block-buttons aligncenter"><!-- wp:button {"backgroundColor":"secondary","textColor":"black"} -->
<div class="wp-block-button"><a class="wp-block-button__link has-black-color has-secondary-background-color has-text-color has-background">Get Started</a></div>
<!-- /wp:button --></div>
<!-- /wp:buttons --></div></div>
<!-- /wp:cover -->
