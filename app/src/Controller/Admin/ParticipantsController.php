<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Participant;
use App\Entity\Provider;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantsController extends BaseController
{
    /**
     * @Route("/admin/participants", name="admin_participants")
     * @Template()
    */
    public function participants()
    {
        return [
            'participants' => $this->getRepository(Participant::class)->getAll()
        ];
    }
}
