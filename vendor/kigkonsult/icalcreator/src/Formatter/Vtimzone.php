<?php
/**
 * iCalcreator, the PHP class package managing iCal (rfc2445/rfc5445) calendar information.
 *
 * This file is a part of iCalcreator.
 *
 * @author    Kjell-Inge Gustafsson, kigkonsult <ical@kigkonsult.se>
 * @copyright 2007-2022 Kjell-Inge Gustafsson, kigkonsult, All rights reserved
 * @link      https://kigkonsult.se
 * @license   Subject matter of licence is the software iCalcreator.
 *            The above copyright, link, package and version notices,
 *            this licence notice and the invariant [rfc5545] PRODID result use
 *            as implemented and invoked in iCalcreator shall be included in
 *            all copies or substantial portions of the iCalcreator.
 *
 *            iCalcreator is free software: you can redistribute it and/or modify
 *            it under the terms of the GNU Lesser General Public License as
 *            published by the Free Software Foundation, either version 3 of
 *            the License, or (at your option) any later version.
 *
 *            iCalcreator is distributed in the hope that it will be useful,
 *            but WITHOUT ANY WARRANTY; without even the implied warranty of
 *            MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 *            GNU Lesser General Public License for more details.
 *
 *            You should have received a copy of the GNU Lesser General Public License
 *            along with iCalcreator. If not, see <https://www.gnu.org/licenses/>.
 */
declare( strict_types = 1 );
namespace Kigkonsult\Icalcreator\Formatter;

use Exception;
use Kigkonsult\Icalcreator\CalendarComponent;
use Kigkonsult\Icalcreator\Vtimezone as Source;

use function strtoupper;
use function sprintf;

/**
 * @since 2.41.55 - 2022-08-13
 */
final class Vtimzone extends FormatBase
{
    /**
     * @param CalendarComponent|Source $source
     * @return string
     * @throws Exception
     */
    public static function format( CalendarComponent|Source $source ) : string
    {
        $compType   = strtoupper( $source->getCompType());
        $allowEmpty = $source->getConfig( self::ALLOWEMPTY );
        $lang       = $source->getConfig( self::LANGUAGE );
        $output     =
            sprintf( self::$FMTBEGIN, $compType ) .
            Property\SingleProps::format(
                self::TZID,
                $source->getTzid( true ),
                $allowEmpty
            ) .
            Property\MultiProps::format(
                self::TZID_ALIAS_OF,
                $source->getAllTzidaliasof( true ),
                $allowEmpty
            ) .
            Property\DtxProperty::format(
                self::LAST_MODIFIED,
                $source->getLastmodified( true ),
                $allowEmpty
            ) .
            Property\Property::format(
                self::TZURL,
                $source->getTzurl( true ),
                $allowEmpty
            ) .
            Property\Dt1Property::format(
                self::TZUNTIL,
                $source->getTzuntil( true ),
                $allowEmpty,
                false,
                true
            ) .
            Property\Xproperty::format(
                $source->getAllXprop( true ),
                $allowEmpty,
                $lang
            );
        $source->sortVtimezonesSubComponents();
        return $output .
            self::formatSubComponents( $source ) .
            sprintf( self::$FMTEND, $compType );
    }
}
