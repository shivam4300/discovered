/************************************************************Backend Js End here************************************************************/

$(document).on('click' ,'.requestForStore' , function(e){
    var _this = $(this);
    var f = new FormData();
    f.append('store_request',1);
        manageMyAjaxPostRequestData(f,base_url+'backend/store/request_store_access').done(function(resp){
            if(resp['status'] == 1){
                Custom_notify('success',resp['message']);
            }else{
                Custom_notify('error',resp['message']);
            }
            setTimeout(()=>{    
                window.location = window.location.href;
            },2000)
        })
});


$(document).on('submit','#addEditProduct',function(e){
	e.preventDefault();
	var _this = $(this);
	let url = _this.attr('action');
	if(checkRequire(_this) == 0){
		var cate = [];
		$.each($("#product_cats_checked option:selected"), function(){            
            cate.push($(this).val());
        });
		var formData = new FormData(_this[0]);
		formData.append('product_cats',cate);
		_this.find('button[type="submit"]').text("Save ")
        .append('<i class="fa fa-spinner fa-spin post_spinner"></i>')
        .prop("disabled", true);
		manageMyAjaxPostRequestData(formData, base_url+url).done(function(resp){
			if(resp['status'] == 1){
				Custom_notify('success',resp['message']);
				_this.find('button[type="submit"]').text("Save").prop("disabled", false);
				location.reload();
			}
		});
	}
});



$(document).on('click' , 'a[data-delete-idffff]' , function(e){
	e.preventDefault();		
	var _this 		= 	$(this);
	let formData 	= 	new FormData();
	var url 		=  _this.attr('data-action-url');
	var id 			=  _this.attr('data-delete-id');
	var field 		=  _this.attr('data-field');
		
	formData.append('id', id);
	formData.append('field', field);
	 
	var swals = {
				  title: "Are you sure want to delete this ?",
				  text: 'If you delete, all the details related with this, will be delete.' ,
				  icon: 'warning',
				  buttons: true,
				  dangerMode: true ,
				};
	
	if(url.search("site_main_data") != -1)
	swals.text = 'If there are slider with the same title in other mode,will be delete';
	
	
	swal(swals)
	.then((done) => {
	  if (done) {
		manageMyAjaxPostRequestData(formData,base_url+url ).done(function (resp) {
				if (resp['status'] == 1) {
					Custom_notify('success',resp['message']);;
					if(_this.parents('table.display').attr('data-refresh-dataTablePosition')){
						dataTableObj[_this.parents('table.display').attr('data-refresh-dataTablePosition')].ajax.url(base_url+_this.parents('table.display').attr('data-action-url')).clear().draw();	
					}
				}else{
					Custom_notify('error',resp['message']);
				}
		});	
		} 
	});
});

$(document).on('click','.removeProImage',function(e){
	e.preventDefault();
	var swals = {
				  title: "Are you sure want to remove this image ?",
				  text: 'If you delete, all the details related with this, will be delete.' ,
				  icon: 'warning',
				  buttons: true,
				  dangerMode: true ,
				};
	swal(swals)
	.then((done) => {
		if (done) {
			var _this = $(this);
			let id = _this.attr('data-img-id');
			if(id !=='undefined'){
				$('#removed_img_id').val(id);
				$('#addEditProduct').submit();
			}
		} 
	});
});


function template(colorArr) {
	return colorArr.html;
}
	
if($('[data-target="select2"]').length){
	$('[data-target="select2"]').each(function(){
		var _this = $(this);
		var selected_val = _this.attr('data-selected');
		if($(this).hasClass('getAttrTerms')){
			
			if(selected_val !==''){
				var formData = new FormData();
				formData.append('attribute_id',_this.attr('id'));
				formData.append('term',selected_val);
				manageMyAjaxPostRequestData(formData, base_url+'backend/store/getAttrbuteTermsByAjax').done(function(resp){
					if(resp['status'] == 1){
						let data = resp['terms'][0];
						var newOption = new Option(data.text, data.id, false, false);
						_this.append(newOption).trigger('change');
					}
				});
			}
						
			_this.select2({
				placeholder : 'Select Options',
				minimumInputLength: 1,
				minimumResultsForSearch: 10,
				ajax: {
				url: base_url+'backend/store/getAttrbuteTermsByAjax',
				dataType: "json",
				type: "POST",
				delay: 250,
				data: function (params) {
					var queryParameters = {
						term: params.term,
						attribute_id : _this.attr('id')
						
					}
					return queryParameters;
				},
				processResults: function (data) {
					return {
						results: data['terms'],
						
					};
				},
				cache: true
			},
			templateResult: template,
			escapeMarkup: function(m) {
			  return m;
			},
			
			
			});
			/*if(selected_val !=='undefined'){
				_this.val(selected_val).trigger('change');
			}*/
			/*var formData = new FormData();
			formData.append('attribute_id',_this.attr('id'));
			manageMyAjaxPostRequestData(formData, base_url+'backend/store/getAttrbuteTermsByAjax').done(function(resp){
				if(resp['status'] == 1){
					//_this.append(resp['data']);
					_this.select2({
						//multiple : true,
						data: resp['terms'],
						templateResult: template,
						escapeMarkup: function(m) {
						  return m;
						},
					});
	
					if(selected_val !=='undefined'){
						_this.val(selected_val).trigger('change');
					}
				}
			});*/
		}
	});
}	



$(document).on('change','#update_order_status',function(e){
	
	var swals = {
				  title: "Are you sure you want to update order status ?",
				  text: '' ,
				  icon: 'warning',
				  buttons: true,
				  dangerMode: true ,
				};
	swal(swals)
	.then((done) => {
		if (done) {
			var _this = $(this);
			var formData = new FormData();
			formData.append('order_id',$('#order_id').val());
			//formData.append('vendor_id',$('#vendor_id').val());
			formData.append('order_status',_this.val());
			manageMyAjaxPostRequestData(formData, base_url+'backend/store/updateOrderStatus').done(function(resp){
				if(resp['status'] == 1){
					Custom_notify('success',resp['message']);
					//location.reload();
				}else{
					Custom_notify('error',resp['message']);
				}
			});
		} 
	});
	
});


/************************************************************Backend Js End here************************************************************/


/************************************************************Front Js Start here************************************************************/

if($('#appendMyShopSlider').length>0){
	$(document).ready(function () {
		setTimeout(()=>{ getProducts(); },500);
		//setTimeout(()=>{ getProducts(); },1500);
	});
	
	var ControlRequest 				= false;
	$(window).scroll(function(){
		/*console.log($(window).scrollTop()  + $(window).height() , $(document).height(), $(document).height()-400);*/
		if($(window).scrollTop()  + $(window).height() > $(document).height()-400){
			if(ControlRequest){
				ControlRequest = false;
				getProducts();
			}
		}
	});
}


var prolistStart = 0;
var prolistLimit = 1;
function getProducts(){
	//if($('#data-user_id').attr('data-user_id') !==undefined){
		let user_id = $('#data-user_id').attr('data-user_id');
		user_id     = (user_id !==undefined) ? user_id : '';
		var formData = new FormData();
		formData.append("start", prolistStart); 
		formData.append("limit", prolistLimit);
		//formData.append("uid", user_id);
		manageMyAjaxPostRequestData(formData, base_url+'store/getProductsList').done(function(resp){
			resp = JSON.parse(resp);
			if(resp['status'] == 1){
				let resData = resp.data;
				if(resData.length){
					$.each(resData, function (i) {
						var sliderHtml = getProductsSliderHtml(resData[i]);
						
						let a =	$('#appendMyShopSlider').length > 0  ? $('#appendMyShopSlider') : $('#appendSlider') ;
						let m = (prolistStart == 0 && i == 0)?'html':'append';
						a[m](sliderHtml);

						let thhs = $('div.au_artist_slider:last');
						
						if(sliderHtml!=undefined){
							swiperslider(thhs);
						}
					
						if(resData[i]['prodList'] !== undefined && resData[i]['prodList'].length<10){
							$('.dis_sh_btnwrap:last').hide();
						}
					});
					ControlRequest = true;
					prolistStart += prolistLimit;
				}else{
					ControlRequest = false;
				}
			}else{
				ControlRequest = false;
			}
		});
	///}
}

