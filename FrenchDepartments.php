<?php
/*************************************************************************************/
/*      This file is part of the Thelia package.                                     */
/*                                                                                   */
/*      Copyright (c) OpenStudio                                                     */
/*      email : dev@thelia.net                                                       */
/*      web : http://www.thelia.net                                                  */
/*                                                                                   */
/*      For the full copyright and license information, please view the LICENSE.txt  */
/*      file that was distributed with this source code.                             */
/*************************************************************************************/

namespace FrenchDepartments;

use Propel\Runtime\Connection\ConnectionInterface;
use Thelia\Model\Address;
use Thelia\Model\AddressQuery;
use Thelia\Model\Country;
use Thelia\Model\CountryAreaQuery;
use Thelia\Model\CountryQuery;
use Thelia\Model\State;
use Thelia\Model\StateI18n;
use Thelia\Model\StateQuery;
use Thelia\Model\TaxRuleCountryQuery;
use Thelia\Module\BaseModule;
use TheliaMigrateCountry\Events\MigrateCountryEvent;

class FrenchDepartments extends BaseModule
{
    /** @var string */
    const DOMAIN_NAME = 'frenchdepartments';

    /**
     * Import French departments in `state` and `state_i18n` DB tables
     *
     * @param ConnectionInterface|null $con
     */
    public function postActivation(ConnectionInterface $con = null)
    {
        $franceCountryId = CountryQuery::create()
            ->filterByIsoalpha3('FRA')
            ->select('id')
            ->findOne();

        if (null === $franceCountryId || null !== StateQuery::create()->findOneByCountryId($franceCountryId)) {
            return;
        }

        $departments = $this->getDepartments();

        foreach ($departments as $department) {
            $departmentLocales = [
                'de_DE' => null,
                'en_US' => null,
                'es_ES' => null,
                'fr_FR' => $department['title'],
            ];

            $state = (new State())
                ->setVisible(1)
                ->setIsocode($department['isocode'])
                ->setCountryId($franceCountryId);

            $state->save();

            $oldCountry = CountryQuery::create()
                ->filterByHasStates(0)
                ->filterByIsoalpha2($department['isocode'])
                ->findOne();

            if (null !== $oldCountry) {
                 $this->migrateOldCountry($oldCountry, $state, $franceCountryId);
            }

            foreach ($departmentLocales as $locale => $title) {
                (new StateI18n())
                    ->setId($state->getId())
                    ->setLocale($locale)
                    ->setTitle($title)
                    ->save();
            }
        }

        $this->updateAddresses($franceCountryId);
    }

