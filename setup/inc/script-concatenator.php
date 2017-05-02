<?php
/**
 * @param $folder_path : the path to the folder containing the files to concatenate
 * @param $file_path   : the path to the file to create
 */
function extra_script_concatenator( $folder_path, $file_path ) {
	$folder_files       = scandir( $folder_path );
	$functions_excluded = array(
		'.',
		'..',
		'Icon',
		'.DS_Store'
	);

	$file_content = '';
//	var_dump($folder_files);
	foreach ( $folder_files as $folder_file ) {
		if ( is_file( $folder_path . '/' . $folder_file ) && ! in_array( $folder_file, $functions_excluded ) ) {
			$file_content .= file_get_contents( $folder_path . '/' . $folder_file );
		}
	}
	$fp = fopen( $file_path, "wb" );
	fwrite( $fp, $file_content );
	fclose( $fp );
}