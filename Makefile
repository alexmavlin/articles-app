up:
	docker-compose up -d
down:
	docker-compose down
nginx-reload:
	docker exec nginx nginx -s reload
bash:
	docker exec -it php bash