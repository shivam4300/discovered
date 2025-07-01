<!-- <div class="ad_generatorWrap">
    <div class="ad_generatorTop">
        <div class="ad_generatorTop">

        </div>
    </div>
    <div class="ad_generatorBody">

    </div>
</div> -->



<div class="ad_generatorWrap">
    <div class="content-wrapper">

    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>MRSS Feed Generator</h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url('admin') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a>MRSS Feed Generator</a></li>
      </ol>
    </section>
    <input type="hidden" id="mrss_post_ids" value="">
    <!-- Main content -->
    <section class="content">
      <div class="row">
        <div class="col-xs-12">
           <div class="box">
                <div class="ad_generatorInner">
                    <div class="ad_generatorLeft">
                        <div class="box-body">
                            <div class="row">
                                <div class="col-lg-2 col-md-3">
                                    <label>Select Feeds</label>
                                        <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Mode',allowHtml:true,allowClear:true}" name="msn_feed_status"  class="form-control Mrssfilter" >
                                        <option value="0" selected>Not Published</option>
                                        <option value="1">Published</option>
                                        </select>
                                </div>

                                <div class="col-lg-2 col-md-3">
                                    <label>Search</label>
                                    <div class="ad_searchWrap">
                                        <input type="search" class="form-control ad_searchInput Mrssfilter" name="search[value]" placeholder="Search Here..">
                                    </div>
                                </div>
                                <div class="col-lg-2 col-md-3">
                                    <label>Select Mode</label>
                                        <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select Mode',allowHtml:true,allowClear:true}" name="mode"  class="form-control Mrssfilter" >
                                            <?php
                                            if(isset($web_mode)){
                                                foreach($web_mode as $list){
                                                    echo '<option value="'.$list['mode_id'].'">'.ucfirst($list['mode']).'</option>';
                                                }
                                                echo '<option value="10">Articles</option>';
                                            }
                                            ?>
                                        </select>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                <label>Select User Type</label>
                                    <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}" name="user_level"  class="form-control Mrssfilter" data-action-url="admin/getUserByCategory" data-id="#user_list" data-placeholder="Select User">
                                            <?php
                                            if(isset($category)){
                                                echo '<option value=""></option>';
                                                foreach($category as $list){
                                                    echo '<option value="'.$list['category_id'].'">'.$list['category_name'].'</option>';
                                                }
                                            }
                                            ?>
                                        </select>
                                </div>
                                <div class="col-lg-2 col-md-4">
                                    <label>Select User</label>
                                    <select data-target="select2" name="user_id"  class="form-control Mrssfilter" id="user_list">
                                        <?php echo '<option value=""></option>'; ?>
                                    </select>
                                </div>

                                <div class="col-lg-2 col-md-4">
                                    <label id="time-range">
                                        <div class="dis_product_pricettl">
                                            <p class="dis_product_priceFT">Duration: <span class="dis_product_priceF slider-time"> 0M </span> - <span class="dis_product_priceT slider-time2"> 10M </span></p>
                                        </div>

                                        <div class="sliders_step1">
                                            <div class="flat-slider" id="slider-range"></div>
                                        </div>
                                    </label>
                                </div>
                            </div>
                            <br>
                            <div class="row">
                                <div class="col-lg-3 col-md-4">
                                    <div class="form-group">
                                        <label>Select video size less than 1.5 GB</label>
                                        <input type="checkbox" class="Mrssfilter" placeholder="" name="filter_by_size" id="filter_by_size" value="0">
                                    </div>
                                </div>
                            </div>
                            <div class="ad_gen_BoxWrap">
                                <div class="ad_gen_BoxlIst" id="MrssVideos">
                                </div>
                                <div class="ad_gen_lm">
                                    <a href="javascript:;" class="ad_genRPbtn LoadMoreMrss" >Load More</a>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="ad_generatorRight">
                        <div class="ad_genSTop">
                                                    <!-- <h2 class="ad_genRTtl">Queue</h2>
                                                    <p class="ad_genRSTtl ">Total Duration: 01:56:12</p> -->
                        <a href="Javascript:;">
                            <i class="fa fa-list ad_genRTtl"></i> <span>Queue</span>
                            <span class="pull-right-container">
                            <small class="label pull-right bg-red ClearAllList">Clear all</small>
                            </span>
                        </a>
                        </div>
                        <div class="ad_genSbody">
                            <ul class="ad_genQueueList" id="ShowFeedQueue">
                                <li>
                                    <p>Loading.......</p>
                                </li>
                            </ul>
                        </div>
                        <div class="ad_genSBtm">
                            <a href="javascript:;" class="ad_genRPbtn MarkAsPublished"></a>
                            <ul class="ad_genRbtnList">
                                <li>
                                    <a href="javascript:;" id="viewMrssFeed" class="ad_genRbtnBtn">View Feed</a>
                                </li>
                                <li class="hide">
                                    <a href="javascript:;" id="publishMrssFeed" class="ad_genRbtnBtn">Publish</a>
                                </li>
                                <li>
                                    <input type="text" id="copyFeedUrl" style="display:none;">
                                    <a href="javascript:;" class="ad_genRbtnBtn copytoclipboard" data-target="#copyFeedUrl">Copy Feed URL</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="create720Video" class="ad_genRbtnBtn">Create 720</a>
                                </li>
                                <li>
                                    <a href="javascript:;" id="export_mrss_videos" class="ad_genRbtnBtn">Export</a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
</div>