<?php
namespace Opencart\Catalog\Controller\Extension\Maiden\Startup;
class MaidenTheme extends \Opencart\System\Engine\Controller {
	public function index(): void {
		if ($this->config->get('maiden_theme_status')) {
			// Add event via code instead of DB
			// Could also just set view/common/header/before
			$this->event->register('view/*/before', new \Opencart\System\Engine\Action('extension/maiden/startup/maiden_theme.event'));
		}
	}

	public function event(string &$route, array &$args, mixed &$output): void {
		$override = ['common/header'];

		if (in_array($route, $override)) {
			$route = 'extension/maiden/' . $route;
		}
	}
}