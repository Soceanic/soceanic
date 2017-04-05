Soceanic
========

Final project for Spring 2017 Databases and GUI

Dev Environment Set Up Commands
-------------------------------

### Starting the vagrant box

Within the top level folder, type 'vagrant up' to start the vagrant box.

### Installation Commands

1.	MySQL: sudo apt-get install -y mysql-server

### Giving vagrant access to /www/data

1.	sudo adduser vagrant www-data
2.	sudo chown -R www-data:www-data /var/www
3.	sudo chmod -R g+rw /var/www

### Set up our Github folder

1.	cd /var/www/html
2.	git clone https://github.com/Soceanic/soceanic.git

AWS
---

Connecting to the test server through your terminal: ssh -i "soceanic.pem" ubuntu@ec2-34-208-214-73.us-west-2.compute.amazonaws.com

I recommended going into your .bash_profile and adding an alias for it

DO NOT DEVELOP ON THE TEST SERVER!!! SHOULD ONLY BE USED TO PULL AND TEST!!!
