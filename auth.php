<?php
// must be run within Dokuwiki
if(!defined('DOKU_INC')) die();

require_once('class-phpass.php');

/**
 * WordPress authentication backend
 *
 * @license    GPL 2 (http://www.gnu.org/licenses/gpl.html)
 * @author     Tim Hawes <me@timhawes.com>
 */
class auth_plugin_authwordpress extends DokuWiki_Auth_Plugin {

    public function __construct() {
        parent::__construct();
	$this->cando['logout'] = true;
    }

    function checkPass($user, $pass) {
      $userinfo = $this->getUserData($user);
      if ($userinfo === false) return false;
    
      $wp_hasher = new PasswordHash(8, true);
      return $wp_hasher->CheckPassword($pass, $userinfo['pass']);
    }
    
    function getUserData($user) {
      global $conf;
      
      $dbh = new PDO($conf['auth']['wordpress']['dsn'], $conf['auth']['wordpress']['username'], $conf['auth']['wordpress']['password']);
      
      $stmt = $dbh->prepare("SELECT id, user_login, user_pass, user_email, display_name, meta_value AS groups FROM wp_users JOIN wp_usermeta on wp_users.id = wp_usermeta.user_id WHERE meta_key = 'wp_capabilities' AND user_login = :user LIMIT 1;");
      $stmt->bindParam(':user', $user);
      $info = null;
      
      if ($stmt->execute()) {
	while ($row = $stmt->fetch()) {
	  $info = array();
	  $info['user'] = $row['user_login'];
	  $info['name'] = $row['display_name'];
	  $info['pass'] = $row['user_pass'];
	  $info['mail'] = $row['user_email'];
	  $info['grps'] = array_keys(unserialize($row['groups']));
	}
      }
      
      return $info;
      
    }
    
    function cleanUser($user) {
      return strtolower($user);
    }
    
}

?>