    /**
     * Real ISO codes follow this format: 'FR-XX' but the database saves only 4 characters
     *
     * @return array
     */
    protected function getDepartments()
    {
        return [
            [
                'isocode' => '01',
                'title' => 'Ain'
            ],
            [
                'isocode' => '02',
                'title' => 'Aisne'
            ],
            [
                'isocode' => '03',
                'title' => 'Allier'
            ],
            [
                'isocode' => '04',
                'title' => 'Alpes-de-Haute-Provence'
            ],
            [
                'isocode' => '05',
                'title' => 'Hautes-Alpes'
            ],
            [
                'isocode' => '06',
                'title' => 'Alpes-Maritimes'
            ],
            [
                'isocode' => '07',
                'title' => 'Ardèche'
            ],
            [
                'isocode' => '08',
                'title' => 'Ardennes'
            ],
            [
                'isocode' => '09',
                'title' => 'Ariège'
            ],
            [
                'isocode' => '10',
                'title' => 'Aube'
            ],
            [
                'isocode' => '11',
                'title' => 'Aude'
            ],
            [
                'isocode' => '12',
                'title' => 'Aveyron'
            ],
            [
                'isocode' => '13',
                'title' => 'Bouches-du-Rhône'
            ],
            [
                'isocode' => '14',
                'title' => 'Calvados'
            ],
            [
                'isocode' => '15',
                'title' => 'Cantal'
            ],
            [
                'isocode' => '16',
                'title' => 'Charente'
            ],
            [
                'isocode' => '17',
                'title' => 'Charente-Maritime'
            ],
            [
                'isocode' => '18',
                'title' => 'Cher'
            ],
            [
                'isocode' => '19',
                'title' => 'Corrèze'
            ],
            [
                'isocode' => '2A',
                'title' => 'Corse-du-Sud'
            ],
            [
                'isocode' => '2B',
                'title' => 'Haute-Corse'
            ],
            [
                'isocode' => '21',
                'title' => 'Côte-d\'Or'
            ],
            [
                'isocode' => '22',
                'title' => 'Côtes-d\'Armor'
            ],
            [
                'isocode' => '23',
                'title' => 'Creuse'
            ],
            [
                'isocode' => '24',
                'title' => 'Dordogne'
            ],
            [
                'isocode' => '25',
                'title' => 'Doubs'
            ],
            [
                'isocode' => '26',
                'title' => 'Drôme'
            ],
            [
                'isocode' => '27',
                'title' => 'Eure'
            ],
            [
                'isocode' => '28',
                'title' => 'Eure-et-Loir'
            ],
            [
                'isocode' => '29',
                'title' => 'Finistère'
            ],
            [
                'isocode' => '30',
                'title' => 'Gard'
            ],
            [
                'isocode' => '31',
                'title' => 'Haute-Garonne'
            ],
            [
                'isocode' => '32',
                'title' => 'Gers'
            ],
            [
                'isocode' => '33',
                'title' => 'Gironde'
            ],
            [
                'isocode' => '34',
                'title' => 'Hérault'
            ],
            [
                'isocode' => '35',
                'title' => 'Ille-et-Vilaine'
            ],
            [
                'isocode' => '36',
                'title' => 'Indre'
            ],
            [
                'isocode' => '37',
                'title' => 'Indre-et-Loire'
            ],
            [
                'isocode' => '38',
                'title' => 'Isère'
            ],
            [
                'isocode' => '39',
                'title' => 'Jura'
            ],
            [
                'isocode' => '40',
                'title' => 'Landes'
            ],
            [
                'isocode' => '41',
                'title' => 'Loir-et-Cher'
            ],
            [
                'isocode' => '42',
                'title' => 'Loire'
            ],
            [
                'isocode' => '43',
                'title' => 'Haute-Loire'
            ],
            [
                'isocode' => '44',
                'title' => 'Loire-Atlantique'
            ],
            [
                'isocode' => '45',
                'title' => 'Loiret'
            ],
            [
                'isocode' => '46',
                'title' => 'Lot'
            ],
            [
                'isocode' => '47',
                'title' => 'Lot-et-Garonne'
            ],
            [
                'isocode' => '48',
                'title' => 'Lozère'
            ],
            [
                'isocode' => '49',
                'title' => 'Maine-et-Loire'
            ],
            [
                'isocode' => '50',
                'title' => 'Manche'
            ],
            [
                'isocode' => '51',
                'title' => 'Marne'
            ],
            [
                'isocode' => '52',
                'title' => 'Haute-Marne'
            ],
            [
                'isocode' => '53',
                'title' => 'Mayenne'
            ],
            [
                'isocode' => '54',
                'title' => 'Meurthe-et-Moselle'
            ],
            [
                'isocode' => '55',
                'title' => 'Meuse'
            ],
            [
                'isocode' => '56',
                'title' => 'Morbihan'
            ],
            [
                'isocode' => '57',
                'title' => 'Moselle'
            ],
            [
                'isocode' => '58',
                'title' => 'Nièvre'
            ],
            [
                'isocode' => '59',
                'title' => 'Nord'
            ],
            [
                'isocode' => '60',
                'title' => 'Oise'
            ],
            [
                'isocode' => '61',
                'title' => 'Orne'
            ],
            [
                'isocode' => '62',
                'title' => 'Pas-de-Calais'
            ],
            [
                'isocode' => '63',
                'title' => 'Puy-de-Dôme'
            ],
            [
                'isocode' => '64',
                'title' => 'Pyrénées-Atlantiques'
            ],
            [
                'isocode' => '65',
                'title' => 'Hautes-Pyrénées'
            ],
            [
                'isocode' => '66',
                'title' => 'Pyrénées-Orientales'
            ],
            [
                'isocode' => '67',
                'title' => 'Bas-Rhin'
            ],
            [
                'isocode' => '68',
                'title' => 'Haut-Rhin'
            ],
            [
                'isocode' => '69',
                'title' => 'Rhône'
            ],
            [
                'isocode' => '70',
                'title' => 'Haute-Saône'
            ],
            [
                'isocode' => '71',
                'title' => 'Saône-et-Loire'
            ],
            [
                'isocode' => '72',
                'title' => 'Sarthe'
            ],
            [
                'isocode' => '73',
                'title' => 'Savoie'
            ],
            [
                'isocode' => '74',
                'title' => 'Haute-Savoie'
            ],
            [
                'isocode' => '75',
                'title' => 'Paris'
            ],
            [
                'isocode' => '76',
                'title' => 'Seine-Maritime'
            ],
            [
                'isocode' => '77',
                'title' => 'Seine-et-Marne'
            ],
            [
                'isocode' => '78',
                'title' => 'Yvelines'
            ],
            [
                'isocode' => '79',
                'title' => 'Deux-Sèvres'
            ],
            [
                'isocode' => '80',
                'title' => 'Somme'
            ],
            [
                'isocode' => '81',
                'title' => 'Tarn'
            ],
            [
                'isocode' => '82',
                'title' => 'Tarn-et-Garonne'
            ],
            [
                'isocode' => '83',
                'title' => 'Var'
            ],
            [
                'isocode' => '84',
                'title' => 'Vaucluse'
            ],
            [
                'isocode' => '85',
                'title' => 'Vendée'
            ],
            [
                'isocode' => '86',
                'title' => 'Vienne'
            ],
            [
                'isocode' => '87',
                'title' => 'Haute-Vienne'
            ],
            [
                'isocode' => '88',
                'title' => 'Vosges'
            ],
            [
                'isocode' => '89',
                'title' => 'Yonne'
            ],
            [
                'isocode' => '90',
                'title' => 'Territoire de Belfort'
            ],
            [
                'isocode' => '91',
                'title' => 'Essonne'
            ],
            [
                'isocode' => '92',
                'title' => 'Hauts-de-Seine'
            ],
            [
                'isocode' => '93',
                'title' => 'Seine-Saint-Denis'
            ],
            [
                'isocode' => '94',
                'title' => 'Val-de-Marne'
            ],
            [
                'isocode' => '95',
                'title' => 'Val-d\'Oise'
            ],
            [
                'isocode' => 'GP',
                'title' => 'Guadeloupe'
            ],
            [
                'isocode' => 'GF',
                'title' => 'Guyane'
            ],
            [
                'isocode' => 'MQ',
                'title' => 'Martinique'
            ],
            [
                'isocode' => 'RE',
                'title' => 'Réunion'
            ],
            [
                'isocode' => 'YT',
                'title' => 'Mayotte'
            ],
            [
                'isocode' => 'CP',
                'title' => 'Île de Clipperton'
            ],
            [
                'isocode' => 'BL',
                'title' => 'Saint-Barthélemy'
            ],
            [
                'isocode' => 'MF',
                'title' => 'Saint-Martin'
            ],
            [
                'isocode' => 'NC',
                'title' => 'Nouvelle-Calédonie'
            ],
            [
                'isocode' => 'PF',
                'title' => 'Polynésie française'
            ],
            [
                'isocode' => 'PM',
                'title' => 'Saint-Pierre-et-Miquelon'
            ],
            [
                'isocode' => 'TF',
                'title' => 'Terres australes et antarctiques françaises'
            ],
            [
                'isocode' => 'WF',
                'title' => 'Wallis-et-Futuna'
            ],
        ];
    }

