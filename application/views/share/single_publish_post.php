<script>
var post_id = <?= $post_id; ?>
</script>
<div class="dis_user_data_wrapper dis_singlepostpage">
      <div class="container">
         <div class="row ">
			<div class="col-lg-12">
				<div id="share_publish_post">
					<?php echo $this->common_html->content_loader_html();?>
				</div>						
            </div>
         </div>
      </div>
   </div>
</div>
<script>

	
window.onload = function() {
 
  setTimeout(function(){
		$('.vjs-big-play-button').trigger('click');
	},1000)
};
</script>