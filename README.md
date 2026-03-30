# MYSQL Backupper Script

This script creates a backup of a MySQL database and uploads it to an AWS S3 bucket.

Please follow the instructions below to set up the environment variables and run this script.

1. Install dependencies using Composer

`composer install`

2. Run the following command to create a copy of the example environment file:

`cp .env.example .env`

3. Open the .env file and fill in the required values for your database and AWS credentials:

`**DATABASE_PORT**: The port number of your MySQL database (e.g., 3306)`
`**DATABASE_USER**: The username for your MySQL database`
`**DATABASE_PASSWORD**: The password for your MySQL database`
`**DATABASE_NAME**: The name of the database you want to back up`
`**AWS_REGION**: The AWS region where your S3 bucket is located (e.g., us-east-1)`
`**AWS_ACCESS_KEY_ID**: Your AWS access key ID`
`**AWS_SECRET_ACCESS_KEY**: Your AWS secret access key`
`**AWS_BUCKET_NAME**: The name of your S3 bucket where the backup will be uploaded`

3. Save the .env file and run the script

`php backupper.php`

4. Add this script to a cron job to automate the backup process. For example, to run the backup every day at 2 AM, you can add the following line to your crontab

`0 2 * * * /usr/bin/php /path/to/backupper.php`