    <?php
    session_start();
    header('Content-Type: application/json');
    require "../../config/db.php";

    if($_SERVER['REQUEST_METHOD'] == "POST"){
        $email = $_POST['email'];
        $password = $_POST['password'];

        //Check database for existence
    $sql = 'Select * from admin where email = :email';

    $stmt = $conn -> prepare($sql);
    $stmt -> execute([
        'email' => $email
    ]);

    $user = $stmt -> fetch(PDO::FETCH_ASSOC);
    
    if($user){
        $hashed_password = $user['password'];
        $admin_id = $user['admin_id'];
        if(password_verify($password, $hashed_password)){
            $_SESSION['admin_id'] = $admin_id;
            echo json_encode([
                'status' => "success"
            ]);
            exit();
        }else{
            echo json_encode([
                'status' => 'error',
                'message' => 'Invalid Password'

            ]);
            exit();
        }
    }else{
        echo json_encode([
            'status' => 'error',
            'message' => 'username not found'
        ]);
        exit();
    }

    }

