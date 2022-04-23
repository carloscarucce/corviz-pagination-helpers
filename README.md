# corviz-pagination-helpers

Provides helper functions for pagination using corviz/framework query builder or native SQL query

## Installation:

```
composer require carloscarucce/corviz-pagination-helpers
```

## Both functions will return an array with the following indexes:

- rows: array containing the rows in the current page
- maxPage: integer number representing the maximum page
- currentPage: integer number representing the current page
- perPage: how many rows per page
- totalRows: the total rows count of the non-paginated query