
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
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
			   <div class="pull-right box-tools">
					<div class="btn-group">
						<a  onclick="window.location.href='/admin_setting/add_home_slider/0/'+$('#select_mode').val()"  type="button" class="btn btn-block btn-primary" >Add New Slider</a>
					</div>
					<!-- <div class="btn-group">
						<a href="<?= base_url('admin/add_genre_slider'); ?>" type="button" class="btn btn-block btn-primary" >Add Genre Slider</a>
					</div> -->
			  </div>
            </div>
            <div class="box-body table-responsive tab-pane">
			<div class="row">
				<div class="col-xs-6">
					<div class="form-group">
					  <label>Select Mode *</label>
					  <select data-target="select2" id="select_mode" data-option="{closeOnSelect:false,placeholder:'Select Mode',allowHtml:true}" name="mode"  class="form-control filter require" data-error="Please Select the website mode">
							<?php 
							if(isset($web_mode)){
								foreach($web_mode as $list){
									$selected = ($list['mode_id'] == 1)?'selected="selected"':'';
									echo '<option '.$selected.' value="'.$list['mode_id'].'">'.$list['mode'].'</option>';
								}
								echo '<option value="article_sidebar">articles sidebar</option>';
							}
							?>
						</select>	
					</div>
				</div>
				<div class="col-xs-6">
					<div class="form-group hide">
					  <label>Select Articles Category *</label>
					  <select data-target="select2" id="select_category" data-option="{closeOnSelect:false,placeholder:'Select Mode',allowHtml:true}" name="article_category"  class="form-control filter require" data-error="Please Select the Articles Category">
							<?php 
							if(isset($article_categories)){
								foreach($article_categories as $list){
									$selected = ($list['id'] == 0)?'selected="selected"':'';
									echo '<option '.$selected.' value="'.$list['id'].'">'.$list['cat_name'].'</option>';
								}
							}
							?>
						</select>	
					</div>
				</div>
			</div>
			
				
              <table class="table nowrap hover display dataTableAjax" data-action-url="admin_setting/access_home_slider_videos" data-target-section="tbody" data-column-class="[{className:'handler'},{className: 'Title'},{className: 'action'}]"  data-refresh-dataTablePosition='0' data-filter='filter' data-callback='(aData[2]).search("checked")' data-sort=" [{'width':'50%','targets':1}]" data-pageLength = '500' data-bLengthChange="false">
                <thead>  
					<tr>
						<th class="handler">Reorder</th> 
						<th class="Title">Title</th>
						<th class="action">Action</th>
					</tr>
                </thead> 
                <tbody class=" sortable hp_slider_body " data-url="admin/Reorder_position/homepage_sliders">
                
                </tbody>
              </table > 
			  
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->

         
          <!-- /.box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>
  
	
