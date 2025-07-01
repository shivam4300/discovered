<div class="dis_cartWrap muli_font full_vh_foooter">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="dis_cartInner">
                    <div class="dis_p_header">
                        <div class="">
                            <h2 class="dis_sp_tpL_ttl mp_0">My Orders</h2>
                        </div>
                    </div>
                    <div class="dis_p_body">
                            <table class="dis_product_table">
                                <thead>
                                <tr>
                                    <!-- <th></th> -->
                                    <th></th>
                                    <th class="text-center">Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Order Id</th>
                                    <th class="text-center">Quantity</th>
                                    <th class="text-center">Date Placed</th>
                                    <th class="text-center">Status</th>
                                    <th class="text-center">Request</th>
                                    <th class="text-center">Action</th>
                                </tr>
                                </thead>
                                <tbody id="orders_tbody"></tbody>
                            </table>
                            <div id="load_orders_btn"></div>
                            <div class="dis_cartFooter">
                                <div class="dis_cartFooterL">
                                    <br>
                                    <a href="<?php echo base_url('store'); ?>"><span class="dis_linkbtn"> <span class="dis_sp_left"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(247, 101, 45)" d="M2.334,4.670 C2.562,4.670 2.688,4.670 2.814,4.670 C6.586,4.670 10.357,4.675 14.129,4.661 C14.362,4.660 14.655,4.582 14.816,4.432 C15.183,4.91 14.963,3.474 14.465,3.360 C14.323,3.328 14.170,3.327 14.23,3.326 C10.283,3.325 6.543,3.325 2.803,3.325 C2.678,3.325 2.554,3.325 2.363,3.325 C2.470,3.207 2.532,3.131 2.602,3.63 C3.233,2.445 3.866,1.829 4.496,1.210 C4.835,0.878 4.864,0.474 4.577,0.191 C4.294,0.88 3.873,0.63 3.536,0.265 C2.445,1.330 1.356,2.397 0.270,3.466 C0.88,3.820 0.88,4.173 0.271,4.528 C1.356,5.598 2.445,6.664 3.535,7.729 C3.883,8.69 4.271,8.88 4.569,7.794 C4.869,7.498 4.845,7.127 4.494,6.782 C3.799,6.99 3.100,5.419 2.334,4.670 Z"/></svg></span> Back To Shopping</a>
                                    <br>
                                </div>
                                <div class="dis_cartFooterR hideme">
                                    <ul class="dis_product_couponList">
                                        <li>
                                            <p class="dis_PC_ttl">Apply Discount</p>
                                        </li>
                                        <li>
                                            <div class="dis_product_couponBox">
                                                <input type="text" placeholder="Enter Coupon Code Here" class="dis_PC_input">
                                                <a href="" class="dis_OrangeBtn">Apply</a>
                                            </div>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                    </div>
                </div>
            </div>
            
        </div>
    </div>
</div>
<div class="modal fade muli_font delete_deactivate_modal dis_center_modal " id="order_modal" role="dialog">
    <div class="modal-dialog modal-md">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="dis_modal_title">Order Actions</h4>
        </div>
        <div class="modal-body">
        <div class="dis_cart_ttlBox" style="justify-content: center;">
			<div class="dis_cart_des">
				<p class="dis_sp_ttl">Order Id :</p>		
			</div>  
			<div class="dis_cart_des">
				<p id="order_key" class="discart_pc"></p>
            </div>
        </div>
        <div class="dis_cart_ttlBox">
            <div class="dis_cart_des">
                <p class="dis_sp_ttl">Product</p>			
            </div>  
            <div class="dis_cart_des" style="margin-left: auto;">
                <p class="dis_sp_ttl">Sub-Total</p>
            </div>
        </div>
        <div id="order_action_items" class="disCBL">
            
        </div>
        <hr>
        <div class="dis_cart_ttlBox">
			<div class="dis_cart_des">
				<p class="dis_sp_ttl">Total </p>		
			</div>  
			<div class="dis_cart_des" style="margin-left: auto;">
				<p id="ord_total" class="discart_pc"></p>
            </div>
        </div>
        <hr>
        
            <form id="order_action_form">
			<div class="dis_deleteacc_reason">		
				<div class="m_b_20">
					<label class="dis_field_label">Actions</label>
                    <div class="dis_sp_select" data-select2-id="257">
							<select class="primay_select dis_field_input select2-hidden-accessible" id="action" name="action" data-target="select2" data-option="{minimumResultsForSearch:-1, width:'100%'}" tabindex="-1" aria-hidden="true">
								<!-- <option value="" data-select2-id="2">Select Size</option> -->
                                <!-- <option value="empty" data-select2-id="260" disabled selected>-- Select --</option>
                                <option id="op_cancel" value="1" data-select2-id="261">Cancel</option>
                                <option id="op_return" value="2" data-select2-id="262">Return</option> -->
                            </select>
					</div>
                    <!-- <select class="primay_select dis_field_input" name="action" id="action">
                        <option value="-" disabled selected>-- action --</option>
                        <option value="cancel">Cancel</option>
                        <option value="return">Return</option>
                    </select> -->
					<label class="dis_field_label">Reason</label>
					<div class="dis_field_wrap">
						<textarea name="reason" class="dis_field_input" placeholder="Mention the reason" id="action_reason"></textarea>
					</div>
				</div>
				<div>
                    <input type="hidden" id="order_id" name="order_id">
                    <input type="hidden" id="ord_prod_id" name="ord_prod_id">
                    <button id="order_action_btn" type="submit" class="dis_btn brandon_font">Submit</button>
				</div>
            </form>
			</div>
        </div>
      </div>
    </div>
  </div>
  <!-- <span class="select2-container select2-container--default select2-container--open" style="position: absolute; top: 455px; left: 611px;"><span class="select2-dropdown select2-dropdown--below" dir="ltr" style="width: 57px;"><span class="select2-search select2-search--dropdown select2-search--hide"><input class="select2-search__field" type="search" tabindex="0" autocomplete="off" autocorrect="off" autocapitalize="none" spellcheck="false" role="textbox"></span><span class="select2-results"><ul class="select2-results__options" role="tree" id="select2--cb-results" aria-expanded="true" aria-hidden="false"><li class="select2-results__option" id="select2--cb-result-ok0c-373" role="treeitem" aria-selected="false" data-select2-id="select2--cb-result-ok0c-373">M</li><li class="select2-results__option select2-results__option--highlighted" id="select2--cb-result-c6dm-370" role="treeitem" aria-selected="false" data-select2-id="select2--cb-result-c6dm-370">XXL</li><li class="select2-results__option" id="select2--cb-result-u18z-340" role="treeitem" aria-selected="true" data-select2-id="select2--cb-result-u18z-340">S</li></ul></span></span></span> -->
<input type="hidden" id="my_orders_page" value="true">