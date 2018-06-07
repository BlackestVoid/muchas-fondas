<?php include_once __DIR__.'/includes/session.php'; ?>
<?php include_once __DIR__.'/../../config/database.php'; ?>
<?php
$restauranteEdit = null;

$stmt = $pdo->prepare('SELECT id, nombre FROM tipo_cocina');
$stmt->execute();
$tipo_cocina = $stmt->fetchAll();

$stmt = $pdo->prepare('SELECT id, nombre FROM usuarios');
$stmt->execute();
$usuarios = $stmt->fetchAll();



$stmt = $pdo->prepare('SELECT * FROM restaurantes');
$stmt->execute();
$restaurantes = $stmt->fetchAll();


if ($_SERVER['REQUEST_METHOD'] === 'POST') {
if (!isset($_POST['_method']) && !isset($_POST['id'])) {
    $params = [
        'nombre' => htmlspecialchars($_POST['nombre']),
        'codigo_postal' => htmlspecialchars($_POST['codigo_postal']),
        'telefono' => htmlspecialchars($_POST['telefono']),
        'info' => htmlspecialchars($_POST['info']),
        'id_cocina' => htmlspecialchars($_POST['id_cocina']),
        'fotos' => htmlspecialchars($_POST['imagenOculta']),
        'usr' => htmlspecialchars($_SESSION['session_id']),
        'status' => htmlspecialchars($_POST['status']),
    ];

    $query = '
    INSERT INTO restaurantes (nombre, codigo_postal, telefono, info, id_cocina, fotos, id_usuario, status)
    VALUES (:nombre, :codigo_postal, :telefono, :info,:id_cocina, :imagenOculta, :session_id, :status)
    ';
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    header("location: {$_SERVER['REQUEST_URI']}");

    return;
}

if (isset($_POST['id'])) {
    $query = '
SELECT id, nombre, codigo_postal, telefono, info, fotos, id_cocina, id_usuario, status 
FROM restaurantes WHERE id = ?';
    $stmt = $pdo->prepare($query);
    $stmt->execute([$_POST['id']]);

    $restauranteEdit = $stmt->fetch();
}

if (isset($_POST['_method'])) {
if ($_POST['_method'] === 'PUT') {
    $params = [
        'nombre' => htmlspecialchars($_POST['nombre']),
        'codigo_postal' => htmlspecialchars($_POST['codigo_postal']),
        'telefono' => htmlspecialchars($_POST['telefono']),
        'info' => htmlspecialchars($_POST['fotos']),
        'fotos' => htmlspecialchars($_POST['info']),
        'status' => htmlspecialchars($_POST['status']),
        'cocina' => ($_POST['id_cocina']),
        'usr' => $_POST['id_usuario'],
        'id' => $_POST['id'],
    ];

    $query = '
    UPDATE restaurantes SET nombre = :nombre, codigo_postal = :codigo_postal, telefono = :telefono, info = :info, fotos = :fotos, status = :status, cocina = :id_cocina, usr = id_usuario
    WHERE id = :id
    ';
    $stmt = $pdo->prepare($query);
    $stmt->execute($params);

    header("location: {$_SERVER['REQUEST_URI']}");

    return;
}
    if ($_POST['_method'] === 'DELETE') {
        $stmt = $pdo->prepare('DELETE FROM restaurantes WHERE id = ?');
        $stmt->execute([$_POST['id']]);

        header("location: {$_SERVER['REQUEST_URI']}");

        return;
    }

}
}
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
                        Restaurantes
                    </h3>
                    <div class="box-tools">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse">
                            <i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>

                <div class="box-body">
                    <?php if ($restauranteEdit) { ?>
                        <form class="form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre"
                                               value="<?= $restauranteEdit['nombre'] ?>">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="row">
                                        <div class="col-md-2">
                                            <div class="form-group">
                                                <label for="codigo_postal">Codigo Postal</label>
                                                <input type="text" class="form-control" id="codigo_postal" name="codigo_postal"
                                                       value="<?= $restauranteEdit['codigo_postal'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="telefono">Teléfono</label>
                                                <input type="text" class="form-control" id="telefono" name="telefono"
                                                       value="<?= $restauranteEdit['telefono'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <label for="info">Info</label>
                                                <input type="text" class="form-control" id="info" name="info"
                                                       value="<?= $restauranteEdit['info'] ?>">
                                            </div>
                                        </div>
                                        <div class="col-md-6">
                                            <div class="form-group">
                                                <input type="file" name="fotos" id="fotos" class="form-control"
                                                       value="<?= $restauranteEdit['fotos'] ?>">
                                                <input type="hidden" name="imagenOculta" id="imagenOculta">
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="status">Estatus</label>
                                        <select name="status" id="status" class="form-control">
                                            <option value="0"
                                                <?= !$restauranteEdit['status'] ? 'selected="selected"' : '' ?>>
                                                Inactivo
                                            </option>
                                            <option value="1"
                                                <?= $restauranteEdit['status'] ? 'selected="selected"' : '' ?>>
                                                Activo
                                            </option>
                                        </select>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12">
                                    <input type="hidden" name="_method" value="PUT">
                                    <input type="hidden" name="id" value="<?= $restauranteEdit['id'] ?>">
                                    <a href="<?= $_SERVER['REQUEST_URI'] ?>" class="btn btn-default">Cancelar</a>
                                    <input type="submit" value="Editar" class="btn btn-info pull-right">
                                </div>
                            </div>
                        </form>
                    <?php } else { ?>
                        <form class="form" method="post" enctype="multipart/form-data">
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="nombre">Nombre</label>
                                        <input type="text" class="form-control" id="nombre" name="nombre">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="codigo_postal">Codigo Postal</label>
                                        <input type="text" class="form-control" id="codigo_postal" name="codigo_postal">
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="telefono">Teléfono</label>
                                        <input type="text" class="form-control" id="telefono" name="telefono">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="fotos">Fotos</label>
                                        <input type="file" name="fotos" id="fotos" class="form-control">
                                        <input type="hidden" name="imagenOculta" id="imagenOculta">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="usr">Usuario</label>
                                        <select name="usr" id="id_usuario" class="form-control">
                                            <?php foreach ($usuarios as $users) { ?>
                                                <option value="<?= $session_id?>">
                                                    <?= $users['nombre'] ?>
                                                </option>
                                            <?php }?>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <div class="form-group">
                                        <label for="id_cocina">Tipo de Cocina</label>
                                        <select name="id_cocina" id="id_cocina" class="form-control">
                                            <?php foreach ($tipo_cocina as $cocinas) { ?>
                                            <option value="<?= $cocinas['id']?>">
                                                <?= $cocinas['nombre'] ?>
                                            </option>
                                            <?php }?>
                                        </select>
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
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="info">Información</label>
                                        <textarea type="text" class="form-control" id="info" name="info"></textarea>
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
                            <th>Cocina</th>
                            <th>Usuario</th>
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
                                <td><?= $restaurante['id_cocina'] ?></td>
                                <td><?= $restaurante['id_usuario'] ?></td>
                                <td align="center" valign="middle">
                                    <?php if ($restaurante['status']) { ?>
                                        <span class="label label-success">Activo</span>
                                    <?php } else { ?>
                                        <span class="label label-default">Inactivo</span>
                                    <?php } ?>
                                </td>
                                <td>
                                    <a href="direcciones.php?restaurant=<?= $restaurante['id'] ?>">Direcciones</a>
                                </td>
                                <td>
                                    <form method="post" style="margin-bottom: .5rem;">
                                        <input type="hidden" name="id" value="<?= $restaurante['id'] ?>">
                                        <button class="btn btn-xs btn-block btn-info">Editar</button>
                                    </form>
                                    <form method="post">
                                        <input type="hidden" name="_method" value="DELETE">
                                        <input type="hidden" name="id" value="<?= $restaurante['id'] ?>">
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

<script>
    $("#fotos").on('change', function() {
        let fotos = new FormData($("form")[0]);
        console.log($(this).val());
        $("#imagenOculta").val($(this).val());
        $.ajax({
            url:"includes/subida.php",
            type: "POST",
            data: fotos,
            contentType: false,
            processData: false,
            success: function(data){
                alert(data);
            }
        });
    });
</script>
</body>
</html>
