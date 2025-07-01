<?php 
$i=0;
foreach($items as $item){
    /*echo "<pre>";
print_r($item);*/

if(!empty($item['thumbnail']['url']) && !empty($item['content']['url'])) {
$i++;
 ?>
<li>
    <input type="hidden" name="url[]" value="<?=$item['content']['url']?>" id="post_url_d_<?=$i?>">
    <input type="hidden" name="vid_provider_id[]" value="<?=$item['guid']?>">
    <input type="hidden" name="thumbnail[]" value="<?=$item['thumbnail']['url'];?>" >
    <input type="hidden" name="duration[]" value="<?=$item['content']['duration'];?>">
    <input type="hidden" name="tag[]" value="<?=isset($item['tags']) ? $item['tags'] : '';?>">
    <input type="hidden" name="post_id[]" value="<?=($i==1)?$ids:''?>" id="post_id_<?=$i?>">

    <div class="cmn_upbox_innerbody">
        <span class="montiz_details_sn ">
            <span class="montiz_details_sn_digit" ><?=$i?></span>
            <span class="montiz_details_sn_right"></span>
        </span>
        <div class="mdetails_data">
            <div class="dis_ve_ss_details">
                <div class="dis_ve_ss_dleft" data-id="<?=$i?>">
                    <div class="dis_ve_ss_thumb" >
                        <img src="<?=$item['thumbnail']['url'];?>" alt="thumb" class="img-reposnive">
                        <div class="dis_ve_ss_thumb_overlay">
                            <span class="dis_ve_ss_thumb_loader"><i class="fa fa-circle-o-notch fa-spin" aria-hidden="true"></i></span>
                            <span class="dis_ve_ss_thumb_play play_cover_video" style="display: none;" >
                                <img src="<?=base_url();?>repo/images/banner_logo1.png">
                            </span>
                        </div>
                        
                    </div>
                </div>
                <div class="dis_ve_ss_dright">
                    <div class="dis_ve_ss_field">
                    <ul class="dis_ve_ss_input_list">
                        <li>
                            <div class="dis_field_box">
                                <label class="dis_field_label">Select Mode</label>
                                <div class="dis_field_wrap dis_select2">
                                    <select class="primay_select dis_field_input SelectBySimpleSelectVideoElephant require" data-target="select2"  data-option="{minimumResultsForSearch:-1, width:'100%'}" data-error="Please select mode." data-url="getGenreList" data-id="#genre<?=$i?>" name="mode[]">
                                        <option value="">Select Mode</option>
                                       <?= $website_mode; ?>	
                                    </select>
                                </div>
                                
                            </div>
                        <li>
                            <div class="dis_field_box">
                                <label class="dis_field_label">Select Genre</label>
                                <div class="dis_field_wrap dis_select2">
                                    <select class="primay_select dis_field_input require SelectBySimpleSelectGenre" data-target="select2"  data-option="{minimumResultsForSearch:-1, width:'100%'}" data-url="getSubGenreList" name="genre[]" id="genre<?=$i?>" data-id="#sub_genre<?=$i?>" data-error="Please select genre.">
                                        <option value="">Select Genre</option>
                                       	
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li style="display: none" class="sub_genre_video_elephant">
                            <div class="dis_field_box">
                                <label class="dis_field_label">Select Genre</label>
                                <div class="dis_field_wrap dis_select2">
                                    <select class="primay_select dis_field_input " data-target="select2"  data-option="{minimumResultsForSearch:-1, width:'100%'}" name="sub_genre[]" id="sub_genre<?=$i?>" data-error="Please select genre.">
                                        <option value="">Select Genre</option>
                                        
                                    </select>
                                </div>
                            </div>
                        </li>
                        <li>
                            <div class="dis_field_box">
                                <label class="dis_field_label">video title</label>
                                <div class="dis_field_wrap">
                                <input type="text" class="dis_field_input require" placeholder="Video Title" name="title[]" maxlength="100" value="<?=$item['title'];?>">
                                </div>
                            </div>                    
                        </li>
                        <li class="dis_ve_ss_input_list_100">
                            <div class="dis_field_box">
                                <label class="dis_field_label">video Description</label>
                                <div class="dis_field_wrap">
                                <input type="text" class="dis_field_input require" placeholder="Video Description" name="description[]" maxlength="100" value="<?=$item['description'];?>">
                                </div>
                            </div>                    
                        </li>
                        <li><button type="button" class="dis_btn skip_video" data-id="post_id_<?=$i?>">Skip</button></li>
                    </ul>
                    
                    </div>                                        
                </div>                                        
            </div>
        </div>										
    </div>
</li>
<?php } } ?>
