<?php

namespace Akceli\Schema;

/**
 * Interface ColumnInterface
 * @package Akceli\Schema
 *
 * @mixin \AkceliColumnTrait
 */
interface ColumnInterface
{
    /**
     * @return string
     */
    public function getField(): string;

    /**
     * @return string
     */
    public function getRelationship(): string;

    /**
     * @return string
     */
    public function getRelatedModel(): string;

    /**
     * @return boolean
     */
    public function hasCastsToAttribute(): bool;

    /**
     * @return string
     */
    public function getCastsToAttribute(): string;

    /**
     * @return bool
     */
    public function hasValidationRules(): bool;

    /**
     * @return string
     */
    public function getValidationRulesAsString(): string;

    /**
     * @param string $column_setting
     * @param null $default
     * @return string|null
     */
    public function getColumnSetting(string $column_setting, $default = null);

    /**
     * @return bool
     */
    public function isIncrementing(): bool;

    /**
     * @return bool
     */
    public function isNullable(): bool;

    /**
     * @return bool
     */
    public function isInteger(): bool;

    /**
     * @return bool
     */
    public function isBoolean(): bool;

    /**
     * @return bool
     */
    public function isTimeStamp(): bool;

    /**
     * @return bool
     */
    public function isEnum(): bool;

    /**
     * @return bool
     */
    public function isString(): bool;
}
