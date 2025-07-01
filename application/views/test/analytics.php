<!DOCTYPE html>
<html>
<head>
  <title>Embed API Demo</title>
</head>
<body>

<!-- Step 1: Create the containing elements. -->

<section id="auth-button"></section>
<section id="view-selector"></section>
<section id="timeline"></section>

<!-- Step 2: Load the library. -->

<script>
(function(w,d,s,g,js,fjs){
  g=w.gapi||(w.gapi={});g.analytics={q:[],ready:function(cb){this.q.push(cb)}};
  js=d.createElement(s);fjs=d.getElementsByTagName(s)[0];
  js.src='https://apis.google.com/js/platform.js';
  fjs.parentNode.insertBefore(js,fjs);js.onload=function(){g.load('analytics')};
}(window,document,'script'));
</script>
<script>
gapi.analytics.ready(function() {

  // Step 3: Authorize the user.

  var CLIENT_ID = '567299795370-gioq89b42gmkrb10i50dso6jsmdb9lj3.apps.googleusercontent.com';

  gapi.analytics.auth.authorize({
    container: 'auth-button',
    clientid: CLIENT_ID,
  });

  // Step 4: Create the view selector.
  var viewSelector = new gapi.analytics.ViewSelector({
    container: 'view-selector'
  });
console.log(gapi.analytics.report.Data);
  // Step 5: Create the timeline chart.
  var timeline = new gapi.analytics.googleCharts.DataChart({
    reportType: 'ga',
    query: {
      // 'dimensions': 'ga:date',
      // 'metrics': 'ga:sessions,ga:bounces,ga:users',
	  // 'dimensions': 'ga:userType',
      // 'metrics': 'ga:newUsers,ga:users,ga:percentNewSessions',
	  'dimensions': 'ga:country',
      'metrics': 'ga:users',
      'start-date': '30daysAgo',
      'end-date': 'yesterday',
    },
    chart: {
      // type: 'LINE',
      type: 'GEO',
      container: 'timeline'
    }
  });

  // Step 6: Hook up the components to work together.

  gapi.analytics.auth.on('success', function(response) {
    viewSelector.execute();
  });

  viewSelector.on('change', function(ids) {
    var newIds = {
      query: {
        ids: ids
      }
    }
    timeline.set(newIds).execute();
  });
  
});
</script>
</body>
</html>