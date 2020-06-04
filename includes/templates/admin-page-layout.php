<section id="allegro-container" class="wrap">
	<h2 class="is-size-3">Allegro Importer</h2>

	<div class="container is-fluid has-background-white" style="padding-top:16px; padding-bottom:16px;margin-top:20px;">
		<div class="field is-grouped">
  			<div class="control field has-addons has-addons-right">
				<p class="control">
					<input class="input" type="text" placeholder="Price">
				</p>
				<p class="control">
					<a class="button">
						<span class="icon is-small is-right">
							<i class="fas fa-percentage"></i>
						</span>
					</a>
				</p>
			</div>
			<p class="control">
				<button id="import" class="button is-info is-light">Import</button>
			</p>
			<p class="control">
				<button id="select-all" class="button is-info is-light">Select All</button>
			</p>
		</div>
	</div>

	<div class="container is-fluid has-background-white" style="margin-top:15px; padding: 1.5rem;">
		<div class="columns">
			<div class="column is-one-quarter">
				<?php include_once("admin-page-sidebar.php") ?>
			</div>
			<div class="column is-three-quarters">
				<div id="grids">

				</div>
			</div>
		</div>
	</div>
</section>