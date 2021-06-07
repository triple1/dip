<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Provider;
use App\Form\ParticipantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantCreateController extends AbstractController
{
    /**
     * @Route("/participant/create", name="participant_create", methods={"GET"})
     * @Template()
    */
    public function create()
    {
        $participant = new Participant();
        $form = $this->createForm(ParticipantType::class, $participant);
        return [
            'form' => $form->createView()
        ];
    }

    /**
     * @Route("/participant/create", name="participant_create_process", methods={"POST"})
     */
    public function createProcess(Request $request)
    {
        $provider = $this->getUser()->getProvider();

        $part = new Participant();
        $form = $this->createForm(ParticipantType::class, $part);
        $form->handleRequest($request);

        /** @var Participant $part */
        $part = $form->getData();
        $part->addProvider($provider);
        $part->setOwnerProvider($provider);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($part);
        $manager->flush();

        $this->addFlash('success', sprintf('Участник \'%s\' успешно создан и добавлен в организацию', $part->getFirstName() . ' ' . $part->getLastName()));
        return $this->redirectToRoute('participants');
    }
}
