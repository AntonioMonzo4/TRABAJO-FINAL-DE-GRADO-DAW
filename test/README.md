# Tests del proyecto (Círculos de Atenea)

Carpeta de pruebas rápidas para validar:
- Integridad de base de datos (usuarios, stock, categorías)
- Reglas básicas de seguridad (password_hash)
- Lógica del carrito (LocalStorage) mediante una página de test

## Estructura

- `seed.sql`: datos mínimos de prueba
- `php/`: tests PHP sin frameworks (runner simple)
- `js/`: test manual del carrito

## Preparación (Local)

1) Importa `test/seed.sql` en tu base de datos (phpMyAdmin):
- Selecciona la BD
- Importar -> `test/seed.sql`

2) Asegúrate de que tu proyecto conecta usando:
`/MODEL/conexion.php`

## Ejecutar tests PHP (Local)

Desde la raíz del proyecto:

```bash
php test/php/TestRunner.php
