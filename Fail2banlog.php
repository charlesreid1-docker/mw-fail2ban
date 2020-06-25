<?php
if ( !defined( 'MEDIAWIKI' ) ) {
	echo "This file is not a valid entry point.";
	exit( 1 );
}

$wgExtensionCredits['other'][] = array(
       'name' => 'fail2banlog',
       'author' => array ( 'Laurent Chouraki', 'Andrey N. Petrov' ),
       'url' => 'https://www.mediawiki.org/wiki/Extension:Fail2banlog',
       'description' => 'Writes a text file with IP of failed login as an input for the fail2ban software'
       );

$wgHooks['AuthManagerLoginAuthenticateAudit'][] = 'logBadLogin';
 
function logBadLogin($response, $user, $username) {
    global $wgRequest;

    if ( $response->status == "PASS" ) return true; // Do not log success or password send request, continue to next hook
    $time = date ("Y-m-d H:i:s T");
    $remote_addr_ip = $_SERVER['REMOTE_ADDR'];
    $get_ip = $wgRequest->getIP();
    $xff = $wgRequest->getHeader( 'X-Forwarded-For' );
    $xff2 = $_SERVER['HTTP_X_FORWARDED_FOR'];
    $client_ip = $_SERVER['HTTP_CLIENT_IP'];

    // append a line to the log
    error_log("$time MediaWiki authentication error from IP:");
    error_log("$time - _SERVER remote address: $remote_addr_ip");
    error_log("$time - wgrequest getIP(): $get_ip");
    error_log("$time - wgrequest getheader xff: $xff");
    error_log("$time - _SERVER xff: $xff2");
    error_log("$time - _SERVER client ip: $client_ip");

    return true; // continue to next hook
}
?>
