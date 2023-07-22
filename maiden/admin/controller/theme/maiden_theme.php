<?php
namespace Opencart\Admin\Controller\Extension\Maiden\Theme;
class MaidenTheme extends \Opencart\System\Engine\Controller {
	public function index(): void {
		$this->load->language('extension/maiden/theme/maiden_theme');

		$this->document->setTitle($this->language->get('heading_title'));

		$data['breadcrumbs'] = [];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_home'),
			'href' => $this->url->link('common/dashboard', 'user_token=' . $this->session->data['user_token'])
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('text_extension'),
			'href' => $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme')
		];

		$data['breadcrumbs'][] = [
			'text' => $this->language->get('heading_title'),
			'href' => $this->url->link('extension/maiden/theme/maiden_theme', 'user_token=' . $this->session->data['user_token'])
		];

		$data['save'] = $this->url->link('extension/maiden/theme/maiden_theme.save', 'user_token=' . $this->session->data['user_token']);
		$data['back'] = $this->url->link('marketplace/extension', 'user_token=' . $this->session->data['user_token'] . '&type=theme');

		$data['maiden_theme_status'] = $this->config->get('maiden_theme_status');

		$data['header'] = $this->load->controller('common/header');
		$data['column_left'] = $this->load->controller('common/column_left');
		$data['footer'] = $this->load->controller('common/footer');

		$this->response->setOutput($this->load->view('extension/maiden/theme/maiden_theme', $data));
	}

	public function save(): void {
		$this->load->language('extension/maiden/theme/maiden_theme');

		$json = [];

		if (!$this->user->hasPermission('modify', 'extension/maiden/theme/maiden_theme')) {
			$json['error'] = $this->language->get('error_permission');
		}

		if (!$json) {
			$this->load->model('setting/setting');

			$this->model_setting_setting->editSetting('maiden_theme', $this->request->post);

			$json['success'] = $this->language->get('text_success');
		}

		$this->response->addHeader('Content-Type: application/json');
		$this->response->setOutput(json_encode($json));
	}

	public function install(): void {
		if ($this->user->hasPermission('modify', 'extension/theme')) {
			// Add startup to catalog
			$startup_data = [
				'code'        => 'maiden_theme',
				'description' => 'Maiden Theme extension',
				'action'      => 'catalog/extension/maiden/startup/maiden_theme',
				'status'      => 1,
				'sort_order'  => 2
			];

			// Add startup for admin
			$this->load->model('setting/startup');

			$this->model_setting_startup->addStartup($startup_data);
		}
	}

	public function uninstall(): void {
		if ($this->user->hasPermission('modify', 'extension/theme')) {
			$this->load->model('setting/startup');

			$this->model_setting_startup->deleteStartupByCode('maiden_theme');
		}
	}
}