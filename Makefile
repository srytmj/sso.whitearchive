.PHONY: help sync deploy update ssh remote-deploy remote-update docker-up docker-down docker-build docker-fresh docker-shell docker-logs docker-prod-deploy docker-prod-update docker-prod-down docker-prod-logs docker-prod-shell

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
	@echo ""
	@echo "  -- Docker (lokal) --"
	@echo "  make docker-fresh   First-time setup: build + up + migrate + seed"
	@echo "  make docker-up      Start container (build jika perlu)"
	@echo "  make docker-down    Stop container"
	@echo "  make docker-build   Rebuild image (setelah ubah composer.json/package.json)"
	@echo "  make docker-shell   Masuk shell container app"
	@echo "  make docker-logs    Tail log semua container"
	@echo ""
	@echo "  -- Docker Production (homelab/Proxmox, butuh network 'proxy' Traefik) --"
	@echo "  make docker-prod-deploy  First-time deploy: build + up + migrate + seed"
	@echo "  make docker-prod-update  Update: pull + rebuild + migrate (data aman)"
	@echo "  make docker-prod-down    Stop container production"
	@echo "  make docker-prod-logs    Tail log container production"
	@echo "  make docker-prod-shell   Masuk shell container app production"

sync:
	bash sync.sh

deploy:
	sudo bash scripts/deploy.sh

update:
	bash scripts/deploy.sh

ssh:
	@if [ -z "$(SERVER_HOST)" ]; then echo "Error: SERVER_HOST belum diset di .env"; exit 1; fi
	ssh -i $(SSH_KEY_PATH) $(SERVER_USER)@$(SERVER_HOST)

remote-deploy:
	@if [ -z "$(SERVER_HOST)" ]; then echo "Error: SERVER_HOST belum diset di .env"; exit 1; fi
	ssh -i $(SSH_KEY_PATH) $(SERVER_USER)@$(SERVER_HOST) "cd $(SERVER_PATH) && sudo bash scripts/deploy.sh"

remote-update:
	@if [ -z "$(SERVER_HOST)" ]; then echo "Error: SERVER_HOST belum diset di .env"; exit 1; fi
	ssh -i $(SSH_KEY_PATH) $(SERVER_USER)@$(SERVER_HOST) "cd $(SERVER_PATH) && bash scripts/deploy.sh"

docker-fresh:
	@if [ ! -f .env ]; then cp .env.example .env; fi
	docker compose up -d --build
	docker compose exec app php artisan key:generate
	docker compose exec app php artisan migrate:fresh --force
	docker compose exec app php artisan passport:keys --force
	docker compose exec app php artisan db:seed --force
	@echo "Selesai. Akses di http://localhost:8000"

docker-up:
	docker compose up -d

docker-down:
	docker compose down

docker-build:
	docker compose up -d --build

docker-shell:
	docker compose exec app sh

docker-logs:
	docker compose logs -f

docker-prod-deploy:
	@if [ ! -f .env ]; then echo "Error: .env belum ada. Copy dari .env.example dan isi dulu."; exit 1; fi
	@if ! docker network inspect proxy >/dev/null 2>&1; then echo "Error: network 'proxy' (Traefik) belum ada. Buat dulu: docker network create proxy"; exit 1; fi
	docker compose -f docker-compose.prod.yml up -d --build
	docker compose -f docker-compose.prod.yml exec app php artisan key:generate
	docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
	docker compose -f docker-compose.prod.yml exec app php artisan passport:keys --force
	docker compose -f docker-compose.prod.yml exec app php artisan db:seed --force
	docker compose -f docker-compose.prod.yml exec app php artisan config:cache
	docker compose -f docker-compose.prod.yml exec app php artisan route:cache
	docker compose -f docker-compose.prod.yml exec app php artisan view:cache
	@echo "Selesai. Cek routing Traefik untuk domain yang dikonfigurasi di docker-compose.prod.yml."

docker-prod-update:
	git pull origin main
	docker compose -f docker-compose.prod.yml up -d --build
	docker compose -f docker-compose.prod.yml exec app php artisan migrate --force
	docker compose -f docker-compose.prod.yml exec app php artisan config:cache
	docker compose -f docker-compose.prod.yml exec app php artisan route:cache
	docker compose -f docker-compose.prod.yml exec app php artisan view:cache

docker-prod-down:
	docker compose -f docker-compose.prod.yml down

docker-prod-logs:
	docker compose -f docker-compose.prod.yml logs -f

docker-prod-shell:
	docker compose -f docker-compose.prod.yml exec app sh
