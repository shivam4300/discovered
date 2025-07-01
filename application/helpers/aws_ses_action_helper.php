<?php 

	require 'aws/vendor/autoload.php';
	// require_once(APPPATH.'third_party/phpmailer/autoload.php');
	use Aws\Ses\SesClient; 
	use Aws\Exception\AwsException;
	
	// use PHPMailer\PHPMailer\PHPMailer;
	// use PHPMailer\PHPMailer\Exception;

	global $globl_cred;
	global $config;

	$config = array(
		'smtp_host'     => 'email-smtp.us-east-1.amazonaws.com', 
		//'smtp_user'     => 'AKIAS5UFB7B7DAZDVJHX',  // old SES key 
		//'smtp_pass'     => 'BP+YlbMM1cVZUIKDXHn//JWPUU05w7eWhKWheUtKaGpx',  // old SES key
		'smtp_user'     => 'AKIAS5UFB7B7L4F3LFBR', // New SES key 
		'smtp_pass'     => 'BLoZa6hAzzMMTrrYTMxmGuaZECTOfrFPsuogz60F+BSH',  // New SES key 
	);
 
	$globl_cred = array(
		'version'     => 'latest',
		'region'      => BUCKET_REGION,
		'credentials' => array(
			'key'         => BUCKET_KEY,
			'secret'      => BUCKET_SECRET,
		)
	);

	function send_smtp($options){
		extract($options);
		$CI = & get_instance();
		$CI->load->library('parser');
		
		$temp_param = [
			'GREETING'=>$greeting,
			'ACTION'=>$action,
			'VIVEKKADATA' => $email,
			'PASSWORD' => $password,
			'BUTTON' => $button,
			'TLINK' => $link,	
			'YEAR' => date('Y'),	
		];

		$template = $CI->parser->parse('common/mail_template/mail_notificaiton_template',$temp_param,true);
		// $this->sent_by_ses($options);
		return send_by_mandrill($options,$template);
	}
	function sent_by_ses($options,$template){
		global $config;
		extract($options);
		$CI->load->library('email');
		
		$config['protocol'] 	= 	'smtp';
		$config['smtp_port'] 	= 	 587;
		$config['smtp_crypto'] 	= 	'tls';
		$config['mailtype'] 	= 	'html';
		$config['charset'] 		= 	'UTF-8';
		
		$CI->email->initialize($config);
		$CI->email->set_newline("\r\n");
		$CI->email->from('contact@discovered.tv');
        $CI->email->to($receiver_email);
        $CI->email->subject($subject);
        $CI->email->message($template);
    	
        if(!$CI->email->send()){
			echo $CI->email->print_debugger();
		}else{
			log_message('error', 'Email Subject:- '.$subject);
		}
	}
	function send_by_mandrill($options,$template){
		extract($options);
		
		$to=[];
		if(is_array($receiver_email)){
			foreach($receiver_email as $email){
				$to[] = array(
					'email' => $email, 
					'type' => 'to'
				);
			}
		}else{
			$to = [["name" => '', "email" => $receiver_email , "type" => "to"]];
		}
		
		$ch = curl_init();
		curl_setopt($ch,CURLOPT_URL, 'https://mandrillapp.com/api/1.0/messages/send.json');
		$params = array(
			"key" => MANDRILL_KEY,
			"message" => array(
				"html" => $template,
				"text" => $template,
				"to" => $to,
				"from_email" => 'contact@discovered.tv',
				"from_name" => 'Team Discovered',
				"subject" => $subject,
			),
			"async" => false,
			"ip_pool" => "Main Pool"
		);
		curl_setopt($ch,CURLOPT_POSTFIELDS, json_encode( $params) );
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		$result = curl_exec($ch);
		$result = json_decode($result,true);
		
		if(isset($result[0]['status'])){
			if($result[0]['status'] == 'sent'){
				return 1;
			}else{
				return 0;
			}
		}else{
			return 0;
		}
	}

	function send_smtp_support_mail($options){
		global $config;

		extract($options);
		$CI = & get_instance();
		$CI->load->library('parser');
		
		if(isset($template)){
			$template = $template;
		}else{
			$temp_param = [
				'ticket_id'		=>$ticket_id,
				'subject'		=>$subject,
				'message'		=>$message,
				'department_name'=>$department_name,
				'ins'			=>$ins,
				'user_name'		=>$user_name,
			];
			$template = $CI->load->view('support/ticket_email',$temp_param,true);
		}

		// $this->sent_by_ses($options);
		$options['subject'] = $mail_subject;
		$options['receiver_email'] = explode(',',$options['receiver_email']);
		$r =  send_by_mandrill($options,$template);
	}
	
	
	function sendmail(){
		global $globl_cred;

			try {
				$args = array(
					'version'     => 'latest',
					'region'      => BUCKET_REGION,
					'credentials' => array(
						'key'         => 'AKIAJF2HAKEJHPZURUJA',
						'secret'      => '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',
					)
				);

				$SesClient = new Aws\Ses\SesClient($args);
			} catch (Aws\Exception\UnresolvedApiException $e) {
				return array( 'error' => $e->getMessage() );
			}
			
			$html_body = '<h1>AWS Amazon Simple Email Service Test Email</h1>' .
				'<p>This email was sent with <a href="https://aws.amazon.com/ses/">' .
				'Amazon SES</a> using the <a href="https://aws.amazon.com/sdk-for-php/">' .
				'AWS SDK for PHP</a>.</p>';
			$subject = 'Amazon SES test (AWS SDK for PHP)';
			$plaintext_body = 'This email was send with Amazon SES using the AWS SDK for PHP.';
			$sender_email = 'email_address';
			$recipient_emails = ['email_address'];
			$char_set = 'UTF-8';
			$configuration_set = 'ConfigSet';

			try {
				$result = $SesClient->sendEmail([
					'Destination' => [
						'ToAddresses' => ['ajaydeep.parmar@pixelnx.com'],
					],
					'ReplyToAddresses' => ['vilash.chouhan@pixelnx.com'],
					'Source' => 'contact@discovered.tv',
					'Message' => [

						'Body' => [
							'Html' => [
								'Charset' => $char_set,
								'Data' => $html_body,
							],
							'Text' => [
								'Charset' => $char_set,
								'Data' => $plaintext_body,
							],
						],
						'Subject' => [
							'Charset' => $char_set,
							'Data' => $subject,
						],
					],
					// If you aren't using a configuration set, comment or delete the
					// following line
					// 'ConfigurationSetName' => $configuration_set,
				]);
				var_dump($result);
			} catch (AwsException $e) {
				// output error message if fails
				echo $e->getMessage();
				echo "\n";
			}
	}

	function phpmailer(){
		
		// Replace sender@example.com with your "From" address.
		// This address must be verified with Amazon SES.
		$sender 	= 'support@teasrr.com';
		$senderName = 'discovered';

		// Replace recipient@example.com with a "To" address. If your account
		// is still in the sandbox, this address must be verified.
		$recipient = 'vilash.chouhan@pixelnx.com';

		// Replace smtp_username with your Amazon SES SMTP user name.
		$usernameSmtp = 'AKIAR4GJKAKM2L4YBSG6';

		// Replace smtp_password with your Amazon SES SMTP password.
		$passwordSmtp = 'BFqTPYh/JkPGqs+dOUAh0dLADJZ371J7yM+hJAdBy5Lk';

		// Specify a configuration set. If you do not want to use a configuration
		// set, comment or remove the next line.
		// $configurationSet = 'ConfigSet';

		// If you're using Amazon SES in a region other than US West (Oregon),
		// replace email-smtp.us-west-2.amazonaws.com with the Amazon SES SMTP
		// endpoint in the appropriate region.
		$host = 'email-smtp.us-east-1.amazonaws.com';
		$port = 587;

		// The subject line of the email
		$subject = 'Amazon SES test (SMTP interface accessed using PHP)';

		// The plain-text body of the email
		$bodyText =  "Email Test\r\nThis email was sent through the
			Amazon SES SMTP interface using the PHPMailer class.";

		// The HTML-formatted body of the email
		$bodyHtml = '<h1>Email Test</h1>
			<p>This email was sent through the
			<a href="https://aws.amazon.com/ses">Amazon SES</a> SMTP
			interface using the <a href="https://github.com/PHPMailer/PHPMailer">
			PHPMailer</a> class.</p>';

		$mail = new PHPMailer(true);

		try {
			// Specify the SMTP settings.
			$mail->isSMTP();
			$mail->setFrom($sender, $senderName);
			$mail->Username   = $usernameSmtp;
			$mail->Password   = $passwordSmtp;
			$mail->Host       = $host;
			$mail->Port       = $port;
			$mail->SMTPAuth   = true;
			$mail->SMTPSecure = 'tls';
			// $mail->addCustomHeader('X-SES-CONFIGURATION-SET', $configurationSet);

			// Specify the message recipients.
			$mail->addAddress($recipient);
			// You can also add CC, BCC, and additional To recipients here.

			// Specify the content of the message.
			$mail->isHTML(true);
			$mail->Subject    = $subject;
			$mail->Body       = $bodyHtml;
			$mail->AltBody    = $bodyText;
			$mail->Send();
			echo "Email sent!" , PHP_EOL;
		} catch (phpmailerException $e) {
			echo "An error occurred. {$e->errorMessage()}", PHP_EOL; //Catch errors from PHPMailer.
		} catch (Exception $e) {
			echo "Email not sent. {$mail->ErrorInfo}", PHP_EOL; //Catch errors from Amazon SES.
		}
	}
	
	

	
	