<?php

declare(strict_types=1);

namespace App\Modules\Module\Domain\Repository;

use App\Modules\Module\Domain\Entity\Module;

interface ModuleRepositoryInterface
{
	/**
	 *	@return ?array<Module>
	 *
	 *	@param	string $id
	 *	@param	mixed $lockMode
	 *	@param	mixed $lockVersion
	 *
	 *	@todo	Update parameters with proper types
	 *			once they are determined.
	 */
	public function find($id, $lockMode = null, $lockVersion = null);

	public function findAll();

	public function save(Module $module): void;
}
