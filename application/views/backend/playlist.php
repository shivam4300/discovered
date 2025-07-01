<?php echo $this->common_html->ConfirmNotificationModal(); ?>
	<div class="dash_page_section">
		<!-- advertising  page start -->
			<div class="dash_advet_wrapper">
				<!-- advertising table section Start -->
				<div class="advet_table_mainwrapper table_area">
					<div class="row">
						<div class="col-md-12">
							
							<div class="table_topmenus">
								<div class="left_menu">
									
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
									<div class="tbl_tm_dropdown mode hide">
										<select  name="delete_status" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' Mode',allowHtml:true,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter " >
										  <option value="0" selected>Active</option>
										</select>
									</div>
									<div class="tbl_tm_dropdown export hide" id="delete_video_btn" data-action-url='backend/playlist/deletePlaylist' data-type="playlist_id">
										<a href="javascript:;" class="table_embedbtn">Delete</a>
									</div>
									
								</div>
								<div class="left_menu">
									<div class="dash_cmn_dd_wrap das_playlist_create tbl_tm_dropdown export">
										<a href="javascript:;" class="table_embedbtn dash_cmn_dd_btn" >Create Playlist</a>
										<div class="dash_min_dd">
											<div class="dash_min_dd_body">
												<ul class="dash_min_dd_list">
													<li>
														<div class="dash_field_box mb-15">
															<label class="dash_field_label mb-10">Playlist Title</label>
															<div class="dash_field_wrap">
																<input type="text" class="dash_field_input w-100" placeholder="Enter Playlist Title" id="playlistTitle">
															</div>                    
														</div>
													</li>
													<li>
														<div class="dash_field_box mb-15">
															<label class="dash_field_label mb-10">Playlist Title</label>
															<div class="dash_field_wrap">
																<select class="dash_field_input w-100" id="PlayListStatus">
																	<?php
																	$ss =$this->audition_functions->post_status();
																	foreach($ss as $k=>$v){
																		$s = ($k == 7)?'selected="selected"':'';
																		echo '<option '.$s.' value="'.$k.'">'.$v.'</option>';
																	} 
																	?>
																</select>
															</div>                    
														</div>
													</li>
												</ul>
											</div>
											<div class="dash_min_dd_footer mt-15">
												<button class="backend_btn color_green h_50 max_width createNewPlaylist" data-page="playlist">Create</button>
											</div>
										</div>
									</div>
									<div class="tbl_tm_dropdown showall">
										<select  name="length" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' All',allowHtml:true,allowClear:false,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter">
										  <option value="">Show All</option>
										  <option value="10" selected>10</option>
										  <option value="20">20</option>
										  <option value="50">50</option>
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
								 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="backend/playlist/show_playlist_details" data-table-position="0" data-target-section="tbody" cellspacing="0" width="100%" data-column-class="[{className: 'srno'},{className: 'preview'},{className: 'title'},{className: 'count'},{className: 'published'},{className: 'status'},{className: 'action'}]"	data-filter='1' data-refresh-dataTablePosition="0">
									<thead>
										<tr>
											<th class="srno">
												<div class="tbl_checkboxx" >
													<input type="checkbox" id="checkall">
													<label for=""></label>
												</div>
											</th>
											<th class="preview">Preview </th>
											<th class="title"> Title</th>
											<th class="count">Video Count </th>
											<th class="short_icon published">Created On</th>
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


<?php  $this->load->view('home/inc/share_popup'); ?>