# Arquitectura objetivo - Proyecto vacaciones

## 1. Objetivo
Modernizar el sistema de vacaciones para dejar PHP gradualmente, contenerizar la aplicación y preparar una futura migración de base de datos sin romper la operación actual.

## 2. Problemas del sistema actual
- Aplicación PHP tradicional acoplada
- Frontend y backend no separados
- Dependencia alta de Oracle y procedimientos almacenados
- Riesgos de seguridad y mantenimiento
- Dependencias frontend viejas o duplicadas
- Poca documentación técnica

## 3. Arquitectura propuesta
### Frontend
- React
- Interfaz moderna por componentes
- Consumo de API REST

### Backend
- Python con FastAPI
- API REST
- Separación por capas:
  - rutas
  - servicios
  - acceso a datos
  - configuración

### Infraestructura
- Docker Compose
- Variables en `.env`
- Contenedor de frontend
- Contenedor de backend
- PostgreSQL para desarrollo o nueva versión
- Oracle como sistema legado externo al inicio

## 4. Estrategia de migración
### Etapa 1
- Diagnosticar sistema actual
- Documentar módulos
- Identificar dependencias Oracle

### Etapa 2
- Crear nueva base técnica con contenedores
- Levantar frontend y backend vacíos pero funcionales

### Etapa 3
- Conectar backend nuevo a Oracle
- Replicar primero módulos de consulta

### Etapa 4
- Crear nueva base en PostgreSQL
- Diseñar migración gradual

### Etapa 5
- Migrar módulos de captura y procesos críticos
- Retirar PHP progresivamente

## 5. Estructura propuesta
- frontend/
- backend/
- docs/
- docker-compose.yml
- .env.example

## 6. Reglas importantes
- No guardar credenciales en código
- No migrar datos sin respaldo
- No reescribir todo de golpe
- Migrar módulo por módulo

## 7. Primeros módulos sugeridos para migrar
- Login
- Consulta de solicitudes
- Aprobación/cancelación
- Búsqueda de usuarios

## 8. Riesgos a vigilar
- Lógica oculta en procedimientos Oracle
- Dependencias no detectadas todavía
- Diferencias entre datos Oracle y PostgreSQL
- Riesgo de romper flujos existentes