<?php

/**
 * Presneter class for listing inserted corporations, using CorpFacade model class
 */

declare(strict_types=1);

namespace App\Presenters;

use App\Model\CorpFacade;
use Nette;


final class CorporationsPresenter extends Nette\Application\UI\Presenter
{
	private CorpFacade $facade;

	public function __construct(CorpFacade $facade)
	{
		$this->facade = $facade;
	}

	
	public function renderDefault(int $page = 1): void
	{
		$this->template->page = $page;
		$this->template->corporations = $this->facade
			->getCorporations()
			->page($page, 5);
	}

}
