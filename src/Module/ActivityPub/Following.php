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

namespace Friendica\Module\ActivityPub;

use Friendica\BaseModule;
use Friendica\Core\System;
use Friendica\Model\Contact;
use Friendica\Model\User;
use Friendica\Protocol\ActivityPub;

/**
 * ActivityPub Following
 */
class Following extends BaseModule
{
	protected function rawContent(array $request = [])
	{
		if (empty($this->parameters['nickname'])) {
			throw new \Friendica\Network\HTTPException\NotFoundException();
		}

		$owner = User::getOwnerDataByNick($this->parameters['nickname']);
		if (empty($owner)) {
			throw new \Friendica\Network\HTTPException\NotFoundException();
		}

		$page = !empty($request['page']) ? (int)$request['page'] : null;

		$following = ActivityPub\Transmitter::getContacts($owner, [Contact::SHARING, Contact::FRIEND], 'following', $page);

		$this->jsonExit($following, 'application/activity+json');
	}
}
