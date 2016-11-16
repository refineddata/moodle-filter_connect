(function(jwplayer){
	
	
	/*
	
	To use, download the latest JWPLayer from http://www.longtailvideo.com/
	
	Example Usage:
	
	<!-- START OF THE PLAYER EMBEDDING TO COPY-PASTE -->
	<div id="mediaplayer">JW Player goes here</div>
	
	
	<script type="text/javascript" src="jwplayer.js">//</script>
	<script type="text/javascript">
		jwplayer("mediaplayer").setup({
			flashplayer: "player.swf",
			file: "video.mp4",
			image: "preview.jpg",
			plugins: {
				'./moodle_monitor.js': {
					student_id: '123',
					course_id: 'course_86',
					activity_id: 'activity_9876',
					video_id: 'video_5234'
				}
			}
		});
	</script>
	<!-- END OF THE PLAYER EMBEDDING -->
	
	*/
	
	var moodleMonitorPlugin = function(player, config, div) {
		
		var MONITOR_URL = '/mod/rtvideo/ajax/grade_movie.php';
		
		var sentPoints = [];
		var requestData = {};
		
		var xmlhttp;
		
		
		for(var i in config) {
			requestData[i] = config[i];
		}
		
		
		player.onReady(function (event) {
			
			//IE did not implement Array.indexOf!
			if(!Array.indexOf){
				Array.prototype.indexOf = function(obj){
					for(var i=0; i<this.length; i++){
						if(this[i]==obj){
							return i;
						}
					}
					return -1;
				}
			}
			
			
			try
			{
				// Chrome, Opera 8.0+, Firefox, Safari
				xmlhttp = new XMLHttpRequest();
			}
			catch (e)
			{
				// Internet Explorer Browsers
				try
				{
					xmlhttp = new ActiveXObject('Msxml2.XMLHTTP');
				}
				catch (e)
				{
					try
					{
						xmlhttp = new ActiveXObject('Microsoft.XMLHTTP');
					}
					catch (e)
					{
						// Something went wrong
						return false;
					}
				}
			}
			
		});
		
		
		player.onTime(function (event) {
			
			requestData.position = Math.round(event.position);
			requestData.duration = Math.round(event.duration);
			
			var percent = Math.round((event.position / event.duration) * 100) / 100;
			
			requestData.pos = 0;
			
			if(percent <= 0.05) {
				requestData.pos = 1;
				
			} else if(percent > 0.23 && percent <= 0.27) {
				requestData.pos = 2;
				
			} else if(percent > 0.44 && percent <= 0.52) {
				requestData.pos = 3;
				
			} else if(percent > 0.73 && percent <= 0.77) {
				requestData.pos = 4;
				
			} else if(percent > 0.94) {
				requestData.pos = 5;
				
			}
			
			if(requestData.pos != 0 && sentPoints.indexOf(requestData.pos) == -1) {
				
				sentPoints.push(requestData.pos);
				
				var postData = '';
				
				for (var i in requestData) {
					postData += i + '=' + escape(requestData[i]) + '&';
				}
				
				xmlhttp.open('POST', MONITOR_URL, true);
				xmlhttp.setRequestHeader('Content-type', 'application/x-www-form-urlencoded');
				xmlhttp.send(postData);
				
			}
			
		});
		
		
		this.resize = function(width, height) {};
	};

	jwplayer().registerPlugin('moodleMonitor', moodleMonitorPlugin);
	
})(jwplayer);