<?php include_once __DIR__.'/includes/session.php'; ?>
<?php include_once __DIR__.'/../../config/database.php'; ?>
<?php
$direccionEdit = null;
$idRestaurant = $_GET['restaurant'];

$query = '
SELECT id, nombre
FROM restaurantes
WHERE id = ?
';

$stmt = $pdo->prepare($query);
$stmt->execute([$idRestaurant]);
$restaurant = $stmt->fetch();

if (!$restaurant) {
    header('location: restaurantes.php');
}

$query = '
SELECT id, latitud, longitud, direccion, id_restaurant, status 
FROM direccion 
WHERE id_restaurant = ?
';

$stmt = $pdo->prepare($query);
$stmt->execute([$restaurant['id']]);
$direcciones = $stmt->fetchAll();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['_method']) && !isset($_POST['id'])) {
        $params = [
            'direccion' => htmlspecialchars($_POST['direccion']),
            'latitud' => htmlspecialchars($_POST['latitud']),
            'longitud' => htmlspecialchars($_POST['longitud']),
            'status' => htmlspecialchars($_POST['status']),
            'restaurant' => $restaurant['id'],
        ];

        $query = '
    INSERT INTO direccion (latitud, longitud, direccion, id_restaurant, status)
    VALUES (:latitud, :longitud, :direccion, :restaurant, :status)
    ';
        $stmt = $pdo->prepare($query);
        $stmt->execute($params);

        header("location: {$_SERVER['REQUEST_URI']}");

        return;
    }

    if (isset($_POST['id'])) {
        $query = '
SELECT id, latitud, longitud, direccion, id_restaurant, status 
FROM direccion WHERE id = ?';
        $stmt = $pdo->prepare($query);
        $stmt->execute([$_POST['id']]);

        $direccionEdit = $stmt->fetch();
    }

    if (isset($_POST['_method'])) {
        if ($_POST['_method'] === 'PUT') {
            $params = [
                'direccion' => htmlspecialchars($_POST['direccion']),
                'latitud' => htmlspecialchars($_POST['latitud']),
                'longitud' => htmlspecialchars($_POST['longitud']),
                'status' => htmlspecialchars($_POST['status']),
                'restaurant' => $direccionEdit['id'],
            ];

            $query = '
    UPDATE direccion SET latitud = :latitud, longitud = :longitud, direccion = :direccion, status = :status
    WHERE id = :restaurant
    ';
            $stmt = $pdo->prepare($query);
            $stmt->execute($params);

            header("location: {$_SERVER['REQUEST_URI']}");

            return;
        }

        if ($_POST['_method'] === 'DELETE') {
            $stmt = $pdo->prepare('DELETE FROM direccion WHERE id = ?');
            $stmt->execute([$_POST['id']]);

            header("location: {$_SERVER['REQUEST_URI']}");

            return;
        }

    }
}
?>
<!DOCTYPE html>
<html>
<?php include_once __DIR__.'/includes/head-snippets.php'; ?>
<body class="hold-transition skin-black sidebar-mini">
<?php include_once __DIR__.'/includes/header.php'; ?>
<?php include_once __DIR__.'/includes/sidebar.php'; ?>
<div class="wrapper">
    <div class="content-wrapper">
        <section class="content container-fluid">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Nueva dirección de <?= $restaurant['nombre'] ?>
                    </h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?php if ($direccionEdit) { ?>
                    <form class="form" method="post" enctype="multipart/form-data">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion"
                                           value="<?= $direccionEdit['direccion'] ?>">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitud">Latitud</label>
                                            <input type="text" class="form-control" id="latitud" name="latitud"
                                                   value="<?= $direccionEdit['latitud'] ?>">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitud">Longitud</label>
                                            <input type="text" class="form-control" id="longitud" name="longitud"
                                                   value="<?= $direccionEdit['longitud'] ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Estatus</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0"
                                            <?= !$direccionEdit['status'] ? 'selected="selected"' : '' ?>>
                                            Inactivo
                                        </option>
                                        <option value="1"
                                            <?= $direccionEdit['status'] ? 'selected="selected"' : '' ?>>
                                            Activo
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="hidden" name="_method" value="PUT">
                                <input type="hidden" name="id" value="<?= $direccionEdit['id'] ?>">
                                <a href="<?= $_SERVER['REQUEST_URI'] ?>" class="btn btn-default">Cancelar</a>
                                <input type="submit" value="Editar" class="btn btn-info pull-right">
                            </div>
                        </div>
                    </form>
                    <?php } else { ?>
                    <form class="form" method="post">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="form-group">
                                    <label for="direccion">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion">
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="latitud">Latitud</label>
                                            <input type="text" class="form-control" id="latitud" name="latitud">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group">
                                            <label for="longitud">Longitud</label>
                                            <input type="text" class="form-control" id="longitud" name="longitud">
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-2">
                                <div class="form-group">
                                    <label for="status">Estatus</label>
                                    <select name="status" id="status" class="form-control">
                                        <option value="0">
                                            Inactivo
                                        </option>
                                        <option value="1" selected="selected">
                                            Activo
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <input type="submit" value="Agregar" class="btn btn-info pull-right">
                            </div>
                        </div>
                    </form>
                    <?php } ?>
                </div>
            </div>

            <div class="box">
                <div class="box-header">
                    <h3 class="box-title">
                        Listado de direcciones de <?= $restaurant['nombre'] ?>
                    </h3>
                </div>
                <div class="box-body no-padding">
                    <table class="table">
                        <thead>
                        <tr>
                            <th>Dirección</th>
                            <th>Latitud</th>
                            <th>Longitud</th>
                            <th>Estatus</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($direcciones as $direccion) { ?>
                        <tr>
                            <td><?= $direccion['direccion'] ?></td>
                            <td><?= $direccion['latitud'] ?></td>
                            <td><?= $direccion['longitud'] ?></td>
                            <td align="center" valign="middle">
                                <?php if ($direccion['status']) { ?>
                                    <span class="label label-success">Activa</span>
                                <?php } else { ?>
                                    <span class="label label-default">Inactiva</span>
                                <?php } ?>
                            </td>
                            <td>
                                <form method="post" style="margin-bottom: .5rem;">
                                    <input type="hidden" name="id" value="<?= $direccion['id'] ?>">
                                    <button class="btn btn-xs btn-block btn-info">Editar</button>
                                </form>
                                <form method="post">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <input type="hidden" name="id" value="<?= $direccion['id'] ?>">
                                    <button class="btn btn-xs btn-block btn-danger">Eliminar</button>
                                </form>
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
