# Resumen inicial para la nueva versión de **vacaciones**

## 1. Propósito de este archivo
Este documento resume el contexto, las reglas de trabajo y el punto de partida para desarrollar una nueva versión del proyecto **vacaciones**, usando apoyo de IA, modernización del front-end y una futura migración de base de datos.

Su objetivo es servir como referencia rápida para:
- retomar el proyecto sin perder contexto,
- alinear decisiones técnicas,
- evitar mezclar entornos personales y empresariales,
- usar IA de forma ordenada y útil desde el inicio.

---

## 2. Contexto actual

### Entorno local definido
La estructura oficial de trabajo quedó así:

```text
C:\GitHub_Proyectos
├── GrupoRIO
└── Personal
```

### Significado de cada carpeta
- `C:\GitHub_Proyectos\GrupoRIO`  
  Solo para repositorios y trabajo empresarial.
- `C:\GitHub_Proyectos\Personal`  
  Solo para repositorios y trabajo personal.

### Regla de separación
No mezclar:
- repositorios empresariales dentro de `Personal`,
- repositorios personales dentro de `GrupoRIO`.

---

## 3. Repositorio del caso práctico
Repositorio empresarial base:

```text
https://github.com/Grupo-RIO/vacaciones
```

Se validó que el repositorio empresarial local:
- está en la carpeta correcta,
- apunta al remoto empresarial,
- usa identidad local empresarial para commits.

También se creó y subió una copia en GitHub personal para trabajo separado, verificando el cambio de autenticación cuando fue necesario.

---

## 4. Regla crítica sobre propiedad y permisos
Antes de copiar, duplicar, subir o reutilizar código empresarial fuera del entorno empresarial:

- no asumir nunca que existe permiso,
- no usar `fork` si se quiere una copia independiente,
- verificar siempre autorización explícita,
- revisar siempre el remoto final con:

```bash
git remote -v
```

Si la copia debe quedar independiente, no debe conservar como remoto final el repositorio empresarial.

---

## 5. Objetivo de la nueva versión
La nueva versión de **vacaciones** debe orientarse a tres líneas principales:

1. **Uso de IA como apoyo al desarrollo**
   - asistencia para análisis de código,
   - generación de propuestas técnicas,
   - ayuda con refactorización,
   - apoyo en documentación, pruebas y planificación.

2. **Modernización del front-end**
   - mejorar experiencia de usuario,
   - actualizar arquitectura visual,
   - limpiar componentes y flujo de pantallas,
   - preparar una base más mantenible.

3. **Cambio o migración de base de datos**
   - entender la base actual,
   - definir el nuevo motor o nueva estrategia,
   - planear migración de estructura y datos,
   - reducir riesgos antes de mover información real.

---

## 6. Enfoque recomendado para trabajar con IA
La IA debe usarse como apoyo, no como sustituto de criterio técnico.

### La IA puede ayudar a:
- resumir arquitectura actual,
- detectar deuda técnica,
- proponer plan de modernización,
- sugerir estructura de carpetas,
- revisar consultas, modelos y flujos,
- transformar código antiguo en una versión más clara,
- redactar tareas, issues, checklists y documentación.

### La IA no debe usarse para:
- subir secretos, tokens o credenciales,
- mover código empresarial a cuentas personales sin permiso,
- asumir reglas de negocio que no estén validadas,
- cambiar base de datos sin plan de migración y respaldo.

---

## 7. Prioridades técnicas sugeridas

### Fase 1: diagnóstico del sistema actual
Objetivo: entender antes de cambiar.

Revisar:
- stack actual,
- estructura del proyecto,
- dependencias,
- front-end actual,
- conexión a base de datos,
- modelos,
- rutas,
- pantallas clave,
- puntos frágiles.

Entregables sugeridos:
- mapa simple del proyecto,
- lista de módulos,
- lista de problemas detectados,
- lista de mejoras prioritarias.

### Fase 2: modernización del front-end
Objetivo: actualizar la experiencia sin romper negocio.

Revisar:
- framework actual,
- componentes reutilizables,
- estilos,
- formularios,
- validaciones,
- navegación,
- accesibilidad,
- consistencia visual.

Posibles metas:
- nueva estructura de componentes,
- layout más limpio,
- diseño más moderno,
- mejor separación entre lógica y presentación.

### Fase 3: análisis y migración de base de datos
Objetivo: cambiar con seguridad.

Revisar:
- base actual,
- tablas,
- relaciones,
- volumen de datos,
- dependencias del backend,
- consultas críticas,
- riesgos de compatibilidad.

Antes de migrar:
- documentar esquema actual,
- definir esquema nuevo,
- crear estrategia de migración,
- planear pruebas,
- respaldar información.

### Fase 4: refactorización y preparación de nueva versión
Objetivo: construir una base sostenible.

Revisar:
- nombres,
- organización,
- servicios,
- separación por capas,
- manejo de errores,
- configuración,
- pruebas.

---

