<?php

final class Gender {
	const MALE = 1;
	const FEMALE = 2;

	protected static function getList()
	{
		return [
			Gender::MALE,
			Gender::FEMALE,
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
				return 'Male';
			case 2:
				return 'Female';
		}
	}
}