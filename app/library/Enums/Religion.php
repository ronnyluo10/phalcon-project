<?php

final class Religion {
	const MOSLEM = 1;
	const CHRISTIAN = 2;
	const CATHOLIC = 3;
	const HINDU = 4;
	const BUDDHA = 5;
	const KHONGHUCU = 6;

	protected static function getList()
	{
		return [
			Religion::MOSLEM,
			Religion::CHRISTIAN,
			Religion::CATHOLIC,
			Religion::HINDU,
			Religion::BUDDHA,
			Religion::KHONGHUCU,
		];
	}

	public static function getArray()
	{
		$results = [];

		foreach (self::getList() as $key => $value) {
			$results[$value] = self::getString($value);
		}

		return $results;
	}

	public static function getString($value)
	{
		switch ($value) {
			case 1:
				return 'Moslem';
			case 2:
				return 'Christian';
			case 3:
				return 'Catholic';
			case 4:
				return 'Hindu';
			case 5:
				return 'Buddha';
			case 6:
				return 'Khonghucu';
		}
	}
}