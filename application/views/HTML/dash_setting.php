<!DOCTYPE html>
<html lang="en">
<!-- Header Start -->
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>Discovered</title>
	<!-- bootstrap css -->
	<link href="<?php echo base_url();?>repo/css/bootstrap.css" rel="stylesheet" type="text/css" /> 
	<!-- fontawesome css -->
	<link href="<?php echo base_url();?>repo/css/font-awesome.css" rel="stylesheet" type="text/css" /> 
	<!-- select 2 offline css -->
	<link href="<?php echo base_url();?>repo_admin/css/select2.min.css" rel="stylesheet" type="text/css" /> 
	<!-- select 2 cdn css -->
	<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/css/select2.min.css" rel="stylesheet" />
	<!-- datatable css -->
	<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.css"/>
	<!-- datepicker css -->
	<link href="<?php echo base_url();?>repo/css/daterangepicker.css" rel="stylesheet" type="text/css" /> 
	<!-- custom css -->
	<link href="<?php echo base_url();?>repo/css/backend/dashboard.css?q=<?php echo date('his');?>" rel="stylesheet" type="text/css" /> 
	<!-- jquery js -->
	<script  type="text/javascript"  src="<?php echo base_url();?>repo/js/jquery.js"></script>

<style>
/* toster popup start */
.toster_popup {
	position: fixed;
    bottom: 20px;
    right: 20px;
    border-radius: 5px;
    background-color: rgb(255, 255, 255);
    box-shadow: 9.44px 11.657px 30px 0px rgba(40, 240, 176, 0.1);
    padding: 15px 20px;
    border-bottom: 5px solid rgb(40, 240, 176);
}
.toster_popup.error {
    box-shadow: 9.44px 11.657px 30px 0px rgba(255, 130, 132, 0.1);
    border-bottom: 5px solid rgb(255, 139, 140);
}
.toster_popup .toster_inner {
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.toster_inner .toster_info {
    padding-left: 19px;
}
.toster_info .top {
    color: #495569;
    font-weight: 700;
}
.toster_info .bottom {
    color: #9ca2ab;
    font-weight: 600;
}

/* toster popup end */
</style>
</head>

<body>


<!-- Sidebar section Start -->
<div class="dis_dash_sidebar">
	<div class="dash_header_logo">
			<a href="#">
				<img src="https://192.168.1.223/discovered/repo/images/dashboard_logo.png" alt="dashboard Logo">
			</a>
		</div>
	<div class="sidebar_inner">
		<ul class="sidebar_main_menu">
			<li>
				<a href="#">
				<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Dashboard-2" data-name="Dashboard" class="cls-2" d="M17.5,10A1.5,1.5,0,0,1,19,11.5v7a1.5,1.5,0,0,1-3,0v-7A1.5,1.5,0,0,1,17.5,10Zm-6-6A1.5,1.5,0,0,1,13,5.5v13a1.5,1.5,0,0,1-3,0V5.5A1.5,1.5,0,0,1,11.5,4Zm-6,5A1.5,1.5,0,0,1,7,10.5v8a1.5,1.5,0,0,1-3,0v-8A1.5,1.5,0,0,1,5.5,9Z"/>
				</svg>
				Dashboard
				</a>
			</li>
			<li><a href="#" class="active">
				<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Advertising-2" data-name="Advertising" class="cls-2" d="M16.652,10.047L9.327,5.466A2.853,2.853,0,0,0,4.963,7.894v9.163a2.828,2.828,0,0,0,1.469,2.5,2.869,2.869,0,0,0,1.387.364,2.841,2.841,0,0,0,1.508-.439L16.652,14.9A2.865,2.865,0,0,0,16.652,10.047Zm-0.168,2.428a1.342,1.342,0,0,1-.632,1.145L8.527,18.2a1.343,1.343,0,0,1-2.053-1.145V7.894A1.338,1.338,0,0,1,8.527,6.749l7.325,4.581A1.343,1.343,0,0,1,16.484,12.475Z"/>
				</svg>
				Advertising</a></li>
			<li><a href="#">
				<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Merchandise-2" data-name="Merchandise" class="cls-2" d="M19.771,8.25l-0.29.281,0.253-.316L16.2,5.164a0.75,0.75,0,0,0-.248-0.146l-2.56-.887a0.746,0.746,0,0,0-.944.429,0.713,0.713,0,0,0-.047.231,1.42,1.42,0,0,1-2.839-.013,0.747,0.747,0,0,0-.783-0.689l-2.761.939a0.726,0.726,0,0,0-.243.141L2.245,8.223a0.718,0.718,0,0,0-.236.514,0.731,0.731,0,0,0,.226.542l2.681,2.63v7.347a0.736,0.736,0,0,0,.741.729H16.33a0.736,0.736,0,0,0,.741-0.729V11.909l2.672-2.621A0.721,0.721,0,0,0,19.771,8.25Zm-3.45.314a0.736,0.736,0,0,0-.741.729V18.51H6.385V9.293a0.741,0.741,0,0,0-1.482,0V9.849L3.83,8.795,6.649,6.347,8.28,5.775a2.909,2.909,0,0,0,3.253,1.78,2.853,2.853,0,0,0,2.158-1.78l1.63,0.571,2.809,2.448L17.062,9.845V9.293A0.736,0.736,0,0,0,16.321,8.564Z"/>
				</svg>
			
			Merchandise</a></li>
			<li><a href="#">
				<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Media-2" data-name="Media" class="cls-2" d="M18.464,3.974a0.719,0.719,0,0,0-.613-0.1L8.625,6.712a0.661,0.661,0,0,0-.474.628v8.352a3.649,3.649,0,0,0-3.5.268,1.96,1.96,0,0,0,0,3.32,3.381,3.381,0,0,0,1.973.6,3.38,3.38,0,0,0,1.974-.6,2.052,2.052,0,0,0,.918-1.66v-6.8L17.376,8.4v4.441a3.656,3.656,0,0,0-3.5.268,1.96,1.96,0,0,0,0,3.32,3.628,3.628,0,0,0,3.947,0,2.051,2.051,0,0,0,.918-1.66V4.505A0.655,0.655,0,0,0,18.464,3.974ZM17.376,5.4V7.022L9.519,9.436V7.818Zm0,9.366a1.386,1.386,0,0,1-1.523.948,1.382,1.382,0,0,1-1.524-.947,1.383,1.383,0,0,1,1.524-.947A1.383,1.383,0,0,1,17.379,14.771Zm-10.752,1.9a1.383,1.383,0,0,1,1.524.947,1.382,1.382,0,0,1-1.524.948A1.382,1.382,0,0,1,5.1,17.619,1.383,1.383,0,0,1,6.627,16.672Z"/>
				</svg>
			Media</a></li>
			<li><a href="#">
				<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Shows-2" data-name="Shows" class="cls-2" d="M20.315,10.867A0.692,0.692,0,0,0,21,10.183V7.737a1.8,1.8,0,0,0-1.814-1.784H3.82A1.8,1.8,0,0,0,2.006,7.737v2.446a0.692,0.692,0,0,0,.68.684,1.574,1.574,0,1,1,0,3.148,0.691,0.691,0,0,0-.68.684v2.445A1.8,1.8,0,0,0,3.82,18.929H19.181A1.8,1.8,0,0,0,21,17.145V14.7a0.693,0.693,0,0,0-.681-0.685A1.574,1.574,0,1,1,20.315,10.867ZM17.28,12.441a2.9,2.9,0,0,0,.863,2.066,3,3,0,0,0,1.4.788v1.849a0.423,0.423,0,0,1-.429.416H16.271v-0.7a0.692,0.692,0,0,0-1.385,0v0.7H3.753a0.423,0.423,0,0,1-.429-0.416V15.3a3,3,0,0,0,1.4-.788,2.906,2.906,0,0,0,0-4.132,2.988,2.988,0,0,0-1.4-.788V7.737a0.423,0.423,0,0,1,.429-0.415H14.886v5.664a0.692,0.692,0,0,0,1.385,0V7.322h2.844a0.422,0.422,0,0,1,.429.415v1.85a2.992,2.992,0,0,0-1.4.788A2.9,2.9,0,0,0,17.28,12.441Zm-0.956,2.347a0.75,0.75,0,0,0-.04-0.128,0.681,0.681,0,0,0-.063-0.116,0.651,0.651,0,0,0-.088-0.106,0.67,0.67,0,0,0-.105-0.084,0.7,0.7,0,0,0-.12-0.063,0.664,0.664,0,0,0-.128-0.039,0.686,0.686,0,0,0-.269,0,0.707,0.707,0,0,0-.132.039,0.715,0.715,0,0,0-.115.06,0.7,0.7,0,0,0-.194.19,0.686,0.686,0,0,0-.064.118,0.741,0.741,0,0,0-.04.128,0.69,0.69,0,0,0-.014.135,0.707,0.707,0,0,0,.014.136,0.7,0.7,0,0,0,.04.127,0.69,0.69,0,0,0,.065.12,0.67,0.67,0,0,0,.086.1,0.646,0.646,0,0,0,.106.085,0.692,0.692,0,0,0,.118.061,0.661,0.661,0,0,0,.129.039,0.7,0.7,0,0,0,.135.014,0.686,0.686,0,0,0,.133-0.013,0.665,0.665,0,0,0,.13-0.039,0.732,0.732,0,0,0,.12-0.062,0.689,0.689,0,0,0,.106-0.085,0.651,0.651,0,0,0,.087-0.1,0.676,0.676,0,0,0,.064-0.118,0.694,0.694,0,0,0,.04-0.128,0.678,0.678,0,0,0,.014-0.135A0.668,0.668,0,0,0,16.324,14.788Zm-4.107-2.871H7.826A0.526,0.526,0,0,1,7.3,11.39h0a0.526,0.526,0,0,1,.523-0.526h4.39a0.526,0.526,0,0,1,.523.526h0A0.526,0.526,0,0,1,12.217,11.916Zm0,2.254H7.826A0.526,0.526,0,0,1,7.3,13.644h0a0.526,0.526,0,0,1,.523-0.526h4.39a0.526,0.526,0,0,1,.523.526h0A0.526,0.526,0,0,1,12.217,14.17Z"/>
				</svg>
			Shows</a></li>
			<li><a href="#">
				<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Partnership_Program-2" data-name="Partnership Program" class="cls-2" d="M21.882,6.714a15.143,15.143,0,0,0-5.663.9,0.616,0.616,0,0,0-.344.375,0.508,0.508,0,0,0-.017.075c-0.816-.294-1.576-0.589-2.264-0.877a3.612,3.612,0,0,0-3.972.369H6.3c0.008-.065.015-0.128,0.021-0.189a0.572,0.572,0,0,0-.2-0.488c-0.8-.71-3.7-0.942-4.911-1.008a0.667,0.667,0,0,0-.481.164,0.586,0.586,0,0,0-.2.437v7.589a0.627,0.627,0,0,0,.647.6h2.7a0.64,0.64,0,0,0,.614-0.415l0.006-.016,0.115,0.111c0.4,0.392.816,0.8,1.1,1.022C7.388,16.683,9.415,18.11,9.82,18.36a3.811,3.811,0,0,0,1.707.522,1.925,1.925,0,0,0,1.058-.26,1.723,1.723,0,0,0,1.06-.045,1.944,1.944,0,0,0,.972-0.778,2.08,2.08,0,0,0,1.113-.192,1.713,1.713,0,0,0,.789-0.715,1.751,1.751,0,0,0,1.034-.392,1.461,1.461,0,0,0,.562-1.323l0.4-.251c0.016,0.052.031,0.1,0.043,0.14a0.644,0.644,0,0,0,.624.443h2.7a0.627,0.627,0,0,0,.646-0.6V7.317A0.627,0.627,0,0,0,21.882,6.714ZM21.235,7.93V14.3H19.663C19.2,12.931,18.089,9.94,17.4,8.493A15.59,15.59,0,0,1,21.235,7.93Zm-7.906,5.108a0.667,0.667,0,0,0-.448.169,0.575,0.575,0,0,0-.019.85c0.182,0.178,1.731,1.69,2.467,2.334a0.524,0.524,0,0,1-.18.139,0.59,0.59,0,0,1-.473.048l-0.04-.034c-0.5-.372-1.77-1.629-2.179-2.045a0.663,0.663,0,0,0-.45-0.2,0.683,0.683,0,0,0-.466.159,0.579,0.579,0,0,0-.025.863c0.3,0.305,1.306,1.3,1.974,1.88a0.656,0.656,0,0,1-.309.245,0.3,0.3,0,0,1-.3-0.031L12.847,17.4c-0.651-.514-2.04-1.9-2.2-2.058a0.666,0.666,0,0,0-.454-0.192,0.7,0.7,0,0,0-.463.163,0.577,0.577,0,0,0-.031.85c0.157,0.157.861,0.862,1.521,1.47a2.812,2.812,0,0,1-.691-0.275c-0.317-.2-2.294-1.578-3.991-2.909C6.31,14.261,5.9,13.858,5.535,13.5c-0.23-.225-0.443-0.431-0.6-0.578,0.376-1.168.864-2.787,1.158-4.162H8.253a3.129,3.129,0,0,0-.984,2.453,1.257,1.257,0,0,0,.9.889,2.524,2.524,0,0,0,2.817-.853,2.652,2.652,0,0,0,.656-0.206l0.184-.075c0.519,0.447,1.2.962,1.859,1.461A15.126,15.126,0,0,1,16.8,15.166a0.305,0.305,0,0,1-.085.419h0a0.413,0.413,0,0,1-.293.1c-0.309-.236-1.575-1.441-2.62-2.46a0.664,0.664,0,0,0-.454-0.188H13.329ZM11.977,9.667a0.684,0.684,0,0,0-.22.036c-0.306.1-.5,0.184-0.652,0.248a1.372,1.372,0,0,1-.543.14,0.652,0.652,0,0,0-.473.265,1.222,1.222,0,0,1-1.509.6,0.071,0.071,0,0,1-.051-0.025A2.639,2.639,0,0,1,9.473,9.3c1.414-1.324,2.093-1.635,3.59-1.009,1.417,0.593,2.81,1.078,3.419,1.283C17,10.755,17.669,12.514,18.1,13.73l-0.55.347A22.3,22.3,0,0,0,14.5,11.5c-0.813-.616-1.581-1.2-2.067-1.653A0.673,0.673,0,0,0,11.977,9.667ZM4.986,7.635A42.7,42.7,0,0,1,3.4,13.46H1.821V7.123A15.318,15.318,0,0,1,4.986,7.635Z"/>
				</svg>
			Partnership Program</a></li>
			<li><a href="#">
			<svg xmlns="https://www.w3.org/2000/svg" width="17px" height="18px" class="sidebar_icon" class="sidebar_icon">
			<path fill-rule="evenodd"  fill="rgb(129, 132, 148)"
			 d="M14.982,5.871 L14.982,1.708 C14.982,1.431 14.832,1.179 14.591,1.054 C14.328,0.911 13.972,0.895 13.803,1.137 C11.672,4.189 10.166,4.400 8.748,4.400 C8.521,4.400 8.298,4.393 8.073,4.394 L3.450,4.394 C1.540,4.394 -0.015,5.982 -0.015,7.934 L-0.015,9.194 C-0.015,11.146 1.540,12.735 3.450,12.735 L5.572,12.735 L6.810,17.473 C6.921,17.749 7.190,17.934 7.481,17.934 C7.572,17.934 7.659,17.917 7.759,17.879 C8.131,17.722 8.306,17.297 8.155,16.910 L7.143,12.735 L8.069,12.735 C10.319,12.735 13.107,13.245 13.611,15.674 C13.632,15.773 13.642,15.823 13.673,15.864 C13.862,16.122 14.214,16.222 14.480,16.128 C14.777,16.025 14.977,15.743 14.977,15.425 L14.977,11.268 C16.167,10.933 16.988,9.840 16.988,8.571 C16.997,7.315 16.160,6.199 14.982,5.871 ZM15.894,8.571 C15.894,9.706 14.993,9.661 14.982,9.650 L14.982,7.483 C14.998,7.483 15.894,7.566 15.894,8.571 ZM5.334,5.878 L5.334,11.251 L3.450,11.251 C2.340,11.251 1.438,10.328 1.438,9.194 L1.438,7.934 C1.438,6.800 2.340,5.878 3.450,5.878 L5.334,5.878 ZM6.782,5.878 L8.069,5.878 C10.199,5.878 12.599,5.121 13.531,3.667 C13.532,5.595 13.531,11.353 13.530,13.008 C12.917,12.229 11.723,11.251 8.069,11.251 L6.782,11.251 L6.782,5.878 Z"/>
			</svg>
			Endorsement</a></li>
			<li><a href="#">
				<svg xmlns="http://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
				  <defs>
					<style>
					  .cls-1 {
						fill: #ff815b;
						fill-opacity: 0;
					  }

					  .cls-2 {
						fill: #818494;
						fill-rule: evenodd;
					  }
					</style>
				  </defs>
				  <rect class="cls-1" width="23" height="23"/>
				  <path id="Payouts-2" data-name="Payouts" class="cls-2" d="M11.952,11.368H10.921a0.793,0.793,0,0,1,0-1.585h2.062a0.756,0.756,0,0,0,0-1.513H12.192V7.477a0.755,0.755,0,1,0-1.511,0V8.282a2.306,2.306,0,0,0,.24,4.6h1.031a0.793,0.793,0,0,1,0,1.586H9.89a0.756,0.756,0,0,0,0,1.513h0.791v0.793a0.755,0.755,0,1,0,1.511,0V15.967A2.306,2.306,0,0,0,11.952,11.368Zm5.486-5.256a8.495,8.495,0,0,0-12,12.025A8.495,8.495,0,0,0,17.437,6.112Zm0.975,6.012a6.976,6.976,0,1,1-6.976-6.99A6.991,6.991,0,0,1,18.413,12.125Z"/>
				</svg>
			Payouts</a></li>
			<li><a href="#">
			<svg xmlns="https://www.w3.org/2000/svg" width="23" height="23" viewBox="0 0 23 23" class="sidebar_icon">
			  <defs>
				<style>
				  .cls-1 {
					fill: #ff815b;
					fill-opacity: 0;
				  }

				  .cls-2 {
					fill: #818494;
					fill-rule: evenodd;
				  }
				</style>
			  </defs>
			  <rect class="cls-1" width="23" height="23"/>
			  <path id="Settings-2" data-name="Settings" class="cls-2" d="M18.913,9.88l-1.1-.187a7.277,7.277,0,0,0-.406-0.983L18.054,7.8a1.3,1.3,0,0,0-.141-1.681l-1.094-1.1a1.332,1.332,0,0,0-1.68-.14l-0.911.649A7.232,7.232,0,0,0,13.2,5.107L13.019,4.022a1.3,1.3,0,0,0-1.289-1.091H10.184a1.3,1.3,0,0,0-1.289,1.09L8.7,5.139a7.1,7.1,0,0,0-.98.413L6.825,4.9a1.309,1.309,0,0,0-1.683.14l-1.1,1.1A1.3,1.3,0,0,0,3.9,7.823L4.559,8.746a7.091,7.091,0,0,0-.4.986l-1.085.184a1.3,1.3,0,0,0-1.091,1.29v1.546a1.3,1.3,0,0,0,1.09,1.289l1.116,0.191a7,7,0,0,0,.413.98l-0.645.9A1.3,1.3,0,0,0,4.1,17.792l1.094,1.1a1.333,1.333,0,0,0,1.679.139l0.922-.656a7.245,7.245,0,0,0,.945.388l0.184,1.1a1.3,1.3,0,0,0,1.29,1.091h1.549a1.3,1.3,0,0,0,1.29-1.091l0.187-1.1a7.181,7.181,0,0,0,.983-0.407L15.128,19a1.335,1.335,0,0,0,1.683-.142l1.094-1.094a1.3,1.3,0,0,0,.141-1.682L17.4,15.172a7.267,7.267,0,0,0,.408-0.982l1.1-.184A1.305,1.305,0,0,0,20,12.715V11.174A1.288,1.288,0,0,0,18.913,9.88Zm-2.353,3.5a5.684,5.684,0,0,1-.616,1.483,0.714,0.714,0,0,0,.032.779l0.846,1.194-0.946.945-1.188-.846a0.714,0.714,0,0,0-.781-0.032,5.7,5.7,0,0,1-1.478.615,0.7,0.7,0,0,0-.528.574l-0.243,1.44H10.322l-0.24-1.441a0.715,0.715,0,0,0-.526-0.573,5.905,5.905,0,0,1-1.451-.6,0.739,0.739,0,0,0-.773.039l-1.2.853-0.945-.946L6.03,15.682A0.728,0.728,0,0,0,6.061,14.9a5.57,5.57,0,0,1-.623-1.478,0.729,0.729,0,0,0-.573-0.525l-1.449-.245V11.311l1.427-.239a0.715,0.715,0,0,0,.578-0.531,5.625,5.625,0,0,1,.6-1.484A0.713,0.713,0,0,0,5.989,8.28l-0.854-1.2,0.947-.945L7.263,6.977a0.732,0.732,0,0,0,.785.031,5.6,5.6,0,0,1,1.478-.624,0.724,0.724,0,0,0,.523-0.573L10.3,4.361h1.335l0.24,1.428a0.716,0.716,0,0,0,.531.577,5.761,5.761,0,0,1,1.518.624,0.717,0.717,0,0,0,.78-0.034l1.18-.849,0.946,0.946L15.98,8.241a0.72,0.72,0,0,0-.032.782,5.668,5.668,0,0,1,.614,1.48,0.7,0.7,0,0,0,.574.527l1.438,0.243,0,1.336-1.439.24A0.716,0.716,0,0,0,16.559,13.375ZM10.992,7.927A4.014,4.014,0,1,0,15,11.941,4.018,4.018,0,0,0,10.992,7.927Zm2.579,4.014a2.58,2.58,0,1,1-2.579-2.581A2.583,2.583,0,0,1,13.571,11.941Z"/>
			</svg>
			Settings</a></li>
			
		</ul>
	</div>
	<div class="sidebar_footer">
		<p>Copyright © 2019 <a href="#">Discovered.tv</a></p>
	</div>
</div>

<!-- Sidebar section End -->

<!-- page main wrapper start -->
<div class="dis_dash_main_warappeer">
	<!-- header sectipon start -->
<div class="dis_dashboard_header_wrapper">
	<div class="dash_header_inner">
		<div class="dash_topmenu">
			
				
					<div class="top_search">
						<div class="search_box">
							<input type="search" placeholder="Search Dashboard">
								<svg xmlns="http://www.w3.org/2000/svg" width="16px" height="16px">
										<path fill-rule="evenodd"  fill="rgb(129, 132, 148)"
										 d="M14.626,14.626 C14.077,15.176 13.186,15.176 12.636,14.626 L9.193,11.182 C9.991,10.669 10.669,9.990 11.183,9.193 L14.626,12.636 C15.176,13.186 15.176,14.077 14.626,14.626 ZM5.658,11.286 C2.549,11.286 0.029,8.766 0.029,5.657 C0.029,2.549 2.549,0.029 5.658,0.029 C8.766,0.029 11.286,2.549 11.286,5.657 C11.286,8.766 8.766,11.286 5.658,11.286 ZM5.658,1.436 C3.330,1.436 1.436,3.330 1.436,5.657 C1.436,7.985 3.330,9.879 5.658,9.879 C7.985,9.879 9.879,7.985 9.879,5.657 C9.879,3.330 7.985,1.436 5.658,1.436 ZM3.312,5.657 L2.374,5.657 C2.374,3.847 3.847,2.374 5.658,2.374 L5.658,3.312 C4.365,3.312 3.312,4.364 3.312,5.657 Z"/>
								</svg>
						</div>
					</div>
				
				
					<div class="top_information">
						<ul>
							<li>
								<a href="#">
								<div class="header_notification">
									<i class="fa fa-bell-o" aria-hidden="true"></i>
									<span>50</span>
								</div>
								</a>
							</li>
							<li>
								<div class="header_profile">
										<p class="admin_name"><span>Howdy,</span>Rachel Greene</p>
											<img src="https://192.168.1.223/discovered/repo/images/dashboard_profile.jpg" alt="dashboard Logo">
										<i class="fa fa-sort-desc user_caret" aria-hidden="true"></i>
										<ul class="profile_dropdown">
											<li><a href="#">Dropdown1</a></li>
											<li><a href="#">Dropdown2</a></li>
											<li><a href="#">Dropdown3</a></li>
											<li><a href="#">Dropdown4</a></li>
										</ul>
								</div>
							</li>
						</ul>
					</div>
				
			
			
			
		</div>
	</div>
</div>
<!-- header section End -->
	<div class="dash_page_section">
		<!-- payouts  page start -->
			<div class="dash_payout_wrapper">
				<!-- top section start -->
				<div class="row">
					<div class="col-12">
						<div class="dash_ctab_wrapper">
							<ul class="">
								<li class="active"><a data-toggle="tab" href="#billing_info">Billing Information</a></li>
								<li><a data-toggle="tab" href="#pay_method">Payment Methods</a></li>
							</ul>

							<div class="tab-content">
								<div id="billing_info" class="tab-pane fade in active">
									<div class="">
										<div class="gi_section">
											<h4 class="gi_title">General Information</h4>
											<div class="gi_inner_box">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Your Name</label>
														  <input type="text" class="form-control" placeholder="Rachel Green">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Your Contact</label>
														  <input type="text" class="form-control" placeholder="415-200-6025">
														</div>
													</div>
													
													<div class="col-md-6">
														<div class="form-group">
															<label class="input_lble">Your Primary Email Address
																<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Check The Button To Use This Email As Default For Billing Information"></i>
															</label>
															<div class="checkbox_inputs ">
															  <input type="text" class="form-control " placeholder="rachel.green@demomail.com">
															  <div class="custm_checkbox abslt_pos">
																  <input type="checkbox" id="parimary_mail" class="hide_checkbox">
																  <label class="backend_checkbox" for="parimary_mail"></label>
															  </div>
															</div>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="input_lble">Secondary Email
																<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Check The Button To Use This Email As Default For Billing Information"></i>
															</label>
															<div class="input_with_addmore">
																<div class="checkbox_inputs ">
																  <input type="text" class="form-control " placeholder="rachel.green@demomail.com">
																  <div class="custm_checkbox abslt_pos">
																	  <input type="checkbox" id="Secondary" class="hide_checkbox">
																	  <label class="backend_checkbox" for="Secondary"></label>
																  </div>
																</div>
																<div class="addmore_btn">
																	<a href="" class="add_btn ">
																		<i class="fa fa-times cross_btn" aria-hidden="true"></i>
																	</a>
																	<a href="" class="add_btn cut">
																		<i class="fa fa-plus plus_btn" aria-hidden="true"></i>
																	</a>
																</div>
															</div>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="gi_section">
											<h4 class="gi_title">Tax Information</h4>
											<div class="gi_inner_box">
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Tax Entity Classification</label>
														  <input type="text" class="form-control" placeholder="Individual/ Sole proprietor/ Single-member LLC">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Tax Entity - Other</label>
														  <input type="text" class="form-control" placeholder="Please Specify">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
															<label class="input_lble">Tax Entity ID
																<i class="fa fa-question-circle" aria-hidden="true" data-toggle="tooltip" data-placement="right" title="Alpha numeric 15 characters"></i>
															
															</label>
														  <input type="text" class="form-control" placeholder="123-45-6789">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Address Line 1</label>
														  <input type="text" class="form-control" placeholder="457, Larry Street">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Address Line 2</label>
														  <input type="text" class="form-control" placeholder="123-45-6789">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">City</label>
														  <!--input type="text" class="form-control" placeholder="San Francisco"-->
														  <select class="dash_select_box setting_selecbox " name="city" >
														  <option value="01">San Francisco</option>
														  <option value="02">San Francisco</option>
														  <option value="03">San Francisco</option>
														</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">State</label>
														  <!--input type="text" class="form-control" placeholder="San Francisco"-->
														   <select class="dash_select_box setting_selecbox " name="state" >
														  <option value="01">San Francisco1</option>
														  <option value="02">San Francisco1</option>
														  <option value="03">San Francisco1</option>
														</select>
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Zip Code</label>
														  <input type="text" class="form-control" placeholder="San Francisco">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Country</label>
														  <input type="text" class="form-control" placeholder="San Francisco">
														</div>
													</div>
												</div>
												<div class="row">
													<div class="col-md-12">
														<div class="terms_conditon">
															<ul>
																<li>
																	<div class="custm_checkbox ">
																	  <input type="checkbox" id="terms1" class="hide_checkbox">
																	  <label class="backend_checkbox" for="terms1"></label>
																	</div>
																	<label class="terms_label">I am a U.S. citizen or other U.S. person per US Tax regulations.</label>
																</li>
																<li>
																	<div class="custm_checkbox ">
																	  <input type="checkbox" id="terms2" class="hide_checkbox">
																	  <label class="backend_checkbox" for="terms2"></label>
																	</div>
																	<label class="terms_label">The Tax ID number shown on this form is your correct taxpayer identification number.</label>
																</li>
																<li>
																	<div class="custm_checkbox ">
																	  <input type="checkbox" id="terms3" class="hide_checkbox">
																	  <label class="backend_checkbox" for="terms3"></label>
																	</div>
																	<label class="terms_label">I am not subject to backup withholding. </label>
																</li>
															</ul>
														</div>
													</div>
												</div>
											</div>
										</div>
										<div class="be_btn_wrapper text-right">
											<a class="backend_btn m-r-30">reset</a>
											<a class="backend_btn color_green">save</a>
										</div>
									</div>
								</div>
								<div id="pay_method" class="tab-pane fade">
									<div class="paymet_methos_wrapper">
										
										<div class="gi_section">
											<h4 class="gi_title">Payment Methods</h4>
											<div class="gi_inner_box">
												<div class="row">
												<div class="col-md-12">
													<div class="paymrnt_btn_wrappr ">
														<ul>
															<li class="active">
																<div class="custm_checkbox custm_radio">
																  <input type="radio" id="ach_checkbox" class="hide_checkbox" name="optradio" checked>
																  <label class="backend_checkbox" for="ach_checkbox">ach</label>
																</div>
															</li>
															<li>
																<div class="custm_checkbox custm_radio">
																  <input type="radio" id="pal_checkbox" class="hide_checkbox" name="optradio">
																  <label class="backend_checkbox" for="pal_checkbox">Paypal</label>
																</div>
															</li>
														</ul>
														
													</div>
												</div>
												</div>
												<div class="row">
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Bank Name</label>
														  <input type="text" class="form-control" placeholder="Enter Bank Name">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Bank Account Number</label>
														  <input type="text" class="form-control" placeholder="Enter Bank Account Number">
														</div>
													</div>
													<div class="col-md-6">
														<div class="form-group">
														  <label class="input_lble">Routing Number</label>
														  <input type="text" class="form-control" placeholder="Enter Routing Number">
														</div>
													</div>

												</div>
											</div>
										</div>
										<div class="be_btn_wrapper text-right">
											<a class="backend_btn m-r-30">reset</a>
											<a class="backend_btn color_green">save</a>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- top section End -->
				
				
			</div>	
		<!-- payout  page End-->
		
		
		<!--sucess meg start
		<div class="toster_popup success">
			<div class="toster_inner">
				<div class="toster_icon">
					<svg viewBox="0 0 512 512" width="39px"  height="39px" xmlns="http://www.w3.org/2000/svg"><path d="m256 0c-141.164062 0-256 114.835938-256 256s114.835938 256 256 256 256-114.835938 256-256-114.835938-256-256-256zm0 0" fill="#28f0b0"/><path d="m385.75 201.75-138.667969 138.664062c-4.160156 4.160157-9.621093 6.253907-15.082031 6.253907s-10.921875-2.09375-15.082031-6.253907l-69.332031-69.332031c-8.34375-8.339843-8.34375-21.824219 0-30.164062 8.339843-8.34375 21.820312-8.34375 30.164062 0l54.25 54.25 123.585938-123.582031c8.339843-8.34375 21.820312-8.34375 30.164062 0 8.339844 8.339843 8.339844 21.820312 0 30.164062zm0 0" fill="#fafafa"/></svg>	

				</div>
				<div class="toster_info">
					<p class="top">Success!</p>
					<p class="bottom">Information Updated.</p>
				</div>
			</div>
		</div>
		
		<div class="toster_popup error">
			<div class="toster_inner">
				<div class="toster_icon">
					<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 512 512" width="39px" height="39px" style="enable-background:new 0 0 512 512;">
						<g>
							<g>
								<path d="M256,0C114.848,0,0,114.848,0,256s114.848,256,256,256s256-114.848,256-256S397.152,0,256,0z M256,480
									C132.48,480,32,379.52,32,256S132.48,32,256,32s224,100.48,224,224S379.52,480,256,480z" fill="#ff6c4f"/>
							</g>
						</g>
						<g>
							<g>
								<rect x="240" y="112" width="32" height="224" fill="#ff6c4f"/>
							</g>
						</g>
						<g>
							<g>
								<rect x="240" y="368" width="32" height="32" fill="#ff6c4f"/>
							</g>
						</g>
					</svg>	

				</div>
				<div class="toster_info">
					<p class="top">Error!</p>
					<p class="bottom">Something Went Wrong.</p>
				</div>
			</div>
		</div-->
		
	
	</div>
</div>


<!-- page main wrapper End -->

<!-- bootstrap js -->
<script async src="<?php echo base_url();?>repo/js/bootstrap.js"></script>
<!-- select2 offline js -->
<script async src="<?php echo base_url();?>repo_admin/js/select2.min.js"></script>
<!-- select2 cdn js -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.10/js/select2.min.js"></script>
<!-- datatable js -->
<script type="text/javascript" src="https://cdn.datatables.net/v/bs/dt-1.10.18/r-2.2.2/datatables.min.js"></script>
<!-- datepicker js -->
<script async src="<?php echo base_url();?>repo/js/daterangepicker.js"></script>
<!-- moment js this is required for rangepicker-->
<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.22.1/moment.min.js"></script>
<!-- custome js -->
<script async src="<?php echo base_url();?>repo/js/dasboard.js?q=<?php echo date('his');?>"></script>
</body>


</html>