function getProductsSliderHtml(resData){
	var color		=	resData['color'];
	var title		=	resData['title'];
	var type		=	resData['type'];
	var href		=	resData['href'];
	var autoPlay 	= 	resData['auto'];
	var onerrorImg  = 	resData['errorImg'];
	var prodData 	= 	resData['prodList'];
	var html = '';
	if(prodData.length>0){	
		html =`<div class="dis_allproductSliderPage">
				<div class="dis_sliderheading">
					<h2 class="dis_sliderheading_ttl muli_font">${title}</h2>
					<div class="dis_sh_btnwrap">
						<a href="" class="dis_sh_btn muli_font">See all
							<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">	<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path></svg></span>
						</a>
					</div>
				</div>
				<div class="au_artist_slider" data-autoplay="${autoPlay}">
					<div class="swiper-container">
						<div class="swiper-wrapper">`;
						
						$.each(prodData, function (i) {
							var prod = prodData[i];
							let proImgUrl = '';
							if(prod.images.length){
								let proImg = prod.images[0];
								proImgUrl = proImg.src;
							}
							var style = '';
							var title = 'Add To Wishlist';
							if (user_login_id !== '') {
								if (prod.fav === 1) {
									style = "#fe5501";
									title = 'Remove From Wishlist';
								}else{
									style = "rgb(143, 157, 165)";
								}
							}else{
								style = "rgb(143, 157, 165)";
							}
							html +=`<div class="swiper-slide">
								<div class="dis_product_box">
									<div class="dis_product_img">
										<img src="${proImgUrl}" onerror="this.onerror=null;this.src="${onerrorImg}" class="img-responsive" alt="Product Image">
										<div class="dis_product_overlay">
											<ul class="dis_product_detailsList">
												<li>
													<a href="${prod.single_prod_link}" data-toggle="tooltip" title="View Product" role="tooltip">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink"width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)"d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="javascript:;" data-toggle="tooltip" title="Add To Cart" onclick="addToCart(${prod.id},1)">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"/></svg>
													</a>
												</li>
												<li>
													<a href="javascript:;" class="addToWishlist" data-state="${prod.fav}" data-uid="${user_login_id}" data-id="${prod.id}" data-toggle="tooltip" title="${title}">
														<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path  fill-rule="evenodd" fill="${style}" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"/></svg>
													</a>
												</li>
											</ul>
											<div class="dis_product_btnwrap">
												<a href="${prod.single_prod_link}" class="dis_black_btn muli_font">Buy Now 
												<span >
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg>
												</span>
												</a>
											</div>
										</div>
									</div>
									<div class="dis_product_content">
										<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">${prod.name}</a></h3>
										<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">${DEFAULT_CURRENCY_SYMBOL+prod.regular_price}</span>${DEFAULT_CURRENCY_SYMBOL+prod.sale_price}</h2>
									</div>
								</div>
							</div>`;
						});
						html +=`</div>
						<div class="swiper-button-next fvs-swiper-button-next"><i class="fa fa-angle-right" aria-hidden="true"></i></div>
						<div class="swiper-button-prev fvs-swiper-button-prev"><i class="fa fa-angle-left" aria-hidden="true"></i></div> 
					</div>
				</div>
			</div>`;
	}
	return html;	
}

if($('#appendSlider').length){
	//getProducts();
}

if($('#prod_id').length){
	let prodId = $('#prod_id').val();
	getSingleProduct(prodId);
	getProReviewHtml(prodId);
}

$('#featured_img').change(function(e){
	var fileList = '';
	var selectedFiles = e.target.files;
	if(selectedFiles.length > 0){
		$('#selected_files').text(selectedFiles.length+' files selected please click on save button to upload.');
	}else{
		$('#selected_files').text('');
	}
});

if($('.tokenTag').length){
	$('.tokenTag').tokenfield({
	  autocomplete: {
		source: function (request, response) {
				  jQuery.post(base_url+"backend/store/getProTaglist", {
					  query: request.term
				  }, function (data) {
					  data = $.parseJSON(data);
					  response(data);
				  });
			  },
		delay: 100
	  },
	  showAutocompleteOnFocus: true
	});
}

/*************************Single Product Start************************************/
$(document).on('change','.fiterBySize',function(e){
	$('.dis_pro_rewUserList').empty();
	let prodId =  $(this).val();
	$('#prod_id').val(prodId);
	var resultObject={};
	resultObject.single_product = searchProd(prodId, globalPro.related_product);
	var singleProHtml = getSingleProHtml(resultObject);
	$('#singleProAppend').html(singleProHtml);
	getProReviewHtml(prodId);
	proSwiperSliders();
	changeBrowserUrl(prodId);
	show_desc();
});

function changeBrowserUrl(prodId) { 
	const nextURL = base_url +'store/single_product/' + prodId;
	const nextTitle = 'Discovered | Single Product';
	const nextState = { };

	// This will create a new entry in the browser's history, without reloading
	// window.history.pushState(nextState, nextTitle, nextURL);

	// This will replace the current entry in the browser's history, without reloading
	window.history.replaceState(nextState, nextTitle, nextURL);
 }

function show_desc() { 
	// $('.tab-pane fade').removeClass('active in');
	// $('.prod_tab').removeClass('active');
	// $('#Description').addClass('active in');
	// $('#desc_tab').addClass('active');
	$( "#desc_tab" ).trigger( "click" );

 }

$(document).on('click','.fiterByColor',function(e){
	$('.dis_pro_rewUserList').empty();
	let prodId =  $(this).attr('data-proid');
	$('#prod_id').val(prodId);
	var resultObject={};
	resultObject.single_product = searchProd(prodId, globalPro.related_product);
	var singleProHtml = getSingleProHtml(resultObject);
	$('#singleProAppend').html(singleProHtml);
	getProReviewHtml(prodId);
	proSwiperSliders();
	changeBrowserUrl(prodId);
	show_desc();
});

function searchProd(nameKey, myArray){
	// console.log(myArray);
	for (var i=0; i < myArray.length; i++) {
        if (myArray[i].id == nameKey) {
			// console.log(myArray[i]);
			return myArray[i];
        }
    }
}

const arrayColumn = (array, column) => {
    return array.map(item => item[column]);
};

var globalPro = '';
function getSingleProduct(prodId){
	var formData = new FormData();
	formData.append("prod_id", prodId); 
	manageMyAjaxPostRequestData(formData, base_url+'store/getSingleProdAjax').done(function(resp){
		resp = JSON.parse(resp);
		if(resp['status'] == 1){
			let resData = resp.data;
			globalPro = resData;
			var singleProHtml = getSingleProHtml(resData);
			$('#singleProAppend').html(singleProHtml);
			
			var sliderHtml = getSingleProSliderHtml(resData['other_related_products']);
			$('#myProductsList').append(sliderHtml);
			setTimeout(()=>{    
                proSwiperSliders();
            },500)
			
		}
	});
}

