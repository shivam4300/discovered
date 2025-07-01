<!--script src="https://cdn.jsdelivr.net/clipboard.js/1.5.12/clipboard.min.js"></script-->

<!--Share post popup start-->
<div class="dis_common_popup dis_sharepost_popup custom_scrol" data-modal="share">
	<div class="common_popup_inner sharepost_inner">
		<h4 class="popup_heading">share post</h4>
		<ul class="share_social">
			<li>
				<a data-share-post="embed">
					<div class="sharepost_icon">

						<svg xmlns="http://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 94.504 94.504" class="share_top">
						<g>
							<g>
								<path d="M93.918,45.833L69.799,21.714c-0.75-0.75-2.077-0.75-2.827,0l-5.229,5.229c-0.781,0.781-0.781,2.047,0,2.828
									l17.477,17.475L61.744,64.724c-0.781,0.781-0.781,2.047,0,2.828l5.229,5.229c0.375,0.375,0.884,0.587,1.414,0.587
									c0.529,0,1.039-0.212,1.414-0.587l24.117-24.118C94.699,47.881,94.699,46.614,93.918,45.833z"/>
								<path d="M32.759,64.724L15.285,47.248l17.477-17.475c0.375-0.375,0.586-0.883,0.586-1.414c0-0.53-0.21-1.039-0.586-1.414
									l-5.229-5.229c-0.375-0.375-0.884-0.586-1.414-0.586c-0.53,0-1.039,0.211-1.414,0.586L0.585,45.833
									c-0.781,0.781-0.781,2.047,0,2.829L24.704,72.78c0.375,0.375,0.884,0.587,1.414,0.587c0.53,0,1.039-0.212,1.414-0.587l5.229-5.229
									C33.542,66.771,33.542,65.505,32.759,64.724z"/>
								<path d="M60.967,13.6c-0.254-0.466-0.682-0.812-1.19-0.962l-4.239-1.251c-1.058-0.314-2.172,0.293-2.484,1.352L33.375,79.382
									c-0.15,0.509-0.092,1.056,0.161,1.521c0.253,0.467,0.682,0.812,1.19,0.963l4.239,1.251c0.189,0.056,0.38,0.083,0.567,0.083
									c0.863,0,1.66-0.564,1.917-1.435l19.679-66.644C61.278,14.612,61.221,14.065,60.967,13.6z"/>
							</g>
						</g>
						</svg>
					</div>
					<div class="sharepost_name">Embed</div>
				</a>
			</li>
			<li><?php if(is_login()){ ?>
					<a target="_blank" data-share-post="discovered" >
				<?php }else{ ?>
					<a class="openModalPopup" data-href="modal/login_popup" data-cls="login_mdl" onclick="$('.dis_sharepost_popup').removeClass('open_commonpopup');">
 				<?php } ?>
					<div class="sharepost_icon discovered">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="30px" class="share_top">
						<path fill-rule="evenodd" fill="rgb(236, 88, 33)"
						d="M15.377,17.693 C15.377,20.566 14.274,22.002 12.068,22.002 C11.149,22.002 10.360,21.702 9.701,21.103 C9.042,20.504 8.713,19.786 8.713,18.949 C8.713,18.083 9.031,17.343 9.667,16.730 C10.302,16.117 11.064,15.810 11.953,15.810 C12.658,15.810 13.362,16.069 14.067,16.589 L14.067,7.669 C13.041,7.583 12.275,7.540 11.769,7.540 C8.583,7.540 5.825,8.651 3.497,10.874 C1.168,13.096 0.004,15.731 0.004,18.776 C0.004,21.922 1.176,24.578 3.520,26.743 C5.864,28.908 8.743,29.990 12.160,29.990 C13.875,29.990 15.549,29.636 17.181,28.929 C18.812,28.222 20.157,27.270 21.214,26.072 C23.052,23.993 23.971,21.302 23.971,17.996 L23.971,0.005 L15.377,0.005 L15.377,17.693 Z"/>
						</svg>
					</div>
					<div class="sharepost_name">Discovered</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="facebook" href="" >
					<div class="sharepost_icon facebook">
						<svg xmlns="https://www.w3.org/2000/svg" width="30px" height="30px" viewBox="0 0 430.113 430.114" class="share_top"><g><g>
							<path id="Facebook" d="M158.081,83.3c0,10.839,0,59.218,0,59.218h-43.385v72.412h43.385v215.183h89.122V214.936h59.805   c0,0,5.601-34.721,8.316-72.685c-7.784,0-67.784,0-67.784,0s0-42.127,0-49.511c0-7.4,9.717-17.354,19.321-17.354   c9.586,0,29.818,0,48.557,0c0-9.859,0-43.924,0-75.385c-25.016,0-53.476,0-66.021,0C155.878-0.004,158.081,72.48,158.081,83.3z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#FFFFFF"/>
						</g></g> </svg>
					</div>
					<div class="sharepost_name">Facebook</div>
				</a>
			</li>
			<li><a target="_blank" data-share-link="twitter" href="" >
					<div class="sharepost_icon twitter 2">
						<svg width="30px" height="30px" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 1226.37 1226.37" ><g><path d="M727.348 519.284 1174.075 0h-105.86L680.322 450.887 370.513 0H13.185l468.492 681.821L13.185 1226.37h105.866l409.625-476.152 327.181 476.152h357.328L727.322 519.284zM582.35 687.828l-47.468-67.894-377.686-540.24H319.8l304.797 435.991 47.468 67.894 396.2 566.721H905.661L582.35 687.854z" fill="#000000" opacity="1" data-original="#000000" class="" style="fill: #ffffff;"></path></g></svg>
					</div>
					<div class="sharepost_name">Twitter</div>
				</a>
			</li>

			<li>
				<a target="_blank" data-share-link="linkedin" href="" rel="noopener">
					<div class="sharepost_icon linkedin">
						<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" version="1.1" id="Layer_1" x="0px" y="0px" viewBox="0 0 512.02 512.02" style="enable-background:new 0 0 512.02 512.02;" xml:space="preserve" width="32" height="32"><g><g transform="translate(0 -1)">
							<path d="M61.813,5.424c33.545,0,61.793,28.248,61.793,61.793c0,35.31-30.014,62.676-61.793,61.793   C29.151,129.893,0.02,102.527,0.02,67.217C-0.863,32.789,27.386,5.424,61.813,5.424" data-original="#007AAA" class="active-path" data-old_color="#007AAA" fill="#FFFFFF"/>
							<path d="M96.241,508.596H25.62c-9.71,0-17.655-7.945-17.655-17.655V181.976   c0-9.71,7.062-17.655,16.772-17.655h71.503c9.71,0,17.655,7.945,17.655,17.655v309.848   C113.896,500.651,105.951,508.596,96.241,508.596" data-original="#007AAA" class="active-path" data-old_color="#007AAA" fill="#FFFFFF"/>
							<path d="M511.137,272.017c0-65.324-48.552-116.524-113.876-116.524h-18.538   c-35.31,0-69.738,16.772-88.276,44.138c-6.179,6.179-8.828,8.828-8.828,8.828v-35.31c0-3.531-5.297-8.828-8.828-8.828h-88.276   c-3.531,0-8.828,3.531-8.828,7.945v329.269c0,3.531,5.297,7.062,8.828,7.062h97.103c3.531,0,8.828-3.531,8.828-7.062V310.858   c0-32.662,24.717-60.028,57.379-60.91c16.772,0,31.779,6.179,43.255,17.655c10.593,10.593,15.007,25.6,15.007,42.372v189.793   c0,3.531,5.297,8.828,8.828,8.828h88.276c3.531,0,8.828-5.297,8.828-8.828V272.017H511.137z" data-original="#007AAA" class="active-path" data-old_color="#007AAA" fill="#FFFFFF"/>
						</g></g> </svg>

					</div>
					<div class="sharepost_name">LinkedIn </div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="pinterest" href="">
					<div class="sharepost_icon pinterest">
						<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 310.05 310.05" width="30px" height="30px" class="share_top"><g><g id="XMLID_798_">
						<path id="XMLID_799_" d="M245.265,31.772C223.923,11.284,194.388,0,162.101,0c-49.32,0-79.654,20.217-96.416,37.176   c-20.658,20.9-32.504,48.651-32.504,76.139c0,34.513,14.436,61.003,38.611,70.858c1.623,0.665,3.256,1,4.857,1   c5.1,0,9.141-3.337,10.541-8.69c0.816-3.071,2.707-10.647,3.529-13.936c1.76-6.495,0.338-9.619-3.5-14.142   c-6.992-8.273-10.248-18.056-10.248-30.788c0-37.818,28.16-78.011,80.352-78.011c41.412,0,67.137,23.537,67.137,61.425   c0,23.909-5.15,46.051-14.504,62.35c-6.5,11.325-17.93,24.825-35.477,24.825c-7.588,0-14.404-3.117-18.705-8.551   c-4.063-5.137-5.402-11.773-3.768-18.689c1.846-7.814,4.363-15.965,6.799-23.845c4.443-14.392,8.643-27.985,8.643-38.83   c0-18.55-11.404-31.014-28.375-31.014c-21.568,0-38.465,21.906-38.465,49.871c0,13.715,3.645,23.973,5.295,27.912   c-2.717,11.512-18.865,79.953-21.928,92.859c-1.771,7.534-12.44,67.039,5.219,71.784c19.841,5.331,37.576-52.623,39.381-59.172   c1.463-5.326,6.582-25.465,9.719-37.845c9.578,9.226,25,15.463,40.006,15.463c28.289,0,53.73-12.73,71.637-35.843   c17.367-22.418,26.932-53.664,26.932-87.978C276.869,77.502,265.349,51.056,245.265,31.772z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#FFFFFF"/>
					</g></g> </svg>
					</div>
					<div class="sharepost_name">Pinterest</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="skype" href="">
					<div class="sharepost_icon skype">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="28" height="28" viewBox="0 0 512 512"><g><path d="M361.473 512c-22.453 0-44.696-4.977-64.762-14.441-14.27 2.34-26.543 3.441-38.191 3.441C123.14 501 13 392.191 13 258.45c0-13.024 1.293-26.575 3.937-41.216A147.132 147.132 0 0 1 0 148.7C0 66.707 67.54 0 150.559 0c25.836 0 51.078 6.523 73.468 18.93 10.2-1.313 21.36-1.93 34.493-1.93 65.417 0 126.828 25.05 172.917 70.531C477.586 133.074 503 193.777 503 258.457c0 17.137-1.168 31.672-3.648 45.113C507.75 322.332 512 342.39 512 363.3c0 81.993-67.527 148.7-150.527 148.7zm-58.446-56.195 6.145 3.265c15.898 8.457 33.984 12.93 52.3 12.93C422.419 472 472 423.238 472 363.3c0-16.995-3.84-33.187-11.418-48.124l-3.25-6.414 1.574-7.016C461.7 289.31 463 275.555 463 258.457 463 147.375 371.27 57 258.52 57c-14.079 0-25.258.781-35.192 2.465l-7.465 1.262-6.43-3.997C191.825 45.785 171.466 40 150.56 40 89.598 40 40 88.762 40 148.7c0 19.335 5.234 38.316 15.137 54.89l3.988 6.68-1.57 7.62C54.488 232.73 53 245.997 53 258.45 53 370.136 145.195 461 258.52 461c10.949 0 22.917-1.254 37.667-3.945zM385 310.863c0-14.383-2.84-26.75-8.504-36.758-5.664-9.964-13.629-18.308-23.648-24.82-9.852-6.375-21.942-11.836-35.907-16.305-13.804-4.378-29.414-8.41-46.394-12.039-13.438-3.086-23.215-5.488-29.043-7.086-5.695-1.558-11.36-3.77-16.883-6.511-5.324-2.645-9.516-5.828-12.531-9.422-2.813-3.43-5.09-7.39-5.09-12.14 0-7.762 5.164-14.302 13.934-20 9.074-5.93 21.3-8.384 36.34-8.384 16.175 0 28 2.184 35.038 7.504 7.27 5.461 13.602 13.258 18.825 23.16 4.527 7.735 8.605 13.13 12.531 16.583 4.223 3.695 10.32 5.355 18.09 5.355 8.543 0 15.812-2.777 21.539-8.71 5.726-5.903 9.703-12.685 9.703-20.138 0-7.73-3.305-15.73-7.66-23.77-4.324-7.972-11.153-15.628-20.399-22.816-9.148-7.125-20.832-12.886-34.636-17.156-13.766-4.277-30.254-6.41-49.004-6.41-23.418 0-44.149 3.215-61.535 9.625-17.653 6.477-31.383 15.973-40.73 28.11-9.477 12.234-15.036 26.44-15.036 42.21 0 16.547 5.32 30.578 14.328 41.801 8.883 11.024 20.996 19.836 36.074 26.211 14.703 6.207 33.188 11.695 54.965 16.34 16.012 3.363 28.969 6.55 38.52 9.5 9.11 2.816 16.68 6.953 22.41 12.273 5.426 5.055 8.398 11.532 8.398 19.77 0 10.414-5.422 18.957-15.87 26.043C292.14 350.137 277.933 353 260.584 353c-12.632 0-22.878-.996-30.483-4.594-7.57-3.527-13.5-8.11-17.59-13.496-4.317-5.664-8.367-12.773-12.086-21.258-3.32-7.757-7.434-13.8-12.293-17.863-5.059-4.242-11.281-6.789-18.485-6.789-8.77 0-16.14 3.121-21.906 8.48-5.793 5.422-8.742 12.063-8.742 19.696 0 12.238 4.52 24.922 13.465 37.672 8.777 12.644 20.43 22.918 34.531 30.484C206.691 395.742 231.98 401 262.125 401c25.086 0 47.164-3.863 65.547-11.453 18.59-7.66 32.922-18.477 42.637-32.14 9.75-13.7 14.671-29.36 14.691-46.544zm0 0" fill="#ffffff" data-original="#000000" class=""></path></g></svg>
					</div>
					<div class="sharepost_name">Skype</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="tumblr" href="">
					<div class="sharepost_icon tumblr">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="28" height="28" viewBox="0 0 512 512"><g><path d="M348.832 428.544c-14.208 0-27.52-3.328-38.528-10.048-8.8-5.12-16.544-13.824-19.616-22.112-3.072-8.448-2.752-25.728-2.752-55.296V223.84h128.096v-95.808H287.936V0h-82.528c-3.328 26.336-9.312 48.192-18.112 65.088-8.416 17.216-20.256 31.776-34.24 44.096-14.464 12.192-36.864 21.504-57.088 28.128v72.544h68.704V389.28c0 23.52 2.368 41.472 7.424 53.728 4.96 12.352 13.44 23.968 26.4 34.848 12.736 11.008 27.968 19.456 46.176 25.408C262.4 509.12 276.352 512 299.808 512c20.576 0 39.808-1.952 57.408-6.272 17.632-4 36.8-10.08 58.816-20.352v-76.96c-25.728 16.8-41.248 20.128-67.2 20.128z" fill="#ffffff" data-original="#000000"></path></g></svg>
						</g></g> </svg>
					</div>
					<div class="sharepost_name">Tumblr</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="reddit" href="">
					<div class="sharepost_icon reddit">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="28" height="28" viewBox="0 0 24 24"><g><path d="M17.631 2.782c.034 1.32.952 2.631 2.685 2.631 1.621 0 2.691-1.216 2.691-2.706 0-1.345-.925-2.707-2.692-2.707-.958 0-1.836.428-2.335 1.321L13.683.032a.75.75 0 0 0-.941.527l-1.923 7.31c-2.236.16-4.278.777-5.951 1.709-2.77-2.595-6.557 1.633-4.091 4.562C-.997 19.682 5.371 24 12 24c6.681 0 12.974-4.39 11.223-9.86 2.458-2.919-1.301-7.154-4.095-4.564-1.87-1.04-4.206-1.678-6.75-1.736l1.617-6.148zM20.315 1.5c.879 0 1.191.65 1.191 1.208 0 .667-.409 1.206-1.191 1.206-.88 0-1.191-.65-1.191-1.208 0-.674.412-1.206 1.191-1.206zM3.531 10.455c-.793.607-1.455 1.297-1.957 2.052-.38-1.412.839-2.632 1.957-2.052zM12 22.5c-8.943 0-13.396-7.182-6.982-11.298.422-.194 2.664-1.876 6.982-1.876 3.756 0 5.474 1.184 6.98 1.875C25.37 15.3 20.998 22.5 12 22.5zm10.426-9.992c-.502-.756-1.164-1.446-1.958-2.053 1.141-.589 2.335.654 1.958 2.053z" fill="#ffffff" data-original="#000000" class=""></path><path d="M10.305 14.935c0-1.321-.998-2.206-2.217-2.206-1.076 0-2.219.773-2.219 2.206 0 1.32.996 2.206 2.217 2.206 1.076 0 2.219-.773 2.219-2.206zm-2.936 0c0-.655.55-.706.719-.706.403 0 .717.229.717.706 0 .655-.55.706-.719.706-.168 0-.717-.051-.717-.706zM15.914 12.729c-1.076 0-2.219.773-2.219 2.206 0 1.32.996 2.206 2.217 2.206 1.076 0 2.219-.773 2.219-2.206 0-1.321-.998-2.206-2.217-2.206zm-.002 2.912c-.168 0-.717-.051-.717-.706s.55-.706.719-.706c.403 0 .717.229.717.706 0 .655-.55.706-.719.706zM15.297 18.42c-1.106 1.517-5.454 1.536-6.593.001-.56-.812-1.786.023-1.241.842 1.591 2.402 7.483 2.403 9.074 0 .536-.803-.667-1.64-1.24-.843z" fill="#ffffff" data-original="#000000" class=""></path></g></svg>
						</g></g> </svg>
					</div>
					<div class="sharepost_name">Reddit</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="livejournal" href="">
					<div class="sharepost_icon live">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="28" height="28" viewBox="0 0 304.999 304.999"><g><path d="M160.662 19.47c-18.934 0-37.323 3.668-54.693 10.881L76.353.732a2.501 2.501 0 0 0-3.082-.359C44.436 18.201 19.773 42.865 1.947 71.699a2.499 2.499 0 0 0 .358 3.082l28.39 28.383c-8.491 18.637-12.808 38.5-12.808 59.074C17.887 240.957 81.936 305 160.662 305c78.721 0 142.765-64.043 142.765-142.762 0-78.723-64.044-142.768-142.765-142.768zm56.563 129.194 9.943 42.082 9.543 44.395-44.337-9.533-42.055-9.893L36.712 102.111c14.252-29.338 38.339-52.619 68.114-65.832l112.399 112.385z" fill="#ffffff" data-original="#000000" class=""></path><path d="m221.76 187.396-7.522-33.023c-25.891 11.889-46.404 32.402-58.283 58.295l33.023 7.52c7.011-14.023 18.769-25.778 32.782-32.792z" fill="#ffffff" data-original="#000000" class=""></path></g></svg>
						</g></g> </svg>
					</div>
					<div class="sharepost_name">Livejournal</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="blogger" href="">
					<div class="sharepost_icon blogger">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="28" height="28" viewBox="0 0 24 24" ><g><path d="M20.087 7.817V6.62c0-3.65-2.97-6.62-6.619-6.62H6.619A6.628 6.628 0 0 0 0 6.62v10.76C0 21.03 2.97 24 6.619 24h10.762A6.628 6.628 0 0 0 24 17.38v-6.848c0-2.173-1.732-2.806-3.913-2.715zM22.5 17.38a5.126 5.126 0 0 1-5.119 5.12H6.619A5.126 5.126 0 0 1 1.5 17.38V6.62A5.126 5.126 0 0 1 6.619 1.5h6.849a5.126 5.126 0 0 1 5.119 5.12c.172 1.846-.59 2.803 1.353 2.706 1.395-.07 2.561-.039 2.561 1.208v6.846z" fill="#ffffff" data-original="#000000" class=""></path><path d="M8.087 10.304H12c2.94 0 2.935-4.435 0-4.435H8.087c-2.94.001-2.936 4.435 0 4.435zm0-2.934H12c.961 0 .962 1.435 0 1.435H8.087c-.961-.001-.962-1.435 0-1.435zM15.913 13.696H8.087c-2.931 0-2.936 4.435 0 4.435h7.826c2.931-.001 2.936-4.435 0-4.435zm0 2.934H8.087c-.95 0-.949-1.435 0-1.435h7.826c.95.001.949 1.435 0 1.435z" fill="#ffffff" data-original="#000000" class=""></path></g></svg>
						</g></g> </svg>
					</div>
					<div class="sharepost_name">Blogger</div>
				</a>
			</li>
			<li>
				<a target="_blank" data-share-link="whatsapp" href="">
					<div class="sharepost_icon whatsapp">
						<svg xmlns:xlink="http://www.w3.org/1999/xlink" xmlns:svgjs="http://svgjs.com/svgjs" width="28" height="28" viewBox="0 0 24 24" ><g><path d="m17.507 14.307-.009.075c-2.199-1.096-2.429-1.242-2.713-.816-.197.295-.771.964-.944 1.162-.175.195-.349.21-.646.075-.3-.15-1.263-.465-2.403-1.485-.888-.795-1.484-1.77-1.66-2.07-.293-.506.32-.578.878-1.634.1-.21.049-.375-.025-.524-.075-.15-.672-1.62-.922-2.206-.24-.584-.487-.51-.672-.51-.576-.05-.997-.042-1.368.344-1.614 1.774-1.207 3.604.174 5.55 2.714 3.552 4.16 4.206 6.804 5.114.714.227 1.365.195 1.88.121.574-.091 1.767-.721 2.016-1.426.255-.705.255-1.29.18-1.425-.074-.135-.27-.21-.57-.345z" fill="#ffffff" data-original="#000000" class=""></path><path d="M20.52 3.449C12.831-3.984.106 1.407.101 11.893c0 2.096.549 4.14 1.595 5.945L0 24l6.335-1.652c7.905 4.27 17.661-1.4 17.665-10.449 0-3.176-1.24-6.165-3.495-8.411zm1.482 8.417c-.006 7.633-8.385 12.4-15.012 8.504l-.36-.214-3.75.975 1.005-3.645-.239-.375c-4.124-6.565.614-15.145 8.426-15.145a9.865 9.865 0 0 1 7.021 2.91 9.788 9.788 0 0 1 2.909 6.99z" fill="#ffffff" data-original="#000000" class=""></path></g></svg>
					</div>
					<div class="sharepost_name">Whatsapp</div>
				</a>
			</li>
		</ul>
		<div class="sharepost_link">
			<textarea class="link_input" id="copy_input" data-share-link="copy_input" readonly="true" rows="1"></textarea>
			<div class="share_copybox" data-clipboard-target="#copy_input" id="copyContent">
				<div class="share_copyicon">
					<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 247.744 247.744" width="16px" height="16px" class=""><g><g>
						<path d="M206.89,0H74.824c-9.649,0-17.5,7.851-17.5,17.5v21.005h-16.47c-9.649,0-17.5,7.85-17.5,17.5   v174.239c0,9.649,7.851,17.5,17.5,17.5h132.065c9.649,0,17.5-7.851,17.5-17.5V209.24h16.471c9.649,0,17.5-7.851,17.5-17.5V17.5   C224.39,7.851,216.54,0,206.89,0z M175.419,230.244c0,1.355-1.145,2.5-2.5,2.5H40.854c-1.355,0-2.5-1.145-2.5-2.5V56.005   c0-1.356,1.145-2.5,2.5-2.5h132.065c1.355,0,2.5,1.144,2.5,2.5V230.244z M209.39,191.74c0,1.355-1.145,2.5-2.5,2.5h-16.471V56.005   c0-9.65-7.851-17.5-17.5-17.5H72.324V17.5c0-1.355,1.144-2.5,2.5-2.5H206.89c1.355,0,2.5,1.145,2.5,2.5V191.74z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
						<path d="M149.553,77.121H64.22c-4.143,0-7.5,3.357-7.5,7.5c0,4.143,3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5C157.053,80.479,153.696,77.121,149.553,77.121z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
						<path d="M149.553,110.898H64.22c-4.143,0-7.5,3.357-7.5,7.5s3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5S153.696,110.898,149.553,110.898z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
						<path d="M149.553,144.676H64.22c-4.143,0-7.5,3.357-7.5,7.5c0,4.143,3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5C157.053,148.033,153.696,144.676,149.553,144.676z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
						<path d="M149.553,178.453H64.22c-4.143,0-7.5,3.357-7.5,7.5c0,4.143,3.357,7.5,7.5,7.5h85.333   c4.143,0,7.5-3.357,7.5-7.5C157.053,181.811,153.696,178.453,149.553,178.453z" data-original="#000002" class="active-path" data-old_color="#000002" fill="#FFFFFF"/>
					</g></g> </svg>
				</div>
				<div class="share_copytext">
					<p>Copy</p>
				</div>
			</div>
		</div>

		<?php if(is_login()){
			?>
		<div class="Pro_search_wrrp hide">
			 <span class="ps_dissugst"></span>
			<div class="ps_search_box">
				<a class="ps_icon">
					<svg xmlns="https://www.w3.org/2000/svg" width="18px" height="18px" viewBox="0 0 485.213 485.213"><g><g><g><path d="M471.882,407.567L360.567,296.243c-16.586,25.795-38.536,47.734-64.331,64.321l111.324,111.324    c17.772,17.768,46.587,17.768,64.321,0C489.654,454.149,489.654,425.334,471.882,407.567z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path>
					<path d="M363.909,181.955C363.909,81.473,282.44,0,181.956,0C81.474,0,0.001,81.473,0.001,181.955s81.473,181.951,181.955,181.951    C282.44,363.906,363.909,282.437,363.909,181.955z M181.956,318.416c-75.252,0-136.465-61.208-136.465-136.46    c0-75.252,61.213-136.465,136.465-136.465c75.25,0,136.468,61.213,136.468,136.465    C318.424,257.208,257.206,318.416,181.956,318.416z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path><path d="M75.817,181.955h30.322c0-41.803,34.014-75.814,75.816-75.814V75.816C123.438,75.816,75.817,123.437,75.817,181.955z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#969696"></path></g></g></g>
				</svg>
				</a>
				<input type="text" class="form-control ps_search ad_search_filter" placeholder="Search People..." >

			</div>
			<div class="ps_result">
				<div class="custom_scrol ps_resultbox">
				  <span class="ps_sugst">SUGGESTED</span>
					<ul class="ad_user_list">
						<?php echo $this->common_html->pro_loader(); ?>
					</ul>

				</div>
			</div>
		</div>

		<?php
		} ?>

		<span class="common_close">
			 <svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"/></g> </svg>
        </span>
	</div>
