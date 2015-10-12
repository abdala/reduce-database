<?php

namespace Reduce\Db\Query;

use Doctrine\DBAL\Query\QueryBuilder as DBALQueryBuilder;

class QueryBuilder extends DBALQueryBuilder
{
    public function where($predicate, $value = null)
    {
        if ($value) {
            $this->andWhere($this->normalizePredicate($predicate));
            $this->createPositionalParameter($value);
            return $this;
        }
        
        if (is_array($predicate)) {
            foreach ($predicate as $key => $value) {
                $this->where($this->normalizePredicate($key), $value);
            }

            return $this;
        }

        $this->andWhere($predicate);
        
        return $this;
    }
    
    protected function normalizePredicate($predicate)
    {
        if (strpos($predicate, '?') === false) {
            $predicate .= ' = ?';
        }
        
        return $predicate;
    }
}
