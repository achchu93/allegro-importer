(function($) {

	$(function () {

		var activeCategory;

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

			$.ajax({
				url: ajaxurl,
				method: 'POST',
				data: {
					action: "get_allegro_offers",
					filters: "category.id="+activeCategory
				}
			}).then(function(response){
				var list = "";
				if(response.items.promoted.length){
					$.each(response.items.promoted, function(i, product){
						var source = $("#temp-product-grid").html();
						var template = Handlebars.compile(source);
						list += template(product);
					});

					$("#grids").html("").html(list);
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
	});


}(window.jQuery));