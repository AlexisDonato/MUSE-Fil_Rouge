<?php

namespace App\Controller;

use DateTime;
use Knp\Snappy\Pdf;
use App\Entity\Cart;
use Twig\Environment;
use App\Service\PdfTools;
use App\Service\Cart\CartService;
use Symfony\Component\Mime\Email;
use App\Repository\CartRepository;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\OrderDetailsRepository;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Knp\Bundle\SnappyBundle\Snappy\Response\PdfResponse;
use Symfony\WebpackEncoreBundle\Asset\EntrypointLookupInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TestController extends AbstractController
{
    private $twig;
    private $pdf;
    private $cartRepository;
    private $cartService;
    private $orderDetails;

    public function __construct(?Request $request, Environment $twig, Pdf $pdf, EntrypointLookupInterface $entrypointLookup, ?CartService $cartService, ?CartRepository $cartRepository, ?Cart $cart, ?UserInterface $user, ?EntityManagerInterface $entityManager, OrderDetailsRepository $orderDetails, MailerInterface $mailer)
    {
        $this->twig = $twig;
        $this->pdf = $pdf;
        $this->cartRepository = $cartRepository;
        $this->cartService = $cartService;
        $this->orderDetails = $orderDetails;

        $this->entrypointLookup = $entrypointLookup;

        // $orderId = $this->request->attributes->get('id');
    }


    #[Route('/test', name: 'app_test')]
    public function pdfAction(Environment $twig, Pdf $pdf, Request $request, EntrypointLookupInterface $entrypointLookup, ?CartService $cartService, ?CartRepository $cartRepository, ?Cart $cart, ?UserInterface $user, ?EntityManagerInterface $entityManager, OrderDetailsRepository $orderDetails, MailerInterface $mailer)
    {
        $orderId = $request->attributes->get('id');
                
        $details = $orderDetails->findBy(['cart' => $orderId]);

        $cartService->setUser($user);
        $clientOrderId = $cart->getClientOrderId();
        
        $carrier = $cart->getCarrier();
        $carrierShipmentId= $cart->getCarrierShipmentId();
        $shipmentDate = $cart->getShipmentDate();
    
        $total = $cart->getTotal();
    
        $user = $cart->getUser();

        $html = $this->renderView('email/invoice.html.twig', array(
                'order_id' => $clientOrderId,
                'cart_id' => $orderId,
                'details' => $details,
                'orderDate' => $orderDate,
                'shipped' => $cart->isShipped(),
                'shipmentDate' => $shipmentDate,
                'carrier' =>$carrier,
                'carrierShipmentId' => $carrierShipmentId,
                'user' => $user,
                'total' => $total,
        ));


        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            'file.pdf'
        

        );
        $this->entrypointLookup->reset();
    }


    #[Route('/test/download', name: 'app_test_download')]
    public function DlPdfAction(PdfTools $pt, Environment $twig, Pdf $pdf, EntrypointLookupInterface $entrypointLookup, ?CartService $cartService, ?CartRepository $cartRepository, ?Cart $cart, ?UserInterface $user, ?EntityManagerInterface $entityManager, OrderDetailsRepository $orderDetails, MailerInterface $mailer)
    {
        $pdf_file_path = '/PDFs';

        // $pt->generateOrder($orderId);

        $html = $this->renderView('email/test.html.twig', array(
        ));

    // $html = $this->generateUrl('app_home', array(), true); // use absolute path! -> Render a pdf document with a relative url inside like css files

        return new PdfResponse(
            $pdf->getOutputFromHtml($html),
            // $pdf->getOutput($pageUrl), // To render a pdf document with a relative url inside like css files
            // $pdf->getOutput('email/test.html.twig',array('ignore-load-errors'=>true)),
            'M_O-'.date('Y-m-d').'.pdf'
        );
        $this->entrypointLookup->reset();
    }

    #[Route('/test2', name: 'app_test2')]
    public function test2(PdfTools $pt, CartRepository $cart,  MailerInterface $mailer)
    {
        $pdf_file_path = '/PDFs';
        // $clientOrderId = $cart->getClientOrderId();
        $pt->generateInvoice(94);

        // $email = (new TemplatedEmail())
        //         ->from(new \Symfony\Component\Mime\Address('muse.info.bot@gmail.com', 'Muse MailBot'))
        //         ->to("toto@gmail.com")
        //         ->cc('muse.info.bot@gmail.com')
        //         ->subject('Votre commande est validée!')
        //         ->htmlTemplate('email/test2.html.twig')
        //         ->context([
        //         ])
        //         ->attachFromPath("/home/alex/AFPA/CDA/Fil Rouge/MUSE/doc/Invoice-1.pdf");

        // $email = (new Email())
        //     ->from('hello@example.com')
        //     ->to('you@example.com')
        //     //->cc('cc@example.com')
        //     //->bcc('bcc@example.com')
        //     //->replyTo('fabien@example.com')
        //     //->priority(Email::PRIORITY_HIGH)
        //     ->subject('Time for Symfony Mailer!')
        //     ->text('Sending emails is fun again!')
        //     ->html('<p>See Twig integration for better HTML integration!</p>')
        //     ->attachFromPath('/home/alex/AFPA/CDA/Fil Rouge/MUSE/doc/Invoice-'.$clientOrderId.'.pdf');
        //     $mailer->send($email);

            
        return new Response("ok");
    }

    #[Route('/test3/{orderId}', name: 'app_test3')]
    public function test3($orderId)
    {
        $order = $this->cartRepository->find($orderId);

        $user = $this->cartService->getUser($orderId);

        $details = $this->orderDetails->findBy(['cart' => $orderId]);


        // $clientOrderId = $this->cartRepository->getClientOrderId($orderId);

        return $this->render('email/invoice.html.twig', array(
            "order" => $order,
            'details' => $details,
            'user' => $user,
        ));



    }

    #[Route('/testc', name: 'app_testc')]
    public function testc(CategoryRepository $repo)
    {

        $details = $repo->findByParent(13);

        dd($details);

        return new Response("ok");



    }

}



