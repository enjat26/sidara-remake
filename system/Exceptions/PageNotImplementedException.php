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

class PageNotImplementedException extends OutOfBoundsException implements ExceptionInterface
{
	use DebugTraceableTrait;

	/**
	 * Error code
	 *
	 * @var integer
	 */
	protected $code = 501;

	public static function forPageComingSoon(string $message = null)
	{
		return new static($message ?? lang('HTTP.pageComingSoon'));
	}

}
