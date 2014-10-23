<?php
namespace HMinng\Validator\Verifier;

use HMinng\DBLibrary\AtsQuery\AtsQueryWhere;
use HMinng\DBLibrary\AtsDAO\AtsDao;

class DatabasePresenceVerifier implements PresenceVerifierInterface {

	/**
	 * The database query.
	 *
	 * @var  \HMinng\DBLibrary\AtsQuery\AtsQueryWhere
	 */
	protected $query;

    /**
	 * The database connection to use.
	 *
	 * @var string
	 */
	protected $connection;

    /**
     * Create a new database presence verifier.
     * @param string $connection
     *
     * @return \HMinng\Validator\Verifier\DatabasePresenceVerifier
     */
	public function __construct($connection = NULL)
	{
        $this->query = AtsQueryWhere::getInstance();
	}

	/**
	 * Count the number of objects in a collection having the given value.
	 *
	 * @param  string  $collection
	 * @param  string  $column
	 * @param  string  $value
	 * @param  int     $excludeId
	 * @param  string  $idColumn
	 * @param  array   $extra
	 * @return int
	 */
	public function getCount($collection, $column, $value, $excludeId = null, $idColumn = null, array $extra = array())
	{
        $this->query->where("$column = ?", $value);

		if ( ! is_null($excludeId) && $excludeId != 'NULL') {
            $idColumn = $idColumn ? $idColumn : 'id';
            $this->query->addWhere("$idColumn <> ?", $excludeId);
		}

		foreach ($extra as $key => $extraValue) {
			$this->addWhere($key, $extraValue);
		}

        $where = $this->query->end();

		return $this->count($collection, $where);
	}

	/**
	 * Count the number of objects in a collection with the given values.
	 *
	 * @param  string  $collection
	 * @param  string  $column
	 * @param  array   $values
	 * @param  array   $extra
	 * @return int
	 */
	public function getMultiCount($collection, $column, array $values, array $extra = array())
	{
        $this->query->whereIn($column, $values);

		foreach ($extra as $key => $extraValue) {
			$this->addWhere($key, $extraValue);
		}

        $where = $this->query->end();

		return $this->count($collection, $where);
	}

	/**
	 * Add a "where" clause to the given query.
	 *
	 * @param  string  $key
	 * @param  string  $extraValue
	 * @return void
	 */
	protected function addWhere($key, $extraValue)
	{
		if ($extraValue === 'NULL') {
			$this->query->andWhere("$key is ?", NULL);
		} elseif ($extraValue === 'NOT_NULL') {
			$this->query->andWhere("$key is not ?", NULL);
		} else {
		    $this->query->andWhere("$key = ?", $extraValue);
		}
	}

    protected function count($table, $where)
    {
       $db = new AtsDao($table, $this->connection);
       return $db->count($where);
    }

    /**
     * Set the connection to be used.
     *
     * @param  string  $connection
     * @return void
     */
    public function setConnection($connection)
    {
        $this->connection = $connection;
    }
}
