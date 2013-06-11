<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( 'Site Data', 'agriflex' ); ?></h2>
	
	<?php $fields->settings(); ?>

	<?php $t = new AgriLife_Data; ?>

	<?php var_dump(get_site_option('site_data')); ?>
	
</div><!-- .wrap -->
