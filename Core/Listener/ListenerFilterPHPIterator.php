<?php
/**
 * PHP File Filter for usage with a DirectoryIterator
 * @category   Listener
 * @package    Listener
 * @subpackage Filter
 * @author     Mario Mueller <mario.mueller.mac@me.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    SVN: $Id:$
 * @link       http://www.azimmermann.com/
 * @since      File available since Release 1.0.0
 */

/**
 * Interface für Object Listener.
 * @category   Listener
 * @package    Listener
 * @subpackage Filter
 * @author     Mario Mueller <mario.mueller.mac@me.com>
 * @copyright  2008-2010 Alexander Zimmermann <alex@azimmermann.com>
 * @license    http://www.opensource.org/licenses/bsd-license.php  BSD License
 * @version    Release: 1.0.0
 * @link       http://www.azimmermann.com/
 * @since      Class available since Release 1.0.0
 */
class ListenerFilterPHPIterator extends FilterIterator
{
    
    /**
     * Just accept files that ends with \.php[3-5]?
     * 
     * @param SplFileObject $oFile The filename to inspect.
     */
    public function accept(SplFileObject $oFile) 
    {
        return (boolean) preg_match('#\.php[3-5]?$#i', $oFile->getFilename());
    } // function
} // class
