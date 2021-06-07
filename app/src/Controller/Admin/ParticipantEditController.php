<?php declare(strict_types=1);

namespace App\Controller\Admin;

use App\Controller\BaseController;
use App\Entity\Participant;
use App\Entity\Provider;
use App\Form\ParticipantType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantEditController extends BaseController
{
    /**
     * @Route("/admin/participant/{idParticipant}/edit", name="admin_participant_edit", methods={"GET"})
     * @Template()
    */
    public function edit(string $idParticipant)
    {
        $part = $this->getRepository(Participant::class)->find($idParticipant);
        $form = $this->createForm(ParticipantType::class, $part);
        return [
            'part' => $part,
            'form' => $form->createView(),
            'providers' => $this->getRepository(Provider::class)->findAll()
        ];
    }

    /**
     * @Route("/admin/participant/edit", name="admin_participant_edit_process", methods={"POST"})
     */
    public function editProcess(Request $request)
    {
        $idProvider = $request->get('id_provider', null);
        /** @var Provider $provider */
        $provider = $this->getRepository(Provider::class)->find($idProvider);
        if (!$provider) throw new NotFoundHttpException('Provider not found');

        $idParticipant = $request->get('id_participant');
        $part = $this->getRepository(Participant::class)->find($idParticipant);
        $form = $this->createForm(ParticipantType::class, $part);
        $form->handleRequest($request);

        /** @var Participant $part */
        $part = $form->getData();

        $part->setProviders([]);
        $part->addProvider($provider);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($part);
        $manager->flush();

        $this->addFlash('success', sprintf('Participant \'%s\' was created', $part->getFirstName() . ' ' . $part->getLastName()));
        return $this->redirectToRoute('admin_participants');
    }
}
