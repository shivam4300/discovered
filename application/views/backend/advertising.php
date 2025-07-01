<?php echo $this->common_html->ConfirmNotificationModal(); ?>
	<div class="dash_page_section">
		<!-- advertising  page start -->
			<div class="dash_advet_wrapper">
				<!-- top section start -->
				<ul class="dash_topadevt_list">					
					<li class="adevt_revenu">
						<div class="adevt_revenu_icon">
							<span class="icon_box"><svg xmlns="https://www.w3.org/2000/svg" xmlns:xlink="https://www.w3.org/1999/xlink" width="10px" height="20px">
							<path fill-rule="evenodd"  fill="rgb(255, 164, 173)"
								d="M5.000,20.000 C5.633,20.000 6.149,19.471 6.149,18.822 L6.149,17.063 L6.383,17.038 C8.445,16.818 10.000,15.057 10.000,12.940 C10.000,10.669 8.189,8.821 5.963,8.821 L4.037,8.821 C3.077,8.821 2.295,8.031 2.295,7.060 C2.295,6.089 3.077,5.300 4.037,5.300 L7.889,5.300 C8.523,5.300 9.038,4.770 9.038,4.119 C9.038,3.470 8.523,2.942 7.889,2.942 L6.149,2.942 L6.149,1.180 C6.149,0.529 5.633,0.000 5.000,0.000 C4.367,0.000 3.853,0.529 3.853,1.180 L3.853,2.939 L3.618,2.964 C1.556,3.182 0.001,4.943 0.001,7.060 C0.001,9.331 1.812,11.179 4.037,11.179 L5.963,11.179 C6.924,11.179 7.706,11.969 7.706,12.940 C7.706,13.911 6.924,14.701 5.963,14.701 L2.111,14.701 C1.479,14.701 0.965,15.230 0.965,15.881 C0.965,16.530 1.479,17.059 2.111,17.059 L3.853,17.059 L3.853,18.822 C3.853,19.471 4.367,20.000 5.000,20.000 Z"/>
							</svg></span>
						</div>
						<div class="adevt_revenu_earning">
							<h2><?php echo (isset($EarningThroughAdvertising))? $currency.round($EarningThroughAdvertising ,3): 0  ; ?></h2>
							<p>Estimated Earning Through Advertising</p>
						</div>
					</li>
					<li class="adevt_revenu bg_blue">
						<div class="adevt_revenu_icon">
							<span class="icon_box"><svg xmlns="https://www.w3.org/2000/svg" width="20px" height="20px">
							<path fill-rule="evenodd"  fill="rgb(144, 207, 255)"
								d="M15.572,19.908 L4.631,19.908 C2.396,19.908 0.578,17.961 0.578,15.567 L0.578,9.739 C0.578,9.252 0.951,8.854 1.410,8.854 C1.869,8.854 2.242,9.252 2.242,9.739 L2.242,15.567 C2.242,16.984 3.314,18.137 4.631,18.137 L15.572,18.137 C16.889,18.137 17.961,16.984 17.961,15.567 L17.961,9.833 C17.961,9.345 18.334,8.948 18.793,8.948 C19.252,8.948 19.625,9.345 19.625,9.833 L19.625,15.567 C19.625,17.961 17.807,19.908 15.572,19.908 ZM13.248,5.544 L10.934,3.062 L10.934,14.204 C10.934,14.692 10.561,15.089 10.102,15.089 C9.643,15.089 9.269,14.692 9.269,14.204 L9.269,3.062 L6.955,5.544 C6.800,5.711 6.590,5.803 6.367,5.803 C6.367,5.803 6.367,5.803 6.366,5.803 C6.142,5.803 5.934,5.711 5.778,5.544 C5.458,5.200 5.458,4.641 5.778,4.296 L9.514,0.290 C9.808,-0.040 10.370,-0.053 10.690,0.290 L14.425,4.296 C14.745,4.641 14.745,5.200 14.426,5.543 C14.127,5.877 13.568,5.886 13.248,5.544 Z"/>
							</svg></span>
						</div>
						<div class="adevt_revenu_earning">
							<h2><?php echo (isset($NumberOfVideoUploaded))? $NumberOfVideoUploaded : 0 ; ?></h2>
							<p>Number Of Videos Uploaded</p>
						</div>
					</li>
					
					<li class="adevt_revenu bg_purple">
						<div class="adevt_revenu_icon">
							<span class="icon_box"><svg xmlns="https://www.w3.org/2000/svg" width="27px" height="22px">
							<path fill-rule="evenodd"  fill="rgb(208, 170, 234)"
								d="M26.205,21.998 L1.759,21.998 C1.329,21.998 0.979,21.627 0.979,21.171 L0.979,0.815 C0.979,0.358 1.329,-0.014 1.759,-0.014 L26.205,-0.014 C26.635,-0.014 26.984,0.358 26.984,0.815 L26.984,21.171 C26.984,21.627 26.635,21.998 26.205,21.998 ZM5.344,1.643 L2.538,1.643 L2.538,5.075 L5.344,5.075 L5.344,1.643 ZM5.344,6.733 L2.538,6.733 L2.538,10.163 L5.344,10.163 L5.344,6.733 ZM5.344,11.821 L2.538,11.821 L2.538,15.252 L5.344,15.252 L5.344,11.821 ZM5.344,16.911 L2.538,16.911 L2.538,20.342 L5.344,20.342 L5.344,16.911 ZM21.061,1.643 L6.904,1.643 L6.904,20.342 L21.061,20.342 L21.061,1.643 ZM25.426,1.643 L22.620,1.643 L22.620,5.075 L25.426,5.075 L25.426,1.643 ZM25.426,6.733 L22.620,6.733 L22.620,10.163 L25.426,10.163 L25.426,6.733 ZM25.426,11.821 L22.620,11.821 L22.620,15.252 L25.426,15.252 L25.426,11.821 ZM25.426,16.911 L22.620,16.911 L22.620,20.342 L25.426,20.342 L25.426,16.911 ZM12.652,7.053 L17.456,10.293 C17.683,10.446 17.817,10.707 17.817,10.992 C17.817,11.277 17.683,11.539 17.456,11.692 L12.654,14.932 C12.524,15.016 12.381,15.060 12.236,15.060 C12.111,15.060 11.987,15.028 11.876,14.966 L11.861,14.959 C11.612,14.812 11.457,14.534 11.457,14.231 L11.457,7.754 C11.457,7.451 11.612,7.172 11.861,7.027 C12.108,6.884 12.412,6.895 12.652,7.053 ZM13.015,12.723 L15.582,10.992 L13.015,9.262 L13.015,12.723 Z"/>
							</svg></span>
						</div>
						<div class="adevt_revenu_earning">
							<h2><?php echo (isset($TotalAdViewCount))? $TotalAdViewCount : 0 ; ?></h2>
							<p>Total Ad Views Count</p>
						</div>
					</li>
					<li class="adevt_revenu bg_orange">
						<div class="adevt_revenu_icon">
							<span class="icon_box"><svg xmlns="https://www.w3.org/2000/svg" width="18px" height="21px">
								<path fill-rule="evenodd"  fill="rgb(255, 210, 155)"
									d="M15.886,7.199 L6.317,1.063 C5.151,0.315 3.739,0.275 2.534,0.964 C1.333,1.649 0.617,2.902 0.617,4.315 L0.617,16.588 C0.617,18.000 1.333,19.253 2.535,19.940 C3.096,20.259 3.724,20.428 4.347,20.428 C5.037,20.428 5.719,20.224 6.317,19.840 L15.886,13.703 C16.985,12.998 17.641,11.782 17.641,10.451 C17.641,9.121 16.985,7.906 15.886,7.199 ZM15.666,10.451 C15.666,11.080 15.357,11.652 14.840,11.984 L5.272,18.121 C4.722,18.472 4.059,18.488 3.495,18.168 C2.928,17.844 2.591,17.254 2.591,16.588 L2.591,4.315 C2.591,3.650 2.928,3.059 3.495,2.736 C3.760,2.584 4.054,2.505 4.346,2.505 C4.670,2.505 4.990,2.601 5.272,2.782 L14.840,8.918 C15.357,9.250 15.666,9.824 15.666,10.451 Z"/>
								</svg></span>
						</div>
						<div class="adevt_revenu_earning">
							<h2><?php echo (isset($AverageEarningPerVideo))? $currency.round($AverageEarningPerVideo,3) : 0  ; ?></h2>
							<p>Estimated Average Earning Per Video</p>
						</div>
					</li>
				</ul>
				<!-- top section End -->
				
				<!-- advertising table section Start -->
				<div class="advet_table_mainwrapper table_area">
					<div class="row">
						<div class="col-md-12">
							<!--div class="table_topbtn">
								<a href="javascript:;" class="table_embedbtn">export</a>
							</div-->
							<div class="table_topmenus">
								<div class="left_menu">
									<div class="tbl_tm_dropdown mode">
										<select  name="mode" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Mode',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter " >
										  <option value="">Mode</option>
										  <option value="1">Music</option>
										  <option value="2">Movies</option>
										  <option value="3">Television</option>
										  <option value="7">Gaming</option>
										</select>
									</div>
									
									<div class="tbl_tm_dropdown tbl_datepicker">
										<input readonly class="daterange" type="text" name="date_range"/>
										<i class="tbl_date_icon fa fa-calendar" aria-hidden="true"></i>
									</div>
									<div class="tbl_tm_dropdown status">
										
										<select  name="privacy_status" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Status',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter" >
										  <option value="">Status</option>
										  <?php $post_status = $this->audition_functions->post_status(); 
												foreach($post_status as $value => $key){
													echo '<option value="'.$value.'">'.$key.'</option>';
												}
										  ?>	
										</select>
									</div>
									<div class="tbl_tm_dropdown mode">
										<select  name="delete_status" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Mode',allowHtml:true,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter " >
										  <option value="0" selected>Active</option>
										  <option value="1"	>Deleted</option>
										</select>
									</div>
									<div class="tbl_tm_dropdown export hide" id="delete_video_btn" data-action-url='dashboard/DeleteBulkChannelVideo' data-type="post_id">
										<a href="javascript:;" class="table_embedbtn">Delete</a>
									</div>
								</div>
								<div class="left_menu">
									<div class="tbl_tm_dropdown export">
										<a href="<?php echo base_url('backend/advertising/advertising_details_export'); ?>" class="table_embedbtn">export</a>
									</div>
									<div class="tbl_tm_dropdown showall">
										<select  name="length" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' All',allowHtml:true,allowClear:false,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter">
										  <option value="">Show All</option>
										  <option value="10" selected>10</option>
										  <option value="20">20</option>
										  <option value="50">50</option>
										  <!--option value="100">100</option-->
										</select>
									</div>
									
									<div class="tbl_tm_dropdown tbl_topsearch">
										<div class="tbl_searchbox">
											<input type="search" placeholder="Search" class="filter" name="search">
											<svg class="search_icon" xmlns="http://www.w3.org/2000/svg" width="16px" height="16px">
											<path fill-rule="evenodd"  fill="rgb(129, 132, 148)"
											 d="M14.626,14.626 C14.077,15.176 13.186,15.176 12.636,14.626 L9.193,11.182 C9.991,10.669 10.669,9.990 11.183,9.193 L14.626,12.636 C15.176,13.186 15.176,14.077 14.626,14.626 ZM5.658,11.286 C2.549,11.286 0.029,8.766 0.029,5.657 C0.029,2.549 2.549,0.029 5.658,0.029 C8.766,0.029 11.286,2.549 11.286,5.657 C11.286,8.766 8.766,11.286 5.658,11.286 ZM5.658,1.436 C3.330,1.436 1.436,3.330 1.436,5.657 C1.436,7.985 3.330,9.879 5.658,9.879 C7.985,9.879 9.879,7.985 9.879,5.657 C9.879,3.330 7.985,1.436 5.658,1.436 ZM3.312,5.657 L2.374,5.657 C2.374,3.847 3.847,2.374 5.658,2.374 L5.658,3.312 C4.365,3.312 3.312,4.364 3.312,5.657 Z"/>
											</svg>
										</div>
									</div>
									
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-12">
							<div class="table_content">
								 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="backend/advertising/show_advertising_details" data-table-position="0" data-target-section="tbody" cellspacing="0" width="100%" data-column-class="[{className: 'srno'},{className: 'preview'},{className: 'title'},{className: 'mode'},{className: 'published'},{className: 'views'},{className: 'ad'},{className: 'earning'},{className: 'status'},{className: 'action'}]"	data-filter='1' data-refresh-dataTablePosition="0" data-orders="[[4,'DESC']]">
									<thead>
										<tr>
											<th class="srno">
												<div class="tbl_checkboxx" >
													<input type="checkbox" id="checkall">
													<label for=""></label>
												</div>
											</th>
											<th class="preview">Preview </th>
											<th class="title">Video Title</th>
											<th class="mode">Mode</th>
											<th class="short_icon published">Published On</th>
											<th class="short_icon views" >Views</th>
											<th class="short_icon ad">Ad Views</th>
											<th class="short_icon earning">Earning</th>
											<th class="status">Status</th>
											<th class="action">Action</th>
										</tr>
									</thead>
									<tbody>
										
									</tbody>
								</table>
							</div>
						</div>
					</div>
				</div>
				<!-- advertising table section End -->
			</div>	
		<!-- advertising  page End-->
		
	
	</div>


<?php 
$this->load->view('home/inc/share_popup');

?>