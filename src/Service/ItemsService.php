<?php
namespace App\Service;

/***********************************************************************
 *
 * (c) 2022 Frank Krüger <fkrueger@mp-group.net>, mp group GmbH
 *
 ***********************************************************************/

use App\Entity\Main\Items;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Service for items (id numbers)
 */
class ItemsService
{
    /**
     * @var EntityManagerInterface emDefault
     */
    protected $emDefault;

    /**
     * @var FlashBagInterface $flashBag
     */
    protected $flashBag;

    /**
     * @var TranslatorInterface $translator
     */
    protected $translator;


    /**
     * constructor
     *
     * @param ManagerRegistry $registry
     * @param SessionInterface $session
     * @param TranslatorInterface $translator
     */
    public function __construct(
        ManagerRegistry $registry,
        SessionInterface $session,
        TranslatorInterface $translator
    ) {
        $this->emDefault = $registry->getManager('default');
        $this->flashBag = $session->getFlashBag();
        $this->translator = $translator;
    }


    /**
     * check idno
     *
     * @param string $idno
     * @param string $flashBagType
     * @param string $purpose
     *
     * @return ?Items
     */
    public function check($idno, $flashBagType = 'itemCheck', $purpose = 'register'): ?Items
    {
        $idno = strtoupper($idno);

        // format check
        if (
            !preg_match(
                '/'.Items::IDNO_PATTERN_UPPER.'/',
                $idno
            )
        ) {
            // flash bag?
            if(!empty($flashBagType)) {
                $this->flashBag->add(
                    $flashBagType,
                    $this->translator->trans('Wrong ID-Number format!')
                );
            }
            return null;
        }

        // get item
        $item = $this->emDefault
            ->getRepository(Items::class)
            ->findOneByIdNo($idno);

        // not found
        if($item === null) {

            // flash bag?
            if(!empty($flashBagType)) {
                $this->flashBag->add(
                    $flashBagType,
                    $this->translator->trans('ID-Number not found!')
                );
            }
            return null;
        }

        // switch item status (noStatus)
        switch ($item->getNoStatus()) {
            case 'deaktiviert':

                // flash bag?
                if(!empty($flashBagType)) {
                    $this->flashBag->add(
                        $flashBagType,
                        $this->translator->trans('ID-Number locked!')
                    );
                }
                return null;
                break;

            // registered
            case 'registriert':

                // purpose register
                if($purpose === 'register') {

                    // flash bag?
                    if(!empty($flashBagType)) {
                        $this->flashBag->add(
                            $flashBagType,
                            $this->translator->trans('ID-Number already registered!')
                        );
                    }
                    return null;
                }
                break;
        }

        // finally return $item
        return $item;
    }
}