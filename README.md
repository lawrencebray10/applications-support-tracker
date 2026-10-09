# Applications Support Tracker

A Laravel-based web application for recording, monitoring, and reporting daily activities performed by an applications support team. The system supports operational handovers, staff accountability, and historical activity tracking.

## Features

- **Authentication:** Secure login and logout for authorized users.
- **Dashboard:** Overview of active activities, daily updates, and update statuses.
- **Activity Management:** Create, view, filter, and deactivate support activities.
- **Activity Updates:** Record an activity's date, status, and remarks.
- **Staff Management:** Create staff accounts and manage employee details, including employee ID, job title, department, and role.
- **Daily Handover:** View daily activity updates, staff details, timestamps, remarks, and update history.
- **Historical Reports:** Filter activity records by a custom date range and view summary statistics.
- **Role-Based Access Control:** Restrict administrative functions to administrators while allowing support staff to access operational features.
- **Activity History:** Preserve previous updates so changes in activity status remain available for review.

## Technology Stack

- **Framework:** Laravel 13
- **Language:** PHP
- **Database:** MySQL
- **Frontend:** HTML, CSS, Blade templates
- **Dependency Management:** Composer
- **Version Control:** Git and GitHub

## User Roles

### Administrator
- Access the dashboard and activity records.
- Create and deactivate activities.
- Create staff accounts and update staff profiles.
- Assign staff roles and manage employee details.
- View reports and daily handover records.

### Support Staff
- Log in to the application.
- View active activities.
- Record activity status updates and remarks.
- View daily handover records and historical reports.
- Access operational pages without administrator-only permissions.

## Prerequisites

Install the following software before setting up the project:

- PHP compatible with the project's Laravel version and its required extensions.
- Composer.
- MySQL Server.
- Node.js and npm, if frontend assets need to be installed or built.
- Git.

## Installation and Setup

### 1. Clone the repository

```bash
git clone https://github.com/lawrencebray10/applications-support-tracker.git
cd applications-support-tracker
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Configure environment variables

Create your local environment file from the example:

```bash
copy .env.example .env
```

Generate an application key:

```bash
php artisan key:generate
```

Update `.env` with your local database credentials and application settings:

```env
APP_NAME="Applications Support Tracker"
APP_ENV=local
APP_DEBUG=true
APP_URL=http://127.0.0.1:8000

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=applications_support_tracker
DB_USERNAME=your_database_username
DB_PASSWORD=your_database_password
```

Replace the database username and password with your own values. Do not commit `.env` or share production credentials.

### 4. Create the database

Create a MySQL database named:

```sql
CREATE DATABASE applications_support_tracker;
```

Ensure the database user configured in `.env` has the necessary privileges.

### 5. Run database migrations

```bash
php artisan config:clear
php artisan migrate
```

These migrations create the application tables, including staff roles, staff details, activities, and activity logs.


### 6. Create the Initial Administrator

Before seeding, configure these variables in your local `.env` file:

```env
INITIAL_ADMIN_NAME="System Administrator"
INITIAL_ADMIN_EMAIL=admin@example.com
INITIAL_ADMIN_PASSWORD=
```

Set `INITIAL_ADMIN_PASSWORD` to a strong password of at least 12 characters. Do not commit your actual password.

Clear Laravel's configuration cache and run the seeder:

```bash
php artisan config:clear
php artisan db:seed
```

The seeder creates the initial administrator only if the configured email does not already exist. It does not reset an existing administrator's password.


### 7. Start the application

```bash
php artisan serve
```

Open the local application in your browser:

http://127.0.0.1:8000

The application redirects unauthenticated visitors to the login page.

## Testing

The application includes automated unit and feature tests. Run the test suite from the project directory:

```bash
php artisan test
```

The feature tests cover:

- **Authentication:** Login-page access, home-page redirection, valid and invalid login attempts, and logout.
- **Activity updates:** Saving status updates and remarks, preserving previous updates, rejecting invalid statuses, and preventing guest submissions.
- **Daily handover:** Displaying activity and staff details, filtering records by date, and restricting access to authenticated users.
- **Reports:** Calculating update totals and status counts, filtering by a custom date range, and validating date ranges.
- **Role-based access control:** Restricting administrative pages and activity creation to administrators while allowing support staff to access operational features.

### Test database configuration

Tests use the dedicated MySQL database configured in `phpunit.xml`. Before running the suite, ensure that:

- MySQL Server is running.
- The configured test database exists.
- The configured database user has the necessary privileges.
- The test database credentials are supplied locally and are not committed to version control.

**Important:** Automated tests use database migrations and may reset test data. Never point the testing configuration at a database containing production or personal data.

### Current test results

The latest local test run passed **22 tests with 66 assertions**. Rerun `php artisan test` after making changes to confirm that the suite still passes.

## License

This project was developed as an applications support tracking system assignment. Confirm the applicable project license before distributing or reusing the software.
