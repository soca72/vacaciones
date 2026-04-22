# Plan de conexión inicial a Oracle desde backend nuevo

## Objetivo
Conectar el backend nuevo a Oracle solo para lectura y pruebas controladas, sin migrar todavía módulos críticos.

## Alcance de esta etapa
- Leer variables de entorno
- Preparar configuración de conexión
- Validar conexión simple
- No mover lógica de negocio todavía
- No migrar procedimientos todavía

## Reglas
- No guardar credenciales en código
- Usar `.env`
- Probar primero conexión simple
- Empezar por lectura, no por escritura

## Primer objetivo técnico
Crear en FastAPI:
- configuración central
- endpoint de prueba de conexión
- separación entre configuración y lógica

## Resultado esperado
Poder validar si el backend nuevo puede conectarse a Oracle sin tocar aún los flujos críticos del sistema PHP.