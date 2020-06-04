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
	<div class="box" id="{{id}}">
		<article class="media">
			<div class="media-left">
				<figure class="image is-128x128">
					<img src="{{images.[0].url}}" alt="{{name}}">
				</figure>
			</div>
			<div class="media-content">
				<div class="content">
					<h4 class="title is-4"><strong>{{name}}</strong></h4>
					<h2 class="title is-2">{{sellingMode.price.amount}} {{sellingMode.price.currency}}</h2>
					<div><strong>Stock: </strong>{{stock.available}} <small>{{stock.unit}}</small></div>
				</div>
			</div>
		</article>
	</div>
</script>