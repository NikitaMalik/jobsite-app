<?php

namespace AppBundle\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use AppBundle\Entity\Job;
use AppBundle\Form\JobType;

use Symfony\Component\HttpFoundation\Request;


class JobController extends Controller {

    /**
     * @Route("/random")
     */
    public function numberAction() {
        $number = mt_rand(0, 100);

        return $this->render('random.html.twig', array(
                    'number' => $number,
        ));
    }

    /**
     * @Route("/create/job")
     */
    public function createAction(Request $request) {
        $em = $this->getDoctrine()->getManager();

        $job = new Job();

        $form = $this->createForm(JobType::class, $job);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($job);
            $em->flush();
            return $this->redirectToRoute('list_job');
        }

        return $this->render(
                        'job/add_job.html.twig', array('form' => $form->createView())
        );

    }

    /**
     * @Route("/job", name="list_job")
     */
    public function showAction() {

        $repository = $this->getDoctrine()->getRepository(Job::class);

        $jobs = $repository->findAll();

        if (is_null($jobs)) {
            throw $this->createNotFoundException(
                    'No jobs found '
            );
        }

        return $this->render('job/list_job.html.twig', array('jobs' => $jobs));
    }

}
