<html>
<head>
    <title>Email Template</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,300;1,400;1,500;1,600;1,700;1,800&display=swap');
        p, h1, h2, h3, h4, ol, li, ul, td, tr { font-family:'Open Sans', sans-serif; }
    </style>
</head>
<body style="box-sizing: border-box;">
    <AW:BODY> 
    <table width="720" bgcolor="#ffffff" cellpadding="0"cellspacing="0" style="font-family: 'Open Sans', sans-serif;text-align:center;width:720px!important;border: 1px solid rgb(227, 227, 227);border-radius: 5px;">
        <tbody>
            <tr>
                <td align="center" valign="top">
                    <table width="720" cellpadding="0" cellspacing="0">
                        <tbody>
                            <tr>
                                <td align="center" valign="top" style="padding: 20px 20px;border-bottom: 1px solid #e3e3e3;background:#fafafa;border-radius: 5px 5px 0px 0px;"> 
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td>
                                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                                        <tbody>
                                                            <?php 
                                                            $createdBy = 'Requested By';
                                                            if(!empty($ticket_id)){ 
                                                                $createdBy = 'Created By';
                                                            ?>
                                                            <tr>
                                                                <td style="font-size: 18px;color: #40404c;font-weight: 600;padding-bottom: 5px;">Ticket ID #<?=$ticket_id?></td>
                                                            </tr>	
                                                            <?php } ?>	
                                                            <tr>
                                                                <td style="font-size: 14px;color: #aeaeae;font-weight: 400;">Created Date - <?=date("j F Y");?></td>
                                                            </tr>		
                                                        </tbody>
                                                    </table>
                                                </td>
                                                <td align="right" style="background:#f3652e!important;color: #ffffff !important; padding:8px 15px !important;text-decoration: none !important;font-weight:600 !important;font-size:16px !important;display: inline-block;border-radius: 5px;float:right;"><?=$department_name?></td>
                                            </tr>	
                                        </tbody>
                                    </table> 
                                </td> 
                            </tr>
                            <tr>
                                <td align="center" valign="top" style="padding: 20px 20px;">
                                    <table border="0" cellpadding="0" cellspacing="0" width="100%">
                                        <tbody>
                                            <tr>
                                                <td style="font-size: 15px;font-weight:700;color: #7a7a7a;padding-bottom: 10px;"><?=$createdBy;?> - <?=$user_name?></td>
                                            </tr>
                                            <tr>
                                                <td style="font-size: 18px;font-weight:700;color: #40404c;margin-bottom: 15px;"> <?=$subject?></td>
                                            </tr>		
                                            <tr>
                                                <td style="font-size: 14px;color: #979797;padding: 15px 0px 15px;"> <?=$message?></td>
                                            </tr>		
                                            <?php if(!empty($ins)){ ?>
                                            <tr>
                                                <td style="padding:0px 0 20px;">
                                                    <a href="<?=base_url('support/ticketDetails/'.$ins)?>" target="_blank" style="color: #f3652e!important;text-decoration: none !important;font-weight:500 !important;font-size:16px !important;display: inline-block;">View Full Thread <img alt="images" src="https://discovered.tv//repo/images/email_temp/right_arrow.png" style="border:0;"></a>
                                                </td>
                                            </tr>	
                                            <?php } ?>	
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