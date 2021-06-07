<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ServicesController extends BaseController
{
    /**
     * @Route("/admin/services", name="admin_services")
     * @Template()
    */
    public function services()
    {
        return [
            'services' => $this->getRepository(Service::class)->findBy([], ['id' => 'desc'])
        ];
    }

    /**
     * @Route("/admin/services/ajax", name="admin_services_ajax")
     */
    public function servicesAjax()
    {
        $services = $this->getRepository(Service::class)->findBy([], ['id' => 'desc']);
        $res = [];
        /** @var Service $service */
        foreach ($services as $service) {
            $res[] = [
                'id' => $service->getId(),
                'name' => $service->getName()
            ];
        }
        return new JsonResponse($res);
    }
}
