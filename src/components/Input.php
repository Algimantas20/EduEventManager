<?php

class Input
{
    public static function renderDropdown($key, array $options, string $currentValue = '')
    {
        echo "<select id=\"{$key}\">";

        foreach ($options as $value => $label) {
            $value = (string)$value;
            $selected = ($value === (string)$currentValue) ? "selected" : "";

            echo "<option value=\"{$value}\" {$selected}>{$label}</option>";
        }

        echo "</select>";
    }

    public static function renderInput(string $key, string $value, string $type, string $attributes)
    {
        echo "<input type=\"{$type}\" id=\"{$key}\" value=\"{$value}\" {$attributes}>";
    }
}
