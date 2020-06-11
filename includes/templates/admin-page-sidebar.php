<?php
$tokens = get_option('allegro_tokens');
if (empty($tokens) || empty($tokens->access_token)) {
	exit();
}

use AsocialMedia\AllegroApi\AllegroRestApi;

$restApi  = new AllegroRestApi($tokens->access_token);
$response = $restApi->get('/sale/categories');

$categories = !empty($response->categories) ? $response->categories : [];
?>

<div class="container sidebar menu">
	<h3 class="is-size-5 has-text-weight-semibold border-heading"><?php _e("Categories", "allegro-import"); ?></h3>
	<?php if (count($categories)) :  ?>
		<ul class="filter-container menu-list">
			<?php foreach ($categories as $category) : ?>
				<li class="category-item" data-category-id="<?php echo $category->id; ?>">
					<label class="checkbox">
						<input type="checkbox" value="<?php echo $category->id; ?>" />
						<?php echo $category->name; ?>
					</label>
				</li>
			<?php endforeach; ?>
		</ul>
	<?php endif; ?>

	<h3 class="is-size-5 has-text-weight-semibold border-heading"><?php _e("Filters", "allegro-import"); ?></h3>

	<div id="filters">
		<?php _e( "Will be available on initial product load", "allegro-import" ) ?>
	</div>

	<button class="button is-primary is-normal" id="filter-btn">Apply Filter</button>
</div>