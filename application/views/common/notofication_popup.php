<style>
/* toster popup start */
.toster_popup {
    z-index: 9999999999;
    position: fixed;
    top: 110px;
    right: 10px;
    border-radius: 5px;
    background-color: rgb(255, 255, 255);
    box-shadow: 7.552px 9.326px 30px 0px rgb(95 95 95 / 3%);
    border: 1px solid rgb(239, 239, 239);
}
.toster_popup.error_noti {
    box-shadow: 9.44px 11.657px 30px 0px rgba(255, 130, 132, 0.1);
}
.toster_popup .toster_inner {
    display: flex;
    border-radius: 5px;
}
.toster_info .top {
    color: #40404b;
    font-weight: 700;
    margin-bottom: 0;
}
.toster_info .bottom {
    color: #9ca0ad;
    font-weight: 600;
    margin: 0;
}
.toster_icon {
    background: #19e767;
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px 15px;
    border-radius: 5px 0 0 5px;
}
.toster_inner .toster_info {
    padding: 14px 15px 15px 15px;
}
.toster_popup.error_noti .toster_icon {
    background: rgb(255 94 38);
}
/* toster popup end */
</style>
<div class="toster_popup success_noti hide">
	<div class="toster_inner">
		<div class="toster_icon">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="33px" height="33px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M16.469,0.481 C7.639,0.481 0.481,7.639 0.481,16.469 C0.481,25.298 7.639,32.456 16.469,32.456 C25.298,32.456 32.456,25.298 32.456,16.469 C32.456,7.639 25.298,0.481 16.469,0.481 ZM22.560,13.214 L15.278,21.369 C14.788,21.941 13.948,21.941 13.458,21.369 L10.377,17.864 C9.957,17.363 9.957,16.576 10.447,16.075 C10.937,15.646 11.707,15.646 12.198,16.147 L14.368,18.650 L20.740,11.569 C21.230,11.068 22.000,10.996 22.490,11.497 C22.980,11.926 22.980,12.785 22.560,13.214 Z"/></svg>
		</div>
		<div class="toster_info">
			<p class="top">Success!</p>
			<p class="bottom">Information Updated.</p>
		</div>
	</div>
</div>
		
<div class="toster_popup error_noti hide">
	<div class="toster_inner">
		<div class="toster_icon">
			<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="32px" height="32px"><path fill-rule="evenodd" fill="rgb(255, 255, 255)" d="M16.000,-0.000 C7.163,-0.000 -0.000,7.163 -0.000,16.000 C-0.000,24.837 7.163,32.000 16.000,32.000 C24.836,32.000 32.000,24.837 32.000,16.000 C32.000,7.163 24.836,-0.000 16.000,-0.000 ZM14.931,7.573 C15.221,7.342 15.596,7.226 16.056,7.226 C16.516,7.226 16.854,7.346 17.070,7.587 C17.285,7.828 17.393,8.152 17.393,8.558 C17.393,9.891 17.370,10.510 17.325,13.527 C17.279,16.543 17.257,17.494 17.257,18.826 C17.257,19.104 17.127,19.322 16.867,19.479 C16.606,19.636 16.336,19.715 16.056,19.715 C15.235,19.715 14.825,19.419 14.825,18.826 C14.825,17.494 14.771,16.543 14.661,13.527 C14.552,10.510 14.497,9.891 14.497,8.558 C14.497,8.133 14.642,7.804 14.931,7.573 ZM17.197,24.323 C16.885,24.624 16.514,24.774 16.086,24.774 C15.599,24.774 15.185,24.624 14.844,24.323 C14.504,24.023 14.333,23.672 14.333,23.271 C14.333,22.854 14.504,22.499 14.844,22.207 C15.185,21.915 15.599,21.769 16.086,21.769 C16.514,21.769 16.885,21.915 17.197,22.207 C17.510,22.499 17.667,22.854 17.667,23.271 C17.667,23.672 17.510,24.023 17.197,24.323 Z"/></svg>	
		</div>
		<div class="toster_info">
			<p class="top">Error!</p>
			<p class="bottom">Something Went Wrong.</p>
		</div>
	</div>
</div>