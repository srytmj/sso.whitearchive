.PHONY: help sync deploy update

help:
	@echo "Available commands:"
	@echo "  make sync     Sync stack dari docs/SRS.md ke .claude/CLAUDE.md"
	@echo "  make deploy   Jalankan deploy wizard pertama kali di server"
	@echo "  make update   Pull kode terbaru dari GitHub + rebuild"

sync:
	bash sync.sh

deploy:
	sudo bash scripts/deploy.sh

update:
	bash scripts/update.sh