## 8. Reglas de seguridad y buenas prácticas
Mantener estas reglas visibles en el proyecto:

- No subir secretos, contraseñas, tokens ni archivos sensibles.
- No mezclar cuenta personal y empresarial.
- Verificar siempre el remoto antes de hacer `push`.
- Verificar correo de commits antes de trabajar en un repo.
- Usar identidad empresarial en repos empresariales.
- Usar identidad personal en repos personales.
- Evitar cambios grandes sin diagnóstico previo.
- Documentar decisiones técnicas importantes.

---

## 9. Comandos de verificación rápida
Antes de trabajar en cualquier repositorio, revisar:

```bash
git remote -v
git config --get user.name
git config --get user.email
git status
```

### Interpretación rápida
- `git remote -v`  
  Dice a qué repositorio remoto está conectado.
- `git config --get user.name`  
  Dice con qué nombre se firmarán los commits.
- `git config --get user.email`  
  Dice con qué correo se firmarán los commits.
- `git status`  
  Dice si hay cambios pendientes.

---

## 10. Prompt base sugerido para continuar el proyecto con IA

```text
Actúa como tutor técnico y asesor práctico para continuar la nueva versión del proyecto "vacaciones".

Contexto:
- Soy principiante y necesito explicaciones claras, paso a paso.
- Trabajo en Windows 11 y uso VS Code.
- Debo mantener separación estricta entre entorno empresarial y personal.
- Mi estructura local es:
  - C:\GitHub_Proyectos\GrupoRIO
  - C:\GitHub_Proyectos\Personal
- El proyecto base es "vacaciones".
- Quiero usar IA como apoyo para análisis, modernización del front-end y futura migración de base de datos.

Forma de trabajo:
- Explica en español claro.
- No asumas conocimiento previo.
- Divide tareas en pasos pequeños y numerados.
- Antes de usar términos técnicos, explícalos de forma simple.
- Cuando haya riesgo de error, indica cómo verificar el resultado.
- Si detectas desalineación, resume qué ya está hecho, qué falta y cuál es el siguiente paso exacto.

Reglas importantes:
- No asumir permisos para mover código empresarial a mi cuenta personal.
- No usar fork si la meta es una copia independiente.
- Verificar siempre remotos con `git remote -v`.
- No recomendar subir secretos ni credenciales.
- Diferenciar claramente entre Git local, GitHub personal, GitHub empresarial y VS Code.

Objetivo:
Ayudarme a crear una nueva versión del proyecto vacaciones, empezando por:
1. diagnóstico del proyecto actual,
2. propuesta de modernización del front,
3. análisis del cambio de base de datos,
4. plan técnico por fases,
5. pasos concretos para ejecutar cada etapa.
```

---

## 11. Primer plan de arranque recomendado

### Paso 1
Levantar un inventario del proyecto actual:
- tecnologías usadas,
- estructura de carpetas,
- dependencias,
- base de datos actual,
- pantallas y módulos.

### Paso 2
Crear un documento de diagnóstico técnico:
- qué funciona,
- qué está viejo,
- qué conviene mantener,
- qué conviene reemplazar.

### Paso 3
Definir la visión de la nueva versión:
- qué se quiere conservar,
- qué se quiere modernizar,
- qué se quiere eliminar,
- qué se quiere migrar.

### Paso 4
Separar tareas en bloques:
- front-end,
- backend,
- base de datos,
- UX/UI,
- pruebas,
- despliegue,
- documentación.

### Paso 5
Usar IA para convertir cada bloque en:
- checklist,
- propuesta técnica,
- pasos concretos,
- riesgos,
- validaciones.

---

## 12. Checklist inicial de proyecto
Usar esta lista como punto de partida:

- [ ] Confirmar permiso y alcance de trabajo.
- [ ] Identificar stack actual del proyecto vacaciones.
- [ ] Revisar estructura de carpetas.
- [ ] Revisar dependencias y versiones.
- [ ] Revisar estado del front-end.
- [ ] Revisar conexión y modelo de base de datos.
- [ ] Documentar problemas actuales.
- [ ] Definir meta de modernización visual.
- [ ] Definir objetivo de cambio de base de datos.
- [ ] Preparar plan por fases.
- [ ] Definir riesgos y validaciones.
- [ ] Crear backlog inicial de tareas.

---

## 13. Resultado esperado
Al usar este archivo como base, el proyecto debería poder arrancar con mayor claridad, manteniendo:

- orden entre entornos,
- control sobre remotos e identidad Git,
- apoyo útil de IA,
- enfoque progresivo,
- menor riesgo en la modernización del sistema.

---

## 14. Siguiente uso recomendado de este archivo
Agregarlo al proyecto, por ejemplo en una ruta como:

```text
docs/RESUMEN_INICIAL_NUEVA_VERSION.md
```

o

```text
README_MODERNIZACION.md
```

Luego usarlo como documento de referencia para:
- sesiones de trabajo con IA,
- planeación técnica,
- onboarding,
- definición de fases de modernización.
