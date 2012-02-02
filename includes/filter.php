<script type="text/javascript">
  function resetCourse() {
    var filtersForm = document.getElementById( "course" );
    for ( var i = 0; i < filtersForm.options.length; i++ ) {
      if ( filtersForm.options[i].value == "All" ) {
        filtersForm.options[i].selected = true;
        return;
      }
    }
  }
</script>

<?php
  $course = $_GET['course'];
  $category = $_GET['category'];
  $term = $_GET['term'];
  // If they don't specify a term, default it to the one for the group most relevant to them.
  if ( $term == "" || $term == null ) {
    $term = Classes::inGroup( $facebook, $me );
  }
  
  $terms = array();
  $result = mysql_query( "SELECT DISTINCT term FROM " . Database::$table_files . " ORDER BY term DESC;" )
    or die( "Error: " . mysql_error() );
  while ( $row = mysql_fetch_assoc( $result ) ) {
    array_push( $terms, $row['term'] );
  }
  
  $courses = array();
  $result = mysql_query( "SELECT DISTINCT course FROM " . Database::$table_files . " WHERE term='" . mysql_real_escape_string( $term ) . "' ORDER BY course DESC;" )
    or die( "Error: " . mysql_error() );
  while ( $row = mysql_fetch_assoc( $result ) ) {
    array_push( $courses, $row['course'] );
  }
?>

<div id="filters">
  <form action="" method="GET" name="filters">
    <div id="filter-title">Filters</div>
    <input name="category" type="hidden" value="<?php echo $category ?>" />
    <div id="filter">
      Term:
      <select name="term" style="width: 200px;" onchange="resetCourse(); document['filters'].submit(); return false">
        <option>All</option>
      <?php
        foreach ( $terms as $listTerm ) {
	      $isDefault = $listTerm === $term ? "selected" : "";
	      echo "<option $isDefault>$listTerm</option>";
	    }
      ?>
      </select>
    </div>
    <div id="filter">
      Course:
      <select id="course" name="course" style="width: 200px;" onchange="document['filters'].submit(); return false">
        <option>All</option>
      <?php
        foreach ( $courses as $listCourse ) {
	      $isDefault = $listCourse === $course ? "selected" : "";
	      echo "<option $isDefault>$listCourse</option>";
	    }
      ?>
      </select>
    </div>
    <?php
      if ( isset( $search ) ) {
        echo '<input type="hidden" name="search" value="' . $search . '" />';
      }
    ?>
  </form>
</div>