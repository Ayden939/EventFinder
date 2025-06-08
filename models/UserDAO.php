<?php
    include_once 'User.php';

    class UserDAO {


        public function getConnection(){
            $mysqli = new mysqli("127.0.0.1", "student", "UApass50", "EventFinderDB");
            if ($mysqli->connect_errno) {
                $mysqli=null;
            }
            return $mysqli;
        }

        public function addUser($user){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("INSERT INTO users (username, first_name, last_name, email, pass_hash) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                die('Prepare failed: ' . $connection->error);
            }else{
                $stmt->bind_param("sssss", $user->getUsername(), $user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getPasswd());
            }
            $executionSuccess =$stmt->execute();
            $stmt->close();
            $connection->close();
            return $executionSuccess;
        }

        public function updateUser($user){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("UPDATE users SET username=?, first_name=?, last_name=?, email=?, pass_hash=? WHERE user_id = ?;");
            $stmt->bind_param("sssssi", $user->getUsername(), $user->getFirstname(), $user->getLastname(), $user->getEmail(), $user->getPasswd(), $user->getUserID());
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function deleteUser($userid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("DELETE FROM users WHERE user_id = ?");
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            $stmt->close();
            $connection->close();
        }

        public function getUsers(){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM users;");
            $stmt->execute();
            $result = $stmt->get_result();
            while($row = $result->fetch_assoc()){
                $user = new User();
                $user->load($row);
                $users[]=$user;
            }
            $stmt->close();
            $connection->close();
            return $users;
        }

        public function getUser($userid){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM users WHERE user_id = ?;");
            $stmt->bind_param("i", $userid);
            $stmt->execute();
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()){
                $user = new User();
                $user->load($row);
            }
            $stmt->close();
            $connection->close();
            return $user;
        }

        public function getUserID($username,$passwd){
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT * FROM users WHERE username = ?;");
            $stmt->bind_param("s", $username);
            $stmt->execute();
            $result = $stmt->get_result();
            if($row = $result->fetch_assoc()){
                $user = new User();
                $user->load($row);
            }
            $userid = $user->getUserID();
            $stmt->close();
            $connection->close();
            return $userid;
        }

        public function authenticate($username, $passwd){
            $found=FALSE;
            $connection=$this->getConnection();
            $stmt = $connection->prepare("SELECT pass_hash FROM users WHERE username = ?;");
            $stmt->bind_param("s",$username);
            if ($stmt->execute()) {

                $stmt->store_result();

                // Check if the user exists
                if ($stmt->num_rows > 0) {

                    $stmt->bind_result($hashed_password);
                    $stmt->fetch();

                    // Verify the input password with the hashed password
                    if (password_verify($passwd, $hashed_password)) {

                        echo "Login successful! Welcome, " . $username;
                        $found = TRUE;

                    } else {

                        echo "Invalid username or password.";
                        $found=FALSE;
                    }
                } else {

                    echo "Invalid username or password.";
                }
                $stmt->close();
            } else {
                echo "Error executing query.";
            }
            $connection->close();
            return $found;
        }

    }
?>
