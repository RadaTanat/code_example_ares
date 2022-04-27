<?php

/**
 * Homepage - default presneter class for reagistration of new corporation using Registration form factory
 */

declare(strict_types=1);

namespace App\Presenters;

use Nette;
use Nette\Application\UI\Form;
use App\Forms\RegistrationFormFactory;

class HomepagePresenter extends \Nette\Application\UI\Presenter
{
	private $database;

	/** @var RegistrationFormFactory @inject */
	public $RegistrationFormFactory;

	public function __construct(\Nette\Database\Context $database)
	{
		$this->database = $database;
	}

	public function renderDefault() {

	}
	
	/**
	 * Registration form factory.
	 * @return Nette\Application\UI\Form
	 */
	protected function createComponentRegistrationForm() {
		$form = $this->RegistrationFormFactory->create();

		$form->onSuccess[] = function ($form) { // save corp. data to databes
			$values = $form->getValues(TRUE);
			$this->database->table('corporation')->insert([$values]);
			$this->redirect('Corporations:'); // when is new corp. inserted to database redirect to Corporations presenter for listing all inserted corporations
		};
		$form->onError[] = function ($form) {
			// error status
		};
		return $form;
	}
}