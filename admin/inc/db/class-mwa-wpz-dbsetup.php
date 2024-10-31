<?php
	defined('ABSPATH') or die();

	if(!class_exists('MWA_WPZ_Dbsetup')){

		class MWA_WPZ_Dbsetup{
			public static function setupdb() {
				global $wpdb;
				
				/* Create Zoom Auth Table for manage Token Generator*/
				$zoom_auth_tblname = $wpdb->prefix .'mwa_zoom_auth';

				$create_zoomauth_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_auth_tblname`(
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`uac` text NOT NULL,
						`client_id` text NOT NULL,
						`client_secret` text NOT NULL,
						`redirect_uri` text NOT NULL,
						`auth_date` date NOT NULL,
						`status` varchar(20) NOT NULL,
						`remark` text NOT NULL,
						`tch_id` int(11) NOT NULL,
						`tok_id` int(11) NOT NULL,
						PRIMARY KEY (`id`)
					)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

				/* Create Zoom Token Table for manage Token & refresh token */				
				$zoom_token_tblname = $wpdb->prefix .'mwa_zoom_token';

				$create_token_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_token_tblname`(
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`access_token` text NOT NULL,
						`tok_date` date NOT NULL,
						`tok_time` time NOT NULL,
						`tch_id` int(11) NOT NULL,
						`authid` int(11) NOT NULL,
						PRIMARY KEY (`id`)
					)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;";


				/* Create Meeting Host Table for manage meeting */				
				$zoom_host_tblname = $wpdb->prefix .'mwa_zoom_host';

				$host_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_host_tblname`(
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`hostid` text NOT NULL,
						`meetingid` text NOT NULL,
						`meeting_password` text NOT NULL,
						`timezone` text NOT NULL,
						`duration` text NOT NULL,
						`meeting_date` date NOT NULL,
						`start_time` time NOT NULL,
						`joinurl` text NOT NULL,
						`topicname` text NOT NULL,
						`view_type` int(11) NOT NULL,
						`batch` int(11) NOT NULL,
						`teacherid` int(11) NOT NULL,
						PRIMARY KEY (`id`)
					)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

				/* Create Time-Zone Table */	
				$zoom_timezone_tblname = $wpdb->prefix .'mwa_timezone';	

				$timezone_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_timezone_tblname`(
						`timezone` varchar(100) NOT NULL,
  						`timezone_val` varchar(102) NOT NULL				
					)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

				/* Insert data into Time-Zone Table */
				$insert_timezone = "INSERT INTO `$zoom_timezone_tblname` (`timezone`, `timezone_val`) VALUES ('Pacific/Midway', '(GMT-11:00) Midway Island, Samoa '),
							('Pacific/Pago_Pago', '(GMT-11:00) Pago Pago '),
							('Pacific/Honolulu', '(GMT-10:00) Hawaii '),
							('America/Anchorage', '(GMT-8:00) Alaska '),
							('America/Vancouver', '(GMT-7:00) Vancouver '),
							('America/Los_Angeles', '(GMT-7:00) Pacific Time (US and Canada) '),
							('America/Tijuana', '(GMT-7:00) Tijuana '),
							('America/Phoenix', '(GMT-7:00) Arizona '),
							('America/Edmonton', '(GMT-6:00) Edmonton '),
							('America/Denver', '(GMT-6:00) Mountain Time (US and Canada) '),
							('America/Mazatlan', '(GMT-6:00) Mazatlan '),
							('America/Regina', '(GMT-6:00) Saskatchewan '),
							('America/Guatemala', '(GMT-6:00) Guatemala '),
							('America/El_Salvador', '(GMT-6:00) El Salvador '),
							('America/Managua', '(GMT-6:00) Managua '),
							('America/Costa_Rica', '(GMT-6:00) Costa Rica '),
							('America/Tegucigalpa', '(GMT-6:00) Tegucigalpa '),
							('America/Winnipeg', '(GMT-5:00) Winnipeg '),
							('America/Chicago', '(GMT-5:00) Central Time (US and Canada) '),
							('America/Mexico_City', '(GMT-5:00) Mexico City '),
							('America/Panama', '(GMT-5:00) Panama '),
							('America/Bogota', '(GMT-5:00) Bogota '),
							('America/Lima', '(GMT-5:00) Lima '),
							('America/Caracas', '(GMT-4:30) Caracas '),
							('America/Montreal', '(GMT-4:00) Montreal '),
							('America/New_York', '(GMT-4:00) Eastern Time (US and Canada) '),
							('America/Indianapolis', '(GMT-4:00) Indiana (East) '),
							('America/Puerto_Rico', '(GMT-4:00) Puerto Rico '),
							('America/Santiago', '(GMT-4:00) Santiago '),
							('America/Halifax', '(GMT-3:00) Halifax '),
							('America/Montevideo', '(GMT-3:00) Montevideo '),
							('America/Araguaina', '(GMT-3:00) Brasilia '),
							('America/Argentina/Buenos_Aires', '(GMT-3:00) Buenos Aires, Georgetown '),
							('America/Sao_Paulo', '(GMT-3:00) Sao Paulo '),
							('Canada/Atlantic', '(GMT-3:00) Atlantic Time (Canada) '),
							('America/St_Johns', '(GMT-2:30) Newfoundland and Labrador '),
							('America/Godthab', '(GMT-2:00) Greenland '),
							('Atlantic/Cape_Verde', '(GMT-1:00) Cape Verde Islands '),
							('Atlantic/Azores', '(GMT+0:00) Azores '),
							('UTC', '(GMT+0:00) Universal Time UTC '),
							('Etc/Greenwich', '(GMT+0:00) Greenwich Mean Time '),
							('Atlantic/Reykjavik', '(GMT+0:00) Reykjavik '),
							('Africa/Nouakchott', '(GMT+0:00) Nouakchott '),
							('Europe/Dublin', '(GMT+1:00) Dublin '),
							('Europe/London', '(GMT+1:00) London '),
							('Europe/Lisbon', '(GMT+1:00) Lisbon '),
							('Africa/Casablanca', '(GMT+1:00) Casablanca '),
							('Africa/Bangui', '(GMT+1:00) West Central Africa '),
							('Africa/Algiers', '(GMT+1:00) Algiers '),
							('Africa/Tunis', '(GMT+1:00) Tunis '),
							('Europe/Belgrade', '(GMT+2:00) Belgrade, Bratislava, Ljubljana '),
							('CET', '(GMT+2:00) Sarajevo, Skopje, Zagreb '),
							('Europe/Oslo', '(GMT+2:00) Oslo '),
							('Europe/Copenhagen', '(GMT+2:00) Copenhagen '),
							('Europe/Brussels', '(GMT+2:00) Brussels '),
							('Europe/Berlin', '(GMT+2:00) Amsterdam, Berlin, Rome, Stockholm, Vienna '),
							('Europe/Amsterdam', '(GMT+2:00) Amsterdam '),
							('Europe/Rome', '(GMT+2:00) Rome '),
							('Europe/Stockholm', '(GMT+2:00) Stockholm '),
							('Europe/Vienna', '(GMT+2:00) Vienna '),
							('Europe/Luxembourg', '(GMT+2:00) Luxembourg '),
							('Europe/Paris', '(GMT+2:00) Paris '),
							('Europe/Zurich', '(GMT+2:00) Zurich '),
							('Europe/Madrid', '(GMT+2:00) Madrid '),
							('Africa/Harare', '(GMT+2:00) Harare, Pretoria '),
							('Europe/Warsaw', '(GMT+2:00) Warsaw '),
							('Europe/Prague', '(GMT+2:00) Prague Bratislava '),
							('Europe/Budapest', '(GMT+2:00) Budapest '),
							('Africa/Tripoli', '(GMT+2:00) Tripoli '),
							('Africa/Cairo', '(GMT+2:00) Cairo '),
							('Africa/Johannesburg', '(GMT+2:00) Johannesburg '),
							('Europe/Helsinki', '(GMT+3:00) Helsinki '),
							('Africa/Nairobi', '(GMT+3:00) Nairobi '),
							('Europe/Sofia', '(GMT+3:00) Sofia '),
							('Europe/Istanbul', '(GMT+3:00) Istanbul '),
							('Europe/Athens', '(GMT+3:00) Athens '),
							('Europe/Bucharest', '(GMT+3:00) Bucharest '),
							('Asia/Nicosia', '(GMT+3:00) Nicosia '),
							('Asia/Beirut', '(GMT+3:00) Beirut '),
							('Asia/Damascus', '(GMT+3:00) Damascus '),
							('Asia/Jerusalem', '(GMT+3:00) Jerusalem '),
							('Asia/Amman', '(GMT+3:00) Amman '),
							('Europe/Moscow', '(GMT+3:00) Moscow '),
							('Asia/Baghdad', '(GMT+3:00) Baghdad '),
							('Asia/Kuwait', '(GMT+3:00) Kuwait '),
							('Asia/Riyadh', '(GMT+3:00) Riyadh '),
							('Asia/Bahrain', '(GMT+3:00) Bahrain '),
							('Asia/Qatar', '(GMT+3:00) Qatar '),
							('Asia/Aden', '(GMT+3:00) Aden '),
							('Africa/Khartoum', '(GMT+3:00) Khartoum '),
							('Africa/Djibouti', '(GMT+3:00) Djibouti '),
							('Africa/Mogadishu', '(GMT+3:00) Mogadishu '),
							('Europe/Kiev', '(GMT+3:00) Kiev '),
							('Asia/Dubai', '(GMT+4:00) Dubai '),
							('Asia/Muscat', '(GMT+4:00) Muscat '),
							('Asia/Tehran', '(GMT+4:30) Tehran '),
							('Asia/Kabul', '(GMT+4:30) Kabul '),
							('Asia/Baku', '(GMT+5:00) Baku, Tbilisi, Yerevan '),
							('Asia/Yekaterinburg', '(GMT+5:00) Yekaterinburg '),
							('Asia/Tashkent', '(GMT+5:00) Islamabad, Karachi, Tashkent '),
							('Asia/Calcutta', '(GMT+5:30) India '),
							('Asia/Kolkata', '(GMT+5:30) Mumbai, Kolkata, New Delhi '),
							('Asia/Kathmandu', '(GMT+5:45) Kathmandu '),
							('Asia/Novosibirsk', '(GMT+6:00) Novosibirsk '),
							('Asia/Almaty', '(GMT+6:00) Almaty '),
							('Asia/Dacca', '(GMT+6:00) Dacca '),
							('Asia/Dhaka', '(GMT+6:00) Astana, Dhaka '),
							('Asia/Krasnoyarsk', '(GMT+7:00) Krasnoyarsk '),
							('Asia/Bangkok', '(GMT+7:00) Bangkok '),
							('Asia/Saigon', '(GMT+7:00) Vietnam '),
							('Asia/Jakarta', '(GMT+7:00) Jakarta '),
							('Asia/Irkutsk', '(GMT+8:00) Irkutsk, Ulaanbaatar '),
							('Asia/Shanghai', '(GMT+8:00) Beijing, Shanghai '),
							('Asia/Hong_Kong', '(GMT+8:00) Hong Kong '),
							('Asia/Taipei', '(GMT+8:00) Taipei '),
							('Asia/Kuala_Lumpur', '(GMT+8:00) Kuala Lumpur '),
							('Asia/Singapore', '(GMT+8:00) Singapore '),
							('Australia/Perth', '(GMT+8:00) Perth '),
							('Asia/Yakutsk', '(GMT+9:00) Yakutsk '),
							('Asia/Seoul', '(GMT+9:00) Seoul '),
							('Asia/Tokyo', '(GMT+9:00) Osaka, Sapporo, Tokyo '),
							('Australia/Darwin', '(GMT+9:30) Darwin '),
							('Australia/Adelaide', '(GMT+9:30) Adelaide '),
							('Asia/Vladivostok', '(GMT+10:00) Vladivostok '),
							('Pacific/Port_Moresby', '(GMT+10:00) Guam, Port Moresby '),
							('Australia/Brisbane', '(GMT+10:00) Brisbane '),
							('Australia/Sydney', '(GMT+10:00) Canberra, Melbourne, Sydney '),
							('Australia/Hobart', '(GMT+10:00) Hobart '),
							('Asia/Magadan', '(GMT+10:00) Magadan '),
							('SST', '(GMT+11:00) Solomon Islands '),
							('Pacific/Noumea', '(GMT+11:00) New Caledonia '),
							('Asia/Kamchatka', '(GMT+12:00) Kamchatka '),
							('Pacific/Fiji', '(GMT+12:00) Fiji Islands, Marshall Islands '),
							('Pacific/Auckland', '(GMT+12:00) Auckland, Wellington')";	

				/* Create User Email Table */	
				$zoom_useremail_tblname = $wpdb->prefix .'mwa_zoom_email_users';	

				$user_email_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_useremail_tblname`(
								`id` INT(11) NOT NULL AUTO_INCREMENT,
								`username` varchar(255) NOT NULL,
								`email` text NOT NULL,
								`status` varchar(255) NOT NULL,
								PRIMARY KEY (`id`)
					)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

				/* Create Zoom Userinfo Table for host manage*/
				$zoom_userinfo_tblname = $wpdb->prefix .'mwa_zoom_userinfo';

				$create_zoomuserinfo_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_userinfo_tblname`(
						`id` INT(11) NOT NULL AUTO_INCREMENT,
						`authid` int(11) NOT NULL,
						`hostid` text NOT NULL,
						`acc_type` text NOT NULL,
						`first_name` text NOT NULL,
						`last_name` text NOT NULL,
						`email` text NOT NULL,
						`account_id` text NOT NULL,
						`role_name` text NOT NULL,
						`personal_meeting_url` text NOT NULL,
						`timezone` text NOT NULL,
						`host_key` text NOT NULL,						
						PRIMARY KEY (`id`)
					)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";

				/* Create table for attached data with created meeting  */				
				$zoom_meetdata_tblname = $wpdb->prefix .'mwa_zoom_meetdata';

				$create_meetdata_tbl = "CREATE TABLE IF NOT EXISTS `$zoom_meetdata_tblname`(
						`id` int(11) NOT NULL AUTO_INCREMENT,
						`meeting_id` text NOT NULL,
						`meetarrdata` text NOT NULL,						
						PRIMARY KEY (`id`)
					)DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;";			

				/*EXECUTE ALL QUERIES*/
				$wpdb->query( $create_zoomauth_tbl );
				$wpdb->query( $create_token_tbl );
				$wpdb->query( $host_tbl );
				$wpdb->query( $timezone_tbl );
				$wpdb->query( $insert_timezone );
				$wpdb->query( $user_email_tbl );
				$wpdb->query( $create_zoomuserinfo_tbl );
				$wpdb->query( $create_meetdata_tbl );

				$tbl_host_exist = "SELECT 1 FROM `$zoom_host_tblname` LIMIT 1";
				$tbl_auth_exist = "SELECT 1 FROM `$zoom_auth_tblname` LIMIT 1";
				$col_desc_host_exist = "SHOW COLUMNS FROM `$zoom_host_tblname` LIKE 'desc'";
				$col_usermail_auth_exist = "SHOW COLUMNS FROM `$zoom_auth_tblname` LIKE 'usermail'";
				$chk_table_host 	= $wpdb->query( $tbl_host_exist );
				$chk_table_auth 	= $wpdb->query( $tbl_auth_exist );

				/* Check Host Table Exist or not */
				if($chk_table_host !== FALSE)
				{	
					/* Check Host Table's desc COLUMN Exist or not */
					if($wpdb->query( $col_desc_host_exist )){
						
					}else{
						/*if column - 'desc' is not exist*/
						$col_desc_qry = "ALTER TABLE $zoom_host_tblname ADD `desc` VARCHAR( 255 ) NOT NULL after `topicname`";
						$wpdb->query( $col_desc_qry );
					}
				}
				else
				{
				    //I can't find it...
				}	

				/* Check Auth Table Exist or not */
				if($chk_table_auth !== FALSE)
				{	
					/* Check Auth Table's usermail COLUMN Exist or not */
					if($wpdb->query( $col_usermail_auth_exist )){
						
					}else{
						/*if column - 'usermail' is not exist*/
						$col_authid_qry = "ALTER TABLE $zoom_auth_tblname ADD `usermail` VARCHAR( 255 ) NOT NULL after `uac`";
						$wpdb->query( $col_authid_qry );
					}
				}
				else
				{
				    //I can't find it...
				}												
			}//end setupdb function
		}//end MWA_WPZ_Dbsetup class
	}// end if exist MWA_WPZ_Dbsetup class