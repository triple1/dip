<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Provider;
use App\Entity\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class AssignServiceController extends BaseController
{
    /**
     * @Route("/admin/services/assign/{idProvider}", name="admin_assign_service", methods={"GET"})
     * @Template()
    */
    public function assign(string $idProvider)
    {
        /** @var Provider $provider */
        $provider = $this->getRepository(Provider::class)->find($idProvider);
        if (!$provider) throw new NotFoundHttpException('Provider not found');

        $servicesAll = $this->getRepository(Service::class)->findAll();

        return [
            'servicesAll' => $servicesAll,
            'provider' => $provider
        ];
    }

    /**
     * @Route("/admin/services/assign", name="admin_assign_service_process", methods={"POST"})
     */
    public function assignProcess(Request $request)
    {
        $idProvider = $request->get('id_provider', null);
        if ($idProvider === null) throw new NotFoundHttpException('Provider not found');

        /** @var Provider $provider */
        $provider = $this->getRepository(Provider::class)->find($idProvider);
        if (!$provider) throw new NotFoundHttpException('Provider not found');

        $idsServices = $request->get('services_ids', []);
        $servicesNew = $this->getRepository(Service::class)->getServicesByIds($idsServices);

        $provider->setServices($servicesNew);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', 'Services assigned!');
        return $this->redirectToRoute('admin_providers');
    }
}
