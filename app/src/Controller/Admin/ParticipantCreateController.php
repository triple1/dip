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

class ParticipantCreateController extends BaseController
{
    /**
     * @Route("/admin/participant/create", name="admin_participant_create", methods={"GET"})
     * @Template()
    */
    public function create()
    {
        $part = new Participant();
        $form = $this->createForm(ParticipantType::class, $part);
        return [
            'form' => $form->createView(),
            'providers' => $this->getRepository(Provider::class)->findAll()
        ];
    }

    /**
     * @Route("/admin/participant/create", name="admin_participant_create_process", methods={"POST"})
     */
    public function createProcess(Request $request)
    {
        $idProvider = $request->get('id_provider', null);
        /** @var Provider $provider */
        $provider = $this->getRepository(Provider::class)->find($idProvider);
        if (!$provider) throw new NotFoundHttpException('Provider not found');

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

        $this->addFlash('success', sprintf('Participant \'%s\' was created', $part->getFirstName() . ' ' . $part->getLastName()));
        return $this->redirectToRoute('admin_participants');
    }
}
