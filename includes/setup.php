<?php
	require_once( 'config.php' );
	$db = Database::connect();
	
	mysql_query( "CREATE TABLE IF NOT EXISTS " . Database::$table_motd . " ( `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `text` TEXT, `uid` VARCHAR( 65 ) );" )
	  or die( "Error: " . mysql_error() );

	mysql_query( "CREATE TABLE IF NOT EXISTS " . Database::$table_files . " ( `path` VARCHAR( 256 ), `time_created` DATETIME, `time_indexed` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, `extension` TEXT, `title` TEXT, `plaintext` TEXT, `term` TEXT, `course` TEXT, `category` INT( 11 ) DEFAULT 1, PRIMARY KEY( `path` ) );" )
	  or die( "Error: " . mysql_error() );
	
	mysql_query( "CREATE TABLE IF NOT EXISTS " . Database::$table_stats . " ( `counter` VARCHAR( 128 ), `kingdom` VARCHAR( 128 ), `phylum` VARCHAR( 128 ), `class` VARCHAR( 128 ), `family` VARCHAR( 128 ), `genus` VARCHAR( 128 ), `value` INT( 32 ), `time` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY( counter, kingdom, phylum, class, family, genus, time ) );" )
	  or die( "Error: " . mysql_error() );
	  
	mysql_query( "CREATE TABLE IF NOT EXISTS " . Database::$table_users . " ( `uid` VARCHAR( 65 ), `name` TEXT, `email` TEXT, last_login TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY( uid ) );" )
	  or die( "Error: " . mysql_error() );

	mysql_query( "CREATE TABLE IF NOT EXISTS " . Database::$table_mobile . " ( `ip` VARCHAR( 20 ), `term` VARCHAR( 6 ), `last_login` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY( ip ) );" )
	  or die( "Error: " . mysql_error() );
?>
