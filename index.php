<?php
// Datos de conexión a la base de datos
$servername = "localhost"; // Si estás en un servidor local
$username = "root"; // Tu usuario de MySQL (por defecto es "root")
$password = ""; // Tu contraseña de MySQL (por defecto es vacía)
$dbname = "Radio"; // El nombre de la base de datos que creaste

// Crear conexión
$conn = new mysqli($servername, $username, $password, $dbname);

// Comprobar la conexión
if ($conn->connect_error) {
    die("Conexión fallida: " . $conn->connect_error);
}

// Si se ha enviado el formulario, procesamos la información
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Recibir datos del formulario
    $nombre = $_POST['nombre'];
    $cabina_id = $_POST['cabina_id'];
    $genero_id = $_POST['genero_id'];

    // Insertar datos en la base de datos
    $sql_insert = "INSERT INTO locutores (nombre, cabina_id) VALUES ('$nombre', '$cabina_id')";
    
    if ($conn->query($sql_insert) === TRUE) {
        $last_id = $conn->insert_id; // Obtener el ID del último locutor insertado
        // Asociar el locutor con el género
        $sql_genre = "INSERT INTO locutor_genero (locutor_id, genero_id) VALUES ($last_id, $genero_id)";
        
        if ($conn->query($sql_genre) === TRUE) {
            echo "<div class='alert success'>Nuevo locutor agregado con éxito.</div>";
        } else {
            echo "<div class='alert error'>Error al asociar género: " . $conn->error . "</div>";
        }
    } else {
        echo "<div class='alert error'>Error al agregar locutor: " . $conn->error . "</div>";
    }
}

// Consulta SQL para obtener los locutores con su cabina y género
$sql = "SELECT l.nombre, c.estación_name, g.genero_name 
        FROM locutores l
        JOIN cabinas c ON l.cabina_id = c.cabina_id
        JOIN locutor_genero lg ON l.locutor_id = lg.locutor_id
        JOIN genero g ON lg.genero_id = g.genero_id";

$result = $conn->query($sql);

// Mostrar los locutores en una tabla
echo '<h3>Lista de Locutores</h3>';
if ($result->num_rows > 0) {
    echo "<table>";
    echo "<tr><th>Nombre del Locutor</th><th>Cabina</th><th>Género</th></tr>";
    while($row = $result->fetch_assoc()) {
        echo "<tr><td>" . $row["nombre"]. "</td><td>" . $row["estación_name"]. "</td><td>" . $row["genero_name"]. "</td></tr>";
    }
    echo "</table>";
} else {
    echo "<div class='alert info'>No hay locutores disponibles.</div>";
}

// Formulario para agregar un nuevo locutor
echo '<h3>Agregar Nuevo Locutor</h3>';
echo '<form method="POST" action="" class="form-container">
      <label for="nombre">Nombre del Locutor:</label>
      <input type="text" id="nombre" name="nombre" required>

      <label for="cabina_id">Selecciona una Cabina:</label>
      <select id="cabina_id" name="cabina_id" required>';

      // Consulta para obtener las cabinas disponibles
      $cabinas_sql = "SELECT * FROM cabinas";
      $cabinas_result = $conn->query($cabinas_sql);
      while ($cabina = $cabinas_result->fetch_assoc()) {
          echo '<option value="' . $cabina['cabina_id'] . '">' . $cabina['estación_name'] . '</option>';
      }

echo '</select>

      <label for="genero_id">Selecciona un Género:</label>
      <select id="genero_id" name="genero_id" required>';

      // Consulta para obtener los géneros disponibles
      $generos_sql = "SELECT * FROM genero";
      $generos_result = $conn->query($generos_sql);
      while ($genero = $generos_result->fetch_assoc()) {
          echo '<option value="' . $genero['genero_id'] . '">' . $genero['genero_name'] . '</option>';
      }

echo '</select>

      <input type="submit" value="Agregar Locutor" class="btn-submit">
      </form>';

// Cerrar la conexión
$conn->close();
?>

<!-- Estilos CSS modernos -->
<style>
    body {
        font-family: Arial, sans-serif;
        background-color: #f4f7fc;
        margin: 0;
        padding: 0;
    }

    h3 {
        color: #333;
        text-align: center;
        padding: 20px;
    }

    .form-container {
        background-color: #fff;
        padding: 20px;
        border-radius: 8px;
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        max-width: 500px;
        margin: 20px auto;
    }

    .form-container label {
        font-size: 14px;
        color: #555;
        margin-bottom: 5px;
        display: block;
    }

    .form-container input[type="text"], .form-container select {
        width: 100%;
        padding: 10px;
        margin-bottom: 20px;
        border: 1px solid #ddd;
        border-radius: 4px;
        font-size: 14px;
    }

    .form-container input[type="submit"] {
        background-color: #4CAF50;
        color: white;
        border: none;
        padding: 12px 20px;
        font-size: 16px;
        cursor: pointer;
        border-radius: 4px;
        width: 100%;
        transition: background-color 0.3s ease;
    }

    .form-container input[type="submit"]:hover {
        background-color: #45a049;
    }

    table {
        width: 80%;
        margin: 20px auto;
        border-collapse: collapse;
        text-align: left;
    }

    table, th, td {
        border: 1px solid #ddd;
    }

    th, td {
        padding: 12px;
        text-align: center;
    }

    th {
        background-color: #f4f7fc;
    }

    .alert {
        padding: 15px;
        margin: 20px auto;
        width: 80%;
        border-radius: 5px;
        text-align: center;
    }

    .success {
        background-color: #4CAF50;
        color: white;
    }

    .error {
        background-color: #f44336;
        color: white;
    }

    .info {
        background-color: #2196F3;
        color: white;
    }
</style>



