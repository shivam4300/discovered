

<div class="dis_search_heading iav_upload_wrapper">
	<div class="au_heading">
		<h2>SEARCH IVA </h2>
		<p>Search and upload content from IVA to discovered</p>
	</div>
	<div class="dis_search_warapper">
		<div class="container">
			<div class="row">
				<form>
					<div class="col-md-offset-2 col-md-6 col-sm-9">
						<div class="search_box">
							<div class="search_filter form-group">
								<span class="search_icon icons">
										<svg xmlns="https://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 485.213 485.213"><g><g><g><path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324    c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/>
										<path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951    C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46    c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465    C318.424,257.208,257.206,318.416,181.956,318.416z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/><path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/></g></g></g>
									</svg>
								</span>
								<input type="text" class="form-control" id="searchIVAInput" placeholder="Search IVA Content...">
							</div>
							
						</div>	
					</div>	
					<div class="col-md-2 col-sm-3">
						<div class="search_filter filter_box">
							<span class="filter_icon icons">
								<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 512 512" width="18px" height="18px"><g><g>
									<g>
										<polygon points="222.949,302.964 222.949,512 289.051,467.933 289.051,302.964   " data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/>
									</g>
								</g><g>
									<g>
										<rect x="2.612" width="506.776" height="49.576" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/>
									</g>
								</g><g>
									<g>
										<polygon points="19.137,82.627 206.424,269.913 305.576,269.913 492.863,82.627   " data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"/>
									</g>
								</g></g> </svg>
							</span>
							<select class="form-control" id="ivaContentType">
								<option value="Celebrity" selected>Celebrity</option>
								<option value="Movie">Movie</option>
								<option value="Show">Show</option>
							</select>
						</div>	
					</div>
				</form>
			</div>
		</div>	
	</div>	
</div>
<div class="dis_videotrailer_warapper">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="au_heading">
					<h2>Results</h2>
					<p>SHOWING RESULTS ACCORDING TO KEYWORD</p>
				</div>
			</div>
		</div>
		<div class="row">
			<div class="col-md-12">
				<div class="iav_enqry_wrapper">
					<?php echo $this->common_html->pro_loader(); ?>
					<div class="panel-group" id="accordion">
						<div class="panel panel-default">
							  <div class="panel_topbox">
								<div class="enqry_headinfo text-center">
									<span class="e_movie_title">Please enter your keyword and search the content.</span>
								</div>
							  </div>
						</div>
					</div>
				</div>
				
				<div class="col-md-12">
					<div class="profile_load_more text-center">
						<a id="uploadSelectedVideo" class="search_profile_btn hide">Upload On Discovered </a>
					</div>
				</div>		
			</div>	
		</div>	
	</div>	
</div>	
	
