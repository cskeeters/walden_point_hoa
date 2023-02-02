ENGINE=docker
IMAGE=php_app
APP_NAME=example
RUN_FLAGS=-it --rm
#RUN_FLAGS=-d
PORT=8000

default:
	$(ENGINE) run -d -p $(PORT):80 -v $(PWD)/app:/var/www/app -v $(PWD)/logs:/var/log/app --name $(APP_NAME) $(IMAGE) serve

debug:
	$(ENGINE) run -it --rm -p $(PORT):80 -v $(PWD)/app:/var/www/app -v $(PWD)/logs:/var/log/app --name $(APP_NAME) $(IMAGE) serve

bash:
	$(ENGINE) run $(RUN_FLAGS) -v $(PWD)/app:/var/www/app -v $(PWD)/logs:/var/log/app --name $(APP_NAME) $(IMAGE) bash

stop:
	$(ENGINE) container rm -f $(APP_NAME)
