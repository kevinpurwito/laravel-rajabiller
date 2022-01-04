# Changelog

All notable changes to `laravel-rajabiller` will be documented in this file

## [2.0.4] - 2022-01-04
### Added
- config to syncH2H or not
### Changed
- Handle empty `KODE_PRODUK` response for populateItems() & populateItemsH2H()
- Skip duplicates on populateItemsH2H()
- Delete duplicate entry in `rb_items.sql`

## [2.0.3] - 2021-12-30
- Cast admin fee from rajabiller to int (to handle empty string)

## [2.0.2] - 2021-12-30
- Fix sync items console command

## [2.0.1] - 2021-10-28
- Fix foreign key for rb_orders to rb_item_id
- Fix foreign key for rb_items to rb_group_id

## [2.0.0] - 2021-10-28
- Drop support for PHP7.4
- Add Credit Card, Transfer & Buy method
- Add process() to RbItem Model to consolidate processing
- Change all methods to return json object instead of `ResponseInterface`
- Update dependencies

## [1.0.3] - 2021-10-27
- New function getItem() in Rajabiller.php

## [1.0.2] - 2021-10-27
- Major changes in database migration & seeders

## [1.0.1] - 2021-10-26
- Use `kevinpurwito/php-constant` package for the constants

## [1.0.0] - 2021-10-25
- Initial release Rajabiller integration for laravel
- Rajabiller credentials via .env and config
- Migrations
- Seeders
- Sync Items command
