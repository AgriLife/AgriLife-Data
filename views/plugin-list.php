<div class="wrap">
	<h2><?php _e( 'Export Plugin List', 'agriflex' ); ?></h2>
	<form method="post" name="plugin-list-exporter" action="<?php echo plugin_dir_url( __FILE__ ) . 'plugin-export.php'; ?>">
		<h3>Download plugin list:</h3>
		<p class="submit">
			<input class="button button-primary" type="submit" name="Download" value="Download" />
		</p>
	</form>
</div>