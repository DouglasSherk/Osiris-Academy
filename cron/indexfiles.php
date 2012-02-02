<?php
	define( "__assetPath", "/var/www/html_megatrons" );

	set_include_path( __assetPath );
	require_once( "includes/config.php" );
	require_once( "includes/filereader.php" );

	function crawlDir( $dir_path ) {
	  $skipText = array( 
		"pdf",
		"zip",
		"rar",
        "7z",
        "epub"
	  );
		
	  $dir = opendir( $dir_path );
	  while ( false !== ( $file = readdir( $dir ) ) ) {
	    if ( $file == "." || $file == ".." ) {
	      continue;
	    }

	    $path = $dir_path . "/" . $file;

	    if ( is_dir( $path ) ) {
	      crawlDir( $path );
	    } else {
	      $text = file2text( $path );
	      if ( $text != "" || in_array( pathinfo( $path, PATHINFO_EXTENSION ), $skipText ) ) {
			echo $file;
			//list( $year, $month, $day, $title, $extension ) = sscanf( $file, "(%d-%d-%d) - %'x20s.%s" );
			$timestamp = strtok( $file, ' ' );
			$garbage = strtok( ' ' );
			$title = mysql_real_escape_string( strtok( '.' ) );
			$extension = strtok( '.' );
	        //echo nl2br( $text ) . "<br /> <br /> <br />";
			
            if ( strstr( $path, "package/" ) === false ) {
              if ( $title == "" || $title == null || strpos( $timestamp, '(' ) === false ) {
                echo '<br />Skipped';
                echo '<br /> <br />';
                continue;
              }
           	  $split = explode( __assetPath, $dir_path );
			  $garbage = strtok( $split[1], '/' );
			  $category = strtok( '/' );
			  $term = strtok( '/' );
			  $course = strtok( '/' );
			  $other = strtok( '/' );
            } else {
              $split = explode( __assetPath, $dir_path );
              $garbage = strtok( $split[1], '/' );
              $category = strtok( '/' );
              $title = $file;
              $term = strtok( $title, '.' );
              $extension = strtok( '.' );
              $course = "";
              $other = "";
            }
			
			echo '<br /> <br />';
						
			$storePath = ".." . $split[1] . "/" . $file;
			
			$categoryNum = 0;
			switch ( $category ) {
				case "notes" :
					if ( $other == "" || $other == "/" || $other == null ) {
						$categoryNum = 1;
					} else {
						$categoryNum = 4;
					}
					break;
				case "gold_pack" :
					$categoryNum = 2;
					break;
				case "books" :
					$categoryNum = 3;
					break;
				case "misc" :
					$categoryNum = 4;
					break;
                case "package" :
                    $categoryNum = 5;
                    break;
			}
			
			list( $year, $month, $day ) = sscanf( $timestamp, '(%d-%d-%d)' );
			// If it was "09" or something, add 2000 so it becomes 2009.
			if ( $year < 2000 ) { 
			  $year += 2000;
			}
			$timestamp = $year . '-' . $month . '-' . $day;
	        mysql_query( "INSERT INTO " . Database::$table_files . " VALUES( '" . mysql_real_escape_string( $storePath ) . "', '" . $timestamp . "', default, '" . $extension . "', '" . $title . "', '" . mysql_real_escape_string( $text ) . "', '" . $term . "', '" . $course . "', '" . $categoryNum . "' ) ON DUPLICATE KEY " .
			"UPDATE time_indexed = IF( time_indexed < FROM_UNIXTIME( " . filemtime( $path ) . " ), CURRENT_TIMESTAMP, time_indexed ), " .
			"plaintext = VALUES( plaintext );" )
	          or die( "Error: " . mysql_error() );
	      }
	    }
	  }
	}

	$db = Database::connect();

	crawlDir( __assetPath . "/files" );
	
	$result = mysql_query( "SELECT path FROM " . Database::$table_files . ";" )
	 or die( "Error: " . mysql_error() );
	while ( $row = mysql_fetch_assoc( $result ) ) {
	  $rel_path = $row['path'];
	  $path = str_replace( '..', __assetPath, $rel_path );
	  if ( !file_exists( $path ) ) {
	    echo "Deleting file index: $rel_path. <br/>";
	    mysql_query( "DELETE FROM " . Database::$table_files . " WHERE path='" . mysql_real_escape_string( $rel_path ) . "'" )
		  or die( "Error: " . mysql_error() );
	  }
	}
?>
