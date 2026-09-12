<?php

session_start();

if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true){
    header('Location: index.php');
    exit;
}

$error = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $email    = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';

    if(empty($email) || empty($password)){
        $error = 'All fields are required.';
    }else{
        try{
            $db = new PDO("mysql:host=localhost;dbname=login_logout_auth", 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $statement = $db->prepare("SELECT * FROM user WHERE email = :email");
            $statement->execute([':email' => $email]);
            $user = $statement->fetch(PDO::FETCH_OBJ);

            if($user && password_verify($password, $user->password)){
                session_regenerate_id(true);
                $_SESSION['authenticated'] = true;
                $_SESSION['email'] = $user->email;
                header('Location: index.php');
                exit;
            }else{
                $error = 'Invalid email or password.';
            }
        }catch(PDOException $e){
            $error = 'Something went wrong. Please try again.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>

    <style>
        body {
            font-family: Arial, sans-serif;
            background: #f1f3f5;
            padding: 40px;
        }

        .card {
            width: 450px;
            margin: auto;
            background: white;
            padding: 25px;
            border-radius: 10px;
        }

        h2 {
            text-align: center;
        }

        label {
            display: block;
            margin-bottom: 5px;
            font-weight: bold;
        }

        input {
            width: 100%;
            box-sizing: border-box;
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 5px;
        }

        button {
            width: 100%;
            background: #0d6efd;
            color: white;
            border: none;
            padding: 12px;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .message {
            background: #f8d7da;
            color: #842029;
            padding: 10px;
            border-radius: 5px;
            margin-bottom: 15px;
        }

        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
        }
    </style>
</head>
<body>

    <div class="card">
        <h2>Login To Your Account</h2>

        <?php if($error !== ''){ ?>
            <p class="message"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <form method="POST" action="">
            <label>Email address</label>
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <button type="submit">Login</button>
        </form>
    </div>

    <a class="back-link" href="index.php">Go back</a>

</body>
</html>
