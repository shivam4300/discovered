<!-- <style>
:root {
    --animation_bg: rgba(130, 130, 130, 0.2);
    --animation_bg1: -webkit-gradient(linear, left top, right top, color-stop(8%, rgba(130, 130, 130, 0.2)), color-stop(18%, rgba(130, 130, 130, 0.3)), color-stop(33%, rgba(130, 130, 130, 0.2)));
    --animation_bg2: linear-gradient(to right, rgba(130, 130, 130, 0.2) 8%, rgba(130, 130, 130, 0.3) 18%, rgba(130, 130, 130, 0.2) 33%);
    --animation_size: 800px 100px;
    --animation_key: animation-squares 2s infinite ease-out;
}
.dsl_15 {
    height: 15px;
}
.dsl_40 {
    height: 40px;
}
.dsmb_10 {
    margin-bottom: 10px;
}
.dis_skeletonBB {
    border-bottom: 1px solid #f5f5f5;
}
.dis_skeletonCircle{
    border-radius: 50%;
}
.dis_skeletonCircle, .dis_skeletonRectangle, .dis_skeleton_line {
    background: var(--animation_bg);
    background: var(--animation_bg1);
    background: var(--animation_bg2);
    background-size: var(--animation_size);
    animation: var(--animation_key);
}
.dis_postAnimation { 
    width: 100%;
    background: #fff;
}
.dis_pAnimation_hLeft .dis_skeletonCircle, .dis_pAnimation_FLeft .dis_skeletonCircle{
    height: 50px;
    width: 50px;
}
.dis_postAnimation_header, .dis_postAnimation_cmnt {
    display: flex;
    align-items: center;
    padding: 20px;
}
.dis_pAnimation_hRIght{
    margin-left: 20px;
    width: 100%;
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.dis_postAnimation_body{
    padding: 20px;
}
.dis_skeletonRectangle{
    height: 280px;
}
.dis_pAnimation_hRIght .dis_skeleton_line {
    width: 100px;
}
.dis_pAnimation_hRIght .dis_skeletonCircle {
    width: 30px;
    height: 30px;
}
@keyframes animation-lines {
    0% {
        background-position: -468px 0;
    }
     100% {
        background-position: 610px 0;
    }
}
 @keyframes animation-squares {
    0% {
        background-position: -468px 0;
    }
     100% {
        background-position: 610px 0;
    }
}
</style> -->
<div class="dis_postAnimation articles">
    <div class="dis_postAnimation_header dis_skeletonBB">
        <div class="dis_pAnimation_hLeft">
            <div class="dis_skeletonCircle"></div>
        </div>
        <div class="dis_pAnimation_hRIght">
          <div>
            <div class="dis_skeleton_line dsl_15 dsmb_10"></div>
            <div class="dis_skeleton_line dsl_15"></div>
          </div>
          <div class="dis_skeletonCircle"></div>
        </div>
    </div>
    <div class="dis_postAnimation_body dis_skeletonBB">
      <div class="dis_skeletonRectangle dsmb_10"></div>
      <div class="dis_skeleton_line dsl_15 dsmb_10"></div>
      <div class="dis_skeleton_line dsl_15 dsmb_10"></div>
      <div class="dis_skeleton_line dsl_15"></div>
    </div>
</div>