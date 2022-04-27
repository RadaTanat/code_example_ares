<?php

/**
 * Model class for listing inserted corporations
 */

declare(strict_types=1);

namespace App\Model;

use Nette;

final class CorpFacade
{
	use Nette\SmartObject;

	private Nette\Database\Explorer $database;


	public function __construct(Nette\Database\Explorer $database)
	{
		$this->database = $database;
	}

	public function getCorporations()
	{
		return $this->database->table('corporation')
		->order('corp_id DESC');
	}
}
