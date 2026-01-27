<?php require_once 'header.php';

?>


<main>
    <h1>Contacto</h1>
    <p class="contact-info">Para cualquier consulta, no dudes en ponerte en contacto con nosotros a través de los siguientes medios:</p>

    <!--Formulario de contacto -->
    <form class="formularioContacto" method="POST" action="./CONTOLLER/enviar_formulario.php">
        <h2>Formulario de contacto</h2>
        <div class="datosFormulario">

            <div class="form-group">

                <label for="nombre">Nombre:</label>
                <input type="text" id="nombre" name="nombre" required>

            </div>

            <div class="form-group">

                <label for="apellidos">Apellidos:</label>
                <input type="text" id="apellidos" name="apellidos" required>

            </div>


            <div class="form-group">
                <label for="email">Correo Electrónico:</label>
                <input type="email" id= "email" name="email" required>
            </div>

            <div class="form-group">
                <label for="asunto">Asunto:</label>
                <input type="text" id="asunto" name="asunto" required>
            </div>

            <div class="form-group">

                <label for="mensaje">Mensaje:</label>
                <textarea id="mensaje" name="mensaje" rows="5" required></textarea>
            </div>

            <button type="submit" class="btn">Enviar</button>
        </div>

        <div class="datosContacto">
            <div class="contact-info-item">
                <h3> <i class="fas fa-phone"> Número de teléfono</i> </h3>
                <p>+34 123 456 789</p>
            </div>

            <div class="contact-info-item">
                <h3><i class="fas fa-envelope"></i> Correo Electrónico:</h3>
                <p>contacto@ejemplo.com</p>
            </div>

            <div class="contact-info-item">
                <h3><i class="fas fa-map-marker-alt"></i> Dirección Física:</h3>
                <p>Calle Falsa 123, Ciudad, País</p>
            </div>

            <div class="contact-info-item">
                <h3><i class="fas fa-share-alt"></i> Síguenos en nuestras redes sociales</h3>
                <div class="socialMediaContacto">
                    <a href="#" title="Facebook"><i class="fab fa-facebook-f"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="LinkedIn"><i class="fab fa-linkedin-in"></i></a>
                </div>
            </div>
        </div>


        <style>
            /* Estilos específicos para la página de contacto */
            .contact-info {
                text-align: center;
                font-size: 1.2rem;
                margin-bottom: 2rem;
                color: var(--dark-gray);
            }

            .formularioContacto {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 3rem;
                background: var(--white);
                padding: 2rem;
                border-radius: 10px;
                box-shadow: var(--shadow);
                margin-top: 2rem;
            }

            .formularioContacto h2 {
                grid-column: 1 / -1;
                text-align: center;
                color: var(--primary-color);
                margin-bottom: 1rem;
                font-size: 1.8rem;
            }

            .datosFormulario {
                display: flex;
                flex-direction: column;
                gap: 1.5rem;
            }

            .form-group {
                display: flex;
                flex-direction: column;
            }

            .form-group label {
                font-weight: 600;
                color: var(--primary-color);
                margin-bottom: 0.5rem;
                font-size: 1rem;
            }

            .form-group input,
            .form-group textarea {
                padding: 0.8rem;
                border: 2px solid var(--medium-gray);
                border-radius: 5px;
                font-size: 1rem;
                transition: var(--transition);
                width: 100%;
                font-family: inherit;
            }

            .form-group input:focus,
            .form-group textarea:focus {
                outline: none;
                border-color: var(--secondary-color);
                box-shadow: 0 0 0 3px rgba(52, 152, 219, 0.1);
            }

            .form-group textarea {
                resize: vertical;
                min-height: 120px;
            }

            .datosFormulario .btn {
                padding: 1rem 2rem;
                background: var(--secondary-color);
                color: var(--white);
                border: none;
                border-radius: 5px;
                font-size: 1.1rem;
                font-weight: 600;
                cursor: pointer;
                transition: var(--transition);
                margin-top: 1rem;
                text-align: center;
            }

            .datosFormulario .btn:hover {
                background: #2980b9;
                transform: translateY(-2px);
            }

            .datosContacto {
                background: var(--light-gray);
                padding: 2rem;
                border-radius: 10px;
                display: flex;
                flex-direction: column;
                gap: 2rem;
            }

            .contact-info-item h3 {
                color: var(--primary-color);
                margin-bottom: 0.5rem;
                font-size: 1.1rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .contact-info-item h3 i {
                color: var(--secondary-color);
                width: 20px;
            }

            .contact-info-item p {
                color: var(--dark-gray);
                margin-left: 1.8rem;
            }

            .socialMediaContacto {
                display: flex;
                gap: 1rem;
                margin-top: 0.5rem;
                margin-left: 1.8rem;
            }

            .socialMediaContacto a {
                display: flex;
                align-items: center;
                justify-content: center;
                width: 45px;
                height: 45px;
                background: var(--secondary-color);
                color: var(--white);
                border-radius: 50%;
                text-decoration: none;
                transition: var(--transition);
                font-size: 1.1rem;
            }

            .socialMediaContacto a:hover {
                background: var(--accent-color);
                transform: translateY(-3px);
            }

            /* Responsive */
            @media (max-width: 768px) {
                .formularioContacto {
                    grid-template-columns: 1fr;
                    gap: 2rem;
                    padding: 1.5rem;
                }

                .datosContacto {
                    padding: 1.5rem;
                }

                .contact-info-item h3 {
                    font-size: 1rem;
                }
            }

            /* Estados de validación */
            .form-group input:valid,
            .form-group textarea:valid {
                border-color: #27ae60;
            }

            .form-group input:invalid:not(:focus):not(:placeholder-shown),
            .form-group textarea:invalid:not(:focus):not(:placeholder-shown) {
                border-color: var(--accent-color);
            }
        </style>


    </form>
</main>




<?php require_once 'footer.php'; ?>