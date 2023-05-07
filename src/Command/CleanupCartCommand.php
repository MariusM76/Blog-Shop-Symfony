<?php

namespace App\Command;

use App\Entity\Cart;
use App\Repository\CartRepository;
use App\Service\CartService;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CleanupCartCommand extends Command
{
    protected static $defaultName = 'app:cleanup-cart';
    protected static $defaultDescription = 'Clean up empty carts';

    /**
     * @param CartRepository $cartRepository
     */

    /** @var EntityManagerInterface */
    protected $entityManager;

    public function __construct(string $name = null, CartRepository $cartRepository, EntityManagerInterface $entityManager)
    {
        parent::__construct($name);
        $this->cartRepository = $cartRepository;
        $this->entityManager = $entityManager;
    }

    protected function configure(): void
    {
        $this
            ->setDescription(self::$defaultDescription)
            ->addArgument('product_limit', InputArgument::OPTIONAL, 'Cart product limitn',0)
            ->addOption('list_id', 'l', InputOption::VALUE_NONE, 'List deleted cart ID')
        ;
    }

    /** @var CartRepository */
    protected $cartRepository;



    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $product_limit = $input->getArgument('product_limit');

//        $io->warning('Your product limit is '.$product_limit);

        $carts = $this->cartRepository->findAll();

        foreach ($carts as $cart){
            if(CartService::countCartProducts($cart) <= $product_limit ){
                $this->entityManager->remove($cart);
                if ($input->getOption('list_id')){
                    $io->success('Cart with ID  '.$cart->getId().' has been deleted');
                }
                $this->entityManager->remove($cart);
                $this->entityManager->flush();
            }
        }
        return 0;
    }
}