function getSingleProHtml(prodDetails){
	let sp 		 = prodDetails['single_product'];
	let variants = globalPro['variants'];  // get from global variable
	getDescriptionSection(sp);
	getAdditionalDescriptionSection(sp);
	let prod_idd = $('#prod_id').val();
	let fav = `data-state="${sp.fav}" data-uid="${user_login_id}" data-id="${prod_idd}"`;
	let svg_style = 'rgb(143, 157, 165)';
	if (sp.fav != 0) {
		svg_style = '#fe5501';
	}
	let discount_tag = '';
	let meta_d_len = sp.meta_data.length - 1;
	if (sp.meta_data[meta_d_len] !== undefined || sp.meta_data[meta_d_len] !== '') {
		discount_tag = `<li><a class="dis_sp_btn">${sp.meta_data?.[meta_d_len]?.['value']}% OFF</a></li>`;
	}
	let html = '';
	if(sp){
		
		html =`<div class="dis_p_header dis_singleProduct_tp">
			<div class="dis_singleProduct_tpL">
				<h2 class="dis_sp_tpL_ttl mp_0">Store Mode Homepage > Coca-Cola Store > Apparel > <span>${sp.name}</span></h2>
			</div>
			<div class="dis_singleProduct_tpR">
				<a href="#" class="dis_linkbtn">Visit Coca-Cola Store <span class="dis_sp_right"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="8" viewBox="0 0 15 8"> <path fill-rule="evenodd" fill="#f7652d" d="M1577.66,217.671h-0.47c-3.78,0-7.55,0-11.32-.009a1.108,1.108,0,0,1-.69-0.229,0.647,0.647,0,0,1,.35-1.072,2.069,2.069,0,0,1,.45-0.034q5.61,0,11.22,0h0.44c-0.11-.119-0.17-0.194-0.24-0.263-0.63-.618-1.27-1.233-1.9-1.852a0.681,0.681,0,0,1-.08-1.02,0.712,0.712,0,0,1,1.04.075q1.635,1.6,3.27,3.2a0.656,0.656,0,0,1,0,1.061q-1.635,1.605-3.27,3.2a0.681,0.681,0,0,1-1.03.065,0.647,0.647,0,0,1,.08-1.013C1576.2,219.1,1576.9,218.42,1577.66,217.671Z" transform="translate(-1565 -213)"/></svg></span></a>
			</div>
		</div>
		<div class="dis_p_body dis_singleProduct_bp m_b_30">
			<div class="dis_singleProduct_bpL">
				<!-- ******************** -->
				<div class="dis_productsImg_wrap">
					<div class="swiper mySwiper2 dis_sp_topImg">
						<div class="swiper-wrapper">`;
							if((sp['images']) !==''){ 
								$.each(sp['images'], function (i,v) {
									html +=`<div class="swiper-slide">
										<div class="dis_sp_Minner">
											<a href="${v.src}" class="view">
												<img src="${v.src}" />
											</a>
										</div>
									</div>`;
								}); 
							} 
						html +=`</div>
						<div class="swiper-button-next"></div>
						<div class="swiper-button-prev"></div>
						<ul class="dis_sp_cartView_list">
							<li>
								<a href="#" class="dis_sp_cartView_icon">
									<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="17px" height="17px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M16.088,5.704 C16.085,6.298 15.664,6.726 15.101,6.730 C14.521,6.735 14.099,6.305 14.091,5.694 C14.086,5.268 14.090,4.842 14.090,4.341 C13.963,4.460 13.888,4.527 13.816,4.598 C12.793,5.620 11.769,6.641 10.748,7.667 C10.473,7.944 10.157,8.093 9.765,8.003 C9.379,7.915 9.122,7.676 9.012,7.291 C8.902,6.902 9.017,6.572 9.295,6.294 C10.331,5.255 11.370,4.219 12.407,3.181 C12.477,3.110 12.543,3.035 12.657,2.912 C12.164,2.912 11.732,2.916 11.300,2.911 C10.706,2.903 10.276,2.488 10.270,1.926 C10.265,1.346 10.694,0.917 11.304,0.914 C12.553,0.908 13.802,0.908 15.051,0.914 C15.672,0.917 16.086,1.335 16.088,1.958 C16.093,3.206 16.094,4.455 16.088,5.704 ZM10.739,10.142 C11.359,10.758 11.976,11.378 12.594,11.996 C13.070,12.474 13.547,12.951 14.088,13.493 C14.088,12.945 14.075,12.489 14.091,12.033 C14.111,11.487 14.549,11.081 15.090,11.083 C15.630,11.084 16.075,11.492 16.081,12.040 C16.093,13.340 16.093,14.642 16.081,15.942 C16.075,16.461 15.664,16.889 15.148,16.895 C13.827,16.910 12.505,16.912 11.184,16.893 C10.656,16.885 10.266,16.425 10.268,15.896 C10.271,15.369 10.668,14.933 11.197,14.908 C11.591,14.889 11.987,14.904 12.383,14.901 C12.456,14.900 12.529,14.886 12.698,14.866 C12.542,14.739 12.447,14.673 12.366,14.593 C11.349,13.579 10.332,12.564 9.320,11.546 C8.868,11.092 8.852,10.485 9.268,10.074 C9.684,9.663 10.281,9.688 10.739,10.142 ZM5.003,13.408 C4.526,13.885 4.049,14.362 3.507,14.903 C4.055,14.903 4.511,14.890 4.967,14.906 C5.513,14.925 5.919,15.363 5.917,15.904 C5.916,16.445 5.508,16.890 4.960,16.895 C3.659,16.908 2.358,16.908 1.058,16.895 C0.538,16.890 0.111,16.478 0.105,15.963 C0.089,14.642 0.088,13.320 0.107,11.999 C0.115,11.471 0.575,11.080 1.104,11.083 C1.631,11.085 2.067,11.483 2.092,12.011 C2.111,12.406 2.096,12.802 2.099,13.198 C2.099,13.271 2.114,13.344 2.133,13.512 C2.261,13.357 2.327,13.261 2.407,13.181 C3.421,12.164 4.435,11.147 5.454,10.135 C5.908,9.683 6.515,9.666 6.925,10.082 C7.337,10.499 7.312,11.095 6.857,11.554 C6.242,12.174 5.622,12.790 5.003,13.408 ZM6.476,7.988 C6.087,8.098 5.758,7.982 5.480,7.705 C4.441,6.669 3.404,5.630 2.366,4.593 C2.296,4.523 2.220,4.457 2.097,4.343 C2.097,4.836 2.102,5.268 2.096,5.699 C2.088,6.294 1.674,6.724 1.111,6.729 C0.532,6.735 0.102,6.305 0.099,5.696 C0.094,4.447 0.093,3.198 0.099,1.949 C0.102,1.328 0.520,0.914 1.143,0.912 C2.392,0.906 3.640,0.906 4.889,0.912 C5.483,0.915 5.911,1.336 5.916,1.899 C5.920,2.478 5.490,2.901 4.880,2.909 C4.453,2.914 4.027,2.910 3.526,2.910 C3.645,3.037 3.712,3.112 3.783,3.183 C4.805,4.207 5.827,5.231 6.852,6.252 C7.129,6.527 7.278,6.843 7.189,7.235 C7.100,7.621 6.862,7.878 6.476,7.988 Z"/></svg>
								</a>
								</span>
							</li>
							<li>
								<a href="javascript:;" class="dis_sp_cartView_icon addToWishlist" ${fav}>
									<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="18px" height="16px"><path fill-rule="evenodd" fill="${svg_style}" d="M8.695,2.907 C9.338,1.855 10.169,1.127 11.270,0.760 C13.051,0.168 15.015,0.867 16.142,2.537 C17.237,4.162 17.420,5.919 16.692,7.756 C16.047,9.381 14.952,10.668 13.744,11.860 C12.324,13.263 10.753,14.462 9.087,15.530 C8.856,15.678 8.641,15.750 8.385,15.586 C5.963,14.034 3.703,12.280 1.921,9.943 C1.094,8.859 0.443,7.673 0.293,6.268 C0.019,3.699 1.589,1.119 4.016,0.649 C5.850,0.295 7.299,1.013 8.424,2.512 C8.509,2.626 8.586,2.748 8.695,2.907 Z"/></svg>
								</a>
							</li>
						</ul>
					</div>
					<div thumbsSlider="" class="swiper mySwiper dis_sp_thumbImg">
						<div class="swiper-wrapper">`;
						
							if((sp['images']) !==''){ 
								$.each(sp['images'], function (i,v) {
									html +=`<div class="swiper-slide">
												<div class="dis_sp_Tinner">
													<img src="${v.src}" />
												</div>
											</div>`;
								}); 
							}
							
						html +=`</div>
					</div>
				</div>
				<!-- ******************** -->
			</div>
			<div class="dis_singleProduct_bpR">
				<p class="dis_sp_ttl">${sp.name}</p>
				<div class="dis_sp_brandrating">
					<p class="hideme">Brand : Coca-Cola</p>
					<p>Rated ${sp.average_rating} of 5 
						${sp.rating_star}
					</p>
				</div>
				<ul class="dis_sp_PB">
					<li>
						<p class="dis_sp_mcPice"><del>${DEFAULT_CURRENCY_SYMBOL+sp.regular_price}</del> <span class="dis_sp_PB_price">${DEFAULT_CURRENCY_SYMBOL+sp.sale_price}</span></p>
					</li>
					${discount_tag}
					<li>`;
					
					if(sp.stock_quantity >0){
						html +=`<a class="dis_sp_btn green">IN STOCK</a>`;
					}else{
						html +=`<a class="dis_sp_btn red">OUT OF STOCK</a>`;
					} 
				html +=`</li>
				</ul>`;
				let selectedColor = '';
				if(variants){
					html +=`<ul class="dis_sp_SC">
					<li>
						<div class="dis_sp_select">
							<select class="primay_select dis_field_input filterBy fiterBySize" name="" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
								<!--option value="" data-select2-id="2">Select Size</option-->`;
									$.each(variants, function (i,v) {
										let selected  = '';
										let prod_id = '';
										for (var property in v) {
											prod_id = property;
											if(property == sp.id) {
												selected = 'selected';
											} 
										}
										if(selected !==''){
											selectedColor = getSelectedColor(variants[i]);
										}
										html +=`<option value="${prod_id}" ${selected}>${i}</option>`;
									});
								
							html +=`</select>
						</div>	
						</li>
						${selectedColor}
						<!--li>
							<div class="dis_sp_CWrap">
								<span>Choose Color :</span>
								<ul class="dis_sp_pc">
									<li></li><li></li><li></li><li></li>
								</ul>
							</div>
							
						</li-->
					</ul>`;
				}
				html +=`<p class="dis_spDetails p_t_20 p_b_10">${jQuery(sp.description).text()}</p>
				<ul style="display: none;" class="dis_sp_DL">
					<li> 50% Cotton, 50% Polyester</li>
					<li> Made in the USA or Imported</li>
					<li> Pull On closure</li>
					<li> Machine Wash</li>
				</ul>
				<ul class="dis_sp_QAB">
					<!--li>
						<div>
						<div class="quantity-box">
							<span class="quantity-minus quantity-icon"> - </span>     
							<input type="text" class="quantity" id="single_pro_qty" value="1" min="1">						                                           
							<span class="quantity-plus  quantity-icon"> + </span>
						</div>
						</div>
					</li-->
					<li>
						<a href="javascript:;" class="dis_OrangeBtn dis_GrayBtn" onclick="addToCart(${sp.id},1)">
							<span class="dis_sp_left"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="14px" height="15px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M13.609,4.989 C13.213,6.700 12.816,8.411 12.416,10.121 C12.284,10.688 11.971,10.949 11.431,10.949 C8.898,10.951 6.364,10.951 3.831,10.949 C3.237,10.949 2.905,10.610 2.832,9.973 C2.550,7.505 2.262,5.037 1.976,2.570 C1.965,2.480 1.946,2.393 1.925,2.275 C1.706,2.275 1.510,2.282 1.315,2.273 C0.926,2.256 0.640,1.936 0.647,1.537 C0.655,1.150 0.936,0.843 1.314,0.832 C1.679,0.821 2.043,0.827 2.408,0.829 C2.910,0.830 3.142,1.043 3.213,1.583 C3.317,2.367 3.412,3.152 3.511,3.937 C3.521,4.016 3.538,4.093 3.556,4.202 C3.672,4.202 3.782,4.202 3.891,4.202 C6.938,4.202 9.985,4.202 13.033,4.202 C13.580,4.202 13.741,4.419 13.609,4.989 ZM4.685,11.914 C5.431,11.912 6.039,12.570 6.033,13.371 C6.028,14.158 5.423,14.803 4.689,14.804 C3.944,14.805 3.335,14.147 3.341,13.346 C3.346,12.560 3.952,11.915 4.685,11.914 ZM10.093,11.914 C10.825,11.922 11.423,12.576 11.421,13.364 C11.418,14.163 10.802,14.814 10.055,14.804 C9.324,14.795 8.726,14.141 8.728,13.353 C8.730,12.555 9.347,11.904 10.093,11.914 Z"/></svg></span>	
							Add To Cart
						</a>
					</li>
					<li>
						<a href="" class="dis_OrangeBtn">Buy Now
							<span  class="dis_sp_right"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.311,4.671 12.185,4.671 C8.413,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.091 0.036,3.475 0.534,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.325 8.456,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.207 12.467,3.132 12.397,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.089 11.127,-0.063 11.464,0.266 C12.554,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.173 14.729,4.528 C13.643,5.598 12.554,6.665 11.464,7.730 C11.116,8.069 10.728,8.089 10.430,7.795 C10.130,7.499 10.154,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"/></svg></span>
						</a>
					</li>
				</ul>
				<div id="show_review_after_add"></div>
			</div>
		</div>`;
	}
	
	return html;
}


