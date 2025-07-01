$(function () {

  'use strict';



  // Get context with jQuery - using jQuery's .get() method.
  if($('#pieChart').length){
	  var pieChartCanvas = $('#pieChart').get(0).getContext('2d');
	  var pieChart       = new Chart(pieChartCanvas);
	  var PieData        = [
		{
		  value    : 700,
		  color    : '#f56954',
		  highlight: '#f56954',
		  label    : 'Chrome'
		},
		{
		  value    : 500,
		  color    : '#00a65a',
		  highlight: '#00a65a',
		  label    : 'IE'
		},
		{
		  value    : 400,
		  color    : '#f39c12',
		  highlight: '#f39c12',
		  label    : 'FireFox'
		},
		{
		  value    : 600,
		  color    : '#00c0ef',
		  highlight: '#00c0ef',
		  label    : 'Safari'
		},
		{
		  value    : 300,
		  color    : '#3c8dbc',
		  highlight: '#3c8dbc',
		  label    : 'Opera'
		},
		{
		  value    : 100,
		  color    : '#d2d6de',
		  highlight: '#d2d6de',
		  label    : 'Navigator'
		}
	  ];
	  var pieOptions     = {
		// Boolean - Whether we should show a stroke on each segment
		segmentShowStroke    : true,
		// String - The colour of each segment stroke
		segmentStrokeColor   : '#fff',
		// Number - The width of each segment stroke
		segmentStrokeWidth   : 1,
		// Number - The percentage of the chart that we cut out of the middle
		percentageInnerCutout: 50, // This is 0 for Pie charts
		// Number - Amount of animation steps
		animationSteps       : 100,
		// String - Animation easing effect
		animationEasing      : 'easeOutBounce',
		// Boolean - Whether we animate the rotation of the Doughnut
		animateRotate        : true,
		// Boolean - Whether we animate scaling the Doughnut from the centre
		animateScale         : false,
		// Boolean - whether to make the chart responsive to window resizing
		responsive           : true,
		// Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
		maintainAspectRatio  : false,
		// String - A legend template
		legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
		// String - A tooltip template
		tooltipTemplate      : '<%=value %> <%=label%> users'
	  };
	  // Create pie or douhnut chart
	  // You can switch between pie and douhnut using the method below.
	  pieChart.Doughnut(PieData, pieOptions);
  }

  $('.sparkbar').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type    : 'bar',
      height  : $this.data('height') ? $this.data('height') : '30',
      barColor: $this.data('color')
    });
  });

  // -----------------
  // - SPARKLINE PIE -
  // -----------------
  $('.sparkpie').each(function () {
    var $this = $(this);
    $this.sparkline('html', {
      type       : 'pie',
      height     : $this.data('height') ? $this.data('height') : '90',
      sliceColors: $this.data('color')
    });
  });

  // ------------------
  // - SPARKLINE LINE -
  // ------------------
    $('.sparkline').each(function () {
      var $this = $(this);
      $this.sparkline('html', {
        type     : 'line',
        height   : $this.data('height') ? $this.data('height') : '90',
        width    : '100%',
        lineColor: $this.data('linecolor'),
        fillColor: $this.data('fillcolor'),
        spotColor: $this.data('spotcolor')
      });
    });
    
    var  start = moment().subtract(6, 'days');
    var  end = moment();

    if ($(".datePicker_new").length) {
        $(".datePicker_new").daterangepicker({
            showDropdowns: true,
            autoUpdateInput: true,
            startDate: start,
            endDate: end,
            locale: {
                format: "YYYY-MM-DD",
                cancelLabel: 'Clear'
            },
            ranges: {
              'Today'       : [moment(), moment()],
              'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
              'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
              'Last 30 Days': [moment().subtract(29, 'days'), moment()],
              'This Month'  : [moment().startOf('month'), moment().endOf('month')],
              'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
              'This Year'   : [moment().startOf('year'), moment()],
              'Last Year'   : [moment().subtract(1, 'years').startOf('year'), moment().subtract(1, 'years').endOf('year')]
           }
        },cb1);
        cb1(start, end);
     
        function cb1(start, end) {
          $('.datePicker_new span').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
          getTicketData(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
          getCountByTicketType(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
          getTicketList(start.format('YYYY-MM-DD'),end.format('YYYY-MM-DD'));
        }
    }

    $('.datePicker_new').on('apply.daterangepicker', function(ev, picker) {
      // $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
    });
  
    $(document).on('click','.clear_filter',function(){
      $('.datePicker_new').val('');
      getTicketData();
      getCountByTicketType();
      getTicketList();
    });

  $('.datePicker_new').on('cancel.daterangepicker', function(ev, picker) {
        $(this).val('');
         getTicketData();
        getCountByTicketType();
        getTicketList();
    });

  

  if ($(".datePicker_customer").length) {
      $(".datePicker_customer").daterangepicker({
          // singleDatePicker: true,
          autoUpdateInput : true,
          showDropdowns   : true,
          // minYear      : 1920,
          // maxYear      : parseInt(moment().format('YYYY'),10),
          startDate: start,
          endDate: end,
          locale: {
              format      : "YYYY-MM-DD",
              cancelLabel : 'Clear'
          },
          ranges: {
            'Today'       : [moment(), moment()],
            'Yesterday'   : [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
            'Last 7 Days' : [moment().subtract(6, 'days'), moment()],
            'Last 30 Days': [moment().subtract(29, 'days'), moment()],
            'This Month'  : [moment().startOf('month'), moment().endOf('month')],
            'Last Month'  : [moment().subtract(1, 'month').startOf('month'), moment().subtract(1,'month').endOf('month')],
            'This Year'   : [moment().startOf('year'), moment()],
            'Last Year'   : [moment().subtract(1, 'years').startOf('year'), moment().subtract(1, 'years').endOf('year')]
         }
      },cb );
      cb(start, end);
     
      function cb(start, end) {
        $('.datePicker_customer span').val(start.format('YYYY-MM-DD') + ' - ' + end.format('YYYY-MM-DD'));
        getGraphDataByYear(start.format('YYYY-MM-DD') , end.format('YYYY-MM-DD'));
      }
  }
  $('.datePicker_customer').on('apply.daterangepicker', function(ev, picker) {
      // $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
      // getGraphDataByYear(picker.startDate.format('YYYY-MM-DD'),picker.endDate.format('YYYY-MM-DD'));
  });

  $(document).on('click','.clear_filter_customer',function(){
      $('.datePicker_customer').val('');
      getGraphDataByYear();
  });


});
/*function getSupportData(){

  $.ajax({
    url: base_url+'admin_dashboard/getSupportData',
    method: "post",
    success: function (resp) {
      //console.log(resp);
      data=resp.data;
      var html="";
      for(var i=0;i<data.length;i++){
      html+=`<div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">${data[i].name}</h3>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="chart-responsive">
                    <canvas id="pieChartSupport_${i}" height="150"></canvas>
                  </div>
                </div>
                <div class="col-md-4">
                  <ul class="chart-legend clearfix">
                    <li><i class="fa fa-circle-o text-yellow"></i> Open</li>
                    <li><i class="fa fa-circle-o text-green"></i> Close</li>
                    <li><i class="fa fa-circle-o text-red"></i> Replied</li>
                  </ul>
                </div>
              </div>
            </div>
          </div>`;
        }
          $("#support_chart").html(html);
    for(var i=0;i<data.length;i++){
      var pieChartCanvas = $('#pieChartSupport_'+i).get(0).getContext('2d');
      var pieChart       = new Chart(pieChartCanvas);
      var PieData        = [
        {
          value    : parseInt(data[i].open),
          color    : '#f39c12',
          highlight: '#f39c12',
          label    : 'Open'
        },
        {
          value    : parseInt(data[i].close),
          color    : '#00a65a',
          highlight: '#00a65a',
          label    : 'Close'
        },
        {
          value    : parseInt(data[i].replied),
          color    : '#f56954',
          highlight: '#f56954',
          label    : 'Replied'
        }
      ];
      var pieOptions     = {
        segmentShowStroke    : true,
        segmentStrokeColor   : '#fff',
        segmentStrokeWidth   : 1,
        percentageInnerCutout: 50, // This is 0 for Pie charts
        animationSteps       : 100,
        animationEasing      : 'easeOutBounce',
        animateRotate        : true,
        animateScale         : false,
        responsive           : true,
        maintainAspectRatio  : false,
        legendTemplate       : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<segments.length; i++){%><li><span style=\'background-color:<%=segments[i].fillColor%>\'></span><%if(segments[i].label){%><%=segments[i].label%><%}%></li><%}%></ul>',
        tooltipTemplate      : '<%=value %> <%=label%> Ticket'
      };

      pieChart.Doughnut(PieData, pieOptions);
    }
    },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }

  });
}*/

function getUserData(){

  $.ajax({
    url: base_url+'admin_dashboard/getUserData',
    method: "post",
    success: function (resp) {
      
      data=resp.data;
      $("#totalUser").html(data.totalUser);
      $("#activeUser").html(data.activeUser);
      $("#inactiveUser").html(data.inactiveUser);
      $("#officialUser").html(data.officialUser);
      $("#incompleteUser").html(data.incompleteUser);
      $("#deletedUser").html(data.deletedUser);
      $("#gamepassCount").html(data.redeemCouponCode+"/"+data.totalCouponCode);
    },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }

  });
}
var userChart = '';
var userChartCount = 0;
function getGraphDataByYear(startDate='',endDate=''){
  const months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
  
  if(userChartCount != 0){
    userChart.destroy()
  }
  

  if(startDate!==''){
      v= startDate.split("-");
     vf= endDate.split("-");

     $('.start_date_customer').text(datShortFormat(v));
     $('.end_date_customer').text(datShortFormat(vf));
  }

  $.ajax({
    url     : base_url+'admin_dashboard/getGraphDataByYear',
    method  : "post",
    data    : { startDate:startDate,
                endDate:endDate 
              },
    success: function (resp) {
            
            data=resp.data;
            
            let labels=[];
            let value=[];
           
            for(var i=0;i<data.length;i++){
              let rgdt =  data[i].regdate ;
              if(rgdt > 0 && rgdt < 13){
                labels.push( moment(rgdt).format("MMM") );  
              }else{
                labels.push( moment(rgdt).format("MMM Do") );
              }
              
              value.push(parseInt(data[i].usercount));
            }

            let lastChar = labels.slice(-1);
            if (lastChar == ',') { // check last character is string
              labels = labels.slice(0, -1); // trim last character
            }
           
            let salesChartCanvas = $('#salesChartDashboard').get(0).getContext('2d');
           
            let salesChart       = new Chart(salesChartCanvas);
            
            let salesChartData = {
            labels  : labels,
            datasets: [

                {
                  label               : 'Digital Goods',
                  fillColor           : 'rgba(60,141,188,0.9)',
                  strokeColor         : 'rgba(60,141,188,0.8)',
                  pointColor          : '#3b8bba',
                  pointStrokeColor    : 'rgba(60,141,188,1)',
                  pointHighlightFill  : '#fff',
                  pointHighlightStroke: 'rgba(60,141,188,1)',
                  data                : value
                }
              ]
            };

          let salesChartOptions = {
            // Boolean - If we should show the scale at all
            showScale               : true,
            // Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : false,
            // String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            // Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            // Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            // Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            // Boolean - Whether the line is curved between points
            bezierCurve             : true,
            // Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            // Boolean - Whether to show a dot for each point
            pointDot                : false,
            // Number - Radius of each point dot in pixels
            pointDotRadius          : 4,
            // Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 1,
            // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            // Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            // Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            // Boolean - Whether to fill the dataset with a color
            datasetFill             : true,
            // String - A legend template
            legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            // Boolean - whether to make the chart responsive to window resizing
            responsive              : true
          };

        // Create the line chart
        userChartCount++;
        userChart =  salesChart.Bar(salesChartData, salesChartOptions);
        
    },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }

  });
}

