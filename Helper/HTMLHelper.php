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
 * Class HTMLHelper
 *
 * @author Daniel González <daniel.gonzalez@freelancemadrid.es>
 */

class HTMLHelper
{
    /**
     *
     *
     * @param string  $text         String to truncate.
     * @param integer $length       Length of returned string, including ellipsis.
     * @param string  $ending       Ending to be appended to the trimmed string.
     * @param boolean $exact        If false, $text will not be cut mid-word
     * @param boolean $html If true, HTML tags would be handled correctly
     *
     * @return string Trimmed string.
     */
    public static function truncate($text, $length = 100, $ending = '...', $exact = false, $html = true)
    {
        if ($html) {
            if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
                return $text;
            }
            preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
            $totalLength = strlen($ending);
            $openTags = array();
            $truncate = '';
            foreach ($lines as $lineMatch) {
                if (!empty($lineMatch[1])) {
                    if (preg_match(
                        '/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is',
                        $lineMatch[1]
                    )
                    ) {
                    } else {
                        if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $lineMatch[1], $tagMatch)) {
                            // delete tag from $open_tags list
                            $pos = array_search($tagMatch[1], $openTags);
                            if ($pos !== false) {
                                unset($openTags[$pos]);
                            }
                            // if tag is an opening tag
                        } else {
                            if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $lineMatch[1], $tagMatch)) {
                                // add tag to the beginning of $open_tags list
                                array_unshift($openTags, strtolower($tagMatch[1]));
                            }
                        }
                    }
                    $truncate .= $lineMatch[1];
                }
                $contentLength = strlen(
                    preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $lineMatch[2])
                );
                if ($totalLength + $contentLength > $length) {
                    $left = $length - $totalLength;
                    $entitiesLength = 0;
                    if (preg_match_all(
                        '/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i',
                        $lineMatch[2],
                        $entities,
                        PREG_OFFSET_CAPTURE
                    )
                    ) {
                        // calculate the real length of all entities in the legal range
                        foreach ($entities[0] as $entity) {
                            if ($entity[1] + 1 - $entitiesLength <= $left) {
                                $left--;
                                $entitiesLength += strlen($entity[0]);
                            } else {
                                break;
                            }
                        }
                    }
                    $truncate .= substr($lineMatch[2], 0, $left + $entitiesLength);
                    break;
                } else {
                    $truncate .= $lineMatch[2];
                    $totalLength += $contentLength;
                }
                if ($totalLength >= $length) {
                    break;
                }
            }
        } else {
            if (strlen($text) <= $length) {
                return $text;
            } else {
                $truncate = substr($text, 0, $length - strlen($ending));
            }
        }
        if (!$exact) {
            $spacePosition = strrpos($truncate, ' ');
            if (isset($spacePosition)) {
                $truncate = substr($truncate, 0, $spacePosition);
            }
        }
        $truncate .= $ending;
        if ($html) {
            foreach ($openTags as $tag) {
                $truncate .= '</' . $tag . '>';
            }
        }

        return $truncate;
    }
}

$text = '<p>Queda muy poco para que aparezca la versión para Android de BBM, el servicio de mensajería online de la BlackBerry, y que uno de sus objetivos es unir dispositivos móviles de iOS y Android con BlackBerry. Y debido a la fase beta en la que se encuentra, realmente no ha sido una sorpresa enorme el […]</p><p>El artículo <a href="http://www.androidsis.com/la-guia-de-usuario-de-blackberry-messenger-aparece-online/">La guía de usuario de BlackBerry Messenger aparece online</a> ha sido originalmente publicado en <a href="http://www.androidsis.com">Androidsis</a>.</p><img width="1" height="1" src="http://actualidadblog.feedsportal.com/c/33423/f/578567/s/30506ff6/sc/15/mf.gif" border="0" alt="mf.gif" /><br clear="all" /><a href="http://share.feedsportal.com/share/twitter/?u=http%3A%2F%2Fwww.androidsis.com%2Fla-guia-de-usuario-de-blackberry-messenger-aparece-online%2F&amp;t=La+gu%C3%ADa+de+usuario+de+BlackBerry+Messenger+aparece+online"><img src="http://res3.feedsportal.com/social/twitter.png" border="0" alt="twitter.png" /></a> <a href="http://share.feedsportal.com/share/facebook/?u=http%3A%2F%2Fwww.androidsis.com%2Fla-guia-de-usuario-de-blackberry-messenger-aparece-online%2F&amp;t=La+gu%C3%ADa+de+usuario+de+BlackBerry+Messenger+aparece+online"><img src="http://res3.feedsportal.com/social/facebook.png" border="0" alt="facebook.png" /></a> <a href="http://share.feedsportal.com/share/linkedin/?u=http%3A%2F%2Fwww.androidsis.com%2Fla-guia-de-usuario-de-blackberry-messenger-aparece-online%2F&amp;t=La+gu%C3%ADa+de+usuario+de+BlackBerry+Messenger+aparece+online"><img src="http://res3.feedsportal.com/social/linkedin.png" border="0" alt="linkedin.png" /></a> <a href="http://share.feedsportal.com/share/gplus/?u=http%3A%2F%2Fwww.androidsis.com%2Fla-guia-de-usuario-de-blackberry-messenger-aparece-online%2F&amp;t=La+gu%C3%ADa+de+usuario+de+BlackBerry+Messenger+aparece+online"><img src="http://res3.feedsportal.com/social/googleplus.png" border="0" alt="googleplus.png" /></a> <a href="http://share.feedsportal.com/share/email/?u=http%3A%2F%2Fwww.androidsis.com%2Fla-guia-de-usuario-de-blackberry-messenger-aparece-online%2F&amp;t=La+gu%C3%ADa+de+usuario+de+BlackBerry+Messenger+aparece+online"><img src="http://res3.feedsportal.com/social/email.png" border="0" alt="email.png" /></a><br /><br /><a href="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/sc/15/rc/1/rc.htm"><img src="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/sc/15/rc/1/rc.img" border="0" alt="rc.img" /></a><br /><a href="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/sc/15/rc/2/rc.htm"><img src="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/sc/15/rc/2/rc.img" border="0" alt="rc.img" /></a><br /><a href="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/sc/15/rc/3/rc.htm"><img src="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/sc/15/rc/3/rc.img" border="0" alt="rc.img" /></a><br /><br /><a href="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/a2.htm"><img src="http://da.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/a2.img" border="0" alt="a2.img" /></a><img width="1" height="1" src="http://pi.feedsportal.com/r/173608147252/u/49/f/578567/c/33423/s/30506ff6/a2t.img" border="0" alt="a2t.img" />';
echo strlen($text) .PHP_EOL;
$text = HTMLHelper::truncate($text, 500M);
echo strlen($text) .PHP_EOL;
