<?php

include_once "page.php";
page_header(PAGE::HOME, true);


?>
	<div id="container"></div>
	<script type="text/javascript" src="/globe/third-party/Three/ThreeWebGL.js"></script>
	<script type="text/javascript" src="/globe/third-party/Three/ThreeExtras.js"></script>
	<script type="text/javascript" src="/globe/third-party/Three/RequestAnimationFrame.js"></script>
	<script type="text/javascript" src="/globe/third-party/Three/Detector.js"></script>
	<script type="text/javascript" src="/globe/third-party/Tween.js"></script>
	<script type="text/javascript" src="/globe/globe.js"></script>
	<script type="text/javascript">
		if(!Detector.webgl){
			Detector.addGetWebGLMessage();
		} else {
//			var years = ['1990','1995','2000'];
			var container = document.getElementById('container');
			var globe = new DAT.Globe(container);
//			console.log(globe);
			var i, tweens = [];

			var settime = function(globe, t) {
				return function() {
					new TWEEN.Tween(globe).to({time: 0},500).easing(TWEEN.Easing.Cubic.EaseOut).start();
/*					var y = document.getElementById('year'+years[t]);
					if (y.getAttribute('class') === 'year active') {
						return;
					}*/
/*					var yy = document.getElementsByClassName('year');
					for(i=0; i<yy.length; i++) {
						yy[i].setAttribute('class','year');
					}
					y.setAttribute('class', 'year active');*/
				};
			};

/*			for(var i = 0; i<years.length; i++) {
				var y = document.getElementById('year'+years[i]);
				y.addEventListener('mouseover', settime(globe,i), false);
			}*/

			var xhr;
			TWEEN.start();


			xhr = new XMLHttpRequest();
			xhr.open('GET', '/geo_data/4-21-13.json', true);
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState === 4) {
					if (xhr.status === 200) {
						var res = JSON.parse(xhr.responseText);
						var max = 5;

						for(var index = 2; index <= res.length; index+=3) {
							res[index]  = Math.log(res[index])/ max;
							console.log(res[index]);
						}
/*


						for(var index = 2; index <= res.length; index+=3) {
							max = Math.max(res[index], max);
						}
						max /= 1;
						for(var index = 2; index <= res.length; index+=3) {
							res[index] = res[index]/max;
						}*/
						var data = [['0', res]];
						window.data = data;
						console.log(data);
						for (i=0;i<data.length;i++) {
							globe.addData(data[i][1], {format: 'magnitude', name: data[i][0], animated: true});
						}
						globe.createPoints();
						settime(globe,0)();
						globe.animate();
					}
				}
			};
			xhr.send(null);
		}

	</script>

<?php
page_footer();
?>
