Soceanic
========

Final project for Spring 2017 Databases and GUI

Dev Environment Set Up Commands
-------------------------------

### Starting the vagrant box

Within the top level folder, type 'vagrant up' to start the vagrant box.

### Installation Commands

1.	PHP: sudo apt-get install php7.0
2.	MySQL: sudo apt-get install -y mysql-server
3.	PHP My Admin: sudo apt-get install phpmyadmin

Note: To get access to phpmyadmin, add the line 'Include /etc/phpmyadmin/apache.conf' to the end of the /etc/apache2/apache2.conf file

AWS
---

Connecting to the test server through your terminal: ssh -i "soceanic.pem" ubuntu@ec2-34-208-214-73.us-west-2.compute.amazonaws.com

I recommended going into your .bash_profile and adding an alias for it

DO NOT DEVELOP ON THE TEST SERVER!!! SHOULD ONLY BE USED TO PULL AND TEST!!!
