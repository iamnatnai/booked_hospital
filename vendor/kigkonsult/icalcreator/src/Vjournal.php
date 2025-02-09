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
namespace Kigkonsult\Icalcreator;

use DateTimeInterface;
use Exception;
use InvalidArgumentException;
use Kigkonsult\Icalcreator\Formatter\Vjournal as Formatter;


/**
 * iCalcreator VJOURNAL component class
 *
 * @since  2.41.55 - 2022-08-13
 */
final class Vjournal extends V2component
{
    use Traits\ATTACHtrait;
    use Traits\ATTENDEEtrait;
    use Traits\Participants2AttendeesTrait;
    use Traits\SubCompsGetTrait;
    use Traits\CATEGORIEStrait;
    use Traits\CLASStrait;
    use Traits\COLORrfc7986trait;
    use Traits\COMMENTtrait;
    use Traits\CONTACTtrait;
    use Traits\CREATEDtrait;
    use Traits\DESCRIPTIONtrait;
    use Traits\DTSTARTtrait;
    use Traits\EXDATEtrait;
    use Traits\EXRULEtrait;
    use Traits\IMAGErfc7986trait;
    use Traits\LAST_MODIFIEDtrait;
    use Traits\ORGANIZERtrait;
    use Traits\RDATEtrait;
    use Traits\RECURRENCE_IDtrait;
    use Traits\RELATED_TOtrait;
    use Traits\REQUEST_STATUStrait;
    use Traits\RRULEtrait;
    use Traits\SEQUENCEtrait;
    use Traits\STATUStrait;
    use Traits\STRUCTURED_DATArfc9073trait;
    use Traits\STYLED_DESCRIPTIONrfc9073trait;
    use Traits\SUMMARYtrait;
    use Traits\UIDrfc7986trait;
    use Traits\URLtrait;

    /**
     * @var string
     */
    protected static string $compSgn = 'j';

    /**
     * Constructor
     *
     * @param null|mixed[] $config
     * @throws Exception
     * @since  2.41.53 - 2022-08-11
     */
    public function __construct( ? array $config = [] )
    {
        parent::__construct( $config );
        $this->setDtstamp();
        $this->setUid();
    }

    /**
     * Return Vjournal object instance
     *
     * @param null|array $config
     * @param null|string|DateTimeInterface $dtstart
     * @param null|string $summary
     * @return Vjournal
     * @throws InvalidArgumentException
     * @throws Exception
     * @since  2.41.53 - 2022-08-08
     */
    public static function factory(
        ? array $config = [],
        null|string|DateTimeInterface $dtstart = null,
        ? string $summary = null
    ) : Vjournal
    {
        $instance = new Vjournal( $config );
        if( null !== $dtstart ) {
            $instance->setDtstart( $dtstart );
        }
        if( null !== $summary ) {
            $instance->setSummary( $summary );
        }
        return $instance;
    }

    /**
     * Destructor
     *
     * @since 2.41.3 2022-01-17
     */
    public function __destruct()
    {
        unset(
            $this->compType,
            $this->xprop,
            $this->components,
            $this->config,
            $this->compix,
            $this->propIx,
            $this->propDelIx
        );
        unset(
            $this->cno,
            $this->srtk
        );
        unset(
            $this->attach,
            $this->attendee,
            $this->categories,
            $this->class,
            $this->color,
            $this->comment,
            $this->contact,
            $this->created,
            $this->description,
            $this->dtstamp,
            $this->dtstart,
            $this->exdate,
            $this->exrule,
            $this->image,
            $this->lastmodified,
            $this->organizer,
            $this->rdate,
            $this->recurrenceid,
            $this->relatedto,
            $this->requeststatus,
            $this->rrule,
            $this->sequence,
            $this->status,
            $this->structureddata,
            $this->styleddescription,
            $this->summary,
            $this->uid,
            $this->url
        );
    }

    /**
     * Return formatted output for calendar component VJOURNAL object instance
     *
     * @return string
     * @throws Exception  (on Rdate err)
     * @since  2.41.55 - 2022-08-13
     */
    public function createComponent() : string
    {
        return Formatter::format( $this );
    }
}
