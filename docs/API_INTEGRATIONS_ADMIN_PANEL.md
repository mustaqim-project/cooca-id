# API Integrations Management - Admin Panel

## Overview

This feature provides a comprehensive admin panel for managing all API integrations from the `.env.example` file, including:

- **Fonnte WhatsApp** - Messaging service
- **SMTP Mail Server** - Email sending
- **Google OAuth** - Authentication
- **Midtrans Payment Gateway** - Payment processing

All integrations are stored in the `api_integrations` table and can be managed through the admin panel.

## Database Schema

### Table: `api_integrations`

| Column | Type | Description |
|--------|------|-------------|
| id | uuid | Primary key |
| name | string | Unique identifier (e.g., 'fonnte', 'smtp') |
| label | string | Display name (e.g., 'Fonnte WhatsApp') |
| category | string | Category (messaging, email, authentication, payment) |
| is_active | boolean | Whether the integration is active |
| credentials | json | API keys, secrets, tokens |
| config | json | Additional configuration options |
| description | text | Description of the integration |
| last_used_at | timestamp | Last time the integration was used |
| tested_at | timestamp | Last test timestamp |
| test_status | boolean | Last test result (true=success, false=failed) |
| test_message | text | Test result message |
| created_by | uuid | User who created the integration |
| updated_by | uuid | User who last updated the integration |
| timestamps | - | created_at, updated_at |
| softDeletes | - | deleted_at |

## Files Created

### Backend (Laravel)

1. **Migration**: `database/migrations/2026_06_12_000030_create_api_integrations_table.php`
2. **Model**: `app/Models/ApiIntegration.php`
3. **Controller**: `app/Http/Controllers/Admin/ApiIntegrationController.php`
4. **Seeder**: `database/seeders/ApiIntegrationSeeder.php`
5. **Routes**: Added to `routes/web.php`

### Frontend (Vue.js + Inertia)

1. **Index Page**: `resources/js/Pages/Admin/ApiIntegrations/Index.vue`
   - Lists all integrations
   - Filter by category
   - Search functionality
   - Test connection button
   - Toggle active/inactive status
   - Edit, View, Delete actions

## Installation & Setup

### 1. Run Migration

```bash
php artisan migrate
```

### 2. Seed Default Integrations

Option A: Via Artisan Command
```bash
php artisan db:seed --class=ApiIntegrationSeeder
```

Option B: Via Admin Panel
- Navigate to Admin > API Integrations
- Click "Seed Defaults" button

### 3. Access Admin Panel

Navigate to: `/admin/api-integrations`

## Features

### List View
- Display all integrations in a table
- Filter by category (messaging, email, authentication, payment)
- Search by name, label, or description
- Status indicators (Active, Connected, Failed, Not Tested)

### Create/Edit Integration
- Name (unique identifier)
- Label (display name)
- Category selection
- Description
- Credentials (JSON format)
- Configuration (JSON format)
- Active/Inactive toggle

### Test Connection
Each integration type has a specific test method:
- **Fonnte**: Validates API key and endpoint
- **SMTP**: Tests connection to mail server
- **Google OAuth**: Validates client credentials
- **Midtrans**: Checks server key and mode

### Seed from .env
The "Seed Defaults" button reads current configuration from:
- `config/services.php` (Fonnte, Google, Midtrans)
- `config/mail.php` (SMTP)

And populates the database with these values.

## Usage Examples

### Accessing Integration in Code

```php
use App\Models\ApiIntegration;

// Get integration by name
$fonnte = ApiIntegration::getByName('fonnte');

// Check if configured
if ($fonnte->isConfigured()) {
    $apiKey = $fonnte->getCredential('api_key');
    // Use the API...
}

// Mark as used
$fonnte->markUsed();
```

### Adding New Integration Type

1. Add credentials to `.env.example`
2. Update `ApiIntegrationSeeder` with new integration data
3. Add test method in `ApiIntegrationController::performTest()`
4. Run seeder or use "Seed Defaults" button

## API Endpoints

| Method | Route | Description |
|--------|-------|-------------|
| GET | `/admin/api-integrations` | List all integrations |
| GET | `/admin/api-integrations/create` | Create form |
| POST | `/admin/api-integrations` | Store new integration |
| GET | `/admin/api-integrations/{id}` | View details |
| GET | `/admin/api-integrations/{id}/edit` | Edit form |
| PUT | `/admin/api-integrations/{id}` | Update integration |
| DELETE | `/admin/api-integrations/{id}` | Delete integration |
| POST | `/admin/api-integrations/{id}/test` | Test connection |
| POST | `/admin/api-integrations/seed` | Seed from .env |

## Security Considerations

1. **Authentication Required**: All routes require `auth:admin` middleware
2. **Credentials Encryption**: Consider encrypting sensitive credentials
3. **Access Control**: Only admins can manage integrations
4. **Audit Trail**: Track who created/updated integrations

## Future Enhancements

- [ ] Encrypt credentials at rest
- [ ] Add webhook configuration
- [ ] Integration usage analytics
- [ ] Auto-refresh status
- [ ] Import/Export configurations
- [ ] Environment-specific configs

## Troubleshooting

### Issue: Integrations not showing
- Run migration: `php artisan migrate`
- Seed data: `php artisan db:seed --class=ApiIntegrationSeeder`

### Issue: Test connection fails
- Check credentials are correctly set
- Verify network connectivity
- Review logs for error messages

### Issue: Seed defaults not working
- Ensure config files reference correct .env variables
- Check config cache: `php artisan config:clear`
