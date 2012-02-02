<?php
/*
 * Copyright � 2008-2010 Garrett Brown <http://www.mediawiki.org/wiki/User:Gbruin>
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 * 
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 * 
 * You should have received a copy of the GNU General Public License along
 * with this program. If not, see <http://www.gnu.org/licenses/>.
 */

/**
 * Facebook for MediaWiki.
 * 
 * Integrates Facebook authentication and features into MediaWiki.
 * 
 * Info is available at <http://www.mediawiki.org/wiki/Extension:Facebook>.
 * Limited support is available at
 * <http://www.mediawiki.org/wiki/Extension_talk:Facebook>.
 * 
 * @file
 * @ingroup Extensions
 * @author Garrett Brown, Sean Colombo
 * @copyright Copyright � 2010 Garrett Brown, Sean Colombo
 * @license http://www.gnu.org/copyleft/gpl.html GNU General Public License 2.0 or later
 */


/*
 * Not a valid entry point, skip unless MEDIAWIKI is defined.
 */
if ( !defined( 'MEDIAWIKI' ) ) {
	die( 'This file is a MediaWiki extension, it is not a valid entry point' );
}

// Make it so that the code will survive the push until the config gets updated.
$wgEnablePreferencesExt = true;

// Facebook version
define( 'MEDIAWIKI_FACEBOOK_VERSION', '3.0_r403, February 5, 2011' );

// Magic string to use in substitution (must be defined prior to including config.php).
define( 'FACEBOOK_LOCALE', '%LOCALE%');

/*
 * Add information about this extension to Special:Version.
 */
$wgExtensionCredits['specialpage'][] = array(
	'path'           => __FILE__,
	'name'           => 'Facebook for MediaWiki',
	'author'         => 'Garrett Brown, Sean Colombo, Tomek Odrobny',
	'url'            => 'http://www.mediawiki.org/wiki/Extension:Facebook',
	'descriptionmsg' => 'facebook-desc',
	'version'        => MEDIAWIKI_FACEBOOK_VERSION,
);

/*
 * Initialization of the autoloaders and special extension pages.
 */
$dir = dirname( __FILE__ ) . '/';
// Load the default configuration
// It's recommended that you override these in LocalSettings.php
include_once $dir . 'config.default.php';
// If config.php exists, import those settings over the default ones
if (file_exists( $dir . 'config.php' )) {
	require_once $dir . 'config.php';
}
// Load the PHP SDK
require_once $dir . 'php-sdk/facebook.php';

// Install the extension
$wgExtensionFunctions[] = 'FacebookInit::init';

if( !empty( $wgFbEnablePushToFacebook ) ) {
	// Need to include it explicitly instead of autoload since it has initialization
	// code of its own. This should be done after Facebook::init is added to
	// $wgExtensionFunctions so that Facebook gets fully initialized first.
	require_once $dir . 'FacebookPushEvent.php';
}

$wgExtensionMessagesFiles['Facebook'] = $dir . 'Facebook.i18n.php';
$wgExtensionMessagesFiles['FBPushEvents'] = $dir . 'pushEvents/FBPushEvents.i18n.php';
$wgExtensionMessagesFiles['FacebookLanguage'] = $dir . 'FacebookLanguage.i18n.php';
$wgExtensionAliasesFiles['Facebook'] = $dir . 'Facebook.alias.php';

$wgAutoloadClasses['FacebookAPI'] = $dir . 'FacebookAPI.php';
$wgAutoloadClasses['FacebookDB'] = $dir . 'FacebookDB.php';
$wgAutoloadClasses['FacebookHooks'] = $dir . 'FacebookHooks.php';
$wgAutoloadClasses['FacebookInit'] = $dir . 'FacebookInit.php';
$wgAutoloadClasses['FacebookLanguage'] = $dir . 'FacebookLanguage.php';
$wgAutoloadClasses['FacebookProfilePic'] = $dir . 'FacebookProfilePic.php';
$wgAutoloadClasses['FacebookUser'] = $dir . 'FacebookUser.php';
$wgAutoloadClasses['FacebookXFBML'] = $dir . 'FacebookXFBML.php';
$wgAutoloadClasses['SpecialConnect'] = $dir . 'SpecialConnect.php';
$wgAutoloadClasses['ChooseNameTemplate'] = $dir . 'templates/ChooseNameTemplate.class.php';

$wgSpecialPages['Connect'] = 'SpecialConnect';

// Define new autopromote condition (use quoted text, numbers can cause collisions)
define( 'APCOND_FB_INGROUP',   'fb*g' );
define( 'APCOND_FB_ISOFFICER', 'fb*o' );
define( 'APCOND_FB_ISADMIN',   'fb*a' );

// rt#68127 - Giving basic permissions to other groups might open security holes
// See <http://trac.wikia-code.com/changeset/27160> and <http://trac.wikia-code.com/changeset/27928> for fix
$wgGroupPermissions['fb-user'] = $wgGroupPermissions['user']; // Create a new group for Facebook users

$wgAjaxExportList[] = 'Facebook::disconnectFromFB';
$wgAjaxExportList[] = 'SpecialConnect::getLoginButtonModal';
$wgAjaxExportList[] = 'SpecialConnect::ajaxModalChooseName'; 
$wgAjaxExportList[] = 'SpecialConnect::checkCreateAccount';

// These hooks need to be hooked up prior to init() because runhooks may be called for them before init is run.
$wgFbHooksToAddImmediately = array( 'SpecialPage_initList' );
foreach( $wgFbHooksToAddImmediately as $hookName ) {
	$wgHooks[$hookName][] = "FacebookHooks::$hookName";
}
