
	<div class="dash_page_section">
		<!-- payouts  page start -->
			<div class="dash_payout_wrapper">
				<!-- top section start -->
				<div class="row">
					<div class="col-xs-12">
						<div class="dash_ctab_wrapper">
							<ul class="">
								<li class="active"><a data-toggle="tab" href="#home">Outstandings</a></li>
								<li ><a data-toggle="tab" href="#menu1" >History of Payments</a></li>
							</ul>

							<div class="tab-content ">
								<div id="home" class="tab-pane fade in active table_area">
								  <!-- advertising table section Start -->
									<div class="advet_table_mainwrapper table_area">
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
										<div class="row ">
											<div class="col-md-12">
												<div class="table_content">
													 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="backend/payouts/show_outstanding_details" data-table-position="0" data-target-section="tbody" cellspacing="0" width="100%" data-column-class="[{className: 'srno'},{className: 'entry_against'},{className: 'debit'},{className: 'credit'},{className: 'balance'},{className: 'created_at'}]"	data-filter='1'>
														<thead>
															<tr>
																<th class="srno">#</th>
																<!--th class="refrence">Reference No. </th-->
																<th class="entry_against">Entry Against</th>
																<th class="debit">Debit</th>
																<th class="credit">Credit</th>
																<th class="balance">Balance</th>
																
																<th class="created_at">Created At</th>
															</tr>
														</thead>
														<tbody>
															
														</tbody>
													</table>
												</div>
											</div>
										</div>
									</div>	
								</div>
								<div id="menu1" class="tab-pane fade">
									<div class="advet_table_mainwrapper table_area">
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
													 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="backend/payouts/show_payment_details" data-table-position="1" data-target-section="tbody" cellspacing="0" width="100%" data-column-class="[{className: 'srno'},{className: 'date'},{className: 'method'},{className: 'txid'},{className: 'short_icon'},{className: 'status'}]"	data-filter='1'>
														<thead>
															<tr>
																<th class="srno"><div class="tbl_serialno_head">#</div></th>
																<th class="date">Date</th>
																<th class="method">Payment Method</th>
																<th class="txid">Payment ID</th>
																<th class="short_icon">Amount</th>
																<th class="status">Status</th>
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
							</div>
						</div>
					</div>
				</div>
				<!-- top section End -->
				
				
			</div>	
		<!-- payout  page End-->
		
	
	</div>
