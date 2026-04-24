# Avance backend Oracle

## Estado
Se validó conexión exitosa desde el backend nuevo en contenedor hacia Oracle 11g externo.

## Evidencia
- Endpoint `/health` funcional
- Endpoint `/db-check` funcional
- Endpoint `/oracle/pool-status` funcional para inspeccionar el estado del pool Oracle sin exponer credenciales
- Respuesta correcta: `{"status":"ok","database":"oracle","result":1}`
- Endpoint `/usuarios/buscar` funcional usando `usuarios.usuario`
- Endpoint `/vacaciones/resumen` funcional para consultar saldo base de vacaciones por usuario
- En `/vacaciones/resumen`, el saldo real `dias_por_tomar` se toma del procedimiento legado `VAC_PRC_CONTARDIASRESTANTES` para respetar el mismo valor mostrado por PHP
- Soporte para busqueda parcial por defecto y exacta con `exacto=true`
- Frontend nuevo en `localhost:3000` consumiendo `/usuarios/buscar` por medio de un proxy interno
- Frontend nuevo mostrando resumen de vacaciones al seleccionar usuario
- Endpoint `/solicitudes/consultar` funcional en modo solo lectura con filtros basicos y bind variables
- Endpoint `/solicitudes/consultar` con validacion explicita de filtros, rango de fechas y filtro opcional por `idsolicitud`
- Frontend nuevo consumiendo usuarios y solicitudes en un flujo simple: buscar usuario -> consultar solicitudes
- Endpoint `/solicitudes/detalle` funcional usando `VAC_PRC_HISTORIALSOLICITUD`
- Frontend nuevo con flujo completo de lectura: buscar usuario -> consultar solicitudes -> ver detalle

## Conclusión
La nueva arquitectura puede conectarse a Oracle 11g desde Docker usando Thick mode e Instant Client.

## Siguiente paso
Continuar con endpoints de lectura y agregar consumo controlado desde frontend nuevo.
