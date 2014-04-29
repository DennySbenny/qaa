<?php

    include (__DIR__ . '/../interfaces/interface_UserStorageHandler.php');
    include ('db_credentials/userDBperm.inc');
    include ('Mysql.php');


    class UserDBHandler implements iUserStorageHandler {

        private $db = null;
        
        
        public function __construct() {
            $this->db = new Mysql(DB_USER, DB_PASS, DB_NAME, DB_HOST);
        }
        
        function __destruct() {
            $this->db->close();
        }
            
        
        public function userExists($idUser) {
            $result = $this->db->doQuery("SELECT id_user FROM USERS WHERE id_user = " . $idUser);
            if (mysqli_num_rows($result) > 0) return true;
            return false;
        }

        public function checkEmailExists($email) {
            $result = $this->db->doQuery("SELECT id_user FROM USERS WHERE email = '" . $email . "'");
   
            if (mysqli_num_rows($result) > 0) return true;
            return false;
        }

        
        public function insertNewUser(
                                         $name
                                        ,$email
                                        ,$password
                                        ,$facebook_id
                                        ,$activation_code
                                    ) {
        
            $query = "INSERT INTO USERS (name, email, password, facebook_id, activation_code)";
            $query .= " VALUES ('$name', '$birthdate', '$email', '$password', '$url_profile_img', '$about_me', '$facebook_id', '$latitude', '$longitude', '$activation_code')";
            
            $result = $this->db->doQuery($query);
            
            return $result;
        }
                
        
    
        public function getPasswordByEmail($email_src) {
            return $this->db->autoGetter("USERS", "password", "email", "'$email_src'");
        }
        
        
        public function getUserIdByEmail($email_src) {
            $idus = $this->db->autoGetter("USERS", "id_user", "email", "'$email_src'");
            return $idus;
        }
        
        public function getUserIdByFacebookId($facebook_id) {
            $idus = $this->db->autoGetter("USERS", "id_user", "facebook_id", "'$facebook_id'");
            return $idus;
        }
        
        
	   /* *** BASE GETTER / SETTER *** */
        
        public function getName($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "name", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
        public function setName($idUser, $value) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "name", "id_user", $idUser, "'$value'");
            return 'ERROR: invalid USER ID';
        }
        
        
        public function getBirthdate($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "birthdate", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
        public function setBirthdate($idUser, $value) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "birthdate", "id_user", $idUser, "'$value'");
            return 'ERROR: invalid USER ID';
        }
        
        
        public function getEmail ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "email", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
        public function setEmail ($idUser, $value) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "email", "id_user", $idUser, "'$value'");
            return 'ERROR: invalid USER ID';    
        }
        
        
        public function getPassword ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "password", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
        public function setPassword ($idUser, $value) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "password", "id_user", $idUser, "'$value'");
            return 'ERROR: invalid USER ID';
        }

        
        public function getCreatedDate ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "created_date", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
                            
        
        public function getFacebookId($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "facebook_id", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
        public function setFacebookId($idUser, $value) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "facebook_id", "id_user", $idUser, "'$value'");
            return 'ERROR: invalid USER ID';
        }
        
        
        public function enableUser ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "is_active", "id_user", $idUser, 1);
            return 'ERROR: invalid USER ID';
        }
        public function disableUser ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "is_active", "id_user", $idUser, 0);
            return 'ERROR: invalid USER ID';
        }
            
        public function getAccountStatus ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "is_active", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
            
        
        public function getActivationCode ($idUser) {
            if ($this->userExists($idUser))
                return $this->db->autoGetter("USERS", "activation_code", "id_user", $idUser);
            return 'ERROR: invalid USER ID';
        }
        public function setActivationCode ($idUser, $value) {
            if ($this->userExists($idUser))
                return $this->db->autoSetter("USERS", "activation_code", "id_user", $idUser, "'$value'");
            return 'ERROR: invalid USER ID';
        }
        /* END BASE GETTER / SETTER */

        
        
    }


?>
