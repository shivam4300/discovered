<div class="dis_sprtAdmin_wrap full_vh_foooter muli_font p_t_50 p_b_50">
    <div class="dis_1200_container">
        <div class="row">
            <div class="col-md-12">
				<div class="dis_sprtAdmin_inner">
					<div class="dis_cmnbox_header text-center">
                        <h2 class="dis_cmnbox_header_ttl"><?=$user_department['name']?></h2>
                        <!-- <p class="dis_cmnbox_header_des">Dummy text here....</p> -->
                    </div>
					<div class="dis_sprtAdmin_body">
						<ul class="dis_sprtAdmin_topbar_list">
							<li>
								<div class="dis_field_wrap dis_select2">
									<select class="primay_select dis_field_input filterBy" name="no_of_records" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
										<option value="">Show Ticket</option>
										<option value="10">Show 10 Ticket</option>
										<option value="20">Show 20 Ticket</option>
										<option value="30">Show 30 Ticket</option>
									</select>
								</div>	
							</li>
							<li>
								<div class="dis_field_wrap dis_select2">
									<select class="primay_select dis_field_input filterBy" name="ticket_status" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
										<option value="">Filter By Status</option>
                                            <option value="0">Open Ticket</option>
                                            <option value="1">Replied Ticket</option>
                                            <option value="2">Close Ticket</option>
                                            <option value="3">Customer Replied Ticket</option>
									</select>
								</div>	
							</li>
							<li>
								<div class="dis_field_wrap dis_select2">
									<select class="primay_select dis_field_input filterBy" name="tech_status" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
										<?php
											$platform = $this->valuelist->platform();
											foreach($platform as $key => $val){
												echo "<option value='$key'>$val</option>";
											}
										?>
									</select>
								</div>	
							</li>
							<?php if(!empty($artist_category)){ ?>
							<li>
								<div class="dis_field_wrap dis_select2">
									<select class="primay_select dis_field_input filterBy" name="user_type" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
										<option value="">Filter By User Type</option>
										<?php foreach($artist_category as $cat){
												echo '<option value="'.$cat['category_id'].'">'.$cat['category_name'].'</option>';
											}
										?>
									</select>
								</div>	
							</li>
							<?php } ?>
							<li>
								<div class="dis_daterangePicker">
									<input class="daterange" type="text" name="date_range" placeholder="Date Range" autocomplete="off">
									<i class="tbl_date_icon fa fa-calendar" aria-hidden="true"></i>
								</div>
							</li>
							<!--li>
								<div class="dis_field_wrap">
									<button type="submit" class="dis_btn min_width_inherit b-r-5">Filter</button>
								</div>	
							</li-->
						</ul>
						<ul class="dis_sprtAdmin_list" id="appendTicketList" data-ticket_type="backend">
							<!--li>
								<div class="dis_sprtAdmin_ticketbox dis_comn_whiteborder">
									<div class="dis_sprtAdmin_tb_left">
										<div class="dis_sprtAdmin_tb_thumb">
											<span>
												<img src="https://test.discovered.tv/repo/images/user/user.png" alt="thumb" class="img-responsive">
											</span>
										</div>
										<div class="dis_sprtAdmin_tb_details">
											<h2 class="dis_tb_hd_ttl m_b_5 mp_0 m_b_5">Ticket Subject Goes Here</h2>
											<ul class="dis_tb_hd_list dis_ticketbox_infoicon_list d-flex">
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0"><a href="#">David Parker</a> - Icon</span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M8.867,13.000 L1.132,13.000 C0.509,12.998 0.005,12.464 0.003,11.805 L0.003,1.195 C0.005,0.536 0.509,0.002 1.132,-0.000 L5.928,-0.000 L5.928,2.314 C5.928,3.414 6.770,4.305 7.810,4.305 L9.996,4.305 L9.996,11.805 C9.995,12.464 9.490,12.998 8.867,13.000 ZM3.655,4.140 L2.023,4.140 C1.713,4.140 1.460,4.408 1.460,4.737 C1.460,5.065 1.713,5.333 2.023,5.333 L3.655,5.333 C3.965,5.333 4.218,5.065 4.218,4.737 C4.218,4.408 3.965,4.140 3.655,4.140 ZM7.451,6.792 L2.023,6.792 C1.713,6.792 1.460,7.060 1.460,7.389 C1.460,7.717 1.713,7.985 2.023,7.985 L7.451,7.985 C7.762,7.985 8.015,7.717 8.015,7.389 C8.015,7.060 7.762,6.792 7.451,6.792 ZM7.451,9.448 L2.023,9.448 C1.713,9.448 1.460,9.715 1.460,10.043 C1.460,10.372 1.713,10.640 2.023,10.640 L7.451,10.640 C7.762,10.640 8.015,10.372 8.015,10.043 C8.015,9.715 7.762,9.448 7.451,9.448 ZM6.680,2.314 L6.680,0.562 L9.473,3.509 L7.810,3.509 C7.186,3.508 6.681,2.974 6.680,2.314 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">Ticket ID #</span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.722,13.000 L1.907,13.000 C1.299,13.000 0.805,12.471 0.805,11.828 L0.805,2.612 C0.805,1.980 1.288,1.466 1.875,1.447 L1.875,3.020 C1.875,3.731 2.420,4.304 3.092,4.304 L3.860,4.304 C4.532,4.304 5.084,3.731 5.084,3.020 L5.084,1.442 L8.545,1.442 L8.545,3.020 C8.545,3.731 9.097,4.304 9.769,4.304 L10.537,4.304 C11.209,4.304 11.754,3.731 11.754,3.020 L11.754,1.447 C12.341,1.466 12.824,1.980 12.824,2.612 L12.824,11.828 C12.824,12.470 12.329,13.000 11.722,13.000 ZM11.397,6.494 C11.397,6.215 11.184,5.990 10.921,5.990 L2.687,5.990 C2.424,5.990 2.211,6.215 2.211,6.494 L2.211,11.254 C2.211,11.532 2.424,11.758 2.687,11.758 L10.921,11.758 C11.184,11.758 11.397,11.532 11.397,11.254 L11.397,6.494 ZM9.734,11.070 L8.761,11.070 C8.607,11.070 8.482,10.938 8.482,10.775 L8.482,9.745 C8.482,9.582 8.607,9.451 8.761,9.451 L9.734,9.451 C9.888,9.451 10.013,9.582 10.013,9.745 L10.013,10.775 C10.013,10.938 9.888,11.070 9.734,11.070 ZM9.734,8.497 L8.761,8.497 C8.607,8.497 8.482,8.365 8.482,8.202 L8.482,7.172 C8.482,7.009 8.607,6.877 8.761,6.877 L9.734,6.877 C9.888,6.877 10.013,7.009 10.013,7.172 L10.013,8.202 C10.013,8.365 9.888,8.497 9.734,8.497 ZM7.301,11.070 L6.328,11.070 C6.174,11.070 6.049,10.938 6.049,10.775 L6.049,9.745 C6.049,9.582 6.174,9.451 6.328,9.451 L7.301,9.451 C7.455,9.451 7.580,9.582 7.580,9.745 L7.580,10.775 C7.580,10.938 7.455,11.070 7.301,11.070 ZM7.301,8.497 L6.328,8.497 C6.174,8.497 6.049,8.365 6.049,8.202 L6.049,7.172 C6.049,7.009 6.174,6.877 6.328,6.877 L7.301,6.877 C7.455,6.877 7.580,7.009 7.580,7.172 L7.580,8.202 C7.580,8.365 7.455,8.497 7.301,8.497 ZM4.868,11.070 L3.895,11.070 C3.741,11.070 3.616,10.938 3.616,10.775 L3.616,9.745 C3.616,9.582 3.741,9.451 3.895,9.451 L4.868,9.451 C5.022,9.451 5.147,9.582 5.147,9.745 L5.147,10.775 C5.147,10.938 5.022,11.070 4.868,11.070 ZM4.868,8.497 L3.895,8.497 C3.741,8.497 3.616,8.365 3.616,8.202 L3.616,7.172 C3.616,7.009 3.741,6.877 3.895,6.877 L4.868,6.877 C5.022,6.877 5.147,7.009 5.147,7.172 L5.147,8.202 C5.147,8.365 5.022,8.497 4.868,8.497 ZM10.519,3.461 L9.759,3.461 C9.529,3.461 9.342,3.263 9.342,3.019 L9.342,0.441 C9.342,0.197 9.529,-0.000 9.759,-0.000 L10.519,-0.000 C10.749,-0.000 10.936,0.197 10.936,0.441 L10.936,3.019 C10.936,3.263 10.749,3.461 10.519,3.461 ZM3.849,3.461 L3.090,3.461 C2.859,3.461 2.672,3.263 2.672,3.019 L2.672,0.441 C2.672,0.197 2.859,-0.000 3.090,-0.000 L3.849,-0.000 C4.079,-0.000 4.266,0.197 4.266,0.441 L4.266,3.019 C4.266,3.263 4.079,3.461 3.849,3.461 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">
															Created Date - 5 October 2021                                                            </span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="12px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M0.388,5.430 C1.171,3.414 3.287,2.405 6.735,2.405 L8.360,2.405 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.087,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.082 2.615,7.295 C2.378,7.508 2.184,7.760 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.555,9.945 1.555,10.451 C1.555,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.566,11.937 C1.525,11.987 1.468,12.011 1.396,12.011 C1.318,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.000,11.494 C0.964,11.409 0.939,11.349 0.924,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">
															Replied On - 5 October 2021                                                            </span>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="dis_sprtAdmin_tb_right">
										<div class="custom_dropdown_wrap">
											<span class="custom_dropdown_btn">
												<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
													<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
												</svg> 
											</span>
											<ul class="custom_dropdown_menu">
												<li class="custom_dropdown_item" >
													<a href="#" class="custom_dd_anchr">
														<span class="custom_dd_icon">
															<svg viewBox="0 0 14 14" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 18px;">
																<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<g id="Rounded" transform="translate(-411.000000, -1487.000000)">
																		<g id="Content" transform="translate(100.000000, 1428.000000)">
																			<g id="-Round-/-Content-/-add" transform="translate(306.000000, 54.000000)">
																				<g transform="translate(0.000000, 0.000000)">
																					<polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
																					<path d="M18,13 L13,13 L13,18 C13,18.55 12.55,19 12,19 C11.45,19 11,18.55 11,18 L11,13 L6,13 C5.45,13 5,12.55 5,12 C5,11.45 5.45,11 6,11 L11,11 L11,6 C11,5.45 11.45,5 12,5 C12.55,5 13,5.45 13,6 L13,11 L18,11 C18.55,11 19,11.45 19,12 C19,12.55 18.55,13 18,13 Z" id="Icon-Color" fill="rgb(64 64 76)" style="width: 9px;"></path>
																				</g>
																			</g>
																		</g>
																	</g>
																</g>
															</svg>
														</span>  
														<span class="custom_dd_text">add videos</span>
													</a>
												</li>
											</ul>
										</div>
									</div>	
									<span class="dis_ticketFlag">Ticket Open</span>
								</div>	
							</li-->
							<!--<li>
								<div class="dis_sprtAdmin_ticketbox dis_comn_whiteborder">
									<div class="dis_sprtAdmin_tb_left">
										<div class="dis_sprtAdmin_tb_thumb">
											<span>
												<img src="https://test.discovered.tv/repo/images/user/user.png" alt="thumb" class="img-responsive">
											</span>
										</div>
										<div class="dis_sprtAdmin_tb_details">
											<h2 class="dis_tb_hd_ttl m_b_5 mp_0 m_b_5">Ticket Subject Goes Here</h2>
											<ul class="dis_tb_hd_list dis_ticketbox_infoicon_list d-flex">
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0"><a href="#">David Parker</a> - Icon</span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M8.867,13.000 L1.132,13.000 C0.509,12.998 0.005,12.464 0.003,11.805 L0.003,1.195 C0.005,0.536 0.509,0.002 1.132,-0.000 L5.928,-0.000 L5.928,2.314 C5.928,3.414 6.770,4.305 7.810,4.305 L9.996,4.305 L9.996,11.805 C9.995,12.464 9.490,12.998 8.867,13.000 ZM3.655,4.140 L2.023,4.140 C1.713,4.140 1.460,4.408 1.460,4.737 C1.460,5.065 1.713,5.333 2.023,5.333 L3.655,5.333 C3.965,5.333 4.218,5.065 4.218,4.737 C4.218,4.408 3.965,4.140 3.655,4.140 ZM7.451,6.792 L2.023,6.792 C1.713,6.792 1.460,7.060 1.460,7.389 C1.460,7.717 1.713,7.985 2.023,7.985 L7.451,7.985 C7.762,7.985 8.015,7.717 8.015,7.389 C8.015,7.060 7.762,6.792 7.451,6.792 ZM7.451,9.448 L2.023,9.448 C1.713,9.448 1.460,9.715 1.460,10.043 C1.460,10.372 1.713,10.640 2.023,10.640 L7.451,10.640 C7.762,10.640 8.015,10.372 8.015,10.043 C8.015,9.715 7.762,9.448 7.451,9.448 ZM6.680,2.314 L6.680,0.562 L9.473,3.509 L7.810,3.509 C7.186,3.508 6.681,2.974 6.680,2.314 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">Ticket ID #</span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.722,13.000 L1.907,13.000 C1.299,13.000 0.805,12.471 0.805,11.828 L0.805,2.612 C0.805,1.980 1.288,1.466 1.875,1.447 L1.875,3.020 C1.875,3.731 2.420,4.304 3.092,4.304 L3.860,4.304 C4.532,4.304 5.084,3.731 5.084,3.020 L5.084,1.442 L8.545,1.442 L8.545,3.020 C8.545,3.731 9.097,4.304 9.769,4.304 L10.537,4.304 C11.209,4.304 11.754,3.731 11.754,3.020 L11.754,1.447 C12.341,1.466 12.824,1.980 12.824,2.612 L12.824,11.828 C12.824,12.470 12.329,13.000 11.722,13.000 ZM11.397,6.494 C11.397,6.215 11.184,5.990 10.921,5.990 L2.687,5.990 C2.424,5.990 2.211,6.215 2.211,6.494 L2.211,11.254 C2.211,11.532 2.424,11.758 2.687,11.758 L10.921,11.758 C11.184,11.758 11.397,11.532 11.397,11.254 L11.397,6.494 ZM9.734,11.070 L8.761,11.070 C8.607,11.070 8.482,10.938 8.482,10.775 L8.482,9.745 C8.482,9.582 8.607,9.451 8.761,9.451 L9.734,9.451 C9.888,9.451 10.013,9.582 10.013,9.745 L10.013,10.775 C10.013,10.938 9.888,11.070 9.734,11.070 ZM9.734,8.497 L8.761,8.497 C8.607,8.497 8.482,8.365 8.482,8.202 L8.482,7.172 C8.482,7.009 8.607,6.877 8.761,6.877 L9.734,6.877 C9.888,6.877 10.013,7.009 10.013,7.172 L10.013,8.202 C10.013,8.365 9.888,8.497 9.734,8.497 ZM7.301,11.070 L6.328,11.070 C6.174,11.070 6.049,10.938 6.049,10.775 L6.049,9.745 C6.049,9.582 6.174,9.451 6.328,9.451 L7.301,9.451 C7.455,9.451 7.580,9.582 7.580,9.745 L7.580,10.775 C7.580,10.938 7.455,11.070 7.301,11.070 ZM7.301,8.497 L6.328,8.497 C6.174,8.497 6.049,8.365 6.049,8.202 L6.049,7.172 C6.049,7.009 6.174,6.877 6.328,6.877 L7.301,6.877 C7.455,6.877 7.580,7.009 7.580,7.172 L7.580,8.202 C7.580,8.365 7.455,8.497 7.301,8.497 ZM4.868,11.070 L3.895,11.070 C3.741,11.070 3.616,10.938 3.616,10.775 L3.616,9.745 C3.616,9.582 3.741,9.451 3.895,9.451 L4.868,9.451 C5.022,9.451 5.147,9.582 5.147,9.745 L5.147,10.775 C5.147,10.938 5.022,11.070 4.868,11.070 ZM4.868,8.497 L3.895,8.497 C3.741,8.497 3.616,8.365 3.616,8.202 L3.616,7.172 C3.616,7.009 3.741,6.877 3.895,6.877 L4.868,6.877 C5.022,6.877 5.147,7.009 5.147,7.172 L5.147,8.202 C5.147,8.365 5.022,8.497 4.868,8.497 ZM10.519,3.461 L9.759,3.461 C9.529,3.461 9.342,3.263 9.342,3.019 L9.342,0.441 C9.342,0.197 9.529,-0.000 9.759,-0.000 L10.519,-0.000 C10.749,-0.000 10.936,0.197 10.936,0.441 L10.936,3.019 C10.936,3.263 10.749,3.461 10.519,3.461 ZM3.849,3.461 L3.090,3.461 C2.859,3.461 2.672,3.263 2.672,3.019 L2.672,0.441 C2.672,0.197 2.859,-0.000 3.090,-0.000 L3.849,-0.000 C4.079,-0.000 4.266,0.197 4.266,0.441 L4.266,3.019 C4.266,3.263 4.079,3.461 3.849,3.461 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">
															Created Date - 5 October 2021                                                            </span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="12px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M0.388,5.430 C1.171,3.414 3.287,2.405 6.735,2.405 L8.360,2.405 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.087,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.082 2.615,7.295 C2.378,7.508 2.184,7.760 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.555,9.945 1.555,10.451 C1.555,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.566,11.937 C1.525,11.987 1.468,12.011 1.396,12.011 C1.318,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.000,11.494 C0.964,11.409 0.939,11.349 0.924,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">
															Replied On - 5 October 2021                                                            </span>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="dis_sprtAdmin_tb_right">
										<div class="custom_dropdown_wrap">
											<span class="custom_dropdown_btn">
												<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
													<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
												</svg> 
											</span>
											<ul class="custom_dropdown_menu">
												<li class="custom_dropdown_item" >
													<a href="#" class="custom_dd_anchr">
														<span class="custom_dd_icon">
															<svg viewBox="0 0 14 14" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 18px;">
																<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<g id="Rounded" transform="translate(-411.000000, -1487.000000)">
																		<g id="Content" transform="translate(100.000000, 1428.000000)">
																			<g id="-Round-/-Content-/-add" transform="translate(306.000000, 54.000000)">
																				<g transform="translate(0.000000, 0.000000)">
																					<polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
																					<path d="M18,13 L13,13 L13,18 C13,18.55 12.55,19 12,19 C11.45,19 11,18.55 11,18 L11,13 L6,13 C5.45,13 5,12.55 5,12 C5,11.45 5.45,11 6,11 L11,11 L11,6 C11,5.45 11.45,5 12,5 C12.55,5 13,5.45 13,6 L13,11 L18,11 C18.55,11 19,11.45 19,12 C19,12.55 18.55,13 18,13 Z" id="Icon-Color" fill="rgb(64 64 76)" style="width: 9px;"></path>
																				</g>
																			</g>
																		</g>
																	</g>
																</g>
															</svg>
														</span>  
														<span class="custom_dd_text">add videos</span>
													</a>
												</li>
											</ul>
										</div>
									</div>	
									<span class="dis_ticketFlag tf_open">Ticket Replied</span>
								</div>	
							</li>
							<li>
								<div class="dis_sprtAdmin_ticketbox dis_comn_whiteborder">
									<div class="dis_sprtAdmin_tb_left">
										<div class="dis_sprtAdmin_tb_thumb">
											<span>
												<img src="https://test.discovered.tv/repo/images/user/user.png" alt="thumb" class="img-responsive">
											</span>
										</div>
										<div class="dis_sprtAdmin_tb_details">
											<h2 class="dis_tb_hd_ttl m_b_5 mp_0 m_b_5">Ticket Subject Goes Here</h2>
											<ul class="dis_tb_hd_list dis_ticketbox_infoicon_list d-flex">
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.096,1.904 C9.868,0.676 8.236,-0.000 6.500,-0.000 C4.764,-0.000 3.131,0.676 1.904,1.904 C0.676,3.131 -0.000,4.764 -0.000,6.500 C-0.000,8.236 0.676,9.868 1.904,11.096 C3.131,12.324 4.764,13.000 6.500,13.000 C8.236,13.000 9.868,12.324 11.096,11.096 C12.324,9.868 13.000,8.236 13.000,6.500 C13.000,4.764 12.324,3.131 11.096,1.904 ZM6.500,12.238 C4.802,12.238 3.274,11.497 2.223,10.321 C2.875,8.593 4.544,7.363 6.500,7.363 C5.238,7.363 4.215,6.340 4.215,5.078 C4.215,3.816 5.238,2.793 6.500,2.793 C7.762,2.793 8.785,3.816 8.785,5.078 C8.785,6.340 7.762,7.363 6.500,7.363 C8.456,7.363 10.125,8.593 10.777,10.321 C9.726,11.497 8.198,12.238 6.500,12.238 Z"/></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0"><a href="#">David Parker</a> - Icon</span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="10px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M8.867,13.000 L1.132,13.000 C0.509,12.998 0.005,12.464 0.003,11.805 L0.003,1.195 C0.005,0.536 0.509,0.002 1.132,-0.000 L5.928,-0.000 L5.928,2.314 C5.928,3.414 6.770,4.305 7.810,4.305 L9.996,4.305 L9.996,11.805 C9.995,12.464 9.490,12.998 8.867,13.000 ZM3.655,4.140 L2.023,4.140 C1.713,4.140 1.460,4.408 1.460,4.737 C1.460,5.065 1.713,5.333 2.023,5.333 L3.655,5.333 C3.965,5.333 4.218,5.065 4.218,4.737 C4.218,4.408 3.965,4.140 3.655,4.140 ZM7.451,6.792 L2.023,6.792 C1.713,6.792 1.460,7.060 1.460,7.389 C1.460,7.717 1.713,7.985 2.023,7.985 L7.451,7.985 C7.762,7.985 8.015,7.717 8.015,7.389 C8.015,7.060 7.762,6.792 7.451,6.792 ZM7.451,9.448 L2.023,9.448 C1.713,9.448 1.460,9.715 1.460,10.043 C1.460,10.372 1.713,10.640 2.023,10.640 L7.451,10.640 C7.762,10.640 8.015,10.372 8.015,10.043 C8.015,9.715 7.762,9.448 7.451,9.448 ZM6.680,2.314 L6.680,0.562 L9.473,3.509 L7.810,3.509 C7.186,3.508 6.681,2.974 6.680,2.314 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">Ticket ID #</span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="13px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M11.722,13.000 L1.907,13.000 C1.299,13.000 0.805,12.471 0.805,11.828 L0.805,2.612 C0.805,1.980 1.288,1.466 1.875,1.447 L1.875,3.020 C1.875,3.731 2.420,4.304 3.092,4.304 L3.860,4.304 C4.532,4.304 5.084,3.731 5.084,3.020 L5.084,1.442 L8.545,1.442 L8.545,3.020 C8.545,3.731 9.097,4.304 9.769,4.304 L10.537,4.304 C11.209,4.304 11.754,3.731 11.754,3.020 L11.754,1.447 C12.341,1.466 12.824,1.980 12.824,2.612 L12.824,11.828 C12.824,12.470 12.329,13.000 11.722,13.000 ZM11.397,6.494 C11.397,6.215 11.184,5.990 10.921,5.990 L2.687,5.990 C2.424,5.990 2.211,6.215 2.211,6.494 L2.211,11.254 C2.211,11.532 2.424,11.758 2.687,11.758 L10.921,11.758 C11.184,11.758 11.397,11.532 11.397,11.254 L11.397,6.494 ZM9.734,11.070 L8.761,11.070 C8.607,11.070 8.482,10.938 8.482,10.775 L8.482,9.745 C8.482,9.582 8.607,9.451 8.761,9.451 L9.734,9.451 C9.888,9.451 10.013,9.582 10.013,9.745 L10.013,10.775 C10.013,10.938 9.888,11.070 9.734,11.070 ZM9.734,8.497 L8.761,8.497 C8.607,8.497 8.482,8.365 8.482,8.202 L8.482,7.172 C8.482,7.009 8.607,6.877 8.761,6.877 L9.734,6.877 C9.888,6.877 10.013,7.009 10.013,7.172 L10.013,8.202 C10.013,8.365 9.888,8.497 9.734,8.497 ZM7.301,11.070 L6.328,11.070 C6.174,11.070 6.049,10.938 6.049,10.775 L6.049,9.745 C6.049,9.582 6.174,9.451 6.328,9.451 L7.301,9.451 C7.455,9.451 7.580,9.582 7.580,9.745 L7.580,10.775 C7.580,10.938 7.455,11.070 7.301,11.070 ZM7.301,8.497 L6.328,8.497 C6.174,8.497 6.049,8.365 6.049,8.202 L6.049,7.172 C6.049,7.009 6.174,6.877 6.328,6.877 L7.301,6.877 C7.455,6.877 7.580,7.009 7.580,7.172 L7.580,8.202 C7.580,8.365 7.455,8.497 7.301,8.497 ZM4.868,11.070 L3.895,11.070 C3.741,11.070 3.616,10.938 3.616,10.775 L3.616,9.745 C3.616,9.582 3.741,9.451 3.895,9.451 L4.868,9.451 C5.022,9.451 5.147,9.582 5.147,9.745 L5.147,10.775 C5.147,10.938 5.022,11.070 4.868,11.070 ZM4.868,8.497 L3.895,8.497 C3.741,8.497 3.616,8.365 3.616,8.202 L3.616,7.172 C3.616,7.009 3.741,6.877 3.895,6.877 L4.868,6.877 C5.022,6.877 5.147,7.009 5.147,7.172 L5.147,8.202 C5.147,8.365 5.022,8.497 4.868,8.497 ZM10.519,3.461 L9.759,3.461 C9.529,3.461 9.342,3.263 9.342,3.019 L9.342,0.441 C9.342,0.197 9.529,-0.000 9.759,-0.000 L10.519,-0.000 C10.749,-0.000 10.936,0.197 10.936,0.441 L10.936,3.019 C10.936,3.263 10.749,3.461 10.519,3.461 ZM3.849,3.461 L3.090,3.461 C2.859,3.461 2.672,3.263 2.672,3.019 L2.672,0.441 C2.672,0.197 2.859,-0.000 3.090,-0.000 L3.849,-0.000 C4.079,-0.000 4.266,0.197 4.266,0.441 L4.266,3.019 C4.266,3.263 4.079,3.461 3.849,3.461 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">
															Created Date - 5 October 2021                                                            </span>
													</div>
												</li>
												<li>
													<div class="dis_ticketbox_infoicon">
														<span class="dis_ticketbox_infoicon_icon">
															<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="13px" height="12px"><path fill-rule="evenodd" fill="rgb(179, 179, 179)" d="M0.388,5.430 C1.171,3.414 3.287,2.405 6.735,2.405 L8.360,2.405 L8.360,0.484 C8.360,0.354 8.406,0.241 8.498,0.147 C8.590,0.052 8.699,0.004 8.825,0.004 C8.950,0.004 9.059,0.052 9.151,0.147 L12.865,3.989 C12.957,4.084 13.003,4.197 13.003,4.327 C13.003,4.457 12.957,4.569 12.865,4.665 L9.151,8.507 C9.059,8.602 8.950,8.650 8.825,8.650 C8.699,8.650 8.590,8.602 8.498,8.507 C8.406,8.412 8.360,8.300 8.360,8.169 L8.360,6.248 L6.735,6.248 C6.261,6.248 5.837,6.263 5.462,6.293 C5.087,6.323 4.715,6.377 4.345,6.455 C3.975,6.532 3.653,6.638 3.380,6.773 C3.107,6.909 2.852,7.082 2.615,7.295 C2.378,7.508 2.184,7.760 2.034,8.053 C1.884,8.346 1.767,8.692 1.683,9.092 C1.598,9.493 1.555,9.945 1.555,10.451 C1.555,10.726 1.568,11.034 1.592,11.374 C1.592,11.404 1.598,11.463 1.610,11.550 C1.622,11.638 1.628,11.704 1.628,11.749 C1.628,11.824 1.608,11.887 1.566,11.937 C1.525,11.987 1.468,12.011 1.396,12.011 C1.318,12.011 1.251,11.969 1.193,11.884 C1.159,11.839 1.128,11.784 1.098,11.719 C1.069,11.654 1.037,11.579 1.000,11.494 C0.964,11.409 0.939,11.349 0.924,11.314 C0.310,9.888 0.003,8.760 0.003,7.929 C0.003,6.934 0.131,6.101 0.388,5.430 Z"></path></svg>
														</span>
														<span class="dis_ticketbox_infoicon_ttl mp_0">
															Replied On - 5 October 2021                                                            </span>
													</div>
												</li>
											</ul>
										</div>
									</div>
									<div class="dis_sprtAdmin_tb_right">
										<div class="custom_dropdown_wrap">
											<span class="custom_dropdown_btn">
												<svg xmlns="http://www.w3.org/2000/svg" width="17px" height="7px" viewBox="0 0 17 7">
													<path fill-rule="evenodd" fill="rgb(119, 119, 119)" d="M14.875,4.000 C13.701,4.000 12.750,3.104 12.750,2.000 C12.750,0.895 13.701,-0.000 14.875,-0.000 C16.049,-0.000 17.000,0.895 17.000,2.000 C17.000,3.104 16.049,4.000 14.875,4.000 ZM8.500,4.000 C7.326,4.000 6.375,3.104 6.375,2.000 C6.375,0.895 7.326,-0.000 8.500,-0.000 C9.673,-0.000 10.625,0.895 10.625,2.000 C10.625,3.104 9.673,4.000 8.500,4.000 ZM2.125,4.000 C0.951,4.000 -0.000,3.104 -0.000,2.000 C-0.000,0.895 0.951,-0.000 2.125,-0.000 C3.299,-0.000 4.250,0.895 4.250,2.000 C4.250,3.104 3.299,4.000 2.125,4.000 Z"></path>
												</svg> 
											</span>
											<ul class="custom_dropdown_menu">
												<li class="custom_dropdown_item" >
													<a href="#" class="custom_dd_anchr">
														<span class="custom_dd_icon">
															<svg viewBox="0 0 14 14" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 18px;">
																<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<g id="Rounded" transform="translate(-411.000000, -1487.000000)">
																		<g id="Content" transform="translate(100.000000, 1428.000000)">
																			<g id="-Round-/-Content-/-add" transform="translate(306.000000, 54.000000)">
																				<g transform="translate(0.000000, 0.000000)">
																					<polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
																					<path d="M18,13 L13,13 L13,18 C13,18.55 12.55,19 12,19 C11.45,19 11,18.55 11,18 L11,13 L6,13 C5.45,13 5,12.55 5,12 C5,11.45 5.45,11 6,11 L11,11 L11,6 C11,5.45 11.45,5 12,5 C12.55,5 13,5.45 13,6 L13,11 L18,11 C18.55,11 19,11.45 19,12 C19,12.55 18.55,13 18,13 Z" id="Icon-Color" fill="rgb(64 64 76)" style="width: 9px;"></path>
																				</g>
																			</g>
																		</g>
																	</g>
																</g>
															</svg>
														</span>  
														<span class="custom_dd_text">reply</span>
													</a>
												</li>
												<li class="custom_dropdown_item" >
													<a href="#" class="custom_dd_anchr">
														<span class="custom_dd_icon">
															<svg viewBox="0 0 14 14" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 18px;">
																<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<g id="Rounded" transform="translate(-411.000000, -1487.000000)">
																		<g id="Content" transform="translate(100.000000, 1428.000000)">
																			<g id="-Round-/-Content-/-add" transform="translate(306.000000, 54.000000)">
																				<g transform="translate(0.000000, 0.000000)">
																					<polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
																					<path d="M18,13 L13,13 L13,18 C13,18.55 12.55,19 12,19 C11.45,19 11,18.55 11,18 L11,13 L6,13 C5.45,13 5,12.55 5,12 C5,11.45 5.45,11 6,11 L11,11 L11,6 C11,5.45 11.45,5 12,5 C12.55,5 13,5.45 13,6 L13,11 L18,11 C18.55,11 19,11.45 19,12 C19,12.55 18.55,13 18,13 Z" id="Icon-Color" fill="rgb(64 64 76)" style="width: 9px;"></path>
																				</g>
																			</g>
																		</g>
																	</g>
																</g>
															</svg>
														</span>  
														<span class="custom_dd_text">transfer</span>
													</a>
												</li>
												<li class="custom_dropdown_item" >
													<a href="#" class="custom_dd_anchr">
														<span class="custom_dd_icon">
															<svg viewBox="0 0 14 14" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" style="width: 18px;">
																<g id="Icons" stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
																	<g id="Rounded" transform="translate(-411.000000, -1487.000000)">
																		<g id="Content" transform="translate(100.000000, 1428.000000)">
																			<g id="-Round-/-Content-/-add" transform="translate(306.000000, 54.000000)">
																				<g transform="translate(0.000000, 0.000000)">
																					<polygon id="Path" points="0 0 24 0 24 24 0 24"></polygon>
																					<path d="M18,13 L13,13 L13,18 C13,18.55 12.55,19 12,19 C11.45,19 11,18.55 11,18 L11,13 L6,13 C5.45,13 5,12.55 5,12 C5,11.45 5.45,11 6,11 L11,11 L11,6 C11,5.45 11.45,5 12,5 C12.55,5 13,5.45 13,6 L13,11 L18,11 C18.55,11 19,11.45 19,12 C19,12.55 18.55,13 18,13 Z" id="Icon-Color" fill="rgb(64 64 76)" style="width: 9px;"></path>
																				</g>
																			</g>
																		</g>
																	</g>
																</g>
															</svg>
														</span>  
														<span class="custom_dd_text">accept</span>
													</a>
												</li>
											</ul>
										</div>
									</div>	
									<span class="dis_ticketFlag tf_closed">Ticket Closed</span>
								</div>	
							</li>-->
						</ul>
					</div>
					<div class="dis_sprtAdmin_footer">
						<ul class="dis_sprtAdmin_footerlist">
							<li>
								<h2 class="dis_sprtAdmin_showing"></h2> <!--Showing 05 of 100 Tickets -->
							</li>
							<li id="pagination">
								<!--<ul class="dis_pagination">
									<li>
										<a href="" class="dis_pagination_item">
											<span>
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="5px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M0.127,3.688 L3.907,0.134 C3.994,0.052 4.111,0.006 4.236,0.006 C4.360,0.006 4.477,0.052 4.564,0.134 L4.842,0.396 C5.024,0.566 5.024,0.843 4.842,1.014 L1.669,3.998 L4.846,6.986 C4.933,7.069 4.982,7.178 4.982,7.295 C4.982,7.412 4.933,7.522 4.846,7.604 L4.568,7.866 C4.480,7.948 4.364,7.994 4.239,7.994 C4.115,7.994 3.998,7.948 3.911,7.866 L0.127,4.308 C0.040,4.226 -0.008,4.116 -0.008,3.999 C-0.008,3.881 0.040,3.771 0.127,3.688 Z"/></svg>
											</span>
										</a>
									</li>
									<li><a href="" class="dis_pagination_item active">1</a></li>
									<li><a href="" class="dis_pagination_item">2</a></li>
									<li><a href="" class="dis_pagination_item">3</a></li>
									<li><a href="" class="dis_pagination_item">...</a></li>
									<li>
										<a href="" class="dis_pagination_item">
											<span>
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="5px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M4.872,3.688 L1.073,0.134 C0.985,0.052 0.867,0.006 0.742,0.006 C0.617,0.006 0.500,0.052 0.412,0.134 L0.132,0.396 C-0.050,0.566 -0.050,0.843 0.132,1.014 L3.323,3.998 L0.129,6.986 C0.041,7.069 -0.008,7.178 -0.008,7.295 C-0.008,7.412 0.041,7.522 0.129,7.604 L0.408,7.866 C0.496,7.948 0.614,7.994 0.739,7.994 C0.864,7.994 0.981,7.948 1.069,7.866 L4.872,4.308 C4.960,4.226 5.008,4.116 5.008,3.999 C5.008,3.881 4.960,3.771 4.872,3.688 Z"/></svg>
											</span>
										</a>
									</li>
								</ul>-->
							</li>
						</ul>
					</div>
				</div>

				<!-- develper code -->
				<!-- <div class="box-body table-responsive">
					<table class="table display dataTableAjax" data-action-url="support/ticket_data"   data-refresh-dataTablePosition='0' data-orders="[[0,'DESC']]" data-sort="[{ targets: [1,2,3], orderable: false}]">
						<thead>
							
						</thead>
						<tbody></tbody>
						
						
					</table>
			  
            	</div> -->
			</div>
		</div>
	</div>
</div>


<div class="dis_ticket_trensfer_modal modal dis_center_modal fade in" id="transferTicket" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
		  <div class="modal-dialog" role="document">
			<div class="modal-content">
			  <div class="modal-header muli_font">
				<button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
				<h3 class="text-center mp_0">Transfer Ticket</h3>
			  </div>
			  <form class="transferTicketForm" action="support/transferTicket">
			  	<input type="hidden" name="ticket_id" id="ticket_id">
				<div class="modal-body muli_font">
					<div class="dis_field_box">
						<label class="dis_field_label">department</label>
						<div class="dis_select2 dis_field_wrap">
							<select class="primay_select dis_field_input require" name="department_id" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}">
								<option value="">Select Deparment</option>
								<?php foreach($department as $departments) {
										if($this->session->userdata('support_department')!=$departments['id']){
									?>
									<option value="<?=$departments['id']?>"><?=$departments['name']?></option>
								<?php } }  ?>
							</select>
						</div>                    
					</div>
				</div>
				<div class="modal-footer">
					<button type="submit" class="dis_btn min_width_inherit b-r-5">Save</button>
					</div>
				</form>
			</div>
		  </div>
		</div>