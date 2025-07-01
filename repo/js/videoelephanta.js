/************** Video Elephanta  STARTS ************************/
var uploadIndex=0;
var postLength=0;
	$('form.videoElephant').on('submit' , function(e){
		var _this = $(this);
		e.preventDefault();
		var checkValid = checkRequire(_this);
		if(checkValid == 0){
			let formData = new FormData(_this[0]); 
			manageMyAjaxPostRequestData(formData , base_url +  $(this).attr('action')).done(function(resp){
				if(resp['status'] == 1){

					if(resp['message']=="error"){

						$('input[name="mrss_url"]').addClass('error').focus();
						$('.cmn_upbox_multibox').html("");
						$('.dis_videoelephant_wrap').addClass('videoelephant_dataload');
						Custom_notify('error',"Please enter valid url");
					}else{
						$('.cmn_upbox_multibox').html(resp['message']);
						uploadIndex=0;
						postLength=0;
						uploadVideoElephant(uploadIndex);
						
						$('.dis_videoelephant_wrap').removeClass('videoelephant_dataload');
					}
					
				}
			});
		}
	});
	function uploadVideoElephant(i){
		var url = $('input[name="url[]"]')
              .map(function(){return $(this).val();}).get();
        var thumbnail = $('input[name="thumbnail[]"]')
              .map(function(){return $(this).val();}).get();
        var post_id = $('input[name="post_id[]"]')
              .map(function(){return $(this).val();}).get();
        var duration = $('input[name="duration[]"]')
              .map(function(){return $(this).val();}).get();
        var vid_provider_id = $('input[name="vid_provider_id[]"]')
              .map(function(){return $(this).val();}).get();
              
       if(postLength==0){
       		postLength=url.length;
       }
       postLength--;
       
        let formData = new FormData();
		formData.append("url", url[i]);
		formData.append("thumbnail",thumbnail[i]);
		formData.append("post_id",post_id[i]);
		formData.append("postLength",postLength);
		formData.append("duration",duration[i]);
		formData.append("vid_provider_id",vid_provider_id[i]);
		manageMyAjaxPostRequestData(formData , base_url + 'Videoelephant/uploadS3VideoElephanta').done(function(resp){
				if(resp['status'] == 1){
					uploadIndex++;
					$(".cmn_upbox_innerbody").eq(i).find('.montiz_details_sn').addClass("active_sn");
					$(".cmn_upbox_innerbody").eq(i).find('.dis_ve_ss_thumb_loader').html("");
					$(".cmn_upbox_innerbody").eq(i).find('.dis_ve_ss_thumb_play').show();
					if(uploadIndex < url.length){

						if(resp['type']==1){
							$('#post_id_'+(uploadIndex)).val(resp['message']['post_old_id']);
							$('#post_id_'+(uploadIndex+1)).val(resp['message']['post_id']);
						}else if(resp['type']==0){
							$('#post_id_'+(uploadIndex+1)).val(resp['message']['post_id']);
						}else if(resp['type']==2){
							Custom_notify('error',resp['message']['error']);
						}
												
						uploadVideoElephant(uploadIndex);
					}
					
				}
			}).fail(function(e) {
				//console.log(e);
				/*$(".cmn_upbox_innerbody").eq(i).find('.montiz_details_sn').addClass("active_sn");
					$(".cmn_upbox_innerbody").eq(i).find('.dis_ve_ss_thumb_loader').html("");
					$(".cmn_upbox_innerbody").eq(i).find('.dis_ve_ss_thumb_play').show();*/
			    getIdifFaildData(i);
			  });
	}
	function getIdifFaildData(i){
		 $.ajax({
	    url: base_url+'Videoelephant/getIdifFaildData',
	    method: "post",
	    success: function (resp) {
	    	uploadIndex++;
	    	$('#post_id_'+(uploadIndex+1)).val(resp['message']['post_id']);
	    	if(postLength>0){
	    		uploadVideoElephant(uploadIndex);
	    	}
	    	
	    }
	     });
	}
	$(document).on('submit' ,'.submitVideoElephantForm', function(e){
		e.preventDefault();
		var _this = $(this);
		var checkValid = checkRequire(_this);
		if(checkValid == 0){
			let formData = new FormData(_this[0]); 
				manageMyAjaxPostRequestData(formData, base_url +  $(this).attr('action')).done(function(resp){
			
					if(resp.status == 1){
						Custom_notify('success',resp.message);
						setTimeout(()=>{ window.location = resp['redurl'] },3000);
					}
				});
		}
		
	})
/************** Video Elephanta  END ************************/
$(document).on('change','.SelectBySimpleSelectVideoElephant' , function(){
		let _this 		= $(this);
		let url 		= _this.data('url');
		let check_url 	= url.split('/').length;
		
		let base = (check_url > 1)? base_url : node_url ;
		ToggleSubGenreVideoElephanta(_this.val(),_this);
		if(_this.val() != '' && _this.val() != 0){
			manageMyAjaxPostRequestData({ id:  _this.val() } , base + url).done(function(resp){
				let elementId = _this.data('id');
				$(elementId).html(resp);
			})
		}
		
	});
