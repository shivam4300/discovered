<?php
defined('BASEPATH') OR exit('No direct script access allowed');

header('Access-Control-Allow-Origin: *');

class Crawling extends CI_Controller {

    function __construct() {
		parent::__construct();
    }

    // public function index(){
		// echo 'We are here..';
    // }
	function file_get_contents_curl($url){
	$ch = curl_init();

	curl_setopt($ch, CURLOPT_HEADER, 0);
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	curl_setopt($ch, CURLOPT_URL, $url);
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

	$data = curl_exec($ch);
	curl_close($ch);

	return $data;
	}
	
    public function index(){
        include APPPATH . 'libraries/simple_html_dom.php';

        $url 	= "https://support.clickbank.com/hc/en-us/articles/220376547-Marketplace-Feed";

        $data 	= $this->file_get_contents_curl($url);
        $dom 	= new simple_html_dom();
        $dom->load($data, true, true);
		$root = '/home/discovered-efs/test.discovered.tv/public_html/uploads/vivek/';
        if(!empty($dom)) {
            $aZipFile = $dom->find(".ytd-thumbnail .yt-img-shadow .ytd-rich-grid-media .yt-formatted-string [href]")[0];
            $aZipFile = $dom->find(".article .article-info .article-content .article-body [href]")[0];
            preg_match_all('~<a(.*?)href="([^"]+)"(.*?)>~', $aZipFile, $zipFile);
            $downloadZip = $root . "clickbank.zip";
            if(copy($zipFile[2][0], $downloadZip)){
                $zip = new ZipArchive;
                $res = $zip->open($downloadZip);
                if ($res === TRUE) {
                    // $extractpath = $root . "vivek/";
                    $zip->extractTo($root); 
                    $zip->close();

                    $xmlFile = $root . "marketplace_feed_v2.xml";

                    $xml = simplexml_load_file($xmlFile);

                    $category = $this->simpleXmlToArray($xml);

                    $category = $category['Category'];
					echo '<pre>';
					print_r( $category);
                } else {
                    echo 'Zip Extract Error';
                }
            }
        }
    }

    function simpleXMLToArray($xml, $flattenValues=true, $flattenAttributes = true, $flattenChildren=true, $valueKey='@value', 	$attributesKey='@attributes', $childrenKey='@children') {
		$return = array();
		if (!($xml instanceof SimpleXMLElement)) {
			return $return;
		}
		$name = $xml->getName();
		$_value = trim((string) $xml);

		if (strlen($_value) == 0) {
			$_value = null;
		}

		if ($_value != null) {
			if (!$flattenValues) {
				$return[$valueKey] = $_value;
			} else {
				$return = $_value;
			}
		}

		$children = array();
		$first = true;
		foreach ($xml->children() as $elementName => $child) {
			$value = $this->simpleXMLToArray($child, $flattenValues, $flattenAttributes, $flattenChildren, $valueKey, $attributesKey, $childrenKey);
			if (isset($children[$elementName])) {
				if ($first) {
					$temp = $children[$elementName];
					unset($children[$elementName]);
					$children[$elementName][] = $temp;
					$first = false;
				}
				$children[$elementName][] = $value;
			} else {
				$children[$elementName] = $value;
			}
		}
		if (count($children) > 0) {
			if (!$flattenChildren) {
				$return[$childrenKey] = $children;
			} else {
				$return = array_merge($return, $children);
			}
		}

		$attributes = array();
		foreach ($xml->attributes() as $name => $value) {
			$attributes[$name] = trim($value);
		}
		if (count($attributes) > 0) {
			if (!$flattenAttributes) {
				$return[$attributesKey] = $attributes;
			} else {
				$return = array_merge($return, $attributes);
			}
		}

		return $return;
	}

