
	<div class="dash_page_section">
		<!-- payouts  page start -->
			<div class="dash_payout_wrapper">
				<!-- top section start -->
				<div class="row">
					<div class="col-xs-12">
						<div class="dash_ctab_wrapper">
							<ul class="">
								<li class="active"><a data-toggle="tab" href="#home">Statements</a></li>
								<li><a data-toggle="tab" href="#menu1" >Tax Documents</a></li>
							</ul> 

							<div class="tab-content">
								<div id="home" class="tab-pane fade in active table_area">
								  <!-- advertising table section Start -->
									<div class="advet_table_mainwrapper ">
										<div class="row">
											<div class="col-md-12">
												<div class="table_topmenus">
													<div class="left_menu">
														<div class="tbl_tm_dropdown tbl_datepicker">
															<input readonly class="daterange " type="text" name="date_range"/>
															<i class="tbl_date_icon fa fa-calendar" aria-hidden="true"></i>
														</div>
													</div>
													<div class="left_menu">
														<div class="tbl_tm_dropdown showall">
															<select  name="length" data-target="select2"  data-option="{closeOnSelect:true,placeholder:' All',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require filter">
															  <option value="">Show All</option>
															  <option value="10" selected>10</option>
															  <option value="20">20</option>
															  <option value="50">50</option>
															  <option value="100">100</option>
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
													 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="backend/statement/show_statement_details" data-table-position="0" data-target-section="tbody" cellspacing="0" width="100%" data-column-class="[{className: 'srno'},{className: 'month'},{className: 'amount'},{className: 'status'},{className: 'Statement'}]"	data-filter='1' data-refresh-dataTablePosition="0" data-orders="[[1,'DESC']]">
														<thead>
															<tr>
																<th class="srno">#</th>
																<!--th class="refrence">Reference No. </th-->
																<th class="month">Statement Month</th>
																<th class="amount">Total Amount</th>
																<th class="status">Paid Status</th>
																<th class="Statement">Statement</th>
															</tr>
														</thead>
														<tbody>
															
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div><!-- advertising table section End -->
									<div class="no_historyfound hide">
										<div class="no_history_inner">
											<img src="<?php echo base_url('repo/images/no_history.png'); ?>" class="img-fluid" />
											<h1 class="no_history_title">No Payments Made Yet</h1>
										</div>
									</div>
								</div>
								
								<div id="menu1" class="tab-pane fade">
									<div class="no_historyfound ">
										<div class="no_history_inner">
											<img src="<?php echo base_url('repo/images/no_history.png'); ?>" class="img-fluid" />
											<h1 class="no_history_title">No Payments Made Yet</h1>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- top section End -->
				
				
			</div>	
		<!-- payout  page End-->
		
	
	</div>
	
	<!-- Statement  Popup Start-->
	
	<div class="dis_common_popup dis_stmnt_popup" >
		
		<div class="common_popup_inner stmnt_inner" id="print_statement">
			<!--button type="button" class="print_pdf" data-id="print_statement">Print</button-->
			<div class="stmnt_innerbox " id="Show_statement">
				
			</div>
				
		<span class="common_close">
			 <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"></path></g> </svg>
		</span>
		</div>	
	</div>