</div>
<!--Share post popup End-->



<!-- embed share video popup   -->
<div class="dis_common_popup dis_embed_urlvideo custom_scrol"  data-modal="embed">
	<div class="common_popup_inner">
		<div class="dis_euvd_header">
			<p class="dis_euvd_ttl"> Embed Option</p>
			<span class="common_close CloseEmbedModal">
				<svg xmlns="https://www.w3.org/2000/svg" viewBox="0 0 15.642 15.642" width="11px" height="11px"><g><path fill-rule="evenodd" d="M8.882,7.821l6.541-6.541c0.293-0.293,0.293-0.768,0-1.061  c-0.293-0.293-0.768-0.293-1.061,0L7.821,6.76L1.28,0.22c-0.293-0.293-0.768-0.293-1.061,0c-0.293,0.293-0.293,0.768,0,1.061  l6.541,6.541L0.22,14.362c-0.293,0.293-0.293,0.768,0,1.061c0.147,0.146,0.338,0.22,0.53,0.22s0.384-0.073,0.53-0.22l6.541-6.541  l6.541,6.541c0.147,0.146,0.338,0.22,0.53,0.22c0.192,0,0.384-0.073,0.53-0.22c0.293-0.293,0.293-0.768,0-1.061L8.882,7.821z" data-original="#000000" class="active-path" data-old_color="#000000" fill="#EB581F"></path></g> </svg>
			</span>
		</div>
		<div class="dis_euvideo_box">

			<div class="dis_euvideoUrlBox">
				<div class="dis_euvideo_url">
					<iframe id="myIframe" class="dis_euvideo_iframe" width="100%" src="" frameborder="0" allow="autoplay" allowfullscreen="true" ></iframe>
				</div>
				<div class="dis_euvd_options">
					<ul class="dis_euvd_checkbox_list 1">
						<li>
							<div class="dis_checkbox">
								<label>
									<input class="EmbedOption" type="checkbox" value="controls" class="check" checked>
									<i class="input-helper"></i>
									<p>Show player controls</p>
								</label>
							</div>
						</li>
						<li>
							<div class="dis_checkbox">
								<label>
									<input class="EmbedOption"  type="checkbox" value="autoplay" class="check" checked>
									<i class="input-helper"></i>
									<p>Autoplay</p>
								</label>
							</div>
						</li>
						<li>
							<div class="dis_checkbox">
								<label>
									<input class="EmbedOption"  type="checkbox" value="muted" class="check" checked>
									<i class="input-helper"></i>
									<p>Muted</p>
								</label>
							</div>
						</li>
						<li>
							<div class="dis_checkbox">
								<label>
									<input class="EmbedOption"  type="checkbox" value="loop" class="check">
									<i class="input-helper"></i>
									<p>Loop the video</p>
								</label>
							</div>
						</li>

						<li>
							<div class="dis_checkbox">
								<label>
									<input class="ResponsiveEmbedOption"  type="checkbox" value="responsive" class="check" checked>
									<i class="input-helper"></i>
									<p>Responsive Player</p>
								</label>
							</div>
						</li>
					</ul>
				</div>
			</div>
			<div class="dis_euvideo_details">
				<div class="dis_euvd_body">
					<ul class="dis_tab_list">
						<li class="active dis_tab_li">
							<a data-toggle="tab" href="#embed_html" class="dis_tab_item">Iframe</a>
						</li>
						<li class="dis_tab_li">
							<a data-toggle="tab" href="#embed_jquery" class="dis_tab_item">JavaScript</a>
						</li>
					</ul>
					<div class="dis_tab_content tab-content">
						<div id="embed_html" class="tab-pane fade in active">
							<div class="dis_euvd_ta_wrap">
								<textarea id="myTextArea"></textarea>
							</div>
							<div class="dis_euvd_footer">
								<a id="copyDyanamicEmbed" class="dis_btn  dis_euvd_copybtn" href="javascript:;"><span class="share_copytext">Copy</span></a>
							</div>
						</div>
						<div id="embed_jquery" class="tab-pane">
							<div class="dis_euvd_ta_wrap">
								<textarea id="myTextArea2"></textarea>
							</div>
							<div class="dis_euvd_footer">
								<a id="copyJavascriptEmbed" class="dis_btn  dis_euvd_copybtn" href="javascript:;"><span class="share_copytext">Copy</span></a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>