<div class="wrap">

	<?php screen_icon(); ?>
	<h2><?php _e( 'Site Data', 'agriflex' ); ?></h2>
	
	<form method="post" name="site-data-exporter" action="<?php echo plugin_dir_url( __FILE__ ) . 'export.php'; ?>">
		<h3>Download all site data: </h3>
		<p class="submit">
			<input class="button button-primary" type="submit" name="Download" value="Download" /></p>
	</form>

	
</div><!-- .wrap -->