$(document).on('change','.SelectBySimpleSelectGenre' , function(){
		let _this 		= $(this);
		let url 		= _this.data('url');
		let check_url 	= url.split('/').length;
		
		let base = (check_url > 1)? base_url : node_url ;
		
		if(_this.val() != '' && _this.val() != 0){
			manageMyAjaxPostRequestData({ genre_id:  _this.val() } , base + url).done(function(resp){
				let elementId = _this.data('id');
				$(elementId).html(resp);
				
			})
		}
		
	});
$(document).on('click','.dis_ve_ss_dleft',function(){
	var id =$(this).attr('data-id');
	let url_ele=$('#post_url_d_'+id).val();

	let html='<iframe id="" src="'+url_ele+'" width="100%" height="480px" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>';
	$('.ele_ifram').html(html);
	$('#velepnt_modal').modal('show');
});

$('#velepnt_modal').on('hidden.bs.modal', function () {
    $('.ele_ifram').html("");
});
function ToggleSubGenreVideoElephanta(val,obj){
	if(val == 7){
		$(obj).parent().parent().parent().parent().find('.sub_genre_video_elephant').show();
	}else{
		$(obj).parent().parent().parent().parent().find('.sub_genre_video_elephant').hide();
	}
}

$(document).on('click','.confirm_slider_popup',function(){
	$('#slider_popup').modal('show');
});

$(document).on('click','.confirm_no',function(){
	$(".r_slider").remove();
	$("form.submitVideoElephantForm").submit();

	$('#slider_popup').modal('hide');
});

$(document).on('click','.confirm_yes',function(){
	$('#slider_popup').modal('hide')
	$('#slider_title_popup').modal('show');
});

$(document).on('click','.confirm_yes_submit',function(){
	let check=1;
	var slider_mode=$("#slider_mode").val();
	var slider_id=$("#slider_id").val();
	if(slider_mode==''){
		Custom_notify('error',"Please select mode");
		return false;
	}
	if(slider_id==''){
		Custom_notify('error',"Please select slider");
		return false;
	}
	$('.SelectBySimpleSelectVideoElephant').each(function(){
	    //console.log($(this).val());
	    if($(this).val()!=$('#slider_mode').val()){
	    	check=0;
	    }
	});
	if(check==0 && slider_mode!=8){
		Custom_notify('error',"The mode doesn't match video mode");
		return false;
	}
	$('input[name="slider_mode"]').remove();
	$('input[name="slider_id"]').remove();
	$("form.submitVideoElephantForm").append("<input type='hidden' name='slider_mode' class='slider_mode' value='"
		+slider_mode+"'>");
	$("form.submitVideoElephantForm").append("<input type='hidden' name='slider_id' class='slider_id' value='"
		+slider_id+"'>");
	$("form.submitVideoElephantForm").submit();
	$('#slider_title_popup').modal('hide')
});

$(document).on('change','.getSlidarList' , function(){
	let _this 		= $(this);		
	if(_this.val() != '' && _this.val() != 0){
		manageMyAjaxPostRequestData({ id:  _this.val() } , base_url + "Videoelephant/getSlidarList").done(function(resp){
			$('.slider_id').html(resp.data);
		})
	}
		
});
$(document).on('click','.skip_video',function(){
	let post_id=$(this).attr('data-id');
	console.log($('#'+post_id).val());
	$('#delete_video_id').val($('#'+post_id).val());
	$('#delete_video_index').val(post_id);
	$('#video_skip').modal('show');
});
$(document).on('click','.confirm_delete' , function(){
	let _this 		= $(this);	
	let delete_video_index=$('#delete_video_index').val();	
	manageMyAjaxPostRequestData({ id: $('#delete_video_id').val() } , base_url + "Videoelephant/confirm_delete").done(function(resp){
				$('#'+delete_video_index).parent().remove();
				$('#video_skip').modal('hide');
	})
});

$(document).on('change','[name="mode"]',function(){
	let mode_id = $(this).val();
	if(mode_id.length){
		ToggleSubGenre();
		manageMyAjaxPostRequestData({ id: $(this).val() }, node_url+'getGenreList').done(function(resp){
			$('[name="genre"]').html(resp);
		})
	}
})
	
$(document).on('change','[name="genre"]',function(){
	manageMyAjaxPostRequestData({ genre_id: $(this).val() }, node_url+'getSubGenreList' ).done(function(resp){
		$('[name="sub_genre"]').html(resp);
	})
		
})

$(document).on('change','.GloabalMode',function(){
	$('[name="mode[]"]').val($(this).val()).trigger('change');	
})

$(document).on('change','.GloabalGenre',function(){
	$('[name="genre[]"]').val($(this).val()).trigger('change');	
})

function ToggleSubGenre(){
	let sub_genre = $('[name="sub_genre"]').parent().parent().parent();

	if($('[name="mode"]').length && $('[name="mode"]').val() == 7){
		sub_genre.show();
	}else{
		sub_genre.hide();
	}
}
