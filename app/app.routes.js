(function(){

	angular.module('RandomFailApp')

	/* App routes configuration */
	.config(function($routeProvider){

		$routeProvider
			
			/* Route for home and its configurations (if necessary) */
			.when('/', {
				templateUrl:'app/components/index.html',
				reloadOnSearch:false
			})

			/* Redirects to home in en case no url is found */
			.otherwise({
				redirectTo:'/'
			});
	});
})();