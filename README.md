# Event Manager

A comprehensive event management application built with Laravel 11, Vue.js 3 (Composition API), Inertia.js, and Vite.

## Features

- **Event Management**: Admin can create, edit, delete, and publish events
- **User Management**: Admin can manage users and assign admin roles
- **Public Event Registration**: Non-authenticated users can register for events and sign up simultaneously
- **QR Code Generation**: Automatic QR code generation for event registrations
- **QR Code Scanning**: Admin can scan QR codes for event check-ins
- **Participant Management**: Admin can view and manage all event participants
- **User Dashboard**: Registered users can view their events and QR codes

## Technology Stack

- **Backend**: Laravel 11
- **Frontend**: Vue.js 3 (Composition API)
- **UI Framework**: Tailwind CSS
- **Rendering**: Inertia.js
- **Build Tool**: Vite
- **QR Code**: SimpleSoftwareIO/simple-qrcode & qrcode.vue

## Installation

### Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js 18 or higher
- NPM or Yarn

### Setup Instructions

1. **Clone the repository**

   ```bash
   git clone https://github.com/josecabalquinto/event-manager.git
   cd event-manager
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**

   ```bash
   npm install
   ```

4. **Environment configuration**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Database setup**

   The application is configured to use SQLite by default. The database file is already created at `database/database.sqlite`.

   Run migrations and seed the database:

   ```bash
   php artisan migrate --seed
   ```

   This will create:

   - Admin user: `admin@example.com` / `password`
   - Test user: `user@example.com` / `password`

6. **Build frontend assets**

   ```bash
   npm run build
   ```

7. **Start the development server**

   ```bash
   php artisan serve
   ```

   The application will be available at `http://localhost:8000`

## Development

To run the development server with hot module replacement:

```bash
# Terminal 1 - Laravel server
php artisan serve --host=0.0.0.0 --port=8000

# Terminal 2 - Vite dev server
npm run dev -- --host=192.168.254.161 --port=5173
```

## Default Credentials

After running the seeder, you can log in with:

**Admin Account:**

- Email: `admin@example.com`
- Password: `password`

**Regular User Account:**

- Email: `user@example.com`
- Password: `password`

## Usage

### For Admins

1. Log in with admin credentials
2. Access the Admin Panel from the navigation
3. Manage Events: Create, edit, and publish events
4. Manage Users: Add or modify user accounts
5. View Participants: See all event registrations
6. Check-In: Scan QR codes to check in participants at events

### For Users

1. Browse published events on the homepage
2. Click on an event to see details
3. Register for an event (creates account if not logged in)
4. View your registered events in "My Events"
5. Access QR code for each registered event
6. Present QR code at the event for check-in

### For Guests

1. Browse published events on the homepage
2. Register for an event by providing name, email, and password
3. Account is automatically created upon registration
4. Access QR code immediately after registration

## Project Structure

```
event-manager/
├── app/
│   ├── Http/
│   │   ├── Controllers/       # Application controllers
│   │   └── Middleware/        # Custom middleware
│   └── Models/                # Eloquent models
├── database/
│   ├── migrations/            # Database migrations
│   └── seeders/               # Database seeders
├── resources/
│   ├── js/
│   │   ├── Pages/             # Inertia Vue pages
│   │   ├── Layouts/           # Vue layout components
│   │   └── app.js             # Main JavaScript entry point
│   └── views/
│       └── app.blade.php      # Main Blade template
├── routes/
│   ├── web.php                # Web routes
│   └── auth.php               # Authentication routes
└── public/                    # Public assets
```

## Key Features Implementation

### QR Code Generation

- Automatic UUID-based QR code generation on registration
- QR codes are displayed using `qrcode.vue` component
- Can be scanned using any QR code reader

### Event Check-In

- Admin can manually enter QR codes for check-in
- Real-time feedback on check-in status
- Prevents duplicate check-ins
- Tracks who performed the check-in and when

### Role-Based Access Control

- Admin middleware protects admin routes
- Users can only view their own registrations
- Admins have full access to all features

## License

MIT License

## Support

For issues and questions, please open an issue on GitHub.
