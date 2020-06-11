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
		<h4 class="is-size-6 filter-heading has-text-weight-medium"><?php _e("State", "allegro-import"); ?></h4>
		<ul class="filter-container">
			<li>
				<label class="checkbox">
					<input type="checkbox" value="new" />
					<?php _e("New", "allegro-import"); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" value="used" />
					<?php _e("Used", "allegro-import"); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" value="broken" />
					<?php _e("Broken", "allegro-import"); ?>
				</label>
			</li>
		</ul>

		<h4 class="is-size-6 filter-heading has-text-weight-medium"><?php _e("Type of offer", "allegro-import"); ?></h4>
		<ul class="filter-container">
			<li>
				<label class="checkbox">
					<input type="checkbox" value="buynow" />
					<?php _e("Buy now", "allegro-import"); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" value="auctions" />
					<?php _e("Austions", "allegro-import"); ?>
				</label>
			</li>
		</ul>

		<h4 class="is-size-6 filter-heading has-text-weight-medium"><?php _e("Price", "allegro-import"); ?></h4>
		<div class="field has-addons has-icons-left filter-container">
			<div class="control">
				<input class="input" type="number" placeholder="From">
			</div>
			<div class="control">
				<a class="button is-static">
					<span class="icon is-large">
						<i class="fas fa-minus"></i>
					</span>
				</a>
			</div>
			<div class="control">
				<input class="input" type="number" placeholder="To">
			</div>
		</div>

		<h4 class="is-size-6 filter-heading has-text-weight-medium"><?php _e("Delivery methods", "allegro-import"); ?></h4>
		<ul class="menu-list">
			<li>
				<label class="checkbox">
					<input type="checkbox" value="buynow" />
					<?php _e("Buy now", "allegro-import"); ?>
				</label>
			</li>
			<li>
				<label class="checkbox">
					<input type="checkbox" value="auctions" />
					<?php _e("Austions", "allegro-import"); ?>
				</label>
			</li>
		</ul>
	</div>

	<button class="button is-primary is-normal" id="filter-btn">Apply Filter</button>
</div>