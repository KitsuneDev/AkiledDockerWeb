set windows-shell := ["powershell.exe", "-NoLogo", "-Command"]
default:
 @just --list
update:
	@git pull
setup:
	python3 -m pip install -r tools/requirements.txt
	python3 tools/envFile.py
	@echo "Setup is done."
	@echo "From now on, use the start command for start all services:"
	@echo "  just start"
	@echo "From now on, use the web for restart web server:"
	@echo "  just web"
	@echo "From now on, use the emu for restart emulator server:"
	@echo "  just emu"
	@echo "From now on, use the imager for restart imager server:"
	@echo "  just imager"
	@echo "and the stop command for stop all services:"
	@echo "  just stop"
createUser:
	python3 -m pip install mariadb
	docker compose up -d
	python3 tools/create_user.py
	docker compose down
start:
	@docker compose up -d
web:
	@docker compose stop web
	@docker compose start web
emu:
	@docker compose stop game
	@docker compose start game
imager:
	@docker compose stop imager
	@docker compose start imager
restart:
	@docker compose stop
	@docker system prune -f
	@docker compose build
	@docker compose up -d
	@sleep 5
	@docker compose restart imager


stop:
	@docker compose down
installUbuntuDeps:
	wget "https://r.mariadb.com/downloads/mariadb_repo_setup"
	chmod +x mariadb_repo_setup
	./mariadb_repo_setup --mariadb-server-version="mariadb-10.6"
	rm mariadb
	apt update
	apt install -y libmariadb3 libmariadb-dev
