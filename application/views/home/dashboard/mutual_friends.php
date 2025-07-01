<div class="dis_allcreators_wrap full_vh_foooter">
    <ul class="dis_tab_list">
        <li class="active dis_tab_li">
            <a data-toggle="tab" href="#add_dir" class="dis_tab_item ActiveDirecotryTab" data-type="addtodirectory">Add To Directory <span id="usersYouMayKnow"></span></a>
        </li>
        <li class="dis_tab_li">
            <a data-toggle="tab" href="#my_dir" class="dis_tab_item ActiveDirecotryTab" data-type="mydirectory">My Directory <span id="usersIFollowed"></span></a>
        </li>
        <li class="dis_tab_li">
            <a data-toggle="tab" href="#fan_dir" class="dis_tab_item ActiveDirecotryTab" data-type="myfan">My Fans <span id="usersWhoFollowedMe"></span></a>
        </li>
    </ul>
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="dis_headingFilter">
                    <ul class="dis_allcrtr_filterList">
                        <li>
                            <div class="">
                                <div class="dis_c_searchWrap muli_font">
                                    <div class="dis_c_searchicon">
                                        <svg xmlns="https://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 485.213 485.213"><g><g><g><path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324    c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path>
                                            <path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951    C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46    c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465    C318.424,257.208,257.206,318.416,181.956,318.416z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path><path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path></g></g></g>
                                        </svg>
                                    </div>
                                    <input type="text" placeholder="Search..." class="dis_c_searchInput filterUserDirectory" id="searchMyMutualFriend">
                                </div>
                            </div>
                        </li>
                        <!-- <li><p class="mp_0">Filter</p></li> -->
                        <li>
                            <div class="dis_field_wrap dis_select2"> 
                                <select data-target="select2" data-option="{closeOnSelect:true,placeholder:'All Category',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}" class="primay_select dis_field_input SelectBySimpleSelect filterUserDirectory" id="selectMyMutualFriend" data-url="node/getSubCategoryList" data-id="#selectMySubMutualFriends">
                                <option value=" ">All Category </option>
                                <?php
                                    foreach($cate_info as $info){
                                        echo '<option value="'.$info['category_id'].'">'.$info['category_name'].'</option>';
                                    }
                                ?>
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="dis_field_wrap dis_select2">
                                <select data-target="select2" multiple data-option="{closeOnSelect:true,placeholder:'All Sub Category',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}" class="primay_select dis_field_input filterUserDirectory" id="selectMySubMutualFriends" >
                                    <option value="">All Sub Category </option>
                                
                                </select>
                            </div>
                        </li>
                        <li>
                            <div class="dis_field_wrap dis_select2">
                                <select data-target="select2" multiple data-option="{closeOnSelect:true,placeholder:'Select Country',allowHtml:true,allowClear:true,minimumResultsForSearch:-1,width: '100%'}" class="primay_select dis_field_input filterUserDirectory" id="selectCountry" >
                                    <option value="">Select Country</option>
                                    <?php
                                        foreach($country_info as $info){
                                            echo '<option value="'.$info['country_id'].'">'.$info['country_name'].'</option>';
                                        }
                                    ?>
                                </select>
                            </div>
                        </li>
                    </ul>
                </div>
    
                <div class="dis_tab_content tab-content">
                    <div id="add_dir" class="tab-pane fade in active">
                        <div class="row">                   
                            <h2 class="dis_allcreators_heading text-center">Brands, Creators & Fans You May Want To Add To Your Directory</h2>
                        </div>	
                        <div class="row" id="addtodirectory"> 
                        </div>	
                    </div>

                    <div id="my_dir" class="tab-pane fade">
                        <div class="row">                   
                            <h2 class="dis_allcreators_heading text-center">Brands & Creators You Follow</h2>
                        </div>	
                        <div class="row" id="mydirectory">  
                        </div>	
                    </div>

                    <div id="fan_dir" class="tab-pane fade">
                        <div class="row">                   
                            <h2 class="dis_allcreators_heading text-center">Brands & Creators that follow you </h2>
                        </div>	
                        <div class="row" id="myfan">  
                        </div>	
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>	



