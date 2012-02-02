<?php
	require_once( "docx.php" );
	require_once( "pdf.php" );
	require_once( "doc.php" );

	function file2text( $path ) {
	  $extension = pathinfo( $path, PATHINFO_EXTENSION );
	  if ( $extension == 'pdf' ) {
	    //$text = pdf2txt( $path );
	    //$text = substr( $text, 0, stripos( $text, 'AdobeUCS' ) );
	    //$text = substr( $text, 0, stripos( $text, '&%$' ) );
	    //return $text;
	    // disabled until memory issues resolved
	  } else if ( $extension == 'docx' ) {
	    return docx2text( $path );
	  } else if ( $extension == 'doc' ) {
	    return parseWord( $path );
	  }
	  return "";
	}
?>
