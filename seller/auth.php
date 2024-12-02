<?php 
if(isset($_SESSION['loggedIn']))
{
    $email = validate($_SESSION['loggedInUser']['email']);

    $query = "SELECT * FROM admins WHERE email = '$email' LIMIT 1";
    $result = mysqli_query($conn, $query);

    if(mysqli_num_rows($result) == 0){
        logoutsession();
        redirect('../login.php', 'error', 'Access Denied');
    }
    else
    {
        $row = mysqli_fetch_assoc($result);

        if($row['is_ban'] == 1){
            logoutsession();
        redirect('../login.php', 'error', 'Your account has been banned');
        }
        
    }
}
else
{
    redirect('../login.php', 'error', 'Login to continue.');
}
?>