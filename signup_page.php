<?php

session_start();

if(isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true){
    header('Location: index.php');
    exit;
}

$error   = '';
$success = '';

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    $name            = $_POST['name'] ?? '';
    $email           = $_POST['email'] ?? '';
    $password        = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm_password'] ?? '';

    if(empty($name) || empty($email) || empty($password) || empty($confirmPassword)){
        $error = 'All fields are required.';
    }elseif($password !== $confirmPassword){
        $error = 'Passwords do not match.';
    }else{
        try{
            $db = new PDO("mysql:host=localhost;dbname=login_logout_auth", 'root', '');
            $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $check = $db->prepare("SELECT * FROM user WHERE email = :email");
            $check->execute([':email' => $email]);

            if($check->fetch()){
                $error = 'The email is already registered.';
            }else{
                $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

                $statement = $db->prepare("INSERT INTO user(name,email,password) VALUES(:name,:email,:password)");
                $statement->execute([
                    ':name'     => $name,
                    ':email'    => $email,
                    ':password' => $hashedPassword,
                ]);

                $success = 'Successfully registered. You can log in now.';
                $name = $email = '';
            }
            }catch(PDOException $e){
            echo $e->getMessage();
            exit;
            }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sign Up</title>

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

        .success {
            background: #d1e7dd;
            color: #0f5132;
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
        <h2>Sign Up a New Account</h2>

        <?php if($error !== ''){ ?>
            <p class="message"><?php echo htmlspecialchars($error); ?></p>
        <?php } ?>

        <?php if($success !== ''){ ?>
            <p class="message success"><?php echo htmlspecialchars($success); ?></p>
        <?php } ?>

        <form method="POST" action="">
            <label>Name</label>
            <input type="text" name="name"
                   value="<?php echo htmlspecialchars($name ?? ''); ?>" required>

            <label>Email address</label>
            <input type="email" name="email"
                   value="<?php echo htmlspecialchars($email ?? ''); ?>" required>

            <label>Password</label>
            <input type="password" name="password" required>

            <label>Confirm Password</label>
            <input type="password" name="confirm_password" required>

            <button type="submit">Sign Up</button>
        </form>
    </div>

    <a class="back-link" href="index.php">Go back</a>

</body>
</html>
