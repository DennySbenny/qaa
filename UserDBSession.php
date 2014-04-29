<?php

    //include ('db_credentials/userDBperm.inc');
    //include ('Mysql.php');

    class UserDBSession {
    
        private $db = null;
        
        
        public function __construct() {
            $this->db = new Mysql(DB_USER, DB_PASS, DB_NAME, DB_HOST);
        }
        
        function __destruct() {
            $this->db->close();
        }
    
        
        public function createNewSession(
                                             $id_user
                                            ,$token
                                            ,$ip
                                            ,$latitude
                                            ,$longitude
                                        ) {
            
            $query = "INSERT INTO LOGIN_SESSION (id_user, token, ip, latitude, longitude, is_active)";
            $query .= " VALUES ('$id_user', '$token', '$ip', '$latitude', '$longitude', 1)";
            
            $result = $this->db->doQuery($query);
            
            if ($result) {
                return $this->getSessionIdByToken($token);
            }
            else return $result;
            
        }
        
        
        
        public function getUserIdByFacebookId($fb_id) {
            return $this->db->autoGetter("logged_users", "id_session", "facebook_id", "'$fb_id'");
        }
        
        public function getLoggedSessionIDbyUserId($id_user) {
            return $this->db->autoGetter("logged_users", "id_session", "id_user", $id_user);
        }
        
        public function getLoggedUserIDbyEmail($email) {
            return $this->db->autoGetter("logged_users", "id_user", "email", "'$email'");
        }
        
        public function getLoggedSessionIDbyEmail($email) {
            return $this->db->autoGetter("logged_users", "id_session", "email", "'$email'");
        }
        
        public function getSessionIdByToken($token) {
            return $this->db->autoGetter("logged_users", "id_session", "token", "'$token'");
        }

        public function setToken($id_sess, $token) {
            return $this->db->autoSetter("LOGIN_SESSION", "token", "id_session", "'$id_sess'", "'$token'");
        }
        
    }

?>