<?php

session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST'){
    $name  = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];

    if(empty($name) || empty($email) || empty($password) || empty($confirm_password)){
        echo "All fields are required";
        exit;
    }

    if($password !== $confirm_password){
        echo "Password do not match";
        exit;
    }

$db = new PDO("mysql:host=localhost;dbname=login_auth", 'root', '');
$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

$check = $db->prepare("SELECT * FROM user WHERE email = :email");
$check -> execute([':email=>$email']);

if($check->fetch()){
    echo "The email has been registered";
    exit;
}

$hashed_password = password_hash($password,PASSWORD_DEFAULT);

$statement = $db->prepare("INSERT INTO users(name,email,password,confirm_password) VALUES(:name,:email,:password,:confirm_password)");
$statement->execute([
    ':name' =>$name,
    ':email' =>$email,
    ':password'=>$hashedPassword,
    ':confirm_password'=>$confirm_password,
]);

echo "Successfully registered";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
</head>
<body>

    <h2>Create an Account</h2>

    <form method="POST" action="">
        <label>Name:</label>
        <input type="name" name="name" required><br><br>

        <label>Email:</label>
        <input type="email" name="email" required><br><br>

        <label>Password:</label>
        <input type="password" name="password" required><br><br>

        <label>Confirm Password:</label>
        <input type="password" name="confirm_password" required><br><br>

        <button type="submit">Sign Up</button>
    </form>

</body>
</html>
