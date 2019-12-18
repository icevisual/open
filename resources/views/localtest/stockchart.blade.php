<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
		<title>Highstock Example</title>

		<script type="text/javascript" src="/js/jquery-1.9.1.min.js"></script>
		<style type="text/css">
${demo.css}
		</style>
		<script type="text/javascript">
$(function () {
    var seriesOptions = [],
        seriesCounter = 0,
        names = {!!json_encode($data['names'])!!};

    /**
     * Create the chart when all data is loaded
     * @returns {undefined}
     */
    function createChart() {

        Highcharts.stockChart('container', {

            rangeSelector: {
                selected: 4
            },

//             yAxis: {
//                 labels: {
//                     formatter: function () {
//                         return (this.value > 0 ? ' + ' : '') + this.value + '%';
//                     }
//                 },
//                 plotLines: [{
//                     value: 0,
//                     width: 2,
//                     color: 'silver'
//                 }]
//             },

//             plotOptions: {
//                 series: {
//                     compare: 'percent',
//                     showInNavigator: true
//                 }
//             },

            tooltip: {
//                 pointFormat: '<span style="color:{series.color}">{series.name}</span>: <b>{point.y}</b> ({point.change}%)<br/>',
                valueDecimals: 2,
                split: true
            },

            series: seriesOptions
        });
    }

    $.each(names, function (i, name) {

        $.getJSON('/topicLog/json/' + name.toLowerCase() + '.json',    function (data) {

            seriesOptions[i] = {
                name: name,
                data: data
            };

            // As we're loading the data asynchronously, we don't know what order it will arrive. So
            // we keep a counter and create the chart when all the data is loaded.
            seriesCounter += 1;

            if (seriesCounter === names.length) {
                createChart();
            }
        });
    });
});
		</script>
	</head>
	<body>
<script src="/js/highstock.js"></script>
<!-- <script src="https://code.highcharts.com/stock/modules/exporting.js"></script> -->


<div id="container" style="height: 400px; min-width: 310px"></div>
	</body>
</html>
