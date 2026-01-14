# Meeting Hall Booking System

## Purpose
This project is a web-based application designed to simplify the process of booking meeting halls, conference rooms, and co-working spaces. It provides a centralized platform for users to browse available venues, check their real-time availability, and make bookings efficiently.

## Project Flow
1.  **Browse**: Users land on the home page and see a list of available venues.
2.  **Filter**: Users can filter halls by Space Type (Conference, Training, etc.), Location (City), Date Range, and Capacity.
3.  **Details**: Clicking on a venue shows detailed information, including a gallery of images, pricing, and amenities.
4.  **Availability**: Users select a date and time slot to check if the hall is free.
5.  **Booking**: If available, the user proceeds to book the slot, and the system confirms the reservation.

## Technology Stack
-   **Frontend**: HTML5, CSS3, Bootstrap 5, JavaScript (jQuery, Select2)
-   **Backend**: Laravel Framework (PHP)
-   **Database**: MySQL

## Steps to Run the Project

Follow these steps to set up and run the project on your local machine.

### 1. Prerequisite: Install XAMPP
This project requires a PHP environment to run.
-   If you don't have XAMPP installed, download it from the [Apache Friends website](https://www.apachefriends.org/download.html).
-   **Recommended Version**: XAMPP with **PHP 8.2.x** (e.g., v8.2.12).
-   Install XAMPP and ensure that **Apache** and **MySQL** services are running via the XAMPP Control Panel.

### 2. Prerequisite: Install Composer
This project uses Composer for PHP dependency management.
-   Download the installer from [getcomposer.org](https://getcomposer.org/download/).
-   Run the installer (`Composer-Setup.exe`).
-   **Important**: During installation, ensure the option **"Add to system path"** is selected (it is usually selected by default). This allows you to run `composer` from any terminal.

### 3. Clone the Repository
Open your terminal or command prompt and clone the project:
```bash
git clone <https://github.com/manalisawant3107/Meeting-Hall-Booking-System.git>
cd MEETING-HALL-BOOKING-SYSTEM
```

### 4. Install Backend Dependencies

Before running this command make sure in C:\xammp\php\php.ini  file 
remove semicolon from this line
;extension=zip  
to this 
extension=zip

Run the following command to install the necessary PHP packages via Composer:
```bash
composer install
```

### 5. Environment Setup
1.  Duplicate the example environment file:
    ```bash
    copy .env.example .env
    ```

2.  Open the `.env` file in a and configure your database settings:
    ```ini
    DB_CONNECTION=mysql
    DB_HOST=127.0.0.1
    DB_PORT=3306
    DB_DATABASE=booking_system_db
    DB_USERNAME=root
    DB_PASSWORD=
    ```
    *Note: Leave password empty if you are using default XAMPP settings.*
    Also Run this command if needed after changing in env file

    ```bash
    php artisan config:clear 
      OR
    php artisan config:cache
    ```
   
### 6. Generate Application Key
Run this command to generate the unique app key:
```bash
php artisan key:generate
```

### 7. Database Setup & Migration
1.  Create a new database named `booking_system_db` in your MySQL (via phpMyAdmin or CLI).
2.  Run the migrations to create the tables:
    ```bash
    php artisan migrate
    ```

### 8. Seed Data & Images
To populate the database with sample halls and link the storage for images:
```bash
php artisan storage:link
php artisan db:seed --class=HallSeeder
```
*This will set up the necessary image links and add sample venues like 'Executive Boardroom' and 'Tech Training Center' to the database.*

### 9. Run the Application
Start the local development server:
```bash
php artisan serve
```
You can now access the application at: `http://127.0.0.1:8000`
