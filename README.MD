## Setup
For install the project run: ```make install```   
After install check http://localhost:8888/  
Another usefully commands you can find in _Makefile_.
## Technologies used
1. PHP 8.2
2. Nginx
3. Mysql 5.7
4. Docker / Docker-compose
5. Symfony 6
6. Google chart js library
7. Bootstrap
8. Twig
## Project parts
1. Dashboard
2. Admin
3. Console command for generate measurement data
## Dashboard part
You can find it on http://localhost:8888/dashboard route.  
There will be some dashboards (each to module) with data. Dashboard shows data for last 5 minutes grouped to batches in 15 second. It shows erros (if in measurement value === null) and average value in this batch. Also it has block of information with data about measurement: about last tic and all data at least 5 minutes.
Status _Is working_ means that in last batch at least one measurement was with data. If all measurements with null value it will be _Is not working_. It works for last batch.
_Working time_ shows how many times module was active. Module is active if in batch was at least one success measurement.

## Admin part
You can find it on http://localhost:8888/admin route.
In admin part you can create, delete, update, show _module_ and _measurement unit_. And see list of measurements. After creating _module_ and _measurement unit_ you can see empty data on dashboard page.
## Generate measurements part
For generationg data for _10 minutes_ run ```make generate-measurements```. It generates random data for modules. While it works, please, update page with dashboards and you will see evolution of average velue and errors count.