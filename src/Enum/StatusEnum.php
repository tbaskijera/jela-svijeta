<?php

namespace App\Enum;

enum StatusEnum: string
{
    case Created = 'created';
    case Updated = 'modified';
    case Deleted = 'deleted';
}
