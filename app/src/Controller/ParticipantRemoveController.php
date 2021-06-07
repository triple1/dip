<?php declare(strict_types=1);

namespace App\Controller;

use App\Controller\BaseController;
use App\Entity\Participant;
use App\Entity\Provider;
use App\Form\ParticipantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantRemoveController extends BaseController
{
    /**
     * @Route("/participant/{idParticipant}/remove", name="participant_remove", methods={"GET"})
     * @Template()
    */
    public function remove(string $idParticipant)
    {
        $part = $this->getRepository(Participant::class)->find($idParticipant);
        return [
            'part' => $part
        ];
    }

    /**
     * @Route("/participant/remove", name="participant_remove_process", methods={"POST"})
     */
    public function removeProcess(Request $request)
    {
        $idParticipant = $request->get('id_participant');
        $part = $this->getRepository(Participant::class)->find($idParticipant);

        $manager = $this->getDoctrine()->getManager();
        $manager->remove($part);
        $manager->flush();

        $this->addFlash('success', sprintf('Участников успешно удален'));
        return $this->redirectToRoute('participants');
    }
}
