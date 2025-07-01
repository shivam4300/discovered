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
        <li class="active"><?= $pageTitle; ?></li>
      </ol>
    </section>
	
		
    <!-- Main content -->
    <section class="content">
      <!-- Info boxes -->
      <div class="row">
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Total Users</span>
              <span class="info-box-number" id="totalUser"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-red"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Active Users</span>
              <span class="info-box-number" id="activeUser"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->

        <!-- fix for small devices only -->
        <div class="clearfix visible-sm-block"></div>

        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-green"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Inactive Users</span>
              <span class="info-box-number" id="inactiveUser"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-yellow"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Official users</span>
              <span class="info-box-number" id="officialUser"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-blue"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Incomplete users</span>
              <span class="info-box-number" id="incompleteUser"></span>
            </div>
            <!-- /.info-box-content --> 
          </div>
          <!-- /.info-box -->
        </div>
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-orange"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Deleted users</span>
              <span class="info-box-number" id="deletedUser"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
        <div class="col-md-2 col-sm-6 col-xs-12">
          <div class="info-box">
            <span class="info-box-icon bg-teal"><i class="ion ion-ios-people-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Gamepass</span>
              <span class="info-box-number" id="gamepassCount"></span>
            </div>
            <!-- /.info-box-content -->
          </div>
          <!-- /.info-box -->
        </div>
        <!-- /.col -->
      </div>
      <!-- /.row -->

      <div class="row">
        <div class="col-md-12">
          
          <div class="box">
            <div class="box-header with-border dis_Admin_header">
              <h3 class="box-title">Registration Report</h3>
              <div class="dis_datepicker_wrap">
                <div class="dis_datepicker_input">
                  <input type="text" name="date_range" class="datePicker_customer form-control" placeholder="date" autocomplete="off">
                </div>  
                <!--div class="dis_datepicker_btn">
                  <button type="button" class="buttons-html5 clear_filter_customer"> Reset</button>
                </div-->
              </div>
            </div>
            
            <div class="box-body">
              <div class="row">
                <div class="col-md-12">
                  
                  <p class="text-center">
                    <strong><span class="start_date_customer">1 Jan, <?=date('Y')?></span> - <span class="end_date_customer"><?=date("j F, Y");?></span></strong>
                  </p>

                  <div class="chart">
                    
                    <canvas id="salesChartDashboard" style="height: 180px;"></canvas>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
       <div class="row">
        <div class="col-md-12">
          <div class="box">
            <div class="box-header with-border dis_Admin_header">
              <h3 class="box-title">Support Tickets</h3>
              <div class="dis_datepicker_wrap">
                <div class="dis_datepicker_input">
                  <input type="text" name="date_range" class="datePicker_new form-control" placeholder="date" autocomplete="off">
                </div>  
                <!--div class="dis_datepicker_btn">
                  <button type="button" class="buttons-html5 clear_filter"> Reset</button>
                </div-->
              </div>
            </div>
            <div class="box-body">
              <div class="row">
                <div class="col-lg-12" style="margin-bottom:50px;">
                  <div  class="col-md-12">
                  <div id="totalTicketByType"></div>
                  </div>
                   
                  <p class="text-center">
                    <strong><span class="start_date">1 Jan, <?=date('Y')?></span> -<span class="end_date"> <?=date("j F, Y");?></span></strong>
                  </p>

                  <div class="chart">
                    
                    <canvas id="salesChartDashboardSupport" width="600" height="180"></canvas>
                  </div>
                  
                    <div id="sdata" class="support_queryLine"></div> 
                </div>
               <div class="col-lg-12">
                <div class="table-responsive">
                  <table class="table no-margin" >
                    <thead>
                    <tr>
                      <th>Ticket no</th>
                    <th>Subject</th>
                      <th>Ticket type</th>
                      <th>User name</th>
                      <th>Status</th>
                    </tr>
                    </thead>
                    <tbody id="TicketDataList">
                      
                    </tbody>
                  </table>
                </div>              
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
      
      <div class="row">
        <!-- Left col -->
        <div class="col-md-12">
          <!-- MAP & BOX PANE -->
          <div class="box box-success">
            <div class="box-header with-border">
              <h3 class="box-title">Visitors Report</h3>
            </div>
            <!-- /.box-header -->
            <div class="box-body no-padding">
              <div class="row">
                <div class="col-md-12 col-sm-12">
                  <div class="pad">
                    <!-- Map will be created here -->
                    <div id="world-map-markers" style="height: 325px;"></div>
                  </div>
                </div>
                
              </div>
              <!-- /.row -->
            </div>
            <!-- /.box-body -->
          </div>
          <!-- /.box -->
          <div class="row">
            

            <div class="col-md-4">
            
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Icon Members</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">8 New Members</span>
                  </div>
                </div>
                
                <div class="box-body no-padding">
                  <ul class="users-list clearfix s_member_list" id="latestmember">
                    
                    
                  </ul>
                  
                </div>
                
                <div class="box-footer text-center">
                  <a href="<?=base_url('admin/userlist')?>" class="uppercase">View All Users</a>
                </div>
               
              </div>
              
            </div>
            <div class="col-md-4">
            
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Emerging Members</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">8 New Members</span>
                  </div>
                </div>
                
                <div class="box-body no-padding">
                  <ul class="users-list clearfix s_member_list" id="latestmemberEmerging">                  
                  </ul>
                </div>
                
                <div class="box-footer text-center">
                  <a href="<?=base_url('admin/userlist')?>" class="uppercase">View All Users</a>
                </div>
               
              </div>
              
            </div>
            <div class="col-md-4">
            
              <div class="box box-danger">
                <div class="box-header with-border">
                  <h3 class="box-title">Latest Brand Members</h3>

                  <div class="box-tools pull-right">
                    <span class="label label-danger">8 New Members</span>
                  </div>
                </div>
                
                <div class="box-body no-padding">
                  <ul class="users-list clearfix s_member_list" id="latestmemberBrand">                  
                  </ul>
                </div>
                
                <div class="box-footer text-center">
                  <a href="<?=base_url('admin/userlist')?>" class="uppercase">View All Users</a>
                </div>
               
              </div>
              
            </div>
          </div>
          <!-- /.row -->

          <!-- TABLE: LATEST ORDERS -->
          <div class="box box-info">
            <div class="box-header with-border">
              <h3 class="box-title">Latest Videos</h3>

             
            </div>
          
            <div class="box-body">
              <div class="table-responsive">
                <table class="table no-margin" >
                  <thead>
                  <tr>
                    <th>#</th>
                    <th>Preview</th>
                    <th>Title</th>
                    <th>Mode</th>
                    <th>Published On</th>
                  </tr>
                  </thead>
                  <tbody id="latestVideo">
                  </tbody>
                </table>
              </div>
            </div>
            
          </div>
          <!-- /.box -->
        </div>
        <!-- /.col -->

        <!-- <div class="col-md-4">
         
          <div class="info-box bg-yellow">
            <span class="info-box-icon"><i class="ion ion-ios-pricetag-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Inventory</span>
              <span class="info-box-number">5,200</span>

              <div class="progress">
                <div class="progress-bar" style="width: 50%"></div>
              </div>
              <span class="progress-description">
                    50% Increase in 30 Days
                  </span>
            </div>
           
          </div>
          
          <div class="info-box bg-green">
            <span class="info-box-icon"><i class="ion ion-ios-heart-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Mentions</span>
              <span class="info-box-number">92,050</span>

              <div class="progress">
                <div class="progress-bar" style="width: 20%"></div>
              </div>
              <span class="progress-description">
                    20% Increase in 30 Days
                  </span>
            </div>
            
          </div>
          
          <div class="info-box bg-red">
            <span class="info-box-icon"><i class="ion ion-ios-cloud-download-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Downloads</span>
              <span class="info-box-number">114,381</span>

              <div class="progress">
                <div class="progress-bar" style="width: 70%"></div>
              </div>
              <span class="progress-description">
                    70% Increase in 30 Days
                  </span>
            </div>
            
          </div>
          
          <div class="info-box bg-aqua">
            <span class="info-box-icon"><i class="ion-ios-chatbubble-outline"></i></span>

            <div class="info-box-content">
              <span class="info-box-text">Direct Messages</span>
              <span class="info-box-number">163,921</span>

              <div class="progress">
                <div class="progress-bar" style="width: 40%"></div>
              </div>
              <span class="progress-description">
                    40% Increase in 30 Days
                  </span>
            </div>
            
          </div>
         

          <div class="box box-default">
            <div class="box-header with-border">
              <h3 class="box-title">Browser Usage</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
           
            <div class="box-body">
              <div class="row">
                <div class="col-md-8">
                  <div class="chart-responsive">
                    <canvas id="pieChart" height="150"></canvas>
                  </div>
                  
                </div>
                
                <div class="col-md-4">
                  <ul class="chart-legend clearfix">
                    <li><i class="fa fa-circle-o text-red"></i> Chrome</li>
                    <li><i class="fa fa-circle-o text-green"></i> IE</li>
                    <li><i class="fa fa-circle-o text-yellow"></i> FireFox</li>
                    <li><i class="fa fa-circle-o text-aqua"></i> Safari</li>
                    <li><i class="fa fa-circle-o text-light-blue"></i> Opera</li>
                    <li><i class="fa fa-circle-o text-gray"></i> Navigator</li>
                  </ul>
                </div>
                
              </div>
              
            </div>
            
            <div class="box-footer no-padding">
              <ul class="nav nav-pills nav-stacked">
                <li><a href="#">United States of America
                  <span class="pull-right text-red"><i class="fa fa-angle-down"></i> 12%</span></a></li>
                <li><a href="#">India <span class="pull-right text-green"><i class="fa fa-angle-up"></i> 4%</span></a>
                </li>
                <li><a href="#">China
                  <span class="pull-right text-yellow"><i class="fa fa-angle-left"></i> 0%</span></a></li>
              </ul>
            </div>
            
          </div>
          
          <div class="box box-primary">
            <div class="box-header with-border">
              <h3 class="box-title">Recently Added Products</h3>

              <div class="box-tools pull-right">
                <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                </button>
                <button type="button" class="btn btn-box-tool" data-widget="remove"><i class="fa fa-times"></i></button>
              </div>
            </div>
            
            <div class="box-body">
              <ul class="products-list product-list-in-box">
                <li class="item">
                  <div class="product-img">
                    <img src="<?= base_url(); ?>repo_admin/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Samsung TV
                      <span class="label label-warning pull-right">$1800</span></a>
                    <span class="product-description">
                          Samsung 32" 1080p 60Hz LED Smart HDTV.
                        </span>
                  </div>
                </li>
                
                <li class="item">
                  <div class="product-img">
                    <img src="<?= base_url(); ?>repo_admin/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Bicycle
                      <span class="label label-info pull-right">$700</span></a>
                    <span class="product-description">
                          26" Mongoose Dolomite Men's 7-speed, Navy Blue.
                        </span>
                  </div>
                </li>
               
                <li class="item">
                  <div class="product-img">
                    <img src="<?= base_url(); ?>repo_admin/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">Xbox One <span
                        class="label label-danger pull-right">$350</span></a>
                    <span class="product-description">
                          Xbox One Console Bundle with Halo Master Chief Collection.
                        </span>
                  </div>
                </li>
                
                <li class="item">
                  <div class="product-img">
                    <img src="<?= base_url(); ?>repo_admin/img/default-50x50.gif" alt="Product Image">
                  </div>
                  <div class="product-info">
                    <a href="javascript:void(0)" class="product-title">PlayStation 4
                      <span class="label label-success pull-right">$399</span></a>
                    <span class="product-description">
                          PlayStation 4 500GB Console (PS4)
                        </span>
                  </div>
                </li>
               
              </ul>
            </div>
            
            <div class="box-footer text-center">
              <a href="javascript:void(0)" class="uppercase">View All Products</a>
            </div>
            
          </div>
          
           
        </div> -->




        <!-- /.col -->
      </div>
      <!-- /.row -->
    </section>
    <!-- /.content -->
    <section class="content">
      <div class="row">
        <div class="col-md-12 col-sm-12 col-xs-12">
        <iframe width="1920" height="1300" src="https://lookerstudio.google.com/embed/reporting/1e48aeb8-8e5b-4d1f-8b52-e346e65c8819/page/p_m63q9t54bd" frameborder="0" style="border:0" allowfullscreen sandbox="allow-storage-access-by-user-activation allow-scripts allow-same-origin allow-popups allow-popups-to-escape-sandbox"></iframe>
        </div>
      </div>
    </section>   
  </div>
  
  
  <style type="text/css">
    .s_member_list{
      display: flex;
      flex-wrap: wrap;
    }
  </style>
  
 