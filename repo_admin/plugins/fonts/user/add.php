<div class="content-wrapper">
	<!-- Content Header (Page header) -->
	<section class="content-header">
		<h1>
			ADD NEW NEWS
			<small></small>
		</h1>
		<ol class="breadcrumb">
			<li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
			<li><a href="#">News</a></li>
			<li class="active">Add</li>
		</ol>
	</section>

	<section class="content">
		<div class="row">
			<form id="news_form" novalidate="novalidate" method="post" action="<?php echo base_url().'news/save_update_news'?>">
				<div class="col-md-12">
					<!-- general form elements -->
					<div class="box box-primary">
						<div class="box-header with-border">
							<h3 class="box-title">ADD NEWS </h3>
							<span class="pull-right"><a class="btn btn-sm btn-primary" href="<?php echo base_url('view_news');?>"><i class="fa fa-arrow-left"></i> Back to List</a> </span>
						</div>
						<div class="box-body">
							<h4 >Address Details</h4>
							<div class="row">
								<div class="col-md-3">
									<div class="form-group">
										<label>State</label>
										<select required id="e_state" name="state" class="form-control select2 input-sm" tabindex="0" onchange="get_address(this,'e_state','e_district','district');if($.trim(this.value) == ''){ $('#e_district').html('<option>--Select State First--</option>');$('#e_tehshil').html('<option>--Select District First--</option>');$('#e_area').html('<option>--Select Tehshil First--</option>') }">
											<option value="">--Select State--</option>
											<?php echo get_states('html','','');?>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>District</label>
										<select required id="e_district" name="district" tabindex="1" class="form-control select2 input-sm" onchange="get_address(this,'e_district','e_tehshil','tehshil');if($.trim(this.value) == ''){ $('#e_tehshil').html('<option>--Select District First--</option>');$('#e_area').html('<option>--Select Tehshil First--</option>'); }">
											<option value="">--Select State First--</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Tehshil</label>
										<select required id="e_tehshil" name="tehshil" tabindex="2" class="form-control select2 input-sm" onchange="get_address(this,'e_tehshil','e_area','area');if($.trim(this.value) == ''){ $('#e_area').html('<option>--Select Tehshil First--</option>'); }">
											<option>--Select District First--</option>
										</select>
									</div>
								</div>
								<div class="col-md-3">
									<div class="form-group">
										<label>Area</label>
										<select id="e_area" name="area" tabindex="3" class="form-control select2 input-sm">
											<option>--Select Tehshil First--</option>
										</select>
									</div>
								</div>
							</div>
							<hr>
							<h4 >Suchna Details</h4>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Publish in papers</label>
										<select id="e_publish_in_papers" name="publish_in_papers[]" type="text" class="form-control select2" multiple="multiple" data-placeholder="Select News papers">
											<option value="Dainik Bhasker">Dainik Bhasker</option>
											<option value="Nai Duniya">Nai Duniya</option>
											<option value="Patrika">Patrika</option>
											<option value="Agniban">Agniban</option>
										</select>
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Date of News</label>
										<input required id="e_date_of_news" name="date_of_news" type="text" class="form-control _datepicker" placeholder="Select Date" readonly>
									</div>
								</div>
							</div>
							<div class="row">
								<div class="col-md-6">
									<div class="form-group">
										<label>Heading</label>
										<input required id="e_heading" name="heading" type="text" class="form-control eng_2_hnd" placeholder="Enter Heading*">
										<!--<input id="e_owner_name" name="e_owner" type="text" class="form-control" onblur="english2hindi(this,'h_owner')" onchange="english2hindi(this,'h_owner')" placeholder="Enter Owner Name*">-->
									</div>
								</div>
								<div class="col-md-6">
									<div class="form-group">
										<label>Sub Heading</label>
										<input required id="e_sub_heading" name="sub_heading" type="text" class="form-control eng_2_hnd" placeholder="Enter Sub Heading*">
										<!--<input id="e_owner_father_name" name="owner_father_name" type="text" class="form-control" onblur="english2hindi(this,'h_owner_father_name')" onchange="english2hindi(this,'h_owner_father_name')" placeholder="Enter Father Name*">-->
									</div>
								</div>
								<div class="col-md-12">
									<div class="form-group">
										<label>News</label>
										<textarea required id="edit" name="news" placeholder=""></textarea>
									</div>
								</div>
							</div>							
						</div>
						<div class="box-footer">
							<button type="submit" id="news_form_submit_btn" class="btn btn-primary">Save News</button>
						</div>
					</div>
				</div>
			</form>

		</div>
	</section>
	
</div>