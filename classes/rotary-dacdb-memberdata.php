<?php
/*
Rotary Membership Data
*/
require_once(ABSPATH . 'wp-admin/includes/media.php');
require_once(ABSPATH . 'wp-admin/includes/file.php');
require_once(ABSPATH . 'wp-admin/includes/image.php');
require_once(ABSPATH . 'wp-admin/includes/user.php');

require_once( ROTARY_DACDB_CLASSES_PATH . 'rotary-dacdb-soapauth.php');


class RotaryDacdbMemberData extends RotaryDaCDb{
	private $rotaryAuth;
	private $rotaryImageURL;
	private $DACDBtransient;
	private static $countryCodes;
	function __construct( $rotaryAuth ) {
		$this->rotaryAuth = $rotaryAuth;
		self::$countryCodes = array(  
			'AFG'=>'AFGHANISTAN',  
			'ALB'=>'ALBANIA',  
			'DZA'=>'ALGERIA',  
			'ASM'=>'AMERICAN SAMOA',  
			'AND'=>'ANDORRA',  
			'AGO'=>'ANGOLA',  
			'AIA'=>'ANGUILLA',  
			'ATA'=>'ANTARCTICA',  
			'ATG'=>'ANTIGUA AND BARBUDA',  
			'ARG'=>'ARGENTINA',  
			'ARM'=>'ARMENIA',  
			'ABW'=>'ARUBA',  
			'AUS'=>'AUSTRALIA',  
			'AUT'=>'AUSTRIA',  
			'AZE'=>'AZERBAIJAN',  
			'BHS'=>'BAHAMAS',  
			'BHR'=>'BAHRAIN',  
			'BGD'=>'BANGLADESH',  
			'BRB'=>'BARBADOS',  
			'BLR'=>'BELARUS',  
			'BEL'=>'BELGIUM',  
			'BLZ'=>'BELIZE',  
			'BEN'=>'BENIN',  
			'BMU'=>'BERMUDA',  
			'BTN'=>'BHUTAN',  
			'BOL'=>'BOLIVIA',  
			'BIH'=>'BOSNIA AND HERZEGOWINA',  
			'BWA'=>'BOTSWANA',  
			'BVT'=>'BOUVET ISLAND',  
			'BRA'=>'BRAZIL',  
			'IOT'=>'BRITISH INDIAN OCEAN TERRITORY',  
			'BRN'=>'BRUNEI DARUSSALAM',  
			'BGR'=>'BULGARIA',  
			'BFA'=>'BURKINA FASO',  
			'BDI'=>'BURUNDI',  
			'KHM'=>'CAMBODIA',  
			'CMR'=>'CAMEROON',  
			'CAN'=>'CANADA',  
			'CPV'=>'CAPE VERDE',  
			'CYM'=>'CAYMAN ISLANDS',  
			'CAF'=>'CENTRAL AFRICAN REPUBLIC',  
			'TCD'=>'CHAD',  
			'CHL'=>'CHILE',  
			'CHN'=>'CHINA',  
			'CXR'=>'CHRISTMAS ISLAND',  
			'CCK'=>'COCOS ISLANDS',  
			'COL'=>'COLOMBIA',  
			'COM'=>'COMOROS',  
			'COG'=>'CONGO',  
			'COD'=>'CONGO, THE DRC',  
			'COK'=>'COOK ISLANDS',  
			'CRI'=>'COSTA RICA',  
			'CIV'=>'COTE D IVOIRE',  
			'HRV'=>'CROATIA',  
			'CUB'=>'CUBA',  
			'CYP'=>'CYPRUS',  
			'CZE'=>'CZECH REPUBLIC',  
			'DNK'=>'DENMARK',  
			'DJI'=>'DJIBOUTI',  
			'DMA'=>'DOMINICA',  
			'DOM'=>'DOMINICAN REPUBLIC',  
			'TMP'=>'EAST TIMOR',  
			'ECU'=>'ECUADOR',  
			'EGY'=>'EGYPT',  
			'SLV'=>'EL SALVADOR',  
			'GNQ'=>'EQUATORIAL GUINEA',  
			'ERI'=>'ERITREA',  
			'EST'=>'ESTONIA',  
			'ETH'=>'ETHIOPIA',  
			'FLK'=>'FALKLAND ISLANDS',  
			'FRO'=>'FAROE ISLANDS',  
			'FJI'=>'FIJI',  
			'FIN'=>'FINLAND',  
			'FRA'=>'FRANCE',  
			'FXX'=>'FRANCE, METROPOLITAN',  
			'GUF'=>'FRENCH GUIANA',  
			'PYF'=>'FRENCH POLYNESIA',  
			'ATF'=>'FRENCH SOUTHERN TERRITORIES',  
			'GAB'=>'GABON',  
			'GMB'=>'GAMBIA',  
			'GEO'=>'GEORGIA',  
			'DEU'=>'GERMANY',  
			'GHA'=>'GHANA',  
			'GIB'=>'GIBRALTAR',  
			'GRC'=>'GREECE',  
			'GRL'=>'GREENLAND',  
			'GRD'=>'GRENADA',  
			'GLP'=>'GUADELOUPE',  
			'GUM'=>'GUAM',  
			'GTM'=>'GUATEMALA',  
			'GIN'=>'GUINEA',  
			'GNB'=>'GUINEA-BISSAU',  
			'GUY'=>'GUYANA',  
			'HTI'=>'HAITI',  
			'HMD'=>'HEARD AND MC DONALD ISLANDS',  
			'VAT'=>'HOLY SEE (VATICAN CITY STATE)',  
			'HND'=>'HONDURAS',  
			'HKG'=>'HONG KONG',  
			'HUN'=>'HUNGARY',  
			'ISL'=>'ICELAND',  
			'IND'=>'INDIA',  
			'IDN'=>'INDONESIA',  
			'IRN'=>'IRAN',  
			'IRQ'=>'IRAQ',  
			'IRL'=>'IRELAND',  
			'ISR'=>'ISRAEL',  
			'ITA'=>'ITALY',  
			'JAM'=>'JAMAICA',  
			'JPN'=>'JAPAN',  
			'JOR'=>'JORDAN',  
			'KAZ'=>'KAZAKHSTAN',  
			'KEN'=>'KENYA',  
			'KIR'=>'KIRIBATI',  
			'PRK'=>'D.P.R.O. KOREA',  
			'KOR'=>'REPUBLIC OF KOREA',  
			'KWT'=>'KUWAIT',  
			'KGZ'=>'KYRGYZSTAN',  
			'LAO'=>'LAOS',  
			'LVA'=>'LATVIA',  
			'LBN'=>'LEBANON',  
			'LSO'=>'LESOTHO',  
			'LBR'=>'LIBERIA',  
			'LBY'=>'LIBYAN ARAB JAMAHIRIYA',  
			'LIE'=>'LIECHTENSTEIN',  
			'LTU'=>'LITHUANIA',  
			'LUX'=>'LUXEMBOURG',  
			'MAC'=>'MACAU',  
			'MKD'=>'MACEDONIA',  
			'MDG'=>'MADAGASCAR',  
			'MWI'=>'MALAWI',  
			'MYS'=>'MALAYSIA',  
			'MDV'=>'MALDIVES',  
			'MLI'=>'MALI',  
			'MLT'=>'MALTA',  
			'MHL'=>'MARSHALL ISLANDS',  
			'MTQ'=>'MARTINIQUE',  
			'MRT'=>'MAURITANIA',  
			'MUS'=>'MAURITIUS',  
			'MYT'=>'MAYOTTE',  
			'MEX'=>'MEXICO',  
			'FSM'=>'FEDERATED STATES OF MICRONESIA',  
			'MDA'=>'REPUBLIC OF MOLDOVA',  
			'MCO'=>'MONACO',  
			'MNG'=>'MONGOLIA',  
			'MSR'=>'MONTSERRAT',  
			'MAR'=>'MOROCCO',  
			'MOZ'=>'MOZAMBIQUE',  
			'MMR'=>'MYANMAR',  
			'NAM'=>'NAMIBIA',  
			'NRU'=>'NAURU',  
			'NPL'=>'NEPAL',  
			'NLD'=>'NETHERLANDS',  
			'ANT'=>'NETHERLANDS ANTILLES',  
			'NCL'=>'NEW CALEDONIA',  
			'NZL'=>'NEW ZEALAND',  
			'NIC'=>'NICARAGUA',  
			'NER'=>'NIGER',  
			'NGA'=>'NIGERIA',  
			'NIU'=>'NIUE',  
			'NFK'=>'NORFOLK ISLAND',  
			'MNP'=>'NORTHERN MARIANA ISLANDS',  
			'NOR'=>'NORWAY',  
			'OMN'=>'OMAN',  
			'PAK'=>'PAKISTAN',  
			'PLW'=>'PALAU',  
			'PAN'=>'PANAMA',  
			'PNG'=>'PAPUA NEW GUINEA',  
			'PRY'=>'PARAGUAY',  
			'PER'=>'PERU',  
			'PHL'=>'PHILIPPINES',  
			'PCN'=>'PITCAIRN',  
			'POL'=>'POLAND',  
			'PRT'=>'PORTUGAL',  
			'PRI'=>'PUERTO RICO',  
			'QAT'=>'QATAR',  
			'REU'=>'REUNION',  
			'ROM'=>'ROMANIA',  
			'RUS'=>'RUSSIAN FEDERATION',  
			'RWA'=>'RWANDA',  
			'KNA'=>'SAINT KITTS AND NEVIS',  
			'LCA'=>'SAINT LUCIA',  
			'VCT'=>'SAINT VINCENT AND THE GRENADINES',  
			'WSM'=>'SAMOA',  
			'SMR'=>'SAN MARINO',  
			'STP'=>'SAO TOME AND PRINCIPE',  
			'SAU'=>'SAUDI ARABIA',  
			'SEN'=>'SENEGAL',  
			'SYC'=>'SEYCHELLES',  
			'SLE'=>'SIERRA LEONE',  
			'SGP'=>'SINGAPORE',  
			'SVK'=>'SLOVAKIA',  
			'SVN'=>'SLOVENIA',  
			'SLB'=>'SOLOMON ISLANDS',  
			'SOM'=>'SOMALIA',  
			'ZAF'=>'SOUTH AFRICA',  
			'SGS'=>'SOUTH GEORGIA AND SOUTH S.S.',  
			'ESP'=>'SPAIN',  
			'LKA'=>'SRI LANKA',  
			'SHN'=>'ST. HELENA',  
			'SPM'=>'ST. PIERRE AND MIQUELON',  
			'SDN'=>'SUDAN',  
			'SUR'=>'SURINAME',  
			'SJM'=>'SVALBARD AND JAN MAYEN ISLANDS',  
			'SWZ'=>'SWAZILAND',  
			'SWE'=>'SWEDEN',  
			'CHE'=>'SWITZERLAND',  
			'SYR'=>'SYRIAN ARAB REPUBLIC',  
			'TWN'=>'TAIWAN, PROVINCE OF CHINA',  
			'TJK'=>'TAJIKISTAN',  
			'TZA'=>'UNITED REPUBLIC OF TANZANIA',  
			'THA'=>'THAILAND',  
			'TGO'=>'TOGO',  
			'TKL'=>'TOKELAU',  
			'TON'=>'TONGA',  
			'TTO'=>'TRINIDAD AND TOBAGO',  
			'TUN'=>'TUNISIA',  
			'TUR'=>'TURKEY',  
			'TKM'=>'TURKMENISTAN',  
			'TCA'=>'TURKS AND CAICOS ISLANDS',  
			'TUV'=>'TUVALU',  
			'UGA'=>'UGANDA',  
			'UKR'=>'UKRAINE',  
			'ARE'=>'UNITED ARAB EMIRATES',  
			'GBR'=>'UNITED KINGDOM',  
			'USA'=>'UNITED STATES',  
			'UMI'=>'U.S. MINOR ISLANDS',  
			'URY'=>'URUGUAY',  
			'UZB'=>'UZBEKISTAN',  
			'VUT'=>'VANUATU',  
			'VEN'=>'VENEZUELA',  
			'VNM'=>'VIET NAM',  
			'VGB'=>'VIRGIN ISLANDS (BRITISH)',  
			'VIR'=>'VIRGIN ISLANDS (U.S.)',  
			'WLF'=>'WALLIS AND FUTUNA ISLANDS',  
			'ESH'=>'WESTERN SAHARA',  
			'YEM'=>'YEMEN',  
			'YUG'=>'Yugoslavia',  
			'ZMB'=>'ZAMBIA',  
			'ZWE'=>'ZIMBABWE' 
		);  
		$options = get_option('rotary_dacdb');
		$this->rotaryImageURL= 'http://www.directory-online.com/Rotary/Accounts/'.$options['rotary_dacdb_district'].'/Pics/';
		$this->DACDBtransient = 'dacdb_'.$options['rotary_dacdb_club'];
		$this->getMemberData();
	}
	function email_change_message( $email_change_email, $user, $userdata ) {
		$email_change_text = sprintf( __('%s,
				
Your email address with the ###SITENAME### website (###SITEURL###) has been changed from ###EMAIL### to %s.
						
This change was made to match your email address registered with DaCDb.
				
If this is not the correct email address for you, please log in to your DaCDb account to correct it. Changes to DaCDb may take up to 7 days to affect the website.  If you have any questions, please contact ###ADMIN_EMAIL###
		
This email was autogenerated, and sent to ###EMAIL###'), $userdata['first_name'], $userdata['user_email'] ) ;
		
		$email_change_email['message'] = $email_change_text;
		return $email_change_email;
	}

	function username_change_message( $email_change_email, $user, $userdata ) {
		$email_change_text = sprintf( __('%s,
	
Your username with the ###SITENAME### website (###SITEURL###) has been changed to ###USERNAME###.
	
This change was made to match your User Name registered with DaCDb (https://www.directory-online.com).  
				
Your email address is %s.  If this is not correct, please log on to DaCDb where you can edit your profile.  Changes to DaCDb may take up to 7 days to affect the website.  If you have any questions, please contact ###ADMIN_EMAIL###
	
This email was autogenerated from an unmonitored email account'), $userdata['first_name'], $userdata['user_email']) ;
	
		$email_change_email['to'] = $userdata['user_email'];
		$email_change_email['subject'] = __( '[%s] Notice of Username Change' );
		$email_change_email['message'] = $email_change_text;
		return $email_change_email;
	}
	
	private function addProfilePhoto($user_id, $newUser, $value, $membername) {
		$addPhoto = false;
		$newPhoto = trim($value);
		if ($newUser ) {
			$addPhoto = true;
		}
		else {
			$currUserPhoto = basename(get_user_meta( $user_id, 'profilepicture', true));
			if (strcasecmp($currUserPhoto, $newPhoto) != 0) {
				$addPhoto = true;
			}
		}
		if ($addPhoto && $newPhoto) {
			$photoHTML = media_sideload_image($this->rotaryImageURL.$value,1,$membername);
			if(!is_wp_error($photoHTML)) {
				$doc = new DOMDocument();
				@$doc->loadHTML($photoHTML);
				$tags = $doc->getElementsByTagName('img');
				update_user_meta( $user_id, 'profilepicture', $tags->item(0)->getAttribute('src') );				
			}
		}
		
	}
	function getMemberData() {
		get_currentuserinfo();
	 	if ( 	false ===  get_transient( $this->DACDBtransient ) 
	 			|| (defined('WP_ROTARY_MEMBERS_FORCE_UPDATE') && true === WP_ROTARY_MEMBERS_FORCE_UPDATE ) 
	 			|| (defined('WP_ROTARY_COMMITTEES_FORCE_UPDATE') && true === WP_ROTARY_COMMITTEES_FORCE_UPDATE ) 
	 			|| 0 < strpos( $user_email, '@example.com' )
			) {
	 		
			do_action( 'before_dacdb_update' );
		  	$this->updateMemberData();
			$this->updateCommitteeData();
			$this->updateCommitteeMembers();
	 	}
	}
	function updateMemberData() {
		global $wpdb;
		$client = $this->rotaryAuth->get_soap_client();
		$token = $this->rotaryAuth->get_soap_token();
		$memberArray = array();
	
	
		//$header = new SoapHeader('http://xWeb', 'Token', $token, false );
		$header = new SoapHeader('http://xweb', 'Token', $token, false );
		$client->__setSoapHeaders(array($header));
		try {
			$rotaryclubmembers = $client->ClubMembers( '0,1,5,148,154', 'UserName' );
			
			$member_table_name = $wpdb->prefix . 'rotarymembers';
			//print_r($rotaryclubmembers->MEMBERS); die;
			$wpdb->query('TRUNCATE TABLE '.$member_table_name);
			foreach($rotaryclubmembers->MEMBERS->MEMBER as $member) {
				$username 	= strval($member->LOGINNAME);
				//add to a DacDB user ids to a custom table that we check to see if a WordPress User is no longer a RotaryMember
				$rows_affected = $wpdb->insert( $member_table_name, array('dacdbuser' => esc_sql( $username )));
		
				$memberArray['clubname'] = strval($member->CLUBNAME);
				$memberArray['first_name'] = strval($member->FIRSTNAME);
				$memberArray['last_name'] = strval($member->LASTNAME);
				$memberArray['classification'] = strval($member->CLASSIFICATION);
				$memberArray['clubrole'] = strval($member->CLUBPOSITION);
				$memberArray['partnername'] = strval($member->PARTNERFIRSTNAME);
				$memberArray['cellphone'] = strval($member->CELLPHONE);
				$memberArray['busphone'] = strval($member->OFFICEPHONE);
				$memberArray['homephone'] = strval($member->HOMEPHONE);
				$memberArray['email'] = strlen(strval($member->PREFERRED_EMAIL))  > 0 ? strval($member->PREFERRED_EMAIL)  : $username.'@example.com';
		
				$memberArray['anniversarydate'] = strval($member->ANNIVERSARYDATE);
				$memberArray['streetaddress1'] = strval($member->PREFERRED_ADDRESS1);
				$memberArray['streetaddress2'] = strval($member->PREFERRED_ADDRESS2);
				$memberArray['city'] = strval($member->PREFERRED_CITY);
				$memberArray['country'] =  ucwords(strtolower(self::$countryCodes[strval($member->PREFERRED_COUNTRYCODE)]));
				$memberArray['state'] = strval($member->PREFERRED_STATECODE);
				$memberArray['county'] = strval($member->PREFERRED_COUNTY);
				$memberArray['zip'] = strval($member->PREFERRED_POSTALZIP);
				$memberArray['company'] = strval($member->BUSNAME);
				$memberArray['jobtitle'] = strval($member->BUSPOSITION);
				$memberArray['birthday'] = strval($member->BIRTHDATE);
				$memberArray['membersince'] = strval($member->STARTDATE);
				$memberArray['busweb'] = strval($member->BUSWEB);
				$memberArray['memberyesno'] = true;
				$memberArray['profilepicture'] = strval($member->IMAGE);
				
				$display_name = $memberArray['first_name'] . ' ' . $memberArray['last_name'];
				
				//echo '<br>' .$username . ' ' . $memberArray['email'];
				
				//check if the user already exists
				$newUser = false;
				
				// if the username exists, then update the email address to this username, and be done
				if( username_exists( $username )) {
					$user_id = username_exists( $username );
					$user = get_userdata( $user_id );
					//check if this email is already in use
					$another_user_id = email_exists( $memberArray['email'] );
					if( $another_user_id != $user_id && $another_user_id ) {
						//usernames are more important than email addresses, so delete the user with the duplicate email, and transfer their content.
						$this->merge_user( $user_id, $another_user_id );
					}
					if( $memberArray['email'] != $user->user_email ) {
						add_filter( 'email_change_email', array( $this, 'email_change_message',  ), 1, 3 );
						wp_update_user( array ('ID' => $user_id, 'user_email' => $memberArray['email'] ) ) ;
						remove_filter( 'email_change_email', array( $this, 'email_change_message',  ), 1, 3 );
					}
					//$user = get_userdata( $user_id );
					//echo '<br>Username found: ' . $user_id . ' Email is now ' . $user->user_email;
				}
				
				// if the username does not exist, but the email does, then copy this user to a new user
				elseif( email_exists( $memberArray['email'] )) {
					
					$old_user_id = email_exists( $memberArray['email'] );
					$old_user = get_userdata( $old_user_id );
					//Add a new user with the correct username to the database
					remove_action('user_register', array($this->rotaryAuth, 'disable_function'));
					$user_id = wp_create_user( $username, 'password', 'temp@example.com' );
					add_action('user_register', array($this->rotaryAuth, 'disable_function'));
					
					$this->merge_user( $user_id, $old_user_id );
					
					add_filter( 'email_change_email', array( $this, 'username_change_message',  ), 1, 3 );
					wp_update_user( array ('ID' => $user_id, 'first_name' => $memberArray['first_name'], 'user_email' => $memberArray['email'] ) ) ;
					remove_filter( 'email_change_email', array( $this, 'username_change_message',  ), 1, 3 );
					$newUser = true;
					
					//echo 'Username changed to ' . $username;
				}
				
				//if neither exists, create a new user
				else {
					remove_action('user_register', array($this->rotaryAuth, 'disable_function'));
					$password = wp_generate_password( $length=12, $include_standard_special_chars=false );
					$user_id = wp_create_user( $username, $password, $memberArray['email'] );
					wp_update_user( array ('ID' => $user_id, 'role' => 'member' )) ;
					$newUser = true;
					add_action('user_register', array($this->rotaryAuth, 'disable_function'));
					//echo '<br>User created ' . $username;
				}
				if (!is_wp_error( $user_id ) && 1 != $user_id && $user_id ) {
					wp_update_user( array ('ID' => $user_id, 'display_name' => $display_name )) ;
					
					foreach ($memberArray as $key => $value) {
						if ('profilepicture' == $key) {
							$this->addProfilePhoto($user_id, $newUser, $value, $display_name );
						}
						else {
							update_user_meta( $user_id, $key, $value );
							//echo '<br>Data updated for ID=' . $user_id . ' Key=' . $key . ' Value='. $value;
						}//end profilepicture if
		
					}//end foreach
						
				}//end userid = 1 check
			}//end foreach
			
			
			// HARD DELETE users who are no longer Rotary Members
			//$query = 'DELETE FROM '  .$wpdb->users .' WHERE '.$wpdb->users.'.ID != 1 AND '.$wpdb->users.'.user_login NOT IN (SELECT dacdbuser FROM ' .$member_table_name.')';
			//$wpdb->query($query);
			
			// SOFT DELETE update users who are no longer Rotary Members to change their member status
			$query = 'UPDATE '.$wpdb->usermeta .', ' . $wpdb->users   .' 
					  SET meta_value = 0 
						WHERE meta_key ="memberyesno" 
						AND '.$wpdb->users.'.user_login NOT IN (
									SELECT dacdbuser 
									FROM ' . $member_table_name.') 
									AND  ' . $wpdb->usermeta.'.user_id = '. $wpdb->users.'.ID';
			//echo $query;
			$wpdb->query($query);
			$query = 'UPDATE '.$wpdb->usermeta .', ' . $wpdb->users   .' 
						SET meta_value = "" 
							WHERE meta_key ="membersince" 
							AND '.$wpdb->users.'.user_login NOT IN (
									SELECT dacdbuser 
									FROM ' . $member_table_name.') 
									AND  ' . $wpdb->usermeta.'.user_id = '. $wpdb->users.'.ID';
			//echo $query;
			$wpdb->query($query);

			set_transient( $this->DACDBtransient , 'dacdb', 60*60*24*7 );
			}
		catch (SoapFault $exception) {
			$exception = $client->__soap_fault;
			delete_transient( $this->DACDBtransient );
			var_dump( $exception );die;
		}
	}
	function merge_user( $user_id, $merged_user_id ) {
		$user = new WP_User( $user_id );
		$merged_user = new WP_User( $merged_user_id );
		$roles = $merged_user->roles;
		foreach( $roles as $role ) {
			$user->add_role( $role );
		}
		wp_delete_user( $merged_user_id, $user_id );
		if( get_userdata( $merged_user_id )) {
			echo 'Please contact the site administrator.  During the DaCDb update, a duplicate user failed to be deleted. The duplicate is  ' . $merged_user_id. ' Trying to merge to user ' .  $user_id . ' Current user logged in is ' . get_current_user_id(); 
		}
	}
	function updateCommitteeData() {
		global $wpdb;		
		$todayDate = date("Y-m-d");
    	$thisMonth = date("m");
    	$dateOneYearAdded = strtotime(date("Y-m-d", strtotime($todayDate)) . "+1 year");
    	$dateOneYearSubtracted = strtotime(date("Y-m-d", strtotime($todayDate)) . "-1 year");
    	$dateTwoYearSubtracted = strtotime(date("Y-m-d", strtotime($todayDate)) . "-2 year");

		if (intval($thisMonth) < 7) {
			$committeeDate = strval(date('Y', $dateOneYearSubtracted)) . '-' . strval(date("y"));
			$committeeLastDate = strval(date('Y', $dateTwoYearSubtracted)) . '-' . strval(date("y", $dateOneYearSubtracted));
		}
		else {
			$committeeDate = strval(date("Y")) . '-' . strval(date('y', $dateOneYearAdded));
			$committeeLastDate = strval(date('Y', $dateOneYearSubtracted)) . '-' . strval(date("y"));
		}
		
		$client = $this->rotaryAuth->get_soap_client();
		$token = $this->rotaryAuth->get_soap_token();
  		$header = new SoapHeader('http://xweb', 'Token', $token, false );
  		$client->__setSoapHeaders(array($header));  
		$options = get_option('rotary_dacdb');
		
		echo 'got here';die;
  		try {
 	 		$rotaryclubcommittees = $client->Committees($options['rotary_dacdb_club'], $committeeDate, '0', 'CommitteeName');
 	 		//TODO: set committee to draft if it was in last years, but not this years
 	 		//$lastyearcommittees = $client->Committees($options['rotary_dacdb_club'], $committeeLastDate, '0', 'CommitteeName');
 	 		
 	 		if (count($rotaryclubcommittees->COMMITTEES->COMMITTEE)) {
 	 			$member_table_name = $wpdb->prefix . 'rotarycommittees';
 	 			$wpdb->query('TRUNCATE TABLE '.$member_table_name);
 	 			foreach($rotaryclubcommittees->COMMITTEES->COMMITTEE as $committee) {
 	 				$committeeNumber = intval($committee->COMMITTEEID);
 	 				//first try to get committee by number, this won't work at year end went committee numbers changes
 	 				$args = array(
 	 						'post_type' => 'rotary-committees',
 	 						//'post_status' => 'publish',  //doesn't matter what the status is - if we find it in DaCDb, we are going to publish it anyway!
 	 						'meta_query' => array(
 	 								array(
 	 										'key' => 'committeenumber',
 	 										'value' => $committeeNumber,
 	 								)
 	 						)
 	 				);
 	 				$query = new WP_Query($args);
 	 				//if committe post by id is not found, look for committee by name
 	 				if (!$query->have_posts()) {
 	 					$args = array(
 	 							'post_type' => 'rotary-committees',
 	 							//'post_status' => 'publish',
 	 							's' => htmlspecialchars( $committee->COMMITTEENAME ),
 	 							'exact' => true, //(bool) - flag to make it only match whole titles/posts - Default value is false. For more information see: https://gist.github.com/2023628#gistcomment-285118
 	 							'sentence' => true //(bool) - flag to make it do a phrase search - Default value is false. For more information see: https://gist.github.com/2023628#gistcomment-285118
 	 					);
 	 					$query = new WP_Query($args);
 	 				}
 	 				//now we know that there are really no committees that look like the one we have from DaCDb
 	 				if (!$query->have_posts()) {
 	 					//add committee to custom table to possibly reset status later
 	 					$rows_affected = $wpdb->insert( $member_table_name, array('committeenum' => esc_sql( $committeeNumber  )));
 	 					// this may be creating duplicate committees
 	 					$this->addNewCommittee( $committee );
 	 				}
 	 				else {
 	 					//add committee to custom table to possibly reset status later
 	 					while ( $query->have_posts() ) {
 	 						$query->the_post();
 	 						update_field('field_5351b9ef109fe', $committee->COMMITTEEID, get_the_id());
 	 						wp_publish_post( get_the_id() );
 	 						$rows_affected = $wpdb->insert( $member_table_name, array('committeenum' => esc_sql( get_field( 'committeenumber' )  )));
 	 						//wp_update_post( get_post( get_the_id() ) );
 	 						//$this->connectMemberToCommittee( $committeeNumber, get_the_id());
 	 					}//endwhile
 	 				}//end check for posts
 	 					
 	 			}//end foreach committee
 	 				
 	 			//Do I ever want to do this?
 	 			//$this->updateDeletedCommitteeStatus();
 	 		}
  		}
   		catch (SoapFault $exception) {
   			echo $exception; 
  		} 

	}
	//This is probably not a very good idea...
	function updateDeletedCommitteeStatus() {
		global $wpdb;
		$member_table_name = $wpdb->prefix . 'rotarycommittees';
		$sql = "UPDATE {$wpdb->posts}  
				INNER JOIN {$wpdb->postmeta} 
					ON {$wpdb->postmeta}.post_id = {$wpdb->posts}.ID 
				SET {$wpdb->posts}.post_status = 'draft' 
					WHERE {$wpdb->posts}.post_type = 'rotary-committees' 
					AND {$wpdb->postmeta}.meta_key = 'committeenumber' 
					AND {$wpdb->postmeta}.meta_value != '' 
					AND {$wpdb->postmeta}.meta_value NOT IN (
												SELECT committeenum 
												FROM {$member_table_name}
												)";
		
		$rows_affected = $wpdb->get_results($sql);

	}	

	
	//add a new committee
	function addNewCommittee( $committee ) {
		$new_post = array(
			'post_title' => $committee->COMMITTEENAME,
			'post_content' => '',
			'post_status' => 'publish',
			'post_author' => 1,
			'post_type' => 'rotary-committees',
			'post_category' => array(0)
		);
		$post_id = wp_insert_post( $new_post );
		//add_post_meta($post_id, 'committeenumber', $committee->COMMITTEEID, true);
		update_field('field_5351b9ef109fe', $committee->COMMITTEEID, $post_id);
		$committeeDesc = str_replace("&rsquo;", "'", $committee->DESCRIPTION);
		update_field('field_5351ba0f109ff', html_entity_decode(strip_tags($committeeDesc)), $post_id);
		//$this->connectMemberToCommittee($committee->COMMITTEEID, $post_id);
	}
	function updateCommitteeMembers() {
		$args = array(
				'post_type' => 'rotary-committees',
				'post_status' => 'publish'
		);
		$query = new WP_Query($args);
		while ( $query->have_posts() ) {
				$query->the_post();
				$committeeNumber = intval( get_field( 'field_5351b9ef109fe' ));
				if($committeeNumber ) {
					$this->connectMemberToCommittee( $committeeNumber, get_the_ID());
				}
		}
		wp_reset_postdata();
	}
	
	//Build the connection from the user (Rotary Member to the Committee) 
	//This relies on the Post2Post plugin
	function connectMemberToCommittee( $committeeNumber, $post_id ) {
		
		$client = $this->rotaryAuth->get_soap_client();
		$token = $this->rotaryAuth->get_soap_token();
  		$header = new SoapHeader('http://xweb', 'Token', $token, false );
  		$client->__setSoapHeaders(array($header)); 
  		$coChairCount = 0;
  		$chairArray = array('CHAIR', 'MEMBERSHIP CHAIR', 'COMMITTEE CHAIR');
  		$cochairArray = array('COCHAIR', 'CO-CHAIR');
		try {	
			$rotaryclubmembers = $client->CommitteeMembersByID(floatval($committeeNumber), 'UserName');
			//print_r($rotaryclubmembers);
		}
		catch (SoapFault $exception) {
			echo $exception;	
			die;
		}
		if ( is_object($rotaryclubmembers->MEMBERS) && is_array($rotaryclubmembers->MEMBERS->MEMBER )) {
			//loop through all users for a committee and delete them
					$users = get_users( array(
						'connected_type' => 'committees_to_users',
						'connected_items' => $post_id,
						'connected_direction' => 'from',
					)); 
					if ( is_array( $users )) {
						foreach ( $users as $user ) {
							p2p_type( 'committees_to_users' )->disconnect( $post_id, $user->ID );
						}
					}	

			foreach($rotaryclubmembers->MEMBERS->MEMBER as $member) {
				$user_id = email_exists($member->EMAIL);
				if ($user_id) {
					//add chair and co-chairs
					
					if (in_array(strtoupper($member->COMMITTEEPOSITION), $chairArray)) {
						update_field('field_5356d453d36ac', $member->EMAIL, $post_id);
					}
					if (in_array(strtoupper($member->COMMITTEEPOSITION), $cochairArray)) {
					   $coChairCount++;
					   if (1 == $coChairCount) {
						   update_field('field_5356d48feb36b', $member->EMAIL, $post_id);
					   }
					   else  {
						   update_field('field_5356d4dbeecc0', $member->EMAIL, $post_id);
					   }
							
					}
					//from  committees to  user
					p2p_type( 'committees_to_users' )->connect( $post_id, $user_id, array('date' => current_time('mysql')
					) );
				}
			} //end foreach
		}
	}

}/*end class*/