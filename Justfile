set windows-shell := ["powershell.exe", "-NoLogo", "-Command"]
default:
  @just --list
setup:
	python3 -m pip install -r tools/requirements.txt
	python3 tools/envFile.py
	@echo "Setup is done."
	@echo "From now on, use the start command:"
	@echo "  just start"
	@echo "and the stop command:"
	@echo "  just stop"
createUser:
	python3 -m pip install mariadb
	docker compose up -d
	python3 tools/create_user.py
	docker compose down
start:
	@docker compose up -d
stop:
	@docker compose down
installUbuntuDeps:
	wget "https://r.mariadb.com/downloads/mariadb_repo_setup"
 	chmod +x mariadb_repo_setup
 	./mariadb_repo_setup --mariadb-server-version="mariadb-10.6"
 	rm mariadb
 	apt update
 	apt install -y libmariadb3 libmariadb-dev
