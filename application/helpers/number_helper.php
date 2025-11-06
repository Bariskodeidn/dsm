<?php

/**
 * CodeIgniter
 *
 * An open source application development framework for PHP
 *
 * This content is released under the MIT License (MIT)
 *
 * Copyright (c) 2014 - 2018, British Columbia Institute of Technology
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 *
 * @package	CodeIgniter
 * @author	EllisLab Dev Team
 * @copyright	Copyright (c) 2008 - 2014, EllisLab, Inc. (https://ellislab.com/)
 * @copyright	Copyright (c) 2014 - 2018, British Columbia Institute of Technology (http://bcit.ca/)
 * @license	http://opensource.org/licenses/MIT	MIT License
 * @link	https://codeigniter.com
 * @since	Version 1.0.0
 * @filesource
 */
defined('BASEPATH') or exit('No direct script access allowed');

/**
 * CodeIgniter Number Helpers
 *
 * @package		CodeIgniter
 * @subpackage	Helpers
 * @category	Helpers
 * @author		EllisLab Dev Team
 * @link		https://codeigniter.com/user_guide/helpers/number_helper.html
 */

// ------------------------------------------------------------------------

if (!function_exists('byte_format')) {
	/**
	 * Formats a numbers as bytes, based on size, and adds the appropriate suffix
	 *
	 * @param	mixed	will be cast as int
	 * @param	int
	 * @return	string
	 */
	function byte_format($num, $precision = 1)
	{
		$CI = &get_instance();
		$CI->lang->load('number');

		if ($num >= 1000000000000) {
			$num = round($num / 1099511627776, $precision);
			$unit = $CI->lang->line('terabyte_abbr');
		} elseif ($num >= 1000000000) {
			$num = round($num / 1073741824, $precision);
			$unit = $CI->lang->line('gigabyte_abbr');
		} elseif ($num >= 1000000) {
			$num = round($num / 1048576, $precision);
			$unit = $CI->lang->line('megabyte_abbr');
		} elseif ($num >= 1000) {
			$num = round($num / 1024, $precision);
			$unit = $CI->lang->line('kilobyte_abbr');
		} else {
			$unit = $CI->lang->line('bytes');
			return number_format($num) . ' ' . $unit;
		}

		return number_format($num, $precision) . ' ' . $unit;
	}
}

if (!function_exists('terbilang')) {
	function terbilang($angka)
	{
		$angka = floatval($angka);
		$bilangan = [
			'',
			'Satu',
			'Dua',
			'Tiga',
			'Empat',
			'Lima',
			'Enam',
			'Tujuh',
			'Delapan',
			'Sembilan',
			'Sepuluh',
			'Sebelas'
		];

		if ($angka < 12) {
			return $bilangan[$angka];
		} else if ($angka < 20) {
			return $bilangan[$angka - 10] . ' Belas';
		} else if ($angka < 100) {
			return $bilangan[floor($angka / 10)] . ' Puluh ' . $bilangan[$angka % 10];
		} else if ($angka < 200) {
			return 'Seratus ' . terbilang($angka - 100);
		} else if ($angka < 1000) {
			return $bilangan[floor($angka / 100)] . ' Ratus ' . terbilang($angka % 100);
		} else if ($angka < 2000) {
			return 'Seribu ' . terbilang($angka - 1000);
		} else if ($angka < 1000000) {
			return terbilang(floor($angka / 1000)) . ' Ribu ' . terbilang($angka % 1000);
		} else if ($angka < 1000000000) {
			return terbilang(floor($angka / 1000000)) . ' Juta ' . terbilang($angka % 1000000);
		} else {
			return 'Angka terlalu besar';
		}
	}
}

function intToRoman($num)
{
	$mapping = [
		1000 => 'M',
		900 => 'CM',
		500 => 'D',
		400 => 'CD',
		100 => 'C',
		90 => 'XC',
		50 => 'L',
		40 => 'XL',
		10 => 'X',
		9 => 'IX',
		5 => 'V',
		4 => 'IV',
		1 => 'I'
	];

	$result = '';

	foreach ($mapping as $value => $roman) {
		while ($num >= $value) {
			$result .= $roman;
			$num -= $value;
		}
	}

	return $result;
}
