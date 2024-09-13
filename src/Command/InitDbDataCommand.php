<?php

namespace App\Command;

use App\Entity\JobOffer;
use App\Entity\User;
use App\Repository\JobOfferRepository;
use App\Repository\UserRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:init-db-data',
    description: 'Command supplements the database with sample values of job offers and users.',
)]
class InitDbDataCommand extends Command
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly JobOfferRepository     $jobOfferRepository,
        private readonly UserRepository         $userRepository,
        string                                  $name = null
    )
    {
        parent::__construct($name);
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $jobOffers = [
            new JobOffer('PHP Developer', 'We are looking for a skilled PHP Developer to join our growing team. You will be responsible for developing new features, maintaining the current system, and ensuring top performance of web applications. Experience with Symfony is a plus.', new DateTime('2024-01-05')),
            new JobOffer('Full Stack Developer', 'The Full Stack Developer will be tasked with developing both front-end and back-end systems. A deep understanding of JavaScript, PHP, and frameworks like Symfony and Vue.js is required.', new DateTime('2024-02-15')),
            new JobOffer('Backend Developer', 'As a Backend Developer, you will focus on server-side logic, databases, and ensuring high-performance requests and response management. Strong knowledge of PHP and MySQL is crucial.', new DateTime('2024-03-12')),
            new JobOffer('Frontend Developer', 'We are searching for a creative Frontend Developer with proficiency in HTML, CSS, and JavaScript. Knowledge of Vue.js and other front-end frameworks is an advantage.', new DateTime('2024-04-07')),
            new JobOffer('DevOps Engineer', 'As a DevOps Engineer, you will be responsible for the continuous integration and deployment of our application infrastructure. Experience with cloud platforms and containerization is preferred.', new DateTime('2024-05-18')),
            new JobOffer('Software Architect', 'The Software Architect will design and guide the evolution of software systems, ensuring high scalability and efficiency. In-depth experience with PHP, Symfony, and enterprise-level applications is essential.', new DateTime('2024-06-25')),
            new JobOffer('Mobile App Developer', 'The Mobile App Developer will work on native and hybrid mobile applications, optimizing for both Android and iOS platforms. Experience with React Native or Flutter is a plus.', new DateTime('2024-07-03')),
            new JobOffer('QA Engineer', 'As a QA Engineer, you will be responsible for designing, executing, and automating test cases to ensure the highest quality of our products. Familiarity with testing tools such as PHPUnit is a must.', new DateTime('2024-08-11')),
            new JobOffer('Database Administrator', 'The Database Administrator will manage and optimize database performance, ensuring data integrity and availability. Strong expertise in MySQL and database replication strategies is required.', new DateTime('2024-09-01')),
            new JobOffer('Scrum Master', 'We are looking for an experienced Scrum Master to facilitate Agile processes and ensure team productivity and workflow efficiency. Familiarity with Agile frameworks such as Scrum and Kanban is necessary.', new DateTime('2024-10-05')),
            new JobOffer('UI/UX Designer', 'As a UI/UX Designer, you will be responsible for designing and optimizing user interfaces and experiences for our web and mobile applications. Strong experience with tools such as Figma or Adobe XD is expected.', new DateTime('2024-11-12')),
            new JobOffer('Project Manager', 'The Project Manager will be responsible for overseeing project timelines, resource allocation, and client communication. Prior experience in managing large software development projects is required.', new DateTime('2024-12-01')),
            new JobOffer('Cybersecurity Specialist', 'The Cybersecurity Specialist will protect our systems from security threats, perform regular audits, and implement robust security measures. Experience with threat analysis and mitigation is a must.', new DateTime('2024-09-18')),
            new JobOffer('System Administrator', 'The System Administrator will manage and maintain our network infrastructure, ensuring smooth operations across all systems. Experience with Linux-based systems is highly valued.', new DateTime('2024-06-30')),
            new JobOffer('AI/ML Engineer', 'The AI/ML Engineer will develop machine learning models and algorithms to improve business processes. Familiarity with frameworks like TensorFlow and PyTorch is important.', new DateTime('2024-05-23')),
            new JobOffer('Technical Writer', 'The Technical Writer will create and maintain technical documentation for our software products, ensuring clarity and accuracy. Experience in writing for developers and IT professionals is essential.', new DateTime('2024-07-21')),
            new JobOffer('Product Owner', 'The Product Owner will work closely with development teams to define product vision, prioritize features, and ensure alignment with market needs. Strong communication and decision-making skills are essential.', new DateTime('2024-04-15')),
            new JobOffer('Business Analyst', 'The Business Analyst will analyze business processes, identify opportunities for improvement, and work with teams to implement changes. Prior experience with Agile methodologies is beneficial.', new DateTime('2024-03-02')),
            new JobOffer('Support Engineer', 'The Support Engineer will assist clients in troubleshooting technical issues, providing expert advice, and ensuring smooth operations. Strong problem-solving skills and technical knowledge are required.', new DateTime('2024-02-28')),
            new JobOffer('Cloud Architect', 'The Cloud Architect will design and implement cloud infrastructure solutions to support business growth. Extensive experience with AWS, Azure, or GCP is required.', new DateTime('2024-01-18')),
            new JobOffer('Marketing Specialist', 'As a Marketing Specialist, you will be responsible for developing and executing marketing campaigns to promote our products and services. You will collaborate with cross-functional teams to create content, track campaign performance, and optimize marketing strategies. Strong analytical skills and experience with digital marketing tools are essential.', new DateTime('2024-01-22')),
            new JobOffer('Sales Representative', 'We are looking for a dynamic Sales Representative to join our growing team. Your role will involve reaching out to potential clients, delivering presentations, and closing deals. Experience in B2B sales and excellent communication skills are crucial for success in this position.', new DateTime('2024-02-05')),
            new JobOffer('Customer Service Specialist', 'The Customer Service Specialist will assist customers with inquiries, provide product information, and resolve issues efficiently. You will play a key role in maintaining customer satisfaction and loyalty. Strong interpersonal skills and experience with CRM systems are preferred.', new DateTime('2024-03-09')),
            new JobOffer('Content Writer', 'We are seeking a talented Content Writer to create engaging and SEO-optimized articles, blogs, and web content. You will work closely with the marketing team to ensure our content reflects the brand voice and attracts target audiences. Excellent writing and editing skills are required.', new DateTime('2024-04-18')),
            new JobOffer('Graphic Designer', 'The Graphic Designer will be responsible for creating visual content for our marketing campaigns, including brochures, social media graphics, and digital ads. You should have a strong portfolio demonstrating proficiency in Adobe Creative Suite and other design tools.', new DateTime('2024-05-06')),
            new JobOffer('HR Manager', 'As the HR Manager, you will oversee all human resource functions, including recruitment, employee relations, and performance management. You will work closely with leadership to ensure the company adheres to labor laws and fosters a positive work environment.', new DateTime('2024-06-13')),
            new JobOffer('Operations Manager', 'The Operations Manager will ensure that our daily operations run smoothly, focusing on efficiency and quality. You will manage teams, coordinate logistics, and streamline processes. A strong background in operations management and problem-solving skills is needed.', new DateTime('2024-07-04')),
            new JobOffer('Restaurant Manager', 'We are seeking an experienced Restaurant Manager to oversee the day-to-day operations of our establishment. You will be responsible for staff management, customer satisfaction, and ensuring that health and safety standards are maintained. Prior experience in the hospitality industry is essential.', new DateTime('2024-08-21')),
            new JobOffer('Event Coordinator', 'The Event Coordinator will plan and execute various events, including corporate conferences, weddings, and community gatherings. You will be responsible for coordinating vendors, managing budgets, and ensuring the smooth execution of events. Strong organizational skills are a must.', new DateTime('2024-09-19')),
            new JobOffer('Logistics Coordinator', 'We are looking for a Logistics Coordinator to manage the shipment and delivery of goods. You will work with suppliers, carriers, and customers to ensure that products are delivered on time and within budget. Experience with supply chain management and logistics software is required.', new DateTime('2024-10-08')),
            new JobOffer('Accountant', 'The Accountant will handle financial transactions, prepare reports, and ensure compliance with tax regulations. You will maintain accurate records and collaborate with the finance team to provide insights on financial performance. Proficiency in accounting software is expected.', new DateTime('2024-11-30')),
            new JobOffer('Fitness Instructor', 'As a Fitness Instructor, you will lead group fitness classes and provide personalized training sessions to clients. Your role is to motivate participants, create fitness plans, and monitor progress. Certification in fitness instruction and a passion for health and wellness are important.', new DateTime('2024-12-12')),
            new JobOffer('Retail Store Manager', 'The Retail Store Manager will oversee all aspects of the store’s operations, including staff supervision, inventory management, and sales performance. You will ensure a high level of customer service and work to meet sales targets. Previous experience in retail management is required.', new DateTime('2024-01-11')),
            new JobOffer('Tour Guide', 'We are seeking an enthusiastic Tour Guide to provide engaging tours of historical sites and local attractions. You will be responsible for educating tourists about the area’s history, culture, and points of interest. Fluency in multiple languages is a plus.', new DateTime('2024-02-24')),
            new JobOffer('Chef', 'The Chef will manage the kitchen, plan menus, and prepare high-quality meals for our restaurant. You will oversee food preparation, staff training, and ensure that all meals meet our quality standards. Culinary school certification and several years of experience are essential.', new DateTime('2024-03-17')),
            new JobOffer('Nurse', 'As a Nurse, you will provide patient care in a healthcare setting, including administering medication, monitoring vitals, and assisting with medical procedures. Compassion and strong attention to detail are essential for this role. A valid nursing license is required.', new DateTime('2024-04-26')),
            new JobOffer('Teacher', 'We are looking for a dedicated Teacher to educate students in a primary or secondary school setting. You will be responsible for creating lesson plans, delivering lectures, and assessing student progress. A teaching certification and experience in the subject area are required.', new DateTime('2024-05-14')),
            new JobOffer('Veterinarian', 'The Veterinarian will diagnose and treat a variety of animals, providing medical care and performing surgeries when necessary. You will work closely with pet owners to ensure their animals’ well-being. A degree in veterinary medicine and relevant experience are required.', new DateTime('2024-06-02')),
            new JobOffer('Real Estate Agent', 'We are seeking a Real Estate Agent to help clients buy, sell, and rent properties. You will assist clients in navigating the real estate market, provide property showings, and negotiate contracts. A real estate license and strong negotiation skills are necessary.', new DateTime('2024-07-15')),
            new JobOffer('Construction Manager', 'The Construction Manager will oversee construction projects from start to finish, ensuring that they are completed on time, within budget, and in accordance with safety standards. You will coordinate with contractors, suppliers, and clients to achieve project goals. Experience in construction management is essential.', new DateTime('2024-08-09')),
        ];

        foreach ($jobOffers as $jobOffer) {
            $existingSetting = $this->jobOfferRepository->findOneBy([
                'title' => $jobOffer->getTitle(),
                'description' => $jobOffer->getDescription()
            ]);

            if ($existingSetting == null) {
                $this->em->persist($jobOffer);
            }
        }

        $user = $this->userRepository->findOneBy(['email' => 'admin@admin.com']);
        if (!$user) {
            $user = new User();
        }
        $user->setName('Jan');
        $user->setLastname('Kowalski');
        $user->setEmail('admin@admin.com');
        $user->setRoles(['ROLE_ADMIN']);
        $user->setPhoneNumber('897123456');
//      password: admin
        $user->setPassword('$2y$13$hDWrVTh6zAFel2gyVA2S1.3y0aNap4V9WzVXV.x2q1ori45Bscm/a');

        $this->em->persist($user);
        $this->em->flush();
        $io->success('Database completed successfully!');

        return Command::SUCCESS;
    }
}
