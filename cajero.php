<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['logout'])) {
    $_SESSION = array();

    if (ini_get("session.use_cookies")) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000,
            $params["path"], $params["domain"],
            $params["secure"], $params["httponly"]
        );
    }

    session_destroy();

    header("Location: index.php");
    exit;
}

if (isset($_SESSION['autenticado']) && $_SESSION['autenticado']) {
    $user = $_SESSION['user'];

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['deposito'])) {
        $deposito = floatval($_POST['deposito']);

        if ($deposito > 0) {
            $user['billetera'] += $deposito;

            $_SESSION['user'] = $user;

            $jsonFile = 'usuarios.json';
            if (file_exists($jsonFile)) {
                $jsonData = file_get_contents($jsonFile);
                $usuarios = json_decode($jsonData, true);

                foreach ($usuarios as &$u) {
                    if ($u['usuario'] === $user['usuario']) {
                        $u['billetera'] = $user['billetera'];
                        break;
                    }
                }

                file_put_contents($jsonFile, json_encode($usuarios, JSON_PRETTY_PRINT));
            }
        } else {
            echo "<script>alert('El monto del depósito debe ser mayor que cero');</script>";
        }
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['extraccion'])) {
        $extraccion = floatval($_POST['extraccion']);

        if ($extraccion > 0 && $extraccion <= $user['billetera']) {
            $user['billetera'] -= $extraccion;

            $_SESSION['user'] = $user;

            $jsonFile = 'usuarios.json';
            if (file_exists($jsonFile)) {
                $jsonData = file_get_contents($jsonFile);
                $usuarios = json_decode($jsonData, true);

                foreach ($usuarios as &$u) {
                    if ($u['usuario'] === $user['usuario']) {
                        $u['billetera'] = $user['billetera'];
                        break;
                    }
                }

                file_put_contents($jsonFile, json_encode($usuarios, JSON_PRETTY_PRINT));
            }
        } else {
            echo "<script>alert('El monto de la extracción no es válido. Verifique su saldo.');</script>";
        }
    }

} else {
    header("Location: index.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- css -->
  <link rel="stylesheet" href="./index.css">
  <link rel="stylesheet" href="./cajero.css">

  <title>Document</title>
</head>
<body>

  <div class="videoContainer">
    <video class="backgroundVideo" muted autoplay loop src="./public/assets/background3.mp4"></video>
  </div>

  <div class="containerLogin">
    <h1><?php echo "Bienvenido, " . htmlspecialchars($user['usuario']) . "!"; ?></h1>
    <h5 class="text-white">Su saldo actual es de: $<?php echo htmlspecialchars($user['billetera']) ?></h5>

    <dialog id="modal-deposito">
      <p>Realizar depósito</p>
      <form method="post">
        <p>Su saldo actual es de: $<?php echo htmlspecialchars($user['billetera']) ?></p>
        <input type="number" placeholder="Cantidad" class="form-control mb-5 color-white" id="deposito" name="deposito" required>
        <button type="submit" class="btn btn-primary mt-4">Depositar</button>
        <button type="button" class="btn btn-secondary mt-4" onclick="document.getElementById('modal-deposito').close()">Cancelar</button>
      </form>
    </dialog>

    <dialog id="modal-extraccion">
      <p>Realizar extracción</p>
      <form method="post">
        <p>Su saldo actual es de: $<?php echo htmlspecialchars($user['billetera']) ?></p>
        <input type="number" placeholder="Cantidad" class="form-control mb-5 color-white" id="extraccion" name="extraccion" required>
        <button type="submit" class="btn btn-primary mt-4">Extraer</button>
        <button type="button" class="btn btn-secondary mt-4" onclick="document.getElementById('modal-extraccion').close()">Cancelar</button>
      </form>
    </dialog>

    <button id="button-deposito" class="button-modal">Realizar Depósito</button>
    <button id="button-extraccion" class="button-modal">Realizar Extracción</button>

    <form method="post" style="margin-top: 20px;">
      <button type="submit" name="logout" class="btn btn-danger mt-4">Cerrar Sesión</button>
    </form>
  </div>

  <script>
    const modalDeposito = document.getElementById('modal-deposito');
    const modalExtraccion = document.getElementById('modal-extraccion');
    const buttonDeposito = document.getElementById('button-deposito');
    const buttonExtraccion = document.getElementById('button-extraccion');

    buttonDeposito.addEventListener('click', () => {
      modalDeposito.showModal();
    });

    buttonExtraccion.addEventListener('click', () => {
      modalExtraccion.showModal();
    });
  </script>

</body>
</html>
