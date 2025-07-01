<script> 
var rows = <?php echo json_encode(array_values($geoData)); ?>;
let arrays = [];
arrays.push(['Country','Users','Sessions']);
rows.forEach(function(rowArray) {
	arrays.push([rowArray[0],parseInt(rowArray[1]),parseInt(rowArray[2])]);
});
</script>
<html>
  <head>
    <script type="text/javascript" src="https://www.gstatic.com/charts/loader.js"></script>
    <script type="text/javascript">
      google.charts.load('current', {
        'packages':['geochart'],
        // Note: you will need to get a mapsApiKey for your project.
        // See: https://developers.google.com/chart/interactive/docs/basic_load_libs#load-settings
        'mapsApiKey': 'AIzaSyDJhAw0Sph8-0jm5PzVa525ZLH9aFCGb1Y'
      });
      google.charts.setOnLoadCallback(drawRegionsMap);

      function drawRegionsMap() {
        // var data = google.visualization.arrayToDataTable([
          // ['Country', 'Popularity'],
          // ['Germany', 200],
          // ['United States', 300],
          // ['Brazil', 400],
          // ['Canada', 500],
          // ['France', 600],
          // ['RU', 700]
        // ]);  
		var data = google.visualization.arrayToDataTable(arrays);

        var options = {};

        var chart = new google.visualization.GeoChart(document.getElementById('regions_div'));

        chart.draw(data, options);
      }
    </script>
  </head>
  <body>
    <div id="regions_div" style="width: 900px; height: 500px;"></div>
  </body>
</html>
