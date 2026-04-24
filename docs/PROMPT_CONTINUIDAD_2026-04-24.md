# Prompt de continuidad

Actúa como arquitecto técnico y agente de implementación para continuar la modernización del proyecto "vacaciones".

## Contexto general
- Soy principiante.
- Trabajo en Windows 11 con VS Code.
- Este repo es mi copia personal de trabajo:
  `C:\GitHub_Proyectos\Personal\vacaciones`
- Rama actual:
  `feat/modernizacion-contenedores`
- No quiero mezclar nada empresarial con lo personal.
- Quiero modernizar el sistema, dejar PHP gradualmente, contenerizar la app y preparar una futura migración de base de datos.
- Debes explicarme en español claro lo que haces y por qué.
- Antes de cambios destructivos, pregúntame.
- No borres recursos de Docker agresivamente sin avisar.
- No expongas credenciales ni modifiques secretos reales.

## Estado actual confirmado
- Frontend nuevo en contenedor levantando en `localhost:3000`
- Backend FastAPI en contenedor levantando en `localhost:8000`
- Oracle 11g sigue siendo externo al contenedor
- `/health` funcionando
- `/db-check` funcionando
- `/oracle/procedimiento-args` funcionando
- `/usuarios/buscar` funcionando
- `/solicitudes/consultar` funcionando
- `/solicitudes/detalle` funcionando
- `/vacaciones/resumen` funcionando

## Decisiones ya tomadas
- Oracle 11g seguirá siendo externo al contenedor
- El backend usa Python + FastAPI
- El frontend es base nueva separada
- La estrategia es migrar primero lectura y después acciones sensibles
- No usar PHP nuevo para la nueva versión
- No tocar PostgreSQL todavía
- No migrar login todavía
- No migrar aprobación/cancelación todavía
- No romper lo que ya funciona
- No cambiar `.env` real ni credenciales sin pedírmelo

## Rama y resguardo
- Trabajamos sobre `feat/modernizacion-contenedores`
- Existe respaldo en `backup/modernizacion-2026-04-23`
- No tocar `main`

## Avance funcional ya logrado
1. Búsqueda de usuarios por `usuarios.usuario`
2. Soporte de búsqueda parcial y exacta con `exacto=true`
3. Consulta de solicitudes en solo lectura
4. Detalle de solicitud en solo lectura
5. Frontend mínimo conectado de punta a punta
6. Consulta directa de solicitudes por `idsolicitud`
7. Corrección del cálculo de `dias derecho` para antigüedad mayor a 16
8. Corrección del saldo real de vacaciones para `scardenas`

## Corrección importante ya validada
- Usuario validado: `scardenas`
- `idusuario`: `88`
- El dato correcto de "días por tomar" es `33`
- El bug era que el backend nuevo recalculaba mal el saldo como `dias_derecho - dias_tomados`
- Ya se corrigió para tomar el saldo real desde el procedimiento legado `VAC_PRC_CONTARDIASRESTANTES`
- Ahora `/vacaciones/resumen` devuelve correctamente:
  - `dias_por_tomar: 33`
  - `dias_restantes: 33`
- Esto ya quedó validado por navegador y endpoint

## Archivos relevantes tocados recientemente
- `backend/app/services/vacation_service.py`
- `frontend/page-template.js`
- `frontend/proxy-handlers.js`
- `docs/AVANCE_BACKEND_ORACLE.md`
- `docs/FLUJO_TECNICO_ACTUAL.md`

## Forma de trabajo deseada
1. Primero inspecciona el repo actual
2. Resume en español el estado vigente
3. Revisa `git status` y confirma qué cambios están sin commit
4. Propón el siguiente paso pequeño y seguro
5. Implementa ese paso
6. Ejecuta validaciones
7. Si algo falla, revisa logs y corrige
8. Al final explícame:
   - qué cambiaste
   - qué archivos tocaste
   - qué comandos ejecutaste
   - cómo validar el resultado

## Primer paso para mañana
- Revisar el estado actual del repo
- Confirmar qué cambios locales siguen pendientes
- Decidir si conviene hacer un commit limpio del arreglo de vacaciones antes de seguir con nuevas funcionalidades
- Luego proponer el siguiente paso técnico más seguro
