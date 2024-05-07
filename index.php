<?php
function obtenerPokemonAleatorio() {
    $numero_pokemon = rand(1, 151);
    $url = "https://pokeapi.co/api/v2/pokemon/$numero_pokemon";

    $response = @file_get_contents($url);
    if ($response === false) {
        return ['error' => 'Error al conectar con la API'];
    }

    $pokemon = json_decode($response, true);
    if ($pokemon === null) {
        return ['error' => 'Error con la respuesta'];
    }
    return $pokemon;
}


$pokemon = null;


if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $pokemon = obtenerPokemonAleatorio();
}

$nombre = $_POST['nombre'] ?? '';
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pokemon</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <style>
        body {
            background-color: #f0f0f0;
        }
        .pokemon-card {
            border: 1px solid #ccc;
            text-align: center; 
            display: flex;
            margin-left: 30px;
        }
        .pokemon-card img {
            max-width: 100%; 
            display: block;
            margin: 0 auto; 
        }
    </style>
</head>
<body>
<div class="d-flex justify-content-center mt-5">
    <h1>DESCUBRE QUÉ POKEMÓN ERES</h1>
</div>
<div class="alert alert-dismissible alert-danger" id="modal" style="display: none;">
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                <strong>Uy, casi!</strong> <a href="#" class="alert-link">Ingresa un nombre</a> e inténtalo de nuevo.
    </div>
<div class="container-fluid mt-5" style="display: flex; justify-content: center;">

    <div class="card text-white bg-warning mb-3" style="max-width: 20rem;">
        <div class="card-header">° ° °</div>
        <div class="card-body">
            <legend style="display: flex; justify-content: center;">¿Eres tu pokemon favorito? Descúbrelo jugando y deja que el destino te lo diga...</legend>
            <form method="POST" action="" id="pokemon-form">
                <fieldset style=" justify-content: center;">
                    <legend>Ingresa tu nombre:</legend>
                    <input type="text" id="nombre" name="nombre">
                    <div class="row mt-3">
                        <div class="col">
                        <button type="button" class="btn btn-success" onclick="consultarPokemon()">Obtener Pokémon</button>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>

    <?php if (isset($pokemon)): ?>
        <div class="pokemon-container">
            <?php if (isset($pokemon['error'])): ?>
                <p><?php echo $pokemon['error']; ?></p>
            <?php elseif ($nombre && isset($pokemon['sprites']['front_default'])): ?>
                <div class="pokemon-card">
                    <p><?php echo $nombre; ?>, tu Pokémon es:</p> 
                    <img src="<?php echo $pokemon['sprites']['front_default']; ?>" alt="Imagen del Pokémon">
                    <div>
                        <h3><?php echo ucfirst($pokemon['name']); ?></h3>
                        <p>Altura: <?php echo $pokemon['height']; ?> decímetros</p>
                        <p>Peso: <?php echo $pokemon['weight']; ?> kilos</p>
                        <p>Habilidades:</p>
                        <ul>
                            <?php foreach ($pokemon['abilities'] as $ability): ?>
                                <li><?php echo $ability['ability']['name']; ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    <?php endif; ?>
    
</div>
<script>
    function consultarPokemon() {
        var nombre = document.getElementById('nombre').value;

        if (nombre.trim() === '') {
            document.getElementById("modal").style.display = "block";
            setTimeout(function() {
                document.getElementById("modal").style.display = "none";
            }, 1000);
        return;
        }

        document.getElementById('pokemon-form').submit();
    }
</script>
</body>
</html>
