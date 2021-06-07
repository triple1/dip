<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Provider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class ProvidersController extends BaseController
{
    /**
     * @Route("/admin/providers", name="admin_providers")
     * @Template()
    */
    public function providers()
    {
        return [
            'providers' => $this->getRepository(Provider::class)->findBy([], ['id' => 'desc'])
        ];
    }
}
