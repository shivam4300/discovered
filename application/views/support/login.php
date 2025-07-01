<div class="dis_support_login_wrap muli_font ">
    <div class="dis_support_login_inner">
        <div class="dis_support_login_box">
                <form class="loginForm" action="support/teamAuth">
                    
               
                    <h2 class="dis_support_login_ttl m_b_20">Support login</h2>
                    <div class="dis_field_box m_b_20">
                        <label class="dis_field_label">Email</label>
                        <div class="dis_field_wrap">
                            <input type="text" class="dis_field_input require" placeholder="Enter Your Email" name="email" value="<?=(isset($_COOKIE['sup_email']))?$_COOKIE['sup_email']:''?>" data-valid="email" data-error="Please enter valid email">
                        </div>                    
                    </div>
                    <div class="dis_field_box m_b_20">
                        <label class="dis_field_label">Password</label>
                        <div class="dis_field_wrap">
                            <input type="password" class="dis_field_input require" placeholder="Enter Your Password" name="password" value="<?=(isset($_COOKIE['sup_pwd']))?$_COOKIE['sup_pwd']:''?>">
                        </div>                    
                    </div>
                    <!-- <div class="dis_field_box m_b_20">
                        <div class="dis_field_wrap">
                            <input type="checkbox" id="rem_me" name="rem_me" value="1" <?=(isset($_COOKIE['sup_email']))?'checked':''?>>
                        </div>                    
                    </div> -->
                    <div class="dis_field_box m_b_20">
                        <div class="checkbox dis_checkbox">
                            <label>
                                <input type="checkbox" id="rem_me" name="rem_me" value="1" <?=(isset($_COOKIE['sup_email']))?'checked':''?>>
                                <i class="input-helper"></i>
                                <p>Remember Me</p>
                            </label>
                        </div>           
                    </div>
                    <div class="dis_field_box">
                        <div class="dis_field_wrap">
                            <button type="submit" class="dis_btn min_width_inherit b-r-5 w-100">Login</button>
                        </div>                    
                    </div>
                </form>
        </div>
    </div>
</div>