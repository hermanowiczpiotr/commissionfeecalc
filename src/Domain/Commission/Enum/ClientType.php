<?php


namespace App\Domain\Commission\Enum;

enum ClientType: string {
    case Private = 'private';
    case Business = 'business';
}