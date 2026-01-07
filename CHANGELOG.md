# Changelog

All notable changes to `manage-eloquent` will be documented in this file

## v1.2.3 - 2026-01-07

### :rocket: Features

- implement ManageEloquent trait for enhanced Eloquent model management by @OoBook in https://github.com/OoBook/manage-eloquent/commit/501e30c4ac6b73657038a378a55f1142cc42da19

### :wrench: Bug Fixes

- remove ManageEloquent trait methods to streamline Eloquent model management by @OoBook in https://github.com/OoBook/manage-eloquent/commit/4cf5800560b67f65fea1c95601bf64c854b016e6

### :recycle: Refactors

- optimize definedRelationsTypes method to return short_relationship_class directly by @OoBook in https://github.com/OoBook/manage-eloquent/commit/5a1b65c882c9dbe7c073293f3b597fe46486917e

### :memo: Documentation

- update README.md by @web-flow in https://github.com/OoBook/manage-eloquent/commit/fb135e2b15c061b2c3f171d805fd6f1f113088db

## v1.2.2 - 2025-05-10

**Full Changelog**: https://github.com/OoBook/manage-eloquent/compare/v1.2.1...v1.2.2

## v1.2.1 - 2025-02-25

### :wrench: Bug Fixes

- Improve verbose output in columns cache cleaning command by @OoBook in https://github.com/OoBook/manage-eloquent/commit/e0c4873d42d558236e8f6e889767391318439ffc

## v1.2.0 - 2025-02-19

### :rocket: Features

- Register cache cleaning commands in service provider by @OoBook in https://github.com/OoBook/manage-eloquent/commit/8c1a6246d2f4f0d6d9499b1d9237f7af64c3728e

### :recycle: Refactors

- Make isSoftDeletable method static by @OoBook in https://github.com/OoBook/manage-eloquent/commit/40d3f76a1ac4257ebfbdac97f4bcd5b43108a712

## v1.1.0 - 2025-02-16

### :rocket: Features

- Add configurable caching for column types and relationships by @OoBook in https://github.com/OoBook/manage-eloquent/commit/c3c17a9e0bd771763dccb37d12d4c44e639c86b8
- Add cache cleaning commands for manage eloquent by @OoBook in https://github.com/OoBook/manage-eloquent/commit/932bb566b86d83f6abe2054a81fbecdd24786164

## v1.0.3 - 2024-10-01

### :wrench: Bug Fixes

- get types with Schema::getColumns for custom types like enum by @OoBook in https://github.com/OoBook/manage-eloquent/commit/e6fce9574a93a8b199b6396962bdce54614b6fa9
- remove laravel 9 and php 8.0 support by @OoBook in https://github.com/OoBook/manage-eloquent/commit/2be3e78165568c0b6000fcbe2375092a665c8353

### :beers: Other Stuff

- Update CHANGELOG file

## v1.0.2 - 2024-09-30

### :wrench: Bug Fixes

- add laravel 11 support with Schema facade by @OoBook in https://github.com/OoBook/manage-eloquent/commit/c496db3746f7795cb8ed15b79392e4c14b8de7b6

### :green_heart: Workflow

- :green_heart: update release on only main push and tests without release by @OoBook in https://github.com/OoBook/manage-eloquent/commit/c69d2a10fc70f265aaa5b32db90a5a9284d05065
- add laravel 11 tests by @OoBook in https://github.com/OoBook/manage-eloquent/commit/3092428897c58deb03a0bd2019f509fb83eb5306

### :beers: Other Stuff

- Update CHANGELOG file

## v1.0.1 - 2024-09-27

### :wrench: Bug Fixes

- :ambulance: add typeMapping for enum issues by @OoBook in https://github.com/OoBook/manage-eloquent/commit/f8724adb9a3db9c57062fd99679388ca40641867
- :ambulance: change getDoctringSchemaManager as getDoctrineConnection due to deprecated by @OoBook in https://github.com/OoBook/manage-eloquent/commit/b23ad18d4c595045c08cbf1570813c079bea2827
- remove laravel 11 support by @OoBook in https://github.com/OoBook/manage-eloquent/commit/f405b783f90dca7553eb751b482f93c22270b8fd

### :white_check_mark: Testing

- :white_check_mark: remove laravel 11 test due to doctrinConnection removed by @OoBook in https://github.com/OoBook/manage-eloquent/commit/830894abe0a80d48b8d1c7c6045646f80722d46c

### :green_heart: Workflow

- :bug: release if tests are success by @OoBook in https://github.com/OoBook/manage-eloquent/commit/4b4a725f04bea94e7478cd11ab00b88bd34c53e9

### :beers: Other Stuff

- Update CHANGELOG file

## V1 - 2024-09-20

İnitial Release.

## v0.1.0 - 2024-09-12

**Full Changelog**: https://github.com/OoBook/manage-eloquent/commits/v0.1.0
