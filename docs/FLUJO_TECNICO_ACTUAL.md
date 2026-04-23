# Flujo tecnico actual

## Objetivo de este documento
Tener un mapa simple de la arquitectura que hoy ya funciona en la rama de modernizacion, para poder seguir avanzando sin perder contexto.

## Componentes activos
- `frontend`
  - Contenedor Node.js
  - Expone `http://localhost:3000`
  - Sirve una pantalla minima para buscar usuarios, consultar solicitudes y ver detalle
  - Funciona como proxy hacia el backend nuevo

- `backend`
  - Contenedor Python con FastAPI
  - Expone `http://localhost:8000`
  - Publica endpoints REST nuevos
  - Se conecta a Oracle 11g externo usando Oracle Instant Client

- `Oracle 11g externo`
  - No esta dentro de Docker
  - Sigue siendo la fuente real de datos en esta etapa

## Flujo actual de lectura
### 1. Buscar usuario
Ruta visible:
- `http://localhost:3000`

Ruta frontend proxy:
- `GET /api/usuarios/buscar?q=scardenas`
- `GET /api/usuarios/buscar?q=scardenas&exacto=true`

Ruta backend:
- `GET /usuarios/buscar?q=scardenas`
- `GET /usuarios/buscar?q=scardenas&exacto=true`

Comportamiento:
- Busca por `usuarios.usuario`
- Usa bind variables
- Soporta coincidencia parcial por defecto
- Soporta coincidencia exacta con `exacto=true`

### 2. Consultar solicitudes
Ruta frontend proxy:
- `GET /api/solicitudes/consultar?...`

Ruta backend:
- `GET /solicitudes/consultar?...`

Filtros actuales:
- `idusuariodata_autorizador`
- `idusuariodata`
- `estado`
- `fi`
- `ff`
- `tipo_fecha`
- `limit`

Comportamiento:
- Consulta de solo lectura
- Usa filtros dinamicos controlados
- Usa bind variables

### 3. Ver detalle de solicitud
Ruta frontend proxy:
- `GET /api/solicitudes/detalle?idsolicitud=...`

Ruta backend:
- `GET /solicitudes/detalle?idsolicitud=...`

Comportamiento:
- Consulta detalle e historial de una solicitud
- Usa el procedimiento Oracle `VAC_PRC_HISTORIALSOLICITUD`
- Se muestra inline debajo de la solicitud seleccionada

## Flujo extremo a extremo
1. El navegador entra a `http://localhost:3000`
2. El frontend renderiza la pantalla minima
3. El frontend llama a sus rutas `/api/...`
4. El proxy del frontend reenvia la peticion al backend FastAPI
5. El backend consulta Oracle 11g externo
6. El backend responde JSON
7. El frontend muestra resultados en pantalla

## Endpoints ya operativos
- `GET /health`
- `GET /db-check`
- `GET /oracle/procedimiento-args`
- `GET /usuarios/buscar`
- `GET /solicitudes/consultar`
- `GET /solicitudes/detalle`

## Archivos clave hoy
- `docker-compose.yml`
- `frontend/server.js`
- `frontend/page-template.js`
- `frontend/proxy-handlers.js`
- `backend/app/main.py`
- `backend/app/services/users_service.py`
- `backend/app/services/requests_service.py`

## Validacion rapida
### Backend
- `http://localhost:8000/health`
- `http://localhost:8000/usuarios/buscar?q=scardenas`
- `http://localhost:8000/usuarios/buscar?q=scardenas&exacto=true`

### Frontend
- `http://localhost:3000`
- `http://localhost:3000/api/usuarios/buscar?q=scardenas&exacto=true`

## Decisiones vigentes
- Oracle 11g sigue siendo externo
- El backend nuevo usa FastAPI
- El frontend nuevo sigue siendo una base minima en Node
- La migracion se esta haciendo primero por lectura
- Login, aprobacion y cancelacion todavia no se migran
- El pool Oracle puede configurarse para no mantener sesiones minimas ociosas con `ORACLE_POOL_MIN=0`

## Siguiente paso sugerido
Seguir con casos de uso de solo lectura o mejorar la estructura interna del frontend para facilitar futuros cambios.
