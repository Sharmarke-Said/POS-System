<?php 
require('config/function.php');

if(isset($_SESSION['loggedIn'])){
    
    logoutsession();
    
    redirect('login.php', 'error', 'Session Expired');
}



?>