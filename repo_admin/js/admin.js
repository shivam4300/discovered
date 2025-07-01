$(document).ready(function () {

    $(document).on('click','.OpenFlagModal',function(){
        $('#related_with').val($(this).attr('data-related_with'));
        $('#related_id').val($(this).attr('data-related_id'));
       
        $('#OpenFlagModal').modal('show');
    })

    var MrssPost = [];
    var mrss_post_ids =  [];
    var MrssFormData = new FormData();
    var minlen = 0 , maxlen = 120 ;
    var length = 10 , start = 0 ;

    MrssFormData.set('videolength',minlen + '-' + maxlen);	
    
    function changeFeedUrl(){
        if($('[name="mode"]').val() == 10){
            $('#create720Video').hide();
        }else{
            $('#create720Video').show();
        }
        
        if($('#copyFeedUrl').length > 0){
            $('#copyFeedUrl').val(base_url + 'admin_mrss/microsoft/' + $('[name="mode"]').val());
        }
    }
    
    $(document).on('change , keyup','.Mrssfilter',function(){
        length = 10 ; start = 0 ; MrssPost = [] , mrss_post_ids = [];
        $('.LoadMoreMrss').show();
        $('#MrssVideos').empty();
      
        html = `<li>
                    <span class="dis_cross_sign"></span>
                    <p data-loading="para"  >Loading......</p>
                </li>`;
        
        $('#ShowFeedQueue  , #MrssVideos ').html(html);
       
        setTimeout(()=>{
            getMyPostIdsOfMode();
            changeFeedUrl();
        },500)
        
    })
    
    function loadMrssVideo(){
        return new Promise(function(myResolve, myReject) {
            MrssFormData.set('page','channel_mrss_video');	
            MrssFormData.set('length',length);	
            MrssFormData.set('start',start);	

            $('.Mrssfilter').each(function(){
                MrssFormData.set($(this).attr('name'),$(this).val());	
            });

            manageMyAjaxPostRequestData(MrssFormData, base_url + 'admin_mrss/access_channel_video_list' ).done(function (resp) {
                resp = JSON.parse(resp);
                let data = resp.data; 
                let html = '';
                
                if(data.length){
                    
                    data.forEach((element,value) => {
                        MrssPost.push(element);
                        checked = mrss_post_ids.includes(element.post_id) ? "checked" : '';  
                
                        html += `<div class="ad_genBox">
                                    <div class="ad_genImg"> 
                                        <img src="${element.image_thumb}" alt="images" title="image" onerror="errorThumb(this)">
                                        <a target="_blank" href="${element.vurl}" class="ad_genOverlay"><img src="${base_url+'repo/images/dis_play_icon.png'}" alt="Play" title="Play"></a>
                                        <div class="mo_checkbox">
                                            <input type="checkbox" `+ checked +` id="SelectMrssVideo${element.post_id}" name="marss_post_id[]" value="${element.post_id}" class="mo_checkboxInput SelectMrssVideo">
                                            <label for="SelectMrssVideo${element.post_id}" class="mo_checkboxLabel"></label>
                                        </div>
                                    </div>
                                    <div class="ad_genData">
                                        <h2 class="ad_genttl">${element.title}</h2>
                                        <ul class="ad_genList">
                                            <li>
                                                <p class="ad_genAuther">${element.user_name}</p>
                                            </li>
                                            <li>
                                                <p class="ad_genTime">${element.created_ago}</p>
                                            </li>
                                            ${element.video_size !=undefined ?  
                                            `<li>
                                                <p class="ad_genTime">${element.video_size}</p>
                                            </li>`
                                            : ''
                                            }
                                            ${element.article_type !=undefined ?  
                                            `<li>
                                                <p class="ad_genTime">${element.article_type}</p>
                                            </li>`
                                            : ''
                                            }
                                        </ul>
                                        
                                    </div>
                                </div>`;
                    });
                    if(start == 0){
                        $('#MrssVideos').html(html);
                    }else{
                        $('#MrssVideos').append(html);
                    }
                    myResolve(); // when successful
                }else{
                    // $('#MrssVideos').html('');
                    $('[data-loading="para"]').text('No Results Found')
                    $('.LoadMoreMrss').hide();
                    myResolve()  // when error
                }
            }); 
        });
    }

   

    $('.slider-time').html(minlen + 'm');
	$('.slider-time2').html(maxlen + 'm');

    $("#slider-range").slider({
		range: true,
		min: minlen,
		max: maxlen,
		step: 1,
		values: [minlen, maxlen],
		slide: function(e, ui) {
			$('.slider-time').html(Math.floor(ui.values[0]) + 'm');
			$('.slider-time2').html(Math.floor(ui.values[1]) + 'm');
        },
        stop : function(e,ui){
            MrssFormData.set('videolength', Math.floor(ui.values[0])  + '-' +  Math.floor(ui.values[1]));	
            length = 10 ; start = 0 ;
            loadMrssVideo();
        }
	});

    $(document).on('click','.LoadMoreMrss',function(){
        start+=length;
        loadMrssVideo();
    })

    
	$(document).on('change','.SelectMrssVideo',function(){
		let _this 	=	$(this);
		let _val	=	_this.val();
		
        let position = mrss_post_ids.indexOf(_val);
        console.log(position,mrss_post_ids);
		if (_this.is(":checked")) {
            console.log(checked);
			if(position == -1) mrss_post_ids.push(_val);
		}else{
            console.log(':unchecked');
			if (position > -1) mrss_post_ids.splice(position, 1);
            console.log(mrss_post_ids,'mrss_post_ids');
        }
        
        $('#publishMrssFeed').trigger('click');
        
    })


    $(document).on('click','#publishMrssFeed',function(){
        
        let f = new FormData();
            f.set('mrss_post_ids',mrss_post_ids);	
            f.set('mode',$('[name="mode"]').val());
            
            manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/add_mrss_feed' ).done(function (resp) {
                console.log(resp,'resp');
                if(resp.status == 1){
                    // Custom_notify('success',resp.message);
                    show_mrss_queue(); 
                }else{
                    Custom_notify('error',resp.message);
                }
            })
    })
    
    $(document).on('click','#viewMrssFeed',function(){
        if(mrss_post_ids.length){
            window.open(
                base_url + 'admin_mrss/microsoft/' + $('[name="mode"]').val(),
                '_blank' 
            );
        }
    })

    $(document).on('click','#export_mrss_videos',function(){
        if(mrss_post_ids.length){
            window.open(
                base_url + 'admin_mrss/export_mrss_videos/' + $('[name="mode"]').val(),
                '_blank' 
            );
        }
    })


    $(document).on('click','#create720Video',function(){
        if(mrss_post_ids.length){
            let f = new FormData();
            f.set('mode',$('[name="mode"]').val());
            manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/create_720_Video' ).done(function (resp) {
                console.log(resp,'resp');
                if(resp.status == 1){
                    Custom_notify('success',resp.message);
                    //show_mrss_queue(); 
                }else{
                    Custom_notify('error',resp.message);
                }
            })
        }
    })


    var queueData = [];
    var videoData = [];
    function show_mrss_queue(){
        let f = new FormData();
        f.set('mrss_post_ids',mrss_post_ids);	
        f.set('mode',$('[name="mode"]').val());
        manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/getMrssContentQueue' ).done(function (resp) {
            if(resp.status == 1){
                let html="";
                queueData = resp.mrss_feed;
                let typ =  f.get('mode') == 10 ? 'article' : 'video';
                $('.MarkAsPublished').text('Mark '+queueData.length+' '+ typ +' As Published')
                
                let vdata = [];
                if(queueData.length){
                    queueData.forEach((element,index)=>{
                        let vidId = element?.uploaded_video?.split('/')?.[2]?.split('.')?.[0];
                        // let vidIds = ['660aa9297f897','660aa9d608615','660aaa72aa23d'];
                        // let vidId = vidIds[index];
                        html += `<li id="${vidId}">
                                    <span class="dis_cross_sign RemoveVideosFromFeed" data-post_id="${element.post_id}"></span>
                                    <p>${element.title}</p>
                                </li>`;
                        vdata.push(vidId);
                    })

                    videoData = vdata;
                   
                    setTimeout(function(){
                        CheckIfVideoIsConvert();
                    }, 10000)
                }
                $('#ShowFeedQueue').html(html);
            }
        })
    }

    function CheckIfVideoIsConvert(){
        let f = new FormData();
        f.set('videoData',JSON.stringify(videoData));	
      
        manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/getVideoStatus' ).done(function (resp) {
           if(resp.status == 1){
                $.each(resp.videoData, function( index, value ) {
                    if(value?.message){
                        let message =  JSON.parse(JSON.parse(value.message)['message']);
                        
                        if(message['state'] == 'COMPLETED' && message.outputs[0]['height'] == 720){
                            $(`#${value.file_id}`).css('backgroundColor','green').css('color','white');
                        }
                    }
                });
                setTimeout(function(){
                    CheckIfVideoIsConvert();
                }, 10000)
           }
        })
    }
    
    $(document).on('click','.MarkAsPublished',function(){
        let f = new FormData();
        f.set('mrss_post_ids',mrss_post_ids);	
        f.set('mode',$('[name="mode"]').val());
        manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/markAsPublished' ).done(function (resp) {
            mrss_post_ids = [];
            loadMrssVideo().then(function(){
                show_mrss_queue();
            });
        })
    })

    $(document).on('click','.ClearAllList',function(){
        let f = new FormData();
        f.set('mode',$('[name="mode"]').val());
        manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/clearModeAllList' ).done(function (resp) {
            mrss_post_ids = [];
            loadMrssVideo().then(function(){
                show_mrss_queue();
            });
        })
    })

    $(document).on('click','.RemoveVideosFromFeed',function(){
        let _this = $(this);
        let post_id = _this.attr('data-post_id');
        let target = $('#SelectMrssVideo'+ post_id);

        if(target.length){
            target.click();
        }else{ 
            let f = new FormData();
            f.set('mrss_post_ids',mrss_post_ids);	
            f.set('mode',$('[name="mode"]').val());
            f.set('remove_mrss_post_id',post_id);	
            manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/removeMrssPostId' ).done(function (resp) {
                mrss_post_ids = (resp.mrss_feed).split(',');
               _this.parent().remove();
            })
        }
    })

    function getMyPostIdsOfMode(){
        if($('[name="mode"]').length > 0){
            let f = new FormData();
            f.set('mode',$('[name="mode"]').val());
            
            manageMyAjaxPostRequestData(f, base_url + 'admin_mrss/getMyPostIdsOfMode' ).done(function (resp) {
                mrss_post_ids = (resp.mrss_feed).split(',');
                loadMrssVideo().then(function(){
                    show_mrss_queue();
                });
            })
        }
    }

    getMyPostIdsOfMode()
    changeFeedUrl()

    $(document).on('click','.copytoclipboard',function(){
        let _this 	= $(this);
        let target 	= $($(this).attr('data-target'));
        target.show()
        target.select();
        document.execCommand('copy');
        target.hide()
        Custom_notify('success','Copied ! Successfully....');
    })
    
    /* Ads Rate Plan Setting*/
    $('.js-data-ajax').each((i)=>{
        
        $('.js-data-ajax').eq(i).select2({
            ajax: {
                url: base_url + $('.js-data-ajax').eq(i).attr('data-ajax--url'),
                dataType: 'json',
                method: 'POST',
                delay: 250,
                placeholder:$('.js-data-ajax').eq(i).attr('data-placeholder'),
                data: function (params) {
                    var query = {
                      search: params.term,
                    }
                    return query;
                  },
                processResults: function (res) {
                    let result = [];
                    $.each(res.data.list, function (key, item) {
                        if(item.name.length)
                        result.push({id: item.id ,text: item.name});
                    });
                    return {
                        results: result 
                    };
                }
            }
        });
    });

    $(document).on('change','#plan_type',function(){
        let _this    = $(this);
        let innerTxt = _this.find('option:selected').text();
        $('.SelectArea').hide();
        $('#'+innerTxt+'_area').show();
    });

    $(document).on('click','.getAdsPlanRate',function(e){
		e.preventDefault();
        $('.SelectArea').hide();
		var _this 		= 	$(this);
		if(_this.attr('data-id') != 'undefined' && _this.attr('data-id') != null && _this.attr('data-id') != ''){
			var id 			=	_this.attr('data-id');
			var plan_type 	=	_this.attr('data-plan_type');
			var url			=	"admin_setting/getAdsPlanRate/"+id+"/"+plan_type;
            
            let index = plan_type == 2 ? 0 : 1 ;
            $('.js-data-ajax').eq(index).empty();
			
            $.get(base_url+url , function(data, status){
				data = JSON.parse(data);
				if(data.status == 1){
					$.each(data.data, function(i, item) {
						$('[name="'+i+'"]').val(item);
                    });
                   
                    $.each(data.list, function(i, item) {
                        var newOption = new Option(item.name, item.id, true, true);
                        $('.js-data-ajax').eq(index).append(newOption);
                    });
                   
                    $('#plan_type').trigger('change');
				}else{
					Custom_notify('error',data.message);;
				}
			});
		}else{
			$("input[type='text']").val('');
			$("input[type='hidden']").val('');
		}
		$('#ADD_PALN').modal('show');	
	})
	
	$(document).on('change','[name="dtv_share"]',function(){
		let dshare = $(this).val();
		let cshare = 100-dshare;
		$('[name="creator_share"]').val(cshare);
	})
    
    /* Ads Rate Plan Setting*/



    /***********Filmhub js start here***********/

	$(document).on('click' , 'a[data-film-id]' , function(){
		let _this 	= 	$(this);
		$('#filmhub_form')[0].reset();
		$("#filmhub_uid").empty().trigger('change');
		
		let filmid 	=	_this.attr('data-film-id');
		let prefixName 	=	_this.attr('data-prefix-name');
		$('#film_id').val(filmid);
		$('#prefix_name').val(prefixName);
	});	

	$(document).on('click' , '#refresh_filmhub_list' , function(){
		let _this 	 = 	$(this);
		let url 	 =	_this.attr('data-film-url');
		let formData = new FormData();
		manageMyAjaxPostRequestData(formData , base_url + url).done(function(resp){
			if (resp['status'] == 1) {
				Custom_notify('success',resp['message']);
                location.reload();
			}else{
				Custom_notify('error',resp['message']);
			}
			
		});
	});

    var filmhub_queue = 0;
    $(document).on('click' , '.getYamlFile' , function(){
		let _this 	 = 	$(this);
		let f = new FormData();
	
        f.append('film_id',$('#film_id').val());
        f.append('filmhub_uid',$('#filmhub_uid').val());

		manageMyAjaxPostRequestData(f , base_url + 'filmhub/readYamlFile').done(function(resp){
			if (resp['status'] == 1) {
                if(resp['data']['contents']){
                    let h = '';
                    resp.data.contents.forEach((key,index)=>{
                        console.log(key,index);
                        let season = key.season_number ? key.season_number : 0;
                        h += `<li class="list-group-item d-flex justify-content-between align-items-center">
                                ${ typeof key.name != 'undefined' ? 'S' + season + 'E' + key.episode_number + ' ' + key.name : key}
                                <span class="badge badge-primary badge-pill">Pending <i class="fa fa-refresh fa-spin" style="font-size:16px"></i></span>
                            </li>`;
                    })
                    $('#listgroup').html(h);
                }
                uploadFilmhubContent(resp['data'],filmhub_queue);
				Custom_notify('success',resp['message']);
			}else{
				Custom_notify('error',resp['message']);
			}
		});
	});

    function uploadFilmhubContent(data,filmhub_queue){
        if(filmhub_queue < data.contents.length){
            let f = new FormData();
	
            f.append('film_id',$('#film_id').val());
            f.append('filmhub_uid',$('#filmhub_uid').val());
            f.append('data',JSON.stringify(data));
            f.append('filzmhub_queue',filmhub_queue);
            f.append('work', JSON.stringify( data['contents'][filmhub_queue] ) );
            f.append('contentsLength', data.contents.length );
            
            manageMyAjaxPostRequestData(f , base_url + 'filmhub/ingest_film').done(function(resp){
                if (resp['status'] == 1) {
                    $('.badge-pill').eq(filmhub_queue).text('complete').css("background-color", "green");;
                    filmhub_queue++;
                    uploadFilmhubContent(data,filmhub_queue);
                    Custom_notify('success',resp['message']);
                }else{
                    Custom_notify('error',resp['message']);
                }
            });
        }
    }
    
	/***********Filmhub js ends here***********/


    
})

    