function getSelectedColor(color){
	colorHtml =`<li>
			<div class="dis_sp_CWrap">
				<span>Choose Color :</span>
					<ul class="dis_sp_pc">`;
					for (var c in color) {
						colorHtml +=`<li class="fiterByColor" data-proid="${c}" style="background:${color[c]}"></li>`;
					}	
			colorHtml +=`</ul>
			</div>
			</li>`;
	return colorHtml; 
	
}

function getDescriptionSection(sp){
	let html ='';
	if(sp){
		html =`<div>
				<p class="dis_sp_ttl">${sp.name}</p>
				<div class="dis_sp_brandrating">
					<p class="hideme">Brand : Coca-Cola</p>
					<p>Rated ${sp.average_rating} of 5 
					${sp.rating_star}
					</p>
				</div>
				<p class="dis_spDetails p_b_10">${jQuery(sp.description).text()}</p>
				<ul class="dis_sp_DL" style="display: none;">
					<li> 50% Cotton, 50% Polyester</li>
					<li> Made in the USA or Imported</li>
					<li> Pull On closure</li>
					<li> Machine Wash</li>
				</ul>
			</div>`;
		$('#review_count').text(sp.rating_count+1);
		$('#average_review').html(`<p>Rated ${sp.average_rating} of 5 </p> ${sp.rating_star}`);
	}	
	
	$('#Description, #reeviews').html(html);
	$('#discription_section').removeClass('hideme');
}	

function getAdditionalDescriptionSection(sp){
	let html ='';
	let result = sp.attributes?.[0]?.['options']?.[0] ? ntc?.name(sp.attributes?.[0]?.['options']?.[0]):'';
	let color = getSelectedColor(sp.attributes?.[0]?.['options']?.[0]);
	let l = sp.dimensions['length'];
	let w = sp.dimensions['width'];
	let h = sp.dimensions['height'];
	if(sp){
		html =`<div>
				<p class="dis_sp_ttl">${sp.name}</p>
				<!-- <div class="dis_sp_brandrating">
					<p class="hideme">Brand : Coca-Cola</p>
					<p>Rated ${sp.average_rating} of 5 
					${sp.rating_star}
					</p>
				</div> -->
				<ul class="dis_sp_DL">
					<li> Color      - ${result[1]} </li>
					<li> Size       - ${sp?.attributes?.[1]?.['options']?.[0]}</li>
					<li> Weight     - ${sp?.weight}</li>
					<li> Dimensions - ${l +' X '+ w + ' X ' + h}</li>
					<li> Shipping   - ${sp.shipping_class}</li>
					<li> SKU 		- ${sp.sku}</li>
				</ul>
			</div>`;
	}	
	
	$('#info').html(html);
	$('#discription_section').removeClass('hideme');
}	

function getSingleProSliderHtml(resData){
	var color		=	resData['color'];
	var title		=	resData['title'];
	var type		=	resData['type'];
	var href		=	resData['href'];
	var autoPlay 	= 	resData['auto'];
	var onerrorImg  = 	resData['errorImg'];
	var prodData 	= 	resData['prodList'];
	var html ='';
	if(prodData.length>0){	
		
			html =`<div class="">
			<div class="dis_sliderheading">
				<h2 class="dis_sliderheading_ttl muli_font">${title}</h2>
				<div style="display:none;" class="dis_sh_btnwrap">
					<a href="https://test.discovered.tv/watch-all?v=top-music" class="dis_sh_btn muli_font">See All<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
					<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
					</svg>
					</span>
					</a>
				</div>
			</div>
			<div>
				<div class="swiper mySwiper">
					<div class="swiper-wrapper">`;
					
						$.each(prodData, function (i) {
							var prod = prodData[i];
							let proImgUrl = '';
							if(prod.images.length){
								let proImg = prod.images[0];
								proImgUrl = proImg.src;
							}
							var style = '';
							var title = 'Add To Wishlist';
							if (user_login_id !== '') {
								if (prod.fav === 1) {
									style = "#fe5501";
									title = 'Remove From Wishlist';
								}else{
									style = "rgb(143, 157, 165)";
								}
							}else{
								style = "rgb(143, 157, 165)";
							}
						let discount_tag = '';
						let meta_d_len = prod.meta_data.length - 1;
						if (prod.meta_data[meta_d_len] !== undefined || prod.meta_data[meta_d_len] !== '') {
							discount_tag = `<span class="dis_product_offTag">${prod.meta_data?.[meta_d_len]?.['value']}% off</span>`;
						}
						html +=`<div class="swiper-slide">
							<div class="dis_product_box">
								<div class="dis_product_img">
									<img src="${proImgUrl}" onerror="this.onerror=null;this.src="${onerrorImg}" class="img-responsive" alt="Product Image">
									<!-- <span class="dis_product_offTag">60% off</span> -->
									${discount_tag}
									<div class="dis_product_overlay">
										<ul class="dis_product_detailsList">
											<li>
												<a href="${prod.single_prod_link}" data-toggle="tooltip" title="View Product" role="tooltip">
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="21px" height="14px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M10.497,4.202 C8.948,4.234 7.718,5.511 7.751,7.053 C7.783,8.596 9.065,9.820 10.614,9.788 C12.139,9.756 13.360,8.516 13.360,6.996 C13.343,5.438 12.061,4.187 10.497,4.202 ZM10.497,0.012 C5.895,-0.009 1.742,2.754 -0.000,6.996 C1.742,11.237 5.896,14.001 10.498,13.979 C15.100,14.002 19.255,11.238 20.996,6.996 C19.255,2.753 15.099,-0.011 10.497,0.012 ZM10.497,11.651 C7.889,11.676 5.754,9.593 5.726,6.996 C5.767,4.371 7.937,2.277 10.572,2.318 C13.149,2.359 15.228,4.429 15.269,6.996 C15.240,9.593 13.105,11.677 10.497,11.651 Z"></path></svg>
												</a>
											</li>
											<li>
												<a href="javascript:;" data-toggle="tooltip" title="Add To Cart" onclick="addToCart(${prod.id},1)">
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="21px"><path fill-rule="evenodd" fill="rgb(143, 157, 165)" d="M19.214,6.789 C18.646,9.237 18.078,11.685 17.505,14.131 C17.315,14.943 16.867,15.316 16.094,15.317 C12.464,15.319 8.835,15.319 5.205,15.316 C4.354,15.316 3.879,14.832 3.774,13.920 C3.370,10.388 2.957,6.858 2.547,3.327 C2.532,3.199 2.504,3.074 2.474,2.905 C2.160,2.905 1.880,2.915 1.600,2.903 C1.043,2.878 0.633,2.420 0.644,1.849 C0.654,1.295 1.058,0.856 1.599,0.840 C2.121,0.824 2.644,0.834 3.166,0.836 C3.885,0.839 4.218,1.143 4.320,1.915 C4.468,3.037 4.604,4.161 4.747,5.284 C4.761,5.396 4.784,5.506 4.811,5.662 C4.978,5.662 5.134,5.662 5.290,5.662 C9.656,5.662 14.022,5.662 18.389,5.663 C19.172,5.663 19.403,5.974 19.214,6.789 ZM6.428,16.696 C7.497,16.695 8.368,17.636 8.360,18.782 C8.353,19.908 7.486,20.831 6.435,20.832 C5.368,20.834 4.495,19.891 4.502,18.746 C4.510,17.621 5.378,16.698 6.428,16.696 ZM14.177,16.696 C15.226,16.709 16.082,17.643 16.078,18.772 C16.075,19.915 15.192,20.846 14.123,20.832 C13.074,20.819 12.218,19.884 12.221,18.756 C12.224,17.614 13.108,16.683 14.177,16.696 Z"></path></svg>
												</a>
											</li>
											<li>
												<a href="javascript:;" class="addToWishlist" data-state="${prod.fav}" data-uid="${user_login_id}" data-id="${prod.id}" data-toggle="tooltip" title="${title}">
													<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="20px" height="19px"><path fill-rule="evenodd" fill="${style}" d="M10.184,3.466 C10.894,2.263 11.813,1.431 13.029,1.012 C14.995,0.335 17.166,1.134 18.410,3.043 C19.620,4.899 19.822,6.908 19.018,9.007 C18.305,10.864 17.095,12.336 15.762,13.698 C14.193,15.301 12.457,16.671 10.617,17.892 C10.362,18.061 10.124,18.143 9.841,17.955 C7.165,16.182 4.669,14.178 2.700,11.506 C1.787,10.268 1.068,8.913 0.902,7.307 C0.600,4.370 2.333,1.422 5.015,0.885 C7.041,0.481 8.642,1.301 9.884,3.014 C9.979,3.145 10.064,3.284 10.184,3.466 Z"></path></svg>
												</a>
											</li>
										</ul>
										<div class="dis_product_btnwrap">
											<a href="${prod.single_prod_link}" class="dis_black_btn muli_font">Buy Now 
											<span>
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.312,4.671 12.185,4.671 C8.414,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.092 0.036,3.475 0.535,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.326 8.457,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.208 12.467,3.132 12.398,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.088 11.127,-0.063 11.464,0.266 C12.555,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.174 14.729,4.528 C13.643,5.598 12.555,6.665 11.464,7.730 C11.117,8.069 10.729,8.089 10.430,7.795 C10.130,7.499 10.155,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"></path></svg>
											</span>
											</a>
										</div>
									</div>
								</div>
								<div class="dis_product_content">
									<a class="dis_product_ttl muli_font" href="https://test.discovered.tv/watch/Zaj2ZGZ3" title="test">${prod.name}</a>
									<p class="dis_product_des">Dient Crewneck Tees for consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat.</p>
									${prod.rating_star}
									<h2 class="dis_product_price mp_0"><span class="dis_cutPrice">${DEFAULT_CURRENCY_SYMBOL+prod.regular_price}</span>${DEFAULT_CURRENCY_SYMBOL+prod.sale_price}</h2>
								</div>
							</div>
						</div>`;
						
					});	
                html +=`</div>
					<!-- <div class="swiper-button-next"></div>
					<div class="swiper-button-prev"></div> -->
				</div>
			</div>
		</div>`;
	}
	return html;	
}