    protected function migrateOldCountry(Country $oldCountry, State $newState, $newCountryId)
    {
        AddressQuery::create()
            ->filterByCountryId($oldCountry->getId())
            ->update(
                [
                    'CountryId' => $newCountryId,
                    'StateId' => $newState->getId()
                ]
            );

        TaxRuleCountryQuery::create()
            ->filterByCountryId($oldCountry->getId())
            ->update(
                [
                    'CountryId' => $newCountryId,
                    'StateId' => $newState->getId()
                ]
            );

        CountryAreaQuery::create()
            ->filterByCountryId($oldCountry->getId())
            ->update(
                [
                    'CountryId' => $newCountryId,
                    'StateId' => $newState->getId()
                ]
            );

        $oldCountry->setVisible(0)
            ->save();
    }

    protected function updateAddresses($frenchCountryId)
    {
        $frenchAddresses = AddressQuery::create()
            ->filterByCountryId($frenchCountryId)
            ->find();

        /** @var Address $frenchAddress */
        foreach ($frenchAddresses as $frenchAddress) {
            $stateIso = $this->getStateIsoByZipcode($frenchAddress->getZipcode());
            $state = StateQuery::create()->filterByCountryId($frenchCountryId)->findOneByIsocode($stateIso);
            if (null !== $state) {
                $frenchAddress->setStateId($state->getId());
                $frenchAddress->save();
            }
        }
    }

    protected function getStateIsoByZipcode($zipcode)
    {
        if (20000 <= $zipcode && $zipcode < 21000) {
            //Corse
            if ($zipcode < 20200) {
                return '2A';
            } else {
                return '2B';
            }
        } elseif (96000 < $zipcode) {
            //COM
            if (97501 == $zipcode || 97502 == $zipcode) {
                return 'PM';
            }

            if (97701 == $zipcode) {
                return 'BL';
            }

            if (97801 == $zipcode) {
                return 'MF';
            }

            if (98411 <= $zipcode && $zipcode <= 98415) {
                return 'TF';
            }

            if (98611 <= $zipcode && $zipcode <= 98413) {
                return 'WF';
            }

            if (98711 <= $zipcode && $zipcode <= 98758) {
                return 'PF';
            }

            if (98801 <= $zipcode && $zipcode <= 98833) {
                return 'NC';
            }

            if (98901 == $zipcode) {
                return 'CP';
            }

            //DOM - TOM
            switch (true) {
                case $zipcode < 97200:
                    return 'GP';
                    break;
                case $zipcode < 97300:
                    return 'MQ';
                    break;
                case $zipcode < 97400:
                    return 'GF';
                    break;
                case $zipcode < 97500:
                    return 'RE';
                    break;
                case $zipcode < 97700:
                    return 'YT';
                    break;
            }
        } else {
            return substr($zipcode, 0, 2);
        }
    }
}
