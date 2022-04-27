<?php

/**
 * Registrationico presneter class for reagistration of new corporation via IČO, using Registrationico form factory
 */

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use App\Model\Ares;
use Nette\Application\UI\Form;
use App\Forms\RegistrationicoFormFactory;

class RegistrationicoPresenter extends \Nette\Application\UI\Presenter
{
	private $database;

	/** @var RegistrationicoFormFactory @inject */
	public $RegistrationicoFormFactory;

	public function __construct(\Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderDefault() {
		
	}

	// handle called via nette.ajax, redraw controls with snippet
	public function handleGetData($ico)
	{
		$ares = new \Ares(); // get new instance of Ares model class
		
		$corp_info = $ares->getDataByICO(intval($ico)); // get corp. data by "ico"
		
		if($corp_info === 0){ // if is result 0, ico is not correct, show the error
			$this->template->error_msg = 'IČO firmy nebylo v databázi ARES nalezeno';
			$this->redrawControl('s_error');
		}else if($corp_info === -1){ // if is result -1, database is not available, show the error
			$this->template->error_msg = 'Databáze ARES není dostupná';
			$this->redrawControl('s_error');
		}else{ // data from Ares model class are OK, set default values to inputs
			$this['registrationicoForm']['name']->setDefaultValue($corp_info['name']);
			$this['registrationicoForm']['address']->setDefaultValue($corp_info['address']);
			$this['registrationicoForm']['dic']->setDefaultValue($corp_info['dic']);
			$this['registrationicoForm']['statutory_name']->setDefaultValue($corp_info['statutory_name']);
			$this['registrationicoForm']['statutory_email']->setAttribute('readonly', false)->getControlPrototype()->addClass('writeable_input');

			// redraw controls with new data
			$this->redrawControl('s_name');
			$this->redrawControl('s_address');
			$this->redrawControl('s_dic');
			$this->redrawControl('s_statutory_name');
			$this->redrawControl('s_statutory_email');
		}
	}
 
	/**
	 * Registration form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentRegistrationicoForm() {
		$form = $this->RegistrationicoFormFactory->create();
		$form->onSuccess[] = function ($form) {
			$values = $form->getValues(TRUE);
			$this->database->table('corporation')->insert([$values]);
			$this->redirect('Corporations:');
		};
		$form->onError[] = function ($form) {
			// error status
		};
		return $form;
	}
}