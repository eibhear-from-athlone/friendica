<?php
/**
 * @copyright Copyright (C) 2010-2024, the Friendica project
 *
 * @license GNU AGPL version 3 or any later version
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License as
 * published by the Free Software Foundation, either version 3 of the
 * License, or (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 */

namespace Friendica\Module\Ping;

use Friendica\Core\System;
use Friendica\Module\Conversation\Network as NetworkModule;

class Network extends NetworkModule
{
	protected function rawContent(array $request = [])
	{
		if (!$this->session->getLocalUserId()) {
			System::exit();
		}

		if (!empty($request['ping'])) {
			$request = $this->getTimelineRequestBySession();
		}

		if (!isset($request['p']) || !isset($request['item'])) {
			System::exit();
		}

		$this->parseRequest($request);

		if ($this->force || !is_null($this->maxId)) {
			System::httpExit('');
		}

		$this->itemsPerPage = 100;

		if ($this->channel->isTimeline($this->selectedTab) || $this->userDefinedChannel->isTimeline($this->selectedTab, $this->session->getLocalUserId())) {
			$items = $this->getChannelItems($request);
		} elseif ($this->community->isTimeline($this->selectedTab)) {
			$items = $this->getCommunityItems();
		} else {
			$items = $this->getItems();
		}
		$count = count($items);
		System::httpExit(($count < 100) ? $count : '99+');
	}
}
