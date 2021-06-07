<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Referral;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantReferralsController extends BaseController
{
    /**
     * @Route("/participant/{id}/referrals", name="participant_referrals", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
    */
    public function referrals(int $id)
    {
        /** @var Participant $participant */
        $participant = $this->getRepository(Participant::class)->find($id);
        $referrals = $this->getRepository(Referral::class)->getReferralsByParticipant($participant);

        return [
            'participant' => $participant,
            'referrals' => $referrals
        ];
    }
}
