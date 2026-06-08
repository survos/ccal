<?php


// uses Survos Param Converter, from the UniqueIdentifiers method of the entity.

namespace App\Controller;

use App\Entity\Cal;
use App\Form\CalType;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\CalRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Workflow\WorkflowInterface;

#[Route('/cal/{calId}')]
class CalController extends AbstractController 
{

public function __construct(private EntityManagerInterface $entityManager) {

}

#[Route(path: '/transition/{transition}', name: 'cal_transition')]
public function transition(Request $request, WorkflowInterface $calStateMachine, string $transition, Cal $cal): Response
{
if ($transition === '_') {
$transition = $request->request->get('transition'); // the _ is a hack to display the form, @todo: cleanup
}

$this->handleTransitionButtons($calStateMachine, $transition, $cal);
$this->entityManager->flush(); // to save the marking
return $this->redirectToRoute('cal_show', $cal->getRP());
}

#[Route('/', name: 'cal_show', options: ['expose' => true])]
    public function show(Cal $cal): Response
    {
        return $this->render('cal/show.html.twig', [
            'cal' => $cal,
        ]);
    }

#[Route('/edit', name: 'cal_edit', options: ['expose' => true])]
    public function edit(Request $request, Cal $cal): Response
    {
        $form = $this->createForm(CalType::class, $cal);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('cal_index');
        }

        return $this->render('cal/edit.html.twig', [
            'cal' => $cal,
            'form' => $form->createView(),
        ]);
    }

#[Route('/delete', name: 'cal_delete', methods:['DELETE'])]
    public function delete(Request $request, Cal $cal): Response
    {
        // hard-coded to getId, should be get parameter of uniqueIdentifiers()
        if ($this->isCsrfTokenValid('delete'.$cal->getId(), $request->request->get('_token'))) {
            $entityManager = $this->entityManager;
            $entityManager->remove($cal);
            $entityManager->flush();
        }

        return $this->redirectToRoute('cal_index');
    }
}
