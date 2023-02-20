<?php

use Phalcon\Http\Request;

class Datatables {
	public static function of($builder) 
	{
		return self::make($builder);
	}

	protected static function make($builder)
	{
		$args = func_get_args();
		return call_user_func_array([new self, 'query'], $args);
	}

	protected function query($builder)
	{
		$request = $this->getRequest();
		$columns = $request->get("tbody");
		$sort = $request->get('sort');
		$offset = $request->get("offset");
		$filters = $request->get('filters');
		$disableSearch = $request->get('disableSearch') ? $request->get('disableSearch') : [];
		$limit = 10;
		$results = [];

		if($search = $request->get("search")) {
			foreach ($columns as $key => $column) {
				if(!in_array($column, $disableSearch)) {
					$builder->orWhere($column.' LIKE "%'.$search.'%"');
				}
			}
		}

		if($filters && count($filters) > 0) {
			foreach ($filters as $filterKey => $filter) {
				if($filter) {
					$filterValue = is_numeric($filter) ? (int) $filter : $filter;
					$builder->andWhere($filterKey.' = '.$filterValue);
				}
			}
		}

		$totalRow = $builder->execute()->count();

		if(! empty($sort) ) {
			$builder->orderBy($sort[0].' '.$sort[1]);
		}

		if(! is_null($offset) ) {
			$index = ($offset - 1) * $limit;
			$builder->limit($limit, $index);
		} else {
			$builder->limit($limit);
		}

		$pages = [];
		$limitPage = 5;
		$totalPage = $totalRow > 0 ? ceil($totalRow / $limit) : 0;
		
		if($totalPage > 1) {
			if($totalPage < $limitPage) {
				for($i = 1; $i <= $totalPage; $i++) {
					$pages[] = $i;
				}
			} else {
				if($offset + $limitPage < $totalPage) {
					if($offset < $limitPage && $offset > 1) {
						for($i = 2; $i < 6; $i++) {
							$pages[] = $i;
						}
					} else {
						if($totalPage > $limitPage) {
							$untilPage = $offset + $limitPage;

							for($i = $offset; $i < $untilPage; $i++) {
								$pages[] = $i;
							}
						} else {
							for($i = 1; $i <= $totalPage; $i++) {
								$pages[] = $i;
							}
						}
					}
				} else {
					if($offset < $totalPage) {
						$untilPage = $totalPage - $limitPage;
						for($i = $untilPage; $i < $totalPage; $i++) {
							$pages[] = $i;
						}
					} else {
						$untilPage = $offset - $limitPage;
						for($i = $totalPage; $i >= $untilPage; $i--) {
							$pages[] = $i;
						}
					}

					sort($pages);
				}
			}
		}

		return [
			"data" => $builder->execute(),
			"totalRow" => $totalRow,
			"totalPage" => $totalPage,
			"pages" => $pages,
		];
	}

	protected function getRequest()
	{
		$request = new Request();
		return $request;
	}
}