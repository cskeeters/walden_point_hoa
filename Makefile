ENGINE=docker
IMAGE=slim_app
APP_NAME=walden-point

# For running on a port
#NET_FLAGS=-p 8080:80
#NET_FLAGS=-p 127.0.0.1:8080:80

# In order to run behind nginx proxy manager (NPM), this container will net to be configured on the same docker network as NPM.
NETWORK=servers
IP=192.168.0.20
NET_FLAGS=--network $(NETWORK) --ip $(IP)

.PHONY: default debug bash stop

default:
	$(ENGINE) run -d $(NET_FLAGS) -v $(PWD)/app:/var/www/app -v $(PWD)/logs:/var/log/app --name $(APP_NAME) $(IMAGE) serve

debug:
	$(ENGINE) run -it --rm $(NET_FLAGS) -v $(PWD)/app:/var/www/app -v $(PWD)/logs:/var/log/app --name $(APP_NAME) $(IMAGE) serve

bash:
	$(ENGINE) run -it --rm -v $(PWD)/app:/var/www/app -v $(PWD)/logs:/var/log/app --name $(APP_NAME) $(IMAGE) bash

stop:
	$(ENGINE) container rm -f $(APP_NAME)
