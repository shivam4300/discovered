<?php 

$order_id  = isset($orderDetails['id']) ? $orderDetails['id'] : '';
$shipping	  = isset($orderDetails['shipping']) ? $orderDetails['shipping'] : [];
$billing	  = isset($orderDetails['billing']) ? $orderDetails['billing'] : [];
$fname  	  = isset($shipping['first_name'])? $shipping['first_name']: '';
$lname 		  = isset($shipping['last_name'])? $shipping['last_name']: '';
$uemail 	  = isset($shipping['email'])? $shippingt['email']: '';
$uphone 	  = isset($shipping['phone'])? $shipping['phone']: '';
$country 	  = isset($shipping['country'])? $shipping['country']: '';
$state 		  = isset($shipping['state'])?$shipping['state'] 	: '';
$city 		  = isset($shipping['city'])? $shipping['city']: '';
$postcode 	  = isset($shipping['postcode'])? $shipping['postcode']: '';
$address      = isset($shipping['address_1'])? $shipping['address_1']: '';	

$b_fname  	  = isset($billing['first_name'])? $billing['first_name']: '';
$b_lname 	  = isset($billing['last_name'])? $billing['last_name']: '';
$b_email 	  = isset($billing['email'])? $billing['email']: '';
$b_phone 	  = isset($billing['phone'])? $billing['phone']: '';
$b_country 	  = isset($billing['country'])? $billing['country']: '';
$b_state 	  = isset($billing['state'])?$billing['state'] 	: '';
$b_city 	  = isset($billing['city'])? $billing['city']: '';
$b_postcode   = isset($billing['postcode'])? $billing['postcode']: '';
$b_address    = isset($billing['address_1'])? $billing['address_1']: '';


$currency 	  		= isset($orderDetails['currency'])? $orderDetails['currency']: '';	
$currency_symbol 	= isset($orderDetails['currency_symbol'])? $orderDetails['currency_symbol']: '';	
$total   			= isset($orderDetails['total'])? $orderDetails['total']: '';
$date_created 		= isset($orderDetails['date_created'])? date('F d,Y h:i a', strtotime($orderDetails['date_created'])): '';
$pay_mode     		= isset($orderDetails['payment_method'])? $orderDetails['payment_method']: '';
$transaction_id   	= isset($orderDetails['transaction_id'])? $orderDetails['transaction_id']: '';
$order_status     	= isset($orderDetails['status'])? $orderDetails['status']: '';
$pay_mode   		= isset($orderDetails['payment_method'])? $orderDetails['payment_method']: '';
$pay_mode   		= isset($orderDetails['payment_method'])? $orderDetails['payment_method']: '';
$line_items   		= isset($orderDetails['line_items'])? $orderDetails['line_items']: '';
$total_commission 	= 0; 

$orderStatusArr = array('pending'=>'Pending payment',
						'processing'=>'Processing',
						'on-hold'=>'On hold',
						'completed'=>'Completed',
						'cancelled'=>'Cancelled',
						'refunded'=>'Refunded',
						'failed'=>'Failed'
					);
