<?php
	class StatsManager {
		public static function poll( $counter, $kingdom = "", $phylum = "", $class = "", $family = "", $genus = "", $value = 1 ) {
			$counter = mysql_real_escape_string( $counter );
			$kingdom = mysql_real_escape_string( $kingdom );
			$phylum = mysql_real_escape_string( $phylum );
			$class = mysql_real_escape_string( $class );
			$family = mysql_real_escape_string( $family );
			$genus = mysql_real_escape_string( $genus );
			mysql_query( "INSERT INTO " . Database::$table_stats . " VALUES( '$counter', '$kingdom', '$phylum', '$class', '$family', '$genus', '1', default ) " .
				"ON DUPLICATE KEY UPDATE value = value + 1;" )
				or die( "Error: " . mysql_error() );
		}
		
		public static function sum( $counter, $kingdom = "", $phylum = "", $class = "", $family = "", $genus = "", $value = 0 ) {
			$counter = mysql_real_escape_string( $counter );
			$kingdom = mysql_real_escape_string( $kingdom );
			$phylum = mysql_real_escape_string( $phylum );
			$class = mysql_real_escape_string( $class );
			$family = mysql_real_escape_string( $family );
			$genus = mysql_real_escape_string( $genus );
			
			$query = "SELECT COUNT( * ) FROM " . Database::$table_stats;
			if ( $counter != "" || $kingdom != "" || $phylum != "" || $class != "" || $family != "" || $genus != "" || $value != 0 ) {
				$query .= " WHERE ";
				$and = false;
				
				if ( $counter != "" ) {
					$query .= "counter='$counter'";
					$and = true;
				}
				
				if ( $kingdom != "" ) {
					if ( $and ) {
						$query .= " AND ";
					}
					
					$query .= "kingdom='$kingdom'";
					$and = true;
				}
				
				if ( $phylum != "" ) {
					if ( $and ) {
						$query .= " AND ";
					}
					
					$query .= "phylum='$phylum'";
					$and = true;
				}
				
				if ( $class != "" ) {
					if ( $and ) {
						$query .= " AND ";
					}
					
					$query .= "class='$class'";
					$and = true;
				}
				
				if ( $family != "" ) {
					if ( $and ) {
						$query .= " AND ";
					}
					
					$query .= "family='$family'";
					$and = true;
				}
				
				if ( $genus != "" ) {
					if ( $and ) {
						$query .= " AND ";
					}
					
					$query .= "genus='$genus'";
					$and = true;
				}
				
				if ( $value != "" ) {
					if ( $and ) {
						$query .= " AND ";
					}
					
					$query .= "value='$value'";
					$and = true;
				}
			}
			
			$query .= ";";
			
			$result = mysql_query( $query ) or die( "Error: " . mysql_error() );
			$row = mysql_fetch_assoc( $result );
			
			return $row['COUNT( * )'];
		}
	}
?>