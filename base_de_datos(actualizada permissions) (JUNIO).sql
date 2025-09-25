
-- Tabla de usuarios
CREATE TABLE `users` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL,
    `email` VARCHAR(150) UNIQUE NOT NULL,
    `phone` VARCHAR(20),
    `address` VARCHAR(255),
    `email_verified_at` timestamp NULL DEFAULT NULL,
    `password` VARCHAR(255) NOT NULL,
    `remember_token` varchar(100) DEFAULT NULL,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- ROLES UTILIZANDO SPATIE LARAVEL-PERMISSIONS
CREATE TABLE `model_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `model_has_roles` (
  `role_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `model_type` varchar(255) NOT NULL,
  `model_id` bigint(20) UNSIGNED NOT NULL,
  FOREIGN KEY (`model_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `permissions` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL -- Soft delete
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `roles` (
  `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `name` varchar(255) NOT NULL,
  `guard_name` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deleted_at` TIMESTAMP NULL DEFAULT NULL -- Soft delete
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE `role_has_permissions` (
  `permission_id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `role_id` bigint(20) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE `profiles` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `bio` TEXT,
    `document_number` VARCHAR(50),
    `photo` VARCHAR(255),
    `birthdate` DATE,
    -- Nuevos campos para capacidades, actitudes y disponibilidad
    `skills` TEXT,
    `interests` TEXT,
    `availability_days` VARCHAR(100),
    `availability_hours` VARCHAR(100),
    `location` VARCHAR(100),
    `transport_available` BOOLEAN DEFAULT FALSE,
    `experience_level` ENUM('básico', 'intermedio', 'avanzado') DEFAULT 'básico',
    `physical_condition` ENUM('buena', 'moderada', 'limitada') DEFAULT 'buena',
    `preferred_tasks` TEXT,
    `languages_spoken` VARCHAR(255),

    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de campañas organizadas por la fundación
CREATE TABLE `campaigns` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `creator_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`creator_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    `name` VARCHAR(150) NOT NULL, -- Nombre de la campaña
    `description` TEXT, -- Descripción de la campaña
    `start_date` DATE, -- Fecha de inicio
    `end_date` DATE, -- Fecha de fin
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, 
    `show_cam` BOOLEAN NULL DEFAULT FALSE, -- Indica si la campaña es visible públicamente
    `foto` TEXT NULL, -- URL de la foto de la campaña
    `observations` TEXT NULL, -- Observaciones adicionales sobre la campaña
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de eventos específicos dentro de una campaña
CREATE TABLE `events` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de campañas
    `creator_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`creator_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    `name` VARCHAR(150) NOT NULL, -- Nombre del evento
    `description` TEXT, -- Descripción del evento
    `event_date` DATE, -- Fecha del evento
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para eventos
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de participantes en eventos
CREATE TABLE `event_participants` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de eventos
    `event_locations_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de ubicaciones
    FOREIGN KEY (`event_locations_id`) REFERENCES `event_locations`(`id`) ON DELETE CASCADE,
    `user_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de usuarios (participantes)
    `registration_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de registro
    `observations` TEXT,
    `status` ENUM('registrado', 'asistido', 'ausente') DEFAULT 'registrado', -- Estado del participante
    
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para participantes
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de ubicaciones de los eventos (donde se realiza cada evento)
CREATE TABLE `event_locations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `event_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de eventos
    `location_name` VARCHAR(150) NOT NULL, -- Nombre del lugar (ej: auditorio, parque, etc.)
    `address` VARCHAR(255), -- Dirección del lugar
    `latitud` DECIMAL(10,8), -- Latitud con hasta 8 decimales
    `longitud` DECIMAL(11,8), -- Longitud con hasta 8 decimales
    `start_hour` TIME, -- Hora de inicio
    `end_hour` TIME, -- Hora de fin
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para ubicaciones
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Asignaciones internas (por ejemplo: asignar un encargado o administrador a un área)
CREATE TABLE `staff_assignments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `area` VARCHAR(100) NOT NULL,
    `Campaign_id`BIGINT UNSIGNED,
    `event_id`BIGINT UNSIGNED,
    `assigned_by_id` BIGINT UNSIGNED,
    `start_date` DATE,
    `end_date` DATE,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`Campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`event_id`) REFERENCES `events`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`assigned_by_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Tabla de donantes externos (personas o entidades que donan)
CREATE TABLE `external_donor`(
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `names` VARCHAR(100) NOT NULL,
    `paternal_surname` VARCHAR(100),
    `maternal_surname` VARCHAR(100),
    `email` VARCHAR(150) UNIQUE NOT NULL,
    `phone` VARCHAR(20),
    `address` VARCHAR(255)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Estados del proceso de donación
CREATE TABLE `donation_statuses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE,
    `description` TEXT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla principal de donaciones
CREATE TABLE `donations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `referencia` VARCHAR(100) NOT NULL UNIQUE, -- Referencia única para la donación
    `name_donation` VARCHAR(150) NOT NULL, -- Nombre de la donación
    `external_donor_id` BIGINT UNSIGNED,
    `user_id` BIGINT UNSIGNED,
    `received_by_id`BIGINT UNSIGNED NOT NULL,
    `status_id` BIGINT UNSIGNED NOT NULL,
    `during_campaign_id` BIGINT UNSIGNED,
    `donation_date` DATE NOT NULL,
    `notes` TEXT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`external_donor_id`) REFERENCES `external_donor`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`received_by_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`status_id`) REFERENCES `donation_statuses`(`id`),
    FOREIGN KEY (`during_campaign_id`) REFERENCES `campaigns`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Detalles por ítem de cada donación
CREATE TABLE `donation_items` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `donation_id` BIGINT UNSIGNED NOT NULL,
    `donation_type_id` BIGINT UNSIGNED NOT NULL,
    `item_name` VARCHAR(150) NOT NULL,
    `quantity` INT UNSIGNED DEFAULT 1,
    `unit` VARCHAR(50), -- Ej: kg, units, liters, etc.
    `description` TEXT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`donation_id`) REFERENCES `donations`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`donation_type_id`) REFERENCES `donation_types`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `donation_item_photos` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `donation_item_id` BIGINT UNSIGNED NOT NULL,
    `photo_url` VARCHAR(255) NOT NULL,
    `caption` VARCHAR(255),
    `created_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`donation_item_id`) REFERENCES `donation_items`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tipos de donación (monetaria, especie, servicios, etc.)
