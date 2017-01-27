<?php
/**
 * \Elabftw\Elabftw\UserStats
 *
 * @author Nicolas CARPi <nicolas.carpi@curie.fr>
 * @copyright 2012 Nicolas CARPi
 * @see https://www.elabftw.net Official website
 * @license AGPL-3.0
 * @package elabftw
 */
namespace Elabftw\Elabftw;

/**
 * Generate and display experiments statistics for a user
 */
class UserStats
{

    /** our team */
    private $team;

    /** id of our user */
    private $userid;

    /** count of experiments */
    private $count = 0;

    /** pdo object */
    private $pdo;

    /** array with status id and count */
    private $countArr = array();

    /** array with status id and name */
    private $statusArr = array();

    /** array with colors for status */
    public $colorsArr = array();

    /** array with percentage and status name */
    public $percentArr = array();

    /**
     * Init the object with a userid and the total count of experiments
     *
     * @param int $team
     * @param int $userid
     * @param int $count total count of experiments
     */
    public function __construct($team, $userid, $count)
    {
        $this->team = $team;
        $this->userid = $userid;
        $this->count = $count;
        $this->pdo = Db::getConnection();
        $this->countStatus();
        $this->makePercent();
    }

    /**
     * Count number of experiments for each status
     *
     */
    private function countStatus()
    {
        // get all status name and id
        $Status = new Status($this->team);
        $statusAll = $Status->readAll();

        // populate arrays
        foreach ($statusAll as $status) {
            $this->statusArr[$status['status_id']] = $status['status'];
            $this->colorsArr[] = $status['color'];
        }

        // count experiments for each status
        foreach ($this->statusArr as $key => $value) {
            $sql = "SELECT COUNT(*)
                FROM experiments
                WHERE userid = :userid
                AND status = :status";
            $req = $this->pdo->prepare($sql);
            $req->bindParam(':userid', $this->userid);
            $req->bindParam(':status', $key);
            $req->execute();
            $this->countArr[$key] = $req->fetchColumn();
        }
    }

    /**
     * Create an array with status name => percent
     *
     */
    private function makePercent()
    {
        foreach ($this->statusArr as $key => $value) {
            $value = str_replace("'", "\'", html_entity_decode($value, ENT_QUOTES));
            $this->percentArr[$value] = round(($this->countArr[$key] / $this->count) * 100);
        }
    }
}