function proSwiperSliders(){
	if($('.dis_productsImg_wrap').length > 0){
		var swiper = new Swiper(".dis_productsImg_wrap .mySwiper", {
			loop: true,
			spaceBetween: 10,
			slidesPerView: 4,
			freeMode: true,
			watchSlidesProgress: true,
		});
		var swiper2 = new Swiper(".dis_productsImg_wrap .mySwiper2", {
			loop: true,
			spaceBetween: 10,
			navigation: {
			nextEl: ".swiper-button-next",
			prevEl: ".swiper-button-prev",
			},
			thumbs: {
			swiper: swiper,
			},
		});
	}
	if($('.dis_sp_sliderwrap').length > 0){
		var swiper = new Swiper(".mySwiper", {
			loop: true,
			spaceBetween: 10,
			slidesPerView: 4,
		});	
	}
	
	if($('[data-toggle="tooltip"]').length > 0){				
		$('[data-toggle="tooltip"]').tooltip();
	}
	
	if ($('[data-target="select2"]').length) {
		$('[data-target="select2"]').each(function () {
			var options = eval("[" + $(this).attr("data-option") + "]");
			if ($.isPlainObject(options[0])) {
				options[0] = $.extend({}, options[0]);
			}
			// console.log(options);
			$(this)[$(this).attr("data-target")].apply($(this), options);
		});
	}
	
	if($('.view').length > 0){
		$('.view').magnificPopup({
			type: 'image',
			mainClass: 'mfp-with-zoom', 			  
			gallery: {
				enabled: true
			},
			zoom: {
				enabled: true, 
				duration: 300, 
				easing: 'ease-in-out', 
			}
		});
	}
}
			
/*************************Single Product End************************************/


