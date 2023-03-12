set windows-shell := ["powershell.exe", "-NoLogo", "-Command"]
default:
  @just --list
setup:
	python3 -m pip install -r tools/requirements.txt
	python3 tools/envFile.py
	docker compose up -d
	python3 tools/create_user.py
	docker compose down
	@echo "Setup is done."
	@echo "From now on, use the start command:"
	@echo "  just start"
	@echo "and the stop command:"
	@echo "  just stop"
start:
	@docker compose up -d
stop:
	@docker compose down
