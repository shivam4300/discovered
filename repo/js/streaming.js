	
	/***************** START OF STREAMING SECTION ******************/	
	
	$(document).on('submit','.RequestToLiveStream',function(e){
		e.preventDefault();
		let _this = $(this);
		let checkValid = checkRequire(_this);
		if(checkValid == 0){
			let f = new FormData(_this[0]); 
				f.append('liveStream',2);
			manageMyAjaxPostRequestData(f,base_url+_this.attr('action')).done(function(resp){
				console.log(resp);
				if(resp.status == 1){
					Custom_notify('success',resp.message);	
					setTimeout(function(){
						location.reload();
					},3000);
				}
			})
		}
	})

	if($('.streamdatepicker').length > 0){
		let start = new Date();
		let m = start .getMinutes();
			m = m/5;
			m = parseInt(m);
			m = m*5 + 5 ;
			start.setMinutes(m);
			
		$('.streamdatepicker').daterangepicker({
			drops : "up",
			minDate : new Date(),
			startDate : start,
			singleDatePicker: true,
			timePicker: true,
			timePickerIncrement: 1, 
			timePicker12Hour: true,
			timePickerIncrement: 5,
			// timePicker24Hour: true,
			locale: {
				// format: 'DD/MMM/YYYY h:mm A'
				format: 'YYYY-MM-DD h:mm A'
			}
		});
	}
	$('[name="schedule"]').change(function(){
		let v = $(this).is(":checked");
		let h = `<span class="publish_btn hideme">
					<i class="fa fa-spinner fa-pulse fa-fw"></i>
				</span>`;
		if(v){
			$('.streamdatepicker').parents('.dateArea').show();
			$('.sumbmitBtn').html('Schedule'+h);
		}else{
			$('.streamdatepicker').parents('.dateArea').hide();
			$('.sumbmitBtn').html('Create'+h);
		}
	})
	var unload = true;
	$(document).on('submit','.SubmitStream' , function(e){
		let _this = $(this) , loader = $('.publish_btn') , live_pid =  $('#live_pid');
		e.preventDefault();
		
		let input 				= document.querySelector('#Stream_thumb');
		let filePath 			= input.value;
		
		let allowedExtensions 	= /(\.jpg|\.jpeg|\.png)$/i;
		let D = new Date();
		
		if(allowedExtensions.exec(filePath)){
			let checkValid = checkRequire(_this);
			if(checkValid == 0){
				loader.removeClass('hideme'); 
				let f = new FormData(_this[0]);
				f.set('offset',D.getTimezoneOffset());
				manageMyAjaxPostRequestData(f,base_url+$(this).attr('action')).done(function(resp){
					if(resp.status == 1){
						if(resp.post_id){
							live_pid.val(resp.post_id);		
							
							loader.parent('button').addClass('stream_sp_next_step');
							
							nextTab(nextTab( $('.dis_stream_sp_toplist li.active') ));
							Custom_notify('success',resp.message);
						}
						
						if(base_url+'media_stream/mstream' == window.location.href){
							unload = false;
							setTimeout(()=>{
								location.reload();
							},3000);
						}else if(base_url+'media_stream' == window.location.href){

						}
						else{
							$('#LiveKey').attr('href',resp.pub_url);
						}
					}
					loader.addClass('hideme'); 					
				}).fail(function(resp){
					loader.addClass('hideme'); 
				});
			}	
		}else{
			Custom_notify('error','Please upload the custom thumbnail having extensions .jpeg/.jpg/.png/.gif only.');
			return false;
		}	 
	})
	
	
	$(document).ready(function(){
		ifStreamedVideo();
	})
	function ifStreamedVideo(){
		let live_pid =  $('#live_pid').val();
		
		if(typeof live_pid != 'undefined' && live_pid > 0){
			$('.publish_btn').parent('button').addClass('stream_sp_next_step');
			let elem = $('.dis_stream_sp_toplist li.active');
			nextTab(nextTab(elem));
			
			if($('.StartChannel').data('id') != ''){
				setTimeout(function(){
					elem = $('.dis_stream_sp_toplist li.active');
					nextTab(nextTab(elem));
				},100)
			}
		}
		
	}
	// ifStreamedVideo();
	
	$(document).ready(function(){
		if($('#request_status').length){
			let v = $('#request_status').val();
			if(v == 2){
				$('.dis_sinfo_ttl').text('Request Sent Successfully.');	
				$('.dis_sinfo_des').text('We have received your request for live streaming, you will get a notification and an email when it is approved.');	
			}
			if(v == 0){
				$('.dis_sinfo_ttl').text('Request declined.');	
				$('.dis_sinfo_des').text('Your request for live streaming has been declined.');	
			}
			if(v == 4){
				$('.dis_sinfo_ttl').text('Request On Hold.');	
				$('.dis_sinfo_des').text('Your request for live streaming is on hold.');	
			}
		}	
	})
	
	$(document).on('click','.GetCurrentStreamInfo' , function(e){
		let _this = $(this) 
		let f = new FormData();
		manageMyAjaxPostRequestData(f,base_url+'Streaming/GetCurrentStreamKey').done(function(resp){
			if(resp.status == 1){
				Custom_notify('success',resp.message);
				$('#stream_key').val(resp.data.value);
			}
		}).fail(function(resp){
			$('#stream_key').val(resp.responseJSON.data.value)
		});	 
	})
	
	
	
	
	/***************** END OF STREAMING SECTION ******************/	
	
	
	
	
	/***************** START OF MEDIA LIVE STREAMING SECTION ******************/
		var StremingChannel = $('.StartStremingChannel');
		var liveframe 		= $('iframe#livestream');
		
		if($('.StartStremingChannel').length || $('.StartChannel').length){
			if(window.location.href == base_url + 'media_stream/mstream'){
				window.onbeforeunload = function(e){
					if(unload)
						return true;
				};
				window.onunload = function(event){
					StremingChannel.attr('data-state','stop').click();
					return true;
				};
			}
			
			$(document).on('click','.StartStremingChannel, .StartChannel' , function(e){
				SCTE = 0;
				let _this 	= $(this) ; 
				let f 		= new FormData();
				let d 		= new Date();
				f.set('channel_id',_this.attr('data-id'));
				f.set('state',_this.attr('data-state'));
				f.set('tz_offset',d.getTimezoneOffset());
				manageMyAjaxPostRequestData(f,base_url+'Media_stream/StartStremingChannel').done(function(resp){
					if(resp.status == 1){
						showChannelStatus(_this,$.trim(resp.message) );
						
						if(_this.attr('data-state') == 'stop'){
							setTimeout(()=>{
								$('.crt_vidoestrm_btn').removeClass('hide');
								StremingChannel.addClass('hide');
								$('#is_stream_live').val(0)
								$('#livestream').addClass('hide');
								$('.Lstrem_error_wrapper').removeClass('hide');
							},3000)
						}
						
						if(_this.attr('data-state') == 'start'){
							$('.dis_simpleLoader').removeClass('hide');
							if(window.location.href == base_url + 'media_stream'){
								if(_this.hasClass('StartChannel')){
									window.location.href = base_url + 'media_stream/mstream';
								}
							}
						}
					}else{
						Custom_notify('error',resp.message);
					}
				})
			});

			$(document).on('click','.CreateChannel' , function(e){
				e.preventDefault();
				SCTE = 0;
				let _this 	= $(this) ; 
				let loader = $('.publish_btn_channel');
				loader.removeClass('hideme');
				_this.removeClass('CreateChannel').addClass('stream_sp_next_step');
				let f 		= new FormData();
				let d 		= new Date();
				f.set('tz_offset',d.getTimezoneOffset());
				manageMyAjaxPostRequestData(f,base_url+'Media_stream/CreateStremingChannel').done(function(resp){
					if(resp.status == 1){
						nextTab(nextTab( $('.dis_stream_sp_toplist li.active') ));
						$('.StartChannel').attr('data-id',resp.Channel.Id).attr('data-inputid',resp.input.Id);
						let u = resp.input.Destinations[0].Url;
							v = "/discovered_live_"+user_login_id+'/';
						let a = u.split(v);
						$('#stream_url').val(a[0]+v);
						$('#stream_key').val(a[1]);

					}else{
						Custom_notify('error',resp.message);
					}
					loader.addClass('hideme');
				}).fail(function(){
					loader.addClass('hideme');
					$('.StartChannel').click();
					nextTab(nextTab( $('.dis_stream_sp_toplist li.active') ));
				})
				
			});


			
			function GetChannelStatus(){
				// if($('#is_stream_live').val() == 1){
					let _this = StremingChannel;
					let f 	= new FormData();
					
					f.set('channel_id',_this.attr('data-id'));
					manageMyAjaxPostRequestData(f,base_url+'Media_stream/getStremingChannelDetail').done(function(resp){
						if(resp.status == 1){
							let state = $.trim(resp.message);
							showChannelStatus(_this,state);
						}else{
							Custom_notify('error',resp.message);
						}
					})
				// }
			}
			var STATES = '';
			
			function showChannelStatus(_this,state){
				STATES = state;
				let loader = (state == "STARTING" || state == "STOPPING")? ' <i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i>' : '' ;
				
				// _this.html(state +' '+ loader);
				$('.dis_streamStatus:first  > strong').html(state +' '+ loader);

				if(state == "STARTING"){
					$('.dis_simpleLoader').removeClass('hide');
				}

				setTimeout(function(){
					if(state == 'RUNNING'){
						$('.dis_simpleLoader').addClass('hide');
						_this.attr('data-state','stop');
						_this.html('End Stream');
					}else
					if(state == 'IDLE'){
						$('.dis_simpleLoader').addClass('hide');
						_this.attr('data-state','start');
						_this.html('Start Channel');
					}else{
						$('.dis_streamStatus:last  > strong').hide();
					}
				},2000);
			}
			
			var SCTE = 0;
			function GetChannelMatrixStatics(){
				
				if(STATES == 'RUNNING' && SCTE == 0)
				{	
					insertScte();
					SCTE = 1;
				}else if(STATES == 'STOPPING'){
					clearInterval(insertSplice);
				}
				if(STATES == 'RUNNING' || STATES == 'STOPPING'){ 
					let _this = StremingChannel;
					let f 	= new FormData();
					
					f.set('channel_id',_this.attr('data-id'));
					f.set('post_id',_this.attr('data-pid'));
					f.set('title',$('#liveTitle').text());

					manageMyAjaxPostRequestData(f,base_url+'Media_stream/GetChannelMatrix').done(function(resp){
						if(resp.status == 1){
							showchart(resp.data);
							if(resp.message == 'STREAM-END'){
								$('.dis_streamStatus:last  > strong').hide();
								_this.attr('data-state','stop').click();
							}else
							if(resp.message == 'STREAM-START'){
								$('.dis_streamStatus:last  > strong').html("Live <span class='slb_animation'></span><span></span><span></span>").show();
								let srcc =  liveframe.attr('src');
								liveframe.attr('src',srcc ); 
							}
						}else{
							Custom_notify('error',resp.message);
						}
					})
					

				}
			}

			var insertSplice = '';
			function insertScte(){
				let f 		= new FormData();
				f.set('channel_id',StremingChannel.attr('data-id'));
				
				insertSplice  = setInterval(function(){
					manageMyAjaxPostRequestData(f,base_url+'Media_stream/insertscteMarker').done(function(resp){})
				},480000);	
			}

			var labels = [];
			var series = [];
			function showchart(data){
				labels = [];
				series = [];
				data = data.slice(0,30);
				$.each(data, function (key, val) {
					if(!labels.includes(val['Timestamp'])){
						let UTC = moment.utc(val['Timestamp']);
						labels.push(UTC.local().format('HH:mm:ss'));
						series.push({meta: val['Unit'], value: val['Average'] } );
					}
				});

				
				// if(series && series.length){
				// 	if(series[0].value > 0){
				// 		$('.dis_streamStatus:last  > strong').html("Live");
				// 	}
				// }
				graphchart();
			}
			
			var my_chart = '';
			function graphchart(){
				my_chart = new Chartist.Line('.ct-chart', {
					labels: labels,
					series: [
							series
						]
				},{
					fullWidth: true,
					chartPadding: {
						right: 0
					},
					plugins: [
						Chartist.plugins.tooltip()
					]
				});
				
			}

			
			$(document).on('click','.showChartTab',function(){
				if(my_chart.length) 
					my_chart.update();
			})

			$('a[href="#analiytics"]').on('shown.bs.tab', function(){
				my_chart.update();
			});

			$(document).ready(function(){
				if(StremingChannel.length){
					GetChannelStatus();
					var ChannelStatics 	= setInterval( GetChannelMatrixStatics, 1000);
					var ChannelStatus 	= setInterval( GetChannelStatus, 2000);
						  
					setTimeout(()=>{
						$('.streamNote').addClass('hide');
					},20000)
				}
			})
			
			$(document).on('click','.ResetInputKey' , function(e){
				let _this 	= $(this) ; 
			 
				let f 		= new FormData();
				f.set('input_id',StremingChannel.data('inputid'));
				_this.find('span').removeClass('hide');
				manageMyAjaxPostRequestData(f,base_url+'Media_stream/resetMediaInputKey').done(function(resp){
					if(resp.status == 1){
						$('#stream_key1').val(resp.data);
						Custom_notify('success',resp.message);
					}else{
						Custom_notify('error',resp.message);
					}
					_this.find('span').addClass('hide');
				})
			})	
		
		}
		
		

	/***************** END OF MEDIA LIVE STREAMING SECTION ******************/	