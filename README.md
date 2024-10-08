# Proyecto de Cajero Automático en PHP

Este proyecto es una simulación de un cajero automático implementado en PHP. Permite a los usuarios realizar depósitos y extracciones de dinero, y gestiona el saldo de la "billetera" del usuario a través de una sesión y almacenamiento en un archivo JSON.

## Descripción
El sistema permite a los usuarios autenticados realizar las siguientes acciones:

- Depositar dinero: Añadir fondos a su saldo.
- Extraer dinero: Retirar fondos de su saldo, siempre que haya suficiente saldo disponible.
- Cerrar sesión: Terminar la sesión actual y redirigir al usuario a la página de inicio de sesión.

## Estructura del Proyecto

- index.php: Página de inicio del cajero automático. Permite el inicio de sesión.

- cajero.php: Página principal después del inicio de sesión, donde se pueden realizar depósitos y extracciones.

- usuarios.json: Archivo JSON que contiene la información de los usuarios y sus saldos.

- public/assets/background3.mp4: Video de fondo para la interfaz de usuario.

- index.css: Hoja de estilos para la página de inicio.

- cajero.css: Hoja de estilos para la interfaz del cajero automático.