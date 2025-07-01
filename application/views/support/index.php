<div class="dis_ticket_query_wrap dis_default_container muli_font p_t_50 p_b_50">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="dis_cmnbox">
                    <div class="dis_cmnbox_header text-center">
                        <h2 class="dis_cmnbox_header_ttl m_b_10">How Can We Help You?</h2>
                        <p class="dis_cmnbox_header_des">Please Fill The Form Below To Submit Your Ticket</p>
                    </div>
                    <div class="dis_cmnbox_body">
                        <form class="supportTicket" action="support/submit_ticket">
							<div class="dis_ticket_query_form">
								<div class="m_b_30">
									<div class="dis_field_box">
										<label class="dis_field_label">What can we help you with today?</label>
										<div class="dis_field_wrap">
											<ul class="dis_ticket_query_radio">
												<?php foreach($department as $key=>$value) { ?>
													<li>
														<div class="dis_box_radiobtn">
															<input class="require" type="radio" <?=($key==0)?'checked="checked"':''?> name="ticket_type" value="<?=$value['id']?>" id="query<?=$key?>">
															<label for="query<?=$key?>"><?=$value['name']?></label>
														</div>
													</li>
												<?php } ?>

											</ul>
										</div>
									</div>
								</div>
								<div class="m_b_30">
									<div class="dis_field_box">
										<label class="dis_field_label">Platform selection</label>
										<div class="dis_field_wrap">
											<select name="technology[]" class="primay_select dis_field_input" data-target="select2" data-option="{multiple:true,minimumResultsForSearch:-1, width:'100%'}">
												<?php
													$platform = $this->valuelist->platform();
													foreach($platform as $key => $val){
														echo "<option value='$key'>$val</option>";
													}
												?>
											</select>
										</div>
									</div>
								</div>
							    <div class="m_b_30">
									<div class="dis_field_box">
										<label class="dis_field_label">Subject</label>
										<div class="dis_field_wrap">
											<input type="text" class="dis_field_input require" placeholder="Subject" name="subject">
										</div>
									</div>
								</div>
							    <div class="m_b_30">
									<div class="dis_field_box">
										<label class="dis_field_label">Issue/Message</label>
										<div class="dis_field_wrap">
											<textarea type="text" name="message" class="dis_field_input require" placeholder="Enter Message Here"></textarea>
										</div>
									</div>
								</div>
							   <!-- <div class="m_b_30">
									<div class="dis_field_box">
										<label class="dis_field_label">Paste Attachment URL of Image/Video. Use Comma (,) to add multiple URL's</label>
										<div class="dis_field_wrap">
										<input type="text" class="dis_field_input require" placeholder="Enter URL" name="attachment_url">
										</div>
										<a href="javascript:void(0);" class="link_color d-inline m_t_10" data-toggle="modal" data-target="#imagevideo_url">How to create a URL? Click here to know</a>
									</div>
								</div> -->

								<div class="m_b_30">
									<div class="dis_field_box">
										<div class="dis_field_wrap">
											<input type="file" class="hide" id="file1" name="image_file[]" multiple="multiple">
											<label for="file1" class="dis_attach_label dis_field_input">
											   <span class="dis_attachFile_icon">
												<svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="15px"><path fill-rule="evenodd" fill="rgb(151, 151, 151)" d="M13.671,3.266 C12.939,1.466 11.561,0.458 9.683,0.351 L9.667,0.351 C8.289,0.377 7.261,0.780 6.432,1.621 C5.638,2.426 4.848,3.262 4.084,4.071 L2.795,5.432 C2.157,6.103 1.519,6.773 0.894,7.458 C0.375,8.028 0.092,8.750 0.054,9.603 C-0.009,11.030 0.733,12.339 1.947,12.936 C3.211,13.559 4.647,13.320 5.609,12.332 C7.349,10.545 9.080,8.701 10.565,7.113 C11.113,6.528 11.277,5.787 11.039,4.971 C10.824,4.236 10.280,3.686 9.582,3.498 C8.876,3.307 8.137,3.521 7.605,4.068 C6.855,4.838 6.118,5.621 5.380,6.403 L4.487,7.348 C4.417,7.422 4.363,7.492 4.322,7.561 C4.120,7.907 4.167,8.349 4.439,8.637 C4.711,8.926 5.141,8.979 5.462,8.765 C5.574,8.689 5.668,8.590 5.757,8.497 L7.015,7.172 C7.583,6.572 8.151,5.973 8.721,5.375 C8.821,5.269 9.082,5.052 9.340,5.312 C9.420,5.393 9.463,5.491 9.464,5.594 C9.466,5.722 9.404,5.859 9.290,5.979 L8.567,6.744 C7.229,8.158 5.891,9.573 4.545,10.980 C3.879,11.677 2.851,11.690 2.203,11.010 C1.543,10.318 1.555,9.248 2.229,8.521 C2.560,8.165 2.895,7.813 3.230,7.461 L3.614,7.059 C4.014,6.638 4.411,6.216 4.809,5.793 C5.709,4.836 6.640,3.847 7.579,2.903 C8.151,2.329 8.910,2.047 9.710,2.116 C10.531,2.184 11.288,2.617 11.786,3.304 C12.689,4.548 12.567,6.215 11.489,7.358 C10.452,8.457 9.412,9.554 8.371,10.650 L6.335,12.796 C6.141,13.003 6.035,13.254 6.036,13.503 C6.038,13.727 6.128,13.942 6.289,14.108 C6.451,14.274 6.648,14.356 6.850,14.356 C7.077,14.356 7.310,14.252 7.507,14.046 C7.842,13.698 8.174,13.347 8.506,12.996 L8.785,12.701 C9.196,12.266 9.611,11.835 10.026,11.404 C10.969,10.424 11.945,9.410 12.859,8.366 C14.148,6.894 14.429,5.131 13.671,3.266 Z"/></svg>
											   </span>
											Attach Image/Video</label>
											<p class="file_name"></p>
											<p >You can only upload mp4, pdf, jpeg, png and docx files</p>
											<p >You can upload a maximum of 2 files</p>
										</div>
									</div>
								</div>
								<div class="">
									<div class="dis_field_box">
										<button type="submit" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Submit Your Ticket</button>
									</div>
								</div>
							</div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
