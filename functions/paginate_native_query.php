<?php

use Corviz\Database\Connection;
use Corviz\Http\Request;

/**
 * @param Connection $connection
 * @param string $query
 * @param Request $request
 * @param array $executeParams
 * @param int $perPage
 *
 * @return array
 */
function paginate_native_query(
    Connection $connection, string $query, Request $request, array $executeParams = [], int $perPage = 15
): array {
    $perPage = $perPage ?: 15;
    $perPage = $perPage < 1 ? 1 : $perPage;
    $executeParams = array_values($executeParams);

    $currentPage = max(
        isset($request->getQueryParams()['page']) ? (int) $request->getQueryParams()['page']: 1,
        1
    );
    $totalRows = $connection->nativeQuery($query, ...$executeParams)->count();
    $maxPage = ceil($totalRows/$perPage);

    if ($currentPage > $maxPage) {
        $currentPage = $maxPage;
    }

    $offset = abs($currentPage - 1) * $perPage;
    $paginatedQuery = "SELECT vw.* FROM ($query) AS vw LIMIT $perPage OFFSET $offset";

    $rows = $connection->nativeQuery($paginatedQuery, ...$executeParams)->fetchAll();

    return compact('rows', 'maxPage', 'currentPage', 'perPage', 'totalRows');
}