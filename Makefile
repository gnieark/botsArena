.PHONY:update
update:
	git submodule init
	git submodule update
	git submodule foreach git reset --hard
	git submodule foreach git pull origin master
