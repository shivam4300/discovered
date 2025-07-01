<div class="dis_createBlogWrap dis_ticket_query_wrap dis_default_container muli_font p_t_50 p_b_50 full_vh_foooter">
    <div class="container">
        <div class="row">
            <div class="col-md-12">
                <div class="dis_cmnbox">
                    <div class="dis_cmnbox_header text-center">
                        <h2 class="dis_cmnbox_header_ttl">Add New Article</h2>
                    </div>
                    <div class="dis_cmnbox_body">
                        <form name="blog_form" id="blog_form" class="supportTicket form" action="">
                        <div class="dis_ticket_query_form">
                            <div class="dis_createBTop">
                                <div class="row">
                                    <div class="col-md-6">
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
                                                <label class="dis_field_label">Slug</label>
                                                <div class="dis_field_wrap">
                                                    <input type="text" id="art_slug" name="art_slug" class="dis_field_input require" placeholder="Slug">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div>
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">category</label>
                                                <div class="dis_field_wrap">
                                                    <select id="category_select" name="art_category" class="primay_select dis_field_input">
                                                        <option value='01'>Entertainment</option>
                                                        <option value='01'>Music</option>
                                                        <option value='01'>Technology</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div>
                                            <div class="dis_field_box">
                                                <label class="dis_field_label">tags</label>
                                                <div class="dis_field_wrap">
                                                    <input type="text" name="art_tags" class="dis_field_input require" id="tokenfield" value="" />
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="dis_createBBtm">
                                <div class="row">
                                    <div id="para">
                                    <div class="col-md-12">
                                        <div class="m_b_30">
                                            <ul class="dis_createBlogIB">
                                                <li style="display:none;">
                                                    <div class="dis_field_box">
                                                        <div class="dis_field_wrap">
                                                            
                                                            <input type="file" class="hide" id="blog_u1_0" data-num="0" name="art_images0[]" multiple="multiple" onChange="img_pathUrl(this, 0);">
                                                            <label for="blog_u1_0" class="dis_attach_label dis_field_input">Upload Image</label>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="dis_field_box">
                                                        <div class="dis_field_wrap">
                                                            
                                                        <button id="" type="button" data-num="0" class="dis_attach_label dis_field_input mode_modal">Upload mode</button>
                                                        </div>
                                                    </div>
                                                </li>
                                                <li>
                                                    <div class="dis_field_box">
                                                        <div class="dis_field_wrap">
                                                            <button id="addNewPara" type="button" class="dis_attach_label dis_field_input">Add New Paragraph</button>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                            <p class="dis_createB_note">First image will be considered as hero image.  <a href="javascript:void(0);" class="link_color d-inline" data-toggle="modal" data-target="#hero_img">Click here</a> to see the default hero image.</p>
                                        </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="m_b_30">
                                            <ul class="dis_createBlogIB" id="show_images0">
                                                
                                            </ul>
                                          </div>
                                    </div>
                                    <div class="col-md-12">
                                        <div class="m_b_30">
                                            <textarea id="editor0" name="ckeditor[]" ></textarea>
                                        </div>
                                    </div>
                                    </div>
                                    <div class="col-md-12">
                                            <input type="hidden" name="hidden_ckeditor" id="hidden_ckeditor" value="" />
                                            <input type="hidden" name="complete_status" id="complete_status" value="1" />
                                            <input type="hidden" name="post_id" id="post_id" value="" />
                                            <button type="submit" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Submit</button>
                                        </div>
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
