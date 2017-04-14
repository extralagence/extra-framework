<?php
$functions_root     = EXTRA_PATH . '/setup/inc';
$functions_files    = scandir( $functions_root );
$functions_excluded = array(
	'.',
	'..'
);

foreach ( $functions_files as $functions_file ) {
	if ( is_file( $functions_root . '/' . $functions_file ) && ! in_array( $functions_file, $functions_excluded ) ) {
		$module_setup_file = $functions_root . '/' . $functions_file;
		if ( file_exists( $module_setup_file ) ) {
			require_once $module_setup_file;
		}
	}
}