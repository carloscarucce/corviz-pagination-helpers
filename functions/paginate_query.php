<?php

use Corviz\Database\Query;
use Corviz\Http\Request;

/**
 * @param Query $query
 * @param Request $request
 * @param array $executeParams
 * @param int $perPage
 *
 * @return array
 */
function paginate_query(Query $query, Request $request, array $executeParams = [], int $perPage = 15): array
{
    $perPage = $perPage ?: 15;
    $perPage = $perPage < 1 ? 1 : $perPage;

    $currentPage = max(
        isset($request->getQueryParams()['page']) ? (int) $request->getQueryParams()['page']: 1,
        1
    );
    $totalRows = (clone $query)->execute($executeParams)->count();
    $maxPage = ceil($totalRows/$perPage);

    if ($currentPage > $maxPage) {
        $currentPage = $maxPage;
    }

    $query->limit($perPage);
    $query->offset(abs($currentPage - 1) * $perPage);

    $rows = $query->execute($executeParams)->fetchAll();

    return compact('rows', 'maxPage', 'currentPage', 'perPage', 'totalRows');
}
