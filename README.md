To start the project run 'docker compose up -d --build' <br/>
If something goes wrong, run 'docker compose down' and then 'docker system prune -a' that will delete all containers to start from 'fresh start'.<br/>
If you were runnig kubernetes before you need to delete all images in kubernetes context too because there are sometimes conflicts between images and containers. <br/>
Very rarely but if you on Ubuntu sometimes you eve need to restart it and then run 'docker system prune -a'  <br/>
Put '127.0.0.1       test_task_2.com' into /etc/hosts. <br/>
To get into fpm container 'docker exec -it test_task_2-fpm-1 bash' <br/>
run composer install in container <br/>
all the code is implemented in TestCommand.php <br/>
to run command enter inside container: bin/console app:test and it will end up with error  <br/> 
because input data has error in one of bin codes and API endpoints have limitations <br/>
For instance https://lookup.binlist.net/ do not allow more than 2 requests from one IP <br/>
That is why there are two flags: -u and -i.  <br/>
If you run command "bin/console app:test -u" then it will use locally saved data without requesting APIs.  <br/>
But still it will use input data with one wrong bin code so error will appear. <br/>
If you run command "bin/console app:test -u -i" then it will not only use locally saved data but also input file without mistakes.  <br/>
So the command will run smoothly without errors.
For http://api.exchangeratesapi.io I had to register my account. So you'll see my API key in code which is the wort practice, <br/>
but I did not want to spend to much time to put it into environment.
Original test task: https://gist.github.com/naymkazp/87112812d3e273083979f3e36035e1e9
