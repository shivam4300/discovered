<?php
defined('BASEPATH') or exit('Access Denied');

class Valuelist 
{
    protected $CI;
   
	public function __construct()
    {
        $this->CI = &get_instance();
    } 

	function platform(){
		$platform = array(
			'All' => 'All Platform',
			'Website' => 'Website',
			'Android' => 'Android',
			'IOS' => 'IOS',
			'Tizen' => 'Tizen',
			'Roku' => 'Roku',
			'AndroidTv' => 'AndroidTv',
		);
		return $platform;
	}
	function mode(){
		$modes = array(
			1 => 'music',
			2 => 'movies',
			3 => 'television',
			4 => 'social',
			5 => 'casting_call',
			6 => 'store',
			7 => 'gaming',
			8 => 'spotlight',
			9 => 'live',
			10 => 'articles',
		);
		return $modes;
	}

	function level(){
		$level = array(
			1 => 'Icon',
			2 => 'Emerging',
			3 => 'Brand',
			4 => 'Fan',
			130 => 'Official',
		);
		return $level;
	}

	function website_mode($key){
		$modes = $this->mode();
		if(is_numeric($key)){
			return isset($modes[$key]) ? $modes[$key] : 'home';
		}else{
			return array_search($key,$modes);
		}
	}
	
