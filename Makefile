.PHONY: phpcs phpcs-fix

phpcs:
	./vendor/bin/phpcs includes/ flat-preloader.php flat-preloader-settings.php
phpcs-fix:
	./vendor/bin/phpcbf includes/ flat-preloader.php flat-preloader-settings.php