?>


 <div class="dis_vendorOders_Wrap">
    <div class="dis_VO_adrs gi_section">
        <div class="dis_VO_adrsTop">
            <div class="dis_VO_adrsL">
                <ul class="dis_VO_adrsUL">
                    <li>
                        <p class="dis_VO_adrsULH">order details</p>
                        <p class="dis_VO_adrsULD">: #<?=$order_id;?></p>
						<input type="hidden" id="order_id" value="<?=$order_id;?>">
						<!--input type="hidden" id="vendor_id" value=""-->
                    </li>
                    <li>
                        <p class="dis_VO_adrsULH">order date</p>
                        <p class="dis_VO_adrsULD">: <?=$date_created;?></p> <!--August 31,2022 12:11 am-->
                    </li>
                    <li>
                        <p class="dis_VO_adrsULH">Payment Method</p>
                        <p class="dis_VO_adrsULD">: <?=$pay_mode;?></p>
                    </li>
                </ul>  
            </div> 
            <div class="dis_VO_adrsR">
            <ul class="dis_VO_adrsUL">
                    <li>
                        <p class="dis_VO_adrsULH">Name</p>
                        <p class="dis_VO_adrsULD">: <?=$b_fname.' '.$b_lname;?></p>
                    </li>
                    <li>
                        <p class="dis_VO_adrsULH">Email</p>
                        <p class="dis_VO_adrsULD">: <?=$b_email;?></p>
                    </li>
                    <li>
                        <p class="dis_VO_adrsULH">Phone</p>
                        <p class="dis_VO_adrsULD">: <?=$b_phone;?></p>
                    </li>
                </ul>  
            </div>       
        </div>       
        <div class="dis_VO_adrsbtm gi_inner_box">
            <ul class="dis_VO_adrsboxList">
                <li>
                    <div class="dis_VO_adrsbox">
                        <h2 class="dis_VO_adrsttl">Billing Address</h2>  
                        <p class="dis_VO_adrsDetl"><?=$b_fname.' '.$b_lname;?> <br> <?=$b_address?>, <?=$b_postcode?> <?=$b_city?> <?=$b_state?>, <?=$b_country?></p>   
                    </div>
                </li>
                <li>
                    <div class="dis_VO_adrsbox">
                        <h2 class="dis_VO_adrsttl">Shipping Address</h2>  
                        <p class="dis_VO_adrsDetl"><?=$fname.' '.$lname;?> <br> <?=$address?>, <?=$postcode?> <?=$city?> <?=$state?>, <?=$country?></p>   
                    </div>
                </li>
            </ul>
            
        </div>
    </div>    
    <div class="dis_VO_table">
    <div class="advet_table_mainwrapper table_area">
        <div class="row">
            <div class="col-md-12">                
                <div class="table_topmenus">
                    <div class="left_menu">                        
                        <div class="tbl_tm_dropdown showall">
                            <select data-target="select2"  data-option="{closeOnSelect:true,placeholder:' All',allowHtml:true,allowClear:false,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require" id="update_order_status">
								<?php 
									foreach($orderStatusArr as $k=>$s){
										$selected = ($k == $order_status) ? 'selected' : '';
										echo '<option value="'.$k.'" '.$selected.'>'.$s.'</option>';
									}										
								
								?>
                            </select>
                        </div>
                        <div class="tbl_tm_dropdown showall">
                            <select  data-target="select2"  data-option="{closeOnSelect:true,placeholder:' All',allowHtml:true,allowClear:false,minimumResultsForSearch:-1,width: '100%'}"  class="form_field require">
                                <option value="0">Refund Status</option>
                                <option value="refund_request">Refund Requested</option>
                                <option value="refund_accept">Refund Accepted</option>
                                <option value="refund_reject">Refund Rejected</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12">
                <div class="table_content">
                        <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="backend/playlist/show_playlist_details" data-table-position="0" data-target-section="tbody" cellspacing="0" width="100%" data-filter='1' data-refresh-dataTablePosition="0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th>Commission</th>
                            </tr>
                        </thead>
                        <tbody>
						
							<?php if(!empty($line_items)){
									foreach($line_items as $list){
											$commission = 0;
											if(!empty($list['meta_data'])){
												foreach($list['meta_data'] as $meta){
													if($meta['key'] == 'commission'){
														$commission = $meta['value'];
													}
												}
			
											}
										$total_commission += $commission;											
										
										$pro_img = isset($prod_img_arr[$list['product_id']]) ? $prod_img_arr[$list['product_id']] : '';
										
										echo '<tr>
												<td>
												<div class="dis_prdct_ttlBox">
													<div class="dis_prdct_img">
														<img src="'.$pro_img.'">
													</div>
													<div class="dis_prdct_des">
														<p class="dis_sp_ttl">'.$list['name'].'</p> 
														<p class="dis_sp_ttl"><b>SKU: '.$list['sku'].'</b></p>  														
													</div>  
												</div>
												</td>
												<td>'.$currency_symbol.''.$list['price'].'</td>
												<td>'.$list['quantity'].'</td>
												<td>'.$currency_symbol.''.$list['total'].'</td>
												<td>'.$currency_symbol.''.$commission.'</td>
											</tr>';
										
									}

							}
							
							
							?>
						
						
                            
                            <!--tr>
                                <td>
                                <div class="dis_prdct_ttlBox">
                                    <div class="dis_prdct_img">
                                        <img src="https://ecomm-test.discovered.tv/wp-content/uploads/2022/07/85eb12082b87c730ac91a7c39da93681.png&quot;">
                                    </div>
                                    <div class="dis_prdct_des">
                                        <p class="dis_sp_ttl">Coca Cola</p>                                        
                                    </div>  
                                </div>
                                </td>
                                <td>$290.00</td>
                                <td>05</td>
                                <td>$290.00</td>
                                <td>$90.00</td>
                            </tr>
                            <tr>
                                <td>
                                <div class="dis_prdct_ttlBox">
                                    <div class="dis_prdct_img">
                                        <img src="https://ecomm-test.discovered.tv/wp-content/uploads/2022/07/85eb12082b87c730ac91a7c39da93681.png&quot;">
                                    </div>
                                    <div class="dis_prdct_des">
                                        <p class="dis_sp_ttl">Coca Cola</p>                                        
                                    </div>  
                                </div>
                                </td>
                                <td>$290.00</td>
                                <td>05</td>
                                <td>$290.00</td>
                                <td>$90.00</td>
                            </tr>
                            <tr>
                                <td>
                                <div class="dis_prdct_ttlBox">
                                    <div class="dis_prdct_img">
                                        <img src="https://ecomm-test.discovered.tv/wp-content/uploads/2022/07/85eb12082b87c730ac91a7c39da93681.png&quot;">
                                    </div>
                                    <div class="dis_prdct_des">
                                        <p class="dis_sp_ttl">Coca Cola</p>                                        
                                    </div>  
                                </div>
                                </td>
                                <td>$290.00</td>
                                <td>05</td>
                                <td>$290.00</td>
                                <td>$90.00</td>
                            </tr>
                            <tr>
                                <td>
                                <div class="dis_prdct_ttlBox">
                                    <div class="dis_prdct_img">
                                        <img src="https://ecomm-test.discovered.tv/wp-content/uploads/2022/07/85eb12082b87c730ac91a7c39da93681.png&quot;">
                                    </div>
                                    <div class="dis_prdct_des">
                                        <p class="dis_sp_ttl">Coca Cola</p>                                        
                                    </div>  
                                </div>
                                </td>
                                <td>$290.00</td>
                                <td>05</td>
                                <td>$290.00</td>
                                <td>$90.00</td>
                            </tr-->
                        </tbody>
                    </table>
                </div>
                <div class="dis_vo_total">
                    <ul class="dis_VO_adrsUL">
                        <li>
                            <p class="dis_VO_adrsULH">Commission</p>
                            <p class="dis_VO_adrsULD">: <?=$currency_symbol;?><?=number_format($total_commission,2);?></p>
                        </li>
                        <li>
                            <p class="dis_VO_adrsULH">Total</p>
                            <p class="dis_VO_adrsULD">: <?=$currency_symbol;?><?=$total;?></p>
                        </li>
                        <li>
                            <p class="dis_VO_adrsULH">Total Earned</p>
                            <p class="dis_VO_adrsULD">: <?=$currency_symbol;?><?=number_format($total_commission,2);?></p>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    </div>    
</div>
