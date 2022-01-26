<?php

/**
 * This file is part of the CodeIgniter 4 framework.
 *
 * (c) CodeIgniter Foundation <admin@codeigniter.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace CodeIgniter\Exceptions;

use OutOfBoundsException;

class PageServiceUnavaibleException extends OutOfBoundsException implements ExceptionInterface
{
	use DebugTraceableTrait;

	/**
	 * Error code
	 *
	 * @var integer
	 */
	protected $code = 503;

	public static function forPageMaintenance(string $message = null)
	{
		return new static($message ?? lang('HTTP.pageMaintenance'));
	}
	
}
