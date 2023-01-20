<?php


namespace App\Service;

use Knp\Snappy\Pdf;
use Twig\Environment;
use App\Service\Cart\CartService;
use App\Repository\CartRepository;
use App\Repository\OrderDetailsRepository;
use Symfony\Component\Security\Core\User\UserInterface;

class PdfTools
{

    private $pdf;
    private $cartRepository;
    private $orderDetails;
    private $cartService;
    private $templating;


    public function __construct(Pdf $pdf, CartRepository $cartRepository, CartService $cartService, OrderDetailsRepository $orderDetails, ?UserInterface $user, Environment $templating)
    {
        $this->pdf = $pdf;
        $this->cartRepository = $cartRepository;
        $this->orderDetails = $orderDetails;
        $this->cartService = $cartService;
        $this->user = $user;
        $this->templating = $templating;
    }

    // Function to generate an invoice for a given order ID
    public function generateInvoice($orderId)
    {

        // Finds the order based on the order ID
        $order = $this->cartRepository->find($orderId);

        // Gets the user associated with the order
        $user = $this->cartService->getUser($orderId);

        // Gets the order details for the order
        $details = $this->orderDetails->findBy(['cart' => $orderId]);

        // Renders the invoice HTML template and pass in the order, details, and user data
        $html = $this->templating->render('email/invoice.html.twig', array(
            "order" => $order,
            'details' => $details,
            'user' => $user,
        ));

        // Generates a PDF from the HTML and saves it to the 'invoices' directory with the order's client order ID as the filename
        $this->pdf->generateFromHtml($html, '../public/invoices/INVOICE-' . $order->getClientOrderId() . '.pdf', [], true);
    }
}
