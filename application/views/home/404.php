<link href="<?php echo base_url('repo/css/font-awesome.css');?>" rel="stylesheet" type="text/css" />
<style>
<?php
	// echo isset($style)?$style:'';  //coming from Embed controller
?>
</style>
<?php
$top 		= isset($top)?$top:'404';
$heading 	= isset($heading)?$heading:'Looks like you\'re lost';
$content 	= isset($content)?$content:'We can not find the page you\'re looking for';
$link 		= isset($link)?$link:base_url();
$button 	= isset($button)?$button:'back to home';

$no_found_error = '<div class="error_wrapper full_vh_foooter">
						<div class="error_inner">
							<h5 class="error_nb">'.$top.'</h5>
							<h2>'.$heading.'</h2>
							<p>'.$content.'</p>
							<a href="'.$link .'" class="dis_btn h_40">'.$button .'</a>
						</div>
					</div>';

if($this->uri->total_segments() == 1){
	$u_name = $this->uri->segment(1);
	$url = base_url('channel?user='.$u_name);
	redirect($url);
}else{
	echo $no_found_error;
}
?>