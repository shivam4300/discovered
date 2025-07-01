<html>
<head>
    <title>Discovered.TV</title>
    <link rel="preconnect" href="https://fonts.gstatic.com">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;0,900;1,300;1,400;1,500;1,600;1,700;1,800&display=swap" rel="stylesheet">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:ital,wght@0,100;0,200;0,300;0,400;0,500;0,700;0,800;0,900;1,100;1,200;1,300;1,500;1,600;1,700;1,800;1,900&display=swap');
        *{
            font-family: 'Poppins',sans-serif!important;
        }
</style>
</head>

<body style="box-sizing: border-box;">
    <table width="700" align="center" bgcolor="#f9f9f9" cellpadding="0" cellspacing="0" style="font-family: 'Poppins', sans-serif!important;text-align:center;margin:auto!important;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <table width="700" cellpadding="0" cellspacing="0" style="margin:auto!important;">
                        <tbody>
                            <tr>
                                <td align="center">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="background-color:#f9f9f9;">
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background-color:#f9f9f9;">
                                                        <tbody>                                                                                                                       
                                                            <tr>
                                                                <td style="padding: 40px 30px 0;">
                                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="margin: auto;">
                                                                        <tbody>                                                                                                                       
                                                                            <tr>
                                                                                <td align="center" width="100%" style="background-color:#ffffff;border: 1px solid #fef1e6;box-shadow: 0px 0px 29px 0px #eaeaea;">
                                                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" bgcolor="#ffffff">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td>
                                                                                                    <img alt="images" src="https://discovered.tv//repo/images/email_temp/new_signup/bg.png" style="border:0;">
                                                                                                </td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%" style="background: #ffffff!important;" bgcolor="#ffffff">
                                                                                        <tbody>
                                                                                            <?php if ($GREETING) {?>
                                                                                            <tr>
                                                                                                <td style="font-size:16px!important;line-height:26px!important;color: #898c9b!important;padding:20px 30px 0;font-weight: 400;">{GREETING}</td>
                                                                                            </tr>
                                                                                            <?php } ?>                                                                                       
                                                                                            
                                                                                            <?php if ($ACTION) {?>
																							<tr>
                                                                                                <td style="font-size:16px!important;line-height:26px!important;color: #898c9b!important;padding:5px 30px 0;font-weight: 400;">{ACTION}</td>
                                                                                            </tr>
                                                                                            <?php } ?>
																						
                                                                                            <?php if ($BUTTON) {?>
                                                                                            <tr>
                                                                                                <td style="padding:30px 30px 0;">
                                                                                                    <a href="{TLINK}" style="font-size:14px!important;color: #ffffff!important;font-weight:500!important;background: #ff6e42!important;text-decoration: none!important;border-radius: 5px!important;padding: 15px 33px!important;letter-spacing: 1px!important;display: inline-block!important;text-transform: uppercase!important;" target="_blank">{BUTTON}</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="font-size:16px!important;line-height:26px!important;color: #898c9b!important;padding:30px 30px 0;font-weight: 400;">Or copy this link and paste in your web browser</td>
                                                                                            </tr>                                                                                            
                                                                                            <tr>
                                                                                                <td style="padding:10px 30px 0;font-size:16px!important;font-weight:400!important;color: #ff6e42!important;">
                                                                                                    <a href="{TLINK}" style="font-size:16px!important;font-weight:400!important;color: #ff6e42!important;" target="_blank">{TLINK}</a>
                                                                                                </td>
                                                                                            </tr>
                                                                                            <?php } ?> 
																							
                                                                                             <!-- If User login with facebook/google account -->
                                                                                            <!--tr>
                                                                                                <td style="font-size:16px!important;line-height:26px!important;color: #898c9b!important;padding:5px 30px 0;font-weight: 400;">Now you can login to Discovered.tv by using your personal email and password or you can login with your Facebook or Google accounts.</td>
                                                                                            </tr--> 
                                                                                            
                                                                                            <?php if ($VIVEKKADATA) {?>                                                                     
                                                                                                <tr>
                                                                                            <td style="font-size:16px;font-weight: 400; padding:30px 30px 0;"><span style="color: #fe672d;">Login Email</span> :   <a href="mailto:david@demomail.com" style="color:#898c9b!important;font-weight: 500;text-decoration: none!important;">{VIVEKKADATA}</span>
                                                                                                </td>
                                                                                                
                                                                                            </tr>                                                                                            
                                                                                            <tr>
                                                                                                
                                                                                                <td style="font-size:16px;font-weight: 400; padding:10px 30px 0;"><span style="color: #fe672d;">Login Password </span> : <span style="color:#898c9b!important;font-weight: 500;">{PASSWORD}</span></td>
                                                                                            </tr>
																							<?php } ?>
                                                                                              <!-- If User login with facebook/google account -->
                                                                                            <tr>
                                                                                                <td style="font-size:16px!important;line-height:26px!important;color: #40404c!important;padding:30px 30px 0;font-weight: 600;">Cheers,</td>
                                                                                            </tr>
                                                                                            <tr>
                                                                                                <td style="font-size:16px!important;line-height:26px!important;color: #40404c!important;padding:0px 30px 30px;font-weight: 600;">Team Discovered!</td>
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                           
                                                                            <tr>
                                                                                <td align="center" style="padding:30px 0px 10px">
                                                                                    <table border="0" cellpadding="0" cellspacing="0">
                                                                                        <tbody>
                                                                                            <tr>
                                                                                                <td align="center" style="cursor:pointer;padding: 5px 10px;">
                                                                                                    <a href="https://play.google.com/store/apps/details?id=com.discoveredtv" target="_blank">
                                                                                                        <img alt="images" src="https://discovered.tv//repo/images/email_temp/club/googleplay.png" style="border:0;">
                                                                                                    </a>
                                                                                                </td>
                                                                                                <td align="center" style="cursor:pointer;padding: 5px 10px;">
                                                                                                    <a href="https://apps.apple.com/in/app/discovered/id1560271435" target="_blank">
                                                                                                        <img alt="images" src="https://discovered.tv//repo/images/email_temp/club/appstore.png" style="border:0;">
                                                                                                    </a>
                                                                                                </td>                                                                                                
                                                                                            </tr>
                                                                                        </tbody>
                                                                                    </table>
                                                                                </td>
                                                                            </tr>
                                                                            <tr>
                                                                                <td align="center" style="font-size:15px!important;line-height:26px!important;color: #898c9b!important;padding:0px 5px 10px;font-weight: 400;">Questions? Email us at <a href="mailto:help@discovered.tv" style="color: #ff6e42!important;font-weight: 500;text-decoration: none!important;">help@discovered.tv</a></td>
                                                                            </tr> 
																			<tr>
                                                                                <td align="center" style="font-size:15px!important;line-height:26px!important;color: #898c9b!important;padding:0px 5px 40px;font-weight: 400;">Copyright © {YEAR} Discovered.tv</td>
                                                                            </tr> 

                                                                        </tbody>
                                                                    </table>
                                                                </td>
                                                            </tr>
                                                        </tbody>
                                                    </table>                                                    
                                                </td>
                                            </tr>
                                        </tbody>
                                    </table>                                    
                                </td>
                            </tr>
                        </tbody>
                    </table>   
                    
                </td>
            </tr>
        </tbody>
    </table>
</body>
</html>