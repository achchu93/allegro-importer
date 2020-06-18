<script type="text/template" id="temp-child-categories">
	<ul class="child-categories" data-category-parent="{{categoryId}}">
		{{#each categories}}
		<li class="category-item" data-category-id="{{this.id}}" data-leaf="{{this.leaf}}">
			<label class="checkbox">
				<input type="checkbox" value="{{this.id}}"/>
				{{this.name}}
			</label>
		</li>
		{{/each}}
	</ul>
</script>

<script type="text/template" id="temp-product-grid">
	<div class="box product-grid" id="{{id}}">
		{{#if permalink}}
		<div class="tags has-addons">
			<span class="tag is-success">Imported</span>
			<span class="tag is-success is-light"><a href="{{permalink}}" target="_blank">View</a></span>
    	</div>
		{{/if}}
		<input type="checkbox" value="{{id}}" class="offer-id">
		<article class="media">
			<div class="media-left">
				<figure class="image is-128x128">
					<img src="{{images.[0].url}}" alt="{{name}}">
				</figure>
			</div>
			<div class="media-content">
				<div class="content">
					<h4 class="title is-4"><strong>{{name}}</strong></h4>
					<h2 class="price-pln title is-5">
					{{sellingMode.price.amount}} {{sellingMode.price.currency}}
					</h2>
					<h2 class="price-uah title is-2">{{'*' sellingMode.price.amount <?php echo ai_get_currency_rate(); ?>}} <?php echo get_woocommerce_currency_symbol(); ?></h2>
					<div><strong>Stock: </strong>{{stock.available}} <small>{{stock.unit}}</small></div>
				</div>
			</div>
		</article>
	</div>
</script>

<script type="text/template" id="temp-import-url">
	<div class="tags has-addons">
		{{#if url}}
		<span class="tag is-success">Imported</span>
		<span class="tag is-success is-light"><a href="{{url}}" target="_blank">View</a></span>
		{{else}}
		<span class="tag is-danger">Import Failed!</span>
		{{/if}}
    </div>
</script>

<script type="text/template" id="temp-filters">
	{{#each filters}}
		{{#if this.values.length}}
			<h4 class="is-size-6 is-capitalized filter-heading has-text-weight-medium">{{ this.name }}</h4>
			{{#ifEquals this.type "MULTI"}}
				<ul class="filter-container">
					{{#each this.values}}
						<li>
							<label class="checkbox">
								<input name="{{../this.id}}" type="checkbox" value="{{this.value}}" {{#if this.selected}}checked {{/if}}>
								{{this.name}}
							</label>
						</li>
					{{/each}}
				</ul>
			{{/ifEquals}}
			{{#ifEquals this.type "SINGLE"}}
				<ul class="filter-container">
					{{#each this.values}}
						<li>
							<label class="radio">
								<input name="{{../this.id}}" type="radio" value="{{this.value}}" {{#if this.selected}}checked {{/if}}>
								{{this.name}}
							</label>
						</li>
					{{/each}}
				</ul>
			{{/ifEquals}}
			{{#ifEquals this.type "TEXT"}}
				<ul class=" filter-container">
					<li>
						<div class="control">
							<input 
							name="{{this.id}}" 
							type="text" 
							placeholder="{{this.name}}" 
							{{#if this.values.[0].selected}} value="{{this.values.[0].value}}" {{/if}}
							>
						</div>
					</li>
				</ul>
			{{/ifEquals}}
			{{#ifEquals this.type "NUMERIC"}}
				<div class="field has-addons has-icons-left filter-container">
					<div class="control">
						<input 
						class="input" 
						min="{{this.minValue}}" 
						max="{{this.maxValue}}" 
						name="{{this.id}}.from" 
						type="number" 
						placeholder="From"
						{{#if this.values.[0].selected}} value="{{this.values.[0].value}}" {{/if}}
						>
					</div>
					<div class="control">
						<a class="button is-static">
							<span class="icon is-large">
								<i class="fas fa-minus"></i>
							</span>
						</a>
					</div>
					<div class="control">
						<input 
						class="input" 
						min="{{this.minValue}}" 
						max="{{this.maxValue}}" 
						name="{{this.id}}.to" 
						type="number" 
						placeholder="To"
						{{#if this.values.[1].selected}} value="{{this.values.[1].value}}" {{/if}}
						>
					</div>
				</div>
			{{/ifEquals}}
		{{/if}}
	{{/each}}
</script>