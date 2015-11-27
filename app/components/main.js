// VAI VAI VAI
angular.module('RandomFailApp')

.factory('VideoService', ['$q', '$http', function($q, $http){
	return {
		getVideo: function() {
			var deferrer = $q.defer();
			var url = angular.element('base').attr('href') + '/api/?page=get_video';
			$http.get(url).success(function(data){
				deferrer.resolve(data);
			});
			return deferrer.promise;
		}
	}
}])

.controller('MainCtrl', ['$scope', '$sce', '$http', '$location', 'VideoService', function($scope, $sce, $http, $location, VideoService){
	
	$scope.videoIndex = 1;
	$scope.view = {video:{}, list:[]};
	$scope.loading = false;

	$scope.getVideoUrl = function(video){
		var videoUrl = 'http://www.youtube.com/embed/' + video.youtube_id;
		return $sce.trustAsResourceUrl(videoUrl);
	};

	$scope.upvote = function(videoIndex){
		$scope.loading = true;
		$http.get(angular.element('base').attr('href') + '/api/?page=like&id=' + $scope.view.video.id).success(function(data){
			$scope.loading = false;
			$scope.view.video.votes++;
		});
	}

	$scope.downvote = function(videoIndex){
		$scope.loading = true;
		$http.get(angular.element('base').attr('href') + '/api/?page=deslike&id=' + $scope.view.video.id).success(function(data){
			$scope.loading = false;
			$scope.view.video.votes--;
		});
	}

	$scope.randomize = function(){
		VideoService.getVideo().then(function(video){
			$scope.view.video = video;
			$scope.view.list.push(video);
			$scope.videoIndex++;
			$location.search({video:$scope.view.video.id});
		});
	}

	$scope.randomize();

	$scope.$watch('videoIndex', function (newIndex) {
		
		if ((newIndex+1) > $scope.view.list.length ) {
			$scope.videoIndex = 0;
		} else if (newIndex < 0 ) {
			$scope.videoIndex = ($scope.view.list.length-1);
		} 
		
		if ($scope.view.list[$scope.videoIndex] != undefined) {

			$scope.view.video = $scope.view.list[$scope.videoIndex];
			$location.search({video:$scope.view.video.id});
		}

	}, true);

}]);