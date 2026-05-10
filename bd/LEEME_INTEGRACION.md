# Integración del Módulo de Ingresos v2 — Continental

## Qué se integró

Se incorporó el nuevo módulo de gestión de donaciones/patrocinios (`donaciones.zip`) al proyecto
`Continental`, manteniendo la coherencia con la arquitectura MVC existente, el sistema de rutas
(`index.php?controller=...&action=...`), la clase `Conectar` de conexión a BD y los estilos
visuales del proyecto.

## Archivos nuevos o modificados

| Archivo | Acción | Descripción |
|---------|--------|-------------|
| `Controllers/DonacionController.php` | **Actualizado** | Se agregaron las acciones `panel`, `registroIngreso`, `procesarPatrocinio` y `exportarCSV` a las acciones existentes |
| `models/donaciones.php` | **Actualizado** | Se agregaron `getKPIsPanel()`, `listarCompleto()`, `obtenerDonadores()`, `crearDonador()` y `registrarPatrocinio()` |
| `view/panel_donaciones.php` | **Nuevo** | Panel financiero con KPIs y tabla unificada de donaciones e ingresos |
| `view/registro_ingreso.php` | **Nuevo** | Formulario para registrar patrocinios (integra buscador de donadores, carga de evidencia y cálculo de comisión) |
| `view/layout/header.php` | **Actualizado** | Se añadieron "Panel Financiero" y "Registrar Ingreso" al menú ADMIN |
| `bd/migracion_donaciones_v2.sql` | **Nuevo** | Script SQL para crear las tablas `donadores` y `patrocinios` |
| `assets/uploads/patrocinios/` | **Nuevo** | Carpeta para archivos de evidencia de patrocinios |

## Cómo acceder

- **Panel financiero admin:** `index.php?controller=donacion&action=panel`
- **Registrar nuevo patrocinio:** `index.php?controller=donacion&action=registroIngreso`
- **Exportar CSV:** `index.php?controller=donacion&action=exportarCSV`

## Migración de base de datos

Ejecuta el siguiente script **después** del `bdsocial.sql` principal:

```sql
SOURCE bd/migracion_donaciones_v2.sql;
```

Las nuevas tablas son opcionales: si no existen, el sistema sigue funcionando con
la tabla `ingresos` existente (compatibilidad hacia atrás garantizada).

## Lógica de comisiones (consistente con el sistema original)

| Monto | Comisión |
|-------|----------|
| ≤ S/ 10,000 | 3 % |
| > S/ 10,000 | 5 % |
