<?php
/**
 * PageCarton
 *
 * LICENSE
 *
 * @category   PageCarton
 * @package    Ayoola_Event_NewSession
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 * @version    $Id: NewSession.php 11.01.2011 9.23am ayoola $
 */

/**
 * @see Ayoola_
 */

//require_once 'Ayoola/.php';

/**
 * @category   PageCarton
 * @package    Ayoola_Event_NewSession
 * @copyright  Copyright (c) 2011-2012 Ayoola Online Inc. (http://www.pagecarton.com)
 * @license    GNU General Public License version 2 or later; see LICENSE.txt
 */

class Ayoola_Event_NewSession extends Ayoola_Event
{

    /**
     * Plays the class
     *
     */
    public function init()
    {
        //    See if a user is still logged in through cookie
        //    Try to login with persistent cookie variables
        do {

            //    break;
            if (isset($_COOKIE[Ayoola_Session::getName()])) {
                //    new session
                //        break;
            }
            $auth = new Ayoola_Access();

            //    auto auth
            //    because of cPanel
            if (!empty($_REQUEST['pc_auto_auth'])) {
                $autoAuthFile = SITE_APPLICATION_PATH . '/auto-auth/' . $_REQUEST['pc_auto_auth'];
                //    var_export( file_get_contents( $autoAuthFile ) );
                //    exit();
                if (is_file($autoAuthFile) && is_writable($autoAuthFile)) {
                    $userInfo = json_decode(file_get_contents($autoAuthFile), true);

                    if (unlink($autoAuthFile)) {
                        if (!empty($_REQUEST['pc_auto_signup'])) {
                            $class = new Application_User_AdminCreator(array('fake_values' => $userInfo));
                            $class->initOnce();
                            //    echo $class->view();
                        }
                        $auth->logout();
                        $auth->getStorage()->store($userInfo);
                        //    var_export( file_get_contents( $autoAuthFile ) );
                        //    exit();
                        break;
                    }
                }
            }

            //    var_export( $_COOKIE );

            $loginObject = new Ayoola_Access_Login(array('no_init' => true));

            //    User doesn't have pesistent login cookie
            $cookieValue = isset($_COOKIE[$loginObject->getObjectName()]) ? $_COOKIE[$loginObject->getObjectName()] : "";
            if (empty($cookieValue)) {break;}
            //    var_export( $_COOKIE );

            //    User is currently logged in
            if ($userInfo = $auth->getUserInfo()) {break;}
            //    var_export( $_COOKIE );
            //    self::v( $cookieValue );

            list($cookieUserid, $cookiePassword, $cookieCreationTime, $strict) = explode(':', base64_decode($cookieValue));
            //        self::v( base64_decode( $cookieValue ) );
            //    self::v( $cookieUserid );
            //        self::v( $cookiePassword );
            //        self::v( $cookieCreationTime );
            //        self::v( $strict );
            if (!isset($cookieUserid, $cookiePassword, $cookieCreationTime)) {break;}
            $cookieAge = time() - $cookieCreationTime;
            if ($cookieAge < 0 || $cookieAge > 1728000) {break;}
            //    self::v( $cookieUserid );
            if (!$database = Application_Settings_Abstract::getSettings('UserAccount', 'default-database')) {
                //    $database = 'file';
            }
            $saved = false;
            //    self::v( $cookieUserid );
            switch ($database) {
                case 'cloud':
                    $response = Ayoola_Api_UserList::send(array('user_id' => intval($cookieUserid)));
                    //        self::v( $response );
                    if (is_array($response['data'])) {
                        $realUserInfo = $response['data'];
                        //        var_export( $realUserInfo );
                    }
                    break;
                case 'relational':
                    $table = new Application_User();
                    if (!$realUserInfo = $table->selectOne('', 'useremail, usersettings, userpassword, userpersonalinfo, useractivation', array('user_id' => intval($cookieUserid)))) {break;}
                    break;
                default:
                    $table = Ayoola_Access_LocalUser::getInstance();
                    if (!$info = $table->selectOne(null, array('email' => strtolower(trim($cookieUserid))))) {break;}
                    $realUserInfo             = $info['user_information'];
                    $realUserInfo['password'] = $info['password'];
                    //    var_export( $info );
                    //    var_export( $correctCookiePassword );
                    break;

            }
            if (empty($realUserInfo['password'])) {break;}
            //    $correctCookiePassword = Ayoola_Access_Login::getPersistentCookieValue( $realUserInfo['email'], $realUserInfo['password'], $cookieCreationTime );
            //    list( $cookieUserid, $cookiePassword, $cookieCreationTime, $strict ) = explode( ':', base64_decode( $correctCookiePassword ) );
            //        self::v( $cookieUserid );
            //        self::v( $cookiePassword );
            //        self::v( $cookieCreationTime );
            //    self::v( $strict );
            //    self::v( base64_decode( $correctCookiePassword ) );
            //    self::v( $realUserInfo );

            if ($realUserInfo['access_level'] >= 99) {
                $correctCookiePassword = Ayoola_Access_Login::getPersistentCookieValue($realUserInfo['email'], $realUserInfo['password'], $cookieCreationTime);
                //    var_export( $cookieValue );
                //    var_export( $correctCookiePassword );

                //    strict cookie value for super users
                if ($correctCookiePassword != $cookieValue) {
                    $auth->logout();
                    break;
                }
            }
            $correctCookiePassword = Ayoola_Access_Login::hashPassWord($realUserInfo['email'] . $realUserInfo['password'], $cookieCreationTime);
            //    var_export( $cookiePassword );
            //    var_export( $correctCookiePassword );
            if ($correctCookiePassword != $cookiePassword) {
                //    $auth->logout();
                break;
            }
            //    exit( $correctCookieValue );
            $auth->getStorage()->store($realUserInfo);
            //    self::v( $realUserInfo );
            return true;
        } while (false);
    }
    // END OF CLASS
}