/*******************************AddToCart Start*********************************/
	function addToCart(prodId,qty){
		
		if(qty == null){
			qty = $('#single_pro_qty').val();
		}
		if(qty < 1){
			Custom_notify('error','Invalid Quantity');
			return false;
		}
		
		var formData = new FormData();
		formData.append("prod_id", prodId); 
		formData.append("qty", qty); 
		manageMyAjaxPostRequestData(formData, base_url+'store/addToCartAjax').done(function(resp){
			//resp = JSON.parse(resp);
			if(resp['status'] == 1){
				getCartItemsHtml(resp.cart);
				Custom_notify('success',resp['message']);
			}else{
				Custom_notify('error',resp['message']);
			}
		});
	}
	
	
	
	
	function getCartItemsHtml(cart){
		var cartItems   = cart['cartItems'];
		var totalItems  = cart['TotalItem'];
		var TotalAmount = cart['amount'];
		var html =``;
		if(!jQuery.isEmptyObject(cartItems)){
			$('#wishlist_count').text(totalItems);
			$('#wishlist_count').show();
			console.log(totalItems);
			$.each(cartItems, function (i,v) {
				html +=`<div class="dis_cart_ttlBox">
							<div class="dis_cart_rmv">
								<span class="dis_productRemove dis_cross_sign removeCartItem" data-prorow-id="${v.rowid}" ></span>
							</div>
							<div class="dis_cart_img">
								<img src="${v.image}">
							</div>
							<div class="dis_cart_des">
								<p class="dis_sp_ttl">${v.name}</p>
								<p class="discart_pc">${DEFAULT_CURRENCY_SYMBOL+v.price} x ${v.qty}</p>
							</div>  
						</div>`;
			});
			
			$('#cart_footer').removeClass('hideme');
			$('#cart_subtotal').text('Sub-Total : '+DEFAULT_CURRENCY_SYMBOL+TotalAmount);
			console.log('success');
		}else{
			$('#wishlist_count').hide();
			html =`<div class="dis_cart_ttlBox">
						<div class="dis_cart_des">
							<p class="dis_sp_ttl">Cart Is Empty</p>
						</div>  
					</div>`;
			$('#cart_footer').addClass('hideme');
			$('#cart_subtotal').text('Sub-Total : '+DEFAULT_CURRENCY_SYMBOL+TotalAmount);
		}
		$('#cartItems').html(html);
	}

	function removeCartItem(proRowid){
		var formData = new FormData();
		formData.append("proRowid", proRowid);
		manageMyAjaxPostRequestData(formData, base_url+'store/remove_ajax_cartitem').done(function(resp){
			if(resp['status'] == 1){
				getCartItemsHtml(resp.cart);
				viewCartPage(resp.cart);
				Custom_notify('success',resp['message']);
			}else{
				Custom_notify('error',resp['message']);
			}
		});
	}
	
	function minusQty(rowid,qty){
		var formData = new FormData();
		formData.append("proRowid", rowid);
		formData.append("qty", qty);
		manageMyAjaxPostRequestData(formData, base_url+'store/minus_ajax_cart').done(function(resp){
			if(resp['status'] == 1){
				getCartItemsHtml(resp.cart);
				viewCartPage(resp.cart);
				Custom_notify('success',resp['message']);
			}else{
				Custom_notify('error',resp['message']);
			}
		});
	}	
	
	
	function plusQty(rowid,qty,proid){
		var formData = new FormData();
		formData.append("proRowid", rowid);
		formData.append("qty", qty);
		formData.append("proid", proid);
		manageMyAjaxPostRequestData(formData, base_url+'store/plus_ajax_cart').done(function(resp){
			if(resp['status'] == 1){
				getCartItemsHtml(resp.cart);
				viewCartPage(resp.cart);
				Custom_notify('success',resp['message']);
			}else{
				Custom_notify('error',resp['message']);
			}
		});
	}	

	function plusQtyddd(proid,qty,optionValue){
		
		$.ajax({ 
			url 		: BASE_URL + "front/add_ajax_cart",
			type 		: "POST",
			data 		: { productid : proid, qty : qty },
			success 	: function(data) {
				if(data == "false"){
					toastr.error('Out Of Stock', '');
				}else{
					$("#cart tbody").html(data);
					onloadCart();
				}	
				
			  },
			error 	: function(data) {
					
					// alert('refresh'); 
			  }
		});
	}
	
	function loadMyCart(){
		var formData = new FormData();
		formData = {};
		manageMyAjaxPostRequestData(formData, base_url+'store/loadMyCart').done(function(resp){
			if(resp['status'] == 1){
				getCartItemsHtml(resp.cart);
				viewCartPage(resp.cart);
				shippingPageCart(resp.cart);
			}
		});
	}
	
	$(document).on('click','.removeCartItem',function(e){
		$('.cart_ddtoggle').addClass('active');
		let proRowid =  $(this).attr('data-prorow-id');
		removeCartItem(proRowid);
	});
	
	
	if($('#view_cart_page').length > 0 || $('#shipping_page_cart').length > 0){
		loadMyCart();
	}
	
	$(document).ready(function () {
		loadMyCart();
	});
	
	function viewCartPage(cart){
		if($('#view_cart_page').length > 0){
			var cartItems   = cart['cartItems'];
			var totalItems  = cart['TotalItem'];
			var TotalAmount = cart['amount'];
			var html =``;

			if(!jQuery.isEmptyObject(cartItems)){
				$.each(cartItems, function (i,v) {
					html +=`<tr>
							<td>
								<span class="dis_productRemove dis_cross_sign removeCartItem" data-prorow-id="${v.rowid}"></span>
							</td>
							<td>
								<div class="dis_cart_ttlBox">
									<div class="dis_cart_img">
										<img src=${v.image}" />
									</div>
									<div class="dis_cart_des">
										<p class="dis_sp_ttl">${v.name} `; 
										
										if(v.options['stock_quantity'] > 0){
											html +=`<span class="dis_sp_btn p5 green">IN STOCK</span> </p>`;
										}else{
											html +=`<span class="dis_sp_btn p5 red">OUT OF STOCK</span> </p>`;
										}
										
									html +=`<div class="dis_sp_brandrating">
											<p class="hideme">Brand : Coca-Cola</p>
											<p>Rated ${v.options['rating']} of 5 
											${v.options['rating_star']}
											</p>
										</div>
									</div>  
								</div>
							</td>
							<td class="text-center"><span class="dis_Tetxt">${DEFAULT_CURRENCY_SYMBOL+v.price}</span></td>
							<td class="text-center">
								<div class="quantity-box">
									<span class="quantity-minuss quantity-icon" onclick="minusQty('${v.rowid}',${v.qty})"> - </span>     
									<input type="text" class="quantity" value="${v.qty}">						                                           
									<span class="quantity-pluss  quantity-icon" onclick="plusQty('${v.rowid}',${v.qty}, ${v.id})"> + </span>
								</div>
							</td>
							<td class="text-center"><span class="dis_Tetxt">${DEFAULT_CURRENCY_SYMBOL+v.subtotal}</span> </td>
						</tr>`;
				
				});
				$('#checkout_btn').removeClass('hideme');
			}else{
				html =`<tr><td class="text-center" colspan="5">Cart Is Empty </td></tr>`;
				$('#checkout_btn').addClass('hideme');
			}
			
			let cartPageSubTotal =`<li>
									<p>Sub-Total</p>
									<p>${DEFAULT_CURRENCY_SYMBOL+TotalAmount}</p>
								</li>`;
								
			let cartPageTotal =`<li>
								<p>Total</p>
								<p>${DEFAULT_CURRENCY_SYMBOL+TotalAmount}</p>
								</li>`;
			$('#cart_page_subtotal').html(cartPageSubTotal);
			$('#cart_page_total').html(cartPageTotal);
			$('#view_cart_page').html(html);
		}
	}
	
	
	function shippingPageCart(cart){
		if($('#shipping_page_cart').length > 0){
			var cartItems   = cart['cartItems'];
			var totalItems  = cart['TotalItem'];
			var TotalAmount = cart['amount'];
			var html =``;

			if(!jQuery.isEmptyObject(cartItems)){
				$.each(cartItems, function (i,v) {
					html +=`<li>
								<p class="dis_ctl_ttl">${v.name} <span>Quantity - ${v.qty}</span> </p>
								<p class="dis_ctl_ttl">${DEFAULT_CURRENCY_SYMBOL+v.price}</p>
							</li>`;
				
				});
				$('#checkout_btn').removeClass('hideme');
			}else{
				html =`<li>
							<p class="dis_ctl_ttl">Cart Is Empty</p>
						</li>`;
				$('#checkout_btn').addClass('hideme');
			}
			
			let cartPageSubTotal =`<li>
									<p>Sub-Total</p>
									<p>${DEFAULT_CURRENCY_SYMBOL+TotalAmount}</p>
								</li>`;
								
			let cartPageTotal =`<li>
								<p>Total</p>
								<p>${DEFAULT_CURRENCY_SYMBOL+TotalAmount}</p>
								</li>`;
			$('#ship_page_subtotal').html(cartPageSubTotal);
			$('#ship_page_total').html(cartPageTotal);
			$('#shipping_page_cart').html(html);
		}
	}
	
	
	

/*******************************AddToCart End***********************************/


/*******************************Order js start***********************************/
$(document).on('submit','#add_shipping_address',function(e){
	e.preventDefault();
	var _this = $(this);
	let url = _this.attr('action');
	if(checkRequire(_this) == 0){
		var cate = [];
		$.each($("#product_cats_checked option:selected"), function(){            
            cate.push($(this).val());
        });
		var formData = new FormData(_this[0]);
		//formData.append('product_cats',cate);
		var btnTxt = _this.find('button[type="submit"]').text();
		_this.find('button[type="submit"]').text(btnTxt+' ')
        .append(' <i class="fa fa-spinner fa-spin post_spinner"></i>')
        .prop("disabled", true);
		manageMyAjaxPostRequestData(formData, base_url+url).done(function(resp){
			if(resp['status'] == 1){
				// Custom_notify('success',resp['message']);
				redirect(resp['redirect']);
			}
			
		}).fail(function(){
			_this.find('button[type="submit"]').text(btnTxt).prop("disabled", false);
		});
	}
});

/*******************************Order js End***********************************/


/********************* AddAndRemoveFromWishlist js start***********************/
var target_obj =  '';
$(document).on('click','.addToWishlist',function(e){
	target_obj = $(this);
	AddRemoveFromWishlist(target_obj);
});
/******************** AddAndRemoveFromWishlist js End**************************/


/*********************** RemoveFromWishlist js start****************************/
$(document).on('click','.un_wish',function(e){
	if ($('#wishlist_page').val() === 'true') {
		$('.cart_ddtoggle').removeClass('active');
		$('#total_wish_items').text($('#total_wish_items').text() - 1);
	}else{
		$('.cart_ddtoggle').addClass('active');
	}

	target_obj = $(this);
	AddRemoveFromWishlist(target_obj);
	$('#'+target_obj.data('id')+'.wish_item').remove();

	if ($('#wishlist_append').is(':empty')){
		$('#wishlist_append').html('<div class="dis_cart_ttlBox"><div class="dis_cart_des"><p class="dis_sp_ttl">Wishlist Is Empty</p></div></div>');
		$('#wishlist_footer').hide();
	}
	if ($('#wishlist_tbody').is(':empty')){
		$('#wishlist_tbody').html('<tr><td class="text-center" colspan="5">Wishlist Is Empty </td></tr>');
	}
});
/************************* RemoveFromWishlist js End******************************/

function show_wishlist() { 
	$.ajax({
		type: "POST",
		url: base_url+'store/get_favorite',
		data: {'user_id': user_login_id},
		beforeSend: function() {
			$('#wishlist_append').html(`<div class="dis_skeleton">
			<div class="dis_skeleton_left">
				<div class="dis_skeletonCircle"></div>
			</div>
			<div class="dis_skeleton_right">
				<div class="dis_skeleton_line"></div>
				<div class="dis_skeleton_line"></div>
			</div>
			</div><div class="dis_skeleton">
			<div class="dis_skeleton_left">
				<div class="dis_skeletonCircle"></div>
			</div>
			<div class="dis_skeleton_right">
				<div class="dis_skeleton_line"></div>
				<div class="dis_skeleton_line"></div>
			</div>
			</div>`);
		},
		success: function (response) {
			let parse_data = jQuery.parseJSON(response);
			if (parse_data.status === 1) {
				let html  = '';
				var stock_color = '';
				var stock_text = '';
				parse_data.data_arr.forEach(val => {
					if (val['stock_status'] == 'instock') {
						stock_color = 'green';
						stock_text = 'IN STOCK';
					}else{
						stock_color = '';
						stock_text = 'Out Of Stock';
					}
					html += `<div class="dis_cart_ttlBox wish_item" id="${val['id']}"><div class="dis_cart_rmv"><span data-state="1" data-uid="${user_login_id}" data-id="${val['id']}" class="dis_productRemove dis_cross_sign un_wish"></span></div><div class="dis_cart_img"><img src="${val['images'][0]['src']}"></div><div class="dis_cart_des"><p class="dis_sp_ttl">${val['name']}</p><p class="discart_pc">${val['price_html']+' '} <span class="dis_sp_btn ${stock_color}">${stock_text}</span></p></div></div>`;
			});
				$('#wishlist_append').html(html);
				$('#wishlist_footer').show();
			}else{
				$('#wishlist_append').html('<div class="dis_cart_ttlBox"><div class="dis_cart_des"><p class="dis_sp_ttl">Wishlist Is Empty</p></div></div>');
				$('#wishlist_footer').hide();
			}
		}
		
	});
 }

 
