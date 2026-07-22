.PHONY: help sync deploy update ssh remote-deploy remote-update

# Load SSH config dari .env jika ada
-include .env
SERVER_USER ?= ubuntu
SERVER_PATH ?= /var/www/sso

help:
	@echo "Available commands:"
	@echo "  make sync           Sync stack dari docs/SRS.md ke .claude/CLAUDE.md"
	@echo ""
	@echo "  -- Di server (jalankan setelah SSH) --"
	@echo "  make deploy         First-time deploy wizard di server"
	@echo "  make update         Pull latest + rebuild di server"
	@echo ""
	@echo "  -- Dari lokal ke server (butuh SERVER_HOST di .env) --"
	@echo "  make ssh            SSH ke server"
	@echo "  make remote-deploy  First-time deploy via SSH dari lokal"
	@echo "  make remote-update  Update server via SSH dari lokal"

sync:
	bash sync.sh

deploy:
	sudo bash scripts/deploy.sh

update:
	bash scripts/update.sh

ssh:
	@if [ -z "$(SERVER_HOST)" ]; then echo "Error: SERVER_HOST belum diset di .env"; exit 1; fi
	ssh -i $(SSH_KEY_PATH) $(SERVER_USER)@$(SERVER_HOST)

remote-deploy:
	@if [ -z "$(SERVER_HOST)" ]; then echo "Error: SERVER_HOST belum diset di .env"; exit 1; fi
	ssh -i $(SSH_KEY_PATH) $(SERVER_USER)@$(SERVER_HOST) "cd $(SERVER_PATH) && sudo bash scripts/deploy.sh"

remote-update:
	@if [ -z "$(SERVER_HOST)" ]; then echo "Error: SERVER_HOST belum diset di .env"; exit 1; fi
	ssh -i $(SSH_KEY_PATH) $(SERVER_USER)@$(SERVER_HOST) "cd $(SERVER_PATH) && bash scripts/update.sh"
