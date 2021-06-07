<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\News;
use App\Entity\Provider;
use App\Entity\Referral;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends BaseController
{
    /**
     * @Route("/", name="home")
     * @Template()
    */
    public function index()
    {
        $provider = $this->getUser()->getProvider();
        $params = [];
        if ($provider instanceof Provider) {
            $params = [
                'newsList' => $this->getRepository(News::class)->getAllNewsByProvider($this->getUser()->getProvider()),
                'provider' => $provider,
                'referralsCountNewCount' => $this->getRepository(Referral::class)->getNewReferralsCountByProvider($provider)
            ];
        }
        return $params;
    }
}
