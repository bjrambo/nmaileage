<?php

class nmileageView extends nmileage
{
	function init()
	{
		if($this->module_info->module != 'nmileage')
		{
			$this->module_info->skin = 'default';
		}
		if(!$this->module_info->skin)
		{
			$this->module_info->skin = 'default';
		}
		$skin = $this->module_info->skin;

		// 레이아웃 설정을 강제로 지정한다.
		$act = Context::get('act');
		if($act !== 'dispNmileageCharge')
		{
			$module_info = getModel('module')->getModuleInfoByMid('mileage');
			$layout_info = LayoutModel::getInstance()->getLayout($module_info->layout_srl);
			if($layout_info)
			{
				$this->module_info->layout_srl = $module_info->layout_srl;
				$this->setLayoutPath($layout_info->path);
			}
		}
		
		// 템플릿 경로 설정
		$this->setTemplatePath(sprintf('%sskins/%s', $this->module_path, $skin));
	}

	function dispNmileageMileageHistory()
	{
		$oNmileageModel = getModel('nmileage');

		$logged_info = Context::get('logged_info');

		if(!Context::get('is_logged'))
		{
			return new BaseObject(-1, "msg_login_required");
		}

		$args = new stdClass();
		$args->member_srl = $logged_info->member_srl;
		$args->page = Context::get('page');
		$args->regdate_more = $start_date;

		$output = executeQueryArray('nmileage.getMileageHistory', $args);

		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);

		Context::set('list', $output->data);
		Context::set('colorset', $this->module_info->colorset);

		$mileage = $oNmileageModel->getMileage($logged_info->member_srl);
		Context::set('mileage', $mileage);

		$this->setTemplateFile('mileagehistory');
	}

	function dispNmileageMyMileage()
	{
		$oNmileageModel = getModel('nmileage');

		$logged_info = Context::get('logged_info');

		$args = new stdClass();
		$args->member_srl = $logged_info->member_srl;
		$output = executeQueryArray('nmileage.getMileageHistory', $args);
		debugPrint($output);
		Context::set('list', $output->data);
		Context::set('total_count', $output->total_count);
		Context::set('total_page', $output->total_page);
		Context::set('page', $output->page);
		Context::set('page_navigation', $output->page_navigation);
		Context::set('colorset', 'transparent');

		$mileage = $oNmileageModel->getMileage($logged_info->member_srl);
		Context::set('mileage', $mileage);

		$this->setTemplateFile('mymileage');
	}
	
	public function dispNmileageCharge()
	{
		$order_id = 'HT'.str_pad(getNextSequence(), 4, "0", STR_PAD_LEFT);
		
		Context::set('order_id', $order_id);
		$this->setTemplateFile('charge');
	}
}
/* End of file nmileage.view.php */
/* Location: ./modules/nmileage/nmileage.view.php */
