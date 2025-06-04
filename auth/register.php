<?php 
require "../includes/header.php";
require "../config/config.php";

$error = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $username = trim($_POST['username']);
    $email = trim($_POST['email']);
    $password_raw = $_POST['password'];

    if (empty($username) || empty($password_raw) || empty($email)) {
        $error = "Please fill all the fields.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Please enter a valid email address.";
    } else {
        // Check if username or email exists
        $checkUser = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        $checkUser->bind_param("ss", $username, $email);
        $checkUser->execute();
        $result = $checkUser->get_result();

        if ($result->num_rows > 0) {
            $error = "Username or email already exists.";
        } else {
            $password = password_hash($password_raw, PASSWORD_DEFAULT);

            $insert = $conn->prepare("INSERT INTO users(username, email, password) VALUES (?, ?, ?)");
            $insert->bind_param("sss", $username, $email, $password);

            if ($insert->execute()) {
                header("Location: login.php");
                exit();
            } else {
                $error = "Something went wrong. Please try again.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Register</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" />
    <style>
        .register-container {
            min-height: 80vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .register-form {
            width: 100%;
            max-width: 400px;
            padding: 30px;
            background: rgba(255, 255, 255, 0.9);
            border-radius: 10px;
            box-shadow: 0 0 15px rgba(0,0,0,0.2);
        }
    </style>
</head>
<body>

<div class="hero-wrap js-fullheight" style="background-image: url('images/image_2.jpg'); background-size: cover; background-position: center;">
    <div class="overlay" style="background: rgba(0,0,0,0.5); position: absolute; top:0; left:0; width: 100%; height: 100%;"></div>
    
    <div class="container register-container position-relative" style="z-index: 2;">
        <form action="register.php" method="POST" class="register-form">
            <h3 class="mb-4 text-center">Register</h3>

            <?php if (!empty($error)): ?>
                <div class="alert alert-danger"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <div class="form-group">
                <input type="text" name="username" class="form-control" placeholder="Username" required value="<?php echo isset($username) ? htmlspecialchars($username) : '' ?>">
            </div>
            <div class="form-group">
                <input type="email" name="email" class="form-control" placeholder="Email" required value="<?php echo isset($email) ? htmlspecialchars($email) : '' ?>">
            </div>
            <div class="form-group">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="form-group">
                <input type="submit" name="submit" value="Register" class="btn btn-primary btn-block py-2">
            </div>

            <p class="text-center">Already have an account? <a href="login.php">Login here</a></p>
        </form>
    </div>
</div>

<?php require "../includes/footer.php"; ?>

</body>
</html>
