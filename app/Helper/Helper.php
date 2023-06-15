<?php

namespace App\Helper;

use Illuminate\Support\Facades\Storage;

class Helper
{
    public static function getPathIfExist($path, $name): string
    {
        return Storage::exists($path.$name)
            ? Storage::url($path.$name)
            : Storage::url($name);
    }

    public static function round_up($value, $places)
    {
        $mult = pow(10, abs($places));
        return $places < 0 ?
            ceil($value / $mult) * $mult :
            ceil($value * $mult) / $mult;
    }

    public static function getUrlWithSlugCategory($category, $slug)
    {
        $url = self::recursiveAddSlugCategory($category);
        return '/' . implode('/', array_reverse($url)) . '/article/' . $slug;
    }

    private static function  recursiveAddSlugCategory( $input)
    {
        $even[] = $input->slug;

        if( $input->parent ) {
            $even = array_merge(
                $even,
                self::recursiveAddSlugCategory( $input->parent)
            );;
        }

        return $even;
    }
}
