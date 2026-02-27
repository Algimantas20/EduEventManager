<?php

class Input
{
    public static function renderDropdown(string $key, array $options, $currentValue = '')
    {
        echo "<select id=\"{$key}\" name=\"{$key}\">";

        foreach ($options as $value => $option) {
            $label = is_array($option) ? $option['label'] : $option;
            $selected = ((string)$value === (string)$currentValue) ? "selected" : "";

            echo "<option value=\"{$value}\" {$selected}>{$label}</option>";
        }

        echo "</select>";
    }

    public static function renderInput(string $key, string $value, string $type, string $attributes)
    {
        echo "<input type=\"{$type}\" id=\"{$key}\" name=\"{$key}\" value=\"{$value}\" {$attributes}>";
    }
}
