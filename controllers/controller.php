<?php
    include_once "ControllerAction.php";
    include_once "UserControllers.php";

    class FrontController { 
        private $controllers;
        

        public function __construct(){
            $this->showErrors(0);
            $this->controllers = $this->loadControllers();
        }

        public function run(){
            session_start();

            //***** 1. Get Request Method and Page Variable *****/
            $method = $_SERVER['REQUEST_METHOD'];
            $page = $_REQUEST['page'];
            if(!(isset($_REQUEST['page']))){
                $page="home";
            }
        
            //***** 2. Route the Request to the Controller Based on Method and Page *** */
            $controller = $this->controllers[$method.$page];
            
            //** 3. Check Security Access ***/
            $controller = $this->securityCheck($controller);
            
            //** 4. Execute the Controller */
            if($method=='GET'){
                $content=$controller->processGET();
            }
            if($method=='POST'){
                $content=$controller->processPOST();
            }

            //**** 5. Render Page Template */
            include "templates/template.php";
        }

        private function loadControllers(){
        /******************************************************
         * Register the Controllers with the Front Controller *
         ******************************************************/
            $controllers["GET"."userguide"] = new UserGuide();
            $controllers["GET"."userinfo"] = new UserInfo();
            $controllers["GET"."update"] = new UserUpdate();
            $controllers["POST"."update"] = new UserUpdate();
            $controllers["GET"."login"] = new Login();
            $controllers["POST"."login"] = new Login();
            $controllers["GET"."logout"] = new LogOut();
            $controllers["POST"."logout"] = new LogOut();
            $controllers["GET"."home"] = new Home();
            $controllers["GET"."userReg"] = new UserReg();
            $controllers["POST"."userReg"] = new UserReg();
            $controllers["GET"."eventReg"] = new EventReg();
            $controllers["POST"."eventReg"] = new EventReg();
            $controllers["GET"."event"] = new Eventt();
            $controllers["GET"."add"] = new Add();
            $controllers["POST"."add"] = new Add();
            $controllers["GET"."eupdate"] = new EventUpdate();
            $controllers["POST"."eupdate"] = new EventUpdate();
            $controllers["GET"."edelete"] = new EventDelete();
            $controllers["POST"."edelete"] = new EventDelete();
            $controllers["GET"."perror"] = new EventError();
            $controllers["GET"."elogin"] = new LoginError();
            $controllers["GET"."eReg"] = new RegError();
            $controllers["GET"."bookmarks"] = new Bookmarks();
            $controllers["POST"."bookmarks"] = new Bookmarks();
            $controllers["GET"."bdelete"] = new BookDelete();
            $controllers["POST"."bdelete"] = new BookDelete();
            return $controllers;
        }

        private function securityCheck($controller){
        /******************************************************
         * Check Access restrictions for selected controller  *
         ******************************************************/
            if($controller->getAccess()=='PROTECTED'){
                if(!isset($_SESSION['loggedin'])){
                    //*** Not Logged In ****/
                    $controller = $this->controllers["GET"."login"];
                }
            }
            return $controller;
        }

        private function showErrors($debug){
            if($debug==1){
                ini_set('display_errors', 1);
                ini_set('display_startup_errors', 1);
                error_reporting(E_ALL);
            }
        }
    }

    $controller = new FrontController();
    $controller->run();
?>
