<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Referral;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantDetailsController extends BaseController
{
    /**
     * @Route("/participant/{id}", name="participant_details", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
    */
    public function details(int $id)
    {
        return [
            'participant' => $this->getRepository(Participant::class)->find($id)
        ];
    }
}