CREATE TABLE `donation_types` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(100) NOT NULL UNIQUE,
    `description` TEXT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




-- Tabla de solicitudes de donación (hechas por beneficiarios)
CREATE TABLE `donation_requests` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `referencia` VARCHAR(100) NOT NULL UNIQUE, -- Referencia única para la solicitud
    `applicant_user__id` BIGINT UNSIGNED NOT NULL,
    `user_in_charge_id` BIGINT UNSIGNED,
    `donation_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de donaciones
    `request_date` TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    `notes` TEXT,
    `state` ENUM('pendiente', 'en revision', 'aceptado', 'rechazado', 'finalizado') DEFAULT 'pendiente',
    `observacions` TEXT, -- Observaciones del encargado
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para solicitudes
    FOREIGN KEY (`applicant_user__id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_in_charge_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`donation_id`) REFERENCES `donations`(`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla extendida de descripciones para solicitudes de donación
CREATE TABLE donation_request_descriptions (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `donation_request_id` BIGINT UNSIGNED NOT NULL, -- Relación con la solicitud de donación
    `recipient_name` VARCHAR(255) NOT NULL, -- Nombre del destinatario
    `recipient_address` TEXT, -- Dirección del destinatario
    `recipient_contact` VARCHAR(100), -- Teléfono u otro medio de contacto
    `recipient_type` ENUM('individual', 'organization', 'community', 'other') DEFAULT 'individual', -- Tipo de beneficiario
    `reason` TEXT, -- Justificación o contexto de la solicitud
    `latitude` DECIMAL(10, 8), -- Coordenadas geográficas para ubicación exacta
    `longitude` DECIMAL(11, 8),
    `extra_instructions` TEXT, -- Indicaciones adicionales para el voluntario
    `supporting_documents` TEXT, -- URLs o referencias a documentos (como evidencia o formularios)
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,

    FOREIGN KEY (donation_request_id) REFERENCES donation_requests(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Tabla de tareas (actividades disponibles para voluntarios)
CREATE TABLE `tasks` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `creator_id` BIGINT UNSIGNED NOT NULL,
    FOREIGN KEY (`creator_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    `name` VARCHAR(150) NOT NULL, -- Nombre de la tarea
    `description` TEXT, -- Descripción de la tarea
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL -- Soft delete para tareas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de asignaciones de tareas a voluntarios
CREATE TABLE `task_assignments` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `task_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de tareas
    `donation_request_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de solicitudes de donación
    `user_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de usuarios (voluntarios)
    `supervisor` BIGINT UNSIGNED NOT NULL,
    `status` ENUM('solicitada','denegada','asignada', 'en_progreso', 'completada', 'cancelada') DEFAULT 'asignada',
    `assigned_at` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha de asignación
    `notes` TEXT, -- Notas adicionales
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para asignaciones de tareas
    FOREIGN KEY (`task_id`) REFERENCES `tasks`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`supervisor`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`donation_request_id`) REFERENCES `donation_requests`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de verificación de voluntarios (documentos y validación)
CREATE TABLE `volunteer_verifications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de usuarios (voluntarios)
    `user_resp_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de usuarios (voluntarios)
    `document_type` VARCHAR(100), -- Tipo de documento (ej: carta de recomendación, comprobante, etc.)
    `document_url` VARCHAR(255), -- URL o ruta del documento
    `name_document` TEXT,
    `status` ENUM('pendiente', 'aprobado', 'rechazado', 'reconsideracion') DEFAULT 'pendiente', -- Estado de la verificación
    `coment` VARCHAR(255),
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para verificaciones
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`user_resp_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de estados de las tareas (para definir los diferentes estados de una tarea)
CREATE TABLE `task_asig_statuses` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(50) NOT NULL UNIQUE, -- Ejemplo: pending, in_progress, completed
    `description` TEXT,
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




-- Tabla de finanzas por campaña (seguimiento financiero de cada campaña)
CREATE TABLE `campaign_finances` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `campaign_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de campañas
    `manager_id` BIGINT UNSIGNED NOT NULL,
    `financial_account_id` BIGINT UNSIGNED NOT NULL, -- Cuenta financiera asociada
    FOREIGN KEY (`financial_account_id`) REFERENCES `financial_accounts`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`manager_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    `income` DECIMAL(15,2) DEFAULT 0.00, -- Ingresos generados por la campaña
    `expenses` DECIMAL(15,2) DEFAULT 0.00, -- Gastos asociados a la campaña
    `net_balance` DECIMAL(15,2) DEFAULT 0.00, -- Balance neto (ingresos - gastos)
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para finanzas
    FOREIGN KEY (`campaign_id`) REFERENCES `campaigns`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


-- Cuentas financieras de la fundación (banco, caja, fondos especiales, etc.)
CREATE TABLE financial_accounts (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- ID de la cuenta financiera
    name VARCHAR(100) NOT NULL,                    -- Nombre de la cuenta (ej: Banco BNB, Caja chica)
    type ENUM('dinero', 'banco', 'fondo campaña', 'otro') NOT NULL, -- Tipo de cuenta
    balance DECIMAL(12,2) DEFAULT 0,               -- Saldo actual
    description TEXT,                              -- Descripción o propósito de la cuenta
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL
);

-- Registro detallado de todas las transacciones (ingresos y egresos)
CREATE TABLE transactions (
    id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- ID de la transacción
    account_id BIGINT UNSIGNED NOT NULL,           -- Cuenta utilizada
    type ENUM('ingreso', 'gasto') NOT NULL,        -- Tipo de transacción (ingreso o gasto)
    amount DECIMAL(12,2) NOT NULL,                 -- Monto de la transacción
    description TEXT,                              -- Descripción del movimiento
    related_campaign_id BIGINT UNSIGNED,           -- Si está vinculada a una campaña específica
    related_event_id BIGINT UNSIGNED,              -- Si está vinculada a un evento específico
    related_event_location_id BIGINT UNSIGNED,     -- Si está vinculada a una ubicación de evento específica
    transaction_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP,                -- Fecha real de la transacción
    transaction_time TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    created_by BIGINT UNSIGNED,                    -- Usuario que registró el movimiento
    created_at TIMESTAMP NULL DEFAULT NULL,
    updated_at TIMESTAMP NULL DEFAULT NULL,
    deleted_at TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (account_id) REFERENCES financial_accounts(id),
    FOREIGN KEY (related_event_id) REFERENCES events(id),
    FOREIGN KEY (related_event_location_id) REFERENCES event_locations(id),
    FOREIGN KEY (related_campaign_id) REFERENCES campaigns(id),
    FOREIGN KEY (created_by) REFERENCES users(id)
);



-- Donaciones monetarias realizadas por usuarios
CREATE TABLE donations_cash (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY, -- ID de la donación
    `donor_id` BIGINT UNSIGNED,                      -- Usuario donante
    `external_donor_id` BIGINT UNSIGNED,
    `amount` DECIMAL(12,2) NOT NULL,                 -- Monto donado
    `method` VARCHAR(50),                            -- Método de pago (efectivo, transferencia, etc.)
    `donation_date` DATE NOT NULL,                   -- Fecha de la donación
    `campaign_id` BIGINT UNSIGNED,                   -- Campaña relacionada si aplica
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    
    FOREIGN KEY (`external_donor_id`) REFERENCES `external_donor`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (donor_id) REFERENCES users(id),
    FOREIGN KEY (campaign_id) REFERENCES campaigns(id)
);


-- Tabla de acciones recomendadas por el agente inteligente
CREATE TABLE `agent_actions` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL, -- Nombre de la acción recomendada
    `description` TEXT, -- Descripción de la acción
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL -- Soft delete para acciones recomendadas
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de criterios de evaluación utilizados por el agente para tomar decisiones
CREATE TABLE `evaluation_criteria` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL, -- Nombre del criterio
    `description` TEXT, -- Descripción del criterio
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL -- Soft delete para criterios de evaluación
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de recomendaciones automatizadas generadas por el sistema
CREATE TABLE `recommendations` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de usuarios (quién recibe la recomendación)
    `action_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de acciones recomendadas
    `description` TEXT, -- Descripción de la recomendación
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para recomendaciones
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE,
    FOREIGN KEY (`action_id`) REFERENCES `agent_actions`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de log de acciones importantes del sistema (registro de acciones)
CREATE TABLE `system_logs` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `action` VARCHAR(255) NOT NULL, -- Acción realizada
    `user_id` BIGINT UNSIGNED, -- Relación con la tabla de usuarios (quién realizó la acción)
    `timestamp` TIMESTAMP DEFAULT CURRENT_TIMESTAMP, -- Fecha y hora de la acción
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para logs
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de indicadores clave de rendimiento (KPI)
CREATE TABLE `kpi_indicators` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `name` VARCHAR(150) NOT NULL, -- Nombre del KPI
    `description` TEXT, -- Descripción del KPI
    `value` DECIMAL(15,2) DEFAULT 0.00, -- Valor del KPI
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL -- Soft delete para indicadores
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de notificaciones
CREATE TABLE `notifications` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL, -- Relación con la tabla de usuarios (quién recibe la notificación)
    `message` TEXT NOT NULL, -- Mensaje de la notificación
    `status` ENUM('read', 'unread') DEFAULT 'unread', -- Estado de la notificación (leída/no leída)
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL, -- Soft delete para notificaciones
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Tabla de archivos adjuntos (documentos adicionales, constancias, autorizaciones, etc.)
CREATE TABLE `attached_files` (
    `id` BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    `user_id` BIGINT UNSIGNED NOT NULL,
    `file_path` VARCHAR(255) NOT NULL,
    `file_name` VARCHAR(255) NOT NULL,
    `file_size` BIGINT UNSIGNED, 
    `file_type` VARCHAR(50), 
    `created_at` TIMESTAMP NULL DEFAULT NULL,
    `updated_at` TIMESTAMP NULL DEFAULT NULL,
    `deleted_at` TIMESTAMP NULL DEFAULT NULL,
    FOREIGN KEY (`user_id`) REFERENCES `users`(`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;


CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `cache_locks`

CREATE TABLE `cache_locks` (
  `key` varchar(255) NOT NULL,
  `owner` varchar(255) NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `failed_jobs`

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `jobs`

CREATE TABLE `jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `queue` varchar(255) NOT NULL,
  `payload` longtext NOT NULL,
  `attempts` tinyint(3) UNSIGNED NOT NULL,
  `reserved_at` int(10) UNSIGNED DEFAULT NULL,
  `available_at` int(10) UNSIGNED NOT NULL,
  `created_at` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `job_batches`

CREATE TABLE `job_batches` (
  `id` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `total_jobs` int(11) NOT NULL,
  `pending_jobs` int(11) NOT NULL,
  `failed_jobs` int(11) NOT NULL,
  `failed_job_ids` longtext NOT NULL,
  `options` mediumtext DEFAULT NULL,
  `cancelled_at` int(11) DEFAULT NULL,
  `created_at` int(11) NOT NULL,
  `finished_at` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `password_reset_tokens`

CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Estructura de tabla para la tabla `sessions`

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;