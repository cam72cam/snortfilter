<?php

include_once "page.php";
page_header(PAGE::GLOBE, true);


?>
	<div id="data_data" style="float:left; height:80%; position: absolute; margin-top:50px; margin-left:20px;"></div>
	<div id="slider" style="float:left; height:80%; position: absolute; width:10; margin-top:100px; margin-left:20px"></div>
	<div id="data" style="float:left; height:80%; position: absolute; width:10; margin-top:100px"></div>
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
			var globe = new DAT.Globe(container)
			var i, tweens = [];

			var xhr;
			TWEEN.start();
			function foo(i) {
				$("#data_data").html(data[i][0]);
				globe.removePoints();
				globe.addData(data[i][1], {format: 'magnitude', name: data[i][0], animated: true});
				globe.createPoints();
				new TWEEN.Tween(globe).to({time: 0},500).easing(TWEEN.Easing.Cubic.EaseOut).start();
				globe.animate();
			}

			xhr = new XMLHttpRequest();
			xhr.open('GET', '/globe_data.json', true);
			xhr.onreadystatechange = function(e) {
				if (xhr.readyState === 4) {
					if (xhr.status === 200) {
						var res = JSON.parse(xhr.responseText);
						var max = 5;
						for(var i = 0; i < res.length; i++) {
							var j = 1;
							for(var index = 2; index <= res[i][j].length; index+=3) {
								res[i][j][index]  = Math.log(res[i][j][index])/ max;
							}
						}
						var data = res;
						window.data = data;
						foo(13);
					}
				}
			};
			xhr.send(null);
		}

	$(function() {
	    $( "#slider" ).slider({
	      value:13,
	      min: 0,
	      max: 13,
		orientation: "vertical",
	      slide: function( event, ui ) {
		foo(ui.value);
	      }
	    });
	  });	

	</script>

<?php
page_footer();
?>
