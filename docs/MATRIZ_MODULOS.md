# Matriz de módulos - Proyecto vacaciones

| Modulo | Archivos PHP actuales | Procedimientos Oracle / consultas | Tipo | Prioridad | Dificultad | Migracion sugerida | Notas |
|---|---|---|---|---|---|---|---|
| Login | index.php, php/comprobarusuario.php, php/conexion.php | VAC_PRC_comprobarusuario, VAC_PRC_comprobarusuario2 | Autenticacion | Alta | Media | Fase 1 | Flujo de entrada al sistema. Revisar uso de SESSION y manejo de errores. |
| Pantalla principal | php/main.php | VAC_PRC_permisosusario y otros relacionados | Navegacion | Alta | Media | Fase 1 | Genera gran parte del HTML y concentra flujo principal. |
| Consulta de solicitudes | php/filtrasolicitudes.php, js/funcionesadministrar.js | SQL directo y consultas Oracle | Consulta | Alta | Media | Fase 1 | Buen candidato inicial para migrar porque permite validar lectura sin tocar escritura. |
| Aprobacion de solicitud | php/apruebasolicitud.php, js/funcionesadministrar.js | Oracle / logica de negocio asociada | Accion | Alta | Alta | Fase 2 | Actualmente se dispara por GET; requiere rediseño a POST/API. |
| Cancelacion de solicitud | php/cancelasolicitud.php, js/funcionesadministrar.js | Oracle / logica de negocio asociada | Accion | Alta | Alta | Fase 2 | Similar a aprobacion; revisar reglas de negocio. |
| Aprobacion masiva | php/apruebasolicitudMasiva.php | Oracle / logica de negocio asociada | Accion masiva | Media | Alta | Fase 3 | Migrar despues de aprobar el flujo individual. |
| Cancelacion masiva | php/cancelasolicitudMasiva.php | Oracle / logica de negocio asociada | Accion masiva | Media | Alta | Fase 3 | Migrar despues del flujo individual. |
| Busqueda de usuarios | livesearch2.php, componentes select2 | VAC_PRC_RETUSRPORNOMFILT | Consulta auxiliar | Media | Media | Fase 2 | Importante para formularios y filtros. |
| Dias restantes / calculos | archivos por identificar, php/funciones.php | VAC_PRC_CONTARDIASRESTANTES | Regla de negocio | Alta | Alta | Fase 2 | Validar si la regla vive totalmente en Oracle. |
| Calendario / utilidades | php/funcionesCalendario.php, main.php | Oracle y/o logica PHP | Soporte visual | Media | Media | Fase 3 | Revisar dependencia real antes de migrar. |
| Correo | Mail/, archivos PHP por identificar | PHPMailer | Integracion | Media | Media | Fase 3 | Migrar cuando los flujos principales ya funcionen. |
| Archivos historicos duplicados | administrar(230511).php, main(230718).php, apruebasolicitud(230508).php y similares | No aplica | Riesgo tecnico | Alta | Media | Fase 0 | Identificar cuales siguen vigentes y cuales son historicos. |

## Reglas para completar esta matriz
- Agregar una fila por cada modulo real detectado.
- No asumir logica que no este confirmada.
- Marcar explicitamente cuando un procedimiento Oracle no este identificado por nombre.
- Priorizar primero lectura y consulta, luego captura y acciones, luego procesos masivos y auxiliares.

## Orden sugerido de migracion
1. Login
2. Consulta de solicitudes
3. Pantalla principal / navegacion
4. Busqueda de usuarios
5. Aprobacion y cancelacion individual
6. Calculos de dias / reglas de negocio
7. Procesos masivos
8. Correo y auxiliares