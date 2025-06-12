backend_examen/
├── 📂 api_docs/                    # Documentación Swag
│   ├── 📄 swagger.yaml            # Especificación Open
├── 📂 config/                      # Configuración de l
│   └── 📄 database.php            # Conexión PDO a MySQ
├── 📂 controllers/                 # Controladores MVC
│   ├── 📄 CamisetaController.php  # CRUD Camisetas + Pr
│   ├── 📄 ClienteController.php   # CRUD Clientes + Cat
│   └── 📄 TallaController.php     # CRUD Tallas
├── 📂 database/                    # Base de datos
│   └── 📄 todocamisetas.sql       # Estructura y datos 
├── 📂 models/                      # Modelos de datos
│   ├── 📄 Camiseta.php            # Modelo Camisetas + 
│   ├── 📄 Cliente.php             # Modelo Clientes + V
│   └── 📄 Talla.php               # Modelo Tallas + Rel
├── 📄 index.php                   # Enrutador principal
├── 📄 .htaccess                   # Configuración Apach
└── 📄 README.md                   # Documentación del p


| Método | Endpoint | Función |
|--------|----------|---------|
| `GET` | `/api/camisetas` | Listar todas las camisetas 
| `GET` | `/api/camisetas/{id}` | Obtener camiseta espec
| `GET` | `/api/camisetas/{id}/precio?cliente_id={id}` 
| `GET` | `/api/camisetas/cliente/{cliente_id}` | Camiseta 
| `POST` | `/api/camisetas` | Crear nueva camiseta 
| `PUT` | `/api/camisetas/{id}` | Actualizar camiseta 
| `DELETE` | `/api/camisetas/{id}` | Eliminar camiseta 