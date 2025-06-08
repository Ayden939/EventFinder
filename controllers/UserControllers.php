<?php
    include 'models/UserDAO.php';
    include 'models/EventDAO.php';
    include 'models/BookmarkDAO.php';

    class Home implements ControllerAction{

        function processGET(){
            return "views/Event_Page.php";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PUBLIC";
        }

    }

    class UserInfo implements ControllerAction{

        function processGET(){
            $userDAO = new UserDAO();
            $user = $userDAO->getUser(intval($_SESSION['userID']));
            $_REQUEST['user']=$user;
            $eventDAO = new EventDAO();
            $events = $eventDAO->getEventsUser($_SESSION['userID']);
            $_REQUEST['events']=$events;
            return "views/user_info.php";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class UserGuide implements ControllerAction{

        function processGET(){
            return "views/user_guide.html";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PUBLIC";
        }

    }

    class UserReg implements ControllerAction{

        function processGET(){
            return "views/UserRegistration.html";
        }

        function processPOST(){
            $username=$_POST['username'];
            $lastname=$_POST['lastname'];
            $firstname=$_POST['firstname'];
            $email=$_POST['email'];
            $passwd=$_POST['password'];
            $hashedPassword = password_hash($passwd, PASSWORD_DEFAULT);
            $user = new User();
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setPasswd($hashedPassword);
            $user->setLastname($lastname);
            $user->setFirstname($firstname);
            $userDAO = new UserDAO();
            $success=$userDAO->addUser($user);
            if ($success) {
                $nextView = "Location: controller.php?page=login";
            } else {
                $nextView = "Location: controller.php?page=eReg";
            }
            header($nextView);
            exit;
        }

        function getAccess(){
            return "PUBLIC";
        }

    }

    class UserUpdate implements ControllerAction{

        function processGET(){
            return "views/userForm.php";
        }

        function processPOST(){
            $userID=intval($_POST["userID"]);
            $username=$_POST['username'];
            $lastname=$_POST['lastname'];
            $firstname=$_POST['firstname'];
            $email=$_POST['email'];
            $passwd=$_POST['passwd'];
            $cpasswd=$_POST['confirmpasswd'];
            if($passwd == $cpasswd){
                $hashedPassword = password_hash($passwd, PASSWORD_DEFAULT);
                $user = new User();
                $user->setUserID($userID);
                $user->setUsername($username);
                $user->setEmail($email);
                $user->setPasswd($hashedPassword);
                $user->setLastname($lastname);
                $user->setFirstname($firstname);
                $userDAO = new UserDAO();
                $userDAO->updateUser($user);
                $nextView="Location: controller.php?page=userinfo";
            }else{
                $_REQUEST['message']="Error: Incorrect Password.";
                $nextView="Location: controller.php?page=userinfo";
            }

            header($nextView);
            exit;
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class Login implements ControllerAction{

        function processGET(){
            return "views/UserLogin.html";
        }

        function processPOST(){
            $username=$_POST['username'];
            $passwd=$_POST['password'];
            $userDAO = new UserDAO();
            $found=$userDAO->authenticate($username,$passwd);
            if($found==null){
                $nextView="Location: controller.php?page=elogin";
            }else{
                $nextView="Location: controller.php?page=home";
                $_SESSION['loggedin']='TRUE';
                $_SESSION['userID']=$userDAO->getUserID($username,$passwd);
            }
            header($nextView);
            exit;
        }
        function getAccess(){
            return "PUBLIC";
        }

    }

    class LoginError implements ControllerAction{
        function processGET(){
            return "views/errorLogin.php";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PUBLIC";
        }

    }

    class LogOut implements ControllerAction{

        function processGET(){
            return "views/logout.php";
        }

        function processPOST(){
            $submit=$_POST['submit'];
            if($submit=='CONFIRM'){
                $nextView="Location: controller.php?page=login";
                $_SESSION['loggedin']='FALSE';
                $_SESSION['userID']='';
            }else{
                $nextView="Location: controller.php?page=home";
            }
            header($nextView);
            exit;
        }
        function getAccess(){
            return "PUBLIC";
        }

    }

    class EventReg implements ControllerAction{

        function processGET(){
            return "views/EventRegistration.html";
        }

        function processPOST(){
                $title=$_POST['title'];
                $userID=$_SESSION['userID'];
                $descr=$_POST['descr'];
                $event_date=$_POST['event_date'];
                $event_time=$_POST['event_time'];
                $event_loc=$_POST['event_loc'];
                $cat=$_POST['category'];
                $event_date_time = $event_date . ' ' . $event_time;
                $event = new Event();
                $event->setTitle($title);
                $event->setUserID($userID);
                $event->setDescr($descr);
                $event->setEventDate($event_date_time);
                $event->setEventLoc($event_loc);
                $event->setCategory($cat);
                $location = $this->locate($event_loc);
                if ($location == null) {
                    die('Location not found');
                }else{
                    $lat = $location['latitude'];
                    $long = $location['longitude'];
                }
                $event->setLat($lat);
                $event->setLong($long);
                $eventDAO = new EventDAO();
                $eventDAO->addEvent($event);
            header('Location: controller.php?page=home');
            return;
        }

        function locate($address){
            $apiKey = 'hVPf26Pk8XkqrieOLO3xGtdtblsaCBai';
            $formatted = urlencode($address);
            $url = "https://api.tomtom.com/search/2/geocode/{$formatted}.json?key={$apiKey}";
            $tomReply = file_get_contents($url);
            $info = json_decode($tomReply);
            // Check if results exist
            if (empty($info->results)) {
                return null;
            } else {
                // Retrieve latitude and longitude
                $latitude = $info->results[0]->position->lat;
                $longitude = $info->results[0]->position->lon;
                return array('latitude' => $latitude, 'longitude' => $longitude);
            }
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class Eventt implements ControllerAction{

        function processGET(){
            $eventid = $_REQUEST['event_id'];
            $eventDAO = new EventDAO();
            $event = $eventDAO->getEvent($eventid);
            $_SESSION['event'] = $event;
            return "views/Event_Details.php";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PUBLIC";
        }
    }

    class EventUpdate implements ControllerAction{

        function processGET(){
            //$eventid = $_REQUEST['event_id'];
            //$_SESSION['eventID'] = $eventid;
            return "views/eventForm.php";
        }

        function processPOST(){
            $eventID=$_POST['eventID'];
            $title=$_POST['title'];
            $userID=$_SESSION['userID'];
            $descr=$_POST['descr'];
            $event_date=$_POST['event_date'];
            $event_loc=$_POST['event_loc'];
            $category=$_POST['category'];
            $event = new Event();
            $event->setEventID($eventID);
            $event->setTitle($title);
            $event->setUserID($userID);
            $event->setDescr($descr);
            $event->setEventDate($event_date);
            $event->setEventLoc($event_loc);
            $event->setCategory($category);
            $location = $this->locate($event_loc);
            if ($location == null) {
                die('Location not found');
            }else{
                $lat = $location['latitude'];
                $long = $location['longitude'];
            }
            $event->setLat($lat);
            $event->setLong($long);
            $eventDAO = new EventDAO();
            $eventDAO->updateEvent($event);
            header("Location: controller.php?page=userinfo");
            exit;
        }

        function locate($address){
            $apiKey = 'hVPf26Pk8XkqrieOLO3xGtdtblsaCBai';
            $formatted = urlencode($address);
            $url = "https://api.tomtom.com/search/2/geocode/{$formatted}.json?key={$apiKey}";
            $tomReply = file_get_contents($url);
            $info = json_decode($tomReply);
            // Check if results exist
            if (empty($info->results)) {
                return null;
            } else {
                // Retrieve latitude and longitude
                $latitude = $info->results[0]->position->lat;
                $longitude = $info->results[0]->position->lon;
                return array('latitude' => $latitude, 'longitude' => $longitude);
            }
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class EventDelete implements ControllerAction{

        function processGET(){
            $eventid = $_GET['eventID'];
            return 'views/delEvent.php';

        }

        function processPOST(){
            $eventid=$_POST['eventID'];
            $submit=$_POST['submit'];
            if($submit=='CONFIRM'){
                $eventDAO = new EventDAO();
                $eventDAO->deleteEvent($eventid);
            }
            header("Location: controller.php?page=userinfo");
            exit;
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class Add implements ControllerAction{

        function processGET(){
            $eventid = $_REQUEST['event_id'];
            $_SESSION['eventID'] = $eventid;
            return "views/addBookmark.php";
        }

        function processPOST(){
            $eventid=$_POST['eventID'];
            $userid=$_SESSION['userID'];
            $submit=$_POST['submit'];
            if($submit=='CONFIRM'){
                $eventDAO = new EventDAO();
                $event = $eventDAO->getEvent($eventid);
                $date = $event->getEventDate();
                $bkmk = new Bookmark();
                $bkmk->setUserID($userid);
                $bkmk->setEventID($eventid);
                $bkmk->setNotifyDate($date);
                $bookmarkDAO = new BookmarkDAO();
                $bookmarkDAO->addBookmark($bkmk);
            }
            header("Location: controller.php?page=home");
            exit;
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class Bookmarks implements ControllerAction {

        function processGET() {
            return "views/Bookmarks_Page.php";
        }
    
        function processPOST() {
            return;
        }
    
        function getAccess() {
            return "PROTECTED";
        }
    }

    class BookDelete implements ControllerAction{

        function processGET(){
            $eventid = $_GET['event_id'];
            return 'views/delBookmark.php';

        }

        function processPOST(){
            $eventid=$_POST['eventID'];
            $userid=$_SESSION['userID'];
            $submit=$_POST['submit'];
            if($submit=='CONFIRM'){
                $bookmarkDAO = new BookmarkDAO();
                $bookmarkDAO->deleteBookmark($userid,$eventid);
            }
            header("Location: controller.php?page=bookmarks");
            exit;
        }

        function getAccess(){
            return "PROTECTED";
        }

    }

    class EventError implements ControllerAction{
        function processGET(){
            return "views/errorEvent.php";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PUBLIC";
        }

    }

    class RegError implements ControllerAction{
        function processGET(){
            return "views/errorReg.php";
        }

        function processPOST(){
            return;
        }

        function getAccess(){
            return "PUBLIC";
        }

    }
?>
