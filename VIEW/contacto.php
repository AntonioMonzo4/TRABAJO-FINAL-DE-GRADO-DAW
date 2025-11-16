<?php require_once 'header.php'; 
//TODO: que el formulario se envia al correo electronico de la tienda
?>


<main>
<h1>Contacto</h1>
<p>Para cualquier consulta, no dudes en ponerte en contacto con nosotros a través de los siguientes medios:</p>

<form class="formularioContacto" method="POST" action="./CONTOLLER/enviar_formulario.php">
    <h1>Formulario de contacto</h1>
    <div class="datosFormulario">
        <label for="nombre">Nombre:</label>
        <input type="text" id="nombre" name="nombre" required>

        <label for="apellidos">Apellidos:</label>
        <input type="text" id="apellidos" name="apellidos" required>

        <label for="email">Correo Electrónico:</label>
        <input type="email" id="email" name="email" required>

        <label for="asunto">Asunto:</label>
        <input type="text" id="asunto" name="asunto" required>

        <label for="mensaje">Mensaje:</label>
        <textarea id="mensaje" name="mensaje" rows="5" required></textarea>

        <button type="submit">Enviar</button>
    </div>

    <div class="datosContacto">
        <h2>Número de teléfono:</h2>
        <p>+34 123 456 789</p>
        
        <h2>Correo Electrónico:</h2>
        <p>contacto@ejemplo.com</p>     

        <h2>Dirección Física:</h2>
        <p>Calle Falsa 123, Ciudad, País</p>    

        <h2>Siguenos en nuestras redes sociales</h2>
        <div class="socialMediaContacto">
            <a href="" title="Facebook"><i></i></a>
            <a href="" title="Twitter"><i></i></a>
            <a href="" title="Instagram"><i></i></a>
        </div>
    </div>

</form>
</main>




<?php require_once 'footer.php'; ?>