To start the project run 'docker compose up -d --build' <br/>
If something goes wrong, run 'docker compose down' and then 'docker system prune -a' that will delete all containers to start from 'fresh start'.<br/>
If you were runnig kubernetes before you need to delete all images in kubernetes context too because there are sometimes conflicts between images and containers. <br/>
Very rarely but if you on Ubuntu sometimes you eve need to restart it and then run 'docker system prune -a'  <br/>
Put '127.0.0.1       test_task_2.com' into /etc/hosts. <br/>
To get into fpm container 'docker exec -it test_task_2-fpm-1 bash' <br/>
cd app, yarn install, yarn build or yarn watch <br/>
You'll see your app on http://test_task_2.com/home (not https://test_task_2.com/home)<br/>

http://api.exchangeratesapi.io/v1/latest?access_key=0b9ab5147fa06aab3cdb30e89c5ffdfc&format=1
