<?php

namespace Database\Seeders;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
      
        
        $role1 = Role::create(['name' => 'admin']);
        $role2 = Role::create(['name' => 'area_manager']);
        $role3 = Role::create(['name' => 'campaign_manager']);
        $role4 = Role::create(['name' => 'donor']);
        $role5 = Role::create(['name' => 'volunteer']);
        $role6 = Role::create(['name' => 'user']);


        
        // Crear permisos
Permission::create(['name' => 'users.view', 'description' => 'Vista de usuario particular'])->syncRoles([$role1]);
Permission::create(['name' => 'users.home', 'description' => 'Acceso a la página principal de usuarios'])->syncRoles([$role1]);
Permission::create(['name' => 'users.create', 'description' => 'Crear nuevos usuarios'])->syncRoles([$role1]);
Permission::create(['name' => 'users.edit', 'description' => 'Editar información de usuarios'])->syncRoles([$role1]);
Permission::create(['name' => 'users.delete', 'description' => 'Eliminar usuarios'])->syncRoles([$role1]);
Permission::create(['name' => 'users.restore', 'description' => 'Restaurar usuarios eliminados'])->syncRoles([$role1]);

Permission::create(['name' => 'donations.view', 'description' => 'Ver detalles de donaciones'])->syncRoles([$role1]);
Permission::create(['name' => 'donations.home', 'description' => 'Acceso al panel principal de donaciones'])->syncRoles([$role1]);
Permission::create(['name' => 'donations.create', 'description' => 'Registrar una nueva donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donations.edit', 'description' => 'Editar información de donaciones'])->syncRoles([$role1]);
Permission::create(['name' => 'donations.delete', 'description' => 'Eliminar donaciones'])->syncRoles([$role1]);
Permission::create(['name' => 'donations.restore', 'description' => 'Restaurar donaciones eliminadas'])->syncRoles([$role1]);

Permission::create(['name' => 'campaigns.view', 'description' => 'Ver campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaigns.home', 'description' => 'Acceso al panel de campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaigns.create', 'description' => 'Crear campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaigns.edit', 'description' => 'Editar campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaigns.delete', 'description' => 'Eliminar campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaigns.restore', 'description' => 'Restaurar campañas'])->syncRoles([$role1]);

Permission::create(['name' => 'campaign-finances.view', 'description' => 'Ver finanzas de campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaign-finances.home', 'description' => 'Acceso a la sección de finanzas de campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaign-finances.create', 'description' => 'Registrar finanzas de campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaign-finances.edit', 'description' => 'Editar finanzas de campañas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaign-finances.delete', 'description' => 'Eliminar registros de finanzas'])->syncRoles([$role1]);
Permission::create(['name' => 'campaign-finances.restore', 'description' => 'Restaurar registros financieros'])->syncRoles([$role1]);

Permission::create(['name' => 'donations-cashes.view', 'description' => 'Ver donaciones monetarias'])->syncRoles([$role1]);
Permission::create(['name' => 'donations-cashes.home', 'description' => 'Panel de donaciones monetarias'])->syncRoles([$role1]);
Permission::create(['name' => 'donations-cashes.create', 'description' => 'Registrar donaciones monetarias'])->syncRoles([$role1]);
Permission::create(['name' => 'donations-cashes.edit', 'description' => 'Editar donaciones monetarias'])->syncRoles([$role1]);
Permission::create(['name' => 'donations-cashes.delete', 'description' => 'Eliminar donaciones monetarias'])->syncRoles([$role1]);
Permission::create(['name' => 'donations-cashes.restore', 'description' => 'Restaurar donaciones monetarias'])->syncRoles([$role1]);

Permission::create(['name' => 'donation-items.view', 'description' => 'Ver ítems donados'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-items.home', 'description' => 'Panel de ítems donados'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-items.create', 'description' => 'Registrar ítems de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-items.edit', 'description' => 'Editar ítems de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-items.delete', 'description' => 'Eliminar ítems de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-items.restore', 'description' => 'Restaurar ítems de donación'])->syncRoles([$role1]);

Permission::create(['name' => 'donation-requests.view', 'description' => 'Ver solicitudes de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-requests.home', 'description' => 'Panel de solicitudes de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-requests.create', 'description' => 'Registrar solicitud de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-requests.edit', 'description' => 'Editar solicitud de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-requests.delete', 'description' => 'Eliminar solicitud de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-requests.restore', 'description' => 'Restaurar solicitud de donación'])->syncRoles([$role1]);