function findAllWishlistProducts(prod_id, state, color) {
	if (prod_id !== '' && state !== '' && color !== '') {
		$('.addToWishlist').each(function() {
			if ($(this).data('id') === prod_id) {
				$(this).find('path').css('fill', color);
				$(this).data('state', state);
			}
		});
	}
}

function AddRemoveFromWishlist(target_obj) { 
	var msg = '';
	var msg_type = '';
	var prod_id = '';
	var state = '';
	var color = '';
	if (user_login_id !== '') {
		if ($(target_obj).data('state') === 0) {
			msg = 'Added To Wishlist';
			msg_type = 'success';
			state = 1;
			prod_id = $(target_obj).data('id');
			color = '#fe5501';
		}
		else if ($(target_obj).data('state') === 1){
			msg = 'Removed From Wishlist';
			msg_type = 'success';
			state = 0;
			prod_id = $(target_obj).data('id');
			color = 'rgb(143, 157, 165)';
		}
	}else{
		msg_type = 'error'
		msg = 'You need to login';
	}
	$('#fav').val(`data-state="${state}" data-uid="${user_login_id}" data-id="${prod_id}"`);
	$('#svg_style').val(color);
	findAllWishlistProducts(prod_id, state, color);
	Custom_notify(msg_type, msg);

	$.ajax({
		type: "POST",
		url: base_url+'store/add_favorite',
		data: {'user_id': $(target_obj).data('uid'), 'prod_id': $(target_obj).data('id')},
		success: function (response) {
			
		}
	});
 }
 
 $(document).ready(function () {
	if ($('#wishlist_page').val() === 'true') {
		getFavArray();
	}
	if ($('#my_orders_page').val() === 'true') {
		get_my_orders();
	}
 });

 function get_my_orders(start = 0) { 
	$.ajax({
		type: "POST",
		url: base_url+'store/get_my_orders',
		data: {'user_id': user_login_id, 'start': start},
		beforeSend: function() {
			$('#orders_tbody').append(`<tr id="loader"><td class="text-center" colspan="9">
			<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>
			<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>
			<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>
			</td></tr>`);
		},
		success: function (response) {
			if (response.type == 'success_orders') {
				
				let html = '';
				let load_html = '';
				let customer_req = '-';
				let array = [];
				let reason = '';
				$.each(response.my_orders, function (i,v) {
					start += 1;
					const d = new Date(v.date_created);
					// let date_format = d.toDateString() + " " + d.toLocaleTimeString();
					let date_format = d.toDateString();
					if (v.parent_id !== 0) {
						$.each(v.line_items, function (j,u) { // v.currency_symbol
							$.each(u.meta, function (k,w) { // v.currency_symbol
								if (w.key == 'customer_req') {
									array = w.value.split(',');
									reason = array[1];
									if (array[0] == 0) {
										customer_req = '-';
									}else if (array[0] == 1) {
										customer_req = 'Cancel';
									}else if (array[0] == 2) {
										customer_req = 'Return';
									}
								}
							});
							html +=`<tr>
									<td><div class="dis_cart_ttlBox"><div class="dis_cart_img"><img src=${u.prod_image}" /></div></div></td>
									<td class="text-center"><span class="dis_Tetxt">${u.name}</span></td>
									<td class="text-center"><span class="dis_Tetxt">${DEFAULT_CURRENCY_SYMBOL + u.total}</span></td>
									<td class="text-center"><span class="dis_Tetxt">${v.order_key}</span></td>
									<td class="text-center"><span class="dis_Tetxt">${u.quantity}</span> </td>
									<td class="text-center"><span class="dis_Tetxt">${date_format}</span> </td>
									<td class="text-center"><span class="dis_Tetxt">${v.status}</span> </td>
									<td class="text-center"><span class="dis_Tetxt">${customer_req}</span> </td>
									<td class="text-center"><span class="dis_Tetxt"><i data-ord_id="${v.id}" data-ord_reason="${reason}" data-ord_status="${v.status}" data-ord_prod_id="${u.id}" data-ord_img="${u.prod_image}" data-ord_name="${u.name}" data-ord_customer_req="${customer_req}" data-ord_status="${v.status}" data-ord_date="${date_format}" data-ord_name="${u.quantity}" data-ord_key="${v.order_key}" data-ord_price="${DEFAULT_CURRENCY_SYMBOL + u.total}" data-toggle="modal" data-target="#order_modal" class="fa fa-fw fa-eye show_detail_order"></i></span> </td>
								</tr>`;
						
						});
					}
				});
				$('#loader').remove();
				$('#orders_tbody').append(html);

				if (response['my_orders'].length >= 3) {
					load_html = `<div class="mp_0 m_b_20">
								<a onclick="get_my_orders(${start})" class="dis_sh_btn muli_font">Load More<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
								<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
								</svg></span></a>
							</div>`;
				}
				$('#load_orders_btn').html(load_html);
			}
			else if(response.type == 'empty_orders'){
				$('#loader').remove();

				if ($('#orders_tbody').is(':empty')) { 
					$('#orders_tbody').append('<tr><td class="text-center" colspan="9">You Have Made No Orders Yet.. </td></tr>');
				} else{
					$('#load_orders_btn').html('');
					$('#orders_tbody').append('<tr><td class="text-center" colspan="9">No More Orders.. </td></tr>');
				}
			  
			}
		}
	});
  }

$('#order_modal').on('hidden.bs.modal', function () {
    $(this).find('form').trigger('reset');
	// $('#action').html('');
	$('#ord_total').text('');
	$('#order_key').text('');
	
})

function get_single_order_arr(order_id) { 
	let ret = ''
	let ids = '';
	let html = '';
	$.ajax({
		type: "POST",
		url: base_url+"store/get_order_by_id",
		data: {'order_id': order_id},
		beforeSend: function() {
			$('#order_action_items').html(`
			<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>
			<div class="dis_skeleton"><div class="dis_skeleton_left"><div class="dis_skeletonCircle"></div></div><div class="dis_skeleton_right"><div class="dis_skeleton_line"></div><div class="dis_skeleton_line"></div></div></div>
			`);
		},
		success: function (response) {
			if (response.type == 'order_data_1') {
				ret = response.single_sub_order;
				$.each(ret[0]['line_items'], function (i, v) { 
					 ids += v['id'] + ',';
					// for modal's product data
					html += `<div class="dis_cart_ttlBox">
					<div class="dis_cart_img">
						<img src="${v['prod_image']}">
					</div>
					<div class="dis_cart_des">
						<p class="dis_sp_ttl">${v['name']}</p>
						<p class="discart_pc">${DEFAULT_CURRENCY_SYMBOL + v['price']} x ${v['quantity']}</p>
					</div> 
					<div class="dis_cart_des" style="margin-left: auto;">
						<p class="dis_sp_ttl"> &nbsp;</p>
						<p class="discart_pc">${DEFAULT_CURRENCY_SYMBOL + v['total']}</p>
					</div> 
				</div>`;
				});
				$('#order_key').text(ret[0]['order_key']);
				$('#ord_total').text(DEFAULT_CURRENCY_SYMBOL +  ret[0]['total']);
				$('#ord_prod_id').val(ids);
				$('#order_action_items').html(html);
			}else{
				ret = 0;
				alert('No data available');
			}
		},
		error: function (){
			alert('something went wrong');
		}
	});
 }

$(document).on('click','.show_detail_order', function(e){
	let id = $(this).attr('data-ord_id');
	get_single_order_arr(id);

	let prod_id = $(this).attr('data-ord_prod_id');
	$('#order_id').val(id);
	

	let html = '';
	let status = $(this).attr('data-ord_status');
	if (status === 'completed') {
		html = `<option id="op_return" value="2" data-select2-id="262" selected>Return</option>`;
	}else if(status === 'pending' || status === 'processing' || status === 'on-hold'){
		html = `<option id="op_cancel" value="1" data-select2-id="261" selected>Cancel</option>`;
	}

	$('#action').html(html);

	let reason = $(this).attr('data-ord_reason');
	if (reason !== '0') {
		$('#action_reason').val(reason);
		$('#action_reason').prop('disabled', true);
		$('#order_action_btn').prop('disabled', true);
		$('#order_action_btn').hide();
	}else{
		$('#action_reason').val('');
		$('#action_reason').prop('disabled', false);
		$('#order_action_btn').prop('disabled', false);
		$('#order_action_btn').show();
	}

});

// $('#order_action_form').submit(function(e){
$(document).on('submit','#order_action_form', function(e){
	e.preventDefault();
	let flag = 0;
	let action_reson = $('#action_reason').val();
	if (action_reson == '') {
		flag += 1;
		Custom_notify('error', 'Please enter reason');
	}
	else if (action_reson.length <= 15) {
		flag += 1;
		Custom_notify('error', 'Please Provide a Brief Reason');
	}
	if ($('#action').val() == 'empty') {
		flag += 1;
		Custom_notify('error', 'Please select an action');
	}
	if (flag == 0) {
		Custom_notify('success', 'Your action is updated');
		$.ajax({
			type: "POST",
			processData: false,
			url: base_url+"store/update_order_meta",
			data: $(this).serialize(),
			success: function (response) {
				if (response.type == 'order_update_1') {
					$('#orders_tbody').html('');
					get_my_orders();
					$('#order_modal').modal('hide');
				}else{
					Custom_notify('error', 'Something went wrong');
				}
			}
		});
	}
})

