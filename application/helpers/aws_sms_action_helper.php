<?php
	require 'aws/vendor/autoload.php';

	use Aws\Sns\SnsClient; 
    use Aws\Exception\AwsException;
    use Aws\Exception\CredentialsException;
	
	global $bucket_name;

	$bucket_name = MAIN_BUCKET;


    function send_sms($data){
        
        $SnSclient = new SnsClient([
            'credentials' => array(
				'key'         => 'AKIAQL4DOKA3FSXXUFHU',
				'secret'      => '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',
			),
            'region' => 'ap-south-1',
            'version' => '2010-03-31',
        ]);
        
        $message = 'This message is sent from a Amazon SNS code sample.';
        $phone = '+919827868313';
        
        try {
            $result = $SnSclient->publish([
                'Message' => $data['message'],
                'PhoneNumber' => $data['phone'],
            ]);
          
            return $result;
        } catch (AwsException $e) {
            // output error message if fails
            print_r($e->getMessage());
        }catch (CredentialsException $e) {
            // output error message if fails
            print_r($e->getMessage());
        }
    }

	
