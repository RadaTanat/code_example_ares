<?php

/**
 * Form foactory for registration of new corporation  
 */

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;


class RegistrationFormFactory {
	/** @var FormFactory */
	private $factory;
	
	/** @var Database  */
	protected $database;



	public function __construct(FormFactory $factory, Nette\Database\Context $database)
	{
		$this->factory = $factory;
		$this->database = $database;
	}


	/**
	 * @return Form
	 */
	public function create()
	{
		$form = $this->factory->create();

		$form->addText('name', 'Název firmy:', 30)->setRequired('Vyplňte Název firmy.');
		$form->addText('address', 'Adresa sídla:', 30)->setRequired('Vyplňte adresu sídla.');
		$form->addText('ico', 'IČO:', 30)->setRequired('Vyplňte IČO.');
		$form->addText('dic', 'DIČ:', 30)->setRequired('Vyplňte DIČ.');
		$form->addText('statutory_name', 'Jméno statutárního zástupce:', 30)->setRequired('Vyplňte jméno statutárního zástupce.');
		$form->addEmail('statutory_email', 'E-mailová adresa statutárního zástupce:')->setAttribute('size', 30);
		$form->addSubmit('send', 'Uložit');
	
		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{

	}
	
}
