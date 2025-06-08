<?php
    interface ControllerAction{
         function processGET();
         function processPOST();
         function getAccess();
    }
?>
