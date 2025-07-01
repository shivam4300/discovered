<style> .dis_sortable_placeholder{
        background-color: #f3f3f3;
         padding-right:10px;
         padding-left:10px;
         text-align:center;
         width:50px;
         height:60px;
    }
</style>
<div class="dis_createBlogWrap dis_ticket_query_wrap dis_default_container muli_font p_t_50 p_b_50 full_vh_foooter">
    <div class="container">
        <div id="sort_containment" class="row">
            <div class="col-md-12">
                <div class="dis_cmnbox">
                    <div class="dis_cmnbox_header text-center">
                        <span class="dis_EditartBack">
                            <svg xmlns="http://www.w3.org/2000/svg" version="1.1" xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="512" height="512" x="0" y="0" viewBox="0 0 512 512" style="enable-background:new 0 0 512 512" xml:space="preserve" class=""><g>
                            <g>
                                <g>
                                    <path d="M492,236H68.442l70.164-69.824c7.829-7.792,7.859-20.455,0.067-28.284c-7.792-7.83-20.456-7.859-28.285-0.068
                                        l-104.504,104c-0.007,0.006-0.012,0.013-0.018,0.019c-7.809,7.792-7.834,20.496-0.002,28.314c0.007,0.006,0.012,0.013,0.018,0.019
                                        l104.504,104c7.828,7.79,20.492,7.763,28.285-0.068c7.792-7.829,7.762-20.492-0.067-28.284L68.442,276H492
                                        c11.046,0,20-8.954,20-20C512,244.954,503.046,236,492,236z" fill="#000000" data-original="#000000" class=""></path>
                                </g>
                            </g>

                            </g></svg>
                        </span>
                        <h2 id="head" class="dis_cmnbox_header_ttl">Add New Article</h2>
                    </div>
                    <div class="dis_cmnbox_body">
                        <form name="blog_form_2" id="blog_form_2" class="supportTicket form" action="">
                        <div class="dis_ticket_query_form">

                            <div class="dis_createBBtm">
                                <p class="dis_createB_note">First image will be considered as hero image.  <a href="javascript:void(0);" class="link_color d-inline" data-toggle="modal" data-target="#hero_img">Click here</a> to see the default hero image.</p>
                                <div class="row">
                                    <div id="para">

                                    </div>
                                    <div class="col-md-12">
                                            <input type="hidden" name="post_id" id="post_id" value="<?php echo $post_id; ?>" />
                                            <button id="submit" type="button" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Submit</button>
                                            <button id="preview" type="button" class="dis_btn min_width_inherit b-r-5 dis_btn_loading">Preview</button>
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

<input type="hidden" id="page" value="create_blogs_2">
<input type="hidden" id="slider_type" value="<?= $article['article_type'];?>">

<!-- <div class="modal dis_athment_url_modal dis_center_modal muli_font fade" id="select_mode_modal" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="dis_cmn_close" data-dismiss="modal">×</button>
                <h4 class="modal-title text-center">Select Image</h4>
            </div>
            <div class="modal-body">
                <span class="disBlog_PP"><input type="button" data-num="" id="custom_" class="btn btn-primary mode" value="Custom"></span> -->
                <!-- <span class="disBlog_PP"><input type="button" data-field="ShowGifhy" id="SearchGifhy" class="btn btn-primary mode" value="Giphy"></span> -->
                <!-- <span class="disBlog_PP"><input type="button" data-field="ShowTenor" id="SearchTenor" class="btn btn-primary mode" value="Tenor"></span> -->
                <!-- <span class="disBlog_PP"><input type="button" data-field="ShowPixabay" id="SearchPixabay" class="btn btn-primary mode" value="Pixabay"></span> -->
                <!-- <span class="disBlog_PP"><input type="button" data-field="ShowUnsplash" id="SearchUnsplash" class="btn btn-primary mode" value="Unsplash"></span> -->
                <!-- <div class="searchbar">
                    <input type="text" class="dis_field_input" name="search" id="search">
                </div>
                <div id="" style="height:200px;overflow-y: scroll;" class="show_content"></div>
            </div>
        </div>
    </div>
</div>
 -->


<!-- create blog  hero image-->
<div class="modal dis_athment_url_modal dis_center_modal muli_font fade" id="hero_img" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
            <button type="button" class="dis_cmn_close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title text-center">First image will be considered as hero image.</h4>
            </div>
            <div class="modal-body">
                <span class="disBlog_PP"><img src="<?php echo base_url('repo/images/blog_pp.png');?>" alt="icon" class="img-responsive" width="100%"></span>
            </div>
        </div>
    </div>
</div>


