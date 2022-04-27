<?php

namespace App\Forms;

use Nette;

class FormFactory {

	/**
	 * @return Form
	 */
	public function create() {
		return new Nette\Application\UI\Form;
	}

}
