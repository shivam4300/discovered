<!doctype html>
<html>

<head>
    <meta name="viewport" content="width=device-width">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <title>Discovered.TV</title>
    <link href="https://fonts.googleapis.com/css?family=Muli:300,400,600,700,800&display=swap" rel="stylesheet">
	<link rel="stylesheet" href=" https://use.typekit.net/bal4nht.css" >
</head>

<body style="margin:0;">
    <!-- body -->
    <table cellpadding="0" cellspacing="0" border="0" style="background-color:#ffffff; font-family: 'Muli', sans-serif; width:100%;">
        <tr>
            <td>
                <table cellpadding="0" cellspacing="0" border="0" style="max-width: 768px;width: 90%;margin:0 auto; margin: 50px auto; border: none;    border-radius: 0px 0px 0px 0px;overflow: hidden;">
                    <tr>
                        <td style="border: none;clear: both !important;background-color:#ffffff;display: block !important;Margin: 0 auto !important;max-width: 768px !important;border-radius:0px;  background:url(<?php echo base_url() ?>repo/images/email_temp/main_bg.jpg);  box-shadow: 0 0 10px rgba(0,0,0,0.2);">
                            <table align="center" cellpadding="0" cellspacing="0" border="0" style="width: 100%; border: none;border: none; margin-bottom: 0px;">
                                <tr style="-webkit-font-smoothing: antialiased;  height: 100%;  -webkit-text-size-adjust: none;  width: 100% !important;">
                                    <td align="center" style="float: left; padding: 40px 0 40px;text-align: center;width: 100%;position: relative;">
                                        <span style="padding-right: 10px;text-align: center;display:inline-block;">
										<img src="<?php echo base_url() ?>repo/images/email_temp/logo.png" alt="Discovered"></span>
                                    </td>
                                </tr>
                            </table>
							
							<table align="center" cellpadding="0" cellspacing="0" border="0" style="font-family: brandon-grotesque, sans-serif; width: 90%; border: none;border: none; background:url(<?php echo base_url() ?>repo/images/email_temp/welcome_bg.jpg); background-size:cover;margin-bottom: 0px;">
                                <tr style="-webkit-font-smoothing: antialiased;  height: 100%;  -webkit-text-size-adjust: none;  width: 100% !important;">
                                    <td align="center" style="float: left; padding: 360px 0 40px;text-align: center;width: 100%;position: relative;">
										<h1 style="color:#ffffff; font-size:36px; text-transform: uppercase; margin: 10px 0px; font-weight: 300;">Welcome</h1>
										<p style="color:#ffffff; font-size:18px; text-transform: uppercase; margin: 0px; font-weight: 300;">Discover A New World Of Entertainment</p>
									</td>
                                </tr>
                            </table>

                            <table cellpadding="0" cellspacing="0" border="0" style="background-color: #ffffff; width: 90%; padding: 35px; border: none; margin: 0px auto; text-align: center; box-shadow: 0px 0px 20px 0px rgba(59, 60, 64, 0.08);">

                                <tr>
                                    <td style="height:15px;"></td>
                                </tr>
								*|IF:GREETING|*
                                <tr>
                                    <td style="font-size:22px; color:#fe672d; font-weight: 700;">*|GREETING|*</td>
                                </tr>
								*|END:IF|*
                                <tr>
                                    <td style="height:20px;"></td>
                                </tr>
								*|IF:ACTION|*
                                <tr>
                                    <td style="font-size:18px; color:#6a6a6a; line-height:28px; font-weight: 600;">*|ACTION|*</td>
                                </tr>
								*|END:IF|* 
								 <tr>
                                    <td style="height:40px;"></td>
                                </tr>
								*|IF:BUTTON|*
								<tr>
                                    <td style="text-align:center;">
                                        <a href="*|TLINK|*" style="font-family: brandon-grotesque, sans-serif; font-size:14px; font-weight:500; text-transform: uppercase; background-color:#fd5421; color:#ffffff; padding: 15px 25px; text-decoration:none;" target="_blank">*|BUTTON|*</a>
									</td>
                                </tr>
								
                                <tr>
                                    <td style="height:40px;"></td>
                                </tr>

								<tr>
                                    <td style="font-size:22px; color:#40404c; line-height:28px; font-weight: 700;">OR</td>
                                </tr>
                                <tr>
                                    <td style="height:20px;"></td>
                                </tr>
								
                                <tr>
                                    <td style="font-size:18px; color:#6a6a6a; font-weight: 600;">Copy this link and paste it on your browser</td>
                                </tr>
								
								<tr> 
                                    <td style="height:25px;"></td>
                                </tr>

                                <tr>
                                    <td style="font-family: brandon-grotesque, sans-serif; font-size:18px; background-color:#fafafa; border:1px solid #969696; width:100%; padding:15px 0px; color:#979797; font-weight: 400;">*|TLINK|*</td>
                                </tr>
								*|END:IF|* 
                               
							   *|IF:VIVEKKADATA|*
								<tr>
                                    <td style="height:40px;"></td>
                                </tr>
								<tr>
                                    <td style="font-size:18px;font-weight: 600; "><span style="color: rgb(254, 103, 45);">Login Email</span> :   <span style="color:#6a6a6a;">*|VIVEKKADATA|*</span></td>
									
                                </tr>
								<tr>
                                    <td style="height:5px;"></td>
                                </tr>
								<tr>
                                    
									<td style="font-size:18px; color:#6a6a6a; font-weight: 600;"><span style="color: rgb(254, 103, 45);">Login Password </span> : <span style="color:#6a6a6a;"> *|PASSWORD|*</span></td>
                                </tr>
								
								*|END:IF|* 
								<tr>
                                    <td style="height:40px;"></td>
                                </tr>
								
                                <tr>
                                    <td style="font-size:18px; color:#40404c; font-weight: 600;">Thank you,</td>
                                </tr>
								
								<tr>
                                    <td style="height:5px;"></td>
                                </tr>
								
								<tr>
                                    <td style="font-size:18px; color:#40404c; font-weight: 600;">Your Team Discovered</td>
                                </tr>

                                <tr>
                                    <td style="height:15px;"></td>
                                </tr>
                            </table>

                            <table cellpadding="0" cellspacing="0" border="0" style=" border: none; width: 100%; padding: 0px;">
                                <tr>
                                    <td style="height:25px;"></td>
                                </tr>
                                <tr>
									<td style="font-family: brandon-grotesque, sans-serif; text-align: center; color: #979797; font-weight: 400; margin-top: 0px; display: block; font-size: 18px;">
										Copyright © 2019 Discovered.tv
									</td>
								</tr>
                                <tr>
                                    <td style="height:25px;"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
    <!-- /body -->
</body>

</html>
