<?php

include_once "lib/db.php";
include_once "page.php";
include_once "formatter.php";

page_header(PAGE::STATS, true);

db::connect();

?>

<div id="container"></div>
<div id="pie"></div>
<script type="text/javascript">

Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
	return {
		radialGradient: { 
			cx: 0.5, cy: 0.3, r: 0.7 
		},
		stops: [
			[0, color],
			[1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		]
	};
});


function APD(x,y) {
	$("#container").highcharts({
		legend: {
			enabled: false
		},
		xAxis: {
			categories: y
		},
		series: [{
			data: x
		}],
		chart: {
			type: 'area'
		},
		title: {
			text: 'Attacks Per Day'
		},
		yAxis: {
			title: {
				text: 'Attacks'
			},
		},
		tooltip: {
			pointFormat: '{point.y}'
		},
		plotOptions: {
			area: {
				fillOpacity: 0.5
			}
		},
	});
}
function BySig(data_) {
	var chart = $('#pie').highcharts({
		chart: {
			plotBackgroundColor: null,
			plotBorderWidth: null,
			plotShadow: false
		},
		title: {
			text: 'Attack Signatures'
		},
		tooltip: {
			pointFormat: '{series.name}: <b>{point.percentage}%</b>',
			percentageDecimals: 1
		},
		plotOptions: {
			pie: {
				allowPointSelect: true,
				cursor: 'pointer',
				showInLegend: true,
				size:'100%',
				dataLabels: {
					enabled: false
				}
			}
		},
		series: [{
			type: 'pie',
			name: 'By Signature',
			data: data_
		}],
		legend: {
			layout: 'vertical',
			align: 'right',
			verticalAlign: 'middle'
		}
	});
	chart.height('100%');
}


$(function () {
	APD([],[]);
	var apd_request = new XMLHttpRequest();
	apd_request.open('GET', '/data_sources/stats_daily.php', true);
	apd_request.onreadystatechange = function(e) {
		if(apd_request.readyState === 4) {
			var res = JSON.parse(apd_request.responseText);
			APD(res[1], res[0]);
		}
	};
	apd_request.send();

	BySig([]);
	var bs_request = new XMLHttpRequest();
	bs_request.open('GET', '/data_sources/stats_by_sig.php', true);
	bs_request.onreadystatechange = function(e) {
		if(bs_request.readyState === 4) {
			var res = JSON.parse(bs_request.responseText);
			window.res = res;
			var sum = 0;
			for(var i = 0; i < res.length; i++) {
				sum += res[i][1]
				console.log(res[i][1]);
			}
			for(var i = 0; i < res.length; i++) {
				res[i][1] = res[i][1]/sum*100;
			}
			res.sort(function(a,b) {
					if(a[1] > b[1])
						return -1;
					if(a[1] < b[1])
						return 1;
					return 0;
			});
			BySig(res);
		}
	};
	bs_request.send();

});
</script>


<?
page_footer();
?>
