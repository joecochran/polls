<?php
/**
 * @copyright Copyright (c) 2017 Vinzenz Rosenkranz <vinzenz.rosenkranz@gmail.com>
 *
 * @author René Gieling <github@dartcafe.de>
 *
 * @license GNU AGPL version 3 or any later version
 *
 *  This program is free software: you can redistribute it and/or modify
 *  it under the terms of the GNU Affero General Public License as
 *  published by the Free Software Foundation, either version 3 of the
 *  License, or (at your option) any later version.
 *
 *  This program is distributed in the hope that it will be useful,
 *  but WITHOUT ANY WARRANTY; without even the implied warranty of
 *  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 *  GNU Affero General Public License for more details.
 *
 *  You should have received a copy of the GNU Affero General Public License
 *  along with this program.  If not, see <http://www.gnu.org/licenses/>.
 *
 */

namespace OCA\Polls\Model;

use \OCP\Calendar\ICalendar;

class CalendarEvent implements \JsonSerializable {

	/** @var Array */
	protected $calDav;

	/** @var Array */
	protected $event;

	/** @var ICalendar */
	protected $calendar;

	public function __construct(
		array $calDav,
		ICalendar $calendar
	) {
		$this->calDav = $calDav;
		$this->calendar = $calendar;
		$this->event = $this->calDav['objects'][0];
	}

	public function getAllDay() {
		return ($this->getEnd() - $this->getStart() === 86400) ? $this->event['DTSTART'][0]->format('Y-m-d') : '';
	}

	public function getCalendarName(): ?string {
		return $this->calendar->getDisplayName();
	}

	public function getCalendarKey(): string {
		return $this->calendar->getKey();
	}

	public function getDisplayColor(): ?string {
		return $this->calendar->getDisplayColor();
	}

	public function getId() {
		return $this->calDav['id'];
	}

	public function getUID() {
		return $this->event['UID'][0];
	}

	public function getSummary() {
		return $this->event['SUMMARY'][0];
	}

	public function getDescription() {
		return $this->event['DESCRIPTION'][0] ?? '';
	}

	public function getLocation() {
		return $this->event['LOCATION'][0] ?? '';
	}

	public function getStart() {
		return isset($this->event['DTSTART'][0]) ? $this->event['DTSTART'][0]->getTimestamp() : 0;
	}

	public function getEnd() {
		return isset($this->event['DTEND'][0])? $this->event['DTEND'][0]->getTimestamp() : 0;
	}

	public function getStatus() {
		return $this->event['STATUS'][0] ?? '';
	}

	public function getCalDav(): array {
		return $this->calDav;
	}

	public function jsonSerialize(): array {
		return	[
			'id' => $this->getId(),
			'UID' => $this->getUID(),
			'calendarKey' => $this->getCalendarKey(),
			'calendarName' => $this->getCalendarName(),
			'displayColor' => $this->getDisplayColor(),
			'allDay' => $this->getAllDay(),
			'description' => $this->getDescription(),
			'end' => $this->getEnd(),
			'location' => $this->getLocation(),
			'start' => $this->getStart(),
			'status' => $this->getStatus(),
			'summary' => $this->getSummary(),
			'calDav' => $this->getCalDav(),
		];
	}
}
