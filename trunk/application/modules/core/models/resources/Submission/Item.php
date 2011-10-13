<?php
/**
 * CORE Conference Manager
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://www.terena.org/license/new-bsd
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to webmaster@terena.org so we can send you a copy immediately.
 *
 * @copyright  Copyright (c) 2011 TERENA (http://www.terena.org)
 * @license    http://www.terena.org/license/new-bsd     New BSD License
 * @revision   $Id$
 */

/**
 * Submission row
 *
 * @package Core_Resource
 * @subpackage Core_Resource_Submission
 * @author Christian Gijtenbeek <gijtenbeek@terena.org>
 */
class Core_Resource_Submission_Item extends TA_Model_Resource_Db_Table_Row_Abstract
{

	/**
	 * Proxy method to $this::getReviewers(), required for TA_Form_Element_User
	 *
	 */
	public function getUsers()
	{
		return $this->getReviewers();
	}

	/**
	 * Get reviewers belonging to this submission
	 *
	 * @return array
	 */
	public function getReviewers()
	{
		$query = "select u.email, u.organisation, rs.reviewer_submission_id as id from reviewers_submissions rs
		left join users u on (rs.user_id = u.user_id)
		where rs.submission_id=:submission_id";

		return $this->getTable()->getAdapter()->query(
			$query, array(':submission_id' => $this->submission_id)
		)->fetchAll();
	}

	public function getSubmissionOneliner()
	{
		return $this->title;
	}

	/**
	 * Is this current time before the edit deadline?
	 *
	 * @return boolean
	 */
	public function isBeforeEditDeadline()
	{
		$conference = Zend_Registry::get('conference');

		if (!isset($conference['review_start'])) {
			return true;
		}

		$now = new Zend_Date();

		if ( $now->isEarlier($conference['review_start']) ) {
		   return true;
		}

		return false;
	}
}