# mail-to-ntfy

This is a simple plugin to send [ntfy.sh](https://ntfy.sh) notifications from wordpress
when an email is triggered on the system.

## Installation

Create a directory in your wordpress dirctory:

`    wordpress/wp-content/plugins/mail-to-ntfy/`

Copy the files:

`    cp index.php /var/www/wordpress/wp-content/plugins/mail-to-ntfy/`
`    cp mail-to-ntfy.php  /var/www/wordpress/wp-content/plugins/mail-to-ntfy/`

Restart webserver:

`    sudo systemctl restart apache2.service`

Configuration:

    useing an Adminisrator account set the ntfy.sh channel where you want the notifications to go to.

