<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Participant;
use App\Entity\Provider;
use App\Entity\Referral;
use App\Entity\Service;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ParticipantReferralCreateController extends BaseController
{
    /**
     * @Route("/participant/{id}/referral/create", name="referral_create", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
    */
    public function create(int $id)
    {
        /** @var Participant $participant */
        $participant = $this->getRepository(Participant::class)->find($id);
        $providers = $this->getRepository(Provider::class)->findAll();
        $services = $this->getRepository(Service::class)->findAll();

        return [
            'participant' => $participant,
            'providers' => $providers,
            'services' => $services
        ];
    }

    /**
     * @Route("/participant/referral/create", name="participant_referral_create_process", methods={"POST"})
    */
    public function createProcess(Request $request)
    {
        $servicesIds = $request->get('services', []);
        $services = $this->getRepository(Service::class)->getServicesByIds($servicesIds);
        if (count($services) === 0) {
            $this->addFlash('danger', 'Услуги не найдены');
            return $this->redirectToRoute('referral_create', ['id' => $request->get('id_participant')]);
        }

        /** @var Provider $provider */
        $provider = $this->getRepository(Provider::class)->find($request->get('id_provider', 0));

        /** @var Participant $participant */
        $participant = $this->getRepository(Participant::class)->find($request->get('id_participant', 0));

        $content = $request->get('content', null);

        $referral = new Referral();
        $referral->setProvider($provider);
        $referral->setParticipant($participant);
        $referral->setContent($content);
        $referral->setServices($services);
        $referral->setStatus(Referral::STATUS_NEW);

        $participant->addProvider($provider);

        $manager = $this->getDoctrine()->getManager();
        $manager->persist($referral);
        $manager->flush();

        $this->addFlash('success', sprintf('Заявка # %s на организацию \'%s\' успешно создана!', $referral->getId(), $provider->getName()));
        return $this->redirectToRoute('participant_referrals', ['id' => $participant->getId()]);
    }
}
