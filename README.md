
# 📚 Círculos de Atenea — Tienda Online de Libros

Proyecto de **Tienda Ecommerce** desarrollado como **Trabajo de Fin de Grado (DAW)** por **Antonio Monzó**.

Se trata de una **librería online completa** con sistema de usuarios, carrito de compra, gestión de pedidos y panel de administración, desarrollada desde cero en PHP y MySQL siguiendo un patrón MVC sencillo.

---

## 🚀 Funcionalidades principales

### 👤 Usuarios
- Registro y login con contraseñas cifradas (`password_hash`)
- Edición de perfil
- Historial de pedidos
- Sistema de roles: **cliente** y **administrador**

### 🛒 Tienda
- Listado de libros por categorías
- Página de detalle de producto
- Carrito de compra usando **LocalStorage**
- Cálculo automático de totales
- Simulación de proceso de compra (checkout)

### 📦 Pedidos
- Creación de pedidos desde el carrito
- Control de stock en servidor (no permite comprar más de lo disponible)
- Estados de pedido (pendiente, procesando, enviado, etc.)
- Vista de pedidos del usuario

### 🔐 Panel de Administración
- Acceso solo para administradores
- Gestión de:
  - Stock de productos
  - Usuarios
  - Pedidos
- Modificación de estado de pedidos
- Edición de roles de usuario

### 📄 Páginas informativas
- Sobre nosotros
- Contacto
- Aviso legal
- Política de privacidad
- Cookies

---

## 🧱 Arquitectura del proyecto

- Backend en **PHP** con router propio (`rutas.php`)
- Base de datos **MySQL**
- Frontend en **HTML + CSS + JavaScript**
- Carrito gestionado en **LocalStorage**
- Patrón tipo **MVC**:
/CONTROLLER
/VIEW
/MODEL
/VIEW/admin
/VIEW/static

---

## 🛠️ Tecnologías utilizadas

- PHP 7+
- MySQL / MariaDB
- JavaScript (Vanilla)
- HTML5
- CSS3
- Git / GitHub
- Hostinger (hosting + dominio)

---

## ⚙️ Instalación en local

1. Clonar el repositorio:
   
 git clone https://github.com/tuusuario/tu-repo.git


2. Importar la base de datos desde phpMyAdmin:

Archivo .sql incluido en el proyecto

3. Configurar la conexión a la BD en:

4. Apuntar el servidor (XAMPP, Laragon, etc.) al directorio del proyecto

5. Acceder desde el navegador:

http://localhost/tu-proyecto

🔑 Usuarios de prueba

Puedes crear usuarios desde el registro.

Para crear un administrador, cambia el campo rol en la base de datos:

UPDATE users SET rol = 'admin' WHERE email = 'tucorreo@ejemplo.com';

🧠 Lo que demuestra este proyecto

Arquitectura web completa

CRUDs complejos

Autenticación y roles

Seguridad básica (hash de contraseñas, validaciones)

Gestión de stock real

Lógica de negocio en servidor

Frontend funcional sin frameworks

Sistema de rutas propio

📌 Estado del proyecto

🟢 Funcional y en desarrollo continuo
🔧 Se pueden añadir en el futuro:

Pasarela de pago real

Envíos reales

Facturas en PDF

Sistema de reseñas

Buscador avanzado

👨‍💻 Autor

Antonio Monzó
Proyecto realizado como Trabajo de Fin de Grado (DAW)

📄 Licencia

Este proyecto es de uso educativo y demostrativo.




