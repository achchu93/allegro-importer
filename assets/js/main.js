(function($) {

	$(function () {

		loadCategories().then(function(response){
			console.log(response);
		});

	});

	$.ajaxSetup({
		headers: {
			//url: 'https://api.allegro.pl',
			Accept: 'application/vnd.allegro.public.v1+json',
			Authorization: 'Bearer eyJhbGciOiJSUzI1NiIsInR5cCI6IkpXVCJ9.eyJzY29wZSI6WyJhbGxlZ3JvOmFwaTpvcmRlcnM6cmVhZCIsImFsbGVncm86YXBpOnByb2ZpbGU6d3JpdGUiLCJhbGxlZ3JvOmFwaTpzYWxlOm9mZmVyczp3cml0ZSIsImFsbGVncm86YXBpOmJpbGxpbmc6cmVhZCIsImFsbGVncm86YXBpOmNhbXBhaWducyIsImFsbGVncm86YXBpOmRpc3B1dGVzIiwiYWxsZWdybzphcGk6c2FsZTpvZmZlcnM6cmVhZCIsImFsbGVncm86YXBpOmJpZHMiLCJhbGxlZ3JvOmFwaTpvcmRlcnM6d3JpdGUiLCJhbGxlZ3JvOmFwaTphZHMiLCJhbGxlZ3JvOmFwaTpwYXltZW50czp3cml0ZSIsImFsbGVncm86YXBpOnNhbGU6c2V0dGluZ3M6d3JpdGUiLCJhbGxlZ3JvOmFwaTpwcm9maWxlOnJlYWQiLCJhbGxlZ3JvOmFwaTpyYXRpbmdzIiwiYWxsZWdybzphcGk6c2FsZTpzZXR0aW5nczpyZWFkIiwiYWxsZWdybzphcGk6cGF5bWVudHM6cmVhZCJdLCJhbGxlZ3JvX2FwaSI6dHJ1ZSwiZXhwIjoxNTkxMDY5MzY2LCJqdGkiOiI1MWZiNmZjYy1mOWUwLTQyNDAtYjMzMy0wNTc4MDAwNzI2OTUiLCJjbGllbnRfaWQiOiI0ODQ4MDM1ZmYxYWQ0YmYxYjNmMDE1ODcxYWIyNjU1YiJ9.nVlQ4diGxv0mRi-NXn7FpM0NvtnzFHKXLCmc71F-AvE2fxfAiSFJ1NNdGNPscs1GQlt6KQzg1pAypMcyjZk0GxVWC3j-EyOWT304aGgtiiABC4q8KrYUwMZZozZR-00ndrPcvb_uX2dF7Bk-FTi44dQCUgCO-o-R5LvVklzSLehPSACyT1IAg8CDcb9y4H_SQ8nDCNmQwLgBlAcB0oDNR3aEOAxgqqi4zRHGNJ2WTjdFb0s4L7rZ-t9JEDEHO6FQLHWN0-l2rpiaS0CrIL-6vocFHOORZAAjZYLQmYcpkp_4Ats9VA5tcbqOBIgOyUd8TAu-doN5Kub7PEtSh9rsig',
			// "Access-Control-Allow-Headers": "Origin, Content-Type, Accept, Authorization",
			"Access-Control-Allow-Origin": "*",
			"Access-Control-Allow-Methods": 'GET',
			"Access-Control-Allow-Headers": 'Access-Control-Allow-Origin, Access-Control-Allow-Methods, Accept, Authorization'
		}
	});

	function loadCategories(){
		return $.ajax({
			url: 'https://api.allegro.pl.allegrosandbox.pl/sale/categories'
		});
	}


}(window.jQuery));