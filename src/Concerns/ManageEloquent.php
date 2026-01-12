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
    private static $manageEloquentDefinedRelationships = [];

    /**
     * Boot the trait and cache defined relationships.
     */
    public static function bootManageEloquent()
    {
        $relationClassesPattern = "|" . preg_quote(config('manage-eloquent.relations_namespace'), "|") . "|";

        $class = static::class;

        $reflector = new \ReflectionClass($class);

        $self = new static();

        static::$manageEloquentDefinedRelationships[$class] = collect($reflector->getMethods(\ReflectionMethod::IS_PUBLIC))
            ->reduce(function($carry, \ReflectionMethod $method) use($relationClassesPattern, $self) {
                try {                    
                    if($method->hasReturnType() && preg_match("{$relationClassesPattern}", ($returnType = $method->getReturnType()) )){
                        $relationshipMethodName = $method->name;
                        $relationship = $self->{ $relationshipMethodName }();
                        $related = $relationship->getRelated();
                        $relationshipTable = $related->getTable();
    
                        // if many to many, get the pivot or through table
                        $hasMiddlemanModel = false;
                        $middlemanModel = null;
                        $middlemanTable = null;
                        $isManyToMany = false;
    
                        if($relationship instanceof \Illuminate\Database\Eloquent\Relations\MorphToMany){
                            $isManyToMany = true;
                            $middlemanTable = $relationship->getTable();
                        }else if($relationship instanceof \Illuminate\Database\Eloquent\Relations\BelongsToMany){
                            $isManyToMany = true;
                            $pivotClass = $relationship->getPivotClass();
    
                            if(!($pivotClass instanceof \Illuminate\Database\Eloquent\Relations\Pivot)){
                                $hasMiddlemanModel = true;
                                $middlemanModel = $pivotClass;
                            }
    
                            $middlemanTable = $relationship->getTable();
                        }else if($relationship instanceof \Illuminate\Database\Eloquent\Relations\HasManyThrough){
                            $isManyToMany = true;
                            $hasMiddlemanModel = true;
                            $middlemanModel = get_class($relationship->getParent());
                            $middlemanTable = $relationship->getParent()->getTable();
                        }
    
                        $carry[$method->name] = [
                            'relationship_class' => (new \ReflectionClass((string) $returnType))->getName(),
                            'short_relationship_class' => (new \ReflectionClass((string) $returnType))->getShortName(),
                            'relationship_model' => get_class($relationship->getRelated()),
                            'relationship_table' => $relationshipTable,
                            'is_many_to_many' => $isManyToMany,
                            'has_middleman' => $hasMiddlemanModel,
                            'middleman_model' => $middlemanModel,
                            'middleman_table' => $middlemanTable,
                        ];
                    }
                } catch (\Exception $e) {

                }

                return $carry;
            });

    }

    public function getEloquentRelationships($relations = null): array
    {
        $class = static::class;

        $definedRelationships = static::$manageEloquentDefinedRelationships[$class] ?? [];

        if($relations){
            if(is_array($relations)){
                return array_keys(Arr::where($definedRelationships, fn($val, $key) => in_array($val, $relations)));

            }else if(is_string($relations)){
                return array_keys(Arr::where($definedRelationships, fn($val, $key) => $val == Str::studly($relations)));
            }
        }

        try {
            return $definedRelationships;
        } catch (\Exception $e) {
            dd(static::$manageEloquentDefinedRelationships);
        }
    }

    /**
     * Get defined relations.
     *
     * @param array|string|null $relations Optional list of relation names to filter.
     * @return array
     */
    public function definedRelations($relations = null): array
    {
        $class = static::class;

        $definedRelationships = static::$manageEloquentDefinedRelationships[$class] ?? [];

        if($relations){
            if(is_array($relations)){
                return array_keys(Arr::where($definedRelationships, fn($val, $key) => in_array($val['short_relationship_class'], $relations)));

            }else if(is_string($relations)){
                return array_keys(Arr::where($definedRelationships, fn($val, $key) => $val['short_relationship_class'] == Str::studly($relations)));
            }
        }

        try {
            return array_keys($definedRelationships);
        } catch (\Exception $e) {
            dd(static::$manageEloquentDefinedRelationships);
        }
    }

    /**
     * Get defined relation types.
     *
     * @param array|string|null $relations Optional list of relation names to filter.
     * @return array
     */
    public function definedRelationsTypes($relations = null): array
    {
        $class = static::class;

        $definedRelationships = static::$manageEloquentDefinedRelationships[$class];

        if($relations){
            if(is_array($relations)){
                return array_map(fn($val) => $val['short_relationship_class'], Arr::where($definedRelationships, fn($val, $key) => in_array($val['short_relationship_class'], $relations)));

            }else if(is_string($relations)){
                return array_map(fn($val) => $val['short_relationship_class'], Arr::where($definedRelationships, fn($val, $key) => $val['short_relationship_class'] == Str::studly($relations)));
            }
        }

        return array_map(fn($val) => $val['short_relationship_class'], $definedRelationships);
    }

    /**
     * Check if a relation exists.
     *
     * @param string $relationName The relation name.
     * @return bool
     */
    public function hasRelation($relationName): bool
    {
        $class = static::class;

        $definedRelationships = static::$manageEloquentDefinedRelationships[$class];

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
        $class = static::class;

        $definedRelationships = static::$manageEloquentDefinedRelationships[$class];

        return array_key_exists($relationName, $definedRelationships) ? $definedRelationships[$relationName]['short_relationship_class'] : false;
    }

    /**
     * Checks if this model is soft deletable.
     *
     * @param array|string|null $columns Optionally limit the check to a set of columns.
     * @return bool
     */
    public static function isSoftDeletable(): bool
    {
        // Model must have the trait
        return in_array('Illuminate\Database\Eloquent\SoftDeletes', class_uses_recursive(static::class));
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
        return array_keys(array_filter($this->getTableColumnTypes(), fn($val) => $val === 'timestamp' || $val === 'datetime'));
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
     * @deprecated This method will be removed in the next major release. Use getTableColumnTypes instead.
     */
    public function getColumnTypes(): array
    {
        return $this->getTableColumnTypes();
    }

    /**
     * Get table column types.
     *
     * @return array
     */
    public function getTableColumnTypes(): array
    {
        $cacheKey = config('manage-eloquent.cache.column_types.key', 'column_types');
        $cacheTtl = config('manage-eloquent.cache.column_types.ttl', 86400);
        $cacheEnabled = config('manage-eloquent.cache.column_types.enabled', true);
        $cacheKey = static::class . "_" . $cacheKey;

        if ($cacheEnabled && Cache::has($cacheKey)) {
            return Cache::get($cacheKey);
        }else{
            $columnTypes = Collection::make(Schema::getColumns($this->getTable()))
                ->mapWithKeys(fn ($column, $attr) => [$column['name'] => $column['type_name'] ] )
                ->toArray();

            if ($cacheEnabled) {
                Cache::put($cacheKey, $columnTypes, $cacheTtl);
            }else if(Cache::has($cacheKey)){
                Cache::forget($cacheKey);
            }

            return $columnTypes;
        }
    }

    /**
     * Get table column names.
     *
     * @return array
     */
    public function getTableColumns(): array
    {
        return array_keys($this->getTableColumnTypes());
    }
}
