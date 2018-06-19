<?php
/*
 	Copyright (C) 2015-18 CERBER TECH INC., http://cerber.tech
    Copyright (C) 2015-18 Gregory Markov, https://wpcerber.com

    Licenced under the GNU GPL.

    This program is free software; you can redistribute it and/or modify
    it under the terms of the GNU General Public License as published by
    the Free Software Foundation; either version 3 of the License, or
    (at your option) any later version.

    This program is distributed in the hope that it will be useful,
    but WITHOUT ANY WARRANTY; without even the implied warranty of
    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
    GNU General Public License for more details.

    You should have received a copy of the GNU General Public License
    along with this program; if not, write to the Free Software
    Foundation, Inc., 51 Franklin St, Fifth Floor, Boston, MA  02110-1301  USA

*/

/*

*========================================================================*
|                                                                        |
|	       ATTENTION!  Do not change or edit this file!                  |
|                                                                        |
*========================================================================*

*/

define( 'CERBER_FT_WP', 1 );
define( 'CERBER_FT_PLUGIN', 2 );
define( 'CERBER_FT_THEME', 3 );
define( 'CERBER_FT_ROOT', 4 );
define( 'CERBER_FT_UPLOAD', 5 );
define( 'CERBER_FT_LNG', 6 );
define( 'CERBER_FT_MUP', 7 );
define( 'CERBER_FT_CNT', 8 );
define( 'CERBER_FT_CONF', 10 );
define( 'CERBER_FT_DRIN', 11 );
define( 'CERBER_FT_OTHER', 12 );

define( 'CERBER_MAX_SECONDS', 5 );

define( 'CERBER_SCF', 16 );
define( 'CERBER_PMC', 17 );
define( 'CERBER_USF', 18 );
define( 'CERBER_EXC', 20 );
define( 'CERBER_UXT', 30 );

define( 'CERBER_MALWR_DETECTED', 1000 );

define( 'CRB_HASH_THEME', 'hash_tm_' );
define( 'CRB_HASH_PLUGIN', 'hash_pl_' );
define( 'CRB_LAST_FILE', 'tmp_last_file' );

function cerber_integrity_page() {

	$tab = cerber_get_tab( 'scanner', array( 'scanner', 'scan_settings', 'help' ) );

	?>
    <div class="wrap crb-admin">

        <h2><?php _e( 'Site Integrity', 'wp-cerber' ) ?></h2>

        <h2 class="nav-tab-wrapper cerber-tabs">
			<?php

			echo '<a href="' . cerber_admin_link( 'scanner' ) . '" class="nav-tab ' . ( $tab == 'scanner' ? 'nav-tab-active' : '' ) . '"><span class="dashicons dashicons-visibility"></span> ' . __( 'Security Scanner' ) . '</a>';
			echo '<a href="' . cerber_admin_link( 'scan_settings' ) . '" class="nav-tab ' . ( $tab == 'scan_settings' ? 'nav-tab-active' : '' ) . '"><span class="dashicons dashicons-admin-settings"></span> ' . __( 'Settings' ) . '</a>';
			echo '<a href="' . cerber_admin_link( 'help', array( 'page' => cerber_get_admin_page() ) ) . '" class="nav-tab ' . ( $tab == 'help' ? 'nav-tab-active' : '' ) . '"><span class="dashicons dashicons-editor-help"></span> ' . __( 'Help', 'wp-cerber' ) . '</a>';

			echo lab_indicator();
			?>
        </h2>

		<?php

		cerber_show_aside( $tab );

		echo '<div class="crb-main">';

		switch ( $tab ) {
			case 'scan_settings':
				cerber_show_settings_page( 'scanner' );
				break;
			case 'help':
				cerber_show_help();
				break;
			default:
				cerber_show_scanner();
		}

		echo '</div>';

		?>

    </div>
	<?php
}

function cerber_show_scanner() {
    // http://www.adequatelygood.com/JavaScript-Module-Pattern-In-Depth.html

	$msg      = '';
	$status   = 0;

	if ( $scan = cerber_get_scan() ) {
		if ( ! $scan['finished'] ) {
			if ( $scan['cloud'] ) {
				$msg    = 'Currently an automatic scan in progress. Please wait until it is finished.';
				$status = 1;
			}
			else {
				$msg    = sprintf( 'Previous scan started %s has not been completed. Continue scanning?', cerber_date( $scan['started'] ) );
				$status = 2;
			}
		}
		else {

		}
	}
	else {
		$msg = 'It seems this website has never been scanned. To start scanning click the button below.';
	}

	$start_quick = '<input data-control="start_scan" data-mode="quick" type="button" value="' . __( 'Start Quick Scan', 'wp-cerber' ) . '" class="button button-primary">';
	$start_full  = '<input data-control="start_scan" data-mode="full" type="button" value="' . __( 'Start Full Scan', 'wp-cerber' ) . '" class="button button-primary">';
	$stop        = '<input id="crb-stop-scan" style="display: none;" data-control="stop_scan" type="button" value="' . __( 'Stop Scanning', 'wp-cerber' ) . '" class="button button-primary">';
	$continue    = '<input id="crb-continue-scan" data-control="continue_scan" type="button" value="' . __( 'Continues Scanning', 'wp-cerber' ) . '" class="button button-primary">';
	$controls    = '';

	switch ( $status ) {
		case 0:
			$controls = $start_quick . $start_full;
			break;
		case 1:
			$controls = '';
			break;
		case 2:
			$controls = $start_quick . $start_full . $continue;
			break;
	}

	$controls .= $stop;


	echo '<div id="crb-scanner">';

	cerber_scanner_dashboard( $msg );

	?>
    <div id="crb-scan-area">
        <form>
            <table id="crb-scan-controls">
                <tr>
                    <td id="crb-file-controls"><input data-control="delete_file" type="button"
                                                      class="button button-secondary"
                                                      value="<?php _e( 'Delete', 'wp-cerber' ); ?>"/></td>
                    <td>
						<?php echo $controls; ?>
                    </td>
                    <td><a href="#" data-control="full-paths">Show full paths</a></td>
                </tr>
            </table>
        </form>
    </div>

    <?php

	echo '</div>';
}

add_action( 'wp_ajax_cerber_scan_control', 'cerber_manual_scan' );
function cerber_manual_scan() {
	global $cerber_db_errors;

	cerber_check_ajax();

	ob_start(); // Collecting possible junk warnings and notices cause we need clean JSON to be sent

	$response    = array();
	$console_log = array();
	$do          = 'stop';

	if ( cerber_is_http_post() && isset( $_POST['cerber_scan_do'] ) ) {
		$do = preg_replace( '/[^a-z_\-\d]/i', '', $_POST['cerber_scan_do'] );
		$mode = ( isset( $_POST['cerber_scan_mode'] ) ) ? preg_replace( '/[^a-z_\-\d]/i', '', $_POST['cerber_scan_mode'] ) : 'quick';

		$response = cerber_scanner( $do, $mode );

	}
	else {
		$console_log[] = 'Unknown HTTP request';
	}

	if ( ! empty( $response['cerber_scan_do'] ) ) {
		$do = $response['cerber_scan_do'];
	}

	if ( $cerber_db_errors ) {
		$console_log = array_merge( $console_log, $cerber_db_errors );
	}

	$console_log[] = 'PHP MEMORY ' . @ini_get( 'memory_limit' );

	ob_end_clean();

	echo json_encode( array(
		'console_log'     => $console_log,
		'cerber_scan_do' => $do,
		'cerber_scanner' => $response,
		//'scan'           => cerber_get_scan(), // debug only
	) );

	wp_die();
}

function cerber_scanner( $control, $mode ) {
	global $cerber_db_errors, $cerber_scan_mode;

	$errors = array();

	if ( function_exists( 'wp_raise_memory_limit' ) ) {
		wp_raise_memory_limit( 'admin' );
	}
	else {
	    $errors[] = 'Unable to raise memory limit';
    }

	if ( ! $mode ) {
		$mode = 'quick';
	}

	$cerber_scan_mode = $mode;
	$status = null;
	$response = array();

	switch ( $control ) {
		case 'start_scan':
			cerber_delete_scan();
			cerber_update_set( CRB_LAST_FILE, '', 0, false );
			cerber_init_scan( $mode );
			cerber_step_scanning();
			break;
		case 'continue_scan':
			if ( $scan = cerber_get_scan() ) {
				$cerber_scan_mode = $scan['mode'];
				cerber_step_scanning();
			}
			else {
				$errors[] = 'No scan in progress';
            }
			break;
        case 'get-last-scan':
	        if ($scan = cerber_get_scan()) {
		        $filtered = $scan['issues'];
		        foreach ( $scan['issues'] as $key => $item ) {
			        if ( isset( $item['issues'] ) ) {
				        foreach ( $item['issues'] as $id => $issue ) {
					        if ( isset( $issue['data']['name'] ) ) {
						        if ( ! file_exists( $issue['data']['name'] ) ) {
							        unset( $filtered[ $key ]['issues'][ $id ] );
						        }
					        }
				        }
			        }
		        }
		        $response['issues'] = $filtered;
	        }
            break;
	}

	if ($scan = cerber_get_scan()) {

		$response['scan_id'] = $scan['id'];

		if ( $scan['finished'] || $scan['aborted'] ) {
			$response['cerber_scan_do'] = 'stop';
		}
		else {
			$response['cerber_scan_do'] = 'continue_scan';
		}

		$response['aborted'] = $scan['aborted'];
		$response['errors'] = array_merge( $errors, $scan['errors'] );
		$response['total'] = $scan['total'];
		//$response['memory_usage'] = memory_get_usage();
		//$response['memory_limit'] = @ini_get( 'memory_limit' );

		if ( ! lab_is_cloud_request() ) {
			$response['step_issues'] = $scan['step_issues'];
			$response['scanned']     = $scan['scanned'];

			$response['started']  = cerber_date( $scan['started'] );
			$response['elapsed'] = time() - $scan['started'];
			$duration = $response['elapsed'];

			$response['finished'] = '';
			$response['duration'] = '';

			if ( $scan['finished'] ) {
				$response['finished'] = cerber_date( $scan['finished'] );
				$duration = $scan['finished'] - $scan['started'];
				$response['step'] = '';
			}
			else {
				$response['step'] = $scan['next_step'];
            }

			if ( $duration < 60 ) {
				$response['duration'] = $duration . ' seconds';
			}
			else {
				$response['duration'] = round( $duration / 60, 2 ) . ' minutes';
			}

			if ( $duration && ! empty( $scan['scanned']['bytes'] ) ) {
				$response['performance'] = number_format( round( ( $scan['scanned']['bytes'] / $duration ) / 1024, 0 ), 0, '.', ' ' );
			}
			else {
				$response['performance'] = 0;
			}
			$response['performance'] .= ' KB/sec';

		}
	}

	if ( $cerber_db_errors ) {
		cerber_watchdog( true );
	}

	return $response;
}

