<?php declare(strict_types=1);

namespace App\Controller;

use App\Entity\Referral;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ReferralController extends BaseController
{
    /**
     * @Route("/referral/{id}/success", name="referral_success", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
    */
    public function success(int $id)
    {
        return ['referral' => $this->getRepository(Referral::class)->find($id)];
    }

    /**
     * @Route("/referral/success", name="referral_success_process", methods={"POST"})
     */
    public function successProcess(Request $request)
    {
        /** @var Referral $referral */
        $referral = $this->getRepository(Referral::class)->find($request->get('id', 0));
        $referral->setStatus(Referral::STATUS_DONE);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('Заявка # \'%s\' успешно выполнена!', $referral->getId()));
        return $this->redirectToRoute('participant_referrals', ['id' => $referral->getParticipant()->getId()]);
    }

    /**
     * @Route("/referral/{id}/reject", name="referral_reject", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
     */
    public function reject(int $id)
    {
        return ['referral' => $this->getRepository(Referral::class)->find($id)];
    }

    /**
     * @Route("/referral/reject", name="referral_reject_process", methods={"POST"})
     */
    public function rejectProcess(Request $request)
    {
        /** @var Referral $referral */
        $referral = $this->getRepository(Referral::class)->find($request->get('id', 0));
        $referral->setStatus(Referral::STATUS_REJECT);
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('Заявка # \'%s\' отклонена!', $referral->getId()));
        return $this->redirectToRoute('participant_referrals', ['id' => $referral->getParticipant()->getId()]);
    }

    /**
     * @Route("/referral/{id}", name="referral_details", methods={"GET"}, requirements={"id":"\d+"})
     * @Template()
     */
    public function details(int $id)
    {
        return ['referral' => $this->getRepository(Referral::class)->find($id)];
    }
}
