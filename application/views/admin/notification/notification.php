<style>
.btn-right{
	float:right;
	margin-left:5px;
}
</style>
<div class="content-wrapper">
<?php 	
	$checkItemData = (isset($page_menu))?explode('|' , $page_menu):array(); 
	$pageTitle = (isset($checkItemData[2]))?$checkItemData[2]:'';
?>
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
       <?= $pageTitle; ?>
      </h1>
      <ol class="breadcrumb">
        <li><a href="<?= base_url('admin') ?>"><i class="fa fa-dashboard"></i> Home</a></li>
        <li><a><?= $pageTitle; ?></a></li>
	  </ol>
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="row tab-pane">
        <div class="col-lg-9">
          <div class="box"> 
            <div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
			      </div>
                  <!-- /.box-header -->
            <div class="box-body">
              <div class="row">
                <div class="col-md-6">
                  <select class="form-control filter" name="user_level">
                    <option value='' selected>ALL</option>
                    <option value='1' >Icon</option>
                    <option value='2' >Emerging</option>
                    <option value='3' >Brand</option>
                    <option value='4' >Fan</option>
                    <option value='130' >Official</option>
                    </select>
                </div>
              </div>
            </div>
            <div class="box-body table-responsive">
              <table class="table nowrap hover display dataTableAjax" data-refresh-dataTablePosition='0' data-action-url="admin_notification/show_notifications" data-filter="parent_id" data-target-section="tbody" data-column-class="[{className: 'sr'},{className:'title'},{className: 'message'},{className: 'cate'},{className:'platforms'},{className:'image'},{className:'createdat'},{className:'results'}]"  data-orders="[[6,'DESC']]">
                <thead>
                  <tr>
                    <th class="sr">#</th>
                    <th class="title">Title</th>
                    <th class="message">Message</th>
                    <th class="cate">Category Name</th>
                    <th class="platforms">Platforms</th>
                    <th class="image">Image</th>
                    <th class="createdat">Created at</th>	
                    <th class="results">Result</th>	
                  </tr>
                </thead>
                <tbody>
                
                </tbody>
              </table>
            </div>
          <!-- /.box-body -->
          </div>
        </div>

        <div class="col-lg-3">
          <div class="box dis_notificationFIled">
            <form action="admin_notification/send_notification" class="myFormList">
              <div class="row">
                  <div class="col-lg-12 col-md-12">
                      <h2 class="dis_notiTTl">Send Push Notifications</h2>
                  </div>
                  <?php 
                    $platform_type = array('web' => 'Web','android' => 'Android','ios' => 'IOS');
                  ?>
                  <div class="col-lg-12 col-md-12 margin-bottom">
                    <label>Select Platform Type </label>
                    <ul class="dis_noticheckList">
                      <?php foreach( $platform_type as $key => $val){
                        echo '<li>
                                <input class="noti_check_input" type="checkbox" value="'.$key.'" id="'.$key.'" name="platform[]">
                                <label class="noti_check_label" for="'.$key.'">'.$val.'</label>
                              </li>';
                      }?>
                      
                    </ul>
                  </div>
                  <div class="col-lg-12 col-md-12 margin-bottom">
                    <label>Select User Type</label>
                    <select data-target="select2" data-option="{closeOnSelect:false,placeholder:'Select User Type',allowHtml:true,allowClear:true}" name="user_level"  class="form-control">
                        <?php	if(isset($category)){
                          echo '<option value="all">All</option>';
                          foreach($category as $list){
                            echo '<option  value="'.$list['category_id'].'">'.$list['category_name'].'</option>';
                          }
                        } 	?>
                    </select>	
                    </select>	
                  </div>
                  <div class="col-lg-12 col-md-12 margin-bottom">
                      <label>Title</label>
                      <div class="ad_searchWrap">
                          <input type="text" class="form-control require" name="title" placeholder="Enter Title">
                      </div>
                  </div>
                  <div class="col-lg-12 col-md-12 margin-bottom">
                      <label>Message</label>
                      <div class="ad_searchWrap">
                          <textarea type="text" class="form-control require" name="body" placeholder="Enter Message"></textarea>
                      </div>
                  </div>
                 
                 
                  
                  <div class="col-lg-12 col-md-12 margin-bottom">
                      <label>Target Link</label>
                      <div class="ad_searchWrap">
                          <input type="text" class="form-control require" name="link" placeholder="Enter Webpage Link">
                      </div>
                  </div>

                  <div class="col-lg-12 col-md-12 margin-bottom">
                    <label>Upload Image</label>
                      <label class="dis_AfminUploadImage" for="notification_image">
                          <input type="file" id="notification_image" name="icon" class="inputfile previewFile" data-id="#showImgPre">
                          <div class="dis_AfminUIL">
                              <span class="mu_upld_boxicon"><img id="showImgPre" width="70px" height="70px" src="<?= base_url('repo/images/cloud.svg');?>" alt="icon"></span>
                          </div>
                          <div id="file_field" class="dis_AfminUIR">
                              <!--h2 class="dis_AfminUITTL">Drag &amp; Drop Your Images Here</h2-->
                              <p class="dis_AfminUISub">Choose Image File</p>
                          </div>
                      </label> 
                  </div>
                  <!--div class="col-lg-12 col-md-12 margin-bottom">
                      <h2 class="dis_notiTTl">Webpage Link</h2>
                  </div-->
                 
                  <div class="col-lg-12 col-md-12">
                    <button  class="buttons-html5" data-savable="true" data-target="content" data-refresh-content="dataTable" data-refresh-dataTablePosition="0" data-action-url="admin_notification/show_notifications">Send</button>
                  </div>
              </div>
            </form>
          </div>
        </div>
     


















        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
        <!-- /.content -->
      </div>
  





