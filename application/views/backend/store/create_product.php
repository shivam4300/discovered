<?php 
$p_id = '';
$p_titile = '';
$p_price = '';
$p_sprice = '';
$p_cates = '';
$p_disc = '';
$p_images = '';
$p_sku = '';
$p_qty = '';
$p_stock_status ='';
$p_weight ='';
$p_lenght ='';
$p_width ='';
$p_height ='';
$p_weight ='';
$p_sclass_id ='';
$p_ptime ='';
$p_catIds = [];
$p_tags = '';
$p_tag_name = '';
if(!empty($proDetails)){
	$p_id = $proDetails['id'];
	$p_titile = $proDetails['name'];
	$p_price = $proDetails['regular_price'];
	$p_sprice = $proDetails['sale_price'];
	$p_cates = $proDetails['categories'];
	$p_disc = $proDetails['description'];
	$p_images = $proDetails['images'];
	$p_sku = $proDetails['sku'];
	$p_qty = $proDetails['stock_quantity'];
	$p_stock_status =$proDetails['stock_status'];
	$p_weight =$proDetails['weight'];
	$p_lenght =$proDetails['dimensions']['length'];
	$p_width  =$proDetails['dimensions']['width'];
	$p_height =$proDetails['dimensions']['height'];
	$p_sclass_id =$proDetails['shipping_class_id'];
	$p_attrbutes = $proDetails['attributes'];
	$p_tags  =$proDetails['tags'];
	
	
	if(!empty($p_tags)){
		foreach($p_tags as $t){
			$p_tag_name = $t['name'];
		}
	}
	
	if(!empty($p_cates)){
		foreach($p_cates as $c){
			$p_catIds[]= $c['id'];
		}
	}
	
	$p_attr = [];
	if(!empty($p_attrbutes)){
		foreach($p_attrbutes as $a){
			$p_attr[$a['name']]= $a['options'][0];
		}
	}
	
}


