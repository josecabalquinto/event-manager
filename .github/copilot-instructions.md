# GitHub Copilot Instructions - Event Manager

## Project Overview

Laravel 11 + Vue 3 + Inertia.js event management application with QR-based check-ins, PDF certificate generation, and blockchain verification on Polygon Amoy testnet. Uses SQLite database with TCPDF for certificate creation and Web3 for blockchain integration. Follows Laravel's resource controller conventions with admin middleware protection.

## Architecture Patterns

### Route Organization

- **Public routes**: Event browsing (`/events`, `/events/{event}`)
- **Auth routes**: User dashboard, profile, my-events (`/my-events`, `/dashboard`), certificates (`/certificates`)
- **Admin routes**: Prefixed `/admin` with middleware `['auth', 'admin']`
- **Registration**: POST `/events/{event}/register` handles both guest signup and authenticated registration

### Model Relationships & Key Patterns

- **UUID QR Codes**: `EventRegistration` auto-generates UUID in `boot()` method via `Str::uuid()`
- **Certificate Numbers**: `Certificate` auto-generates format `CERT-{RANDOM8}-{YEAR}` in `boot()` method
- **Role-based Access**: `User::isAdmin()` method + `AdminMiddleware` for route protection
- **Eloquent Scopes**: `Event::published()`, `Event::upcoming()` for common queries
- **Computed Attributes**: `Event::getAvailableSpotsAttribute()`, `Event::getBannerImageUrlAttribute()`, `Certificate::getCertificateUrlAttribute()`
- **Relationships**: `EventRegistration` hasOne `Certificate`, `Certificate` belongsTo `EventRegistration`

### Frontend Structure (Inertia + Vue 3 Composition API)

- **Page Components**: `resources/js/Pages/{Section}/{Action}.vue` (e.g., `Events/Show.vue`, `Admin/Dashboard.vue`)
- **Layout Pattern**: All pages use `<AppLayout>` component with yellow/gold gradient theme
- **QR Display**: Uses `qrcode.vue` package for QR code rendering
- **State Management**: Props passed from Laravel controllers, no separate state store
- **Admin Layout**: Separate `AdminLayout.vue` for admin pages with navigation

### File Upload & Storage

- **Banner Images**: Stored in `storage/app/public`, accessed via `asset('storage/' . $path)`
- **Certificate PDFs**: Generated via `CertificatePdfService` using TCPDF library
- **Image Processing**: Controllers handle file validation, storage links created via `php artisan storage:link`

## Development Workflows

### Setup Commands

```bash
# Fresh setup
composer install && npm install
cp .env.example .env && php artisan key:generate
php artisan migrate --seed  # Creates admin@example.com/password & user@example.com/password
npm run build && php artisan serve

# Development with hot reload
php artisan serve  # Terminal 1
npm run dev        # Terminal 2
```

### Database Patterns

- **SQLite**: Default database at `database/database.sqlite`
- **Seeding**: `DatabaseSeeder` creates admin and test users with `is_admin` boolean flag
- **Migrations**: Include coordinates (`latitude`/`longitude`), banner images, and certificate fields
- **Laravel 11**: Uses new migration structure and `boot()` patterns

### QR Code Workflow

1. **Registration**: UUID generated automatically in `EventRegistration::boot()`
2. **Display**: QR codes shown via `qrcode.vue` component in user dashboard
3. **Scanning**: Admin enters QR code manually in check-in form (no camera scanning)
4. **Validation**: `CheckInController::scan()` validates UUID and prevents duplicate check-ins

### Certificate Workflow

1. **Generation**: Admin generates certificates for checked-in participants via `AdminCertificateController::generateForEvent()`
2. **Auto-numbering**: Certificate numbers format `CERT-{8CHARS}-{YEAR}` generated in model `boot()`
3. **Hash Generation**: Certificate hash generated from participant data using `Certificate::generateCertificateHash()`
4. **Blockchain Issuance**: `BlockchainCertificateService` issues certificate hash to Polygon Amoy smart contract
5. **PDF Creation**: `CertificatePdfService` uses TCPDF to generate landscape A4 certificates with blockchain QR codes
6. **Verification**: Public verification via `/certificates/verify/{hash}` compares local and blockchain data
7. **Access**: Users view certificates in `/certificates` or from individual event registrations
8. **Download**: PDF files stored in `storage/app/public/certificates/` when `is_generated` is true

## Key Conventions

### Controller Patterns

- **Resource Controllers**: `AdminEventController`, `AdminUserController` follow Laravel resource conventions
- **Authorization**: Check `$registration->user_id !== Auth::id()` for ownership validation
- **Inertia Responses**: Return `Inertia::render('Page/Component', $data)` with explicitly mapped data arrays

### Frontend Conventions

- **Tailwind Classes**: Extensive use of Tailwind with custom color schemes (yellow/gold themes)
- **Image Modals**: Click-to-expand pattern for banner images with overlay gradients
- **Icon Usage**: Heroicons SVG icons embedded directly in templates
- **Form Handling**: Inertia form helpers for Laravel validation integration

### Admin Features

- **Middleware Stack**: `['auth', 'admin']` protects all admin routes
- **Participant Management**: View registrations across all events via `AdminParticipantController`
- **Event Management**: Full CRUD with file upload support via `AdminEventController::update` with files
- **Check-in System**: Manual QR code entry rather than camera-based scanning
- **Certificate Management**: Generate certificates for checked-in participants, track generation status via `AdminCertificateController`

## Integration Points

- **Ziggy**: Laravel route helpers available in Vue (`route()` function)
- **Vite**: Asset building with hot module replacement in development
- **Storage**: Public disk symlink required for banner image access
- **QR Libraries**: `simplesoftwareio/simple-qrcode` (Laravel) + `qrcode.vue` (frontend)
- **Blockchain**: Web3 + Ethers.js for Polygon Amoy testnet integration via `BlockchainCertificateService`
- **Smart Contract**: Solidity contract at `contracts/CertificateRegistry.sol` for certificate verification

## File Locations

- **Models**: Standard Laravel locations with computed attributes and scopes
- **Vue Pages**: `resources/js/Pages/` following controller/action naming
- **Migrations**: Include recent additions for coordinates and banner images
- **Config**: Custom admin middleware registered in `bootstrap/app.php`
