<?php
session_start();
include "db_conn.php";

if (
    isset($_POST['uname'])
    && isset($_POST['password'])
    && isset($_POST['re_password'])
    && isset($_POST['first_name'])
    && isset($_POST['last_name'])
    && isset($_POST['email'])
) {
    function validate($data)
    {
        $data = trim($data);
        $data = stripslashes($data);
        $data = htmlspecialchars($data);
        return $data;
    }

    $uname = validate($_POST['uname']);
    $pass = validate($_POST['password']);
    $re_pass = validate($_POST['re_password']);
    $first_name = validate($_POST['first_name']);
    $last_name = validate($_POST['last_name']);
    $email = validate($_POST['email']);

    $user_data = 'uname=' . $uname . '&first_name=' . $first_name . '&last_name=' . $last_name . '&email=' . $email;

    // Validation rules
    $min_name_length = 2;
    $min_username_length = 4;
    $min_password_length = 8;

    if (empty($first_name) || strlen($first_name) < $min_name_length) {
        header("Location: signup.php?error=First Name must be at least $min_name_length characters long&$user_data");
        exit();
    } elseif (empty($last_name) || strlen($last_name) < $min_name_length) {
        header("Location: signup.php?error=Last Name must be at least $min_name_length characters long&$user_data");
        exit();
    } elseif (empty($uname) || strlen($uname) < $min_username_length) {
        header("Location: signup.php?error=User Name must be at least $min_username_length characters long&$user_data");
        exit();
    } elseif (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header("Location: signup.php?error=Invalid email address&$user_data");
        exit();
    } elseif (empty($pass) || strlen($pass) < $min_password_length) {
        header("Location: signup.php?error=Password must be at least $min_password_length characters long&$user_data");
        exit();
    } elseif (empty($re_pass)) {
        header("Location: signup.php?error=Re-Password is required&$user_data");
        exit();
    } elseif ($pass !== $re_pass) {
        header("Location: signup.php?error=The confirmation password does not match&$user_data");
        exit();
    } else {
        // Hashing the password
        $pass = md5($pass);

        $sql = "SELECT * FROM users WHERE user_name='$uname' OR email='$email'";
        $result = mysqli_query($conn, $sql);

        if (mysqli_num_rows($result) > 0) {
            header("Location: signup.php?error=The username or email is taken, try another&$user_data");
            exit();
        } else {
            $sql2 = "INSERT INTO users(user_name, password, first_name, last_name, email) VALUES('$uname', '$pass', '$first_name', '$last_name', '$email')";
            $result2 = mysqli_query($conn, $sql2);
            if ($result2) {
                header("Location: signup.php?success=Your account has been created successfully");
                exit();
            } else {
                header("Location: signup.php?error=Unknown error occurred&$user_data");
                exit();
            }
        }
    }
} else {
    header("Location: signup.php");
    exit();
}
?>
