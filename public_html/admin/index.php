<?php declare(strict_types=1);

include_once __DIR__.'/../../config/database.php';

session_start();

if (isset($_SESSION['session_id'])) {
    header('location: dashboard.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $userEmail = htmlspecialchars($_POST['email']);
    $userPassword = htmlspecialchars($_POST['password']);

    if ($userEmail && $userPassword) {
        $queryString = 'SELECT id, nombre, correo, password FROM usuarios WHERE correo = ? AND status = 1 LIMIT 1';
        $query = $pdo->prepare($queryString);
        $query->execute([$userEmail]);
        $user = $query->fetch();

        if ($user['password'] === $userPassword) {
            $_SESSION['nombre'] = $user['nombre'];
            $_SESSION['session_id'] = $user['id'];

            header('location: dashboard.php');
        }
    }
}

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Muchasfondas Dashboard</title>
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
    <link rel="stylesheet" href="assets/css/bootstrap.min.css">
    <link rel="stylesheet" href="assets/css/AdminLTE.min.css">
    <link rel="stylesheet" href="assets/css/skin-black.css">
    <link rel="stylesheet" href="assets/css/app.css">
</head>
<body class="hold-transition login-page">
<div class="login-box">
    <div class="login-logo">
        <a href="#">Muchasfondas</a>
    </div>

    <div class="login-box-body">
        <form id="login-form" method="post">

            <?php if (isset($user) && !$user) { ?>
                <div class="alert alert-warning alert-dismissible" role="alert">
                    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                    Email o password erroneos
                </div>
            <?php } ?>

            <div class="form-group has-feedback <?= isset($userEmail) && !$userEmail ? 'has-error' : '' ?>">
                <input type="email" name="email" class="form-control"
                       placeholder="Email"
                       value="<?= isset($userEmail) ? $userEmail : '' ?>">
                <span class="glyphicon glyphicon-envelope form-control-feedback"></span>
                <span class="help-block">
                    <?= isset($userEmail) && !$userEmail ? 'Este campo no puede estar vacio' : '' ?>
                </span>
            </div>

            <div class="form-group has-feedback <?= isset($userPassword) && !$userPassword ? 'has-error' : '' ?>">
                <input type="password" name="password" class="form-control" placeholder="Password">
                <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                <span class="help-block">
                    <?= isset($userPassword) && !$userPassword ? 'Este campo no puede estar vacio' : '' ?>
                </span>
            </div>

            <button type="submit" class="btn btn-primary btn-block btn-flat">Acceder</button>

        </form>

        <a href="#" class="text-center">Crear nueva cuenta</a>
    </div>
</div>

<script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>
<script src="assets/js/vendor/adminlte.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
