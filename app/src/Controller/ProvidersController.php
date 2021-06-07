<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Provider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class ProvidersController extends BaseController
{
    /**
     * @Route("/providers", name="providers")
     * @Template()
    */
    public function providers()
    {
        return [
            'providers' => $this->getRepository(Provider::class)->findBy([], ['id' => 'desc'])
        ];
    }
}
