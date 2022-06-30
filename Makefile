.PHONY: phpcs phpcs-fix

phpcs:
	./vendor/bin/phpcs flat-preloader.php flat-preloader-settings.php
phpcs-fix:
	./vendor/bin/phpcbf flat-preloader.php flat-preloader-settings.php
