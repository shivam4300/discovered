

<div class="dis_help_page">
	<div class="dis_search_heading iav_upload_wrapper">
		<div class="au_heading">
			<h2>Got Questions?</h2>
			<p>Search Our Help Center To Find Quick Answers</p>
		</div>
		<div class="dis_search_warapper">
			<div class="container">
				<div class="row">
				<form action="<?= base_url('help'); ?>" method="GET">
					<div class="col-md-offset-3 col-md-6">
						<div class="search_box">
							<div class="search_filter form-group">
								<span class="search_icon icons search-me">
										<svg xmlns="https://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 485.213 485.213"><g><g><g><path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324    c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/>
										<path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951    C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46    c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465    C318.424,257.208,257.206,318.416,181.956,318.416z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/><path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/></g></g></g>
									</svg>
								</span>
								<input type="text" autocomplete="off" value="<?php echo $search_query; ?>" class="form-control search_content_focus" name="search_query" placeholder="Search Discovered Help Center..."  id="searchFaqEnquiry" >
							</div>

						</div>
					</div>
				</form>

					<p class="helppage_note">Can’t Find An Answer? We are happy to assist, please send us an email at <a href="javascript:;">help@discovered.tv</a> Or Create a
						<?php if(is_login()){ ?>
							<a href="<?php echo base_url('support'); ?>" target="_blank">Ticket Here </a>
						<?php }else{ ?>
							<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl" onclick="OpenRoute('support')">Ticket Here </a>
						<?php } ?>


					</p>
				</div>
			</div>
		</div>
	</div>


	<div class="dis_videotrailer_warapper dis_help_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="au_heading">
						<h2>General enquiries </h2>
						<p>New Around Here? Start With The Basics</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12">
					<div class="iav_enqry_wrapper">
					<div class="panel-group load-my-content" id="accordion" data-action="<?php echo base_url('help/get_my_content/2?'.$_SERVER['QUERY_STRING']); ?>">		<?php echo $this->common_html->content_loader_html(); ?>
					</div>
					</div>
				</div>
			</div>
		</div>
	</div>


	<div class="dis_faq_wrapper">
		<div class="container">
			<div class="row">
				<div class="col-md-12">
					<div class="au_heading">
						<h2>FAQ’s</h2>
						<p>Browse Through The Most Frequently Asked Questions</p>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-md-12 load-my-content" data-action="<?php echo base_url('help/get_my_content/1?'.$_SERVER['QUERY_STRING']); ?>">
					<?php echo $this->common_html->content_loader_html(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