Permission::create(['name' => 'donation-request-descriptions.view', 'description' => 'Ver descripciones de solicitudes'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-request-descriptions.home', 'description' => 'Panel de descripciones de solicitudes'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-request-descriptions.create', 'description' => 'Agregar descripción a solicitud'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-request-descriptions.edit', 'description' => 'Editar descripción de solicitud'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-request-descriptions.delete', 'description' => 'Eliminar descripción de solicitud'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-request-descriptions.restore', 'description' => 'Restaurar descripción de solicitud'])->syncRoles([$role1]);

Permission::create(['name' => 'donation-types.view', 'description' => 'Ver tipos de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-types.home', 'description' => 'Panel de tipos de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-types.create', 'description' => 'Crear tipo de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-types.edit', 'description' => 'Editar tipo de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-types.delete', 'description' => 'Eliminar tipo de donación'])->syncRoles([$role1]);
Permission::create(['name' => 'donation-types.restore', 'description' => 'Restaurar tipo de donación'])->syncRoles([$role1]);

Permission::create(['name' => 'events.view', 'description' => 'Ver eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'events.home', 'description' => 'Panel de eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'events.create', 'description' => 'Crear eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'events.edit', 'description' => 'Editar eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'events.delete', 'description' => 'Eliminar eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'events.restore', 'description' => 'Restaurar eventos'])->syncRoles([$role1]);

Permission::create(['name' => 'event-locations.view', 'description' => 'Ver ubicaciones de eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'event-locations.home', 'description' => 'Panel de ubicaciones de eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'event-locations.create', 'description' => 'Registrar ubicación de evento'])->syncRoles([$role1]);
Permission::create(['name' => 'event-locations.edit', 'description' => 'Editar ubicación de evento'])->syncRoles([$role1]);
Permission::create(['name' => 'event-locations.delete', 'description' => 'Eliminar ubicación de evento'])->syncRoles([$role1]);
Permission::create(['name' => 'event-locations.restore', 'description' => 'Restaurar ubicación de evento'])->syncRoles([$role1]);

Permission::create(['name' => 'event-participants.view', 'description' => 'Ver participantes de eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'event-participants.home', 'description' => 'Panel de participantes de eventos'])->syncRoles([$role1]);
Permission::create(['name' => 'event-participants.create', 'description' => 'Registrar participante en evento'])->syncRoles([$role1]);
Permission::create(['name' => 'event-participants.edit', 'description' => 'Editar participante de evento'])->syncRoles([$role1]);
Permission::create(['name' => 'event-participants.delete', 'description' => 'Eliminar participante de evento'])->syncRoles([$role1]);
Permission::create(['name' => 'event-participants.restore', 'description' => 'Restaurar participante de evento'])->syncRoles([$role1]);

Permission::create(['name' => 'external-donors.view', 'description' => 'Ver donantes externos'])->syncRoles([$role1]);
Permission::create(['name' => 'external-donors.home', 'description' => 'Panel de donantes externos'])->syncRoles([$role1]);
Permission::create(['name' => 'external-donors.create', 'description' => 'Registrar donante externo'])->syncRoles([$role1]);
Permission::create(['name' => 'external-donors.edit', 'description' => 'Editar donante externo'])->syncRoles([$role1]);
Permission::create(['name' => 'external-donors.delete', 'description' => 'Eliminar donante externo'])->syncRoles([$role1]);
Permission::create(['name' => 'external-donors.restore', 'description' => 'Restaurar donante externo'])->syncRoles([$role1]);

Permission::create(['name' => 'financial-accounts.view', 'description' => 'Ver cuentas financieras'])->syncRoles([$role1]);
Permission::create(['name' => 'financial-accounts.home', 'description' => 'Panel de cuentas financieras'])->syncRoles([$role1]);
Permission::create(['name' => 'financial-accounts.create', 'description' => 'Registrar cuenta financiera'])->syncRoles([$role1]);
Permission::create(['name' => 'financial-accounts.edit', 'description' => 'Editar cuenta financiera'])->syncRoles([$role1]);
Permission::create(['name' => 'financial-accounts.delete', 'description' => 'Eliminar cuenta financiera'])->syncRoles([$role1]);
Permission::create(['name' => 'financial-accounts.restore', 'description' => 'Restaurar cuenta financiera'])->syncRoles([$role1]);

Permission::create(['name' => 'notifications.view', 'description' => 'Ver notificaciones'])->syncRoles([$role1]);
Permission::create(['name' => 'notifications.home', 'description' => 'Panel de notificaciones'])->syncRoles([$role1]);
Permission::create(['name' => 'notifications.create', 'description' => 'Crear notificación'])->syncRoles([$role1]);
Permission::create(['name' => 'notifications.edit', 'description' => 'Editar notificación'])->syncRoles([$role1]);
Permission::create(['name' => 'notifications.delete', 'description' => 'Eliminar notificación'])->syncRoles([$role1]);
Permission::create(['name' => 'notifications.restore', 'description' => 'Restaurar notificación'])->syncRoles([$role1]);

Permission::create(['name' => 'profiles.view', 'description' => 'Ver perfiles'])->syncRoles([$role1]);
Permission::create(['name' => 'profiles.home', 'description' => 'Panel de perfiles'])->syncRoles([$role1]);
Permission::create(['name' => 'profiles.create', 'description' => 'Crear perfil'])->syncRoles([$role1]);
Permission::create(['name' => 'profiles.edit', 'description' => 'Editar perfil'])->syncRoles([$role1]);
Permission::create(['name' => 'profiles.delete', 'description' => 'Eliminar perfil'])->syncRoles([$role1]);
Permission::create(['name' => 'profiles.restore', 'description' => 'Restaurar perfil'])->syncRoles([$role1]);

Permission::create(['name' => 'transactions.view', 'description' => 'Ver transacciones'])->syncRoles([$role1]);
Permission::create(['name' => 'transactions.home', 'description' => 'Panel de transacciones'])->syncRoles([$role1]);
Permission::create(['name' => 'transactions.create', 'description' => 'Registrar transacción'])->syncRoles([$role1]);
Permission::create(['name' => 'transactions.edit', 'description' => 'Editar transacción'])->syncRoles([$role1]);
Permission::create(['name' => 'transactions.delete', 'description' => 'Eliminar transacción'])->syncRoles([$role1]);
Permission::create(['name' => 'transactions.restore', 'description' => 'Restaurar transacción'])->syncRoles([$role1]);

Permission::create(['name' => 'volunteer-verifications.view', 'description' => 'Ver verificaciones de voluntarios'])->syncRoles([$role1]);
Permission::create(['name' => 'volunteer-verifications.home', 'description' => 'Panel de verificaciones de voluntarios'])->syncRoles([$role1]);
Permission::create(['name' => 'volunteer-verifications.create', 'description' => 'Registrar verificación de voluntario'])->syncRoles([$role1]);
Permission::create(['name' => 'volunteer-verifications.edit', 'description' => 'Editar verificación de voluntario'])->syncRoles([$role1]);
Permission::create(['name' => 'volunteer-verifications.delete', 'description' => 'Eliminar verificación de voluntario'])->syncRoles([$role1]);
Permission::create(['name' => 'volunteer-verifications.restore', 'description' => 'Restaurar verificación de voluntario'])->syncRoles([$role1]);

// Permisos para Volunteer (Voluntarios)
Permission::create(['name' => 'volunteer.dashboard.view', 'description' => 'Ver panel del voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.tasks.view', 'description' => 'Ver tareas disponibles'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.tasks.claim', 'description' => 'Reclamar tarea disponible'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.feedback.submit', 'description' => 'Enviar retroalimentación de actividad'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.activities.view', 'description' => 'Ver historial de actividades'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.activities.submit', 'description' => 'Registrar actividad realizada'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.notifications.view', 'description' => 'Ver notificaciones'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.profile.view', 'description' => 'Ver perfil del voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.tasks.view_available', 'description' => 'Ver tareas de voluntariado disponibles'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.tasks.select', 'description' => 'Seleccionar tareas para realizar'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.tasks.complete', 'description' => 'Marcar tareas como completadas'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.rewards.view', 'description' => 'Ver puntos y recompensas disponibles'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.rewards.redeem', 'description' => 'Canjear puntos por recompensas'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.verifications.view', 'description' => 'Ver verificación de voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.verifications.request', 'description' => 'Solicitar verificación de voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.verifications.submit', 'description' => 'Enviar verificación de voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.verifications.cancel', 'description' => 'Cancelar verificación de voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.verifications.restore', 'description' => 'Restaurar verificación de voluntario'])->syncRoles([$role5]);
Permission::create(['name' => 'volunteer.verifications.edit', 'description' => 'Editar verificación de voluntario'])->syncRoles([$role5]);


// Permisos para Donor (Donantes)

Permission::create(['name' => 'donations.create', 'description' => 'Registrar una nueva donación'])->syncRoles([$role4]);
Permission::create(['name' => 'donations.track', 'description' => 'Ver el estado de sus donaciones'])->syncRoles([$role4]);
Permission::create(['name' => 'donations.cancel', 'description' => 'Cancelar donaciones antes de su recolección'])->syncRoles([$role4]);
Permission::create(['name' => 'donations.upload_photos', 'description' => 'Subir imágenes de los artículos donados'])->syncRoles([$role4]);
Permission::create(['name' => 'tasks.request', 'description' => 'Solicitar voluntarios para que recojan sus donaciones'])->syncRoles([$role4]);
Permission::create(['name' => 'donations.view_history', 'description' => 'Ver historial de donaciones'])->syncRoles([$role4]);
Permission::create(['name' => 'donations.view_notifications', 'description' => 'Ver notificaciones relacionadas con donaciones'])->syncRoles([$role4]); 

// Permisos para Area Manager (Gerente de Área)
Permission::create(['name' => 'donations.view', 'description' => 'Ver donaciones asignadas a su área'])->syncRoles([$role2]);
Permission::create(['name' => 'volunteers.assign', 'description' => 'Asignar voluntarios a tareas de su área'])->syncRoles([$role2]);
Permission::create(['name' => 'tasks.validate', 'description' => 'Validar tareas completadas en su área'])->syncRoles([$role2]);
Permission::create(['name' => 'area.reports.view', 'description' => 'Ver reportes de actividad de su área'])->syncRoles([$role2]);
Permission::create(['name' => 'area.notifications.view', 'description' => 'Ver notificaciones relacionadas con su área'])->syncRoles([$role2]);
Permission::create(['name' => 'area.tasks.view', 'description' => 'Ver tareas asignadas a su área'])->syncRoles([$role2]);

// Permisos para Campaign Manager (Gerente de Campaña)
Permission::create(['name' => 'campaigns.create', 'description' => 'Crear nuevas campañas de donación'])->syncRoles([$role3]);
Permission::create(['name' => 'campaigns.edit', 'description' => 'Editar campañas existentes'])->syncRoles([$role3]);
Permission::create(['name' => 'campaigns.view', 'description' => 'Ver detalles de campañas'])->syncRoles([$role3]);
Permission::create(['name' => 'campaigns.reports.view', 'description' => 'Ver reportes de desempeño de campañas'])->syncRoles([$role3]);
Permission::create(['name' => 'campaigns.notifications.view', 'description' => 'Ver notificaciones relacionadas con campañas'])->syncRoles([$role3]);
Permission::create(['name' => 'campaigns.tasks.view', 'description' => 'Ver tareas asignadas a campañas'])->syncRoles([$role3]);
Permission::create(['name' => 'campaigns.tasks.assign', 'description' => 'Asignar tareas a voluntarios para campañas'])->syncRoles([$role3]);


// Permisos para User (Usuario Normal)
Permission::create(['name' => 'donations.view', 'description' => 'Ver las donaciones disponibles y realizar nuevas donaciones'])->syncRoles([$role6]);
Permission::create(['name' => 'profile.view', 'description' => 'Ver y editar la información de su perfil'])->syncRoles([$role6]);
Permission::create(['name' => 'auth.register', 'description' => 'Registrar una cuenta en el sistema'])->syncRoles([$role6]);
Permission::create(['name' => 'auth.login', 'description' => 'Iniciar sesión en el sistema'])->syncRoles([$role6]);
Permission::create(['name' => 'profile.create', 'description' => 'Completar el perfil de usuario con datos personales'])->syncRoles([$role6]);
Permission::create(['name' => 'donations.view', 'description' => 'Ver campañas de donaciones y actividades relacionadas'])->syncRoles([$role6]);
Permission::create(['name' => 'notifications.subscribe', 'description' => 'Suscribirse a notificaciones para recibir actualizaciones sobre campañas y oportunidades de voluntariado'])->syncRoles([$role6]);

    }
} 
