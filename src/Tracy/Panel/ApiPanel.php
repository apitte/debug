<?php declare(strict_types = 1);

namespace Apitte\Debug\Tracy\Panel;

use Apitte\Core\Schema\Schema;
use Tracy\IBarPanel;

final class ApiPanel implements IBarPanel
{

	/** @var Schema */
	private $schema;

	public function __construct(Schema $schema)
	{
		$this->schema = $schema;
	}

	/**
	 * Renders HTML code for custom tab.
	 */
	public function getTab(): string
	{
		ob_start();
		$schema = $this->schema;
		require __DIR__ . '/templates/tab.phtml';

		return ob_get_clean();
	}

	/**
	 * Renders HTML code for custom panel.
	 */
	public function getPanel(): string
	{
		ob_start();
		$schema = $this->schema;
		require __DIR__ . '/templates/panel.phtml';

		return ob_get_clean();
	}

}
