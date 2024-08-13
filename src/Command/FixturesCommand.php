<?php


namespace App\Command;

use App\Entity\Advice;
use App\Entity\Month;
use App\Entity\User;
use Doctrine\DBAL\Exception;
use Doctrine\ORM\EntityManagerInterface;
use PhpParser\Node\Expr\Array_;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'app:fixtures', description: 'generate fixtures')]
class FixturesCommand extends Command
{

    private EntityManagerInterface $entityManager;

private array $monthArray;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->monthArray = [];
        parent::__construct();
    }

    /**
     * @throws Exception
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        $io->title('Generate fixtures');
        $io->info("you are about to generate fixtures");

        if (!$io->confirm("Are you sure to perform this operation ?", !$input->isInteractive())) {
            $io->writeln("Abort");
            return self::SUCCESS;
        }

        $this->resetData($this->entityManager);

        $this->createUser();

        $this->createMonth();

        $this->createAdvice();


        $this->entityManager->flush();

        return Command::SUCCESS;
    }

    /**
     * @throws Exception
     */
    private function resetData(EntityManagerInterface $manager): void
    {
        $connection = $manager->getConnection();
        $schemaManager = $connection->createSchemaManager();
        $tables = $schemaManager->listTableNames();

        $connection->executeStatement("SET FOREIGN_KEY_CHECKS = 0;");

        foreach ($tables as $table) {
            $connection->executeStatement("TRUNCATE TABLE $table");
        }
        $connection->executeStatement("SET FOREIGN_KEY_CHECKS = 1;");
    }

    private function createUser(): void
    {
        for ($i = 0; $i < 10; $i++) {

            $user = new User();
            $plainTextPassword = 'secret';
            $hashedPassword = password_hash($plainTextPassword, PASSWORD_DEFAULT);

            $user
                ->setEmail('user_' . $i . '@example.com')
                ->setPassword($hashedPassword)
                ->setPostalCode($i + 0000);
            if ($i % 2 === 0) {
                $user->setRoles(['ROLE_USER', 'ROLE_ADMIN']);
            }

            $this->entityManager->persist($user);
        }
    }

    private function createMonth(): void
    {
        $monthsName = ['January', 'February', 'March', 'April', 'May', 'June', 'July', 'August', 'September', 'October', 'November', 'December'];
        for ($i = 0; $i <= 11; $i++) {
            $mounth = new Month();

            $mounth->setMonthNumber($i + 1);
            $mounth->setMonthName($monthsName[$i]);

            $this->entityManager->persist($mounth);
            $this->monthArray[] = $mounth;
        }
    }

    private function createAdvice(): void
    {
        $advice = new Advice();
        $advice->setContent("ne te decouvre pas d'un fil");

        $advice->addMonth($this->monthArray[3]);
        $advice->addMonth($this->monthArray[4]);

        $this->entityManager->persist($advice);

        $advice = new Advice();
        $advice->setContent("la baignade c'est aussi pour les tomates");

        $advice->addMonth($this->monthArray[7]);
        $advice->addMonth($this->monthArray[8]);

        $this->entityManager->persist($advice);

        $advice = new Advice();
        $advice->setContent("penser a recolter la marijuanga");

        $advice->addMonth($this->monthArray[11]);

        $this->entityManager->persist($advice);
    }

}
