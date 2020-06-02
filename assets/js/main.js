(function($) {

	$(function () {

		loadCategories().then(function(response){
			console.log(response);
		});

	});

	$.ajaxSetup({
		headers: {
			//url: 'https://api.allegro.pl',
			"Content-type": 'application/vnd.allegro.public.v1+json',
			Accept: 'application/vnd.allegro.public.v1+json',
			Authorization: 'Bearer ' + btoa(ai_params.client_id + ':' + ai_params.client_secret),
			// "Access-Control-Allow-Headers": "Origin, Content-Type, Accept, Authorization",
			//"Access-Control-Allow-Origin": "*",
			// "Access-Control-Allow-Methods": 'GET',
			// "Access-Control-Allow-Headers": 'Access-Control-Allow-Origin, Access-Control-Allow-Methods, Accept, Authorization'
		}
	});

	function loadCategories(){
		return $.ajax({
			url: 'https://api.allegro.pl/sale/categories'
		});
	}


}(window.jQuery));