	function PayPalBatchStatus($status){
		// batch_statusenum
		$arr = array(
			'DENIED'	=>	
			'Your payout requests were denied, so they were not processed. Check the error messages to see any steps necessary to fix these issues.',
			
			'PENDING'	=>	
			'Your payout requests were received and will be processed soon.',
			
			'PROCESSING'=>	
			'Your payout requests were denied, so they were not processed. Check the error messages to see any steps necessary to fix these issues.',
			
			'SUCCESS'	=>	
			'Your payout batch was processed and completed. Check the status of each item for any holds or unclaimed transactions.',
			
			'CANCELED'	=>	
			'The payouts file that was uploaded through the PayPal portal was cancelled by the sender.',
		);
		return isset($arr[$status])?$arr[$status]:'';
	}
	function PayPalTransatctionStatus($status){
		// batch_statusenum
		$arr = array(
			'SUCCESS'	=>	
			'Funds have been credited to the recipient’s account.',
			
			'FAILED'	=>	
			'This payout request has failed, so funds were not deducted from the sender’s account.',
			
			'PENDING'=>	
			'Your payout request was received and will be processed.',
			
			'ONHOLD'	=>	
			'This payout request is being reviewed and is on hold.',
			
			'UNCLAIMED'	=>	
			' The recipient for this payout does not have a PayPal account. A link to sign up for a PayPal account was sent to the recipient. However, if the recipient does not claim this payout within 30 days, the funds are returned to your account.',
			
			'RETURNED'	=>	
			'The recipient has not claimed this payout, so the funds have been returned to your account.',
			
			'BLOCKED'	=>	
			'This payout request has been blocked.',
			
			'REFUNDED'	=>	
			'This payout request was refunded.',
			
			'REVERSED'	=>	
			'This payout request was reversed.',
		);
		return isset($arr[$status])?$arr[$status]:'';
	}
	function PayPalTransactionErrorMessages($status){
		$arr = array(
			'ACCOUNT_RESTRICTED'=>	
			'Access to your account has been restricted. Contact your account manager or our customer service team for assistance.',
			
			'ACCOUNT_UNCONFIRMED_EMAIL'	=>	
			'You need to be a verified PayPal account holder to send payouts. You can verify your account by confirming your email and your bank account or credit card. Contact your account manager or our customer service team for assistance.',
			
			'AUTHORIZATION_ERROR'	=>	
			'Your account is not authorized to use payouts. Contact your account manager or our customer service team for assistance.',
			
			'BATCH_NOT_COMPLETED' =>	
			'This payout batch is still being processed. Please try again later.',
			
			'CLOSED_MARKET'	=>	
			'This account is not allowed to receive payouts from other countries. Try re-sending this payout to another account.',
			
			'CURRENCY_COMPLIANCE'=>	
			'This currency cannot be accepted for this recipient’s account. You can re-send this payout with a different currency.',
			
			'DUPLICATE_ITEM' =>	
			'This transaction is duplicated in this batch. Please check the Ref_ID / Sender_Item_ID.',
			
			'CLOSED_MARKET'	=>	
			'This account is not allowed to receive payouts from other countries. Try re-sending this payout to another account.',
			
			'GAMER_FAILED_FUNDING_SOURCE_CHECK' =>
			'The funding source that was selected for this payout is not allowed. Try again by using your PayPal balance instead.',

			'GAMING_INVALID_PAYMENT_FLOW' =>
			'This payment flow is not allowed for gaming merchant accounts.',

			'INSUFFICIENT_FUNDS'=>
			'You have insufficient funds in your PayPal balance. You\'ll need to add funds to your account to complete the payout.',

			'INTERNAL_ERROR'=>
			'An error occurred while processing this payout request. Please re-submit this payout as a new batch or file.',

			'ITEM_ALREADY_CANCELLED'=>
			'This payout request has already been cancelled.',

			'ITEM_CANCELLATION_FAILED'=>
			'An error occurred while processing this payout request. Try again in a few minutes.',

			'ITEM_INCORRECT_STATUS'=>
			'You can only cancel items that are unclaimed.',

			'VALIDATION_ERROR'=>
			'JSON request is malformed. Check your request format and try again.',

			'NEGATIVE_BALANCE'=>
			'You have insufficient funds in your PayPal balance. You\'ll need to add funds to your account to complete the payout.',

			'NON_HOLDING_CURRENCY'=>
			'Your account does not have a PayPal balance in this currency. Try again with a currency that has funds in your PayPal account, or change your account settings to this currency.',

			'PENDING_RECIPIENT_NON_HOLDING_CURRENCY_PAYMENT_PREFERENCE'=>
			'This payout is pending because the recipient has set their account preferences to review credits in this currency. The recipient has been notified. Check back later for the status of this payout.',

			'POS_LIMIT_EXCEEDED'=>
			'You have exceeded the POS cumulative spending limit. Contact your account manager or our customer service team for assistance.',

			'RATE_LIMIT_VALIDATION_FAILURES'=>
			'Your request has been blocked due to multiple failed attempts. Please try again later.',

			'RECEIVER_ACCOUNT_LOCKED'=>
			'We were not able to send a payout because the recipient’s account is inactive or restricted. Funds have been returned to your account.',

			'RECEIVER_COUNTRY_NOT_ALLOWED'=>
			'We can’t send this payout because the recipient lives in a country where payouts are not allowed.',

			'RECEIVER_STATE_RESTRICTED'=>
			'We cant send this payout because the recipient lives in a state where payouts are not allowed.',

			'RECEIVER_UNCONFIRMED'=>
			'The recipient’s email or phone number is unconfirmed. Any payments made to this account will be marked as Unclaimed until the recipient confirms their account information. Funds will be returned to your account if they are not claimed within 30 days.',

			'RECEIVER_UNREGISTERED'=>
			'The recipient for this payout does not have an account. A link to sign up for an account was sent to the recipient. However, if the recipient does not claim this payout within 30 days, the funds will be returned to your account.',

			'RECEIVER_ACCOUNT_LIMITATION'=>
			'The recipient\'s account is currently under limitation. Any payments made to this account will be on hold until the issue is resolved. Funds will be returned to your account if the limitation is not resolved within 30 days of the sent payout.',

			'RECEIVER_YOUTH_ACCOUNT'=>
			'We were not able to send a payout because the recipient has a youth account. Please check with the recipient for an alternate account to receive the payout.',

			'RECEIVING_LIMIT_EXCEEDED'=>
			'The recipient cannot accept this payout, because it exceeds the amount they can receive at this time. Please resubmit your payout request for a different amount.',

			'REFUSED_ACCESS_DENIED'=>
			'Your account is not allowed to send money. Check with your primary account holder to get permission to send money.',

			'RECEIVER_REFUSED'=>
			'The recipient has refused this payout in this currency. Try resending in a different currency.',

			'REGULATORY_BLOCKED'=>
			'This transaction is blocked due to regulatory compliance restrictions.',

			'REGULATORY_PENDING'=>
			'This transaction is pending, while it is reviewed for compliance with government regulations.',

			'REQUIRED_SCOPE_MISSING'=>
			'The access token doesn\'t have the required scope. You\'ll need to use the access token with the correct scope to send a payout.',

			'RISK_DECLINE'=>
			'This transaction was declined due to risk concerns.',

			'SELF_PAY_NOT_ALLOWED'=>
			'You can’t send a payout to yourself. Try sending it to a different account.',

			'SENDER_ACCOUNT_LOCKED'=>
			'You can’t send a payout now, because your account is locked or inactive. Contact your account manager or our customer service team for assistance.',

			'SENDER_ACCOUNT_UNVERIFIED'=>
			'To send a payout, you need to have a verified PayPal account. You can verify your account by confirming your bank account or credit card. Contact your account manager or our customer service team for assistance.',

			'SENDER_STATE_RESTRICTED'=>
			'Your address is in a state where payouts are not allowed. Contact your account manager or our customer service team for assistance.',

			'SPENDING_LIMIT_EXCEEDED'=>
			'You’ve exceeded your spending limit. Contact your account manager or our customer service team for assistance.',

			'TRANSACTION_DECLINED_BY_TRAVEL_RULE'=>
			'Your payout request does not comply with travel rule regulations. To send this payout, you’ll need to update and verify your account information. Contact your account manager or our customer service team for assistance.',

			'TRANSACTION_LIMIT_EXCEEDED'=>
			'This payout request has exceeded the limit for this type of transaction. The funds have been returned to your account.',

			'UNDEFINED'=>
			'An error occurred while processing this payout request. Try again in a few minutes, or try resending as part of a new request or file.',

			'UNVERIFIED_RECIPIENT_NOT_SUPPORTED'=>
			'This payout request was not completed because the recipient has not verified their account. Your account is only allowed to send payout to verified accounts.',

			'USER_BUSINESS_ERROR'=>
			'An error occurred while processing this payout request. For batch processing, try again with a different sender_batch_ID. For single payout items, try again with email or payer ID as recipient type.',

			'USER_COUNTRY_NOT_ALLOWED'=>
			'Your address is in a country where payouts are not allowed. Contact your account manager or our customer service team for assistance.',

			'USER_FUNDING_SOURCE_INELIGIBLE'=>
			'The funding source for this payout is not allowed. Try again by using your PayPal balance instead.',

			'ZERO_AMOUNT'=>
			'Please provide a valid payment amount.',

			'APPROVER_DENIED'=>
			'Payout request rejected by the approver. Please check with your approver.',

			'INVALID_EMAIL'=>
			'Email Address doesn’t exist. Try again with the correct Email Id.',
		);
		return isset($arr[$status])?$arr[$status]:'';
	}
	public function achReturnTxnStatus($status){
		$arr = [
			'R01' =>
			'Insufficient funds',
			'R02' =>
			'Bank account closed',
			'R03' =>
			'No bank account/unable to locate account',
			'R04' =>
			'Invalid bank account number',
			'R06' =>
			'Returned per ODFI request',
			'R07' =>
			'Authorization revoked by customer',
			'R08' =>
			'Payment stopped',
			'R09' =>
			'Uncollected funds',
			'R10' =>
			'Customer advises not authorized',
			'R11' =>
			'Check truncation entry return',
			'R12' =>
			'Branch sold to another RDFI',
			'R13' =>
			'RDFI not qualified to participate',
			'R14' =>
			'Representative payee deceased or unable to continue in that capacity',
			'R15' =>
			'Beneficiary or bank account holder',
			'R16' =>
			'Bank account frozen',
			'R17' =>
			'File record edit criteria',
			'R18' =>
			'Improper effective entry date',
			'R19' =>
			'Amount field error',
			'R20' =>
			'Non-payment bank account',
			'R21' =>
			'Invalid company ID number',
			'R22' =>
			'Invalid individual ID number',
			'R23' =>
			'Credit entry refused by receiver',
			'R24' =>
			'Duplicate entry',
			'R25' =>
			'Addenda error',
			'R26' =>
			'Mandatory field error',
			'R27' =>
			'Trace number error',
			'R28' =>
			'Transit routing number check digit error',
			'R29' =>
			'Corporate customer advises not authorized',
			'R30' =>
			'RDFI not participant in check truncation program',
			'R31' =>
			'Permissible return entry (CCD and CTX only)',
			'R32' =>
			'RDFI non-settlement',
			'R33' =>
			'Return of XCK entry',
			'R34' =>
			'Limited participation RDFI',
			'R35' =>
			'Return of improper debit entry',
			'C01' =>
			'Incorrect bank account number',
			'C02' =>
			'Incorrect transit/routing number',
			'C03' =>
			'Incorrect transit/routing number and bank account number',
			'C04' =>
			'Bank account name change',
			'C05' =>
			'Incorrect payment code',
			'C06' =>
			'Incorrect bank account number and transit code',
			'C07' =>
			'Incorrect transit/routing number, bank account number and payment code',
			'C09' =>
			'Incorrect individual ID number',
			'C10' =>
			'Incorrect company name',
			'C11' =>
			'Incorrect company identification',
			'C12' =>
			'Incorrect company name and company ID',
		];
		return isset($arr[$status])?$arr[$status]:'';
	}
	
