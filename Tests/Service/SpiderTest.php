<?php
/**
 * This file is part of the planetubuntu package.
 *
 * (c) Daniel González <daniel@desarrolla2.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Desarrolla2\Bundle\PlanetBundle\Tests\Service;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Desarrolla2\Bundle\PlanetBundle\Service\Spider;
use Doctrine\ORM\EntityManager;
use Desarrolla2\Bundle\BlogBundle\Entity\Link;
use Desarrolla2\RSSClient\Node\RSS20 as Node;

/**
 * SpiderTest
 */
class SpiderTest extends WebTestCase
{
    /**
     * @var Spider
     */
    protected $spider;

    /**
     * @var EntityManager
     */
    protected $em;

    public function setUp()
    {
        $this->spider = $this->getContainer()->get('planet.spider');
        $this->em = $this->getContainer()->get('doctrine')->getManager();
    }

    /**
     *
     */
    public function testGetImage()
    {
        $link = $this->em->getRepository('BlogBundle:Link')->find(54);

        $node = new Node();
        $node->setGuid(mktime());
        $node->setLink('http://desarrolla2.com');
        $node->setTitle('iconos faenza');
        $node->setDescription(
            '<p>Faenza es probablemente el mejor tema de iconos que conozco. Cuando se utiliza junto con el tema GTK Equinox tenemos una de las mejores combinaciones para ubuntu, según mi criterio claro. Pa
ra instalarlo ejecutamos lo siguiente.</p>
<pre class="brush: bash; gutter: true">sudo add-apt-repository ppa:tiheum/equinox &amp;&amp; sudo apt-get update
sudo apt-get install gtk2-engines-equinox equinox-theme equinox-ubuntu-theme faenza-icon-theme</pre>
<p>Ahora activamos el tema, para ello "Apparence" &gt; "Themes" &gt; y seleccionamos cualquiera de los que se encuentran disponibles, en mi caso "Equinox evolution" [caption id="attachment_558" align="alignce
nter" width="602" caption="Appearance Preferences"]<a href="http://desarrolla2.com/wp-content/uploads/2011/09/Screenshot-Appearance-Preferences.png"><img class="size-full wp-image-558" title="Appearance Prefe
rences" src="http://desarrolla2.com/wp-content/uploads/2011/09/Screenshot-Appearance-Preferences.png" alt="Appearance Preferences" width="602" height="523" /></a>[/caption] Para utilizar el pack de iconos de
faenza, desde la misma ventana y teniendo el tema activo seleccionado: "Customize" &gt; "Icons" &gt; y seleccionamos el que nos guste en mi caso "Faenza-Darker. [caption id="attachment_559" align="aligncenter
" width="421" caption="Customize Theme"]<a href="http://desarrolla2.com/wp-content/uploads/2011/09/Screenshot-Customize-Theme.png"><img class="size-full wp-image-559" title="Customize Theme" src="http://desar
rolla2.com/wp-content/uploads/2011/09/Screenshot-Customize-Theme.png" alt="Customize Theme" width="421" height="479" /></a>[/caption] Listo, ya lo tienes instalado. ¿Que te parece?</p>'
        );

        $post = $this->spider->parseFeed($link, $node);

        $this->assertEquals(
            'http://desarrolla2.com/wp-content/uploads/2011/09/Screenshot-Appearance-Preferences.png    ',
            $post->getImage()

        );

    }

} 