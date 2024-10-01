<?php

namespace Oobook\Database\Eloquent\Concerns;

use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

/**
 * Trait to manage Eloquent models.
 */
trait ManageEloquent
{
    /**
     * Cached defined relationships.
     */
    private static $_definedRelationships = [];

    /**
     * Model cache keys.
     */
    protected $modelCacheKeys = [
        'column_types',
    ];

    /**
     * Boot the trait and cache defined relationships.
     */
    public static function bootManageEloquent()
    {
        $relationClassesPattern = "|" . preg_quote(config('manage-eloquent.relations_namespace'), "|") . "|";

        $class = get_called_class();

        $reflector = new \ReflectionClass($class);

        static::$_definedRelationships[$class] = collect($reflector->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->reduce(function($carry, \ReflectionMethod $method) use($relationClassesPattern) {
                if($method->hasReturnType() && preg_match("{$relationClassesPattern}", ($returnType = $method->getReturnType()) )){
                    $carry[$method->name] = (new \ReflectionClass((string) $returnType))->getShortName();
                }

                return $carry;
            });
    }

    /**
     * Get defined relations.
     *
     * @param array|string|null $relations Optional list of relation names to filter.
     * @return array
     */
    public function definedRelations($relations = null): array
    {
        $class = get_called_class();

        $definedRelationships = static::$_definedRelationships[$class];

        if($relations){
            if(is_array($relations)){
                return array_keys(Arr::where($definedRelationships, fn($val, $key) => in_array($val, $relations)));

            }else if(is_string($relations)){
                return array_keys(Arr::where($definedRelationships, fn($val, $key) => $val == Str::studly($relations)));
            }
        }

        return array_keys($definedRelationships);
    }
    /**
     * Get defined relation types.
     *
     * @param array|string|null $relations Optional list of relation names to filter.
     * @return array
     */
    public function definedRelationsTypes($relations = null): array
    {
        $class = get_called_class();

        $definedRelationships = static::$_definedRelationships[$class];

        if($relations){
            if(is_array($relations)){
                return Arr::where($definedRelationships, fn($val, $key) => in_array($val, $relations));

            }else if(is_string($relations)){
                return Arr::where($definedRelationships, fn($val, $key) => $val == Str::studly($relations));
            }
        }

        return $definedRelationships;
    }

    /**
     * Check if a relation exists.
     *
     * @param string $relationName The relation name.
     * @return bool
     */
    public function hasRelation($relationName): bool
    {
        $class = get_called_class();

        $definedRelationships = static::$_definedRelationships[$class];

        return array_key_exists($relationName, $definedRelationships);
    }

    /**
     * Get the relation type.
     *
     * @param string $relationName The relation name.
     * @return string|false
     */
    public function getRelationType($relationName): string
    {
        $class = get_called_class();

        $definedRelationships = static::$_definedRelationships[$class];

        return array_key_exists($relationName, $definedRelationships) ? $definedRelationships[$relationName] : false;
    }

    /**
     * Checks if this model is soft deletable.
     *
     * @param array|string|null $columns Optionally limit the check to a set of columns.
     * @return bool
     */
    public function isSoftDeletable(): bool
    {
        // Model must have the trait
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive($this));
    }

    /**
     * Check if a column exists.
     *
     * @param string $column The column name.
     * @return bool
     */
    public function hasColumn($column): bool
    {
        return $this->getConnection()->getSchemaBuilder()->hasColumn($this->getTable(), $column);
    }

    /**
     * Get timestamp columns.
     *
     * @return array
     */
    public function getTimestampColumns(): array
    {
        return array_keys(array_filter($this->getColumnTypes(), fn($val) => $val === 'timestamp' || $val === 'datetime'));
    }

    /**
     * Check if a column is a timestamp column.
     *
     * @param string $column The column name.
     * @return bool
     */
    public function isTimestampColumn($column): bool
    {
        return in_array($column, $this->getTimestampColumns());
    }

    /**
     * Get column types.
     *
     * @return array
     */
    public function getColumnTypes(): array
    {
        $columnsKey = get_class($this) . "_column_types";

        if (Cache::has($columnsKey)) {
            return Cache::get($columnsKey);
        }else{
            $columnTypes = Collection::make(Schema::getColumns($this->getTable()))
                ->mapWithKeys(fn ($column, $attr) => [$column['name'] => $column['type_name'] ] )
                ->toArray();

            Cache::put($columnsKey, $columnTypes);

            return $columnTypes;
        }
    }

    /**
     * Get column names.
     *
     * @return array
     */
    public function getColumns(): array
    {
        return array_keys($this->getColumnTypes());
    }
}
