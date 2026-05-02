---
name: levlup-skill
description: "Skill exclusiva del Proyecto Final de 2º DAW. App Levlup: WebApp de gestión de tareas, seguimiento de hábitos y gamificación. Usar SIEMPRE que el usuario mencione Levlup o cualquier aspecto técnico o de diseño del proyecto."
---

## Contexto

- Laravel 12, PHP 8+, Blade, JavaScript puro, Bootstrap + CSS propio, MySQL.
- Responder siempre en castellano.

---

## Reglas de desarrollo

- MVC estricto: lógica en servicios, controladores solo gestionan petición y delegan, modelos solo relaciones y scopes, vistas solo presentación.
- Validaciones con $request->validate() en el controlador. Sin DTOs.
- Prohibido CSS o JS inline. JS en public/js/, CSS en public/css/.
- CSS modular: app.css importa base.css, layout.css, components.css, dashboard.css, tareas.css, habitos.css, sugerencias.css, intereses.css.
- Código y comentarios en castellano. Snake_case en BD.
- Nivel junior: claro, directo, sin sobreingeniería.

---

## Reglas críticas

- Nunca eliminar ni romper funcionalidades existentes.
- Nunca asumir contexto. Pedir archivos necesarios antes de generar código.
- Parámetro de ruta Task es {tarea}, Habit es {habito}.
- Nunca modificar points ni level directamente, siempre via User::addPoints().
- Tareas completadas no se pueden editar ni eliminar.
- Hábitos se archivan (active=false), no se eliminan.