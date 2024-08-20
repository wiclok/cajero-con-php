<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">

  <!-- bootstrap -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

  <!-- css -->
  <link rel="stylesheet" href="./index.css">

  <title>Document</title>
</head>

<body>

  <div class="videoContainer">
    <video class="backgroundVideo" muted autoplay loop src="./public/assets/background3.mp4"></video>
  </div>
  
  <?php
session_start();

$message = '';
$autenticado = false;

if ($_SERVER["REQUEST_METHOD"] == "POST") {
  if (isset($_POST['usuario']) && isset($_POST['contrasena'])) {
    $usuario = trim(htmlspecialchars($_POST['usuario']));
    $contrasena = trim(htmlspecialchars($_POST['contrasena']));

    $jsonFile = 'usuarios.json';
    if (file_exists($jsonFile)) {
      $jsonData = file_get_contents($jsonFile); 
      $usuarios = json_decode($jsonData, true);

      foreach ($usuarios as $user) {
        if ($user['usuario'] === $usuario && $user['contrasena'] === $contrasena) {
          $autenticado = true;

          $_SESSION['user'] = $user;
          $_SESSION['autenticado'] = $autenticado;

          header("Location: cajero.php");
          exit;
        }
      }

      $message = $autenticado ? 'Usuario autenticado' : 'Usuario o contraseña incorrectos';
    } else {
      $message = 'El archivo de usuarios no existe.';
    }
  } else {
    $message = 'Por favor, complete todos los campos.';
  }
}
?>


  <div class="containerLogin">
    <div class="containerLogin-title">
      <img class="container-title_iconBank" src="./public/assets/banco.png" alt="icono de banco">
      <h1>Cajero Automático</h1>
    </div>

    <div class="containerLogin-form">
      <h1>Iniciar Sesión</h1>
      <div class="containerLogin-form_formAction">
        <form action="" method="POST">
          <input type="text" placeholder="Usuario" class="form-control mb-5 color-white" id="usuario" name="usuario" required>
          <input type="password" placeholder="Contraseña" class="form-control" id="contrasena" name="contrasena" required>
          <button type="submit" class="btn btn-primary mt-4">Ingresar</button>
        </form>
        <?php if (!empty($message)): ?>
          <div class="mt-3 alert <?php echo $autenticado ? 'alert-success' : 'alert-danger'; ?>">
            <?php echo $message; ?>
          </div>
        <?php endif; ?>
      </div>
    </div>
  </div>

</body>

</html>
