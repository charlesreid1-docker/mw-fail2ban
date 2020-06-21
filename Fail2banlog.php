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
        if ( $response->status == "PASS" ) return true; // Do not log success or password send request, continue to next hook
        $time = date ("Y-m-d H:i:s T");
        $ip = $_SERVER['REMOTE_ADDR']; // wfGetIP() may yield different results for proxies

        // append a line to the log
        error_log("$time MediaWiki authentication error from $ip\n",0);
        return true; // continue to next hook
}
?>
