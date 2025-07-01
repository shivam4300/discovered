<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= addcslashes($title, "'");  ?></title>
	<style>
</style>

<script>
	const base_url = '<?php echo base_url(); ?>';

	window.getCustomParam = function () {
		return {
			'VideoUserId'	: <?= $user_id; ?>,
			'VideoPostId'	: <?= $post_id; ?>,
			'VideoMode'		: '<?= $web_mode; ?>',
			'VideoGenre'	: 0,
			'VideoCategory'	: 1,  //IAB category
			'channel'		: 'NA',
			'profile'		: 'NA',
			'title'			: '<?= addcslashes($title, "'");  ?>',
			'is_stream_live': <?= $is_stream_live; ?>,
			'language'		: 'en_CA',
			'duration'		: 0,
			'UidIdWhoIsWatching': 0,
			'domain'		: (window.location.href).replace('http://', '').replace('https://', '').replace('www.', '').split(/[/?#]/)[0]
		};
	}
</script>

	<input type="hidden" id="VideoUserId"	value="<?php echo isset($user_id)	? $user_id 	: 0 ; ?>">
	<input type="hidden" id="VideoPostId" 	value="<?php echo isset($post_id)	? $post_id 	: 0 ; ?>">
	<input type="hidden" id="VideoMode" 	value="<?php echo isset($web_mode)	? $web_mode : 0 ; ?>">
	<input type="hidden" id="VideoTag" 		value="<?php echo isset($tag)	? $tag : '' ; ?>">

	<input type="hidden" id="category_" value="<?php echo isset($category)?$category:'' ; ?>">
	<input type="hidden" id="genre_" value="<?php echo isset($genre)?$genre:'' ; ?>">
	<input type="hidden" id="mode_" value="<?php echo isset($mode)?$mode:'' ; ?>">
	<input type="hidden" id="list_" value="<?php echo isset($playlist_id)?$playlist_id: ''; ?>">
	<input type="hidden" id="pListId" value="<?php echo isset($pListId)?$pListId: ''; ?>">
	<input type="hidden" id="strArn" value="<?php echo isset($strArn)?$strArn:''; ?>">
	<input type="hidden" id="is_stream_live" value="<?php echo isset($is_stream_live)?$is_stream_live:''; ?>">

	<?php if(isset($video_type) && $video_type == 2 && isset($is_stream_live) && $is_stream_live  == 1) { ?>
		<input type="hidden" value="<?= $schedule_time; ?>" id="schedule_time" data-is_sechdule="<?php echo isset($is_scheduled)?$is_scheduled:''; ?>">
	<?php } ?>

	<!-- <link href="<?php echo base_url();?>repo/css/player/videojs.css" rel="stylesheet" type="text/css" /> -->
	<link href="<?php echo base_url();?>repo/css/player/videojs_v7.5.4.css" rel="stylesheet" type="text/css" />

	<link href="https://googleads.github.io/videojs-ima/node_modules/videojs-contrib-ads/dist/videojs.ads.css" rel="stylesheet">
	<link href="https://googleads.github.io/videojs-ima/dist/videojs.ima.css" rel="stylesheet">

</head>
<body style="margin:0">
	<div class="box" src="<?php if(isset($file)){echo $file; } ;?>" mime="<?php echo isset($mime_type)? $mime_type : '' ; ?>">
		<video playsinline id="my_video" style="height:100vh!important;" class="video-js vjs-big-play-centered vjs-default-skin" poster='<?= $ThumbImage; ?>' vidid = "<?php echo $vid_id; ?>" single= "<?php echo $post_key; ?>" >

		</video>
		<h4 class="ctm_videotitle"><?php if(isset($title)){echo $title; } ;?></h2>
		<div class="video_nextmain">
			<div class="video_nextwrapper">
				<div class="video_nextinnner custom_scrol">
					<div class="top_bar">
						<p class="top_title">Up/Next</p>
						<div class="auto_play_wraapper">
							<input type="checkbox" class="switch_tgl" id="player_next">
							<label class="tgl_btn" for="player_next"></label>
						</div>

					</div>

					<div id="UpNext">

					</div>
				</div>
				<div class="video_nexttoggle">
				</div>
			</div>
		</div>
	</div>
</body>

<script src="https://imasdk.googleapis.com/js/sdkloader/ima3.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>repo/js/player/videojs.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>repo/js/player/videojs.ads.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>repo/js/player/videojs.ima.min.js"></script>
<script type="text/javascript" src="<?php echo base_url();?>repo/js/player/player3.js?q=<?=VRSN;?>"></script>
</html>