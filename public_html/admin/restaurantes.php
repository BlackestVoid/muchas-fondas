<?php include_once __DIR__.'/includes/session.php'; ?>
<?php include_once __DIR__.'/../../config/database.php'; ?>
<?php
$stmt = $pdo->prepare('SELECT * FROM restaurantes');
$stmt->execute();
$restaurantes = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html>
<?php include_once __DIR__ . '/includes/head-snippets.php'; ?>
<body class="hold-transition skin-black sidebar-mini">
<?php include_once __DIR__ . '/includes/header.php'; ?>
<?php include_once __DIR__ . '/includes/sidebar.php'; ?>
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content container-fluid">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Listado de restaurantes
                    </h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Codigo Postal</th>
                            <th>Telefono</th>
                            <th>Informacion</th>
                            <th>Fotos</th>
                            <th>Estatus</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($restaurantes as $restaurante) { ?>
                            <tr>
                                <td><?= $restaurante['nombre'] ?></td>
                                <td><?= $restaurante['codigo_postal'] ?></td>
                                <td><?= $restaurante['telefono'] ?></td>
                                <td><?= $restaurante['info'] ?></td>
                                <td><?= $restaurante['fotos'] ?></td>
                                <td align="center" valign="middle">
                                    <?php if ($restaurante['status']) { ?>
                                        <span class="label label-success">Activa</span>
                                    <?php } else { ?>
                                        <span class="label label-default">Inactiva</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="direcciones.php?restaurant=<?= $restaurante['id'] ?>">Direcciones</a>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<script src="assets/js/vendor/jquery-3.3.1.min.js"></script>
<script src="assets/js/vendor/bootstrap.min.js"></script>
<script src="assets/js/vendor/adminlte.min.js"></script>
<script src="assets/js/app.js"></script>
</body>
</html>
