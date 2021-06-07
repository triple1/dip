<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Provider;
use App\Entity\Referral;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ReferralsController extends BaseController
{
    /**
     * @Route("/relerrals", name="referrals")
     * @Template()
    */
    public function referrals()
    {
        /** @var Provider $provider */
        $provider = $this->getUser()->getProvider();
        $referrals = $this->getRepository(Referral::class)->getReferralsByProvider($provider);

        return [
            'referrals' => $referrals,
            'provider' => $provider
        ];
    }
}
