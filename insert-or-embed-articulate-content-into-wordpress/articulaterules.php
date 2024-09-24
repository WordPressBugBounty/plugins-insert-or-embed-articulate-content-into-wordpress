<?php
function getFile( $dir, $return_detail_array = false ) {
	$indexFileName     = '';
	$otherHtmlFileName = '';
	$hasindex          = false;
	$hashtml           = false;
	$myDirectory       = opendir( $dir );
	$valid_file_found  = false;
	// get each entry
	while ( $entryName = readdir( $myDirectory ) ) {
		$f = getUploadsPath() . $entryName;
		if ( $entryName != '.' && $entryName != '..' && is_dir( $f ) == false ) {

			// need to get the filename without the extension
			$fname = pathinfo( $f, PATHINFO_FILENAME );
			// need the extension as well
			$ext = pathinfo( $f, PATHINFO_EXTENSION );
			// need to check the filename and only return Articulate
			if ( ( $fname == 'player' || $fname == 'story' || $fname == 'engage' || $fname == 'quiz' || $fname == 'presentation' || $fname == 'interaction' ) && $ext == 'html' ) :
					$valid_file_found = true;
				// check for Captivate
				elseif ( ( $fname == 'multiscreen' || $fname == 'index_AICC' || $fname == 'index_SCORM' || $fname == 'index_scorm' || $fname == 'index_aicc' || $fname == 'index_tincan' || $fname == 'index_TINCAN' ) && ( $ext == 'html' || $ext == 'htm' ) ) :
					$valid_file_found = true;
					// check for Elucidat
				elseif ( ( $fname == 'launch' ) && ( $ext == 'html' || $ext == 'htm' ) ) :
					$valid_file_found = true;
					// check for Pubcoder
				elseif ( ( $fname == 'content' ) && ( $ext == 'html' || $ext == 'htm' ) ) :
					$valid_file_found = true;
				elseif ( ( $fname == 'index' || $fname == 'INDEX' ) && ( $ext == 'html' || $ext == 'htm' ) ) :
					$hasindex      = true;
					$indexFileName = $entryName;

				elseif ( ( $ext == 'html' || $ext == 'htm' ) && ( $fname !== 'Close' ) ) :
					$hashtml           = true;
					$otherHtmlFileName = $entryName;
			endif;
		}
		if ( $valid_file_found ) {
			break;}
	}
	closedir( $myDirectory );
	$returnArr              = array();
	$returnArr['file_name'] = '';
	$returnArr['status']    = 'no_html_file_found';

	if ( $valid_file_found ) {
		$returnArr['file_name'] = $entryName;
		$returnArr['status']    = 'valid_html_file_found';
	} elseif ( $hasindex == true ) {
		$returnArr['file_name'] = $indexFileName;
		$returnArr['status']    = 'index_html_file_found';
	} elseif ( $hashtml == true ) {
		$returnArr['file_name'] = $otherHtmlFileName;
		$returnArr['status']    = 'other_html_file_found';
	} elseif ( file_exists( $dir . '/' . 'scormcontent/index.html' ) ) {
		$returnArr['file_name'] = 'scormcontent/index.html';
		$returnArr['status']    = 'valid_html_file_found';
	} elseif ( file_exists( $dir . '/' . 'res/index.html' ) ) {
		$returnArr['file_name'] = 'res/index.html';
		$returnArr['status']    = 'valid_html_file_found';
	}

	if ( $return_detail_array ) {
		return $returnArr;
	} else {
		return $returnArr['file_name'];
	}

}