function datShortFormat(date){
  const months = ["JAN", "FEB", "MAR","APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"];
  let current_datetime = new Date(date)
  let formatted_date = current_datetime.getDate() + "-" + months[current_datetime.getMonth()] + "-" + current_datetime.getFullYear()
  return formatted_date;
}


var supportChart = '';
var supportChartCount = 0;

function getTicketData(startDate='',endDate=''){
  if(supportChartCount != 0){
    supportChart.destroy()
  }
  

  $.ajax({
    url: base_url+'admin_dashboard/getTicketGraphDataByYear',
    method: "post",
    data:{startDate:startDate,endDate:endDate},
    success: function (resp) {
          data=resp.data;
          
          fillColor             = ['rgba(60,141,188,0.9)','rgba(243,156,83,0.9)','rgba(221,75,57,0.9)'];
          strokeColor           = ['rgba(60,141,188,0.8)','rgba(243,156,83,0.8)','rgba(221,75,57,0.8)'];
          pointColor            = ['#3b8bba','#f39c12','#dd4b39','rgba(221,75,57,1)'];
          pointStrokeColor      = ['rgba(243,156,83,1)','rgba(60,141,188,1)'];
          pointHighlightFill    = ['#fff','#fff','#fff'];
          pointHighlightStroke  = ['rgba(60,141,188,1)','rgba(60,141,188,1)','rgba(60,141,188,1)'];
          classname             = ['progress-bar-aqua','progress-bar-yellow','progress-bar-red'];
          

          
          var value=[];
          var htmldata = "";
          for (var i = 0; i < data.length; i++) 
          {

              htmldata+=`<div class="progress-group">
              <span class="progress-text">${data[i].name}</span>
              <div class="progress sm">
                <div class="progress-bar ${classname[i]}" style="width: 100%"></div>
              </div>
            </div>`;


              var countData=[];
              var tc= data[i].count_data;
              var dates = [];
              
              if(resp.isyear == 0){
                v   = startDate.split("-");
                vf  = endDate.split("-");

                $('.start_date').text(datShortFormat(v));
                $('.end_date').text(datShortFormat(vf));
                  // Strip hours minutes seconds etc.
                const theDate = new Date(v); vf = new Date(vf);
                  while (theDate <= vf) {
                  dates = [...dates, moment(theDate.getFullYear()+'-'+(theDate.getMonth()+1)+'-'+ theDate.getDate()).format("MMM Do")]
                  var obj = tc.find(o => parseInt(o.month) === theDate.getDate());

                  if(typeof obj === 'undefined'){
                    countData.push(parseInt(0));
                  }else{
                    countData.push(parseInt(obj.total));
                  }
                  
                  theDate.setDate(theDate.getDate() + 1);
                }

                value.push({
                      label:data[i].name,
                      fillColor           : fillColor[i],
                      strokeColor         : strokeColor[i],
                      pointColor          : pointColor[i],
                      pointStrokeColor    : pointStrokeColor[i],
                      pointHighlightFill  : pointHighlightFill[i],
                      pointHighlightStroke: pointHighlightStroke[i],
                      data :countData 
                  });

                    var salesChartData1 = {
                      labels  : dates,
                      datasets: value
                    };
            }else{
                const d = new Date();
                let month = (d.getMonth())+1;
                
                for(var J=1;J<=month;J++){

                    var obj = tc.find(o => parseInt(o.month) === J);

                    if(typeof obj === 'undefined'){

                      countData.push(parseInt(0));
                    }else{
                      countData.push(parseInt(obj.total));
                    }
                }

                value.push({
                  label:data[i].name,
                  fillColor           : fillColor[i],
                  strokeColor         : strokeColor[i],
                  pointColor          : pointColor[i],
                  pointStrokeColor    : pointStrokeColor[i],
                  pointHighlightFill  : pointHighlightFill[i],
                  pointHighlightStroke: pointHighlightStroke[i],
                  data :countData });

                var salesChartData1 = {
                  labels  : ["January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"],
                  datasets: value
                };
            }
          }
          
          console.log(salesChartData1)

      /*  $('#salesChartDashboardSupport').html('');*/

          var salesChartDashboardSupport = $('#salesChartDashboardSupport').get(0).getContext('2d');
          var salesChart       = new Chart(salesChartDashboardSupport);

          var salesChartOptions1 = {
            // Boolean - If we should show the scale at all
            showScale               : true,
            // Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines      : true,
            // String - Colour of the grid lines
            scaleGridLineColor      : 'rgba(0,0,0,.05)',
            // Number - Width of the grid lines
            scaleGridLineWidth      : 1,
            // Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            // Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines  : true,
            // Boolean - Whether the line is curved between points
            bezierCurve             : true,
            // Number - Tension of the bezier curve between points
            bezierCurveTension      : 0.3,
            // Boolean - Whether to show a dot for each point
            pointDot                : true,
            // Number - Radius of each point dot in pixels
            pointDotRadius          : 4,
            // Number - Pixel width of point dot stroke
            pointDotStrokeWidth     : 1,
            // Number - amount extra to add to the radius to cater for hit detection outside the drawn point
            pointHitDetectionRadius : 20,
            // Boolean - Whether to show a stroke for datasets
            datasetStroke           : true,
            // Number - Pixel width of dataset stroke
            datasetStrokeWidth      : 2,
            // Boolean - Whether to fill the dataset with a color
            datasetFill             : false,
            // String - A legend template
            legendTemplate          : '<ul class=\'<%=name.toLowerCase()%>-legend\'><% for (var i=0; i<datasets.length; i++){%><li><span style=\'background-color:<%=datasets[i].lineColor%>\'></span><%=datasets[i].label%></li><%}%></ul>',
            // Boolean - whether to maintain the starting aspect ratio or not when responsive, if set to false, will take up entire container
            maintainAspectRatio     : true,
            // Boolean - whether to make the chart responsive to window resizing
            responsive              : true
          };

          $('#sdata').html(htmldata);

          supportChartCount++;
          supportChart =   salesChart.Line(salesChartData1, salesChartOptions1);

    },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }

  });
}


