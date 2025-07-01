<style>	
.select2-selection--single{
height:1% !important; 
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
      <div class="row">
        <div class="col-xs-12">
          <div class="box">
            <div class="box-header">
              <h3 class="box-title"><?= $pageTitle; ?></h3>
			</div>
            <!-- /.box-header -->
			
			<div class="container" style="width: 100%">
			   <ul class="nav nav-tabs">
				<li class="<?php if(!isset($_COOKIE['setTab'])){ echo "active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#home">GENERAL ENQUIRIES</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu1">HELP AND FAQ’S</a></li>
			  </ul>

			  <div class="tab-content">
				<div id="home" class="tab-pane fade <?php if(!isset($_COOKIE['setTab'])){ echo "in active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "in active";} } ?>">
					<div class="box-header">
					  <h3 class="box-title">GENERAL 
						<small>ENQUIRIES</small>
					  </h3>
					  <div class="pull-right box-tools">
						<button type="button" class="btn btn-block btn-primary getEnqury">ADD NEW ENQUIRIES</button>
					  </div>
					</div>
					<div class="box-body table-responsive">
						 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_setting/access_help_enquiry/2" data-target-section="tbody" data-column-class="[{className: 'srno'},{className: 'image'},{className: 'title'},{className: 'subject'},{className: 'p_status'},{className: 'action'}]" data-refresh-dataTablePosition='0'>
							<thead>
								<tr>
								  <th class="srno">Sr.NO.</th>
								  <th class="image">Image</th>
								  <th class="title">Title</th>
								  <th class="subject">Subject</th>
								  <th class="p_status">Privacy Status</th>
								  <th class="action">Action</th>
								  </tr>
							</thead>
							<tbody>
							</tfoot>
						  </table>
					</div>
					
				</div>
				
				<div id="menu1" class="tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "in active";} } ?> ">
				<div class="box-header">
				  <h3 class="box-title">HELP 
					<small>FAQ’S</small>
				  </h3>
				  <div class="pull-right box-tools">
					<button type="button" class="btn btn-block btn-primary getHelp">ADD NEW FAQ’S</button>
				  </div>
				</div>	
				<div class="row">
					<div class="box-body table-responsive">
						 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_setting/access_help_enquiry/1" data-target-section="tbody" data-column-class="[{className: 'srno'},{className: 'subject'},{className: 'p_status'},{className: 'description'},{className: 'action'}]" data-refresh-dataTablePosition='1'>
							<thead>
								<tr>
								  <th class="srno">Sr.NO.</th>
								  <th class="subject">Question</th>
								  <th class="p_status">Privacy Status</th>
								  <th class="description">Answer</th>
								  <th class="action">Action</th>
								  </tr>
							</thead>
							<tbody>
							</tfoot>
						  </table>
					</div>
				</div>
				</div>
			</div>
           
            <!-- /.box-body -->
          </div>
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
  </div>



<div class="modal fade" id="ENQUIRIES" role="dialog">
  <div class="modal-dialog  modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">ADD NEW ENQUIRIES</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="<?php echo base_url('admin_setting/add_new_enquiries'); ?>" method="POST"  id="SaveEnquiry">
		<input type="hidden" name="faq_id" id="faq_id" value="">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label for="exampleInputFile">Icon Image</label>
						<div class="ch_file">
							<input type="file" name="icon_image" id="icon_image">
							<span class="choosefile">Choose file</span>
						</div>
                </div>
				<div class="form-group">
					<label>Title</label>
					<input type="text" class="form-control require" name="title" value="" placeholder="Enter Title ..." id="title">
				</div>
				<div class="form-group">
					<label>Subject</label>
					<input type="text" class="form-control require" name="subject" value="" placeholder="Enter Subject ..." id="subject">
				</div>
				<div class="form-group">
					<label>Description</label>
					<input type="text" class="form-control ckeditor" name="description" value="" placeholder="Enter Description ..." id="description">
				</div>
				<div class="form-group">
					<label>Privacy status</label>
					<select class="form-control" name="show_status" value="" placeholder="Select privacy status" id="show_status">
						<option value='1'>Public</option>
						<option value='0'>Private</option>
					</select>
				</div>
			</div>
          </div>
          <!-- /.row -->
        </div>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" >Save changes</button>
	  </div>
	  </form>
	</div>
  </div>
</div>

<div class="modal fade" id="HELP_FAQ" role="dialog">
  <div class="modal-dialog  modal-lg">
	<div class="modal-content">
	  <div class="modal-header">
		<button type="button" class="close" data-dismiss="modal" aria-label="Close">
		  <span aria-hidden="true">&times;</span></button>
		<h4 class="modal-title">ADD NEW FAQ</h4>
	  </div>
	  <div class="modal-body">
		<div class="box-body">
		<form action="<?php echo base_url('admin_setting/add_new_faq'); ?>" method="POST"  id="SaveFAQ">
		<input type="hidden" name="faq_id" id="_faq_id" value="">
          <div class="row">
            <div class="col-md-12">
				<div class="form-group">
					<label>Question</label>
					<input type="text" class="form-control require" id="_subject" name="subject" value="" placeholder="Enter Question ...">
				</div>
				<div class="form-group">
                  <label>Answer</label>
                  <textarea class="form-control" rows="10" id="_description" name="description" placeholder="Enter Answer ..."></textarea>
                </div>
				<div class="form-group">
					<label>Privacy status</label>
					<select class="form-control" name="show_status" value="" placeholder="Select privacy status" id="_show_status">
						<option value='1'>Public</option>
						<option value='0'>Private</option>
					</select>
				</div>
			</div>
          </div>
          <!-- /.row -->
        </div>
		
	  </div>
	  <div class="modal-footer">
		<button type="button" class="btn btn-default pull-left" data-dismiss="modal">Close</button>
		<button type="submit" class="btn btn-primary" >Save changes</button>
	  </div>
	  </form>
	</div>
  </div>
</div>
