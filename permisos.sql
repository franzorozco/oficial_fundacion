INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('users.verlista', 'web', NOW(), NOW()),
('users.ver', 'web', NOW(), NOW()),
('users.editar', 'web', NOW(), NOW()),
('users.editarRol', 'web', NOW(), NOW()),
('users.eliminar', 'web', NOW(), NOW()),
('users.crear', 'web', NOW(), NOW()),
('users.verEliminados', 'web', NOW(), NOW()),
('users.regenerarPDF', 'web', NOW(), NOW()),
('users.imprimir', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('volunteers.verlista', 'web', NOW(), NOW()),
('volunteers.ver', 'web', NOW(), NOW()),
('volunteers.editar', 'web', NOW(), NOW()),
('volunteers.eliminar', 'web', NOW(), NOW()),
('volunteers.crear', 'web', NOW(), NOW()),
('volunteers.verEliminados', 'web', NOW(), NOW()),
('volunteers.regenerarPDF', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('volunteer-verifications.verlista', 'web', NOW(), NOW()),
('volunteer-verifications.crear', 'web', NOW(), NOW()),
('volunteer-verifications.verEliminados', 'web', NOW(), NOW()),
('volunteer-verifications.regenerarPDF', 'web', NOW(), NOW()),
('volunteer-verifications.misDecisiones', 'web', NOW(), NOW()),
('volunteer-verifications.buscar', 'web', NOW(), NOW()),
('volunteer-verifications.ver', 'web', NOW(), NOW()),
('volunteer-verifications.editar', 'web', NOW(), NOW()),
('volunteer-verifications.eliminar', 'web', NOW(), NOW()),
('volunteer-verifications.aprobar', 'web', NOW(), NOW()),
('volunteer-verifications.rechazar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('campaigns.verlista', 'web', NOW(), NOW()),
('campaigns.buscar', 'web', NOW(), NOW()),
('campaigns.filtrar', 'web', NOW(), NOW()),
('campaigns.crear', 'web', NOW(), NOW()),
('campaigns.regenerarPDF', 'web', NOW(), NOW()),
('campaigns.verEliminadas', 'web', NOW(), NOW()),
('campaigns.ver', 'web', NOW(), NOW()),
('campaigns.editar', 'web', NOW(), NOW()),
('campaigns.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('events.verlista', 'web', NOW(), NOW()),
('events.buscar', 'web', NOW(), NOW()),
('events.filtrar', 'web', NOW(), NOW()),
('events.crear', 'web', NOW(), NOW()),
('events.verEliminados', 'web', NOW(), NOW()),
('events.ver', 'web', NOW(), NOW()),
('events.editar', 'web', NOW(), NOW()),
('events.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('event-locations.verlista', 'web', NOW(), NOW()),
('event-locations.buscar', 'web', NOW(), NOW()),
('event-locations.filtrar', 'web', NOW(), NOW()),
('event-locations.crear', 'web', NOW(), NOW()),
('event-locations.verEliminados', 'web', NOW(), NOW()),
('event-locations.ver', 'web', NOW(), NOW()),
('event-locations.editar', 'web', NOW(), NOW()),
('event-locations.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('event-participants.verlista', 'web', NOW(), NOW()),
('event-participants.buscar', 'web', NOW(), NOW()),
('event-participants.filtrar', 'web', NOW(), NOW()),
('event-participants.crear', 'web', NOW(), NOW()),
('event-participants.ver', 'web', NOW(), NOW()),
('event-participants.editar', 'web', NOW(), NOW()),
('event-participants.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('campaign-finances.verlista', 'web', NOW(), NOW()),
('campaign-finances.buscar', 'web', NOW(), NOW()),
('campaign-finances.crear', 'web', NOW(), NOW()),
('campaign-finances.ver', 'web', NOW(), NOW()),
('campaign-finances.editar', 'web', NOW(), NOW()),
('campaign-finances.eliminar', 'web', NOW(), NOW()),
('campaign-finances.exportar-pdf', 'web', NOW(), NOW()),
('campaign-finances.vereliminados', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donations.verlista', 'web', NOW(), NOW()),
('donations.filtrar', 'web', NOW(), NOW()),
('donations.crear', 'web', NOW(), NOW()),
('donations.ver', 'web', NOW(), NOW()),
('donations.editar', 'web', NOW(), NOW()),
('donations.eliminar', 'web', NOW(), NOW()),
('donations.verpdf', 'web', NOW(), NOW()),
('donations.verpdfgeneral', 'web', NOW(), NOW()),
('donations.vereliminados', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donations-incoming.verlista', 'web', NOW(), NOW()),
('donations-incoming.filtrar', 'web', NOW(), NOW()),
('donations-incoming.ver', 'web', NOW(), NOW()),
('donations-incoming.verpdf', 'web', NOW(), NOW()),
('donations-incoming.verpdfgeneral', 'web', NOW(), NOW()),
('donations-incoming.aceptar', 'web', NOW(), NOW()),
('donations-incoming.rechazar', 'web', NOW(), NOW()),
('donations-incoming.historial', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donation-items.verlista', 'web', NOW(), NOW()),
('donation-items.buscar', 'web', NOW(), NOW()),
('donation-items.crear', 'web', NOW(), NOW()),
('donation-items.ver', 'web', NOW(), NOW()),
('donation-items.editar', 'web', NOW(), NOW()),
('donation-items.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donantes.verlista', 'web', NOW(), NOW()),
('donantes.buscar', 'web', NOW(), NOW()),
('donantes.filtrar', 'web', NOW(), NOW()),
('donantes.ver', 'web', NOW(), NOW()),
('donantes.crear', 'web', NOW(), NOW()),
('donantes.editar', 'web', NOW(), NOW()),
('donantes.eliminar', 'web', NOW(), NOW()),
('donantes.ver_eliminados', 'web', NOW(), NOW()),
('donantes.exportar_pdf', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('external-donors.verlista', 'web', NOW(), NOW()),
('external-donors.buscar', 'web', NOW(), NOW()),
('external-donors.filtrar', 'web', NOW(), NOW()),
('external-donors.ver', 'web', NOW(), NOW()),
('external-donors.crear', 'web', NOW(), NOW()),
('external-donors.editar', 'web', NOW(), NOW()),
('external-donors.eliminar', 'web', NOW(), NOW()),
('external-donors.ver_eliminados', 'web', NOW(), NOW()),
('external-donors.exportar_pdf', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donation-requests.verlista', 'web', NOW(), NOW()),
('donation-requests.buscar', 'web', NOW(), NOW()),
('donation-requests.filtrar', 'web', NOW(), NOW()),
('donation-requests.ver', 'web', NOW(), NOW()),
('donation-requests.crear', 'web', NOW(), NOW()),
('donation-requests.editar', 'web', NOW(), NOW()),
('donation-requests.eliminar', 'web', NOW(), NOW()),
('donation-requests.aceptar', 'web', NOW(), NOW()),
('donation-requests.rechazar', 'web', NOW(), NOW()),
('donation-requests.ver_eliminados', 'web', NOW(), NOW()),
('donation-requests.exportar_pdf', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donation-request-descriptions.verlista', 'web', NOW(), NOW()),
('donation-request-descriptions.buscar', 'web', NOW(), NOW()),
('donation-request-descriptions.ver', 'web', NOW(), NOW()),
('donation-request-descriptions.crear', 'web', NOW(), NOW()),
('donation-request-descriptions.editar', 'web', NOW(), NOW()),
('donation-request-descriptions.eliminar', 'web', NOW(), NOW()),
('donation-request-descriptions.ver_eliminados', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('task-assignments.verlista', 'web', NOW(), NOW()),
('task-assignments.filtrar', 'web', NOW(), NOW()),
('task-assignments.pdf', 'web', NOW(), NOW()),
('task-assignments.ver', 'web', NOW(), NOW()),
('task-assignments.crear', 'web', NOW(), NOW()),
('task-assignments.editar', 'web', NOW(), NOW()),
('task-assignments.eliminar', 'web', NOW(), NOW()),
('task-assignments.cambiar_estado', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('financial-accounts.verlista', 'web', NOW(), NOW()),
('financial-accounts.filtrar', 'web', NOW(), NOW()),
('financial-accounts.pdf', 'web', NOW(), NOW()),
('financial-accounts.trashed', 'web', NOW(), NOW()),
('financial-accounts.crear', 'web', NOW(), NOW()),
('financial-accounts.editar', 'web', NOW(), NOW()),
('financial-accounts.ver', 'web', NOW(), NOW()),
('financial-accounts.eliminar', 'web', NOW(), NOW()),
('financial-accounts.transferir', 'web', NOW(), NOW()),
('financial-accounts.imprimir', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('transactions.verlista', 'web', NOW(), NOW()),
('transactions.filtrar', 'web', NOW(), NOW()),
('transactions.pdf', 'web', NOW(), NOW()),
('transactions.ver', 'web', NOW(), NOW()),
('transactions.imprimir', 'web', NOW(), NOW()),
('transactions.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('tasks.verlista', 'web', NOW(), NOW()),
('tasks.crear', 'web', NOW(), NOW()),
('tasks.ver', 'web', NOW(), NOW()),
('tasks.editar', 'web', NOW(), NOW()),
('tasks.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('donation-types.verlista', 'web', NOW(), NOW()),
('donation-types.crear', 'web', NOW(), NOW()),
('donation-types.ver', 'web', NOW(), NOW()),
('donation-types.editar', 'web', NOW(), NOW()),
('donation-types.eliminar', 'web', NOW(), NOW());
INSERT INTO permissions (name, guard_name, created_at, updated_at) VALUES
('roles.verlista', 'web', NOW(), NOW()),
('roles.crear', 'web', NOW(), NOW()),
('roles.ver', 'web', NOW(), NOW()),
('roles.editar', 'web', NOW(), NOW()),
('roles.eliminar', 'web', NOW(), NOW()),
('roles.trashed', 'web', NOW(), NOW());
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT id, 1 FROM permissions;
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT id, 2 FROM permissions
WHERE name LIKE 'volunteers.%'
   OR name LIKE 'campaigns.%'
   OR name LIKE 'events.%'
   OR name LIKE 'event-%'
   OR name LIKE 'task-%'
   OR name LIKE 'roles.verlista'
   OR name LIKE 'roles.ver'
   OR name LIKE 'roles.editar';
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT id, 3 FROM permissions
WHERE name LIKE 'campaigns.%'
   OR name LIKE 'campaign-finances.%'
   OR name LIKE 'donations.%'
   OR name LIKE 'donation-%'
   OR name LIKE 'task-%'
   OR name LIKE 'transactions.%'
   OR name LIKE 'financial-accounts.%'
   OR name LIKE 'roles.verlista';
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT id, 4 FROM permissions
WHERE name LIKE 'donations.ver%'
   OR name LIKE 'donations-incoming.ver%'
   OR name LIKE 'donation-items.ver%'
   OR name LIKE 'donation-requests.ver%'
   OR name LIKE 'donation-request-descriptions.ver%'
   OR name LIKE 'donantes.ver%'
   OR name LIKE 'external-donors.ver%';
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT id, 5 FROM permissions
WHERE name LIKE 'campaigns.ver%'
   OR name LIKE 'events.ver%'
   OR name LIKE 'event-participants.ver%'
   OR name LIKE 'volunteers.ver%'
   OR name LIKE 'volunteer-verifications.misDecisiones'
   OR name LIKE 'volunteer-verifications.ver';
INSERT INTO role_has_permissions (permission_id, role_id)
SELECT id, 6 FROM permissions
WHERE name IN (
    'users.ver',
    'users.regenerarPDF',
    'donations.ver',
    'campaigns.ver',
    'events.ver'
);
