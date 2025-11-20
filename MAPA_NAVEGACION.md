# 🗺️ MAPA DE NAVEGACIÓN - Sistema de Gestión de Ofertas de Prácticas

Este documento contiene los mapas de navegación visuales que muestran el flujo completo de pantallas de la aplicación.

## 📖 Cómo Visualizar los Diagramas

Los diagramas están escritos en sintaxis **Mermaid**, que se renderiza automáticamente en:
- GitHub (vista de archivo)
- GitLab
- Editores compatibles (VSCode con extensión Mermaid, IntelliJ IDEA)
- Visores Markdown online (StackEdit, Dillinger)

Para exportar como imágenes:
1. Usar la extensión de navegador "Mermaid Diagram Exporter"
2. Copiar el código en [Mermaid Live Editor](https://mermaid.live)
3. Usar herramientas CLI como `mmdc` (mermaid-cli)

---

## 🌐 1. DIAGRAMA GENERAL DE FLUJO

Este diagrama muestra el flujo principal de la aplicación desde el punto de entrada hasta la bifurcación por roles.

```mermaid
flowchart TD
    Start([👤 Usuario Accede]) --> Landing[🏠 Landing Page<br/>views/home/landing.php]
    Landing --> |Click Login| Login[🔑 Login<br/>views/auth/login.php]
    Landing --> |Click Registro| RegTipo{📝 Seleccionar Tipo}
    
    RegTipo --> |Empresa| RegEmpresa[🏢 Registro Empresa<br/>views/auth/register.php]
    RegTipo --> |Alumno| RegAlumno[🎓 Registro Alumno<br/>views/auth/register.php]
    
    RegEmpresa --> |Enviar| Pendiente[⏳ Pendiente Verificación]
    RegAlumno --> |Enviar| LoginRedir[Redirige a Login]
    
    Login --> |Credenciales| Autenticar{🔐 Autenticar Usuario}
    Autenticar --> |Admin| PanelAdmin[👨‍💼 Panel Admin]
    Autenticar --> |Empresa| PanelEmpresa[🏢 Panel Empresa]
    Autenticar --> |Alumno| PanelAlumno[🎓 Panel Alumno]
    Autenticar --> |Error| Login
    
    PanelAdmin --> |Logout| Landing
    PanelEmpresa --> |Logout| Landing
    PanelAlumno --> |Logout| Landing
    
    style Start fill:#e1f5ff
    style Landing fill:#fff3cd
    style Login fill:#d1ecf1
    style RegEmpresa fill:#d4edda
    style RegAlumno fill:#d4edda
    style PanelAdmin fill:#f8d7da
    style PanelEmpresa fill:#d1ecf1
    style PanelAlumno fill:#cfe2ff
```

---

## 👨‍💼 2. DIAGRAMA DE FLUJO - ROL ADMINISTRADOR

El administrador tiene acceso completo a la gestión de empresas, alumnos, ofertas y solicitudes.

```mermaid
flowchart TD
    Admin[👨‍💼 Panel Admin<br/>views/admin/panelAdmin.php] --> MenuAdmin{📋 Menú Principal}
    
    MenuAdmin --> |Panel Empresas| Empresas[🏢 Panel de Empresas<br/>index.php?menu=adminPanel&accion=panelEmpresas]
    MenuAdmin --> |Panel Alumnos| Alumnos[🎓 Panel de Alumnos<br/>index.php?menu=adminPanel&accion=panelAlumnos]
    MenuAdmin --> |Panel Solicitudes| Solicitudes[📄 Panel de Solicitudes<br/>index.php?menu=adminPanel&accion=panelSolicitudes]
    MenuAdmin --> |Panel Ofertas| Ofertas[💼 Panel de Ofertas<br/>index.php?menu=adminPanel&accion=panelOfertas]
    
    Empresas --> AccionEmp{⚙️ Acciones}
    AccionEmp --> |Ver| VerEmpresa[👁️ Ficha Empresa<br/>views/admin/fichaEmpresa.php]
    AccionEmp --> |Agregar| AgregarEmp[➕ Agregar Empresa<br/>views/admin/agregarEmpresa.php]
    AccionEmp --> |Editar| EditarEmp[✏️ Editar Empresa<br/>views/admin/editarEmpresa.php]
    AccionEmp --> |Eliminar| EliminarEmp[🗑️ Eliminar Empresa]
    AccionEmp --> |Verificar| VerificarEmp[✅ Verificar Empresa]
    AccionEmp --> |Generar PDF| PDFEmp[📄 Generar PDF<br/>views/admin/fichaEmpresaPdf.php]
    
    AgregarEmp --> |Guardar| ValidarEmp{✓ Validar}
    EditarEmp --> |Guardar| ValidarEmp
    ValidarEmp --> |OK| Empresas
    ValidarEmp --> |Error| AgregarEmp
    ValidarEmp --> |Error| EditarEmp
    
    VerEmpresa --> Empresas
    EliminarEmp --> Empresas
    VerificarEmp --> |Envía Email| Empresas
    PDFEmp --> Empresas
    
    Alumnos --> |Gestionar| ListaAlumnos[📋 Lista de Alumnos]
    Solicitudes --> |Ver| ListaSolicitudes[📋 Lista de Solicitudes]
    Ofertas --> |Ver| ListaOfertas[📋 Lista de Ofertas]
    
    classDef adminStyle fill:#f8d7da,stroke:#dc3545,stroke-width:2px
    class Admin,MenuAdmin,Empresas,Alumnos,Solicitudes,Ofertas adminStyle
```

---

## 🏢 3. DIAGRAMA DE FLUJO - ROL EMPRESA

Las empresas pueden gestionar sus ofertas activas y pasadas, y ver las solicitudes recibidas.

```mermaid
flowchart TD
    Empresa[🏢 Panel Empresa] --> MenuEmpresa{📋 Menú Principal}
    
    MenuEmpresa --> |Ofertas| OfertasMenu{💼 Gestión de Ofertas}
    MenuEmpresa --> |Solicitudes| SolicitudesEmp[📨 Ver Solicitudes Recibidas<br/>views/solicitudes/solicitudesEmpresa.php]
    
    OfertasMenu --> |Activas| OfertasActivas[✅ Ofertas Activas<br/>views/ofertas/ofertasEmpresa.php<br/>index.php?menu=ofertas&accion=activas]
    OfertasMenu --> |Pasadas| OfertasPasadas[📅 Ofertas Pasadas<br/>views/ofertas/ofertasEmpresa.php<br/>index.php?menu=ofertas&accion=pasadas]
    
    OfertasActivas --> AccionesActivas{⚙️ Acciones}
    AccionesActivas --> |Crear| CrearOferta[➕ Crear Oferta<br/>views/ofertas/agregarOferta.php<br/>index.php?menu=ofertas&accion=crear]
    AccionesActivas --> |Editar| EditarOferta[✏️ Editar Oferta<br/>views/ofertas/editarOferta.php<br/>index.php?menu=ofertas&accion=editar]
    AccionesActivas --> |Eliminar| EliminarOferta[🗑️ Eliminar Oferta]
    AccionesActivas --> |Eliminar Todas| EliminarTodasActivas[🗑️ Eliminar Todas las Activas]
    
    CrearOferta --> |Guardar| ValidarOferta{✓ Validar}
    EditarOferta --> |Guardar| ValidarOferta
    ValidarOferta --> |OK| OfertasActivas
    ValidarOferta --> |Error| CrearOferta
    ValidarOferta --> |Error| EditarOferta
    
    EliminarOferta --> OfertasActivas
    EliminarTodasActivas --> OfertasActivas
    
    OfertasPasadas --> AccionesPasadas{⚙️ Acciones}
    AccionesPasadas --> |Eliminar| EliminarPasada[🗑️ Eliminar Oferta Pasada]
    AccionesPasadas --> |Eliminar Todas| EliminarTodasPasadas[🗑️ Eliminar Todas las Pasadas]
    
    EliminarPasada --> OfertasPasadas
    EliminarTodasPasadas --> OfertasPasadas
    
    SolicitudesEmp --> |Ver Detalles| DetalleSolicitud[📋 Detalle de Solicitud]
    DetalleSolicitud --> SolicitudesEmp
    
    classDef empresaStyle fill:#d1ecf1,stroke:#0c5460,stroke-width:2px
    class Empresa,MenuEmpresa,OfertasActivas,OfertasPasadas,SolicitudesEmp empresaStyle
```

---

## 🎓 4. DIAGRAMA DE FLUJO - ROL ALUMNO

Los alumnos pueden explorar ofertas, aplicar a ellas y gestionar sus solicitudes.

```mermaid
flowchart TD
    Alumno[🎓 Panel Alumno] --> MenuAlumno{📋 Menú Principal}
    
    MenuAlumno --> |Explorar Ofertas| ExplorarOfertas[🔍 Explorar Ofertas<br/>views/ofertas/ofertasAlumno.php<br/>index.php?menu=ofertas]
    MenuAlumno --> |Mis Solicitudes| MisSolicitudes[📨 Mis Solicitudes<br/>views/solicitudes/solicitudesAlumno.php<br/>index.php?menu=solicitudes]
    
    ExplorarOfertas --> Filtros{🔧 Aplicar Filtros}
    Filtros --> |Por Ciclo| FiltroCiclo[📚 Filtrar por Ciclo<br/>index.php?menu=ofertas&ciclo=X]
    Filtros --> |Por Fecha| FiltroFecha[📅 Ordenar por Fecha<br/>index.php?menu=ofertas&ordenFecha=asc/desc]
    Filtros --> |Sin Filtro| ExplorarOfertas
    
    FiltroCiclo --> ExplorarOfertas
    FiltroFecha --> ExplorarOfertas
    
    ExplorarOfertas --> AccionesOferta{⚙️ Acciones}
    AccionesOferta --> |Ver Detalles| VerDetalle[👁️ Ver Detalle de Oferta<br/>views/ofertas/verOferta.php<br/>index.php?menu=ofertas&accion=verDetalles&oferta_id=X]
    AccionesOferta --> |Aplicar| Aplicar[✅ Aplicar a Oferta]
    
    VerDetalle --> |Aplicar| Aplicar
    VerDetalle --> |Volver| ExplorarOfertas
    
    Aplicar --> |Confirmar| ConfirmarApp{✓ Crear Solicitud}
    ConfirmarApp --> |OK| ExplorarOfertas
    
    MisSolicitudes --> AccionesSol{⚙️ Acciones}
    AccionesSol --> |Ver Estado| EstadoSol[📊 Ver Estado de Solicitud]
    AccionesSol --> |Retirar| RetirarSol[❌ Retirar Solicitud]
    
    EstadoSol --> MisSolicitudes
    RetirarSol --> |Confirmar| MisSolicitudes
    
    classDef alumnoStyle fill:#cfe2ff,stroke:#084298,stroke-width:2px
    class Alumno,MenuAlumno,ExplorarOfertas,MisSolicitudes alumnoStyle
```

---

## 📊 5. TABLA RESUMEN DE RUTAS, VISTAS Y CONTROLLERS

| Ruta | Parámetros | Vista | Controller | Rol | Descripción |
|------|-----------|-------|------------|-----|-------------|
| `/` | - | `views/home/landing.php` | `HomeController::landingPage()` | Público | Landing page principal |
| `/?menu=login` | - | `views/auth/login.php` | `AuthController::login()` | Público | Página de inicio de sesión |
| `/?menu=register` | `tipo=empresa/alumno` | `views/auth/register.php` | `AuthController::register()` | Público | Página de registro |
| `/?menu=logout` | - | - | `AuthController::logout()` | Todos | Cerrar sesión |
| `/?menu=adminPanel` | - | `views/admin/panelAdmin.php` | `AdminController::adminPanel()` | Admin | Panel principal del administrador |
| `/?menu=adminPanel&accion=panelEmpresas` | - | `views/admin/panelAdmin.php` | `AdminController::adminPanel()` | Admin | Panel de gestión de empresas |
| `/?menu=adminPanel&accion=panelEmpresas&opcion=ver` | `empresa_id` | `views/admin/fichaEmpresa.php` | `AdminController::adminPanel()` | Admin | Ver ficha de empresa |
| `/?menu=adminPanel&accion=panelEmpresas&opcion=agregar` | - | `views/admin/agregarEmpresa.php` | `AdminController::adminPanel()` | Admin | Formulario agregar empresa |
| `/?menu=adminPanel&accion=panelEmpresas&opcion=editar` | `empresa_id` | `views/admin/editarEmpresa.php` | `AdminController::adminPanel()` | Admin | Formulario editar empresa |
| `/?menu=adminPanel&accion=panelAlumnos` | - | `views/admin/panelAdmin.php` | `AdminController::adminPanel()` | Admin | Panel de gestión de alumnos |
| `/?menu=adminPanel&accion=panelSolicitudes` | - | `views/admin/panelAdmin.php` | `AdminController::adminPanel()` | Admin | Panel de gestión de solicitudes |
| `/?menu=adminPanel&accion=panelOfertas` | - | `views/admin/panelAdmin.php` | `AdminController::adminPanel()` | Admin | Panel de gestión de ofertas |
| `/?menu=ofertas` | - | `views/ofertas/ofertasAlumno.php` | `OfertaController::ofertas()` | Alumno | Explorar ofertas disponibles |
| `/?menu=ofertas` | `ciclo`, `ordenFecha` | `views/ofertas/ofertasAlumno.php` | `OfertaController::ofertas()` | Alumno | Ofertas con filtros |
| `/?menu=ofertas&accion=verDetalles` | `oferta_id` | `views/ofertas/verOferta.php` | `OfertaController::ofertas()` | Alumno | Ver detalles de una oferta |
| `/?menu=ofertas&accion=activas` | - | `views/ofertas/ofertasEmpresa.php` | `OfertaController::ofertas()` | Empresa | Ofertas activas de la empresa |
| `/?menu=ofertas&accion=pasadas` | - | `views/ofertas/ofertasEmpresa.php` | `OfertaController::ofertas()` | Empresa | Ofertas pasadas de la empresa |
| `/?menu=ofertas&accion=crear` | - | `views/ofertas/agregarOferta.php` | `OfertaController::ofertas()` | Empresa | Formulario crear oferta |
| `/?menu=ofertas&accion=editar` | `oferta_id` | `views/ofertas/editarOferta.php` | `OfertaController::ofertas()` | Empresa | Formulario editar oferta |
| `/?menu=solicitudes` | - | `views/solicitudes/solicitudesAlumno.php` | `SolicitudController::solicitudes()` | Alumno | Mis solicitudes enviadas |
| `/?menu=solicitudes` | - | `views/solicitudes/solicitudesEmpresa.php` | `SolicitudController::solicitudes()` | Empresa | Solicitudes recibidas |

---

## 🔄 6. DIAGRAMA DE OPERACIONES POST (Acciones de Formularios)

Este diagrama muestra las operaciones que se realizan mediante formularios POST.

```mermaid
flowchart LR
    subgraph Admin["👨‍💼 Operaciones Admin"]
        AdminPost1[POST: Agregar Empresa] --> ValidateAdmin1{Validar}
        AdminPost2[POST: Editar Empresa] --> ValidateAdmin1
        AdminPost3[POST: Eliminar Empresa] --> DirectAdmin
        AdminPost4[POST: Verificar Empresa] --> EmailAdmin[Enviar Email]
        AdminPost5[POST: Generar PDF] --> PDFAdmin[Generar PDF]
        
        ValidateAdmin1 --> |OK| RedirAdmin[Redirigir]
        ValidateAdmin1 --> |Error| ShowFormAdmin[Mostrar Formulario]
        EmailAdmin --> RedirAdmin
        DirectAdmin --> RedirAdmin
    end
    
    subgraph Empresa["🏢 Operaciones Empresa"]
        EmpPost1[POST: Crear Oferta] --> ValidateEmp{Validar}
        EmpPost2[POST: Editar Oferta] --> ValidateEmp
        EmpPost3[POST: Eliminar Oferta] --> DirectEmp
        EmpPost4[POST: Eliminar Todas Activas] --> DirectEmp
        EmpPost5[POST: Eliminar Todas Pasadas] --> DirectEmp
        
        ValidateEmp --> |OK| RedirEmp[Redirigir]
        ValidateEmp --> |Error| ShowFormEmp[Mostrar Formulario]
        DirectEmp --> RedirEmp
    end
    
    subgraph Alumno["🎓 Operaciones Alumno"]
        AlumPost1[POST: Aplicar Oferta] --> CreateSol[Crear Solicitud]
        AlumPost2[POST: Retirar Solicitud] --> DeleteSol[Eliminar Solicitud]
        
        CreateSol --> RedirAlum[Redirigir con Filtros]
        DeleteSol --> RedirAlum
    end
    
    classDef adminStyle fill:#f8d7da,stroke:#dc3545,stroke-width:2px
    classDef empresaStyle fill:#d1ecf1,stroke:#0c5460,stroke-width:2px
    classDef alumnoStyle fill:#cfe2ff,stroke:#084298,stroke-width:2px
    
    class AdminPost1,AdminPost2,AdminPost3,AdminPost4,AdminPost5,ValidateAdmin1,DirectAdmin,EmailAdmin,PDFAdmin,RedirAdmin,ShowFormAdmin adminStyle
    class EmpPost1,EmpPost2,EmpPost3,EmpPost4,EmpPost5,ValidateEmp,DirectEmp,RedirEmp,ShowFormEmp empresaStyle
    class AlumPost1,AlumPost2,CreateSol,DeleteSol,RedirAlum alumnoStyle
```

---

## 🎨 7. LEYENDA DE COLORES

Los diagramas utilizan los siguientes códigos de color para diferenciar los roles:

| Color | Rol | Descripción |
|-------|-----|-------------|
| 🔴 Rojo claro (`#f8d7da`) | **Administrador** | Pantallas y operaciones exclusivas del administrador |
| 🔵 Azul claro (`#d1ecf1`) | **Empresa** | Pantallas y operaciones exclusivas de empresas |
| 🟦 Azul oscuro (`#cfe2ff`) | **Alumno** | Pantallas y operaciones exclusivas de alumnos |
| 🟡 Amarillo (`#fff3cd`) | **Público** | Pantallas accesibles sin autenticación |
| 🟢 Verde (`#d4edda`) | **Registro** | Pantallas de registro de nuevos usuarios |

---

## 📝 8. NOTAS TÉCNICAS

### Estructura de Controllers

- **Router.php**: Controlador principal que enruta las peticiones según el parámetro `menu`
- **AuthController.php**: Maneja login, registro y logout
- **HomeController.php**: Muestra la landing page
- **AdminController.php**: Gestiona todas las operaciones del panel de administrador
- **OfertaController.php**: Maneja ofertas tanto para empresas como para alumnos
- **SolicitudController.php**: Gestiona las solicitudes tanto para empresas como para alumnos

### Flujo de Autenticación

1. Usuario accede a `/?menu=login`
2. Envía credenciales por POST
3. `AuthController::login()` valida las credenciales
4. Según el rol (1=Admin, 2=Alumno, 3=Empresa), se establece la sesión
5. Se verifica que el usuario esté activo/verificado
6. Se genera un token de sesión
7. Se redirige a la página principal según el rol

### Sistema de Permisos

- **Admin (rol=1)**: Acceso completo a todos los paneles de administración
- **Alumno (rol=2)**: Acceso a exploración de ofertas y gestión de solicitudes
- **Empresa (rol=3)**: Acceso a gestión de ofertas y visualización de solicitudes
- Las empresas deben estar **verificadas** (`verificada=1`) para acceder
- Los alumnos deben estar **activos** (`activo=1`) para acceder

### Validaciones

- Todas las operaciones de creación y edición pasan por validadores (`Validator.php`)
- Si hay errores de validación, se vuelve a mostrar el formulario con los errores
- Si la validación es correcta, se procesa la operación y se redirige

---

## 🔗 9. REFERENCIAS

### Archivos Principales

- **Routing**: `/controllers/Router.php`
- **Controllers**: `/controllers/*.php`
- **Views**: `/views/**/*.php`
- **Services**: `/services/*.php`
- **Models**: `/models/*.php`
- **Repositories**: `/repositories/*.php`

### APIs Auxiliares

La aplicación también incluye APIs REST en `/public/assets/api/` para operaciones asíncronas:
- `api_oferta.php`: Operaciones de ofertas
- `api_solicitud.php`: Operaciones de solicitudes
- `api_empresa.php`: Operaciones de empresas
- `api_alumno.php`: Operaciones de alumnos
- `api_familia.php`: Obtener familias profesionales
- `api_provincia.php`: Obtener provincias
- `api_imagen.php`: Gestión de imágenes

---

## 📚 10. GLOSARIO

- **Landing Page**: Página de inicio pública de la aplicación
- **CRUD**: Create, Read, Update, Delete (operaciones básicas de base de datos)
- **Oferta**: Oferta de prácticas publicada por una empresa
- **Solicitud**: Aplicación de un alumno a una oferta de prácticas
- **Ciclo**: Ciclo formativo (FP) al que pertenece un alumno
- **Familia Profesional**: Área o sector de formación profesional
- **Verificación**: Proceso de aprobación de una empresa por el administrador
- **Token**: Identificador único de sesión para seguridad

---

**Última actualización**: 2025-11-20

**Versión del documento**: 1.0
