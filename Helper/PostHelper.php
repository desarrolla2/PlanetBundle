<?php
/**
 * This file is part of the planetandroid project.
 *
 * Copyright (c)
 *
 * This source file is subject to the MIT license that is bundled
 * with this package in the file LICENSE.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Helper;


/**
 * Class TextHelper
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class PostHelper
{

    /**
     *
     * @param string $string
     * @return string
     */
    public static function doCleanContent($string)
    {
        $string = strip_tags(
            $string,
            '<pre><cite><code><em><i><ul><li><ol><small><span><strike><a>' .
            '<b><p><br><br/><img><h4><h5><h3><h2>' .
            '<table><tr><td><ht>'
        );

        return self::doClean($string);
    }

    /**
     *
     * @param string $string
     * @return string
     */
    public static function doCleanIntro($string)
    {
        $string = strip_tags(
            $string,
            '<pre><cite><code><em><i><ul><li><ol><small><span><strike><a>' .
            '<b><p><br><br/><img><h4><h5><h3><h2>'
        );

        return self::doClean(HTMLHelper::truncate($string, 500));
    }


    /**
     *
     * @param string $string
     * @return string
     */
    protected static function doClean($string)
    {
        $string = str_replace('<p>&nbsp;</p>', '', $string);
        $string = trim(str_replace('<p></p>', '', $string));
        $string = preg_replace('/\s\s+/', ' ', $string);
        $string = preg_replace('/(<[^>]+) style=".*?"/i', '$1', $string);

        return trim($string);
    }
}