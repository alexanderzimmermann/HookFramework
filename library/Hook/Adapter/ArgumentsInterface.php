<?php
/**
 * Arguments interface.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    PHP 5.4
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

namespace Hook\Adapter;

/**
 * Arguments interface.
 * @category   Category
 * @package    Package
 * @subpackage Subpackage
 * @author     Alexander Zimmermann <alex@azimmermann.com>
 * @copyright  2008-2013 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 0.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
interface ArgumentsInterface
{
    /**
     * Arguments Ok.
     * @return boolean
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function argumentsOk();

    /**
     * Return complete hook type.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMainHook();

    /**
     * Return only main type string.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getMainType();

    /**
     * Return subtype.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getSubType();

    /**
     * Return repository path.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRepository();

    /**
     * Return the repository name.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRepositoryName();

    /**
     * Return user.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getUser();

    /**
     * Return Transaction number.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getTransaction();

    /**
     * Return version number.
     * @return integer
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getRevision();

    /**
     * Return the file on lock call.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getFile();

    /**
     * Return property name that is required on a Revprop-Change hook call.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getPropertyName();

    /**
     * Returns action name.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getAction();

    /**
     * Returns all available sub actions from the main action.
     * @return array
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getSubActions();

    /**
     * Returns error text.
     * @return string
     * @author Alexander Zimmermann <alex@azimmermann.com>
     */
    public function getError();
}