function getLatestUserRecord(){
  $.ajax({
    url: base_url+'admin_dashboard/getLatestUserRecord',
    method: "post",
    success: function (resp) {
      //console.log(resp.length);
      var data=resp.data;
      var html="";
        for(var i=0;i<data.length;i++){
       html+=`<li><img src="${data[i].uc_pic}" alt="User Image"><a class="users-list-name" href="${data[i].href}" target="_blank">${data[i].user_name}</a><span class="users-list-date">${(data[i].category_name)?data[i].category_name:''}</span></li>`;
      }

      $('#latestmember').html(html);
     },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}
function getLatestUserRecordEmerging(){
  $.ajax({
    url: base_url+'admin_dashboard/getLatestUserRecordEmerging',
    method: "post",
    success: function (resp) {
      //console.log(resp.length);
      var data=resp.data;
      var html="";
        for(var i=0;i<data.length;i++){
       html+=`<li><img src="${data[i].uc_pic}" alt="User Image"><a class="users-list-name" href="${data[i].href}" target="_blank">${data[i].user_name}</a><span class="users-list-date">${(data[i].category_name)?data[i].category_name:''}</span></li>`;
      }

      $('#latestmemberEmerging').html(html);
     },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}
function getLatestUserRecordBrand(){
  $.ajax({
    url: base_url+'admin_dashboard/latestmemberBrand',
    method: "post",
    success: function (resp) {
      //console.log(resp.length);
      var data=resp.data;
      var html="";
        for(var i=0;i<data.length;i++){
       html+=`<li><img src="${data[i].uc_pic}" alt="User Image"><a class="users-list-name" href="${data[i].href}" target="_blank">${data[i].user_name}</a><span class="users-list-date">${(data[i].category_name)?data[i].category_name:''}</span></li>`;
      }

      $('#latestmemberBrand').html(html);
     },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}
const countries = {
  AF: "Afghanistan",
  AX: "Aland Islands",
  AL: "Albania",
  DZ: "Algeria",
  AS: "American Samoa",
  AD: "Andorra",
  AO: "Angola",
  AI: "Anguilla",
  AQ: "Antarctica",
  AG: "Antigua And Barbuda",
  AR: "Argentina",
  AM: "Armenia",
  AW: "Aruba",
  AU: "Australia",
  AT: "Austria",
  AZ: "Azerbaijan",
  BS: "Bahamas",
  BH: "Bahrain",
  BD: "Bangladesh",
  BB: "Barbados",
  BY: "Belarus",
  BE: "Belgium",
  BZ: "Belize",
  BJ: "Benin",
  BM: "Bermuda",
  BT: "Bhutan",
  BO: "Bolivia",
  BA: "Bosnia And Herzegovina",
  BW: "Botswana",
  BV: "Bouvet Island",
  BR: "Brazil",
  IO: "British Indian Ocean Territory",
  BN: "Brunei Darussalam",
  BG: "Bulgaria",
  BF: "Burkina Faso",
  BI: "Burundi",
  KH: "Cambodia",
  CM: "Cameroon",
  CA: "Canada",
  CV: "Cape Verde",
  KY: "Cayman Islands",
  CF: "Central African Republic",
  TD: "Chad",
  CL: "Chile",
  CN: "China",
  CX: "Christmas Island",
  CC: "Cocos (Keeling) Islands",
  CO: "Colombia",
  KM: "Comoros",
  CG: "Congo",
  CD: "Congo, Democratic Republic",
  CK: "Cook Islands",
  CR: "Costa Rica",
  CI: 'Cote D"Ivoire',
  HR: "Croatia",
  CU: "Cuba",
  CY: "Cyprus",
  CZ: "Czech Republic",
  DK: "Denmark",
  DJ: "Djibouti",
  DM: "Dominica",
  DO: "Dominican Republic",
  EC: "Ecuador",
  EG: "Egypt",
  SV: "El Salvador",
  GQ: "Equatorial Guinea",
  ER: "Eritrea",
  EE: "Estonia",
  ET: "Ethiopia",
  FK: "Falkland Islands (Malvinas)",
  FO: "Faroe Islands",
  FJ: "Fiji",
  FI: "Finland",
  FR: "France",
  GF: "French Guiana",
  PF: "French Polynesia",
  TF: "French Southern Territories",
  GA: "Gabon",
  GM: "Gambia",
  GE: "Georgia",
  DE: "Germany",
  GH: "Ghana",
  GI: "Gibraltar",
  GR: "Greece",
  GL: "Greenland",
  GD: "Grenada",
  GP: "Guadeloupe",
  GU: "Guam",
  GT: "Guatemala",
  GG: "Guernsey",
  GN: "Guinea",
  GW: "Guinea-Bissau",
  GY: "Guyana",
  HT: "Haiti",
  HM: "Heard Island & Mcdonald Islands",
  VA: "Holy See (Vatican City State)",
  HN: "Honduras",
  HK: "Hong Kong",
  HU: "Hungary",
  IS: "Iceland",
  IN: "India",
  ID: "Indonesia",
  IR: "Iran, Islamic Republic Of",
  IQ: "Iraq",
  IE: "Ireland",
  IM: "Isle Of Man",
  IL: "Israel",
  IT: "Italy",
  JM: "Jamaica",
  JP: "Japan",
  JE: "Jersey",
  JO: "Jordan",
  KZ: "Kazakhstan",
  KE: "Kenya",
  KI: "Kiribati",
  KR: "Korea",
  KP: "North Korea",
  KW: "Kuwait",
  KG: "Kyrgyzstan",
  LA: 'Lao People"s Democratic Republic',
  LV: "Latvia",
  LB: "Lebanon",
  LS: "Lesotho",
  LR: "Liberia",
  LY: "Libyan Arab Jamahiriya",
  LI: "Liechtenstein",
  LT: "Lithuania",
  LU: "Luxembourg",
  MO: "Macao",
  MK: "Macedonia",
  MG: "Madagascar",
  MW: "Malawi",
  MY: "Malaysia",
  MV: "Maldives",
  ML: "Mali",
  MT: "Malta",
  MH: "Marshall Islands",
  MQ: "Martinique",
  MR: "Mauritania",
  MU: "Mauritius",
  YT: "Mayotte",
  MX: "Mexico",
  FM: "Micronesia, Federated States Of",
  MD: "Moldova",
  MC: "Monaco",
  MN: "Mongolia",
  ME: "Montenegro",
  MS: "Montserrat",
  MA: "Morocco",
  MZ: "Mozambique",
  MM: "Myanmar",
  NA: "Namibia",
  NR: "Nauru",
  NP: "Nepal",
  NL: "Netherlands",
  AN: "Netherlands Antilles",
  NC: "New Caledonia",
  NZ: "New Zealand",
  NI: "Nicaragua",
  NE: "Niger",
  NG: "Nigeria",
  NU: "Niue",
  NF: "Norfolk Island",
  MP: "Northern Mariana Islands",
  NO: "Norway",
  OM: "Oman",
  PK: "Pakistan",
  PW: "Palau",
  PS: "Palestinian Territory, Occupied",
  PA: "Panama",
  PG: "Papua New Guinea",
  PY: "Paraguay",
  PE: "Peru",
  PH: "Philippines",
  PN: "Pitcairn",
  PL: "Poland",
  PT: "Portugal",
  PR: "Puerto Rico",
  QA: "Qatar",
  RE: "Reunion",
  RO: "Romania",
  RU: "Russian Federation",
  RW: "Rwanda",
  BL: "Saint Barthelemy",
  SH: "Saint Helena",
  KN: "Saint Kitts And Nevis",
  LC: "Saint Lucia",
  MF: "Saint Martin",
  PM: "Saint Pierre And Miquelon",
  VC: "Saint Vincent And Grenadines",
  WS: "Samoa",
  SM: "San Marino",
  ST: "Sao Tome And Principe",
  SA: "Saudi Arabia",
  SN: "Senegal",
  RS: "Serbia",
  SC: "Seychelles",
  SL: "Sierra Leone",
  SG: "Singapore",
  SK: "Slovakia",
  SI: "Slovenia",
  SB: "Solomon Islands",
  SO: "Somalia",
  ZA: "South Africa",
  GS: "South Georgia And Sandwich Isl.",
  ES: "Spain",
  LK: "Sri Lanka",
  SD: "Sudan",
  SR: "Suriname",
  SJ: "Svalbard And Jan Mayen",
  SZ: "Swaziland",
  SE: "Sweden",
  CH: "Switzerland",
  SY: "Syrian Arab Republic",
  TW: "Taiwan",
  TJ: "Tajikistan",
  TZ: "Tanzania",
  TH: "Thailand",
  TL: "Timor-Leste",
  TG: "Togo",
  TK: "Tokelau",
  TO: "Tonga",
  TT: "Trinidad And Tobago",
  TN: "Tunisia",
  TR: "Turkey",
  TM: "Turkmenistan",
  TC: "Turks And Caicos Islands",
  TV: "Tuvalu",
  UG: "Uganda",
  UA: "Ukraine",
  AE: "United Arab Emirates",
  GB: "United Kingdom",
  US: "United States",
  UM: "United States Outlying Islands",
  UY: "Uruguay",
  UZ: "Uzbekistan",
  VU: "Vanuatu",
  VE: "Venezuela",
  VN: "Vietnam",
  VG: "Virgin Islands, British",
  VI: "Virgin Islands, U.S.",
  WF: "Wallis And Futuna",
  EH: "Western Sahara",
  YE: "Yemen",
  ZM: "Zambia",
  ZW: "Zimbabwe",
};
function getKeyByValue(object, value) {
  //console.log(object, value);
  return Object.keys(object).find(key => object[key].toLowerCase() === value.toLowerCase());
}
var obj={};

function mapData(){
  $.ajax({
    url: base_url+'admin_dashboard/mapData',
    method: "post",
    success: function (resp) {
      //console.log(resp);
    var country_data = resp.data;
      for (var i = 0; i < country_data.length; i++) {

       var code= getKeyByValue(countries,country_data[i].country_name);
       //console.log(code);
        obj[code] = "#00c0ef";
      }
      //console.log(obj);
  $('#world-map-markers').vectorMap({
    map              : 'world_mill_en',
    normalizeFunction: 'polynomial',
    hoverOpacity     : 0.7,
    hoverColor       : false,
    backgroundColor  : 'transparent',
    regionStyle      : {
      initial      : {
        fill            : 'rgba(210, 214, 222, 1)',
        'fill-opacity'  : 1,
        stroke          : 'none',
        'stroke-width'  : 0,
        'stroke-opacity': 1
      },
      hover        : {
        'fill-opacity': 0.7,
        cursor        : 'pointer'
      },
      selected     : {
        fill: 'yellow'
      },
      selectedHover: {}
    },
    markerStyle      : {
      initial: {
        fill  : '#00a65a',
        stroke: '#111'
      }
    },
    series: {
      regions: [{
        values: obj
      }]
    },
    onRegionTipShow: function(e, el, code){
      //console.log(el);
      e.preventDefault();
    }
  });
  },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}
function getLatestVideo(){
  $.ajax({
    url: base_url+'admin_dashboard/getLatestVideo',
    method: "post",
    success: function (resp) {
      var data=resp.data;
      html="";
      for (var i = 0; i < data.length; i++) {
        html+=`<tr>
              <td>${i+1}</td>
              <td><div class="table_preview">

                        <a target="_blank" href="${data[i].url}">
                       <img src="${data[i].img}" alt="preview" onerror="this.onerror=null;this.src='${data[i].error_image}'" style="width: 70px; height: 56px;">
                        </a>
                      </div></td>
              <td>${data[i].title}</td>
              <td>${data[i].mode ? (data[i].mode).toUpperCase() : '' }</td>
              <td>${(data[i].created_at).toUpperCase()}</td>
        </tr>`
      }
    $("#latestVideo").html(html);
  },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}

function getCountByTicketType(startDate='',endDate=''){
  $.ajax({
    url: base_url+'admin_dashboard/getCountByTicketType',
    method: "post",
    data:{startDate:startDate,endDate:endDate},
    success: function (resp) {
      var data=resp.data;
      html="";
      //console.log(data);
      classname=['bg-aqua','bg-red','bg-yellow','bg-green'];
      classname=['bg-aqua','bg-aqua','bg-aqua','bg-aqua'];
      for (var i = 0; i < data.length; i++) {
        var t_type="";
        if(data[i].status==0){
           t_type="Open Ticket";
        }else if(data[i].status==1){
           t_type="Replied Ticket";
        }else if(data[i].status==2){
           t_type="Close Ticket";
        }else if(data[i].status==3){
           t_type="Customer Replied Ticket";
        }
        html+=`<div class="col-lg-3 col-xs-6">
          <div class="small-box ${classname[i]}">
            <div class="inner">
              <h3>${data[i].total}</h3>
              <p>Total ${t_type} </p>
            </div>
          </div>
        </div>
        `;
      }
    $("#totalTicketByType").html(html);
  },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}
function getTicketList(startDate='',endDate=''){
  $.ajax({
    url: base_url+'admin_dashboard/getTicketList',
    method: "post",
    data:{startDate:startDate,endDate:endDate},
    success: function (resp) {
      var data=resp.latest_ticket;
      html="";

      for (var i = 0; i < data.length; i++) {
        var uid=0;
        var status="";
          if(data[i]['ticket_type']==1){
            uid=5;
          }else if(data[i]['ticket_type']==2){
            uid=6;
          }else if(data[i]['ticket_type']==3){
            uid=7;
          }

          if(data[i]['t_status']==0){
                          status= "Open";
                        }else if(data[i]['t_status']==1){
                          status= "Replied";
                        }else if(data[i]['t_status']==2){
                          status= "Closed";
                        }
        html+=`<tr>
              <td><a href="javascript:void(0)" class="LoginMeSupport" data-uid="${uid}" data-tid="${data[i]['t_id']}">${data[i]['ticket_no']}</a></td>
              <td>${data[i].subject}</td>
              <td>${data[i].name}</td>
              <td>${data[i].user_uname}</td>
              <td>${data[i].status}</td>
        </tr>`
      }
    $("#TicketDataList").html(html);
  },
    error : function(resp){
      if(resp.responseJSON.message){
        toastr.warning(resp.responseJSON.message);
      }else{
        toastr.error('Something went wrong, please try again.');
      }
    }
  });
}
//getSupportData();
getUserData();

// getTicketData();
getLatestUserRecord();
mapData();
getLatestUserRecordEmerging();
getLatestUserRecordBrand();
getLatestVideo();
getCountByTicketType();
getTicketList();
