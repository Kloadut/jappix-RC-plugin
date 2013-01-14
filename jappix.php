<?php
/**
 * Jappix plugin
 *
 * Jappix-mini roundcube integration
 *
 * @author Alexis Gavoty for LINAGORA
 * @license AGPLv3 for OBM <http://obm.org/content/obm-license>
 *
 */
class jappix extends rcube_plugin
{

    // Define tasks where Jappix-mini will be display
    public $task = 'login|logout|mail|settings|addressbook';
    private $rcmail;

    function init()
    {
        //Include our own config file
        require_once('config.inc.php');

        // Load rcmail instance and config
        $this->load_config();
        $this->rcmail = rcmail::get_instance();

        // Check if local JS is indicated in the configuration, or fetch files from Jappix static server
        if ($this->rcmail->config->jappixJS != "")
            $this->include_script($this->rcmail->config->jappixJS);
        else
            $this->include_script($this->rcmail->config->jappixStatic . 'php/get.php?l=' . $this->rcmail->config->jappixStaticLocale . '&t=js&g=mini.xml');

        // Include additionnal local tweak files
        $this->include_script('inc/jappix.js');
        $this->include_stylesheet('inc/jappix.css');

        // Hook declarations
        $this->add_hook('render_page', array(
            $this,
            'render_page'
        ));
        //$this->add_hook('session_destroy', array(
        //    $this,
        //    'session_destroy'
        //));

    }

    /**
     * Check if the Jabber ID is White/Blacklisted
     *
     * @param string $xid The concern Jabber ID
     * @return boolean true|false
     */
    function checkJabberID($xid)
    {
        // Case of default authentication
        if ($xid == "'+getDB(\'jappix-mini-login\', \'xid\')+'")
            $xid = $_SESSION['username'];

        // Check if whitelisted
        if ($this->jabberIdWhitelist != "") {
            $jabberIdWhitelistArray = array_map('trim', explode(",", $this->jabberIdWhitelist));
            $whitelisted            = 0;
            foreach ($jabberIdWhitelistArray as $whiteJabberId) {
                if ($xid == $whiteJabberId)
                    $whitelisted++;
            }
            return ($whitelisted > 0) ? true : false;

        // Or if blacklisted
        } elseif ($this->jabberIdBlacklist != "") {
            $jabberIdBlacklistArray = array_map('trim', explode(",", $this->jabberIdBlacklist));
            $blacklisted            = 0;
            foreach ($jabberIdBlacklistArray as $blackJabberId) {
                if ($xid == $blackJabberId)
                    $blacklisted++;
            }
            return ($blacklisted > 0) ? false : true;

        // ...Or not
        } else {
            return true;
        }
    }

    /**
     * "render_page" hook
     *
     * @param array $args template and content
     */
    function render_page($args)
    {
        $framed        = $_GET["_framed"];
        $currentAction = $_GET["_action"];
	    $post_framed = $_POST["_framed"];
        $jsInclude = "";
        $jsInclude2 = "";

        // Check the authentication type and return auth vars
        if ($this->jabberAuthType == "http_auth") {
            $xid = $_SERVER['PHP_AUTH_USER'] . $this->jabberAuthUserAppend;
            $pwd = $_SERVER['PHP_AUTH_PW'];
        } elseif ($this->jabberId != "" && $this->jabberPwd != "") {
            $xid = $this->jabberId;
            $pwd = $this->jabberPwd;
        } else {
            $xid = "'+getDB(\'jappix-mini-login\', \'xid\')+'";
            $pwd = "'+getDB(\'jappix-mini-login\', \'pwd\')+'";
            $jsInclude = "if (getDB('jappix-mini', 'dom')) {";
            $jsInclude2 = "} else { resetDB(); }";
        }

        // Check if jappix-mini get the right environment
        if ($this->checkJabberID($xid) && $this->jabberDomain != "" && $framed != 1 && $post_framed != 1 && $currentAction != "compose") {

            $this->rcmail->output->add_footer("<script type='text/javascript'>

                $(document).ready(function () {

                    ". $jsInclude ."
                        HOST_MAIN = '" . $this->jabberDomain . "';
                        HOST_MUC = '" . $this->jappixMuc . "';
                        HOST_ANONYMOUS = '" . $this->jabberDomain . "';
                        HOST_BOSH_MINI = '" . $this->jappixBosh . "';
                        HOST_STATIC = '" . $this->jappixStatic . "';
                        JAPPIX_STATIC = '" . $this->jappixStatic . "';
                        ". $this->jappixVars ."
                        launchMini(false, false, '" . $this->jabberDomain . "','" . $xid . "' ,'" . $pwd . "' );
			            installPopUp();
			            //rcmail.message_list.key_press = function(){};
                        //rcmail.message_list.key_down = function(){};

                    ". $jsInclude2 ."
                });
            </script>");

        // Case of iframe
        } else {
            $this->rcmail->output->add_footer("<script type='text/javascript'>installPopUp();</script>");
        }
    }

}
