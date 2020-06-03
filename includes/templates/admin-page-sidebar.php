<?php 
$tokens = get_option('allegro_tokens');
if(empty($tokens) || empty($tokens->access_token)){
	exit();
}

use AsocialMedia\AllegroApi\AllegroRestApi;
$restApi  = new AllegroRestApi($tokens->access_token); 
$response = $restApi->get('/sale/categories');

$categories = !empty($response->categories) ? $response->categories : [];
?>

<div class="container">
	<h3 class="is-size-5 border-heading"><?php _e( "Categories", "allegro-import" ); ?></h3>
	<?php if( count($categories) ):  ?>
	<ul>
	<?php foreach($categories as $category): ?>
	<li>
		<label class="checkbox">
		<input type="checkbox" value="<?php echo $category->id; ?>" />
		<?php echo $category->name; ?>
		</label>
	</li>
	<?php endforeach; ?>
	</ul>
	<?php endif; ?>

	<hr />
	<h3 class="is-size-5 border-heading"><?php _e( "Filters", "allegro-import" ); ?></h3>

	<h4 class="is-size-6 filter-heading"><?php _e( "State", "allegro-import" ); ?></h4>
	<ul class="menu-list">
		<li>
			<label class="checkbox">
				<input type="checkbox" value="new" />
				<?php _e( "New", "allegro-import" ); ?>
			</label>
		</li>
		<li>
			<label class="checkbox">
				<input type="checkbox" value="used" />
				<?php _e( "Used", "allegro-import" ); ?>
			</label>
		</li>
		<li>
			<label class="checkbox">
				<input type="checkbox" value="broken" />
				<?php _e( "Broken", "allegro-import" ); ?>
			</label>
		</li>
	</ul>

	<h4 class="is-size-6 filter-heading"><?php _e( "Type of offer", "allegro-import" ); ?></h4>
	<ul class="menu-list">
		<li>
			<label class="checkbox">
				<input type="checkbox" value="buynow" />
				<?php _e( "Buy now", "allegro-import" ); ?>
			</label>
		</li>
		<li>
			<label class="checkbox">
				<input type="checkbox" value="auctions" />
				<?php _e( "Austions", "allegro-import" ); ?>
			</label>
		</li>
	</ul>

	<h4 class="is-size-6 filter-heading"><?php _e( "Price", "allegro-import" ); ?></h4>
	<div class="field has-addons has-icons-left">
  		<div class="control">
    		<input class="input" type="number" placeholder="From">
  		</div>
		<div class="control">
			<a class="button is-static">-</a>
		</div>
		<div class="control">
    		<input class="input" type="number" placeholder="To">
  		</div>
	</div>

	<h4 class="is-size-6 filter-heading"><?php _e( "Delivery methods", "allegro-import" ); ?></h4>
	<ul class="menu-list">
		<li>
			<label class="checkbox">
				<input type="checkbox" value="buynow" />
				<?php _e( "Buy now", "allegro-import" ); ?>
			</label>
		</li>
		<li>
			<label class="checkbox">
				<input type="checkbox" value="auctions" />
				<?php _e( "Austions", "allegro-import" ); ?>
			</label>
		</li>
	</ul>
</div>