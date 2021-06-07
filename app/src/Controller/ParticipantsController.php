<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Provider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantsController extends BaseController
{
    /**
     * @Route("/participants", name="participants")
     * @Template()
    */
    public function participants()
    {
        /** @var Provider $provider */
        $provider = $this->getUser()->getProvider();

        $participants = $this->getRepository(Participant::class)->getAllForProvider($provider);

        return [
            'participants' => $participants
        ];
    }
}
