<div class="dis_ticket_wrap  muli_font full_vh_foooter p_t_50 p_b_50">
    <div class="container">
        <div class="row">
            <?php //print_r($ticketData);?>
            <div class="col-md-12">
                <div class="dis_cmnbox">
                    <div class="dis_cmnbox_header text-center">
                        <h2 class="dis_cmnbox_header_ttl m_b_10">Your Tickets</h2>
                        <p class="dis_cmnbox_header_des">View Your Tickets Or Raise A New Ticket</p>
                    </div>
                    <div class="dis_cmnbox_body">
                        <div class="dis_ticket_topbtn m_b_40">
                            <ul class="dis_ticket_topbtn_list">
                                <li>
                                    <div class="dis_field_wrap dis_select2">
                                        <select class="primay_select dis_field_input filterBy" name="ticket_status" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}" id="filterByStatus">
                                            <option value="">Filter By Status</option>
                                            <option value="0">Open Ticket</option>
                                            <option value="1">Replied Ticket</option>
                                            <option value="2">Close Ticket</option>
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
								<li>
									<div class="dis_daterangePicker">
										<input class="daterange" type="text" name="date_range" placeholder="Date Range" autocomplete="off">
										<i class="tbl_date_icon fa fa-calendar" aria-hidden="true"></i>
									</div>
								</li>
                                <li>
                                    <a href="<?=base_url('support/create_ticket')?>" class="dis_btn min_width_inherit b-r-5">Create A New Ticket</a>
                                </li>
                            </ul>
                        </div>
                        <ul class="dis_ticketbox_list" id="appendTicketList" data-ticket_type="frontend">


                        </ul>
                    </div>
                    <div class="dis_sprtAdmin_footer">
						<ul class="dis_sprtAdmin_footerlist">
							<li>
								<h2 class="dis_sprtAdmin_showing"></h2>
							</li>
							<li id="pagination">

							</li>
						</ul>
					</div>
                </div>
            </div>
        </div>
    </div>
</div>