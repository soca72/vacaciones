# Diagnóstico técnico - Proyecto vacaciones

## 1. Objetivo
Entender el sistema actual antes de modernizarlo.

## 2. Stack actual detectado
- Lenguaje:
- Front-end:
- Back-end:
- Base de datos:
- Servidor / entorno:

## 3. Estructura del proyecto
- Carpeta principal:
- Módulos detectados:
- Archivos clave:

## 4. Funcionamiento actual
- Cómo inicia la aplicación:
- Cómo navega:
- Cómo se conecta a la base de datos:
- Principales pantallas o módulos:

## 5. Riesgos técnicos visibles
- 
- 
- 

## 6. Qué conviene conservar
- 
- 

## 7. Qué conviene modernizar
- 
- 

## 8. Siguientes pasos
- Completar inventario del sistema
- Identificar dependencias
- Revisar conexión a base de datos
- Definir arquitectura objetivo

1. Stack actual

Por lo que sí se ve en el código, este proyecto usa:

PHP clásico en el servidor.
HTML, CSS y JavaScript en el cliente.
jQuery para interacción y AJAX.
Bootstrap para estilos y componentes.
Oracle como base de datos, usando funciones oci_*.
PHPMailer para envío de correos.
$_SESSION para manejo de sesión/login.
No se ve evidencia de:

Laravel, Symfony, CodeIgniter.
React, Vue, Angular.
Un sistema de build moderno en la raíz como npm, vite, webpack.
Entonces, en simple: es una aplicación PHP tradicional, no una app moderna separada en frontend y API.

2. Estructura de carpetas

La estructura principal del proyecto es:

index.php
Página de entrada y login.
php/
Aquí vive casi toda la lógica del sistema.
js/
Librerías JavaScript y scripts propios.
css/
Estilos y librerías CSS.
Mail/
Librería PHPMailer.
images/, img/, fonts/
Recursos estáticos.
.well-known/
Archivos públicos de configuración/verificación.
README.md
Muy breve; no documenta técnicamente el proyecto.
Dentro de php/ hay muchos archivos funcionales, por ejemplo:

conexion.php: conexión a Oracle.
comprobarusuario.php: login.
main.php: pantalla principal.
filtrasolicitudes.php: consultas filtradas.
apruebasolicitud.php, cancelasolicitud.php: acciones de aprobación/cancelación.
funciones.php, funcionesCalendario.php: utilidades.
También hay muchos archivos duplicados con fechas o versiones en el nombre:

administrar(230511).php
main(230718).php
apruebasolicitud(230508).php
Eso sugiere versiones guardadas manualmente dentro del proyecto.

3. Dependencias principales

Las dependencias visibles más importantes son:

Oracle OCI para PHP:
Se usa en php/conexion.php.
jQuery 3.1.0:
Se carga desde index.php.
Bootstrap:
También se carga desde archivos locales.
Select2:
Usado en la pantalla principal para búsquedas.
Pickadate / timepicki:
Para fechas y horas.
Moment.js
PHPMailer
Además hay otras librerías copiadas dentro del repo, como:

DataTables
bootstrap-table
archivos de Bootstrap 4 y Bootstrap 5
Pero no todas se ven claramente activas en el flujo principal. Algunas podrían ser arrastre histórico.

4. Cómo funciona el front-end actual

El front-end actual funciona así:

El usuario entra a index.php.
Ve un formulario de login.
Ese formulario envía datos a php/comprobarusuario.php.
Si el login es correcto, se guardan variables en sesión.
Luego entra a php/main.php.
main.php genera gran parte del HTML de la interfaz.
JavaScript y jQuery hacen peticiones AJAX a otros archivos PHP para cargar o cambiar datos.
O sea:

El frontend no está separado del backend.
PHP imprime HTML directamente.
JavaScript encima agrega dinamismo.
Ejemplos claros:

main.php carga selectores, calendario, formularios y scripts.
js/funcionesadministrar.js hace llamadas AJAX a:
filtrasolicitudes.php
apruebasolicitud.php
cancelasolicitud.php
apruebasolicitudMasiva.php
cancelasolicitudMasiva.php
También se usa select2 con livesearch2.php para buscar usuarios.

5. Cómo se conecta a la base de datos

La conexión está centralizada en php/conexion.php.

Ahí se ve que:

Se usa oci_connect(...).
Se crea un cursor Oracle con oci_new_cursor(...).
La aplicación consulta la base de dos maneras:

Con procedimientos almacenados Oracle:

VAC_PRC_comprobarusuario
VAC_PRC_comprobarusuario2
VAC_PRC_CONTARDIASRESTANTES
VAC_PRC_RETUSRPORNOMFILT
VAC_PRC_permisosusario
otros VAC_PRC_*
Con SQL directo:

Por ejemplo en php/filtrasolicitudes.php
Esto es importante: una parte fuerte de la lógica del negocio parece vivir en la base de datos, no solo en PHP.

No está claro en este repo:

La estructura completa de tablas.
El código fuente de los procedimientos almacenados.
Qué tanto depende el negocio de Oracle.
6. Riesgos técnicos visibles

Los riesgos más claros que sí se ven son:

Configuración sensible dentro del código.

La conexión a base de datos está escrita en conexion.php.
No expongo secretos, pero sí es un riesgo claro.
SQL concatenado manualmente.

En filtrasolicitudes.php se arma SQL con valores de entrada.
Eso puede ser peligroso si no está muy controlado.
Código muy mezclado.

HTML, PHP, JS y reglas de negocio están fuertemente acoplados.
Dependencia alta de Oracle y procedimientos almacenados.

Si no hay documentación del lado de base, mantener esto se complica.
Versionado manual dentro del proyecto.

Hay muchas copias de archivos con fechas.
Eso confunde cuál es la versión verdadera.
Librerías viejas o mezcladas.

Hay varias versiones de Bootstrap en el repo.
jQuery 3.1.0 ya es viejo.
Problemas de codificación de texto.

Se ven caracteres rotos como ContraseÃ±a, aÃ±os, aprobaciÃ³n.
Acciones sensibles vía GET.

Aprobar/cancelar se dispara con $.get(...).
No es lo más seguro ni lo más limpio para cambios de datos.
Poca documentación técnica.

El README.md no explica instalación, arquitectura ni flujos.
No se ven pruebas automáticas.

No encontré tests del sistema principal.
7. Lista priorizada de mejoras

Sin reescribir todavía el sistema, la prioridad sería:

Sacar credenciales y configuración sensible del código.
Revisar todas las consultas SQL armadas por concatenación.
Identificar archivos vigentes vs archivos históricos.
Documentar el flujo real del sistema.
Unificar codificación de caracteres a UTF-8.
Ordenar dependencias frontend y limpiar duplicados.
Cambiar operaciones que modifican datos para usar POST.
Mejorar manejo de errores y logs.
Documentar requisitos de instalación.
Separar poco a poco lógica, vista y acceso a datos.
Lo que no está claro, prefiero decirlo explícitamente:

No se puede confirmar desde este repo la versión exacta de PHP en producción.
No se puede confirmar el servidor web exacto.
No se ve el código de los procedimientos Oracle.
No se puede asegurar qué librerías “sobran” sin revisar uso completo archivo por archivo.