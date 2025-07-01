</div>
	<script>
	c 	= document.head || document.body || document.documentElement; 
	function loadScript(src, cb) { 
		var s = document.createElement('script'); 
		s.src = src;  
		c.appendChild(s); 
		s.onload = cb; 
		s.onerror = cb; 
		return s; 
	} 
	</script> 	
	<script  type="text/javascript"  src="<?= CDN_BASE_URL ;?>repo/js/jquery.js"></script>		
	<script async src="<?= CDN_BASE_URL ;?>repo/js/bootstrap.js"></script>
	<script async src="<?= CDN_BASE_URL ;?>repo_admin/js/valid.js"></script>
	<script src="<?= base_url(); ?>repo_admin/js/sweetalert.min.js"></script>

	
	<?php if(isset($page_info['page'])){?>
		
		<?php if(in_array($page_info['page'], ['dashboard','advertising','payouts','statements','products'])){ ?> 
			<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
			<script async src="<?=CDN_BASE_URL;?>repo/js/daterangepicker.js"></script> 
		<?php } ?>
		
		<?php if(in_array($page_info['page'], ['dashboard']) ){ ?>

			<script>
				loadScript('<?=CDN_BASE_URL;?>repo/js/chartist.min.js', function(){
					loadScript('<?=CDN_BASE_URL;?>repo/js/chartist-plugin-tooltip.min.js');});
			</script>	
			
			<script async src="<?=CDN_BASE_URL;?>repo/js/backend/dashboard.js?q=<?php echo date('his');?>"></script>			
		<?php } ?>
		
		<?php if(in_array($page_info['page'], ['advertising','payouts','setting','statements','playlist','products','create_product','view_order'])){ ?>
			<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
			<script type="text/javascript" src="<?=CDN_BASE_URL ;?>repo/js/jquery-ui.min.js"></script>
			<script type="text/javascript" src="<?=CDN_BASE_URL;?>repo/js/bootstrap-tokenfield.min.js"></script>
			
		<?php } ?>
		
		<?php if(in_array($page_info['page'], ['advertising','payouts','statements','playlist','products'])){ ?> 
			<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
		<?php } ?>
		
		<?php if(in_array($page_info['page'], ['advertising','playlist'])){ ?> 
			<script async src="<?=BASE_URL;?>repo/js/share.js?q=<?php echo date('his');?>"></script>
		<?php } ?>
		<?php if(in_array($page_info['page'], ['advertising','setting','payouts','statements','playlist','products','create_product','view_order'])){ ?> 
			<script async src="<?=base_url() ;?>repo/js/backend/common.js?q=<?php echo date('his');?>"></script>			
		<?php } ?>
		<?php if(in_array($page_info['page'], ['advertising'])){ ?> 
			<script async src="<?=CDN_BASE_URL;?>repo/js/backend/advertising.js?q=<?php echo date('his');?>"></script>
			<script async src="<?=base_url() ;?>repo/js/common.js?q=<?php echo date('his');?>"></script>
		<?php } ?>
		<?php if(in_array($page_info['page'], ['setting'])){ ?> 
			<script async src="<?=CDN_BASE_URL;?>repo/js/backend/setting.js?q=<?php echo date('his');?>"></script>
		<?php } ?>
		<?php if(in_array($page_info['page'], ['statements'])){ ?> 
			<script async src="<?=CDN_BASE_URL;?>repo/js/backend/statement.js?q=<?php echo date('his');?>"></script>
		<?php } ?>
		<?php if(in_array($page_info['page'], ['playlist'])){ ?> 
			<script async src="<?=CDN_BASE_URL;?>repo/js/backend/playlist.js?q=<?php echo date('his');?>"></script>
		<?php } ?>

		<?php if(in_array($page_info['page'], ['store_request','create_product','view_order'])){ ?> 
			<script async src="<?=BASE_URL;?>repo/js/backend/store.js?q=<?php echo date('his');?>"></script>
		<?php } ?>
		
	<?php } ?>
	
	<script async src="<?=BASE_URL;?>repo/js/backend/designer.js?q=<?php echo date('his');?>"></script>
	
	
	
</body>
</html>