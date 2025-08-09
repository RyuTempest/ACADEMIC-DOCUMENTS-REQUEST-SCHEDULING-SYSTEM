# Academic Documents Request Scheduling System

## Description
The **Academic Documents Request Scheduling System** is a web-based platform designed to streamline the process of requesting, scheduling, and managing academic documents. This system aims to enhance efficiency, reduce manual effort, and provide a seamless experience for students, administrators, and staff. It includes features such as document request management, appointment scheduling, and automated notifications.

## Features
- **Document Request Management**: Submit, track, and manage requests for academic documents such as transcripts, certifications, and diplomas.
- **Scheduling System**: Schedule appointments for document pickup or processing, ensuring an organized workflow.
- **User Roles**:
  - **Students**: Submit requests and track their status.
  - **Administrators**: Manage requests, schedules, and generate reports.
  - **Staff**: Process and fulfill document requests.
- **Notifications**: Automated email or SMS notifications to keep users updated on the status of their requests.
- **Reports**: Generate detailed reports for administrative purposes, including request statistics and user activity logs.
- **Secure Authentication**: Password-protected accounts with email-based password recovery.
- **Responsive Design**: Optimized for both desktop and mobile devices.

## Technologies Used
- **Backend**: PHP (mysqli)
- **Database**: MySQL/MariaDB
- **Frontend**: Bootstrap, Tailwind CSS, FullCalendar, jQuery, SweetAlert2
- **Composer Packages**:
  - `twilio/sdk` (optional SMS integration)
  - `google/apiclient` (optional future integrations)
  - `phpmailer/phpmailer` (email for password reset)
- **Web Server**: Apache or Nginx (Apache with PHP module recommended)
- **Version Control**: Git and GitHub for source code management

## Installation
Follow these steps to set up the project on your local machine:

1. **Clone the Repository**:
   ```bash
   git clone https://github.com/RyuTempest/ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM.git
   ```

2. **Navigate to the Project Directory**:
   ```bash
   cd ACADEMIC-DOCUMENTS-REQUEST-SCHEDULING-SYSTEM
   ```

3. **Install Composer Dependencies**:
   Ensure you have [Composer](https://getcomposer.org/) installed, then run:
   ```bash
   composer install
   ```

4. **Set Up the Database**:
   - Create a new MySQL/MariaDB database.
   - Import the provided SQL file (`database.sql`) located in the `database` folder:
     ```sql
     mysql -u [username] -p [database_name] < database/database.sql
     ```

5. **Configure Environment Variables**:
   - Create a `.env` file in the root directory.
   - Add the following variables:
     ```env
     DB_HOST=localhost
     DB_NAME=[your_database_name]
     DB_USER=[your_database_user]
     DB_PASS=[your_database_password]
     SMTP_HOST=[your_smtp_host]
     SMTP_USER=[your_smtp_user]
     SMTP_PASS=[your_smtp_password]
     TWILIO_SID=[your_twilio_sid] # Optional
     TWILIO_TOKEN=[your_twilio_token] # Optional
     ```

6. **Run the Application**:
   - Deploy the project to a local or remote server with PHP support.
   - Access the application in your browser at `http://localhost/[project-folder]`.

## Usage
1. **Access the Application**:
   - Open the application in your browser at the configured URL.
2. **User Registration and Login**:
   - Students can register for an account or log in using their credentials.
3. **Submit Requests**:
   - Students can submit requests for academic documents and track their status.
4. **Administrative Management**:
   - Administrators can view, approve, or reject requests, manage schedules, and generate reports.
5. **Notifications**:
   - Users receive email or SMS notifications for updates on their requests.

## Folder Structure
- **`/database`**: Contains the SQL file for database setup.
- **`/public`**: Publicly accessible files, including assets like CSS, JavaScript, and images.
- **`/src`**: Core application code, including controllers, models, and views.
- **`/vendor`**: Composer dependencies (generated after running `composer install`).

## Contributing
Contributions are welcome! To contribute:
1. Fork the repository.
2. Create a new branch:
   ```bash
   git checkout -b feature-name
   ```
3. Commit your changes:
   ```bash
   git commit -m "Add feature-name"
   ```
4. Push to your branch:
   ```bash
   git push origin feature-name
   ```
5. Open a pull request.

## License
This project is licensed under the [MIT License](LICENSE).

## Contact
For questions or feedback, please contact:
- **Name**: Ray Rafael M. Avila
- **Email**: avilarayrafael8@gmail.com
- **GitHub**: [RyuTempest](https://github.com/RyuTempest)
