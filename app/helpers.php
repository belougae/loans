<?php

function grid_type($i, $value)
{
    switch ($value) {
        case 0:
            return "<span style='color:red'>$i</span>";
            break;
        case 1:
            return "<span style='color:yellow'>$i</span>";
            break;
        case 2:
            return "<span style='color:green'>$i</span>";
            break;
        default:
            return $i;
            break;
    }
}