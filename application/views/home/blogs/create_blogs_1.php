<?php

    $article_type = ['default','slider'];
?>

<div class="dis_createBlogWrap dis_ticket_query_wrap dis_default_container muli_font p_t_50 p_b_50 full_vh_foooter">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="dis_cmnbox">
                    <div class="dis_cmnbox_header text-center">
                        <!-- <span class="dis_EditartBack">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g> <g> <g> <path d="M492,236H68.442l70.164-69.824c7.829-7.792,7.859-20.455,0.067-28.284c-7.792-7.83-20.456-7.859-28.285-0.068 l-104.504,104c-0.007,0.006-0.012,0.013-0.018,0.019c-7.809,7.792-7.834,20.496-0.002,28.314c0.007,0.006,0.012,0.013,0.018,0.019 l104.504,104c7.828,7.79,20.492,7.763,28.285-0.068c7.792-7.829,7.762-20.492-0.067-28.284L68.442,276H492 c11.046,0,20-8.954,20-20C512,244.954,503.046,236,492,236z" fill="#000000" data-original="#000000" class=""></path> </g> </g> </g></svg>
                        </span> -->
                        <h2 id="first_step_head" class="dis_cmnbox_header_ttl">Add New Article</h2>
                    </div>
                    <div class="dis_cmnbox_body">
                        <form name="blog_form_1" id="blog_form_1" class="supportTicket form" action="<?php base_url('article/create_update_form1') ?>">
                        <div class="dis_ticket_query_form">
                            <div class="dis_createBTop">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="m_b_30">
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">Title</label>
                                                <div class="dis_field_wrap">
                                                    <input type="text" id="art_title" name="art_title" class="dis_field_input require" placeholder="Enter Title">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="m_b_30">
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">category</label>
                                                <div class="dis_field_wrap">
                                                    <select id="category_select" name="art_category" class="primay_select dis_field_input">

                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
									<div class="col-md-6">
                                        <div class="m_b_30">
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">Blog Author Name</label>
                                                <div class="dis_field_wrap">
                                                    <input type="text" name="art_auth_name" id="art_auth_name" class="dis_field_input require" placeholder="Blog Author Name">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="m_b_10">
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">tags</label>
                                                <div class="dis_field_wrap dis_Custmtoken">
                                                    <input type="text" name="art_tags" class="dis_field_input require" id="tokenfield" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="m_b_10">
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">Privacy</label>
                                                <div class="dis_field_wrap dis_Custmtoken">
                                                    <div class="dis_select2">
                                                        <select data-target="select2" id="privacy_status" name="art_privacy_status" data-option="{closeOnSelect:true,placeholder:'All Category',allowHtml:true,minimumResultsForSearch:-1,width: '100%'}" class="primay_select dis_field_input">
                                                            <option title="Anyone can see" selected value="7">Public</option>
                                                            <option title="Only me can see" value="5">Only Me</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="m_b_10">
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">Type</label>
                                                <div class="dis_field_wrap dis_Custmtoken">
                                                    <div class="dis_select2">
                                                        <select data-target="select2" id="article_type" name="art_article_type" data-option="{closeOnSelect:true,placeholder:'All Category',allowHtml:true,minimumResultsForSearch:-1,width: '100%'}" class="primay_select dis_field_input">
                                                            <option title="Article will show in Single Page" selected value="default">Default</option>
                                                            <option title="Article will show in Slider" value="slider">Slider</option>
                                                        </select>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

								</div>
                            </div>
                            <div class="dis_createBBtm">
                                <div class="row">

                                    <div class="col-md-12">
                                            <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
                                            <button type="submit" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Next</button>

                                    </div>
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
<div class="modal dis_athment_url_modal dis_center_modal muli_font fade" id="select_mode_modal" role="dialog">
<div class="modal-dialog">
    <div class="modal-content">
        <div class="modal-header">
        <button type="button" class="dis_cmn_close" data-dismiss="modal">×</button>
        <h4 class="modal-title text-center">Select Mode</h4>
        </div>
        <div class="modal-body">
            <span class="disBlog_PP"><input type="button" data-num="" id="custom" class="btn btn-primary mode" value="Custom"></span>
            <!-- <span class="disBlog_PP"><input type="button" data-field="ShowGifhy" id="SearchGifhy" class="btn btn-primary mode" value="Giphy"></span> -->
            <!-- <span class="disBlog_PP"><input type="button" data-field="ShowTenor" id="SearchTenor" class="btn btn-primary mode" value="Tenor"></span> -->
            <span class="disBlog_PP"><input type="button" data-field="ShowPixabay" id="SearchPixabay" class="btn btn-primary mode" value="Pixabay"></span>
            <!-- <span class="disBlog_PP"><input type="button" data-field="ShowUnsplash" id="SearchUnsplash" class="btn btn-primary mode" value="Unsplash"></span> -->
            <div class="searchbar">
                <input type="text" class="dis_field_input" name="search" id="search">
            </div>
            <div id="" style="height:200px;overflow-y: scroll;" class="show_content"></div>
        </div>
    </div>
</div>
</div>
<input type="hidden" id="page" value="create_blog_1">
