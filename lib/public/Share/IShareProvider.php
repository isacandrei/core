<?php
/**
 * @author Roeland Jago Douma <rullzer@owncloud.com>
 *
 * @copyright Copyright (c) 2017, ownCloud GmbH
 * @license AGPL-3.0
 *
 * This code is free software: you can redistribute it and/or modify
 * it under the terms of the GNU Affero General Public License, version 3,
 * as published by the Free Software Foundation.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU Affero General Public License for more details.
 *
 * You should have received a copy of the GNU Affero General Public License, version 3,
 * along with this program.  If not, see <http://www.gnu.org/licenses/>
 *
 */

namespace OCP\Share;

use OCP\Share\Exceptions\ShareNotFound;
use OCP\Files\Node;

/**
 * Interface IShareProvider
 *
 * @package OCP\Share
 * @since 9.0.0
 */
interface IShareProvider {

	/**
	 * Return the identifier of this provider.
	 *
	 * @return string Containing only [a-zA-Z0-9]
	 * @since 9.0.0
	 */
	public function identifier();

	/**
	 * Create a share
	 * 
	 * @param \OCP\Share\IShare $share
	 * @return \OCP\Share\IShare The share object
	 * @since 9.0.0
	 */
	public function create(\OCP\Share\IShare $share);

	/**
	 * Update a share
	 *
	 * @param \OCP\Share\IShare $share
	 * @return \OCP\Share\IShare The share object
	 * @since 9.0.0
	 */
	public function update(\OCP\Share\IShare $share);

	/**
	 * Delete a share
	 *
	 * @param \OCP\Share\IShare $share
	 * @since 9.0.0
	 */
	public function delete(\OCP\Share\IShare $share);

	/**
	 * Unshare a file from self as recipient.
	 * This may require special handling. If a user unshares a group
	 * share from their self then the original group share should still exist.
	 *
	 * @param \OCP\Share\IShare $share
	 * @param string $recipient UserId of the recipient
	 * @since 9.0.0
	 */
	public function deleteFromSelf(\OCP\Share\IShare $share, $recipient);

	/**
	 * Move a share as a recipient.
	 * This is updating the share target. Thus the mount point of the recipient.
	 * This may require special handling. If a user moves a group share
	 * the target should only be changed for them.
	 *
	 * @param \OCP\Share\IShare $share
	 * @param string $recipient userId of recipient
	 * @return \OCP\Share\IShare
	 * @since 9.0.0
	 */
	public function move(\OCP\Share\IShare $share, $recipient);

	/**
	 * Get all shares by the given user
	 *
	 * @param string $userId
	 * @param int $shareType
	 * @param Node|null $node
	 * @param bool $reshares Also get the shares where $user is the owner instead of just the shares where $user is the initiator
	 * @param int $limit The maximum number of shares to be returned, -1 for all shares
	 * @param int $offset
	 * @return \OCP\Share\IShare[]
	 * @since 9.0.0
	 */
	public function getSharesBy($userId, $shareType, $node, $reshares, $limit, $offset);

	/**
	 * Get all shares by the given user for specified shareTypes array
	 *
	 * @param string $userId
	 * @param int[] $shareTypes
	 * @param Node[] $nodeIDs
	 * @param bool $reshares - Also get the shares where $user is the owner instead of just the shares where $user is the initiator
	 * @return \OCP\Share\IShare[]
	 * @since 10.0.0
	 */
	public function getAllSharesBy($userId, $shareTypes, $nodeIDs, $reshares);
	
	/**
	 * Get share by id
	 *
	 * @param int $id
	 * @param string|null $recipientId
	 * @return \OCP\Share\IShare
	 * @throws ShareNotFound
	 * @since 9.0.0
	 */
	public function getShareById($id, $recipientId = null);

	/**
	 * Get shares for a given path
	 *
	 * @param Node $path
	 * @return \OCP\Share\IShare[]
	 * @since 9.0.0
	 */
	public function getSharesByPath(Node $path);

	/**
	 * Get shared with the given user
	 *
	 * @param string $userId get shares where this user is the recipient
	 * @param int $shareType
	 * @param Node|null $node
	 * @param int $limit The max number of entries returned, -1 for all
	 * @param int $offset
	 * @return \OCP\Share\IShare[]
	 * @since 9.0.0
	 */
	public function getSharedWith($userId, $shareType, $node, $limit, $offset);

	/**
	 * Get a share by token
	 *
	 * @param string $token
	 * @return \OCP\Share\IShare
	 * @throws ShareNotFound
	 * @since 9.0.0
	 */
	public function getShareByToken($token);

	/**
	 * A user is deleted from the system
	 * So clean up the relevant shares.
	 *
	 * @param string $uid
	 * @param int $shareType
	 * @since 9.1.0
	 */
	public function userDeleted($uid, $shareType);

	/**
	 * A group is deleted from the system.
	 * We have to clean up all shares to this group.
	 * Providers not handling group shares should just return
	 *
	 * @param string $gid
	 * @since 9.1.0
	 */
	public function groupDeleted($gid);

	/**
	 * A user is deleted from a group
	 * We have to clean up all the related user specific group shares
	 * Providers not handling group shares should just return
	 *
	 * @param string $uid
	 * @param string $gid
	 * @since 9.1.0
	 */
	public function userDeletedFromGroup($uid, $gid);
}