function cerber_show_last_scan_results() {
	global $wpdb;

	$rows = $wpdb->get_results( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_status = 1 AND hash_match !=1' );
	if ( ! $rows ) {
		return 0;
	}
	echo '<h3>Last scan results</h3>';
	echo '<pre style="background-color: #fff; text-align: left;">';
	foreach ( $rows as $row ) {
		$mime = wp_check_filetype( $row->file_name );
		echo $row->file_name . ' ' . $mime['mode'] . ' ' . $row->file_hash_repo . "\n";
	}
	echo '</pre>';
}

function cerber_step_scanning() {
    global $cerber_scan_mode;

	ignore_user_abort( true );

	cerber_exec_timer();

	cerber_update_scan( array( 'step_issues' => array() ) );

	if ( ! $scan = cerber_get_scan() ) {
		return false;
	}

	if ( $scan['finished'] ) {
		return true;
	}

	$update = array();
	$update['next_step'] = $scan['next_step'];
	$update['aborted'] = 0;


	switch ( $scan['next_step'] ) {
		case 1:
			if ( !$result = cerber_scan_directory( ABSPATH, null, '_crb_save_file_names' ) ) {
				$update['aborted'] = 1;
				break;
			}
			else {
				$update['total']['folders'] = $result[0];
				_crb_save_file_names( array( dirname( ABSPATH ) . DIRECTORY_SEPARATOR . 'wp-config.php' ) );

				if ( crb_get_settings( 'scan_tmp' ) ) {
					$tmp_dir = @ini_get( 'upload_tmp_dir' );
					if ( is_dir( $tmp_dir ) && $result = cerber_scan_directory( $tmp_dir, null, '_crb_save_file_names' ) ) {
						$update['total']['folders'] += $result[0];
					}
					$another_dir = sys_get_temp_dir();
					if ( $another_dir !== $tmp_dir && @is_dir( $another_dir ) && $result = cerber_scan_directory( $another_dir, null, '_crb_save_file_names' ) ) {
						$update['total']['folders'] += $result[0];
					}
				}
				if ( crb_get_settings( 'scan_sess' ) ) {
					$another_dir = session_save_path();
					if ( is_dir( $another_dir ) && $result = cerber_scan_directory( $another_dir, null, '_crb_save_file_names' ) ) {
						$update['total']['folders'] += $result[0];
					}
				}

				$update['total']['files'] = cerber_db_get_var( 'SELECT COUNT(scan_id) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] );
				$update['next_step'] ++;
			}
			break;
		case 2:
			//$start = time();
			$x = 0;
			$exceed = false;
			if ( $result = cerber_db_get_results( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] . ' AND file_hash = ""' ) ) {
				foreach ( $result as $row ) {
					if ( ! cerber_update_file_info( $row ) ) {
						cerber_log_scan_error( 'Unable to update file info. Scanning has been aborted.' );
						$update['aborted'] = 1;
						break;
					}
					if ( 0 === ($x % 100) ) {
						//if ( ( time() - $start ) > CERBER_MAX_SECONDS ) {
						if ( cerber_exec_timer() ) {
						    $exceed = true;
							break;
						}
					}
					$x++;
				}
				// Some files might be symlinks
				$update['total']['files'] = cerber_db_get_var( 'SELECT COUNT(scan_id) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] );
				$update['total']['parsed'] = cerber_db_get_var( 'SELECT COUNT(scan_id) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] . ' AND file_type !=0' );
			}
			else {
				$update['aborted'] = 1;
            }
			if ( ! $exceed && ! $update['aborted'] ) {
				$update['next_step'] ++;
			}
			break;
		case 3:
			cerber_verify_wp();
			$update['next_step'] ++;
            break;
		case 4:
			$remain = cerber_verify_plugins();
			if ( ! $remain ) {
				$update['next_step'] ++;
			}
			break;
		case 5:
			$remain = cerber_verify_themes();
			if ( ! $remain ) {
				$update['next_step'] ++;
			}
			break;
		case 6:
			$remain = cerber_process_files();
			if ( ! $remain ) {
				$update['next_step'] ++;
			}
			break;
	}

	if ( $update['next_step'] > 6 ) {
		$update['finished'] = time();
	}

	if ( $update['aborted'] ) {
		$update['aborted'] = time();
	}

	$update['scanned']['files'] = cerber_db_get_var( 'SELECT COUNT(scan_id) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] . ' AND scan_status > 0' );
	$update['scanned']['bytes'] = cerber_db_get_var( 'SELECT SUM(file_size) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] . ' AND scan_status > 0' );

	if ( ! $scan = cerber_get_scan() ) {
		return false;
	}

	$update['issues'] = cerber_merge_issues( $scan['issues'], $scan['step_issues'] );

	return cerber_update_scan( $update );

}

/**
 * Initialize data structure for a new Scan
 *
 * @param string $mode  quick|fool
 *
 * @return array|bool
 */
function cerber_init_scan( $mode = 'quick' ) {
	cerber_delete_scan();

	if ( ! $mode ) {
		$mode = 'quick';
    }

	$data              = array();
	$data['mode']      = $mode;     // Quick | Full
	$data['id']        = time();
	$data['started']   = $data['id'];
	$data['finished']  = 0;
	$data['aborted']   = 0;         // If > 0, the scan has been aborted due to unrecoverable errors
	$data['errors']    = array();   // Any software, DB, CURL, I/O and other system errors - for diagnostic/debugging
	$data['scanned']   = array();
	$data['issues']    = array();   // The list of issues found during the scanning (for end user)
	$data['total']     = array();    // Counters
	$data['total']['issues'] = 0;
	$data['integrity']   = array();
	$data['ip']        = cerber_get_remote_ip();
	$data['cloud']     = lab_is_cloud_request();
	$data['step']      = array();
	$data['next_step'] = 1;

	if ( ! cerber_update_set( 'scan', $data, $data['id'] ) ) {

		return false;
	}

	return $data;
}

/**
 * Return ID for the Scan in progress (the latest scan started)
 *
 * @return bool|integer Scan ID false if no scan in progress (no files to scan)
 */
function cerber_get_scan_id() {

	$scan = cerber_get_scan();

	if ( $scan ) {
		$scan_id = absint( $scan['id'] );
	}
	else {
		$scan_id = false;
	}

	return $scan_id;
}

/**
 * Return Scan data
 *
 * @param integer $scan_id if not specified the last Scan data is returned
 *
 * @return array|bool
 */
function cerber_get_scan( $scan_id = null ) {
    global $wpdb;

    // If no ID is specified look for the latest one
	if ( $scan_id === null && $all = $wpdb->get_col( 'SELECT the_id FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE the_key = "scan"' ) ) {
		$scan_id = max( $all );
	}

	if ( ! $scan_id ) {
		return false;
	}

	return cerber_get_set( 'scan', $scan_id );

}

/**
 * Save issues (for end user reporting) during the scanning
 *
 * @param string $section
 * @param array $issues
 * @param string $container Top level container for the section
 *
 * @return bool
 */
function cerber_push_issues( $section, $issues = array(), $container = '' ) {
	if ( empty( $issues ) || empty( $section ) ) {
		return false;
	}

	$list = array();

	// Add some details

	$setype = 0;

	foreach ( $issues as $issue ) {

		$data = array();

		if ( isset( $issue['file'] ) ) {

		    $file = $issue['file'];
			$fsize        = $file['file_size'];
			$data['size'] = ( $fsize < 1024 ) ? $fsize . ' Bytes' : size_format( $fsize );
			$ftime        = $file['file_mtime'];
			$data['time'] = cerber_auto_date( $ftime );
			$data['name'] = $file['file_name'];
			$data['type'] = $file['file_type'];

			// Is file can be deleted safely

			$allowed = 0;
			if ( $file['file_type'] != CERBER_FT_CONF ) {
				if ( ! empty( $file['fd_allowed'] ) ) {
					if ( cerber_can_be_deleted( $file['file_name'] ) ) {
						$allowed = 1;
					}
				}
			}

			$data['fd_allowed'] = $allowed;

		}
        elseif ( isset( $issue['plugin'] ) ) {
			$data['version'] = $issue['plugin']['Version'];
	        $setype = 3;
		}
        elseif ( isset( $issue['theme'] ) ) {
			$data['version'] = $issue['theme']->get('Version');
	        $setype = 2;
		}
        elseif ( isset( $issue['wordpress'] ) ) {
	        $data['version'] = $issue['wordpress'];
	        $setype = 1;
        }

		$issue_type = $issue[0];
		$details = ( isset( $issue[2] ) ) ? $issue[2] : '';
		$short_name = ( isset( $issue[1] ) ) ? $issue[1] : '';

		$list[] = array(
			$issue_type, // Type of issue
			$short_name, // Object name
			cerber_calculate_risk( $issue ),
			'data'    => $data,
			'details' => $details,
		);
	}

	// Some stuff for better end-user report displaying

	if ( $section == 'WordPress' ) {
		$container = 'crb-wordpress';
	}
	if ( $section == 'Uploads folder' ) {
		$setype = 20;
	}
	if ( $section == 'Unattended files' ) {
		$container = 'crb-unattended';
		$setype = 21;
	}

	// TODO: $container Should be refactored

	if ( ! $container ) {
		//$container = sha1( $section );
		if ( isset( $issues[0]['file'] ) ) {
			switch ( $issues[0]['file']['file_type'] ) {
				case CERBER_FT_WP:
				case CERBER_FT_CONF:
					$container = 'crb-wordpress';
					break;
				case CERBER_FT_PLUGIN:
					$container = 'crb-plugins';
					break;
				case CERBER_FT_THEME:
					$container = 'crb-themes';
					break;
				case CERBER_FT_UPLOAD:
					$container = 'crb-uploads';
					break;
                case CERBER_FT_MUP:
	                $container = 'crb-muplugins';
	                break;
				case CERBER_FT_DRIN:
					$container = 'crb-dropins';
					break;
				default:
					$container = 'crb-unattended';
			}
		}
		else {
			if ( $section == 'WordPress' ) {
				$container = 'crb-wordpress';
			}
		}
	}

	if (!$container) {
	    $container = 'crb-unattended';
		$setype = 2;
	}


	// Save all

    // TODO: save section issues as a separate row with cerber_update_set()

	$id = sha1( $section );

	$scan = cerber_get_scan();

	$scan['step_issues'] = cerber_merge_issues( $scan['step_issues'], array(
		$id =>
			array(
				'name'      => $section,
				'container' => $container,
				'setype'    => $setype,
				'issues'    => $list,
			)
	) );

	$ret = cerber_update_scan( $scan );

	if ( ! $ret ) {
		cerber_log_scan_error( 'Unable to save the list of issues!' );
	}

	return $ret;
}

/**
 * Indicator for end-user
 *
 * @param $issue
 *
 * @return int|mixed
 */
function cerber_calculate_risk( $issue ) {
	$risk = array( 1 => 0, 10 => 1, 11 => 2, 5 => 3, 6 => 3, 7 => 3, 8 => 3);

	if ( isset( $risk[ $issue[0] ] ) ) {
		return $risk[ $issue[0] ];
	}

	$file = $issue['file'];

	// Small junk files?
	$size_factor = null;
	if ( isset( $file['file_size'] ) ) {
		if ( $file['file_size'] < 10 ) {
			$size_factor = 1;
		}
		elseif ( $file['file_size'] < 30 ) {
			$size_factor = 2;
		}
	}

	// TODO: convert into a formula with metrics
	switch ( $issue[0] ) {
		case 14:
			if ( $size_factor ) {
				return $size_factor;
			}
			return 2;
			break;
		case CERBER_EXC:
			if ( $size_factor ) {
				return $size_factor;
			}
			if ( $file['file_type'] == CERBER_FT_UPLOAD ) {
				return 3;
			}
			return 2;
			break;
		case 15:
		case CERBER_USF:
		case CERBER_SCF:
		case CERBER_PMC:
			if ( $size_factor ) {
				return $size_factor;
			}

			if ( ! cerber_detect_exec_extension( $file['file_name'], array( 'js', 'inc' ) ) ) {
				return 2;
			}

			return 3;
			break;
	}

	return 1;
}

function cerber_get_risk_desc() {
	return array(
		'',
		'Low',
		'Medium',
		'High',
	);
}

function cerber_get_issue_desc( $id = null ) {
	$issues = array(
		0 => 'To be scanned',
		1 => __( 'Verified' ),
		5 => __( 'Integrity data not found', 'wp-cerber' ),
		6 => __( 'Unable to check the integrity of the plugin due to a network error', 'wp-cerber' ),
		7 => __( 'Unable to check the integrity of WordPress files due to a network error', 'wp-cerber' ),
		8 => __( 'Unable to check the integrity of the theme due to a network error', 'wp-cerber' ),

		10         => __( "Local file doesn't exist", 'wp-cerber' ),
		11         => 'No local hash found',
		13         => __( 'Unable to process file', 'wp-cerber' ),
		14         => __( 'Unable to open file', 'wp-cerber' ),
		15         => __( 'Content has been modified', 'wp-cerber' ),

        CERBER_SCF => __( 'Suspicious code found', 'wp-cerber' ),
		CERBER_PMC => __( 'Potentially malicious code found', 'wp-cerber' ),
		CERBER_USF => __( 'Unattended suspicious file', 'wp-cerber' ),
		CERBER_EXC => __( 'Executable code found', 'wp-cerber' ),

        CERBER_UXT => __( 'Unwanted extension', 'wp-cerber' ),
	);

	if ( $id !== null ) {
		return $issues[ $id ];
	}

	return $issues;
}


/**
 * Merge two lists of issues in a correct way
 *
 * @param $issues1
 * @param $issues2
 *
 * @return array
 */
function cerber_merge_issues( $issues1, $issues2 ) {
	if ( ! $issues1 ) {
		$issues1 = array();
	}
	foreach ( $issues2 as $id => $item ) {
		if ( ! isset( $issues1[ $id ] ) ) {
			//$issues1[ $id ] = array( 'name' => $item['name'], 'issues' => $item['issues'] );
			$issues1[ $id ] = $item;
		}
		else {
			$issues1[ $id ]['issues'] = array_merge( $issues1[ $id ]['issues'], $item['issues'] );
		}
	}

	return $issues1;
}

/**
 * Update scan data by simply merging values in array
 *
 * @param array $new_data
 *
 * @return bool
 */
function cerber_update_scan( $new_data ) {
	if ( ! $old_data = cerber_get_scan() ) {
		return false;
	}

	if ( isset( $new_data['id'] ) ) {
		unset( $new_data['id'] );
	}
	$data = array_merge( $old_data, $new_data );

	return cerber_update_set( 'scan', $data, $old_data['id'] );
}

/**
 * Update scan data and preserve existing keys in array (scan structure)
 *
 * @param array $new_data
 *
 * @return bool
 */
function cerber_set_scan( $new_data ) {
	if ( ! $scan_data = cerber_get_scan() ) {
		return false;
	}

	$data = cerber_array_merge_recurively( $scan_data, $new_data );

	return cerber_update_scan( $data );
}

/**
 * Delete scan results from DB
 *
 * @return bool
 */
function cerber_delete_scan( $scan_id = null ) {
	if ( ! $scan_id ) {
		$scan_id = cerber_get_scan_id(); // Last scan
	}
	if ( $scan_id && cerber_delete_set( 'scan', $scan_id ) ) {
		//cerber_db_query( 'DELETE FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan_id );
		cerber_db_query( 'DELETE FROM ' . CERBER_SCAN_TABLE );
		cerber_delete_set( 'tmp_verify_plugins', $scan_id );

		return true;
	}

	return false;
}

/**
 * Log system errors for the current scan
 *
 * @param string $msg
 *
 * @return bool
 */
function cerber_log_scan_error( $msg = '' ) {

	$scan = cerber_get_scan();
	$scan['errors'][] = $msg;

	return cerber_update_scan( array( 'errors' => $scan['errors'] ) );

}

/**
 * Check the integrity of installed plugins
 *
 * @return int The number of plugins to process
 */
function cerber_verify_plugins() {
	if ( ! $scan_id = cerber_get_scan_id() ) {
		return 0;
	}

	$key = 'tmp_verify_plugins';
    $done = cerber_get_set( $key, $scan_id );

	$plugins = get_plugins();

	if ( $done ) {
		$to_scan = array_diff( array_keys( $plugins ), array_keys( $done ) );
	}
	else {
		$done    = array();
		$to_scan = array_keys( $plugins );
	}

	if ( empty( $to_scan ) ) {
		return 0;
	}

	//$plugins_dir = mb_substr( cerber_get_plugins_dir(), mb_strlen( ABSPATH ) ) . DIRECTORY_SEPARATOR;
	$plugins_dir = cerber_get_plugins_dir() . DIRECTORY_SEPARATOR;
	$file_count  = 0;
	$bytes = 0;

	$max_files = 100;

	while ( ! empty( $to_scan ) ) {
		$plugin = array_shift( $to_scan );
		$issues = array();

		if ( false === strpos( $plugin, '/' ) ) {
			// A single-file plugin with no plugin folder (no hash on wordpress.org)
			$done[ $plugin ] = 1;

			if ( $plugin == 'hello.php' ) { // It's checked with WP hash
				continue;
			}

			$plugin_folder = $plugin;
			/*
			$issues[] = array( 5, '', 'plugin' => $plugins[ $plugin ] );
			if ( $issues ) {
				cerber_push_issues( $plugins[ $plugin ]['Name'], $issues, 'crb-plugins' );
			}
			continue;
			*/
		}
		else {
			$plugin_folder = dirname( $plugin );
		}

		$plugin_hash = cerber_get_plugin_hash( $plugin_folder, $plugins[ $plugin ]['Version'] );

		if ( $plugin_hash && ! is_wp_error( $plugin_hash ) ) {
			foreach ( $plugin_hash->files as $file => $hash ) {

				if ( ! cerber_is_file_type_scan( $file ) ) {
					continue;
				}

				$file_name      = $plugins_dir . $plugin_folder . DIRECTORY_SEPARATOR . $file;
				$file_name_hash = sha1( $file_name );
				$where          = 'scan_id = ' . $scan_id . ' AND file_name_hash = "' . $file_name_hash . '"';
				$local_file     = cerber_db_get_row( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE ' . $where );

				if ( ! $local_file ) {
					$issues[] = array( 10, DIRECTORY_SEPARATOR . $plugin_folder . DIRECTORY_SEPARATOR . $file );
					continue;
				}

				$short_name = cerber_get_short_name( $local_file );

				if ( empty( $local_file['file_hash'] ) ) {
					$issues[] = array( 11, $short_name, 'file' => $local_file );
					continue;
				}
				$hash_match = 0;
				if ( isset( $hash->sha256 ) ) {
					$repo_hash = $hash->sha256;
					if ( is_array( $repo_hash ) ) {
						$file_hash_repo = 'REPO provides multiple values, none match';
						foreach ( $repo_hash as $item ) {
							if ( $local_file['file_hash'] == $item ) {
								$hash_match     = 1;
								$file_hash_repo = $item;
								break;
							}
						}
					}
					else {
						$file_hash_repo = $repo_hash;
						if ( $local_file['file_hash'] == $repo_hash ) {
							$hash_match = 1;
						}
					}
				}
				else {
					$file_hash_repo = 'SHA256 hash not found';
				}

				if ( $hash_match ) {
					$status = 1;
				}
				else {
					$status   = 15;
					$issues[] = array( $status, $short_name, 'file' => $local_file );
				}

				cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET file_hash_repo = "' . $file_hash_repo . '", hash_match = ' . $hash_match . ', scan_status = ' . $status . ' WHERE ' . $where );

				$file_count ++;
				$bytes += absint( $local_file['file_size'] );

			}
			$verified = 1;
		}
		else {
			$verified = cerber_verify_plugin( $plugin_folder, $plugins[ $plugin ] );
		}

		if ( ! $verified ) {
			$verified = 0;
			$status = 5;
		}
        else {
	        $verified = 1;
			$status = 1;
		}
		$issues[] = array( $status, '', 'plugin' => $plugins[ $plugin ] );

		if ( $issues ) {
			cerber_push_issues( $plugins[ $plugin ]['Name'], $issues, 'crb-plugins' );
		}

		cerber_set_scan( array( 'integrity' => array( 'plugins' => array( $plugin => $verified ) ) ) );

		$done[ $plugin ] = 1;

		if ( $file_count > $max_files || cerber_exec_timer() ) {
			break;
		}

	}

	cerber_update_set( $key, $done, $scan_id );

	return count( $to_scan );
}

/**
 * Checking the integrity of a plugin if there is no hash on wordpress.org
 *
 * @param string $plugin_folder Just folder, no full path, no slashes
 * @param array $plugin_data
 *
 * @return bool If true the plugin was verified by using an alternative source of hash
 */
function cerber_verify_plugin( $plugin_folder, $plugin_data ) {
	$ret  = false;
	$hash = null;

	// Is there local hash?

	$hash = cerber_get_local_hash( CRB_HASH_PLUGIN . sha1( $plugin_data['Name'] . $plugin_folder ), $plugin_data['Version'] );

	// Possibly remote hash?

	if ( ! $hash ) {

		$hash_url = null;

		if ( $plugin_folder == 'wp-cerber' ) {
			$hash_url = 'https://my.wpcerber.com/downloads/checksums/' . $plugin_data['Version'] . '.json';
		}

		if ( $hash_url ) {
			$response = cerber_obtain_hash( $hash_url );
			if ( ! $response['error'] ) {
				$hash = get_object_vars( $response['server_data'] );
			}
			else {
				if ( ! empty( $response['curl_error'] ) ) {
					$msg = 'CURL ' . $response['curl_error'];
				}
                elseif ( ! empty( $response['json_error'] ) ) {
					$msg = 'JSON ' . $response['json_error'];
				}
				else {
					$msg = 'Unknown network error';
				}
				//$ret = new WP_Error( 'net_issue', $msg );
				cerber_log_scan_error( $msg );
			}

		}
	}

	if ( $hash ) {
		//$local_prefix = cerber_get_plugins_dir() . DIRECTORY_SEPARATOR . $plugin_folder . DIRECTORY_SEPARATOR;
		$local_prefix = cerber_get_plugins_dir() . DIRECTORY_SEPARATOR;
		if ( ! strpos( $plugin_folder, '.' ) ) { // Not a single file plugin
			$local_prefix .= $plugin_folder . DIRECTORY_SEPARATOR;
		}
		$issues = cerber_verify_files( $hash, 'file_hash', $local_prefix );
		cerber_push_issues( $plugin_data['Name'], $issues, 'crb-plugins' );
		$ret = true;
	}

	return $ret;
}

/**
 * Verifying the integrity of the WordPress
 *
 * @return int
 */
function cerber_verify_wp() {
	$wp_version = cerber_get_wp_version();

	$ret     = 0;
	$wp_hash = cerber_get_wp_hash();
	if ( ! is_wp_error( $wp_hash ) ) {
		$data = get_object_vars( $wp_hash->checksums );

		// In case the default name 'plugins' of the plugins folder has been changed
		$wp_plugins_dir = basename( cerber_get_plugins_dir() );
		if ( $wp_plugins_dir != 'plugins' ) {
			$new_data = array();
			foreach ( $data as $key => $item ) {
				if ( 0 === strpos( $key, 'wp-content/plugins/' ) ) {
					$new_data[ 'wp-content/' . $wp_plugins_dir . '/' . substr( $key, 19 ) ] = $item;
				}
				else {
					$new_data[ $key ] = $item;
				}
			}
			$data = $new_data;
		}

		// In case the default name 'wp-content' of the CONTENT folder has been changed
		//$wp_content_dir = mb_substr( dirname( cerber_get_plugins_dir() ), mb_strlen( ABSPATH ) );
		$wp_content_dir = basename( dirname( cerber_get_plugins_dir() ) );
		if ( $wp_content_dir != 'wp-content' ) {
			$new_data = array();
			foreach ( $data as $key => $item ) {
				if ( 0 === strpos( $key, 'wp-content/' ) ) {
					$new_data[ $wp_content_dir . '/' . substr( $key, 11 ) ] = $item;
				}
				else {
					$new_data[ $key ] = $item;
				}
			}
			$data = $new_data;
		}

		$verified = 1;
		cerber_push_issues( 'WordPress', array( array( 1, 'wordpress' => $wp_version ) ) );
		$issues = cerber_verify_files( $data, 'file_md5', ABSPATH, array(CERBER_FT_PLUGIN, CERBER_FT_THEME), CERBER_FT_WP, '_crb_not_existing' );
		cerber_push_issues( 'WordPress', $issues );
	}
	else {
		cerber_push_issues( 'WordPress', array( array( 7, 'wordpress' => $wp_version ) ) );
		$verified = 0;
	}

	cerber_set_scan( array( 'integrity' => array( 'wordpress' => $verified ) ) );

	return $ret;
}

// Themes and plugin will be checked separately, not as a part of WP
function _crb_not_existing( $file_name ) {
	static $themes_prefix, $plugins_prefix;

	if ( $themes_prefix == null ) {
		$themes_prefix = basename( dirname( cerber_get_plugins_dir() ) ) . '/themes/';
	}
	if ( 0 === strpos( $file_name, $themes_prefix ) ) {
		return false;
	}

	if ( $plugins_prefix == null ) {
		$plugins_prefix = basename( dirname( cerber_get_plugins_dir() ) ) . '/' . basename( cerber_get_plugins_dir() ) . '/';
	}
	if ( 0 === strpos( $file_name, $plugins_prefix ) ) {
		return false;
	}

	return true;
}

/**
 * Verifying the integrity of the themes
 *
 * @return int
 */
function cerber_verify_themes() {

	$themes = wp_get_themes();

	foreach ( $themes as $theme_folder => $theme ) {
		$issues = array();
		$hash = cerber_get_theme_hash( $theme_folder, $theme );

		if ( $hash && ! is_wp_error( $hash ) ) {
			$local_prefix = cerber_get_themes_dir() . DIRECTORY_SEPARATOR . $theme_folder . DIRECTORY_SEPARATOR;
			$issues = cerber_verify_files( $hash, 'file_hash', $local_prefix, null, CERBER_FT_THEME );
			//cerber_push_issues( $theme->get( 'Name' ), $issues );
			$verified = 1;
			$status = 1;
		}
		else {
			if ( is_wp_error( $hash ) ) {
				cerber_log_scan_error( $hash->get_error_message() );
			}
			$verified = 0;
			$status = 5;
		}

		$issues[] = array( $status, $theme_folder, 'theme' => $theme );

		cerber_set_scan( array( 'integrity' => array( 'themes' => array( $theme_folder => $verified ) ) ) );

		if ( $issues ) {
			cerber_push_issues( $theme->get( 'Name' ), $issues, 'crb-themes' );
		}
	}

	//$scan_id = cerber_get_scan_id();
	//cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET scan_status = 5 WHERE scan_id = ' . $scan_id . ' AND file_type = ' . CERBER_FT_THEME );

	return 0;
}

/**
 * Inspecting unattended files (remained after integrity checking) for traces of malware
 *
 * @return int
 */
function cerber_process_files() {

	if ( ! $scan = cerber_get_scan() ) {
		return 0;
	}

	// -------- Plugins data

	$plugins = array();
	foreach ( get_plugins() as $key => $item ) {
		if ( $pos = strpos( $key, DIRECTORY_SEPARATOR ) ) {
			$new_key = substr( $key, 0, strpos( $key, DIRECTORY_SEPARATOR ) );
		}
		else {
			$new_key = $key;
		}

		$plugins[ $new_key ] = $item;
		if ( ! empty( $scan['integrity']['plugins'][ $key ] ) ) {
			$plugins[ $new_key ]['integrity'] = true;
		}
	}

	// ---------------------------------------------------------------------------

	// -------- Themes data

    $themes = wp_get_themes();

	// ---------------------------------------------------------------------------

	$can_be_deleted = array( CERBER_FT_UPLOAD, CERBER_FT_CNT, CERBER_FT_OTHER, CERBER_FT_LNG );

	$issues = array();
	$remain = 0;

	// Prevent hanging
	if ( $f = cerber_get_set( CRB_LAST_FILE, 0, false ) ) {
		cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET scan_status = 13 WHERE scan_id = ' . $scan['id'] . ' AND file_name_hash = "' . sha1( $f ) . '"' );
		cerber_update_set( CRB_LAST_FILE, '', 0, false );
		$m = cerber_get_issue_desc( 13 ) . ' ' . $f . ' size: ' . @filesize( $f ) . ' bytes';
		cerber_log_scan_error( $m );
	}

	if ( $files = cerber_db_get_results( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan['id'] . ' AND scan_status NOT IN (1,14,15)' ) ) {

		if ( $unwanted = crb_get_settings( 'scan_uext' ) ) {
			$unwanted = array_map( function ( $ext ) {
				return strtolower( trim( $ext, '. ' ) );
			}, $unwanted );
		}

		$x = 0;

		foreach ( $files as $file ) {

			if ( cerber_is_htaccess( $file['file_name'] ) ) { // || $file['file_size'] == 0
				cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET scan_status = 1 WHERE scan_id = ' . $scan['id'] . ' AND file_name_hash = "' . $file['file_name_hash'] . '"' );
				continue;
			}

			$integrity_verified = false;
			$severity_limit     = 6;
			$status             = CERBER_USF;
			$section            = '';
			$do_not_del         = false;

			switch ( $file['file_type'] ) {
				case CERBER_FT_WP:
					$section    = 'WordPress';
					$do_not_del = true;
					if ( ! empty( $scan['integrity']['wordpress'] ) ) {
						$integrity_verified = true;
					}
					break;
				case CERBER_FT_PLUGIN:
					$f = cerber_get_file_folder( $file['file_name'], cerber_get_plugins_dir() );
					if ( isset( $plugins[ $f ] ) ) {
						$section    = $plugins[ $f ]['Name'];
						$do_not_del = true;
						if ( ! empty( $plugins[ $f ]['integrity'] ) ) {
							$integrity_verified = true;
						}
					}
					else {
						$severity_limit = 1;
					}
					break;
				case CERBER_FT_THEME:
					$f = cerber_get_file_folder( $file['file_name'], cerber_get_themes_dir() );
					if ( isset( $themes[ $f ] ) ) {
						$section    = $themes[ $f ]->get( 'Name' ); // WP_Theme object
						$do_not_del = true;
						if ( ! empty( $scan['integrity']['themes'][ $f ] ) ) {
							$integrity_verified = true;
						}
						$severity_limit = 5;
					}
					else {
						$severity_limit = 1;
					}
					//$status = 1;
					break;
				case CERBER_FT_ROOT:
					if ( cerber_is_htaccess( $file['file_name']) ) {
						$section = 'WordPress';
					}
					if ( ! empty( $scan['integrity']['wordpress'] ) ) {
						$integrity_verified = true;
					}
					$do_not_del = true;
					$severity_limit = 1;
					break;
				case CERBER_FT_CONF:
					$section        = 'WordPress';
					$do_not_del     = true;
					$severity_limit = 2;
					break;
				case CERBER_FT_UPLOAD:
					$section = 'Uploads folder';
					$severity_limit = 1;
					break;
				case CERBER_FT_MUP:
					$section    = 'Must-use plugins';
					$do_not_del = true;
					break;
				case CERBER_FT_OTHER:
					$severity_limit = 1;
					break;
				case CERBER_FT_DRIN:
					$section = 'Drop-ins';
					break;
					default:
					$severity_limit = 2;
					break;

			}

			// Now we're ready to perform inspection

			if ( ! $integrity_verified ) {

			    $result = cerber_inspect_file( $file['file_name'] );

				if ( ! is_wp_error( $result ) ) {
					$status = 1;
					if ( $result['severity'] == CERBER_MALWR_DETECTED ) {
						$status = CERBER_PMC;
					}
					/*
                    elseif ( $result['severity'] == $severity_limit ) {
						$status = CERBER_USF;
					}*/
                    elseif ( $result['severity'] >= $severity_limit ) {
						if ( $result['severity'] == 1 ) {
							$status = CERBER_EXC;
						}
						else {
							$status = CERBER_SCF;
						}
					}
				}
				else {
					$status = 14;
				}

			}
			else {
				$result = array();
            }

			// An exception for wp-config.php
			if ( $status == CERBER_USF && $file['file_type'] == CERBER_FT_CONF ) {
				$status = 1;
			}

			// Unwanted extensions
			if ( $status == 1 && $unwanted ) {
				$f = strtolower( basename( $file['file_name'] ) );
				$e = explode( '.', $f );
				array_shift( $e );
				if ( $e && array_intersect( $unwanted, $e ) ) {
					$status = CERBER_UXT;
				}
			}

			// There is an issue with file
			if ( $status > 1 ) {

				if ( ! $section ) {
					$section = 'Unattended files';

					$len = 0;
					if ( 0 === strpos( $file['file_name'], rtrim( ABSPATH, '/\\' ) ) ) {
						$len = mb_strlen( ABSPATH ) - 1;
					}
					if ( $len ) {
						$short_name = mb_substr( $file['file_name'], $len );
					}
					else {
						$short_name = $file['file_name'];
                    }
				}
				else {
					$short_name = cerber_get_short_name( $file );
				}

			    // Is file can be deleted?

				if ( $status >= CERBER_SCF ) {
					if ( $integrity_verified ) {
						$file['fd_allowed'] = 1;
					}
                    elseif ( ! $do_not_del || in_array( $file['file_type'], $can_be_deleted ) ) {
						$file['fd_allowed'] = 1;
					}
				}

				//$short_name = cerber_get_short_name( $file );
				$issues[ $section ][] = array( $status, $short_name, $result, 'file' => $file );

			}

			cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET scan_status = ' . $status . ' WHERE scan_id = ' . $scan['id'] . ' AND file_name_hash = "' . $file['file_name_hash'] . '"' );

			if ( 0 === ($x % 100) ) {
				if ( cerber_exec_timer() ) {
					$remain = 1;
				    break;
				}
			}
			$x++;
		}
	}

	if ( $issues ) {
		foreach ( $issues as $section => $list ) {
			cerber_push_issues( $section, $list );
		}
	}

	return $remain;
}

/**
 * Scan a file for suspicious and malicious code
 *
 * @param string $file_name
 *
 * @return array|bool|WP_Error
 */
function cerber_inspect_file( $file_name = '' ) {
	global $cerber_scan_mode, $wp_cerber;

	if ( !@is_file( $file_name ) ) {
		return false;
	}

	if ( ! cerber_check_extension( $file_name, array( 'php', 'inc', 'phtm', 'phtml', 'phps', 'php2', 'php3', 'php4', 'php5', 'php6', 'php7' ) ) ) {
		$php = false;

		if ( $cerber_scan_mode == 'full' ) {
			// Try to find an PHP open tag in the content
			if ( $f = @fopen( $file_name, 'r' ) ) {
				$str = fread( $f, 10000 );
				if ( false !== strrpos( $str, '<?php' ) ) {
					$php = true;
				}
				fclose( $f );
			}
			else {
				cerber_log_scan_error( cerber_scan_msg( 0, $file_name ) );
            }
		}

		if ( ! $php ) {
			return array( 'severity' => 0 );
		}
	}

	cerber_update_set( CRB_LAST_FILE, $file_name, 0, false );
	$result = cerber_inspect_php( $file_name );
	cerber_update_set( CRB_LAST_FILE, '', 0, false );

	if ( is_wp_error( $result ) ) {
		cerber_log_scan_error( $result->get_error_message() );
	}

	return $result;
}

/**
 * Scan a file for suspicious and malicious PHP code
 *
 * @param string $file_name
 *
 * @return array|bool|WP_Error
 */
function cerber_inspect_php( $file_name = '' ) {
	if ( ! $content = @file_get_contents( $file_name ) ) {
		return new WP_Error( 'cerber-file', cerber_scan_msg( 0, $file_name ) );
	}

	$important = array( T_STRING, T_EVAL );

	$tokens = token_get_all( $content );
	unset( $content );
	if ( ! $tokens ) {
		return array( 'severity' => 0 ); // weird
	}

	$code_found = 0; // Any PHP code in the file = 1
	$severity = array();
	$xdata = array();
	$pos  = array();
	$open = null;
	$list = cerber_get_unsafe();

	foreach ( $tokens as $token ) {
		if ( ! is_array( $token ) ) {
			continue;
		}
		if ( in_array( $token[0], $important ) ) {
			$code_found = 1;
			if ( isset( $list[ $token[1] ] ) ) {
				$xdata[]    = array( 1, $token[1], $token[2], $token[0] );
				$severity[] = $list[ $token[1] ][0];
			}
		}
		if ( $token[0] == T_OPEN_TAG ) {
			$open = $token[2] - 1;
		}
		if ( $open && ( $token[0] == T_CLOSE_TAG ) ) {
			$pos[] = array( $open, $token[2] - 1 );
			$open  = null;
		}
	}
	if ( $open !== null ) { // No closing tag till the end of the file
		$pos[] = array( $open, null );
	}

	if ( empty( $pos ) ) {
		return false;
	}
	if ( ! $lines = @file( $file_name ) ) {
		return new WP_Error( 'cerber-file', cerber_scan_msg( 0, $file_name ) );
	}

	$code  = array();
	$last  = count( $pos ) - 1;

	foreach ( $pos as $k => $p ) {
		if ( $last == $k ) {
			$length = null;
		}
		else {
			$length = $p[1] - $p[0] + 1;
		}
		$code = $code + array_slice( $lines, $p[0], $length, true );
	}

	//unset( $lines );
	$code = implode( "\n", $code );
	$code = cerber_remove_comments( $code );
	$code = preg_replace( "/[\n\s]+/", '', $code );

	if ( ! $code ) {
		return false;
	}

	// Check for malicious code patterns

	foreach ( cerber_get_patterns() as $pa ) {
	    if ($pa[1] == 2) { // 2 = REGEX
		    $matches = array();
		    if ( preg_match_all( '/' . $pa[2] . '/i', $code, $matches, PREG_OFFSET_CAPTURE ) ) {

		        if ( ! empty( $pa['not_func'] ) && function_exists( $pa['not_func'] ) ) {
				    foreach ( $matches[0] as $key => $match ) {
					    if ( call_user_func( $pa['not_func'], $match[0] ) ) {
						    unset( $matches[0][ $key ] );
					    }
				    }
			    }

			    if ( ! empty( $pa['func'] ) && function_exists( $pa['func'] ) ) {
				    foreach ( $matches[0] as $key => $match ) {
					    if ( ! call_user_func( $pa['func'], $match[0] ) ) {
						    unset( $matches[0][ $key ] );
					    }
				    }
			    }

			    if ( ! empty( $matches[0] ) ) {
				    $xdata[]    = array( 2, $pa[0], array_values( $matches[0] ) );
				    $severity[] = $pa[3];
			    }
		    }
	    }
	    else {
		    if ( false !== stripos( $code, $pa[2] ) ) {
			    $xdata[]    = array( 2, $pa[0], array( array( $pa[2] ) ) );
			    $severity[] = $pa[3];
		    }
	    }
	}

	// Try to find line numbers for matches
	if ( $xdata ) {
		foreach ( $xdata as $x => $d ) {
			if ( $d[0] != 2 || ! isset( $d[2] ) ) {
				continue;
			}
			foreach ( $d[2] as $y => $m ) {
				foreach ( $lines as $i => $line ) {
					if ( false !== strrpos( $line, $m[0] ) ) {
						$xdata[ $x ][2][ $y ][2] = $i + 1;
						break;
					}
				}
				if ( ! isset( $xdata[ $x ][2][ $y ][2] ) ) {
					$xdata[ $x ][2][ $y ][2] = '?';
				}
			}
		}
	}

	unset( $lines );

	// An attempt to interpret the results

	$max = 0;

	if ( $severity ) {
		$malwr_found        = false;
		$malwr_combinations = array( array( 10, 7 ), array( 9, 7 ) );
		foreach ( $malwr_combinations as $malwr ) {
			if ( $int = array_intersect( $malwr, $severity ) ) {
				if ( count( $malwr ) == count( $int ) ) {
					$malwr_found = true;
				}
			}
		}

		$max = ( $malwr_found ) ? CERBER_MALWR_DETECTED : max( $severity );
	}

	if ( $code_found && ! $max ) {
		$max = $code_found;
	}

	return array( 'severity' => $max, 'xdata' => $xdata );

}

/**
 * Unsafe code tokens
 *
 * @return array
 */
function cerber_get_unsafe(){
	return array(
		'system' => array( 10, 'May be used to get/change vital system information or to run arbitrary server software.' ),
		'shell_exec' => array(10, 'Executes arbitrary command via shell and returns the complete output as a string.'),
		'exec' => array(10, 'Executes arbitrary programs on the web server.'),
		'assert' => array(10, 'Allows arbitrary code execution.'),
		'passthru' => array(10,'Executes arbitrary programs on the web server and displays raw output.'),
		'pcntl_exec' => array(10, 'Executes arbitrary programs on the web server in the current process space.'),
		'proc_open' => array(10, 'Executes an arbitrary command on the web server and open file pointers for input/output.'),
		'popen' => array(10, 'Opens a process (execute an arbitrary command) file pointer on the web server.'),
		'dl' => array(10, 'Loads a PHP extension on the web server at runtime.'),
		'eval' => array( 9, 'May be used to execute malicious code on the web server. Pairing with base64_decode function indicates malicious code.' ),
		'str_rot13' => array(9, 'Perform the rot13 transform on a string. May be used to obfuscate malware.'),
		'base64_decode' => array(7, 'May be used to obfuscate and hinder detection of malicious code. Pairing with eval function indicates malicious code.'),
		'socket_create' => array(6, 'Creates a network connection with any remote host. May be used to load malicious code from any web server with no restrictions.'),

		'hexdec' => array(5, 'Hexadecimal to decimal. May be used to obfuscate malware.'),
		'dechex' => array(5, 'Decimal to hexadecimal. May be used to obfuscate malware.'),

		'chmod' => array(5, 'Changes file access mode.'),
		'chown' => array(5, 'Changes file owner.'),
		'chgrp' => array(5, 'Changes file group.'),
		'symlink' => array(5, 'Creates a symbolic link to the existing file.'),
		'unlink' => array(5, 'Deletes a file.'),

		'gzinflate' => array(4, 'Inflate a deflated string. May be used to obfuscate malware.'),
		'gzdeflate' => array(4, 'Deflate a string. May be used to obfuscate malware.'),

		'curl_exec' => array(4, 'Load external data from any web server. May be used to load malicious code from any web server with no restrictions.'),
		'file_get_contents' => array(4, 'Read the entire file into a string. May be used to load malicious code from any web server with no restrictions.'),

        'wp_remote_request' => array(3, 'Load data from any web server. May be used to load malicious code from an external source.'),
		'wp_remote_get' => array(3, 'Load external data from any web server. May be used to load malicious code from an external source.'),
		'wp_remote_post' => array(3, 'Upload or download data from/to any web server. May be used to load malicious code from an external source.'),
		'wp_safe_remote_post' => array(3, 'Upload or download data from/to any web server. May be used to load malicious code from an external source.'),
		'wp_remote_head' => array(3, 'Load data from any web server. May be used to load malicious code from an external source.'),

        'create_function' => array(2, 'Create an anonymous (lambda-style) function. Deprecated. A native anonymous function must be used instead.'),
		'call_user_func' => array(2, 'Call any function given by the first parameter. May be used to run malicious code or hinder code inspection.'),
		'call_user_func_array' => array(2, 'Call any function with an array of parameters. May be used to run malicious code or hinder code inspection.'),
	);
}

/**
 * Unsafe code patterns/signatures
 *
 * @return array
 */
function cerber_get_patterns() {
	$list = array(
		array( 'VARF', 2, '\$[a-z0-9\_]+?\((?!\))', 11, 'A variable function call. Usually is used to hinder malware detection.' ), // pattern with function parameter(s): $example(something)
		array( 'IPV4', 2, '(?:[0-9]{1,3}\.){3}[0-9]{1,3}', 6, 'An external IPv4 address. Can cause data leakage.', 'func' => '_is_ip_external' ),
		array( 'IPV6', 2, '(?:[A-F0-9]{1,4}:){7}[A-F0-9]{1,4}', 6, 'An external IPv6 address. Can cause data leakage.', 'func' => '_is_ip_external' ),
		array( 'BCTK', 2, '`[a-z]+`', 10, 'Execute arbitrary command on the web server' ),
		array( 'PIDT', 3, 'php://input', 6, 'Get data or commands from the Internet. Should be used in trusted or verified software only' ),
		array( 'NGET', 3, '$_GET', 3, 'Get data or commands from the Internet. Should be used in trusted or verified software only' ),
		array( 'NPST', 3, '$_POST', 3, 'Get data or commands from the Internet. Should be used in trusted or verified software only' ),
		array( 'NREQ', 3, '$_REQUEST', 3, 'Get data or commands from the Internet. Should be used in trusted or verified software only' ),

        // Should be in a separate data set for non-php files
        //array( 'SHL1', 3, '#!/bin/sh', 6, 'Executable shell script' ),
	);
	if ( $custom = crb_get_settings( 'scan_cpt' ) ) {
		foreach ( $custom as $i => $p ) {
			if ( substr( $p, 0, 1 ) == '{' && substr( $p, - 1 ) == '}' ) {
				$p = substr( $p, 1, - 1 );
				$t = 2;
			}
			else {
				$t = 3;
			}
			$list[] = array( 'CUS' . $i, $t, $p, 4, __( 'Custom signature found', 'w-cerber' ) );
		}
	}

	return $list;
}

function _is_ip_external( $ip ) {
	if ( is_ip_private( $ip ) ) {
		return false;
	}
	if ( defined( 'DB_HOST' ) && DB_HOST === $ip ) {
		return false;
	}

	return true;
}

add_action( 'wp_ajax_cerber_get_strings', function () {
	cerber_check_ajax();
	$data = array();
	$data[1] = cerber_get_unsafe();
	$list = array();
	foreach ( cerber_get_patterns() as $p ) {
		$list[ $p[0] ] = $p[4];
	}
	$data[2] = $list;
	$data['complete'] = 1;
	echo json_encode( $data );

	wp_die();
} );

/**
 * Verify a set of file using hash data provided as array of $file_name => $hash
 *
 * @param array $hash_data Hash
 * @param string $field Name of DB table field with local hash
 * @param string $local_prefix  Local filename prefix
 * @param int $set_type If set, the file type will be set to this value
 * @param callable $func If a local file doesn't exist it will be saved as an issue if return true
 *
 * @return array List of issues found
 */
function cerber_verify_files( $hash_data, $field = 'file_hash', $local_prefix = '', $type_not_in = array(), $set_type = null, $func = null ) {
	if ( ! $scan = cerber_get_scan() ) {
		return 0;
	}

	$set_type = absint( $set_type );
	$issues = array();
	$file_count = 0;

	if ( !$func || !function_exists( $func ) ) {
		$func = null;
	}

	foreach ( $hash_data as $file_name => $hash ) {

		if ( ! cerber_is_file_type_scan( $file_name ) ) {
			continue;
		}

		$file_name_hash = sha1( $local_prefix . $file_name );
		$where          = 'scan_id = ' . $scan['id'] . ' AND file_name_hash = "' . $file_name_hash . '"';
		/*
		if ($type_not_in){
			$type_not_in = array_filter( array_map( 'absint', $type_not_in ) );
			$where .= ' AND file_type NOT IN (' . implode( ',', $type_not_in ) . ')';
        }*/

        $local_file = cerber_db_get_row( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE ' . $where );

		if ( ! $local_file ) {
			if ( $func ) {
				if ( ! call_user_func( $func, $file_name ) ) {
					continue;
				}
			}
			$issues[] = array( 10, '/' . ltrim( $file_name, '/' ) );
			continue;
		}

		if ( ! empty( $type_not_in ) && in_array( $local_file['file_type'], $type_not_in ) ) {
			continue;
		}

		$short_name = cerber_get_short_name( $local_file );

		if ( empty( $local_file[ $field ] ) ) {
			$issues[] = array( 11, $short_name, 'file' => $local_file );
			continue;
		}
		$hash_match = ( $local_file[ $field ] === $hash ) ? 1 : 0;

		if ( $hash_match ) {
			$status = 1;
		}
		else {
			$status   = 15;
			$issues[] = array( $status, $short_name, 'file' => $local_file );
		}

		$file_type = ( ! empty( $set_type ) ) ? $set_type : $local_file['file_type'];

		cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET file_type = ' . $file_type . ', file_hash_repo = "' . $hash . '", hash_match = ' . $hash_match . ', scan_status = ' . $status . ' WHERE ' . $where );

		$file_count ++;

	}

	return $issues;
}

/**
 * Retrieve hash for a given plugin from wordpress.org
 *
 * @param $plugin string  Plugin folder
 * @param $ver string Plugin version
 * @param $nocache bool If true, do not use data from the local cache (refresh one)
 *
 * @return WP_Error|array|mixed
 */
function cerber_get_plugin_hash( $plugin, $ver, $nocache = false ) {

	if ( !$plugin = preg_replace( '/[^a-z\-\d]/i', '', $plugin ) ) {
		return false;
	}

	$response = cerber_obtain_hash( 'https://downloads.wordpress.org/plugin-checksums/' . $plugin . '/' . $ver . '.json', $nocache );

	if ( ! $response['error'] ) {
		return $response['server_data'];
	}

	if ( $response['http_code'] == 404 ) {
		$ret = new WP_Error( 'no_remote_hash', 'The plugin is not found on wordpress.org' );
	}
	else {
		if ( ! empty( $response['curl_error'] ) ) {
			$msg = 'CURL ' . $response['curl_error'];
		}
        elseif ( ! empty( $response['json_error'] ) ) {
			$msg = 'JSON ' . $response['json_error'];
		}
		else {
			$msg = 'Unknown network error';
		}
		$ret = new WP_Error( 'net_issue', $msg );
		cerber_log_scan_error( $msg );
	}


	return $ret;

}

/**
 * @param $theme_folder
 * @param $theme object WP_Theme
 *
 * @return bool|WP_Error|array  false if no local hash or theme is not publicly hosted on on the wordpress.org
 */
function cerber_get_theme_hash( $theme_folder, $theme ) {

	if ( $hash = cerber_get_local_hash( CRB_HASH_THEME . sha1( $theme->get( 'Name' ) . $theme_folder ), $theme->get('Version') ) ) {
	    return $hash;
	}

	// Try to load a reference ZIP archive from wordpress.org

	$tmp_folder = cerber_get_tmp_file_folder();
	if ( is_wp_error( $tmp_folder ) ) {
		return $tmp_folder;
	}

	$tmp_zip_file = $tmp_folder . $theme_folder . '.' . $theme->get( 'Version' ) . '.zip';

	if ( ! $fp = fopen( $tmp_zip_file, 'w' ) ) {
		return new WP_Error( 'cerber-file', 'Unable to create temporary file ' . $tmp_zip_file );
	}

	$curl = @curl_init();
	if ( ! $curl ) {
		return new WP_Error( 'cerber-curl', 'CURL library is disabled or not installed on this web server.');
	}

	$url = 'https://downloads.wordpress.org/theme/' . $theme_folder . '.' . $theme->get( 'Version' ) . '.zip';

	curl_setopt_array( $curl, array(
		CURLOPT_URL               => $url,
		CURLOPT_POST              => false,
		CURLOPT_USERAGENT         => 'Cerber Security Plugin',
		CURLOPT_FILE              => $fp,
		CURLOPT_FAILONERROR       => true,
		CURLOPT_CONNECTTIMEOUT    => 5,
		CURLOPT_TIMEOUT           => 25, // including CURLOPT_CONNECTTIMEOUT
		CURLOPT_DNS_CACHE_TIMEOUT => 3 * 3600,
		CURLOPT_SSL_VERIFYHOST    => 2,
		CURLOPT_SSL_VERIFYPEER    => true,
		CURLOPT_CAINFO            => ABSPATH . WPINC . '/certificates/ca-bundle.crt',
	) );

	if ( ! curl_exec( $curl ) ) {
		$code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
		curl_close( $curl );
		fclose( $fp );
		unlink( $tmp_zip_file );

		if ( 404 == $code ) {
			return false; // Nothing serious, should not be logged
		}

		return new WP_Error( 'cerber-curl', 'Unable to download: ' . $url );
	}

	curl_close( $curl );
	fclose( $fp );

	$result = cerber_need_for_hash( $tmp_zip_file, true, time() + DAY_IN_SECONDS );
	if ( is_wp_error( $result ) ) {
	    return $result;
    }

	if ( $hash = cerber_get_local_hash( CRB_HASH_THEME . sha1( $theme->get( 'Name' ) . $theme_folder ), $theme->get('Version') ) ) {
		return $hash;
	}

	return false;
}

/**
 * Retrieve MD5 hash from wordpress.org
 * See also: get_core_checksums();
 *
 * @param bool $nocache if true, do not use the local cache
 *
 * @return array|object|WP_Error
 */
function cerber_get_wp_hash( $nocache = false ) {

    $wp_version = cerber_get_wp_version();

	$locale = get_locale();

	$response = cerber_obtain_hash( 'https://api.wordpress.org/core/checksums/1.0/?version=' . $wp_version . '&locale=' . $locale, $nocache );

	if ( ! $response['error'] ) {
		$ret = $response['server_data'];
	}
	else {
		if ( ! empty( $response['curl_error'] ) ) {
			$msg = 'CURL ' . $response['curl_error'];
		}
        elseif ( ! empty( $response['json_error'] ) ) {
			$msg = 'JSON ' . $response['json_error'];
		}
		else {
			$msg = 'Unknown network error';
		}
		$ret = new WP_Error( 'net_issue', $msg );
		cerber_log_scan_error( $msg );
	}

	return $ret;

}

/**
 * Download hash from the given URL. Network level.
 *
 * @param $url
 * @param bool $nocache If true, do not use data from the local cache (refresh one)
 *
 * @return array|bool
 */
function cerber_obtain_hash( $url, $nocache = false ) {

	$key = 'tmp_hashcache_' . sha1( $url );

	if ( ! $nocache && $cache = cerber_get_set( $key ) ) {
		return $cache;
	}

	$ret = array( 'error' => 1 );

	$curl = @curl_init();
	if ( ! $curl ) {
	    $ret['curl_error'] = 'CURL library is disabled or not installed on this web server.';
		return $ret;
	}

	curl_setopt_array( $curl, array(
		CURLOPT_URL               => $url,
		CURLOPT_POST              => false,
		CURLOPT_USERAGENT         => 'Cerber Security Plugin',
		CURLOPT_RETURNTRANSFER    => true,
		CURLOPT_CONNECTTIMEOUT    => 5,
		CURLOPT_TIMEOUT           => 10, // including CURLOPT_CONNECTTIMEOUT
		CURLOPT_DNS_CACHE_TIMEOUT => 3 * 3600,
		CURLOPT_SSL_VERIFYHOST    => 2,
		CURLOPT_SSL_VERIFYPEER    => true,
		CURLOPT_CAINFO            => ABSPATH . WPINC . '/certificates/ca-bundle.crt',
	) );

	$result = curl_exec( $curl );

	$ret['curl_status'] = curl_getinfo( $curl );
	$http_code = curl_getinfo( $curl, CURLINFO_HTTP_CODE );
	$ret['http_code'] = $http_code;

	if ( $result ) {
		if ( 200 === $http_code ) {
			$ret['server_data'] = json_decode( $result );
			if ( JSON_ERROR_NONE != json_last_error() ) {
				$ret['server_data'] = '';
				$ret['json_error'] = json_last_error();
				$ret['error'] = json_last_error();
			}
			else {
				$ret['error'] = 0;
				cerber_update_set( $key, $ret, 0, true, time() + DAY_IN_SECONDS );
			}
		}
        elseif ( 404 === $http_code ) {
	        $ret['curl_error'] = 'Remote server return 404 URL not found';
	        $ret['error'] = $ret['curl_error'];
			// There is no information about the plugin or this version of the plugin
		}
		else {
			if ( ! $err = curl_error( $curl ) ) {
				$err = 'Unknown CURL (network) error with code ' . $http_code;
			}
			$ret['curl_error'] = $err;
			$ret['error'] = $err;
		}
	}
	else {
		if ( ! $err = curl_error( $curl ) ) {
			$err = 'Unknown CURL (network) error with code ' . $http_code;
		}
		$ret['curl_error'] = $err;
		$ret['error'] = $err;
		//curl_errno($curl);
	}

	if ( ! empty( $ret['curl_error'] ) ) {
		$ret['curl_error'] = 'ERR# ' . curl_errno( $curl ) . ' ' . $ret['curl_error'] . ' for URL: ' . $url;
	}

	curl_close( $curl );

	return $ret;
}

function cerber_detect_file( $file_name ) {
	static $upload_dir = null;
	static $plugin_dir = null;
	static $theme_dir = null;
	static $content_dir = null;
	static $len = null;

	if ( $len === null ) {
		$len = strlen( ABSPATH );
	}
	if ( $content_dir === null ) {
		//$content_dir = mb_substr( dirname( cerber_get_plugins_dir() ), $len );
		$content_dir = dirname( cerber_get_plugins_dir() );
	}
	if ( $upload_dir === null ) {
		// TODO: implement this for multisite
		//$wp_upload_dir = wp_upload_dir();
		//$upload_dir    = mb_substr( $wp_upload_dir['path'], $len );
		//$upload_dir    = $wp_upload_dir['path'];
		$upload_dir = cerber_get_upload_dir();
	}
	if ( $plugin_dir === null ) {
		//$plugin_dir = mb_substr( cerber_get_plugins_dir(), $len );
		$plugin_dir = cerber_get_plugins_dir();
	}
	if ( $theme_dir === null ) {
		//$theme_dir = get_theme_root();
		//$theme_dir = $content_dir . DIRECTORY_SEPARATOR . 'themes';
		$theme_dir = cerber_get_themes_dir();
	}

	// Check in a particular order for a better performance

	if ( 0 === strpos( $file_name, ABSPATH . 'wp-admin' . DIRECTORY_SEPARATOR ) ) {
		return CERBER_FT_WP; // WP
	}
	if ( 0 === strpos( $file_name, ABSPATH . 'wp-includes' . DIRECTORY_SEPARATOR ) ) {
		return CERBER_FT_WP; // WP
	}

	if ( 0 === strpos( $file_name, $plugin_dir . DIRECTORY_SEPARATOR ) ) {
		return CERBER_FT_PLUGIN; // Plugin
	}

	if ( 0 === strpos( $file_name, $theme_dir . DIRECTORY_SEPARATOR ) ) {
		return CERBER_FT_THEME; // Theme
	}

	if ( 0 === strpos( $file_name, $upload_dir . DIRECTORY_SEPARATOR ) ) {
		return CERBER_FT_UPLOAD; // Upload folder
	}

	if ( 0 === strpos( $file_name, $content_dir . DIRECTORY_SEPARATOR ) ) {
		if ( 0 === strpos( $file_name, $content_dir . DIRECTORY_SEPARATOR . 'languages' . DIRECTORY_SEPARATOR ) ) {
			return CERBER_FT_LNG; // Translations
		}
		if ( 0 === strpos( $file_name, $content_dir . DIRECTORY_SEPARATOR . 'mu-plugins' . DIRECTORY_SEPARATOR ) ) {
			return CERBER_FT_MUP; // A file in MU plugins folder
		}
		if ( $file_name === $content_dir . DIRECTORY_SEPARATOR . 'index.php' ) {
			return CERBER_FT_WP; // WP
		}

		if ( cerber_is_dropin( $file_name ) ) {
			return CERBER_FT_DRIN;
		}

		return CERBER_FT_CNT; // WP Content
	}

	if ( strrpos( $file_name, DIRECTORY_SEPARATOR ) === ( $len - 1 ) ) {
		//if ( strrchr( $file_name, DIRECTORY_SEPARATOR ) === DIRECTORY_SEPARATOR . 'wp-config.php' ) {
		if ( basename( $file_name ) == 'wp-config.php' ) {
			return CERBER_FT_CONF;
		}

		return CERBER_FT_ROOT; // File in the root folder
	}

	if ( basename( $file_name ) == 'wp-config.php' ) {
		if ( ! file_exists( ABSPATH . '/wp-config.php' ) ) {
			return CERBER_FT_CONF;
		}
	}

	return CERBER_FT_OTHER; // Some subfolder in the root folder

}

function cerber_is_htaccess( $file_name ) {
	if ( strrchr( $file_name, DIRECTORY_SEPARATOR ) === DIRECTORY_SEPARATOR . '.htaccess' ) {
		return true;
	}

	return false;
}

function cerber_is_dropin( $file_name ) {
	$dropins = _get_dropins();
	if ( isset( $dropins[ basename( $file_name ) ] ) ) {
		return true;
	}

	return false;
}

/**
 * Return theme or plugin main folder
 *
 * @param $file_name
 * @param $path
 *
 * @return string
 */
function cerber_get_file_folder( $file_name, $path ) {
	$p_start = mb_strlen( $path ) + 1;
	$folder = mb_substr( $file_name, $p_start );
	if ( $pos = mb_strpos( $folder, DIRECTORY_SEPARATOR ) ) {
		$folder = mb_substr( $folder, 0, $pos );
	}

	return $folder;
}

/**
 * Prepare and save file data to the DB
 *
 * @param array $file A row from the cerber_files table
 *
 * @return bool
 */
function cerber_update_file_info( $file ) {
	static $md5;
	static $hash;

	if ( $md5 === null ) {
		$md5 = array( CERBER_FT_WP, CERBER_FT_PLUGIN, CERBER_FT_THEME, CERBER_FT_ROOT );
	}

	if ( $hash === null ) {
		$hash = array( CERBER_FT_PLUGIN, CERBER_FT_THEME );
	}

	$type = cerber_detect_file( $file['file_name'] );
	$file_name = $file['file_name'];

	// A symbolic link in the content folder? Transform it to a real file name
	if ( $type == CERBER_FT_CNT && is_link( $file['file_name'] ) ) {
		$file_name  = @readlink( $file['file_name'] );
		if ( is_dir( $file_name ) ) {
			$delete_it = true;
		}
		else {
			$delete_it = cerber_db_get_row( 'SELECT COUNT(scan_id) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $file['scan_id'] . ' AND file_name = "' . $file_name . '"' );
		}
		if ( $delete_it ) {
			return cerber_db_query( 'DELETE FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $file['scan_id'] . ' AND file_name_hash = "' . $file['file_name_hash'] . '"' );
		}
	}

	$file_hash = '';
	$file_md5 = '';

	if ( is_readable( $file_name ) ) {
		if ( in_array( $type, $md5 ) ) {
			if ( ! $file_md5 = @md5_file( $file_name ) ) {
				$file_md5 = '';
			}
		}
		if ( in_array( $type, $hash ) ) {
			if ( ! $file_hash = @hash_file( 'sha256', $file_name ) ) {
				$file_hash = '';
			}
		}
	}
	else {
		cerber_log_scan_error( cerber_scan_msg( 0, $file_name ) );
	}

	$size = filesize( $file_name );
	$size = ( is_numeric( $size ) ) ? $size : 0;

	$perms = fileperms( $file_name );
	$perms = ( is_numeric( $perms ) ) ? $perms : 0;

	$mtime = filemtime( $file_name );
	$mtime = ( is_numeric( $mtime ) ) ? $mtime : 0;

	$is_writable = ( is_writable( $file_name ) ) ? 1 : 0;

	if ( ! cerber_db_query( 'UPDATE ' . CERBER_SCAN_TABLE . ' SET file_name = "' . $file_name . '", file_hash = "' . $file_hash . '", file_md5 = "' . $file_md5 . '", file_size = ' . $size . ', file_type = ' . $type . ', file_perms = ' . $perms . ', file_writable = ' . $is_writable . ', file_mtime = ' . $mtime .
                            ' WHERE scan_id = ' . $file['scan_id'] . ' AND file_name_hash = "' . $file['file_name_hash'] . '"' ) ) {
		return false;
	}

	return true;
}


/**
 * Recursively creates a list of files in a given folder with a given filename pattern
 *
 * @param string $root The starting folder with trailing slash
 * @param string $pattern Pattern for filenames to include
 * @param callable $function The function to save the list of files that are passed as an array
 *
 * @return array The total number of folders and files
 */
function cerber_scan_directory( $root, $pattern = null, $function ) {
    static $history = array();
    static $exclude = null;

    // Prevent infinite recursion
	if ( isset( $history[ $root ] ) ) {
		return array( 0, 0 );
	}
	$history[ $root ] = 1;

	// Must be excluded
	if ( $exclude === null ) {
		$exclude = crb_get_settings( 'scan_exclude' );
		if ( ! $exclude ) {
			$exclude = array();
		}
		$d = cerber_get_the_folder();
		if ( is_dir( $d ) ) {
			$exclude[] = $d;
		}
		$exclude   = array_map( function ( $item ) {
			return rtrim( $item, '/\\' );
		}, $exclude );
	}

	if ( ! $pattern ) {
		$pattern = '{*,.*}';
	}

	$dir_counter  = 1;
	$file_counter = 0;
	$root = rtrim( $root, '/\\' ) . DIRECTORY_SEPARATOR;
	$list         = array();

	if ( $files = glob( $root . $pattern, GLOB_BRACE ) ) {
		foreach ( $files as $file_name ) {
			if ( @is_dir( $file_name ) ) {
				continue;
			}
			$file_counter ++;
			$file_name = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $file_name );
			$list[]    = $file_name;
			if ( count( $list ) > 200 ) { // packet size, can affect the DB performance if $function saves file names to the DB
				call_user_func( $function, $list );
				$list = array();
			}
		}
		if ( ! empty( $list ) ) {
			call_user_func( $function, $list );
		}
	}
    elseif ( $files === false ) {
		cerber_log_scan_error( 'PHP glob got error while accessing ' . $root . $pattern );
	}

	if ( $dirs = glob( $root . '*', GLOB_ONLYDIR ) ) {
		foreach ( $dirs as $dir ) {
			if ( in_array( $dir, $exclude ) ) {
				continue;
			}
			list ( $dc, $fc ) = cerber_scan_directory( $dir, $pattern, $function );
			$dir_counter  += $dc;
			$file_counter += $fc;
		}
	}
    elseif ( $files === false ) {
		cerber_log_scan_error( 'PHP glob got error while accessing ' . $root . '*' );
	}

	return array( $dir_counter, $file_counter );
}

/**
 * Packet saving of file names
 *
 * @param array $list
 *
 * @return bool|mysqli_result
 */
function _crb_save_file_names( $list ) {
    global $cerber_scan_mode;
	static $scan_id;

	$list = array_filter( $list );
	if ( empty( $list ) ) {
		return true;
	}

	if ( ! isset( $scan_id ) ) {
		$scan_id = cerber_get_scan_id();
		if ( ! $scan_id ) {
			return false;
		}
	}

	if ( $cerber_scan_mode == 'full' ) {
		$scan_mode = 1;
	}
	else {
		$scan_mode = 0;
	}

	$sql = '';

	foreach ( $list as $filename ) {
		if ( ! @is_file( $filename ) || ! cerber_is_file_type_scan( $filename ) ) {
			continue;
		}
		$sha1 = sha1( $filename );
		if ( cerber_db_get_var( 'SELECT COUNT(scan_id) FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan_id . ' AND file_name_hash = "' . $sha1 . '"' ) ) {
			continue;
		}
		$filename = cerber_real_escape( $filename );

		$sql      .= '(' . $scan_id . ',' . $scan_mode . ',"' . $sha1 . '","' . $filename . '"),';
	}

	if ( ! $sql ) {
		return true;
	}

	$sql = rtrim( $sql, ',' );

	$ret = cerber_db_query( 'INSERT INTO ' . CERBER_SCAN_TABLE . ' (scan_id, scan_mode, file_name_hash, file_name) VALUES ' . $sql );
	if ( ! $ret ) {
		cerber_log_scan_error( 'DB Error occurred while saving filenames' );
	}

	return $ret;
}

/**
 * Return true if a given file must be checked (scanned)
 *
 * @param $filename
 *
 * @return bool
 */
function cerber_is_file_type_scan( $filename ) {
	global $cerber_scan_mode;
	static $code = array( 'php', 'inc' );

	if ( $cerber_scan_mode == 'full' ) {
		return true;
	}
	else {

	    if ( cerber_check_extension( $filename, $code ) ) {
			return true;
		}

		$pos = strrpos( $filename, DIRECTORY_SEPARATOR );
		if ( $pos ) {
			$filename = substr( $filename, $pos + 1 );
		}

		if ( $filename == '.htaccess' ) {
			return true;
		}

		return false;

	}

	return false;
}

/**
 * Check if a filename has an extension from a given list
 *
 * @param $filename
 * @param array $ext_list
 *
 * @return bool
 */
function cerber_check_extension( $filename, $ext_list = array() ) {
	if ( ! is_array( $ext_list ) || empty( $ext_list ) ) {
		return false;
	}

	//$d = cerber_detect_exec_extension();

    $pos = mb_strrpos( $filename, DIRECTORY_SEPARATOR );
	if ( $pos ) {
		$filename = mb_substr( $filename, $pos + 1 );
	}

	$pos = mb_strpos( $filename, '.' );
	if (!$pos) {
		return false;
	}

	$ext = mb_substr( $filename, $pos + 1 );
	$ext = strtolower( $ext );

	// A normal, single extension

	if ( in_array( $ext, $ext_list ) ) {
		return true;
	}

	// No more additional extensions

	if ( substr_count( $ext, '.' ) == 0 ) {
		return false;
	}

	// Multiple "extensions"

	$last = substr( $ext, strrpos( $ext, '.' ) + 1 );
	if ( in_array( $last, $ext_list ) ) {
		return true;
	}
	$first = substr( $ext, 0, strpos( $ext, '.' ) - 1);
	if ( in_array( $first, $ext_list ) ) {
		return true;
	}

	return false;

}

/**
 * Retrieve a value from the key-value storage
 *
 * @param string $key
 * @param integer $id
 * @param bool $unserialize
 *
 * @return bool|array
 */
function cerber_get_set( $key, $id = null, $unserialize = true ) {
	$key = preg_replace( '/[^a-z_\-\d]/i', '', $key );

	$and = '';
	if ( $id !== null ) {
		$and = ' AND the_id = ' . absint( $id );
	}

	$ret = false;

	if ( $row = cerber_db_get_row( 'SELECT * FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE the_key = "' . $key . '" ' . $and ) ) {
		if ( $row['expires'] > 0 && $row['expires'] < time() ) {
			return false;
		}
		if ( $unserialize ) {
			$ret = unserialize( $row['the_value'] );
		}
		else {
			$ret = $row['the_value'];
		}
	}

	return $ret;
}

/**
 * Update/insert value to the key-value storage
 *
 * @param string $key
 * @param $value
 * @param integer $id
 * @param bool $serialize
 * @param integer $expires Unix timestamp (UTC) when this element will be deleted
 *
 * @return bool
 */
function cerber_update_set( $key, $value, $id = null, $serialize = true, $expires = null ) {

	$key = preg_replace( '/[^a-z_\-\d]/i', '', $key );

	if ( $id !== null ) {
		$id = absint( $id );
	}
	else {
		$id = 0;
	}

	if ( $serialize ) {
		$value = serialize( $value );
	}
	$value = cerber_real_escape( $value );

	if ( $expires !== null ) {
		$expires = absint( $expires );
	}
	else {
		$expires = 0;
	}

	if ( false !== cerber_get_set( $key, $id, false ) ) {
		$sql = 'UPDATE ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' SET the_value = "' . $value . '", expires = ' . $expires . ' WHERE the_key = "' . $key . '" AND the_id = ' . $id;
	}
	else {
		$sql = 'INSERT INTO ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' (the_key, the_id, the_value, expires) VALUES ("' . $key . '",' . $id . ',"' . $value . '",' . $expires . ')';
	}

	if ( cerber_db_query( $sql ) ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Delete value from the storage
 *
 * @param string $key
 * @param integer $id
 *
 * @return bool
 */
function cerber_delete_set( $key, $id = null) {

	$key = preg_replace( '/[^a-z_\-\d]/i', '', $key );

	$and = '';
	if ( $id !== null ) {
		$and = ' AND the_id = ' . absint( $id );
	}

	if ( cerber_db_query( 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE the_key = "' . $key . '"' . $and ) ) {
		return true;
	}
	else {
		return false;
	}
}

/**
 * Clean up all expired sets. Usually by cron.
 * @param bool $all if true, deletes all sets that has expiration
 *
 * @return bool
 */
function cerber_delete_expired_set( $all = false ) {
	if ( ! $all ) {
		$where = 'AND expires < ' . time();
	}
	else {
		$where = '';
	}
	if ( cerber_db_query( 'DELETE FROM ' . cerber_get_db_prefix() . CERBER_SETS_TABLE . ' WHERE expires > 0 ' . $where ) ) {
		return true;
	}
	else {
		return false;
	}
}

function cerber_step_desc(){
	static $steps = array(
		'',
		'Scanning folders for files',
		'Parsing the list of files',
		'Verifying the integrity of WordPress',
		'Verifying the integrity of the plugins',
		'Verifying the integrity of the themes',
		'Searching for malicious code'
	);

	return $steps;
}

/**
 * Overwrites values and preserve array hierarchy (keys)
 *
 * @param array $a1
 * @param array $a2
 *
 * @return mixed
 */
function cerber_array_merge_recurively( $a1, $a2 ) {
	foreach ( $a2 as $key => $value ) {
		if ( isset( $a1[ $key ] ) && is_array( $a1[ $key ] ) && is_array( $value ) ) {
			$a1[ $key ] = cerber_array_merge_recurively( $a1[ $key ], $value );
		}
		else {
			$a1[ $key ] = $value;
		}
	}

	return $a1;
}

function cerber_get_short_name( $file_row ) {
	if ( ! $file_row ) {
		return '';
	}
	$len = null;
	switch ( $file_row['file_type'] ) {
		case CERBER_FT_PLUGIN:
			$len = mb_strlen( cerber_get_plugins_dir() );
			break;
		case CERBER_FT_THEME:
			$len = mb_strlen( cerber_get_themes_dir() );
			break;
		case CERBER_FT_UPLOAD:
			$len = mb_strlen( dirname( cerber_get_upload_dir() ) );
			break;
		default:
			if ( 0 === strpos( $file_row['file_name'], rtrim( ABSPATH, '/\\' ) ) ) {
				$len = mb_strlen( ABSPATH ) - 1;
			}
	}

	if ( $len ) {
		return mb_substr( $file_row['file_name'], $len );
	}

	return $file_row['file_name'];
}

function cerber_scanner_dashboard( $msg = '' ) {
	?>
    <div id="crb-scan-display">
        <div id="crb-scan-info" class="scan-tile">
            <table>
                <tr><td>Started</td><td id="crb-started" data-init="-">-</td></tr>
                <tr><td>Finished</td><td id="crb-finished" data-init="-">-</td></tr>
                <tr><td>Duration</td><td id="crb-duration" data-init="-">-</td></tr>
                <tr><td>Performance</td><td id="crb-performance" data-init="-">-</td></tr>
            </table>
        </div>
        <div class="scan-tile">
            <div><p id="crb-total-files" data-init="-">0</p>
                <p><?php _e( 'Files to scan', 'wp-cerber' ); ?></p></div>
        </div>
        <div class="scan-tile">
            <div><p><span id="crb-scanned-files" data-init="-">0</span><span id="crb-scanned-percentage" data-init=""></span></p>
                <p>Scanned</p></div>
        </div>
        <div class="scan-tile">
            <div><p id="crb-critical" data-init="-">0</p>
                <p><?php _e( 'Critical issues', 'wp-cerber' ); ?></p></div>
        </div>
        <div class="scan-tile">
            <div><p id="crb-warning" data-init="-">0</p>
                <p><?php _e( 'Issues total', 'wp-cerber' ); ?></p></div>
        </div>
        <div id="crb-scan-progress">
            <div>
                <div id="the-scan-bar"></div>
            </div>
        </div>

        <p id="crb-scan-message"><?php echo $msg; ?></p>

    </div>
    <div id="crb-scan-details">
        <table class="crb-table" id="crb-browse-files">
            <?php
            $rows = array();
            $rows[] = '<tr class="crb-scan-container" id="crb-wordpress" style=""><td colspan="6">WordPress</td></tr>';
            $rows[] = '<tr class="crb-scan-container" id="crb-muplugins" style=""><td colspan="6">Must use plugins</td></tr>';
            $rows[] = '<tr class="crb-scan-container" id="crb-dropins" style=""><td colspan="6">Drop-ins</td></tr>';
            $rows[] = '<tr class="crb-scan-container" id="crb-plugins" style=""><td colspan="6">Plugins</td></tr>';

            /*
            $plugins = get_plugins();
            foreach ( $plugins as $plugin ) {
	            $rows[] = '<tr class="crb-scan-section" id="' . sha1( $plugin['Name'] ) . '" style="display:none;"></tr>';
            }
            */
            $rows[] = '<tr class="crb-scan-container" id="crb-themes" style=""><td colspan="6">Themes</td></tr>';

            /*$themes = wp_get_themes();
            foreach ( $themes as $theme_folder => $theme ) {
	            $rows[] = '<tr class="crb-scan-section" id="' . sha1( $theme->get( 'Name' ) ) . '" style="display:none;"></tr>';
            }*/

            $rows[] = '<tr class="crb-scan-container" id="crb-uploads" style=""><td colspan="6">Uploads folder</td></tr>';
            $rows[] = '<tr class="crb-scan-container" id="crb-unattended" style=""><td colspan="6">Unattended files</td></tr>';
            echo implode("\n",$rows);
            ?>
        </table>
    </div>

	<?php

	cerber_ref_upload_form();
}

/**
 * Finalizes current AJAX request and sends data to the client
 *
 * @param $data array
 */
function cerber_end_ajax( $data = array() ) {
	global $cerber_db_errors;

	if ( ! $data ) {
		$data = array();
	}
	$data['cerber_db_errors'] = $cerber_db_errors;
	if (!$cerber_db_errors) $data['OK'] = 'OK!';
	echo json_encode( $data );

	wp_die();
}



// ======================================================================================================



function cerber_ref_upload_form() {
	?>
    <div id="crb-ref-upload-dialog" style="display: none;">
        <p>We have not found any integrity data to verify <span id="ref-section-name"></span>.</p>
        <p>You need to upload a ZIP
            archive from which you've installed it. This enables the security scanner to verify the integrity of the
            code and detect malware.</p>
        <form enctype="multipart/form-data">
            <input type="file" name="refile" id="refile" required="required" accept=".zip">
            <input type="submit" name="submit" value="Upload file" class="button button-primary">
            <ul style="list-style: none;">
                <li style="display:none;" class="crb-status-msg">Uploading the file, please wait&#8230;</li>
                <li style="display:none;" class="crb-status-msg">Processing the file, please wait&#8230;</li>
            </ul>
        </form>
    </div>

    <?php
}

/**
 * Upload a reference ZIP archive for a theme or a plugin
 *
 */
add_action( 'wp_ajax_cerber_ref_upload', function () {

	cerber_check_ajax();

	//ob_start(); // Collecting possible junk warnings and notices cause we need clean JSON to be sent

	$error = '';

	$folder = cerber_get_tmp_file_folder();
	if ( is_wp_error( $folder ) ) {
		cerber_end_ajax( array( 'error' => $folder->get_error_message() ) );
	}

	if ( isset( $_FILES['refile'] ) ) {

		// Step 1, saving file

		if ( ! is_uploaded_file( $_FILES['refile']['tmp_name'] ) ) {
			$error = 'Unable to read uploaded file';
		}

		if ( ! cerber_check_extension( $_FILES['refile']['name'], array( 'zip' ) ) ) {
			$error = 'Incorrect file format';
		}

		if ( cerber_detect_exec_extension( $_FILES['refile']['name'] ) ) {
			$error = 'Incorrect file format';
		}

		if ( false !== strpos( $_FILES['refile']['name'], '/' ) ) {
			$error = 'Incorrect filename';
		}

		if ( $error ) {
			cerber_end_ajax( array( 'error' => $error ) );
		}

		if ( false === @move_uploaded_file( $_FILES['refile']['tmp_name'], $folder . $_FILES['refile']['name'] ) ) {
			cerber_end_ajax( array( 'error' => 'Unable to copy file to ' . $folder ) );
		}

	}
	else {

		// Step 2, creating hash

		$result = cerber_need_for_hash();
		if ( is_wp_error( $result ) ) {
			cerber_end_ajax( array( 'error' => $result->get_error_message() ) );
		}
	}

	cerber_end_ajax();

} );

// Process a manually installed/upgraded plugin/theme, part 1
add_filter( 'wp_insert_attachment_data', function ( $data, $postarr ) {
	global $crb_new_zip_file;
	if ( $postarr['context'] == 'upgrader' && $postarr['post_status'] == 'private' && isset( $postarr['file'] ) ) {
		$crb_new_zip_file = $postarr['file'];
	}

	return $data;
}, 10, 2 );

// Process a manually installed/upgraded plugin/theme, part 2
add_action( 'upgrader_process_complete', function ( $object, $extra ) {
	global $crb_new_zip_file;
	if ( empty( $crb_new_zip_file ) ) {
		return;
	}
	switch ( $extra['type'] ) {
		case 'plugin':
		case 'theme':
			if ( file_exists( $crb_new_zip_file ) ) {
				$tmp = cerber_get_tmp_file_folder();
				if ( ! is_wp_error( $tmp ) ) {
					$target_zip = $tmp . basename( $crb_new_zip_file );
					if ( copy( $crb_new_zip_file, $target_zip ) ) {
						wp_schedule_single_event( time() + 5 * MINUTE_IN_SECONDS, 'cerber_scheduled_hash', array( $target_zip ) );
						cerber_need_for_hash( $target_zip );
					}
					else {
					    // Error
                    }
				}
				else {
					// Error
                }
			}
			break;
	}

}, 10, 2 );

// Process a manually installed/upgraded plugin/theme, part 3
add_action( 'cerber_scheduled_hash', 'cerber_scheduled_hash' );
function cerber_scheduled_hash( $zip_file = '' ) {
	$result = cerber_need_for_hash( $zip_file );
	if ( is_wp_error( $result ) ) {
		//cerber_log( $result->get_error_message() );
	}
}

/**
 * Generate hash for an uploaded theme/plugin ZIP archive or for a specified ZIP file.
 * Hash will not be created if a theme/plugin is not installed on the website.
 *
 * @param string $zip_file Be used if set
 * @param bool $delete If true the source ZIP will be deleted
 * @param int $expires Timestamp when hash will expire, 0 = never
 *
 * @return bool|WP_Error
 */
function cerber_need_for_hash( $zip_file = '', $delete = true, $expires = 0 ) {
	$folder     = cerber_get_tmp_file_folder();
	$zip_folder = $folder . 'zip' . DIRECTORY_SEPARATOR;

	if ( ! $zip_file ) {
		if ( ! $files = glob( $folder . '*.zip' ) ) {
			return false;
		}
	}
	else {
		if ( ! is_array( $zip_file ) ) {
			$files = array( $zip_file );
		}
		else {
			$files = $zip_file;
		}
	}

	$fs = cerber_init_wp_filesystem();

	foreach ( $files as $zip_file ) {

		if ( ! file_exists( $zip_file ) ) {
			continue;
		}

		if ( file_exists( $zip_folder ) && ! $fs->delete( $zip_folder, true ) ) {
			return new WP_Error( 'cerber-zip', 'Unable to clean up temporary zip folder ' . $zip_folder );
		}

		$result = cerber_unzip( $zip_file, $zip_folder );

		if ( $delete ) {
			unlink( $zip_file );
		}

		if ( is_wp_error( $result ) ) {
			return new WP_Error( 'cerber-zip', 'Unable to unzip file ' . $zip_file . ' ' . $result->get_error_message() );
		}

		if ( ! $obj = cerber_detect_object( $zip_folder ) ) {
			return new WP_Error( 'cerber-file', 'File ' . basename( $zip_file ) . ' can not be used. Proper program code not found or version mismatch. Please upload another file.' );
		}

		$dir = $obj['src'] . DIRECTORY_SEPARATOR;
		$len = mb_strlen( $dir );

		global $the_file_list;
		$the_file_list = array();

		cerber_scan_directory( $dir, null, function ($list){
		    global $the_file_list;
			$the_file_list = array_merge( $the_file_list, $list );
        } );

		if ( empty( $the_file_list ) ) {
			return new WP_Error( 'cerber-dir', 'No files found in ' . $zip_file );
		}

		$hash = array();

		foreach ( $the_file_list as $file_name ) {
			$hash[ mb_substr( $file_name, $len ) ] = hash_file( 'sha256', $file_name );
		}

		if ( !$obj['single'] ) {
			$b = $obj['src'];
		}
		else {
			$b = $obj['file'];
		}

		//$key = $obj['type'] . sha1( $obj['name'] . basename( $obj['src'] ) );
		$key = $obj['type'] . sha1( $obj['name'] . basename( $b ) );

        if ( ! cerber_update_set( $key, array(
			'name' => $obj['name'],
			'ver'  => $obj['ver'],
			'hash' => $hash,
			'time' => time()
		), 0, true, $expires )
		) {
			return new WP_Error( 'cerber-zip', 'Database error occurred while saving hash' );
		}
	}

	$fs->delete( $zip_folder, true );
    unset($the_file_list);

	return true;
}

/**
 * Retrieve local hash for plugin or theme
 *
 * @param $key
 * @param $version
 *
 * @return bool|mixed
 */
function cerber_get_local_hash( $key, $version ) {
	if ( $local_hash = cerber_get_set( $key ) ) {
		if ( $local_hash['ver'] == $version ) {
			return $local_hash['hash'];
		}
	}

	return false;
}

/**
 * @return string|WP_Error Full path to the folder with trailing slash
 */
function cerber_get_tmp_file_folder() {
	$folder = cerber_get_the_folder();
	if ( is_wp_error( $folder ) ) {
		return $folder;
	}

	$folder = $folder . 'tmp' . DIRECTORY_SEPARATOR;

	if ( ! is_dir( $folder ) ) {
		if ( ! mkdir( $folder, 0755, true ) ) {
			// TODO: try to set permissions for the parent folder
			return new WP_Error( 'cerber-dir', 'Unable to create the tmp directory ' . $folder );
		}
	}

	return $folder;
}

/**
 * Return Cerber's folder. If there is no folder it will be created.
 *
 * @return string|WP_Error  Full path to the folder with trailing slash
 */
function cerber_get_the_folder() {
    static $ret;

	if ( $ret !== null ) {
		return $ret;
	}

	$opt = cerber_get_set( '_cerber_mnemosyne' );
	$u   = wp_upload_dir();

	if ( $opt && isset( $opt[4] ) && isset( $opt[ $opt[4] ] ) ) {
		$key = preg_replace( '/[^a-z0-9]/i', '', $opt[ $opt[4] ] );
		if ( $key ) {
			$folder = $u['basedir'] . DIRECTORY_SEPARATOR . 'wp-cerber-' . $key . DIRECTORY_SEPARATOR;
			if ( is_dir( $folder ) ) {
				if ( ! wp_is_writable( $folder ) ) {
					if ( ! chmod( $folder, 0755 ) ) {
						return new WP_Error( 'cerber-dir', __( 'The directory is not writable', 'wp-cerber' ) . ' ' . $folder );
					}
				}
				cerber_lock_the_folder( $folder );

				$ret = $folder;
				return $ret;
			}
		}
	}

	// Let's create the folder

	$key    = substr( str_shuffle( '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, rand( 16, 20 ) );
	$folder = $u['basedir'] . DIRECTORY_SEPARATOR . 'wp-cerber-' . $key . DIRECTORY_SEPARATOR;

	if ( ! mkdir( $folder, 0755, true ) ) {
		// TODO: try to set permissions for the parent folder
		return new WP_Error( 'cerber-dir', __( 'Unable to create WP CERBER directory', 'wp-cerber' ) . ' ' . $folder );
	}

	if ( ! cerber_lock_the_folder( $folder ) ) {
		return new WP_Error( 'cerber-dir', 'Unable to lock the directory ' . $folder );
	}

	$k      = substr( str_shuffle( '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ' ), 0, rand( 16, 20 ) );
	$i      = rand( 5, 10 );
	if ( ! cerber_update_set( '_cerber_mnemosyne', array( rand( 0, 3 ) => $k, 4 => $i, $i => $key ) ) ) {
		return new WP_Error( 'cerber-dir', 'Unable to save option' );
	}

	$ret = $folder;
	return $ret;
}

/**
 * Make a folder not accessible from the web
 *
 * @param $folder string
 *
 * @return bool
 */
function cerber_lock_the_folder( $folder ) {
	if ( $f = fopen( $folder . '.htaccess', 'w' ) ) {
		if ( fwrite( $f, 'deny from all' ) ) {
			fclose( $f );

			return true;
		}
	}

	return false;
}

function cerber_unzip( $file_name, $folder ) {
	cerber_init_wp_filesystem();

	return unzip_file( $file_name, $folder );

}

/**
 * @return WP_Error|WP_Filesystem_Direct
 */
function cerber_init_wp_filesystem() {
	global $wp_filesystem;

	require_once( ABSPATH . 'wp-admin/includes/file.php' );

	add_filter( 'filesystem_method', '__ret_direct' );
	if ( ! WP_Filesystem() ) {
		return new WP_Error( 'cerber-file', 'Unable to init WP_Filesystem' );
	}
	remove_filter( 'filesystem_method', '__ret_direct' );

	return $wp_filesystem;
}

function __ret_direct() {
	return 'direct';
}

function cerber_detect_object( $folder = '' ) {

    // Look for a theme

	$the_folder = false;

	$dirs = glob( $folder . '*', GLOB_ONLYDIR );
	if ( $dirs ) {
		$the_folder = $dirs[0]; // we expect only one subfolder
		if ( ! file_exists( $the_folder ) ) {
			$the_folder = false;
		}
	}

	if ( $result = cerber_check_theme_data( $the_folder ) ) {
		return array(
			'type'   => CRB_HASH_THEME,
			'name'   => $result->get( 'Name' ),
			'ver'    => $result->get( 'Version' ),
			'src'    => $the_folder,
			'single' => false,
		);
	}

	// Look for a plugin

	$files = glob( $folder . '*.php' ); // single file plugin
	if ( ! $files && $the_folder ) { // plugin with folder
		$files = glob( $the_folder . DIRECTORY_SEPARATOR . '*.php' );
		$single = false;
	}
	else {
	    $single = true;
    }

	if ( ! $files ) {
		return false;
	}

	require_once( ABSPATH . 'wp-admin/includes/plugin.php' );
	foreach ( $files as $file_name ) {
		$plugin_data = get_plugin_data( $file_name );
		if ( ! empty ( $plugin_data['Name'] ) && ! empty ( $plugin_data['Version'] ) ) {
			foreach ( get_plugins() as $key => $plugin ) {
				if ( $plugin['Name'] == $plugin_data['Name'] && $plugin['Version'] == $plugin_data['Version'] ) {

					return array(
						'type'   => CRB_HASH_PLUGIN,
						'name'   => $plugin_data['Name'],
						'ver'    => $plugin_data['Version'],
						'data'   => $plugin_data,
						'src'    => dirname( $file_name ),
						'single' => $single,
						'file'   => $file_name
					);
				}
			}

		}
	}


	return false;
}

/**
 * @param string $folder A folder with theme files
 *
 * @return bool|WP_Theme
 */
function cerber_check_theme_data( $folder ) {

	$style = $folder . DIRECTORY_SEPARATOR . 'style.css';
	if ( ! file_exists( $style ) ) {
		return false;
	}

    // See class-wp-theme.php
	static $theme_headers = array(
		'Name'        => 'Theme Name',
		'ThemeURI'    => 'Theme URI',
		'Description' => 'Description',
		'Author'      => 'Author',
		'AuthorURI'   => 'Author URI',
		'Version'     => 'Version',
		'Template'    => 'Template',
		'Status'      => 'Status',
		'Tags'        => 'Tags',
		'TextDomain'  => 'Text Domain',
		'DomainPath'  => 'Domain Path',
	);
	$theme_folder = basename( $folder );
	$headers = get_file_data( $style, $theme_headers, 'theme' );
	// $headers['Version'] means just theme, $headers['Template'] means child theme
	if ( ! empty ( $headers['Name'] ) && ( ! empty ( $headers['Version'] ) || ! empty ( $headers['Template'] ) ) ) {
		$themes = wp_get_themes();
		foreach ( $themes as $the_folder => $theme ) {
			if ( $the_folder != $theme_folder ) {
				continue;
			}
			if ( $headers['Name'] == $theme->get( 'Name' ) ) {
				if ( ! empty ( $headers['Version'] ) && ( $headers['Version'] == $theme->get( 'Version' ) ) ) {
					return $theme;
				}
				if ( ! empty ( $headers['Template'] ) && ( $headers['Template'] == $theme->get( 'Template' ) ) ) {
					return $theme;
				}
			}
		}
	}

	return false;
}

/**
 * File viewer, server side AJAX
 *
 */
add_action( 'wp_ajax_cerber_view_file', function () {
	global $cerber_db_errors;

	cerber_check_ajax();

	$file_name = $_GET['file'];
	if ( ! @is_file( $file_name ) ) {
		wp_die( 'I/O Error' );
	}

	$file_size = filesize( $file_name );

	if ( $file_size > 8000000 ) {
		wp_die( 'Error: This file is too large to display.' );
	}

	if ( $file_size <= 0 ) {
		wp_die( 'The file is empty.' );
	}

	$scan_id = absint( $_GET['scan_id'] );

	$the_file = cerber_db_get_row( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan_id . ' AND file_name = "' . $file_name . '"' );

	if ( ! $the_file ) {
		wp_die( 'Access error.' );
	}

	if ( ! $source = file_get_contents( $file_name ) ) {
		wp_die( 'Error: Unable to load file.' );
	}

	$source = htmlspecialchars( $source, ENT_SUBSTITUTE );

	if ( ! $source ) {
		$source = 'Unable to display the content of the file. This file contains non-printable characters.';
	}

	if ( cerber_detect_exec_extension( $file_name ) || cerber_check_extension( $file_name, array( 'js', 'css', 'inc' ) ) ) {
		$paint = true;
	}
	else {
		$paint = false;
	}

	$overlay = '';
	if ( $paint ) {
		$overlay = '<div id="crb-overlay">Loading, please wait...</div>';
	}

	$sh_url  = plugin_dir_url( __FILE__ ) . 'assets/sh/';
	$sheight = absint( $_GET['sheight'] ) - 100; // highlighter is un-responsible, so we need tell him the real height
	$c_height = absint( $_GET['sheight'] );

	?>
    <!DOCTYPE html><html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <script type="text/javascript" src="<?php echo $sh_url ?>scripts/shCore.js"></script>
        <script type="text/javascript" src="<?php echo $sh_url; ?>scripts/shBrushPhp.js"></script>
        <link href="<?php echo $sh_url; ?>styles/shCore.css" rel="stylesheet" type="text/css" />
        <link href="<?php echo $sh_url; ?>styles/shThemeDefault.css" rel="stylesheet" type="text/css" />
        <style type="text/css" media="all">
            body {
                overflow: hidden;
                font-family: 'Roboto', sans-serif;
                font-size: 14px;
            }

            #crb-overlay {
                display: flex;
                justify-content: center;
                align-items: center;
                text-align: center;
                background-color: #fff;
                position: fixed;
                width: 100%;
                height: 100%
                z-index: 2;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
            }

            #crb-issue {
                border-left: 3px solid crimson;
                background-color: #eee;
                padding: 1em;
                overflow: auto;
            }

            #crb-file-content {
            <?php
            if (!$paint) {
                echo '
                max-height: '.$sheight .'px;
                overflow: auto;
                padding: 15px;
                ';
            }
            else {
                echo 'overflow: hidden;';
            }
            ?>
            }

            .syntaxhighlighter {
                max-height: <?php echo $sheight; ?>px;
            }

            .syntaxhighlighter code {
                font-family: Menlo, Consolas, Monaco, monospace !important;
                font-size: 13px !important;
            }

            .syntaxhighlighter .gutter .line{
                border-right: 3px solid #c7c7c7 !important;
            }

        </style>
    </head>

    <body>

	<?php

	echo $overlay;

	echo '<pre id="crb-file-content" class="brush: php; toolbar: false;">' . $source . '</pre>';

	if ( $the_file ) {
		echo '<div id="crb-issue">Issue: ' . cerber_get_issue_desc( $the_file['scan_status'] ) . '</div>';
	}

	if ( $paint ) :
		?>

        <script type="text/javascript">
            SyntaxHighlighter.defaults["highlight"];
            SyntaxHighlighter.all();
            function crb_waitUntilRender() {
                var overlay = document.getElementById("crb-overlay").style.visibility = "hidden";
            }
            var intervalID = setInterval(crb_waitUntilRender, 200);


        </script>

		<?php

	endif;

	?>

    </body>
    </html>

	<?php

	wp_die();
} );


/**
 * Deleting files, server side AJAX
 *
 */
add_action( 'wp_ajax_cerber_scan_delete_files', function () {
	global $cerber_db_errors;

	cerber_check_ajax();

	if ( empty( $_POST['files'] ) || empty( $_POST['scan_id'] ) ) {
		wp_die( 'Error!' );
	}

	$scan_id = absint( $_POST['scan_id'] );

	if ( ! cerber_get_scan( $scan_id ) ) {
		wp_die( 'Error!' );
	}

	$list = array();
	$i = 0;
	$errors = array();

	foreach ( $_POST['files'] as $file_name ) {

		if ( ! is_file( $file_name ) ) {
			continue;
		}

		$the_file = cerber_db_get_row( 'SELECT * FROM ' . CERBER_SCAN_TABLE . ' WHERE scan_id = ' . $scan_id . ' AND file_name = "' . $file_name . '"' );
		if ( ! $the_file ) {
			continue;
		}

		$result = cerber_quarantine_move( $file_name, $scan_id );
		if ( is_wp_error( $result ) ) {
			$errors[] = $result->get_error_message();
		}
        elseif ( ! $result ) {
			$errors[] = 'Not possible';
		}
		else {
			$i ++;
			$list[] = $file_name;
		}

	}

	cerber_end_ajax( array( 'errors' => $errors, 'number' => $i, 'deleted' => $list ) );

});
/**
 * Move files to the quarantine folder
 *
 * @param string $file_name
 * @param integer $scan_id
 *
 * @return bool|WP_Error
 */
function cerber_quarantine_move( $file_name, $scan_id ) {
	static $folder;

	$scan_id = absint( $scan_id );
	if ( ! is_file( $file_name ) || ! $scan_id ) {
		return false;
	}
	if ( ! cerber_can_be_deleted( $file_name, true ) ) {
		return new WP_Error( 'cerber-del', "This file can't be deleted: ". $file_name );
	}

	if ( $folder === null ) {
		$folder = cerber_get_the_folder();
	}
	if ( is_wp_error( $folder ) ) {
		return $folder;
	}

	$quarantine = $folder . 'quarantine' . DIRECTORY_SEPARATOR . $scan_id . DIRECTORY_SEPARATOR;

	if ( ! is_dir( $quarantine ) ) {
		if ( ! mkdir( $quarantine, 0755, true ) ) {
			// TODO: try to set permissions for the parent folder
			return new WP_Error( 'cerber-dir', 'Unable to create the quarantine directory ' . $quarantine );
		}
	}
	else {
		if ( ! chmod( $quarantine, 0755 ) ) {
			return new WP_Error( 'cerber-dir', 'Unable to set folder permissions for ' . $quarantine );
		}
	}

	cerber_lock_the_folder( $quarantine );

	// Preserve original paths for deleted files in a restore file
	$restore = $quarantine . '.restore';
	if ( ! file_exists( $restore ) ) {
		if ( ! $f = fopen( $restore, 'w' ) ) {
			return new WP_Error( 'cerber-quar', 'Unable to create a restore file.' );
		}
		fwrite( $f, 'Information for restoring deleted files.' . PHP_EOL
		            . 'Deletion date | Deleted file => Original file to copy to restore.' . PHP_EOL
		            . '-----------------------------------------------------------------'
		            . PHP_EOL . PHP_EOL );
	}
	else {
		if ( ! $f = fopen( $restore, 'a' ) ) {
			return new WP_Error( 'cerber-quar', 'Unable to write to the restore file.');
		}
	}

	// Avoid file name collisions
	$new_name = $quarantine . basename( $file_name );
	if ( file_exists( $new_name ) ) {
		$i = 2;
		while ( file_exists( $new_name ) ) {
			$new_name = $quarantine . basename( $file_name ) . '.' . $i;
			$i ++;
		}
	}

	if ( ! @rename( $file_name, $new_name ) ) {
		return new WP_Error( 'cerber-quar', 'Unable to move file ' . $file_name . '. Check the file folder permissions.' );
	}

	// Save restoring info
	fwrite( $f, PHP_EOL . cerber_date( time() ) . ' | ' . basename( $new_name ) . ' => ' . $file_name );
	fclose( $f );

	return true;
}

/**
 * Some files can't be deleted...
 *
 * @param $file_name
 * @param bool $check_inclusion
 *
 * @return bool
 */
function cerber_can_be_deleted( $file_name, $check_inclusion = false ) {
	static $abspath;

	if ( ! file_exists( $file_name ) || ! is_file( $file_name ) || is_link( $file_name ) ) {
		return false;
	}
	//if ( basename( $file_name ) == '.htaccess' ) {
	if ( cerber_is_htaccess( $file_name ) || cerber_is_dropin( $file_name ) ) {
		return false;
	}

	if ( $check_inclusion && in_array( $file_name, get_included_files() ) ) {
		return false;
	}

    if ( basename( $file_name ) == 'wp-config.php' ) {
		// All stuff can contain different directory separators, make them the same
	    if ( ! $abspath ) {
			$abspath = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, ABSPATH );
		}
		$file_name = str_replace( array( '/', '\\' ), DIRECTORY_SEPARATOR, $file_name );

		if ( $file_name == ABSPATH . 'wp-config.php' ) {
			return false;
		}
		if ( ! file_exists( ABSPATH . 'wp-config.php' ) && $file_name == dirname( ABSPATH ) . DIRECTORY_SEPARATOR . 'wp-config.php' ) {
			return false;
		}
	}

	return true;
}

/**
 * Is time for current step is over?
 *
 * @param int $limit
 *
 * @return bool True if the time of execution of the current step is over
 */
function cerber_exec_timer( $limit = CERBER_MAX_SECONDS) {
	static $start;
	if ( $start === null ) {
		$start = time();
	}

	if ( ( time() - $start ) > $limit ) {
		return true;
	}

	return false;
}

function cerber_scan_msg( $id, $txt = '' ) {
	$m = array( __( 'Unable to open file', 'wp-cerber' ) );

	$ret = '???';
	if ( isset( $m[ $id ] ) ) {
		$ret = $m[ $id ];
	}
	if ( $txt ) {
		//sprintf()
		$ret .= ' ' . $txt;
	}

	return $ret;
}