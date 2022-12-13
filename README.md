# Welcome!

This attempts to calculate the value for the quantity required if the required amount is available.
If not, it will send back a message mentioning the required quantity is greater than the available amount.


# Installation
Follow the below steps to set up the app.

1. Clone the repository
> git clone git@github.com:elabuwa/figured.git figured

2. Go to the installed directory
> cd figured

3. Install composer and npm packages
> composer install
> npm install

4. Copy and rename the .env.example file to .env and update the DB credentials
5. Generate a new key
> php artisan key:generate

6. Build assets
> npm run build

7. Run the app
> php artisan serve

# The works
This uses a service pattern to carry out the required tasks once the process is handed off by the controller.
The methods of the service will handle only one designated task.