	public function achReturnCodeMessage($status){
		$arr = [
			'R01' =>
			'Available balance is not sufficient to cover the amount of the debit entry',
			'R02' =>
			'Previously active amount has been closed by the customer of RDFI',
			'R03' =>
			'Account number does not correspond to the individual identified in the entry, or the account number designated is not an open account',
			'R04' =>
			'Account number structure is not valid',
			'R06' =>
			'ODFI requested the RDFI to return the entry',
			'R07' =>
			'Receiver has revoked authorization',
			'R08' =>
			'Receiver of a recurring debit has stopped payment of an entry',
			'R09' =>
			'Collected funds are not sufficient for payment of the debit entry',
			'R10' =>
			'Receiver has advised RDFI that originator is not authorized to debit his bank account',
			'R11' =>
			'To be used when returning a check truncation entry',
			'R12' =>
			'RDFI unable to post entry destined for a bank account maintained at a branch sold to another financial institution',
			'R13' =>
			'Financial institution does not receive commercial ACH entries',
			'R14' =>
			'The representative payee authorized to accept entries on behalf of a beneficiary is either deceased or unable to continue in that capacity',
			'R15' =>
			'(Other than representative payee) deceased* - (1) the beneficiary entitled to payments is deceased or (2) the bank account holder other than a representative payee is deceased',
			'R16' =>
			'Funds in bank account are unavailable due to action by RDFI or legal order',
			'R17' =>
			'Fields rejected by RDFI processing (identified in return addenda)',
			'R18' =>
			'Entries have been presented prior to the first available processing window for the effective date.',
			'R19' =>
			'Improper formatting of the amount field',
			'R20' =>
			'Entry destined for non-payment bank account defined by reg.',
			'R21' =>
			'The company ID information not valid (normally CIE entries)',
			'R22' =>
			'Individual id used by receiver is incorrect (CIE entries)',
			'R23' =>
			'Receiver returned entry because minimum or exact amount not remitted, bank account is subject to litigation, or payment represents an overpayment, originator is not known to receiver or receiver has not authorized this credit entry to this bank account',
			'R24' =>
			'RDFI has received a duplicate entry',
			'R25' =>
			'Improper formatting of the addenda record information',
			'R26' =>
			'Improper information in one of the mandatory fields',
			'R27' =>
			'Original entry trace number is not valid for return entry; or addenda trace numbers do not correspond with entry detail record',
			'R28' =>
			'Check digit for the transit routing number is incorrect',
			'R29' =>
			'RDFI has bee notified by corporate receiver that debit entry of originator is not authorized',
			'R30' =>
			'Financial institution not participating in automated check safekeeping application',
			'R31' =>
			'RDFI has been notified by the ODFI that it agrees to accept a CCD or CTX return entry',
			'R32' =>
			'RDFI is not able to settle the entry',
			'R33' =>
			'RDFI determines at its sole discretion to return an XCK entry; an XCK return entry may be initiated by midnight of the sixtieth day following the settlement date if the XCK entry',
			'R34' =>
			'RDFI participation has been limited by a federal or state supervisor',
			'R35' =>
			'ACH debit not permitted for use with the CIE standard entry class code (except for reversals)',
			'C01' =>
			'Bank account number incorrect or formatted incorrectly',
			'C02' =>
			'Once valid transit/routing number must be changed',
			'C03' =>
			'Once valid transit/routing number must be changed and causes a change to bank account number structure',
			'C04' =>
			'Customer has changed name or ODFI submitted name incorrectly',
			'C05' =>
			'Entry posted to demand account should contain savings payment codes or vice versa',
			'C06' =>
			'Bank account number must be changed and payment code should indicate posting to another account type (demand/savings)',
			'C07' =>
			'Changes required in three fields indicated',
			'C09' =>
			'Individual\'s ID number is incorrect',
			'C10' =>
			'Company name is no longer valid and should be changed.',
			'C11' =>
			'Company ID is no longer valid and should be changed',
			'C12' =>
			'Both the company name and company id',
		];
		return isset($arr[$status])?$arr[$status]:'';
	}

	function createS3SubKey($key, $postfix ='', $ext = '.noext',$expl = 'videos/'){
		$key 	= explode('.',$key);
		$f 		= explode($expl,$key[0]);
		$postfix = !empty($postfix)? '_'.$postfix:'';
		
		$ky = isset($key[0]) ? $key[0] : '';
		$ext = isset($key[1]) ? $key[1] : '';
		$fil = isset($f[1]) ? $f[1] : '';
		return [
			'key' => $ky,
			'childKey' => $ky.'/'.$fil.$postfix.$ext,
			'file' => $fil,
			'ext' => $ext,
			'inputFileWithExt' => $fil.'.'.$ext,
			'OutpuFileWithExt' => $fil.$ext,
		];
	}
	
	function SingupSourelist(){ 
		return array(
			'engine' 	=> 'Search Engine (Google Yahoo etc.)',
			'creator' 	=>'Recommended by a friend or Creator',
			'social' 	=>'Social Media (Tik Tok, Instagram, Facebook etc.)',
			'apps' 		=>'Samsung, Android TV or Roku',
			'blog' 		=>'Blog or Publication',
			'other' 	=>'Other'
		);
	}
    
}
