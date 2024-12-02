<?php 
require('config/function.php');

if(isset($_POST['loginBTN'])){
    
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);

    if(!empty($email) && !empty($password)){
        
        $query = "SELECT * FROM admins WHERE email = '$email' LIMIT 1";
        $result = mysqli_query($conn, $query);

        if($result){
            
            if(mysqli_num_rows($result) == 1){
                
                $row = mysqli_fetch_assoc($result);

                $hashedPassword = $row['password'];
                if(!password_verify($password, $hashedPassword)){
                    redirect('login.php', 'error', 'Invalid password!.');
                }
               if($row['is_ban'] == 1){
                redirect('login.php', 'error', 'Your account has been banned!.');
               }

                $_SESSION['loggedIn'] = true;
                $_SESSION['loggedInUser'] = [
                    'user_id' => $row['id'],
                    'name' => $row['name'],
                    'email' => $row['email'],
                    'phone' => $row['phone'],
                ];

                redirect('admin/index.php', 'success', 'Logged in successfully.');
            }
            else{
                redirect('login.php', 'error', 'Invalid email address!.');
            }
            
        }
        else{
            redirect('login.php', 'error', 'Something went wrong');
        }
    }
    else{
        redirect('login.php', 'error', 'Fill the email and password fields');
    }
}

?>