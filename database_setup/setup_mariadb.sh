#!/bin/bash

# Variables
ROOT_PASSWORD="UApass50"
STUDENT_USER="student"
STUDENT_PASSWORD="UApass50"
DB_NAME="EventFinderDB"
CREATE_SQL_PATH="/home/swhite/Desktop/EventFinderWebApp/EventFinderApp/database_setup/create.sql" #Update with where you have create.sql  

print_message() {
    echo "====================================================================="
    echo "$1"
    echo "====================================================================="
}

is_mariadb_installed() {
    if command -v mysql >/dev/null 2>&1; then
        return 0  # MariaDB is installed
    else
        return 1  # MariaDB is not installed
    fi
}

# Step 1: Check if MariaDB is installed
if is_mariadb_installed; then
    print_message "MariaDB is already installed. Proceeding with database setup..."
else
    print_message "MariaDB is not installed. Installing MariaDB..."
    sudo zypper install -y mariadb mariadb-client

    # Start and enable MariaDB
    print_message "Starting and enabling MariaDB..."
    sudo systemctl start mariadb
    sudo systemctl enable mariadb

    # Secure MariaDB installation (set root password)
    print_message "Securing MariaDB installation..."
    sudo mysql_secure_installation <<EOF

y
$ROOT_PASSWORD
$ROOT_PASSWORD
y
y
y
y
EOF
fi

if [ -f "$CREATE_SQL_PATH" ]; then
    print_message "Loading the schema from create.sql..."
    sudo mysql -u root -p"$ROOT_PASSWORD" < "$CREATE_SQL_PATH"
else
    echo "Error: create.sql file not found at $CREATE_SQL_PATH"
    exit 1
fi

print_message "Restarting MariaDB..."
sudo systemctl restart mariadb

print_message "MariaDB setup is complete! Database: $DB_NAME, User: $STUDENT_USER"

