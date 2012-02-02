<?php
    class FBLogin {
        public static $appId = '129338740471924';
        public static $secret = '';
    }

	class General {
		public static $name = "Megatrons";
		public static $contactEmail = "contact@site.com";
	}

	class Users {
		// Users who are banned from using this entire site.
		// They will see a banned message when they try to log in.
		public static $banned = array( 
			"123123123",
		);
		
		// Admins are people who can add new notes and delete comments.
		public static $admin = array(
			"512769141", 		// Doug
		);

		// This is a list of people allowed to see content even if they're
		// not a member of any of the whitelisted Facebook groups. It maps
		// them to a term that they should be spoofed as part of.
		public static $whitelist = array(
			"999911111" => "3B",
		);
	}

	class Database {
		// This is the hostname of your server
		static $hostname = "127.0.0.1";
		// This is the password to the account listed in $username
		static $password = "";
		// This is the account to log into SQL with
		static $username = "root";
		// This is the database where all the clan API stuff is stored
		static $database = "megatrons";

		public static function connect() {
			$temp_db = mysql_connect( self::$hostname, self::$username, self::$password ) or die( "Could not connect to database:" . mysql_error() );
			mysql_select_db( self::$database, $temp_db ) or die( "Database does not exist: " . mysql_error() );

			return $temp_db;
		}

		public static $table_users = "mn_users";
		public static $table_motd = "mn_motd";
		public static $table_files = "mn_files";
		public static $table_stats = "mn_stats";
		public static $table_mobile = "mn_mobile";
	}
	
	class Classes {
		public static $classes = array( 
			"4B" => "",
			"4A" => "",
			"3B" => "138685252848607",  // 2013
			"3A" => "97962230954",      // 2014
			"2B" => "",	
			"2A" => "115231971847182",  // 2015
			"1B" => "",
			"1A" => ""
		);

		public static $calendars = array( 
			"4B" => "",
			"4A" => "",
			"3B" => "https://www.google.com/calendar/feeds/bsvvl5f44c128u3h6vcdtu3plc%40group.calendar.google.com/public/basic",
			"3A" => "https://www.google.com/calendar/feeds/mechatronics2014%40gmail.com/public/basic",
			"2B" => "",
			"2A" => "",
			"1B" =>	"",
			"1A" => ""
		);
		
		public static function inGroup( $fb, $user ) {
            if (isset($_SESSION['class'])) {
                return $_SESSION['class'];
            }

			$class = Users::$whitelist[$fb->getUser()];
			if ( $class != null && $class != "" ) {
                $_SESSION['class'] = $class;
				return $class;
			}

            foreach ( self::$classes as $class => $groupId ) {
				if ( $groupId != "" ) {
                    $groups = $fb->getGroups();
					if ( $groups && !empty( $groups ) && $groups['data'] && !empty( $groups['data'] ) ) {
                        foreach ( $groups['data'] as $group ) {	
							if ( $group['id'] == $groupId ) {
                                $_SESSION['class'] = $class;
								return $class;
							}
						}
					}
				}
			}
			return null;
		}
	}

	// stupid hack to get around PHP's limitations
	define( "__server", "http://megatrons.ca" );
	define( "__assets", "/" );
	define( "__root", "/var/www/html_megatrons" );
?>
