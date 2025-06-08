<?php
    class User implements JsonSerializable{
        private $userID;
        private $username;
        private $firstname;
        private $lastname;
        private $email;
        private $passwd;


        public function load($row){
            $this->setUserID($row['user_id']);
            $this->setUsername($row['username']);
            $this->setFirstname($row['first_name']);
            $this->setLastname($row['last_name']);
            $this->setEmail($row["email"]);
            $this->setPasswd($row["pass_hash"]);
        }

        public function setUserID($userID){
            $this->userID=$userID;
        }

        public function getUserID(){
            return $this->userID;
        }

        public function setUsername($username){
            $this->username=$username;
        }

        public function getUsername(){
            return $this->username;
        }

        public function setEmail($email){
            $this->email=$email;
        }

        public function getEmail(){
            return $this->email;
        }

        public function setPasswd($passwd){
            $this->passwd=$passwd;
        }

        public function getPasswd(){
            return $this->passwd;
        }

        public function setLastname($lastname){
            $this->lastname=$lastname;
        }

        public function getLastname(){
            return $this->lastname;
        }

        public function setFirstname($firstname){
            $this->firstname=$firstname;
        }

        public function getFirstname(){
            return $this->firstname;
        }

        public function jsonSerialize(){
            return array(
                'userID' => $this->userID,
                'username' => $this->username,
                'firstname' => $this->firstname,
                'lastname'=> $this->lastname,
                'email' => $this->email,
                'passwd' => $this->passwd
            );
        }
    }
?>
