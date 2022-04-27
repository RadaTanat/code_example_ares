<?php

/**
 * Form foactory for registration of new corporation via IČO  
 */

namespace App\Forms;

use Nette;
use Nette\Application\UI\Form;


class RegistrationicoFormFactory {
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

		// only "ico" (IČO) is writeable input
		$form->addText('ico', 'IČO:', 30)->setRequired('Vyplňte IČO.')->setAttribute('placeholder', 'Zadejte 8-místné ident. číslo firmy');
		
		// others inputs are with attribute "readonly" - these inputs will be set automatically by "ico" input 
		$form->addText('name', 'Název firmy:', 30)->setAttribute('readonly')->getControlPrototype()->addClass('readonly_input');
		$form->addText('address', 'Adresa sídla:', 30)->setAttribute('readonly')->getControlPrototype()->addClass('readonly_input');
		$form->addText('dic', 'DIČ:', 30)->setAttribute('readonly')->getControlPrototype()->addClass('readonly_input');
		$form->addText('statutory_name', 'Jméno statutárního zástupce:', 30)->setAttribute('readonly')->getControlPrototype()->addClass('readonly_input');
		$form->addEmail('statutory_email', 'E-mailová adresa statutárního zástupce:')->setAttribute('readonly')->getControlPrototype()->addClass('readonly_input');
		//

		$form->addSubmit('send', 'Uložit');
	
		$form->onSuccess[] = [$this, 'formSucceeded'];
		return $form;
	}


	public function formSucceeded(Form $form, $values)
	{

	}
	
}