    public function digiStoreProduct(){
        include APPPATH . 'third_party/simple_html_dom.php';

        $url = "https://www.digistore24.com/en/home/marketplace/";

        $data = file_get_contents_curl($url);

        $dom = new simple_html_dom();
        $dom->load($data, true, true);
        
        if(!empty($dom)) {
            foreach ($dom->find("#leftMenu #accordion .panel.no-radius") as $divClass) {
                $parentCategory = $divClass->find('.panel-heading .panel-title')[0];
                $where = array( 'n_id' => 3, 'name' => $parentCategory->innertext );
                $result = $this->Common_DML->get_data( TBL_NETWORK_CATEGORY, $where, '*' );

                if(!empty($result) && count($result) == 1){
                    $nc_parent_id = $result[0]['nc_id'];
                }else{
                    $c = array(
                        'n_id' => 3,
                        'parent_id' => 0,
                        'name' => $parentCategory->innertext,
                        'isCreated' => date('Y-m-d H:i:s'),
                        'isUpdated' => date('Y-m-d H:i:s'),
                        'status' => 1
                    );

                    $nc_parent_id = $this->Common_DML->put_data( TBL_NETWORK_CATEGORY, $c );
                }

                foreach ($divClass->find(".panel-collapse ul li") as $subCategory) {
                    preg_match_all('~<a(.*?)href="([^"]+)"(.*?)>(.*)<\/a>~', $subCategory, $matches);
                    
                    $where = array( 'n_id' => 3, 'name' => $matches[4][0] );
                    $result = $this->Common_DML->get_data( TBL_NETWORK_CATEGORY, $where, '*' );

                    if(!empty($result) && count($result) == 1){
                        $nc_id = $result[0]['nc_id'];
                    }else{
                        $sc = array(
                            'n_id' => 3,
                            'parent_id' => $nc_parent_id,
                            'name' => $matches[4][0],
                            'isCreated' => date('Y-m-d H:i:s'),
                            'isUpdated' => date('Y-m-d H:i:s'),
                            'status' => 1
                        );
                        $nc_id = $this->Common_DML->put_data( TBL_NETWORK_CATEGORY, $sc );
                    }

                    $url = "https://www.digistore24.com{$matches[2][0]}";

                    $product_data = file_get_contents_curl($url);
                    $dom_product = new simple_html_dom();
                    $dom_product->load($product_data, true, true);


                    $products = array();
                    if(!empty($dom_product)) {
                        $total = isset($dom_product->find(".resultCount .count")[0]) ? $dom_product->find(".resultCount .count")[0]->innertext : '';
                        //echo $matches[4][0],'----https://www.digistore24.com',$matches[2][0],'----',$total,'</br>';
                        if(!empty($total)){
                            $count = (int) $total / 5;
                            $i = 1;
                            do{
                                if($i != 1){
                                    $text = file_get_contents_curl("{$url}?page={$i}");
                                    $dom_product = new simple_html_dom();
                                    $dom_product->load($text, true, true);
                                    if(empty($dom_product)) {
                                        break;
                                    }
                                }
                                foreach($dom_product->find(".affiliation_not_requested_container") as $divClass) {
                                    $product = array(
                                        'n_id' => 3,
                                        'nc_id' => $nc_id,
                                        'product_id' => '',
                                        'name' => '',
                                        'description' => '',
                                        'image_url' => '',
                                        'product_url' => '',
                                        'commission' => '',
                                        'isCreated' => date('Y-m-d H:i:s'),
                                        'isUpdated' => date('Y-m-d H:i:s'),
                                        'status' => 1
                                    );
                                    foreach($divClass->find(".helBold") as $title) {
                                        $product['name'] = str_replace('*Net per sale', '', $title->plaintext);
                                    }
                                    foreach($divClass->find(".rightColumn .pieContainer .innerText") as $commission) {
                                        $product['commission'] = str_replace('<span class="h6"><br />Commission</span>', '', $commission->innertext);
                                    }
                                    foreach($divClass->find(".promo_button_container [href]") as $href) {
                                        preg_match_all('~<a(.*?)href="([^"]+)"(.*?)>~', $href, $matches);
                                        $product_id = str_replace('https://www.digistore24.com/signup/', '', $matches[2][0]);
                                        $product_id = str_replace('/', '', $product_id);
                                        $product_url = "https://www.digistore24.com/redir/{$product_id}/userid/";
                                        $product['product_url'] = $product_url;
                                        $product['product_id'] = $product_id;
                                    }
                                    foreach($divClass->find(".productImg") as $img) {
                                        preg_match_all('~<img.*?src=["\']+(.*?)["\']+~', $img->innertext, $matches);
                                        $product_image = $matches[1][0];
                                        $product['image_url'] = $product_image;
                                    }
                                    foreach($divClass->find(".productText") as $description) {
                                        $product['description'] = $description->outertext;
                                    }

                                    $where = array( 'product_id' => $product['product_id'] );
                                    $result = $this->Common_DML->get_data( TBL_NETWORK_PRODUCT, $where, '*' );
            
                                    if(!empty($result) && count($result) == 1){
                                        continue;
                                    }
                                    
                                    $this->Common_DML->put_data( TBL_NETWORK_PRODUCT, $product );


                                }
                                $i++;
                            }while($i<=$count);
                        }
                    }

                    
                }               

            }
        }
    }

    public function authFacebook(){
        $this->load->library('Facebooklib');
        $this->session->set_userdata( 'facebook_domain', $_GET['url'] );
        $this->session->set_userdata( 'user_id', $_GET['user_id'] );
        $auth_url = $this->facebooklib->createAuthorizationURL();
        redirect($auth_url);
    }

    public function redirectOnFacebookToGetAccessToken(){
        $this->load->library('Facebooklib');
        if(isset($_GET['code'])){
            $date = date('Y-m-d H:i:s');
            $userID = $this->session->userdata( 'user_id' );
            $result = $this->facebooklib->generateAccessToken( $_GET['code'] );
            /* Store in database */
            if($result['status']){
                $access_token = $result['token'];
                $data = array( 
                    'user_id' => $userID,
                    'key' => 'FacebookAccessToken', 
                    'value' => json_encode($access_token), 
                    'isCreated' => $date,
                    'isUpdated' => $date,
                    'status' => 1 
                );
                $this->Common_DML->put_data( TBL_USER_SETTINGS, $data );
                
                $this->facebooklib->setAccessToken($access_token);
                $userinfo = $this->facebooklib->getUserInfo();
                $data = array( 
                    'user_id' => $userID,
                    'key' => 'FacebookUserInfo', 
                    'value' => json_encode($userinfo), 
                    'isCreated' => $date,
                    'isUpdated' => $date,
                    'status' => 1 
                );
                $this->Common_DML->put_data( TBL_USER_SETTINGS, $data );
            }

            $domain = $this->session->userdata( 'facebook_domain' );

            redirect($domain . 'facebook/redirect');

        }else{
            echo 'Access deny';
        }
    }

}