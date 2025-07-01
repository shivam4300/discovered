var currency='';
(function ($) {
	$(document).ready(function(){
	setTimeout(function(){
		
		if($('#totalEarningData').length){
			var myEarningType 	= 'totalEarning';
			var allLoadedData 	= [];
			var filterRangeType = 5; 
			var formData1 = new FormData();
				formData1.append('target',myEarningType );
				formData1.append('type',filterRangeType );
				formData1.append('totalEarningDetail',1 );
				
				manageMyAjaxPostRequestData(formData1,base_url+'backend/dashboard/get_basic_data').done(function(resp){
					resp = $.parseJSON(resp);
					if(resp['status'] == 1){
						
						allLoadedData[myEarningType] = [];
						allLoadedData[myEarningType][filterRangeType] = resp;
						currency = resp['data']['currency'];
						
						$('.advertisingEarning').html(currency+resp['data']['advertisingEarning']);
						$('.endoresmentEarning').html(currency+resp['data']['endoresmentEarning']);
						$('.mediaEarning').html(currency+resp['data']['mediaEarning']);
						$('.merchentiseEarning').html(currency+resp['data']['merchentiseEarning']);
						$('.partenrShipProgramEarning').html('$'+resp['data']['partenrShipProgramEarning']);
						$('.showsEarning').html(currency+resp['data']['showsEarning']);
						$('.totalEarning').html(currency+resp['data']['totalEarning']);
						
						newLeadChart('.dash_earning_line .ct-chart' , resp['data']['mainChart']['label'] , resp['data']['mainChart']['series'] , resp['data']['mainChart']['max']);
						
						totalEarningsPie('.dash_donut_chart .ct-chart' , resp['data']['earningsBreakdown']['label'] , resp['data']['earningsBreakdown']['series']);
					}else{
						
					}
				});
			
			
			
			// $('ul#myEarningsTabs li:not(:first-child):not(:has(.active)) a , ul.filetMyEarningRange li:not(:last-child) a:not(:has(.active))').on('click' , function(){
			$('ul#myEarningsTabs li:not(:has(.active)) a , ul.filetMyEarningRange li:not(:last-child) a:not(:has(.active))').on('click' , function(){
					var _this = $(this);
					
					var filterSec = (_this.parent().parent().hasClass('nav'))?'mainTab':'range';
					
					
					
					if(filterSec == 'mainTab'){
						if(_this.parent().hasClass('active')){ return false;}
						
						myEarningType = _this.find('h2').attr('class');
						$('#EarningsThrough .add_heading').html('Earnings Through '+ _this.find('p').html() );
						
					}else{
						
						filterRangeType = _this.attr('data-type');
						if(_this.hasClass('active')){ return false;}
						
						$('ul.filetMyEarningRange li a').removeClass('active');
						$('ul.filetMyEarningRange li a[data-type="'+filterRangeType+'"]').addClass('active');
						
					}
					
					$('#mainGraphTitle , #secondGraphTitle').html(  $('ul.filetMyEarningRange li a.active').first().text()   );
					
					var formData2 = new FormData();
					formData2.append('target',myEarningType );
					formData2.append('type',filterRangeType );
					
					filterMyDataFromDb(_this , formData2);
					
				});
			}
			
			function filterMyDataFromDb(_this , passData , filterSec){
				console.log(passData.get('type'));
				manageMyAjaxPostRequestData(passData, base_url+'backend/dashboard/get_basic_data').done(function(resp){
					resp = $.parseJSON(resp);
					if(resp['status'] == 1){
						if(!allLoadedData[myEarningType]){ allLoadedData[myEarningType] = []; }
						allLoadedData[myEarningType][filterRangeType] = resp;
						fillupMyData(_this , resp , filterSec);
					}else{
						
					}
				});
			}
		
			function fillupMyData(_this , resp , filterSec = ''){
				
				var targetSection = (myEarningType == 'totalEarning')?'.totalEarningsGraphs':'.commonEarningsGraphs';
				
				newLeadChart(targetSection , resp['data']['mainChart']['label'] , resp['data']['mainChart']['series'] , resp['data']['mainChart']['max']);
				
				if(myEarningType == 'totalEarning')
				totalEarningsPie('.dash_donut_chart .ct-chart' , resp['data']['earningsBreakdown']['label'] , resp['data']['earningsBreakdown']['series']);
				currency = resp['data']['currency'];
				if(resp['data']['earningThrough']){
					
					var perData = resp['data']['earningThrough']['per'];
					var amount = resp['data']['earningThrough']['amount'];
					
					$('#EarningsThrough .add_heading_revenu').html(currency+amount);
					
					$('#EarningsThrough .counter').attr('data-percent' , perData);
					
					$('#EarningsThrough').next().find('.add_heading_revenu').html((perData != null)?perData:0);
					
					manage_my_earning_through_circle(amount);
				}else{
					$('.advertisingEarning').html(currency+resp['data']['advertisingEarning']);
					$('.endoresmentEarning').html(currency+resp['data']['endoresmentEarning']);
					$('.mediaEarning').html(currency+resp['data']['mediaEarning']);
					$('.merchentiseEarning').html(currency+resp['data']['merchentiseEarning']);
					$('.partenrShipProgramEarning').html(currency+resp['data']['partenrShipProgramEarning']);
					$('.showsEarning').html(currency+resp['data']['showsEarning']);
					$('.totalEarning').html(currency+resp['data']['totalEarning']);
				}
			}
			
			function setProgress(elem, percent) {
				var
				degrees = percent * 3.6,
				transform = /MSIE 9/.test(navigator.userAgent) ? 'msTransform' : 'transform';
				elem.querySelector('.counter').setAttribute('data-percent', Math.round(percent));
				elem.querySelector('.progressEnd').style[transform] = 'rotate(' + degrees + 'deg)';
				elem.querySelector('.progress').style[transform] = 'rotate(' + degrees + 'deg)';
				if (percent >= 50 && !/(^|\s)fiftyPlus(\s|$)/.test(elem.className))
				elem.className += ' fiftyPlus';
			}
		
			function manage_my_earning_through_circle(earningThroughPer){
				let target = 100;
					target = (earningThroughPer *100)/target;
				$('.circular_pogrsbar').html(`<div class="circlePercent">
												  <div class="counter" data-percent="0">
												  <span class="cp_bg"></span>
												  </div>
												  <!--div class="figure">6</div-->
												  <div class="progress"></div>
												  <div class="progressEnd"></div>
												</div>`);
				var elem = document.querySelector('.circlePercent'),percent = 0,stopped = false;
				(function animate() {
					setProgress(elem, (percent += .25));
					if (percent < target && !stopped)
					  setTimeout(animate, 20);
				})();
			}
			
			
			var tagetDateRangePickerParent;
			var tagetDateRangePickerData;
			
			$('.rangepicker').on('click' , function(){
				tagetDateRangePickerParent = $(this).closest('a');
			});
			
			$('.rangepicker').daterangepicker({
				timePicker: false,
				opens: 'center',
				drops: 'up',
				locale: {
				  format: 'DD-MM-YYYY'
				}
			}, function(start, end) {
				
				console.log(start);
				tagetDateRangePickerData = 'From ' +start.format('MMMM D, YYYY')+' - To '+end.format('MMMM D, YYYY');
				$('#mainGraphTitle , #secondGraphTitle').html( tagetDateRangePickerData );
				
				 
				let formData = new FormData();
					formData.append('target',myEarningType );
					formData.append('type',9 );
					formData.append('start',start.format('DD-MM-YYYY'));
					formData.append('end',end.format('DD-MM-YYYY'));
					
					filterMyDataFromDb(tagetDateRangePickerParent , formData , 'range'); 
			});
			$('input.rangepicker.dash').val('Custom');
		
		
			function totalEarningsPie(targetSection , labelData , seriesData){
				var chart = new Chartist.Pie(targetSection, {
				labels: labelData,
				series: seriesData
				}, {
				  donut: true,
				  showLabel: false,
				  donutWidth: 35,
				  plugins: [
					Chartist.plugins.tooltip()
				  ]
				});
			}
			
			var newLeadChart = function(chartId, labelList, seriesList, max) {
				var data = { 
							labels: labelList, 
							series: [{
									  name: 'leads',
									  meta: 'Users',
									  data: seriesList
									}]
							};
							
							
				var option = { 
								showArea	: 	true,
								low			: 	0,
								high		: 	max , //highest  value
								fullWidth	: 	true,
								height		:	500,
								axisX		:	{
													offset		: 50, // margin
													labelInterpolationFnc: function(value, index) {
														return value != '' ?value : null;
													},
													fullWidth 	: true
														},
													axisY		: {
														offset: 30, // margin
														onlyInteger: true
													},
								series		: 	{
													'leads': {
													  lineSmooth: Chartist.Interpolation.simple()
													}
												},
								plugins: [ Chartist.plugins.tooltip()]
							};
							
							
				var lineChart = new Chartist.Line(chartId , data , option);
				//start create
				
				var gradientsAdded = true;
				
				lineChart.on('draw', function(data) {
					
					var _stops, defs, gradient, i, len, stop;
					
					var elem, parent;
					if (data.type === 'point') {
						elem = data.element;
						parent = new Chartist.Svg(elem._node.parentNode);

						parent.elem('line', {
							x1: data.x,
							y1: data.y,
							"class": 'ct-point-content'
						});
					} 
				});
				
			}
		
		},1000)	
		
	});
	
}(jQuery));	



