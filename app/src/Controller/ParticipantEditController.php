<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Provider;
use App\Form\ParticipantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantEditController extends BaseController
{
    /**
     * @Route("/participant/edit/{id}", name="participant_edit", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
    */
    public function edit(int $id)
    {
        $participant = $this->getRepository(Participant::class)->find($id);
        $form = $this->createForm(ParticipantType::class, $participant);
        return [
            'form' => $form->createView(),
            'participant' => $participant
        ];
    }

    /**
     * @Route("/participant/edit", name="participant_edit_process", methods={"POST"})
     */
    public function editProcess(Request $request)
    {
        $idParticipant = $request->get('id_participant');
        $part = $this->getRepository(Participant::class)->find($idParticipant);
        $form = $this->createForm(ParticipantType::class, $part);
        $form->handleRequest($request);

        /** @var Participant $part */
        $part = $form->getData();

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($part);
        $manager->flush();

        $this->addFlash('success', sprintf('Данные участника \'%s\' успешно сохранены', $part->getFirstName() . ' ' . $part->getLastName()));
        return $this->redirectToRoute('participants');
    }
}
