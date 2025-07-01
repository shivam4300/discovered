<style>	
.main-sidebar , .main-header{
	display:none;
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
				<div class="container" style="width: 100%">
					<div class="row">
						<?php foreach($images as $image){ ?>
							<div class="col-md-3">
								<img src="<?php echo base_url('uploads/admin/enquiry/'.$image['image_name']); ?>" style="width:100%;height:250px">
								<button onclick="setLink('<?php echo base_url('uploads/admin/enquiry/'.$image['image_name']); ?>')" type="button" class="btn btn-block btn-primary">Choose Image</button>
							</div>
							
						<?php } ?>
					</div>
				</div>
			</div>
		</div>
      </div>
    </section>
 </div>
<script>
	setTimeout(function(){
		document.querySelector('.sidebar-toggle').click();
	},1000);
	function setLink(url){  
		window.opener.CKEDITOR.tools.callFunction(1, url);
		window.close(); 			
	}
</script>
