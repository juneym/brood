<?php
/**
 * Brood
 *
 * @category   Brood
 * @package    Brood_Config
 * @copyright  Copyright (c) 2011 IGN Entertainment, Inc. (http://corp.ign.com/)
 * @license    http://www.opensource.org/licenses/mit-license.php     MIT License
 */

namespace Brood\Config;

/**
 * Class representing an action element
 *
 * @category   Brood
 * @package    Brood_Config
 * @copyright  Copyright (c) 2011 IGN Entertainment, Inc. (http://corp.ign.com/)
 * @license    http://www.opensource.org/licenses/mit-license.php     MIT License
 */
class Action
{
    protected $xml;

    public function __construct($xml)
    {
        $this->xml = $xml;
    }

    public function getClass()
    {
        if (isset($this->xml['class'])) {
            return (string) $this->xml['class'];
        }
    }

    public function getFile()
    {
        if (isset($this->xml['file'])) {
            return (string) $this->xml['file'];
        }
    }

    public function getAttributes()
    {
        return $this->xml->attributes();
    }

    public function getOverlord()
    {
        return (bool) $this->xml->overlord;
    }

    public function getHostGroups()
    {
        $hostGroups = array();
        foreach ($this->xml->hostgroup as $hostGroup) {
            $hostGroups[(string) $hostGroup] = $hostGroup->attributes();
        }
        return $hostGroups;
    }

    public function getHosts()
    {
        $hosts = array();
        foreach ($this->xml->host as $host) {
            $hosts[(string) $host] = $host->attributes();
        }
        return $hosts;
    }

    public function getParameter($param)
    {
        if (isset($this->xml->parameters[0])) {
            return $this->xml->parameters[0]->$param;
        }
    }
}
