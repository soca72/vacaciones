EL proyecto original se ejecuta en:
http://192.168.1.38/vacaciones/

# Vacaciones - Nueva version

Base tecnica inicial para la modernizacion del proyecto.

## Servicios iniciales
- frontend
- backend

## Levantar proyecto
```bash
docker compose up --build
```

## Requisito local para Oracle
El backend usa Oracle Instant Client para conectarse a Oracle 11g desde el contenedor.

Antes de construir el backend, debe existir este archivo local:

```text
backend/oracle/instantclient-basic.zip
```

Notas:
- Ese zip no debe versionarse en git.
- Debe existir solo en tu copia local de trabajo.
- Si falta, el build del backend fallara al copiar el archivo dentro de la imagen.

## Pool Oracle en desarrollo
Si quieres reducir sesiones Oracle ociosas en desarrollo, puedes usar estos valores:

```text
ORACLE_POOL_MIN=0
ORACLE_POOL_MAX=5
ORACLE_POOL_INCREMENT=1
```

Con `ORACLE_POOL_MIN=0`, el backend no intenta mantener una sesion minima abierta por defecto.
