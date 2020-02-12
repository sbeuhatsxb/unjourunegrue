<?php


namespace App\Service;


use App\Entity\PictureOfTheDay;
use Doctrine\Persistence\ObjectManager;
use FilesystemIterator;
use Symfony\Component\Filesystem\Filesystem;

class ManagePictures
{
    //Base directory
    const DIR = "build/pictures";

    const BACKUP = "build/pictures/passedPictures";

    /**
     * @var ObjectManager
     */
    private $objectManager;

    public function __construct(ObjectManager $objectManager)
    {
        $this->objectManager = $objectManager;
    }

    function is_dir_empty($dir)
    {

        //Checking if picture pile directory is empty (returns FALSE if not empty)
        $fi = new FilesystemIterator($dir, FilesystemIterator::SKIP_DOTS);
        if (!is_readable($dir)) return NULL;
        return (($fi->getType() === "dir") == true);
    }

    public function pictureOfTheDayManager()
    {
        $files = scandir(self::DIR);
        $backupFiles = scandir(self::BACKUP);

        //Checking if folder is empty
        if ($this->is_dir_empty(self::DIR)) {

            $this->restoreAllPictures($backupFiles);
            $this->pictureOfTheDayManager();

        } else {

            //Taking the first file and moving it to pictureOfTheDay
            foreach ($files as $file) {

                $ext = pathinfo($file, PATHINFO_EXTENSION);

                if ($ext != "jpg") {
                    //Skipping "." and ".." files
                    continue;
                } else {

                    $pictureRepo = $this->objectManager->getRepository(PictureOfTheDay::class)->findAll();

                    if (empty($pictureRepo)) {
                        $this->registerPictureOfTheDay($file);
                        $filesystem = new Filesystem();
                        $filesystem->copy(self::DIR . "/" . $file, self::DIR . "/pictureOfTheDay/PictureOfTheDay.jpg", true);
                    };

                    //Test if BDD is empty, else it means that we're initializing the website
                    foreach ($pictureRepo as $registeredPicture) {
                        $today = new \DateTime();
                        $currentPictureDate = $registeredPicture->getDate();
                        $interval = $today->format("d") - $currentPictureDate->format("d");

                        //Checking if last registered picture lasts from yersterday or more
                        if ($interval != 0) {
                            $this->objectManager->remove($registeredPicture);
                            $this->moveFiles($file, $registeredPicture->getFilename());
                            $this->registerPictureOfTheDay($file);

                        } else {
                            break;
                        }
                    }
                    break;
                };
            }
        }
    }

    private function registerPictureOfTheDay($file)
    {

        $pictureOfTheDayRegistering = new PictureOfTheDay();
        $pictureOfTheDayRegistering->setFilename($file);
        $pictureOfTheDayRegistering->setDate(new \DateTime());
        $this->objectManager->persist($pictureOfTheDayRegistering);

        $this->objectManager->flush();
    }

    private function moveFiles($file, $registeredPicture)
    {

        $filesystem = new Filesystem();
        //Savefile
        $filesystem->copy(self::DIR . "/pictureOfTheDay/PictureOfTheDay.jpg", self::DIR . "/passedPictures/" . $registeredPicture, true);
        //copy file to the current directory
        $filesystem->copy(self::DIR . "/" . $file, self::DIR . "/pictureOfTheDay/PictureOfTheDay.jpg", true);
        //remove it fromthe bank
        $filesystem->remove(self::DIR . "/" . $file);
    }

    private function restoreAllPictures($backupfiles)
    {

        $filesystem = new Filesystem();

        foreach ($backupfiles as $file) {

            $ext = pathinfo($file, PATHINFO_EXTENSION);

            if ($ext != "jpg") {
                //Skipping "." and ".." files
                continue;
            } else {

                //Backup files from passedPictures to current directory
                $filesystem->copy(self::BACKUP . "/" . $file, self::DIR . "/" . $file, true);
                //remove it from the bank
                $filesystem->remove(self::BACKUP . "/" . $file);
            }
        }
        sleep(2);
    }
}