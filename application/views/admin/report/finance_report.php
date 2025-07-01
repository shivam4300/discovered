<style>
	.dataTables_filter {display: none;}
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
				<li class="<?php if(!isset($_COOKIE['setTab'])){ echo "active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#home">Advertising Report</a></li>
				<!--li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu1">Payouts Report</a></li-->
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu1">Payment History</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu2'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu2">Payment Batch</a></li>
				<li class="<?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu3'){echo "active";} } ?>"><a data-toggle="tab" class="setTab" href="#menu3">Payout Statement</a></li>
			  </ul>

			  <div class="tab-content">
				<div id="home" class=" tab-pane fade <?php if(!isset($_COOKIE['setTab'])){ echo "in active"; } ?> <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#home'){echo "in active";} } ?>">
					<div class="box-header">
					  <h3 class="box-title">All 
						<small>Report</small>
					  </h3>
					  <div class="pull-right box-tools">
						<!--button type="button" class="btn btn-block btn-primary getEnqury">ADD NEW REPORT</button-->
					  </div>
					</div>
					<div class="box-body">
						<div class="row">
							<div class="col-md-6">
								General settings
							</div>
							<div class="col-md-6">
								<div class="form-group"> 
								  <label>Date range :</label>
								   <input class="form-control input-lg require daterange " type="text" placeholder="" autocomplete="off" name="date_range" placeholder="Select A Date Range" value="">	
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-md-6">
								Dimensions
							</div>
							<div class="col-md-6">
								<div class="form-group">
									  <label>Group By :</label>
									  <select name="group_by" class="form-control input-lg require filter" placeholder="Select Group By" >
										<?php
											echo '<option value="">Select Group By</option>';
											foreach($group_by as $key=>$value){
												echo '<option value="'.$key.'">'.$value.'</option>';
											}
										?>
									  </select>	
								</div>
								<div class="form-group"> 
								  <label>Search Key :</label>
								   <input class="form-control input-lg require filter" type="text" autocomplete="off" name="searches" placeholder="Search Parameter" value="">	
								</div>
									
							</div>
						</div>
						
					</div>
					
					<div class="box-body table-responsive">
						 <div class="pull-right box-tools">
							<!-- <a href="<?php echo base_url('admin_finance/export_payouts_report'); ?>" type="button" class="btn btn-block btn-primary">EXPORT REPORT</a> -->
						 </div>
						 
						 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_finance/access_finance_report" data-target-section="tbody" data-column-class="[{className: 'type'},{className: 'view_count'},{className: 'ads_count'},{className: 'creator'},{className: 'discovered'},{className: 'dtvexpenses'},{className: 'total'},{className: 'billing_name'},{className: 'billing_contact'},	{className: 'billing_email'},{className: 'tax_entity'},{className: 'tax_entity_id'},{className: 'payment_method_type'},{className: 'bank_name'},{className: 'bank_acc_number'},{className: 'routing_number'},{className: 'swift_code'},{className: 'paypal_id'}]"  data-refresh-dataTablePosition='0' data-filter='filter'>
						 
						 <thead> 
							<tr>
							  <th class="type" style="width: 200px;">Type</th>
							  <th class="view_count">View Count</th>
							  <th class="ads_count">Ads Count</th>
							  <th class="creator">Creator Share</th>
							  <th class="discovered">Discovered Share</th>
							  <th class="dtvexpenses">Discovered Expense</th>
							  <th class="total">Total</th>
							
							  <th class="billing_name">Billing Name</th>
							  <th class="billing_contact">Billing Contact</th>
							  <th class="billing_email">Billing Email List</th>
							  
							  <th class="tax_entity">Tax Entity</th>
							  <th class="tax_entity_id">Tax Entity Id</th>
							  <th class="payment_method_type">Payment Method</th>
							  <th class="bank_name">Bank Name</th>
							  <th class="bank_acc_number">Bank Acc No</th>
							  <th class="routing_number">Routing Number</th>
							  <th class="swift_code">Sweft code</th>
							  <th class="paypal_id">Pay Pal Id</th>
							</tr>
						 </thead>
						 
						 <tbody>
						
						 </tbody>
					  </table>
					
					</div>
				</div>
				
				<!--div id="menu1" class=" tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "in active";} } ?> ">
				<div class="box-header">
				  <h3 class="box-title">Payouts 
					<small>Report</small>
				  </h3>
				</div>
				<form action="admin/send_payout" method="POST" id="send_payout">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						  <label>Select Mode *</label>
						
							<select name="payment_mode"  class="form-control input-lg filter require" >
								<option value="1" selected>ACH</option>
								<option value="2">PayPal</option>
							</select>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group"> 
						  <label>Search Key :</label>
						   <input class="form-control input-lg require filter" type="text" autocomplete="off" name="search" placeholder="Search Parameter" value="">	
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="box-body table-responsive">
						
						 <div class="pull-right box-tools">
							<a href="<?php echo base_url('admin/export_payouts_report'); ?>" type="button" class="btn btn-block btn-primary">EXPORT REPORT</a>
						 </div>
						  <div class="pull-right box-tools" style="margin-right:5px;">
							<button type="submit"  class="btn btn-block btn-primary">Send Payments</button>
							
						 </div>
						 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin/access_payouts_report" data-target-section="tbody" data-column-class="[{className: 'checkinput'},{className: 'uniqe'},{className: 'creator'},{className: 'outstanding'},{className: 'payable'},{className: 'payment_mode'},{className: 'payment_detail'},{className: 'balance'}]"  data-refresh-dataTablePosition='1' data-filter="filter"  data-sort="[{'targets': [0],'orderable': false}]">
						  
						 <thead> 
							<tr>
							  <th class="checkinput"><input type="checkbox"  onclick="checkAll(this,'checkAll')"></th>
							  <th class="uniqe">Uniqe Name</th>
							  <th class="creator">Creator</th>
							  <th class="outstanding">Outstanding</th>
							  <th class="payable">Payable</th>
							  <th class="payment_mode">Payment Mode</th>
							  <th class="payment_detail">Payment Details</th>
							  <th class="balance">Balance</th>
							 </tr>
						 </thead>
						 
						 <tbody>
						
						 </tbody>
					  </table>
					
					</div>
				</div>
				
				</form>
				</div-->
				
				
				
				<div id="menu1" class=" tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu1'){echo "in active";} } ?> ">
					<div class="box-header">
					  <h3 class="box-title">Payment  
						<small> History</small>
					  </h3>
					</div>
					<div class="row">
						<div class="box-body table-responsive">
							  <!--div class="pull-right box-tools">
								<a href="<?php echo base_url('admin_finance/export_payment_history'); ?>" type="button" class="btn btn-block btn-primary">EXPORT REPORT</a>
							 </div-->
							 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_finance/access_payment_history" data-target-section="tbody" data-column-class="[{className: 'uniqe'},{className: 'creator'},{className: 'pay_through'},{className: 'payout_batch_id'},{className: 'payout_item_id'},{className: 'pay_amount'},{className: 'currency'},{className: 'payment_status'},{className: 'payment_fess'},{className: 'receiver_detail'},{className: 'created_at'}]"  data-refresh-dataTablePosition='1' data-filter="filter" data-orders="[[10,'DESC']]">
							 
							 <thead> 
								<tr>
								  <th class="uniqe">Uniqe Name</th>
								  <th class="creator">Creator</th>
								  <th class="pay_through">Payment Mode</th>
								  <th class="payout_batch_id">Payment Batch Id</th>
								  <th class="payout_item_id">Payment Item Id</th>
								  <th class="pay_amount">Payment Amount</th>
								  <th class="currency">Payment Currency</th>
								  <th class="payment_status">Payment Status</th>
								  <th class="payment_fess">Payment Fees</th>
								  <th class="receiver_detail">Receiver Detail</th>
								  <th class="created_at">Created at</th>
								 </tr>
							 </thead>
							 
							 <tbody>
							
							 </tbody>
						  </table>
						
						</div>
					</div>
				</div>
				
				<div id="menu2" class=" tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu2'){echo "in active";} } ?> ">
					<div class="box-header">
					  <h3 class="box-title">Payment  
						<small>Batch</small>
					  </h3>
					</div>
					<div class="row">
						<div class="box-body table-responsive">
							 <table class="table dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_finance/access_payment_batches" data-target-section="tbody" data-column-class="[{className: 'txnid'},{className: 'custom_file_id'},{className: 'level'},{className: 'operational_url'},{className: 'status'},{className: 'query_type'},{className: 'method'},{className: 'created_at'},{className: 'action'}]"  data-refresh-dataTablePosition='2' data-filter="filter">
				
							 <thead> 
								<tr>
								  <th class="txnid">Batch ID</th>
								  <th class="custom_file_id">Custom ID</th>
								  <th class="level">Level</th>
								  <th class="operational_url">Operational_url</th>
								  <th class="status">Status</th>
								  <th class="query_type">Query</th>
								  <th class="method">Method</th>
								  <th class="created_at">Created At</th>
								  <th class="action">Action</th>
						
								 </tr>
							 </thead>
							 
							 <tbody>
							
							 </tbody>
						  </table>
						
						</div>
					</div>
				</div>
				
				<div id="menu3" class=" tab-pane fade <?php if(isset($_COOKIE['setTab'])){ if($_COOKIE['setTab'] == '#menu3'){echo "in active";} } ?> ">
				<div class="box-header">
				  <h3 class="box-title">Payouts 
					<small>Report</small>
				  </h3>
				</div>
				<form action="admin_finance/send_payout_statement" method="POST" id="send_payout_statement">
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
						  <label>Select Mode *</label>
						
							<select name="payment_mode"  class="form-control input-lg filter require" >
								<option value="1" selected>ACH</option>
								<option value="2">PayPal</option>
							</select>	
						</div>	
					</div>
					<div class="col-md-6">
						<div class="form-group"> 
						  <label>Search Key :</label>
						   <input class="form-control input-lg require filter" type="text" autocomplete="off" name="search" placeholder="Search Parameter" value="">	
						</div>
					</div>
				</div>
				
				<div class="row">
					<div class="box-body table-responsive">
						
						 <!--div class="pull-right box-tools">
							<a href="<?php echo base_url('_s/export_payouts_report'); ?>" type="button" class="btn btn-block btn-primary">EXPORT REPORT</a>
						 </div-->
						  <div class="pull-right box-tools" style="margin-right:5px;">
							<button type="submit"  class="btn btn-block btn-primary">Send Payments</button>
							
						 </div>
						 <table class="table table-hover table-bordered dt-responsive nowrap hover display dataTableAjax" data-action-url="admin_finance/access_payment_statement" data-target-section="tbody" data-column-class="[{className: 'checkinput'},{className: 'creator'},{className: 'outstanding'},{className: 'payable'},{className: 'payment_mode'},{className: 'payment_detail'},{className: 'balance'}]"  data-refresh-dataTablePosition='3' data-filter="filter"  data-sort="[{'targets': [0],'orderable': false}]">
						  
						 <thead> 
							<tr>
							  <th class="checkinput"><input type="checkbox"  onclick="checkAll(this,'checkAll')"></th>
							  <th class="creator">Creator</th>
							  <th class="outstanding">Outstanding</th>
							  <th class="payable">Statements</th>
							  <th class="payment_mode">Payment Mode</th>
							  <th class="payment_detail">Payment Details</th>
							  <th class="total">Total</th>
							 </tr>
						 </thead>
						 
						 <tbody>
						
						 </tbody>
					  </table>
					
					</div>
				</div>
				
				</form>
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