function get_order_items(id) { 
	
 }

 function getFavArray() { 
	$.ajax({
		type: "POST",
		data: {'user_id': user_login_id},
		url: base_url+'store/get_favorite',
		beforeSend: function() {
			$('#wishlist_tbody').html(`<tr><td class="text-center" colspan="5"><div class="dis_skeleton">
			<div class="dis_skeleton_left">
				<div class="dis_skeletonCircle"></div>
			</div>
			<div class="dis_skeleton_right">
				<div class="dis_skeleton_line"></div>
				<div class="dis_skeleton_line"></div>
			</div>
			</div><div class="dis_skeleton">
			<div class="dis_skeleton_left">
				<div class="dis_skeletonCircle"></div>
			</div>
			<div class="dis_skeleton_right">
				<div class="dis_skeleton_line"></div>
				<div class="dis_skeleton_line"></div>
			</div>
		</div></td></tr>`);
		},
		success: function (response) {
			let parse_data = jQuery.parseJSON(response);
			if (parse_data.status === 1) {
				let html  = '';
				var stock_color = '';
				var stock_text = '';
				var total_wish_items = parse_data.data_arr.length ;
				$('#total_wish_items').text(total_wish_items);
				parse_data.data_arr.forEach(val => {
					if (val['stock_status'] == 'instock') {
						stock_color = 'green';
						stock_text = 'IN STOCK';
					}else{
						stock_color = '';
						stock_text = 'Out Of Stock'; 
					}
					html += `<tr class="wish_item" id="${val['id']}"> <td> <span data-state="1" data-uid="${user_login_id}" data-id="${val['id']}" class="dis_productRemove dis_cross_sign un_wish"></span> </td> <td> <div class="dis_cart_ttlBox"> <div class="dis_cart_img"> <img src="${val['images'][0]['src']}" /> </div> <div class="dis_cart_des"> <p class="dis_sp_ttl">${val['name']}</p> <div class="dis_sp_brandrating"> <p>Brand : ${val['store_name']}</p> <p>Rated ${val['rounded_rating']} of 5 <ul class="dis_pro_rating"> ${val['rating_star_html']} </ul> </p> </div> </div> </div> </td> <td class="text-center"><span class="dis_Tetxt"> $${val['price']}</span></td> <td class="text-center"> <span class="dis_sp_btn p5 ${stock_color}">${stock_text}</span> </td> <td class="text-center"><span style="cursor: pointer;" class="dis_sp_btn p5 green" onclick="addToCart(${val['id']},1)">Add to Cart</span> </td> </tr>`;
			});
				$('#wishlist_tbody').html(html);
			}else{
				$('#wishlist_tbody').html('<tr><td class="text-center" colspan="5">Wishlist Is Empty </td></tr>');
			}
		}
	});
  }

// $(document).on('click','.rate_star',function(e){
// 	let rate = $(this).data('rate');
// 	$('#prod_rating_post').val(rate);
// 	let i = 0;
// 	while (i < 5) {
// 		if (i < rate ) {
// 			$(".rate_star").eq(i).addClass("active");
// 		}else{
// 			$(".rate_star").eq(i).removeClass("active");
// 		}
// 		i++;
// 	}
// });

$(document).on('mouseenter', '.rate_star', function() {
	let rate = $(this).data('rate');
	$('#prod_rating_post').val(rate);
	let i = 0;
	while (i < 5) {
		if (i < rate ) {
			$(".rate_star").eq(i).addClass("active");
		}else{
			$(".rate_star").eq(i).removeClass("active");
		}
		i++;
	}
});

$(document).ready(function () {
	$('#review_from').submit(function(e){
		$('#prod_id_post').val($('#prod_id').val());
		e.preventDefault();
		let flag = 0;
		if (user_login_id !== '') {
			if ($('#prod_rating_post').val() == '') {
				Custom_notify('error', 'Please select a rating');
				$('#rating_star').css('color','red');
				flag += 1;
			}
			if ($('#prod_review_post').val() == '') {
				Custom_notify('error', 'Review field is required');
				$('#prod_review_post').css('border-color','red');
				$('#review_star').css('color','red');
				flag += 1;
			}
		}else{
			flag += 1;
			Custom_notify('error','Please login to review !');
		}
	
		if (flag === 0) {

			$.ajax({
				type: "POST",
				url: e.target.action,
				data: new FormData(this),
				processData: false,
				contentType: false,
				cache: false,
				success: function (data) {
					let msg_type = '';
					let msg = '';
					if (data['status'] === 1) {
						// location.href('#customer_reviews');
						$('.dis_pro_rewUserList').empty();
						var getMeTo = document.getElementById("reviews");
    					getMeTo.scrollIntoView({behavior: 'smooth'}, true);
						msg_type = 'success';
						msg = 'Review Added Successfully';
						// formReset();
						getProReviewHtml(data['prod_id']); // createProductReview
					}else{
						msg_type = 'error';
						msg = data['message'];
					}
					Custom_notify(msg_type, msg);
				}
			});
		}else{
			console.log(0);
		}
	});
});

function formReset() { 
	$(".rate_star").removeClass("active");
	$('#rating_star').css('color','rgb(143, 157, 165)');
	$('#prod_review_post').css('border-color','rgb(43, 57, 65)');
	$('#review_star').css('color','rgb(143, 157, 165)');
	$('#prod_rating_post').val('');
 }

function getProReviewHtml(prod_id, start = 0) { 
	formReset();
	$.ajax({
		type: "POST",
		url: base_url+'store/getProductReviews',
		data: {'prod_id': prod_id, 'start': start},
		success: function (data) {
			let rev_html = proReviewHtml(data['reviews'], start);
			// $('#show_more_rev').remove();
			$('.dis_pro_rewUserList').append(rev_html);
			if (data['reviewer'] !== 0) {
				$('#review_form').hide();
				$('#review_from_btn').attr('onclick', '');
			}else{
				$('#review_form').show();
				$('#review_from_btn').attr('onclick', 'submitReviewForm()');
			}
		}
	});
 }

 function submitReviewForm() { 
	$('#review_from').submit();
  }

 function proReviewHtml(review_arr, start) { 
	let html = '';
	let prod_id = '';
	if (review_arr.length > 0) {
		review_arr.forEach(rev_obj => {
			start += 1;
			let date = moment(rev_obj['date_created']);
			let format_date = date.format('MMMM DD YYYY');
			html += `<li data-prod_id="${rev_obj['product_id']}" data-email="${rev_obj['reviewer_email']}" class="dis_pro_rewUser">
			<div class="dis_pro_rewimg">
				<img src="${rev_obj['reviewer_avatar_urls'][24]}">
			</div>
			<div class="dis_pro_rewData">
				${rev_obj['rating_star_html']}
				<h2 class="dis_pro_rewbane mp_0">${rev_obj['reviewer']} - <span>${format_date}</span></h2>
				${rev_obj['review']}
			</div>
		</li>`;
		prod_id = rev_obj['product_id'];
		});

		if (start == 5) {
			html += `<div id="show_more_rev" class="dis_sh_btnwrap mp_0 m_b_20">
			<a data-prod_id="${prod_id}" data-start="${start}" class="dis_sh_btn muli_font show_more_btn">See More<span class="dis_sh_btnicon"><svg version="1.2" baseProfile="tiny-ps" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 9 14" width="9" height="12">
			<path fill-rule="evenodd" fill="rgb(235 88 31)" id="Arrow" class="shp0" d="M8.41 7C8.41 7.2 8.33 7.4 8.19 7.54L2.12 13.78C1.98 13.92 1.8 14 1.6 14C1.4 14 1.21 13.92 1.07 13.78L0.62 13.32C0.48 13.17 0.41 12.98 0.41 12.78C0.41 12.57 0.48 12.38 0.62 12.23L5.72 7L0.63 1.77C0.34 1.47 0.34 0.98 0.63 0.68L1.08 0.22C1.22 0.08 1.4 0 1.6 0C1.8 0 1.99 0.08 2.13 0.22L8.19 6.45C8.33 6.6 8.41 6.79 8.41 7Z"></path>
			</svg>
			</span>
			</a>
		</div>`;
		}

		$('#empty_reviews').hide();
		$('#not_logged_in').hide();
	}else{
		setTimeout(() => {
			$('#review_count').text(0);
		}, 500);
		$('#empty_reviews').show();
		$('#not_logged_in').hide();
	}if (user_login_id === '') {
		$('#not_logged_in').show();
	}
	return html;
  }


$(document).on('click','.show_more_btn', function(e){ 
	e.preventDefault();
    $(this).remove();
	getProReviewHtml($(this).data('prod_id'), $(this).data('start'));
 });
