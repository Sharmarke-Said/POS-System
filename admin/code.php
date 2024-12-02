<?php 
include("../config/function.php");

// Add Admin
if(isset($_POST['saveAdmin'])){
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0; 

    if($name != '' && $email != '' && $password != '' && $phone != ''){

        $emailCheck = mysqli_query($conn, "SELECT * FROM admins WHERE email = '$email'");

        if($emailCheck){
            if(mysqli_num_rows($emailCheck) > 0){
                redirect('admins.php', 'Email already used by another user.');
            }
        }
        
        $bcrypt_password = password_hash($password, PASSWORD_BCRYPT);

        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $bcrypt_password,
            'phone' => $phone,
            'is_ban' => $is_ban,
            				
        ];

        $result = insert('admins', $data);

        if($result){
            redirect('admins.php', 'success', 'Admin Added Successfully.');
        }else{
            redirect('admins.php', 'Something went wrong!');
        }
        
    }else{
        redirect('admins.php', 'Please fill the required fields');
    }
}

// Update Admin
if(isset($_POST['updateAdmin'])){
    $adminId = validate($_POST['adminId']);
    
    $adminData = getByID('admins', $adminId);

    if($adminData['status'] != 200){
        redirect('admins.php?id='.$adminId, 'error', 'Please fill the required fields');
    }
    
    $name = validate($_POST['name']);
    $email = validate($_POST['email']);
    $password = validate($_POST['password']);
    $phone = validate($_POST['phone']);
    $is_ban = isset($_POST['is_ban']) == true ? 1:0; 

    if($password != ''){
        $hashedPassword = password_hash($password, PASSWORD_BCRYPT);
    }
    else{
        $hashedPassword = $adminData['data']['password'];
    }

    

    if($name != '' && $email != ''){
        $data = [
            'name' => $name,
            'email' => $email,
            'password' => $hashedPassword,
            'phone' => $phone,
            'is_ban' => $is_ban,
            				
        ];

        $result = update('admins',$adminId, $data);

        if($result){
            redirect('admins.php?id='.$adminId, 'success', 'Admin Updated Successfully.');
        }else{
            redirect('admins.php?id='.$adminId, 'error', 'Something went wrong!');
        }
    }
    else{
        redirect('admins.php', 'error', 'Please fill the required fields');
    }
}


// Delete Admin

$paramResultId = checkIdParam('id');

if(is_numeric($paramResultId)){
    $adminId = validate($paramResultId);

    // echo $adminId;

    $adminData = getByID('admins', $adminId);
    if($adminData['status'] == 200)
    {
        $adminDeleteRes = delete('admins', $adminId);
        if($adminDeleteRes)
        {
            redirect('admins.php', 'success', 'Admin deleted successfully');
        }
        else{
            redirect('admins.php', 'error', 'Something went wrong');
        }
        
    }
    else
    {
        redirect('admins.php', $adminData['message']);
    }
}
else{
    redirect('admins.php', 'error', 'Something went wrong');
}



?>