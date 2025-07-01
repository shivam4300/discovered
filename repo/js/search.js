$(document).ready(function(){

	var IsLoaded = true;
	const urlParams = new URLSearchParams(window.location.search);
	const params = Object.fromEntries(urlParams)

	if(params.hide == 'search|people'){
		$(window).scroll(function(){
			$('.profile_load_more:first').hide();
			var hT = $('.profile_load_more:first')?.offset()?.top,hH =$('.profile_load_more:first').outerHeight(),wH = $(window).height(),wS = $(this).scrollTop();
			if (wS > (hT+hH-wH)){
				if(IsLoaded){
					$('[data-action="loadMoreContent"]:first').click();
				}
			}
		});
	}
	
	if($('#lodeMoreScrollId').length>0){
		//$('#lodeMoreScrollId').scroll(function () {
		$(window).scroll(function () {
			$('.profile_load_more:first').hide();
			var hT = $('.profile_load_more:first')?.offset()?.top,hH =$('.profile_load_more:first').outerHeight(),wH = $(window).height(),wS = $(this).scrollTop();
			if (IsLoaded && wS > (hT+hH-wH)){
			//if (IsLoaded && this.scrollHeight - $(this).scrollTop() - $(this).offset().top - $(this).height() <= 0) {
				IsLoaded = false;
				$('[data-action="loadMoreContent"]:first').click();
			}else{
				$('.profile_load_more:first').show();
			}
		});
	}

	$('.load-my-content').each(function(){
		var _this = $(this);
		var targetUrl = _this.attr('data-action');
		$.ajax({
			method : 'POST',
			url : targetUrl,
			success : function(resp){
				if($('.dis_preloader').length){
					$('.dis_preloader').remove();
				}
				_this.html(resp);
			}
		});
	});
	
	
	
	$(document).on('click' , '[data-action="loadMoreContent"]' , function(){
		var _this = $(this);

		IsLoaded = false; _this.html('Loading...');

		var targetOffset = _this.attr('data-offset');
		var targetSection = _this.closest('.load-my-content');
		var targetUrl = _this.closest('.load-my-content').attr('data-action');
		var preHeight = targetSection.height();
		
		$.ajax({
			method : 'POST',
			url : targetUrl,
			data : {'start' : targetOffset},
			success : function(resp){
				_this.parent().parent().remove();
				targetSection.append(resp);
				IsLoaded = true;
			}
		});
	});
	
	$(document).on('click' , '.search-me' , function(){
		$(this).closest('form').submit();		
	});
	
	$(document).on('change' , '#filter_in_televison_mode' , function(){
		$('#search_mode_id').val($(this).val());
		$('[name="genre_id"]').val('').trigger('change');
		$('.search-me').trigger('click');		
	});

	$(document).on('click change' , '.search-me-by-mode' , function(){
		$('#search_mode_id').val($(this).attr('data-mode-id'));
		$('[name="genre_id"]').val('').trigger('change');
		$('.search-me').trigger('click');		
	});
	/*********Filter Section start here******/
	$(document).on('click' , '#apply_filter_btn' , function(){
		$('ul.custom_filter li').each(function(i,elem) {
			if($(elem).hasClass('active') && $(elem).attr('data-range-type')){
				$('#search_daterange').val($(this).attr('data-range-type'));
			}else
			if($(elem).hasClass('active') && $(elem).attr('data-duration-type')){
				$('#search_video_duration').val($(this).attr('data-duration-type'));
			}else
			if($(elem).hasClass('active') && $(elem).attr('data-sort-type')){
				$('#search_sort_by').val($(this).attr('data-sort-type'));
			}
		});
		$('[name="genre_id"]').val('').trigger('change');
		$('.search-me').trigger('click');		
	});

	$(document).on('click' , 'ul.custom_filter li' , function(){
		$(this).closest('li').siblings().removeClass('active');
		$(this).addClass('active');
	});

	$(document).on('click' , '#clear_filter_btn' , function(){
		$('ul.custom_filter li').removeClass('active');
		$('#search_daterange').val('');
		$('#search_video_duration').val('');
		$('#search_sort_by').val('');
		$('[name="genre_id"]').val('').trigger('change');
		$('.search-me').trigger('click');	
	});

	/*********Filter Section end here******/


	/******************************************/
	/************ IVA Search Start ************/
	/******************************************/
	
	var targetLoader = $('.iav_enqry_wrapper .pro_loader');
	var defaultFilteredData = $('#accordion').html();
	
	$(document).on('change' , '#ivaContentType' , function(){
		
	});
	
	$(document).on('click' , '#uploadSelectedVideo' , function(){
		console.log($(this).val());
		var videoData = [];
		var videoContent = [];
		if($('#accordion input.selectedVideo').length){
			$('#accordion input.selectedVideo').each(function(){
				if($(this).is(':checked')){
					videoData.push($(this).val());
					videoContent.push($(this).parent().find('.myVideoData').html());
				}
			});
		}
		
		if(videoData.length > 0){
			targetLoader.show();
			$.ajax({
				url : base_url+'IVA_api/upload_my_video',
				method : 'post',
				data : {
					selectedVideo : videoData,
					selectedVideoContent : videoContent 
				},
				success : function(resp){
					targetLoader.hide();
					resp = $.parseJSON(resp);
					console.log(resp);
					if(resp['status'] == 1){
						$('#searchIVAInput').val('');
						$('#accordion').html(defaultFilteredData);
						$('#uploadSelectedVideo').addClass('hide');
						success_popup_function('IVA data successfully mapped to DTV.');
					}else{
						server_error_popup_function('Something went wrong, please try again later.');
					}
				},
				error : function(){
					targetLoader.hide();
					server_error_popup_function('Something went wrong, please try again later.');
				}
			});
		}else{
			server_error_popup_function('Please select any one content to apply this action.');
		}
	});
	
	function filter_data_from_vai(targetId) {
		targetLoader.show();
		
		$.ajax({
			url: base_url+'IVA_api/search_video_data',
			method : "post",
			data: {
				id : targetId,
				type : $('#ivaContentType').val()
			},
			success: function( data ) {
				targetLoader.hide();
				resp = $.parseJSON(data);
				if(resp['status'] == 1){
					$('#accordion').html(resp['data']);
					$('#uploadSelectedVideo').removeClass('hide');
				}else{
					$('#accordion').html('');
					$('#uploadSelectedVideo').addClass('hide');
				}
			},
			error : function(){
				targetLoader.hide();
				server_error_popup_function('Something went wrong, please try again later.');
			}
		});
		
		
		
	}

	$( "#searchIVAInput" ).autocomplete({
		messages: {
			noResults: 'no results',
			results: function(amount) {
				return ''
				// return amount + 'results.'
			}
		},
		appendTo: ".dis_search_warapper .search_box",
		source: function( request, response ) {
			$.ajax({
				url: base_url+'IVA_api/search_autocomplete',
				dataType: "jsonp",
				data: {
					q: request.term,
					t: $('#ivaContentType').val()
				},
				success: function( data ) {
					response( data );
				}
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			filter_data_from_vai( ui.item ? ui.item.value : '');
		},
		open: function() {
			$( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			$( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
	
	
	/******************************************/
	/************** IVA Search End ************/
	/******************************************/
	
	
	
	
	$( "#searchFaqEnquiry" ).autocomplete({
		messages: {
			noResults: 'no results',
			results: function(amount) {
				return ''
				// return amount + 'results.'
			}
		},
		appendTo: ".dis_search_warapper .search_box",
		source: function( request, response ) {
			$.ajax({
				url: base_url+'help/search_content',
				dataType: "jsonp",
				data: {
					q: request.term,
				},
				success: function( data ) {
					response( data );
				}
			});
		},
		minLength: 1,
		select: function( event, ui ) {
			let search = ui.item ? ui.item.value : '';
			$('#searchFaqEnquiry').val(search);
			$('#searchFaqEnquiry').parents('form').submit();
		},
		open: function() {
			// $( this ).removeClass( "ui-corner-all" ).addClass( "ui-corner-top" );
		},
		close: function() {
			// $( this ).removeClass( "ui-corner-top" ).addClass( "ui-corner-all" );
		}
	});
	
});