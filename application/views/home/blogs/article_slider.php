<?php $domain = get_domain_only(base_url()); ?>
<div class="dis_articlesWrap dis_articleSingle muli_font">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="dis_artheadingFIlter hideme" id="articles_filter">
                    <div class="dis_artHFL">
                        <ul class="dis_artHFLList">
                            <li>
                                <h2>My Articles</h2>
                            </li>
                            <li>
                                <ul class="dis_artHFLFIlter">
                                    <li>
                                        <div id="publish_status" class="dis_field_wrap dis_select2"> 
                                            <select data-target="select2" id="complete_status" name="complete_status" data-option="{closeOnSelect:true,placeholder:'Public',allowHtml:true,allowClear:false,minimumResultsForSearch:-1,width: '100%'}" class="primay_select dis_field_input SelectBySimpleSelect filterUserDirectory" id="selectMyMutualFriend" data-url="node/getSubCategoryList" data-id="#selectMySubMutualFriends">
                                            <option selected value="1">Published Articles</option>
                                            <option value="0">Articles In Draft</option>
                                            <!-- <option value="2">Articles In Trash</option> -->
                                            </select>
                                        </div>
                                    </li>
                                    <li>
                                        <div id="add_new_btn" class="dis_blogSBCreate">
                                            <a href="<?=base_url('articles/step1');?>" class="dis_btn gray_btn min_width_inherit b-r-5">
                                                <span class="dis_blogSBCIcon">
                                                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 22 19"><path fill="#FFF" fill-rule="evenodd" d="M20.535 4.974l-5.802 5.796a6.173 6.173 0 01-4.392 1.817.792.792 0 01-.793-.792c0-1.657.646-3.215 1.819-4.387l5.802-5.796a2.385 2.385 0 013.366 0c.931.929.93 2.433 0 3.362zm-1.122-2.242a.795.795 0 00-1.122 0l-5.802 5.797a4.586 4.586 0 00-1.272 2.391 4.59 4.59 0 002.394-1.271l5.802-5.796a.792.792 0 000-1.121zM7.269 9.417h-2.38a.793.793 0 110-1.585h2.38a.793.793 0 110 1.585zm0 3.17h-2.38a.792.792 0 110-1.585h2.38a.793.793 0 110 1.585zm4.275-7.925H4.889a2.382 2.382 0 00-2.38 2.378v9.51a.794.794 0 001.322.591l1.323-1.182a.793.793 0 01.529-.202h8.41a2.381 2.381 0 002.38-2.377v-1.778a.792.792 0 011.586 0v1.778a3.968 3.968 0 01-3.966 3.962H5.986l-1.097.98A2.381 2.381 0 01.922 16.55V7.04a3.97 3.97 0 013.967-3.963h6.655a.793.793 0 110 1.585z"/></svg>
                                                </span>
                                                Add new article
                                            </a>      
                                        </div>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </div>            
                </div>
                
                <div class="dis_articleSec">
                    <!-- <div class="dis_articleMBox m_b_30 text-center" id="<?= $domain; ?>_article_top">
                                 
                    </div> -->
                    <div class="dis_articleL" id="content">
                        <?php echo $this->load->view('common/skeletion_article',[],true) ?>
                    </div>
                    <div class="dis_articleR">  
                        <div class="dis_art_tgl">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>                  
                        <div id="sidebar">
                            <div class="dis_articleMBox m_b_30 text-center" id="<?= $domain; ?>_sidebar_1">
                                    <!--ADS AREA--->
                            </div>
                            
                            <div class="dis_articleMBox m_b_30">
                                <div class="dis_articleLRH m_b_20">
                                    <h2 class="dis_articleHeading">Search</h2>
                                </div> 
                                <div class="dis_blogSBSearch">
                                    <input type="text" id="art_search" class="dis_blogSBSearchInput search_content" data-search="#appendUl" data-api_url="articles/searchKeyWords" data-mode="<?=  $mode ?>" data-post="<?= $post_id ?>" data-searchType='titles' placeholder="Search here...">
                                    <span class="dis_blogSBSearchIcon"><img src="<?php echo base_url('repo/images/search.svg');?>" alt="icon"></span>
                                </div>
                                <ul class="list-group dis_listgroup custom_scrol" id="appendUl" data-append="#appendUl" style="display: none; position: inherit;">
                                </ul>
                            </div>
                            
                            <div class="dis_articleMBox m_b_30">
                                <div class="dis_articleLRH m_b_20">
                                    <h2 class="dis_articleHeading">Most Popular</h2>
                                </div>                                
                                <ul id="most_popular" class="dis_articleList DAS2 DAS2_mini">
                                    <?php echo $this->load->view('common/skeletion_article',[],true) ?>
                                </ul>
                            </div>
                            
                            <div class="dis_articleMBox m_b_30" id="<?= $domain; ?>_sidebar_2">
                                    <!--ADS AREA--->
                            </div>
                            
                            <div id="top_in_cat_div" class="dis_articleNumberWrap dis_articleMBox m_b_30">
                                <div class="dis_articleLRH m_b_20">
                                    <h2 class="dis_articleHeading" id="top_in_category"></h2>
                                </div>                                
                                <ul id="top_in_category_list" class="dis_articleList DAS2 DAS2_mini dis_Anumber">
                                    <?php echo $this->load->view('common/skeletion_article',[],true) ?>
                                </ul>
                            </div>
                            
                            <div class="dis_articleMBox m_b_30 text-center" id="taboola_ads1" >
                                    <!--ADS AREA--->
                            </div>
                          

                            <div class="dis_articleMBox m_b_30">
                                <div class="dis_articleLRH m_b_20">
                                    <h2 class="dis_articleHeading m_b_30">Categories </h2>
                                </div>                                
                                <ul id="categories_list" class="dis_bgartiList">
                                   
                                </ul>
                            </div>
                            
                            <div class="dis_articleMBox m_b_30 fixedAds text-center" id="<?= $domain; ?>_sidebar_3" >
                                    <!--ADS AREA--->
                            </div>
                        </div>
                    </div>
                </div>
                <div id="footer">Footer</div>
            </div>
        </div>
    </div>
</div>
<div style="display:none;" id="ads_container_load"></div>
<input type="hidden" id="page" value="article_slider">
<input type="hidden" id="mode" value="<?=  $mode ?>">
<input type="hidden" id="post_id" value="<?= $post_id ?>">
