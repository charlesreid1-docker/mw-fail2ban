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
    $remote_ip = $wgRequest->getHeader('X-Forwarded-For');

    // append a line to the logs
    error_log("$time MediaWiki authentication error: failed login attempt from IP $remote_ip");

    return true; // continue to next hook
}
?>