<div class="modal dis_Articles_addimages dis_center_modal muli_font fade" id="select_mode_modal" role="dialog">
    <div class="modal-dialog ">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="dis_cmn_close" data-dismiss="modal">×</button>
                <h4 class="modal-title text-center">Select Media</h4>
            </div>
            <div class="modal-body">
                <ul class="dis_tab_list">
                    <li class="active dis_tab_li">
                        <a data-toggle="tab" href="#custom_image" class="dis_tab_item">Custom Image</a>
                    </li>
                    <li class="dis_tab_li">
                        <a data-toggle="tab" href="#Pixabay" class="dis_tab_item">PixaBay</a>
                    </li>
                    <li class="dis_tab_li video_lib">
                        <a data-toggle="tab" href="#video_library" class="dis_tab_item">Video</a>
                    </li>
                </ul>
                <div class="dis_tab_content tab-content">
                    <div id="custom_image" class="tab-pane fade in active">
                        <div class="dis_artCI_inner">
                            <form id="submit_image">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="m_b_30 ">
                                        <label class="dis_artUploadImage" for="articleCustom">
                                            <input type="file" id="articleCustom" name="file" class="inputfile" data-counter="" data-for_id="0" onChange="show_img_blob(this);">
                                            <input type="hidden" name="input_id" id="input_id" value="">
                                            <div class="dis_artUIL">
                                                <span class="mu_upld_boxicon"><img src="https://test.discovered.tv/repo/images/cloud.svg"  alt="icon"></span>
                                            </div>
                                            <div id="file_field" class="dis_artUIR">
                                                <h2 class="dis_artUITTL">Drag & Drop Your Images Here</h2>
                                                <p class="dis_artUISub">Or Choose File</p>
                                                <h2 class="dis_artUITTL">Only JPG, JPEG, PNG and GIF files are allowed.</h2>
                                            </div>
                                            <div style="display:none;" id="show_blob" class="dis_artUIR">
                                                <img id="show_blob_img" src="" alt="">
                                            </div>

                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="m_b_30">
                                        <div class="dis_field_box">
                                            <label class="dis_field_label">Author</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" id="" name="author" class="dis_field_input require" placeholder="Enter Author" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="m_b_30">
                                        <div class="dis_field_box">
                                            <label class="dis_field_label">Publisher</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" id="" name="publisher" class="dis_field_input require" placeholder="Enter Source" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="m_b_30">
                                        <div class="dis_field_box">
                                            <label class="dis_field_label">License id</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" id="" name="license_id" class="dis_field_input require" placeholder="Enter license" required>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <button id="" type="submit" class="submit_img_btn dis_btn min_width_inherit b-r-5">Submit</button>
                                </div>
                            </div>
                            </form>
                        </div>
                    </div>
                    <div id="Pixabay" class="tab-pane fade">
                        <div class="dis_artPixa_inner">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="m_b_30">
                                        <div class="dis_field_box">
                                            <label class="dis_field_label">Search Images</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" id="img_search" name="" class="SearchPixabay dis_field_input require" placeholder="Write here and press enter...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="m_b_30">
                                        <ul style="display:none;" id="ShowPixabay" class="dis_artPixa_List">

                                        </ul>
                                        <div class="m_t_10 text-center">
                                            <button style="display:none;" id="loadMoreBtn" type="button" class="SearchEnter dis_btn gray_btn min_width_inherit b-r-5 h_40">Load More</button>
                                        </div>
                                    </div>
                                </div>
                                <div style="display:none;" id="pixabay_submit" class="col-md-12">
                                    <button id="submit_url_image" type="button" class="submit_img_btn dis_btn min_width_inherit b-r-5">Submit</button>
                                </div>

                            </div>
                        </div>
                    </div>
                    <div id="video_library" data-start="0" class="tab-pane fade">
                        <div class="dis_artPixa_inner">
                            <div class="row">

                                <div class="col-md-12">
                                    <div class="m_b_30">
                                        <div class="dis_field_box">
                                            <label class="dis_field_label">Search Videos</label>
                                            <div class="dis_field_wrap">
                                                <input type="text" id="video_search" name="" class="search_video_lib dis_field_input require" placeholder="Write here and press enter...">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-12">
                                    <div class="m_b_30">
                                        <ul id="ShowVideoLibrary" class="dis_artPixa_List">

                                        </ul>
                                        <div class="m_t_10 text-center">
                                            <button id="loadMoreBtnVideoLib" data-start="0" type="button" class=" dis_btn gray_btn min_width_inherit b-r-5 h_40">Load More</button>
                                        </div>
                                    </div>
                                </div>
                                <div id="video_lib_submit" class="col-md-12">
                                    <button id="submit_video_url" type="button" class="submit_img_btn dis_btn min_width_inherit b-r-5">Submit</button>
                                </div>

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="<?= base_url('repo_admin/js/sweetalert.min.js') ?>" ></script>
<input type="hidden" id="error" value="">