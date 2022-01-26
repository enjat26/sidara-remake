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

class PageForbiddenException extends OutOfBoundsException implements ExceptionInterface
{
	use DebugTraceableTrait;

	/**
	 * Error code
	 *
	 * @var integer
	 */
	protected $code = 403;

	public static function forPageForbidden(string $message = null)
	{
		return new static($message ?? lang('HTTP.pageForbidden'));
	}

	public static function forControllerForbidden(string $controller, string $method)
	{
		return new static(lang('HTTP.controllerForbidden', [$controller, $method]));
	}

	public static function forMethodForbidden(string $method)
	{
		return new static(lang('HTTP.methodForbidden', [$method]));
	}
	
}
