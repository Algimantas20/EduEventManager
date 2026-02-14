<?

function DisplayStatusInput(string $currentValue) : void
{
    $options =
    [
        'A' => 'Active',
        'I' => 'Inactive',
        'D' => 'Deleted'
    ];

    echo "<select name='status'>";

    foreach ($options as $value => $label)
    {
        $selected = ($value === $currentValue) ? " selected" : "";
        echo "<option value='$value'$selected>$label</option>";
    }

    echo "</select>";
}

?>