?>
<div class="dash_page_section">
    <div class="dash_addProductWrap">
        <div class="row">
			<form action="backend/store/addEditProducts" method="POST" id="addEditProduct">
				<div class="col-xs-12">
					<div class="">
						<div class="gi_section">
							<h4 class="gi_title">Add Products</h4>
							<div class="gi_inner_box">
								<div class="row">
									<div class="col-lg-6">
										<div class="form-group">
											<label class="input_lble">Product Title</label>
											<input type="text" class="form-control require" placeholder="Enter Product Title" name="pro_title" id="pro_title" value="<?=$p_titile; ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="input_lble">Price </label>
											<input type="number" class="form-control require" placeholder="Enter Price" name="regular_price" id="regular_price" min="0" value="<?=$p_price; ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="input_lble">Sale Price</label>
											<input type="number" class="form-control require" placeholder="Enter Sale Price" name="sale_price" id="sale_price" min="0" value="<?=$p_sprice; ?>">
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="input_lble">Categories</label>
											<div class="dis_multiselectWrap multi-checkbox">
												<select  class="cv form-control require" name="product_cats_checked" id="product_cats_checked" data-error="Please choose categories." data-placeholder="Select categories." multiple="multiple">
													<option value="">Categories</option>
													<?php if(!empty($pro_cats)){ 
															foreach($pro_cats as $list){ 
																$selected = (in_array($list['id'] , $p_catIds)) ? 'selected' : '';
																echo '<option value="'.$list['id'].'" '.$selected.'>'.$list['name'].'</option>';
															}
														}
													?>
												</select>  
											</div>                                    
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="input_lble">Description</label>
											<textarea class="form-control" placeholder="Enter Description.." name="pro_discription" id="pro_discription" ><?=strip_tags($p_disc); ?></textarea>
										</div>
									</div>
									<div class="col-lg-6">
										<div class="form-group">
											<label class="input_lble">Product Image</label>
											<ul class="dis_dashproduct_upload">
												<li>
													<div class="dis_dashProUp_box">
														<input type="file" class="dis_dashProUp_input hide" name="pro_img[]" id="featured_img" multiple>
														<label for="featured_img" class="dis_dashProUp_label">
															<span class="dis_dashProUp_icon">
															<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="40" height="40" viewBox="0 0 512.056 512.056" ><g> <g xmlns="http://www.w3.org/2000/svg"> <g> <g> <path d="M426.635,188.224C402.969,93.946,307.358,36.704,213.08,60.37C139.404,78.865,85.907,142.542,80.395,218.303 C28.082,226.93-7.333,276.331,1.294,328.644c7.669,46.507,47.967,80.566,95.101,80.379h80v-32h-80c-35.346,0-64-28.654-64-64 c0-35.346,28.654-64,64-64c8.837,0,16-7.163,16-16c-0.08-79.529,64.327-144.065,143.856-144.144 c68.844-0.069,128.107,48.601,141.424,116.144c1.315,6.744,6.788,11.896,13.6,12.8c43.742,6.229,74.151,46.738,67.923,90.479 c-5.593,39.278-39.129,68.523-78.803,68.721h-64v32h64c61.856-0.187,111.848-50.483,111.66-112.339 C511.899,245.194,476.655,200.443,426.635,188.224z" fill="#ff815b" data-original="#ff815b" class=""></path> <path d="M245.035,253.664l-64,64l22.56,22.56l36.8-36.64v153.44h32v-153.44l36.64,36.64l22.56-22.56l-64-64 C261.354,247.46,251.276,247.46,245.035,253.664z" fill="#ff815b" data-original="#ff815b" class=""></path> </g> </g> </g></g></svg>
															</span>
															<p class="dis_dashProUp_text">upload your image here</p>
															<p class="dis_dashProUp_text" id="selected_files"></p>
															<!-- <p class="dis_dashProUp_type">(Only *.jpeg and *.png images will be accepted)</p> -->
														</label>
													</div>
												</li>
												<?php if(!empty($p_images)){ 
														$imgCount = sizeof($p_images);
														foreach($p_images as $img){ ?>
													<li>
														<div class="dis_dashProUp_img">
															<span class="dis_dashProUp_innerimg">
																<img src="<?=$img['src'];?>" alt="Prduct image" class="img-fluid">
																<?php if($imgCount>1){ ?>
																<span class="dis_cross_sign removeProImage" data-img-id="<?=$img['id'];?>" data-action-url="<?=base_url();?>backend/store/removeProductImage"></span>
																<?php } ?>
															</span>
														</div>
													</li>
												<?php } } ?>
												<!--li>
													<div class="dis_dashProUp_img">
														<span class="dis_dashProUp_innerimg">
															<img src="https://d38ol7btrdlcn8.cloudfront.net/aud_201/images/1099256328_thumb.jpg" alt="Prduct image" class="img-fluid">
															<span class="dis_cross_sign"></span>
														</span>
													</div>
												</li>
												<li>
													<div class="dis_dashProUp_img">
														<span class="dis_dashProUp_innerimg">
															<img src="https://d38ol7btrdlcn8.cloudfront.net/aud_201/images/1099256328_thumb.jpg" alt="Prduct image" class="img-fluid">
															<span class="dis_cross_sign"></span>
														</span>
													</div>
												</li-->
											</ul>
										</div>
									</div>
									<div class="col-lg-12">
										<div class="form-group">
											<label class="input_lble">Product Group</label>
											<input type="text" class="form-control tokenTag" placeholder="Choose Product Group" name="pro_tags" id="pro_tags" value="<?=$p_tag_name; ?>" data-limit="1">
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					<div class="dis_addpro_ISA">
						<div class="gi_section">                       
							<div class="gi_inner_box">
								<div class="dis_addpro_tabWrap">
									<ul class="dis_addpro_tablst">
										<li class="active"><a data-toggle="tab" href="#attributes">Attributes</a></li>
										<li><a data-toggle="tab" href="#inventory">Inventory</a></li>
										<li><a data-toggle="tab" href="#shipping">Shipping</a></li>
									</ul>
									<div class="tab-content">
										<div id="attributes" class="tab-pane fade in active">            
											<div class="row">
											<?php if(!empty($attributes)){
													foreach($attributes as $list){ 
													$selectedVal = isset($p_attr[$list['name']]) ? $p_attr[$list['name']] : '';
											?> 
												<div class="col-lg-4">
													<div class="form-group">
														<input type="hidden" name="attr[<?=$list['name'];?>][id]"  value="<?=$list['id'];?>">
														<label class="input_lble"><?=$list['name'];?></label>
														<div class="">
															<select  name="attr[<?=$list['name'];?>][options]" id="<?=$list['id'];?>" data-target="select2"  data-option="{closeOnSelect:false,placeholder:' Select option',allowHtml:true,width: '100%'}"  class="form-control require getAttrTerms" data-error="Please choose <?=$list['name'];?>." data-selected="<?=$selectedVal;?>">
																	
															</select>
														</div>  
													</div>
												</div>
											<?php } } ?> 
												<!--div class="col-lg-4">
													<div class="form-group">
														<label class="input_lble">Model</label>
														<div class="">
															<select  name="atrr_modal" id="atrr_modal" data-target="select2"  data-option="{closeOnSelect:false,placeholder:' Select Modal',allowHtml:true,width: '100%'}"  class="form-control require" data-error="Please choose Modal." multiple="multiple">
																	<option value="01">modal1</option>
																	<option value="02">modal2</option>
															</select>  
														</div>  
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label class="input_lble">Size</label>
														<div class="">
															<select  name="atrr_size" id="atrr_size" data-target="select2"  data-option="{closeOnSelect:false,placeholder:' Select Size',allowHtml:true,width: '100%'}"  class="form-control require" data-error="Please choose Size." multiple="multiple">
																	<option value="01">Size1</option>
																	<option value="02">Size2</option>
															</select>  
														</div>  
													</div>
												</div-->
												<!--div class="col-lg-12">
													<div class="form-group">
														<label class="input_lble">Add Attributes</label>
														<div class="dis_ap_addatr_textbtn">
															<ul class="dis_ap_addatr_plus">
																<li>
																	<label class="input_lble">Active?</label>
																	<div class="checkbox_inputs ">
																	<div class="custm_checkbox">
																		<input type="checkbox" id="atrr_active" class="hide_checkbox" name="atrr_active">
																		<label class="backend_checkbox" for="atrr_active"></label>
																	</div>
																	</div>
																</li>
																<li>
																	<label class="input_lble">Name</label>
																	<input type="text" class="form-control" placeholder="Enter Name" name="">
																</li>
																<li>
																	<label class="input_lble">Value(s)</label>
																	<input type="text" class="form-control" placeholder="Enter Value(s)" name="">
																</li>
																<li>
																	<label class="input_lble">Visible on the product page</label>
																	<div class="checkbox_inputs ">
																	<div class="custm_checkbox">
																		<input type="checkbox" id="pro_page" class="hide_checkbox" name="pro_page">
																		<label class="backend_checkbox" for="pro_page"></label>
																	</div>
																	</div>
																</li>
																<li>
																	<div class="addmore_btn">
																		<a class="add_btn">
																			<i class="fa fa-plus plus_btn" aria-hidden="true"></i>
																		</a>
																	</div>
																</li>
															</ul>
															<ul class="dis_ap_addatr_plus">
																<li>
																	<label class="input_lble">Active?</label>
																	<div class="checkbox_inputs ">
																	<div class="custm_checkbox">
																		<input type="checkbox" id="atrr_active2" class="hide_checkbox" name="atrr_active2">
																		<label class="backend_checkbox" for="atrr_active2"></label>
																	</div>
																	</div>
																</li>
																<li>
																	<label class="input_lble">Name</label>
																	<input type="text" class="form-control" placeholder="Enter Name" name="">
																</li>
																<li>
																	<label class="input_lble">Value(s)</label>
																	<input type="text" class="form-control" placeholder="Enter Value(s)" name="">
																</li>
																<li>
																	<label class="input_lble">Visible on the product page</label>
																	<div class="checkbox_inputs ">
																	<div class="custm_checkbox">
																		<input type="checkbox" id="pro_page2" class="hide_checkbox" name="pro_page2">
																		<label class="backend_checkbox" for="pro_page2"></label>
																	</div>
																	</div>
																</li>
																<li>
																	<div class="addmore_btn">
																		<a class="add_btn cut">
																			<i class="fa fa-times cross_btn" aria-hidden="true"></i>
																		</a>
																	</div>
																</li>
															</ul>
														</div>                                                    
													</div>
												</div-->
											</div>
										</div>
										<div id="inventory" class="tab-pane fade">
											<div class="row">
												<div class="col-lg-4">
													<div class="form-group">
														<label class="input_lble">SKU</label>
														<input type="text" class="form-control require" placeholder="Enter SKU" name="pro_sku" id="pro_sku" data-error="Please enter SKU." value="<?=$p_sku; ?>">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label class="input_lble">Stock Qty </label>
														<input type="number" class="form-control require" placeholder="Enter Stock Qty" name="stock_qty" id="stock_qty" min="0" data-error="Please enter product quantity." value="<?=$p_qty; ?>">
													</div>
												</div>
												<div class="col-lg-4">
													<div class="form-group">
														<label class="input_lble">Stock Status</label>
														<div class="">
															<select  name="stock_status" id="stock_status" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Select Stock Status',allowHtml:true,width: '100%'}"  class="form-control require" data-error="Please choose Stock Status." data-error="Please choose stock status.">
																	<option value="instock" <?= ($p_stock_status=='instock') ? 'selected' : ''; ?>>In stock</option>
																	<option value="outofstock" <?= ($p_stock_status=='outofstock') ? 'selected' : ''; ?>>Out of stock</option>
																	<option value="onbackorder" <?= ($p_stock_status=='onbackorder') ? 'selected' : ''; ?>>On backorder</option>
															</select>  
														</div>                                    
													</div>
												</div>
											</div>
										</div>
										<div id="shipping" class="tab-pane fade">            
											<div class="row">
												<div class="col-lg-6">
													<div class="form-group">
														<label class="input_lble">Weight (kg)</label>
														<input type="number" class="form-control require" placeholder="Enter Weight (kg)" name="weight" id="weight" min="0" data-error="Please enter weight." value="<?=$p_weight;?>">
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label class="input_lble">Dimensions (cm) </label>
														<ul class="dis_dimension_col">
															<li>
																<input type="number" class="form-control require" placeholder="Enter Lenght" name="length" id="length" min="0" data-error="Please enter length." value="<?=$p_lenght;?>">
															</li>
															<li>
																<input type="text" class="form-control require" placeholder="Enter Width" name="width" id="width"  data-error="Please enter width." value="<?=$p_width;?>">
															</li>
															<li>
																<input type="text" class="form-control require" placeholder="Enter Height" name="height" id="height"  data-error="Please enter height." value="<?=$p_height;?>">
															</li>
														</ul>
														
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label class="input_lble">Shipping class</label>
														<div class="">
															<select  name="shipping_class" id="shipping_class" data-target="select2" data-option="{closeOnSelect:true,placeholder:' Select Shipping class',allowHtml:true,width: '100%'}"  class="form-control require" data-error="Please choose Shipping class.">
																	<option value="">Shipping Class</option>
																	<?php if(!empty($shipping_classes)){ 
																			foreach($shipping_classes as $list){ 
																			$selected = ($list['id'] == $p_sclass_id) ? 'selected' : '';
																				echo '<option value="'.$list['id'].'" '.$selected.'>'.$list['name'].'</option>';
																			}
																		}
																	?>
															</select> 
														</div>                                    
													</div>
												</div>
												<div class="col-lg-6">
													<div class="form-group">
														<label class="input_lble">Processing Time</label>
														<div class="">
															<select  name="processing_time" id="processing_time" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Select Processing Time',allowHtml:true,width: '100%'}"  class="form-control require" data-error="Please choose Processing Time.">
																	<option value="1">Processing Time1</option>
																	<option value="2">Processing Time2</option>
															</select>  
														</div>                                    
													</div>
												</div>
											</div>
										</div>
										
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="be_btn_wrapper text-right">
						<!--a class="backend_btn m-r-30 resetForm" data-id="#BillingForm">reset</a-->
						<input type="hidden" name="removed_img_id" id="removed_img_id" value="">
						<input type="hidden" name="id" value="<?=$p_id;?>">
						<button class="backend_btn color_green" type="submit" >save</button>
					</div>
				</div>
			</form>
        </div>
    </div>	
</div>