(function($) {

	registerHelpers();

	$(function () {

		var activeCategory;
		var globalFilters;
		var offset = 0;

		$('.filter-container').on('change','.category-item [type=checkbox]', function(e){
			if($(this).is(":checked")){
				var categoryItem = $(this).parents(".category-item").first();
				var categoryId = categoryItem.data("category-id");
				var parentCatId = $('[data-category-id="' + categoryId + '"]').parents('[data-category-parent]').last().data('category-parent');

				activeCategory = categoryId;

				if (categoryItem.find('.child-categories').length || categoryItem.data('leaf')){
					return;
				}

				$('.category-item [type=checkbox]')
					.not(jQuery('.category-item[data-category-id="' + (parentCatId ? parentCatId : categoryId) +'"] [type=checkbox]'))
					.prop('checked', false);

				$.ajax({
					url: ajaxurl,
					method: 'POST',
					data: {
						action: 'get_allegro_categories',
						category: categoryId
					}
				}).then(function (response) {
					if(response.categories.length){
						var source = $("#temp-child-categories").html();
						var template = Handlebars.compile(source);

						categoryItem.append(template({ categoryId: categoryId, categories: response.categories }));
					}
				});
			}
		});

		$('#filter-btn').on('click', function(e){
			var checkedCategories = $('.category-item [type=checkbox]:checked');

			if(!checkedCategories.length){
				alert("category should be selected");
				return;
			}

			var activeCatEl = $('.category-item[data-category-id=' + activeCategory + ']');
			if (!activeCatEl.length || !activeCatEl.parent().hasClass('child-categories')){
				alert("select a child category. Root level category doesnt have items");
				return;
			}

			globalFilters = "category.id=" + activeCategory + "&include=all";
			var serialized = $("#filters").find('[type=text],[type=number],[type=checkbox],[type=radio]').filter(function () {
				return $(this).val();
			}).serialize();
			if( $.trim(serialized) !== '' ){
				globalFilters += "&"+serialized;
			}

			offset = 0;
			$("#grids").html("").block();
			$("html, body").animate({ scrollTop: 0 }, 500);
			$("#pagination").addClass("is-hidden");

			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					action: "get_allegro_offers",
					filters: globalFilters
				}
			}).then(function(response){
				var list  = "";
				var count = 0;
				if(response.items){
					$.each(response.items.promoted, function(i, product){
						var source = $("#temp-product-grid").html();
						var template = Handlebars.compile(source);
						list += template(product);
						offset++;
						count++;
					});

					$.each(response.items.regular, function (i, product) {
						var source = $("#temp-product-grid").html();
						var template = Handlebars.compile(source);
						list += template(product);
						offset++;
						count++;
					});
				}

				$("#grids").html(list);

				if (count >= 10) {
					$("#pagination").removeClass("is-hidden");
				}

				var filters = "";
				if (response.filters && response.filters.length){
					var source = $("#temp-filters").html();
					var template = Handlebars.compile(source);
					filters += template({ filters: response.filters});

					$("#filters").html(filters);
				}
			});
		});

		$('#select-all').on('click', function(){

			$(".offer-id").prop("checked", $(this).hasClass('is-light')).trigger('change');
			$(this).toggleClass('is-light');
		});

		$('#grids').on('change', '.offer-id', function(){
			$('#import').toggleClass('is-light', !$('.offer-id:checked').length)
		});

		$('#import').on('click', function(e){

			var loadingContainer = $("#allegro-container");
			loadingContainer.block({
				message: 'Importing...'
			});

			var ids = $('.offer-id:checked').map(function (i, el) {
				return $(el).val();
			}).toArray();

			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					action: 'import_allegro_products',
					product_ids: ids,
					price: $("#price-increment").val()
				},
				timeout: 60000
			}).then(function(response){

				$.each(response, function(id, url){
					var checkbox = $('.offer-id[value=' + id + ']');
					var source = $("#temp-import-url").html();
					var template = Handlebars.compile(source);

					checkbox.prop('checked', false).trigger('change');
					checkbox.replaceWith(template({url: url}));
				});

				loadingContainer.unblock();
			});


		});

		$("#pagination").on('click', '.button', function(){

			$("#grids").html("").block();
			$("html, body").animate({ scrollTop: 0 }, 500);
			$("#pagination").addClass("is-hidden");

			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					action: "get_allegro_offers",
					filters: globalFilters + "&offset=" + offset
				}
			}).then(function (response) {
				var list  = "";
				var count = 0;
				if (response.items) {
					$.each(response.items.promoted, function (i, product) {
						var source = $("#temp-product-grid").html();
						var template = Handlebars.compile(source);
						list += template(product);
						offset++;
						count++;
					});

					$.each(response.items.regular, function (i, product) {
						var source = $("#temp-product-grid").html();
						var template = Handlebars.compile(source);
						list += template(product);
						offset++;
						count++;
					});					
				}

				$("#grids").html(list);

				if (count >= 10) {
					$("#pagination").removeClass("is-hidden");
				}

				var filters = "";
				if (response.filters && response.filters.length) {
					var source = $("#temp-filters").html();
					var template = Handlebars.compile(source);
					filters += template({ filters: response.filters });

					$("#filters").html(filters);
				}
			});

		});
	});


	function registerHelpers(){

		Handlebars.registerHelper('ifEquals', function (a, b, options) {
			if (a == b){
				return options.fn(this);
			}

			return options.inverse(this);
		});

		Handlebars.registerHelper('+', function (x, y) { return parseFloat(x + y).toFixed(2) });
		Handlebars.registerHelper('-', function (x, y) { return parseFloat(x - y).toFixed(2) });
		Handlebars.registerHelper('*', function (x, y) { return parseFloat(x * y).toFixed(2) });
		Handlebars.registerHelper('/', function (x, y) { return parseFloat(x / y).toFixed(2) });
	}


}(window.jQuery));