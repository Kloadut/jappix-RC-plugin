<?php

/**
 * Jappix-mini plugin for Roundcube
 *
 * @author: Alexis Gavoty for LINAGORA <agavoty@linagora.com>
 * @license: AGPLv3 for OBM <http://obm.org/content/obm-license>
 *
 * Note: Leave configuration variables blank if unused
 *
 */


/**
 * Hosts configuration (required)
 *
 */

// XMPP host
$this->jabberDomain = "example.org";

// Bosh host (i.e: 'http://example.org:5280/http-bind/')
// Default: 'https://bind.jappix.com/';
$this->jappixBosh = 'https://bind.jappix.com/';

// MUC host (groupchat)
$this->jappixMuc = 'conference.jappix.com';



/**
 * Authentication type
 *
 * default: Roundcube login form credentials
 * http_auth: HTTP header variables
 * CAS: to be added
 *
 */

$this->jabberAuthType = 'default'; // default | http_auth
$this->jabberAuthUserAppend = ''; // Optionnal append to username in http_auth (i.e: '@domain.net')



/**
 * Javascript configuration
 *
 * Either fetch JS from a standard Jappix server, or include it from a local file
 * Leave blank unused variables
 *
 */

// Remote
$this->jappixStatic = 'https://static.jappix.com/'; // Jappix remote host
$this->jappixStaticLocale = 'fr';

// OR local
$this->jappixJS = ''; // Default: 'inc/local/mini-min-fr.js';



/**
 * Whitelist/Blacklist
 *
 * Allow or deny specific Jabber IDs (comma separated)
 *
 * Example: "foo@jabber.org,foo2@example.com"
 *
 */

$this->jabberIdWhitelist = '';
// OR
$this->jabberIdBlacklist = '';



/**
 * Additionnal configurations (optionnal)
 *
 */

// Additionnal static JS vars
$this->jappixVars = ''; // Example: 'MINI_ANIMATE = true; MINI_GROUPCHATS = ["room@muc.jappix.com"];'

// Autologin credentials (auth type must be 'default')
$this->jabberId = '';
$this->jabberPwd = '';
