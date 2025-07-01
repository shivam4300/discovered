<div class="dis_cartWrap muli_font full_vh_foooter">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-9">
                <div class="dis_cartInner">
                    <div class="dis_p_header">
                        <div class="">
                            <h2 class="dis_sp_tpL_ttl mp_0">My Wishlist</h2>
                        </div>
                    </div>
                    <div class="dis_p_body">
                            <table class="dis_product_table">
                                <thead>
                                <tr>
                                    <th></th>
                                    <th>Product</th>
                                    <th class="text-center">Price</th>
                                    <th class="text-center">Stock</th>
                                    <th class="text-center">Cart</th>
                                </tr>
                                </thead>
                                <tbody id="wishlist_tbody">
                         
                                </tbody>
                            </table>
                            <div class="dis_cartFooter">
                                <div class="dis_cartFooterL">
                                    <a href="<?php echo base_url('store'); ?>"><span class="dis_linkbtn"> <span class="dis_sp_left"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(247, 101, 45)" d="M2.334,4.670 C2.562,4.670 2.688,4.670 2.814,4.670 C6.586,4.670 10.357,4.675 14.129,4.661 C14.362,4.660 14.655,4.582 14.816,4.432 C15.183,4.91 14.963,3.474 14.465,3.360 C14.323,3.328 14.170,3.327 14.23,3.326 C10.283,3.325 6.543,3.325 2.803,3.325 C2.678,3.325 2.554,3.325 2.363,3.325 C2.470,3.207 2.532,3.131 2.602,3.63 C3.233,2.445 3.866,1.829 4.496,1.210 C4.835,0.878 4.864,0.474 4.577,0.191 C4.294,0.88 3.873,0.63 3.536,0.265 C2.445,1.330 1.356,2.397 0.270,3.466 C0.88,3.820 0.88,4.173 0.271,4.528 C1.356,5.598 2.445,6.664 3.535,7.729 C3.883,8.69 4.271,8.88 4.569,7.794 C4.869,7.498 4.845,7.127 4.494,6.782 C3.799,6.99 3.100,5.419 2.334,4.670 Z"/></svg></span> Back To Shopping</a>
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
            <div class="col-md-3">
                <div class="dis_product_totalBox">
                    <div class="dis_p_header">
                        <div class="">
                            <h2 class="dis_sp_tpL_ttl mp_0">Wishlist Total Items</h2>
                        </div>
                    </div>
                    <div class="dis_p_body">
                        <div class="dis_cart_T">
                            <ul class="dis_cart_totalList BB" id="cart_page_subtotal">
                                <!--li>
                                <p>Sub-Total</p>
                                <p>$119.80</p>
                                </li>
                                <li>
                                <p>Sub-Total</p>
                                <p>$119.80</p>
                                </li>
                                <li>
                                <p>Sub-Total</p>
                                <p>$119.80</p>
                                </li>
                                <li>
                                <p>Sub-Total</p>
                                <p>$119.80</p>
                                </li-->
                            </ul>
                        </div>
                        <div class="dis_cart_F">
                            <ul class="dis_cart_totalList" id="cart_page_total">
                                <li>
                                <p>Total-Items</p>
                                <p id="total_wish_items">0</p>
                                </li>
                            </ul>
                            <?php 
                                if(!empty($uid)){
                                    ?>
                                        <a href="<?=base_url('store/cart');?>" class="dis_OrangeBtn w-100" id="checkout_btn">View Cart
                            
                                            <span class="dis_sp_right"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.311,4.671 12.185,4.671 C8.413,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.091 0.036,3.475 0.534,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.325 8.456,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.207 12.467,3.132 12.397,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.089 11.127,-0.063 11.464,0.266 C12.554,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.173 14.729,4.528 C13.643,5.598 12.554,6.665 11.464,7.730 C11.116,8.069 10.728,8.089 10.430,7.795 C10.130,7.499 10.154,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"></path></svg></span>
                                            </a>
                                    
                                    <?php
                                }else{ ?>
                          
                                <a data-toggle="modal" data-target="#myModal" class="dis_OrangeBtn w-100" id="checkout_btn">Proceed To Checkout
                                
                                    <span class="dis_sp_right"><svg xmlns:xlink="http://www.w3.org/1999/xlink" width="15px" height="8px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M12.665,4.671 C12.438,4.671 12.311,4.671 12.185,4.671 C8.413,4.671 4.642,4.675 0.870,4.662 C0.637,4.661 0.344,4.583 0.184,4.433 C-0.183,4.091 0.036,3.475 0.534,3.361 C0.677,3.328 0.829,3.327 0.977,3.327 C4.717,3.325 8.456,3.326 12.196,3.326 C12.321,3.326 12.446,3.326 12.636,3.326 C12.529,3.207 12.467,3.132 12.397,3.063 C11.767,2.445 11.133,1.830 10.504,1.211 C10.165,0.878 10.136,0.475 10.422,0.191 C10.705,-0.089 11.127,-0.063 11.464,0.266 C12.554,1.331 13.643,2.398 14.729,3.467 C15.089,3.821 15.089,4.173 14.729,4.528 C13.643,5.598 12.554,6.665 11.464,7.730 C11.116,8.069 10.728,8.089 10.430,7.795 C10.130,7.499 10.154,7.127 10.506,6.782 C11.201,6.099 11.899,5.420 12.665,4.671 Z"></path></svg></span>
                                </a>
                                
                            
                            <?php } ?>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<input type="hidden" id="wishlist_page" value="true">