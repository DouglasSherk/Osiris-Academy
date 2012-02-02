                      <form action="search.php" method="GET">
					    <?php 
						  if ( isset( $term ) ) {
						    echo '<input type="hidden" name="term" value="' . $term . '" />';
						  }
						  if ( isset( $course ) ) {
						    echo '<input type="hidden" name="course" value="' . $course . '" />';
						  }
						?>
                        <input type="text" id="searchtext" name="search">
                        <input type="image" value="submit" src="img/search.png" width="20" height="24" border="0" alt="Submit" name="image"> 
                      </form>