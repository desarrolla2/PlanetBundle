<?php


namespace Desarrolla2\Bundle\PlanetBundle\Controller\Backend;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;

/**
 * @Route("/report")
 */
class ReportController extends Controller
{


    /**
     * @Route("/unrelated" , name="_planet_backend_report_unrelated")
     * @Template()
     */
    public function reportAction()
    {
        $unrelated =
            $this->getDoctrine()->getManager()
                ->getRepository('PlanetBundle:Unrelated')
                ->getPublished();

        return array(
            'unrelated' => $unrelated,
        );
    }

    /**
     * @Route("/unrelated/{id}/clean" , name="_planet_backend_report_unrelated_clean")
     * @Template()
     */
    public function cleanAction(Request $request)
    {
        $post = $this->getDoctrine()->getManager()
            ->getRepository('BlogBundle:Post')->find($request->get('id', false));
        if (!$post) {
            throw $this->createNotFoundException('The post does not exist');
        }

        $this->getDoctrine()->getManager()
            ->getRepository('PlanetBundle:Unrelated')
            ->clean($post);

        return new RedirectResponse($this->generateUrl('_unrelated_report'), 302);
    }
}